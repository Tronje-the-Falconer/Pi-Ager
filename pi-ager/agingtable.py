#!/usr/bin/python3

######################################################### Importieren der Module
import sys
import os
import glob
import time
import datetime
import csv
import gettext
from datetime import timedelta
import pi_ager_debug
import pi_ager_database
import pi_ager_organization
import pi_ager_init
import pi_ager_paths
import pi_ager_names

######################################################### Definieren von Funktionen
# #---------------------------------------------------------------------------------- Funktion zur uebersetzung von z.B. Listenobjekten z.B. animals = [N_('mollusk'), N_('albatross'), N_('rat')]
# def N_(message):
    # return message
#---------------------------------------------------------------------------------- Funktion zum Lesen des Dictionarys und setzen der Werte
def read_dictionary(dictionary):
    global period_endtime

    if pi_ager_debug.debugging == 'on':
        print ('DEBUG read_dictionary()')
    # Variablen aus Dictionary setzen
    for key, value in iter(dictionary.items()):
        if value == None or value == '':                      # wenn ein Wert leer ist muss er aus der letzten settings.json ausgelesen  werden
            value = pi_ager_database.get_table_value(pi_ager_names.config_settings_table,key)
            exec('{} = {}'.format(key,value), exec_scope)   # fuellt die jeweilige Variable mit altem Wert (value = columname)
        else:
            value = int(value)
            exec('{} = {}'.format(key,value), exec_scope)
    duration = int (exec_scope['days'])
    global duration_sleep
    duration_sleep = int(duration) * day_in_seconds    # Anzahl der Tage von "column" mit 86400 (Sekunden) multipliziert fuer wartezeit bis zur naechsten Periode
#---------------------------------------------------------------------------------- Aufbereitung fuer die Lesbarkeit im Logfile und Fuellen der Variablen
    modus = int(exec_scope['modus'] + 0.5)                # Rundet auf Ganzzahl, Integer da der Modus immer Integer sein sollte 
    if modus == 0:
        operating_mode = "\n" + _('operation mode') + ': ' + _('cooling')
    elif modus == 1:
        operating_mode = "\n" + _('operation mode') + ': ' + _('cooling with humidify')
    elif modus == 2:
        operating_mode = "\n" + _('operation mode') + ': ' + _('heating with humidify')
    elif modus == 3:
        operating_mode = "\n" + _('operation mode') + ': ' + _('automatic with humidify')
    elif modus == 4:
        operating_mode = "\n" + _('operation mode') + ': ' + _('automatic with dehumidify and humidify')
    else:
        operating_mode = "\n" + _('operation mode wrong or set incorrectly')
    setpoint_temperature_logstring = "\n" + _('setpoint temperature') + ": \t \t" + str(exec_scope['setpoint_temperature']) + " C"
    switch_on_cooling_compressor_logstring = "\n" + _('switch-on value temperature') + ": \t" + str(switch_on_cooling_compressor) + " C"
    switch_off_cooling_compressor_logstring = "\n" + _('switch-off value temperature') + ": \t" + str(switch_off_cooling_compressor) + " C"
    sollfeuchtigkeit_logstring = "\n" + _('setpoint humidity') + ": \t \t" + str(exec_scope['setpoint_humidity']) + "%"
    switch_on_humidifier_logstring = "\n" + _('switch-on value humidity') + ": \t \t" + str(switch_on_humidifier) + "%"
    switch_off_humidifier_logstring = "\n" + _('switch-off value humidity') + ": \t \t" + str(switch_off_humidifier) + "%"
    delay_humidify_logstring = "\n" + _('humidification delay') + ": \t" + str(delay_humidify) + ' ' + _("minutes")
    circulation_air_period_format = int(exec_scope['circulation_air_period'])/60
    circulation_air_period_logstring = "\n" + _('timer circulation air period every') + ": \t" + str(circulation_air_period_format) + ' ' + _("minutes")
    circulation_air_duration_format = int(exec_scope['circulation_air_duration'])/60
    circulation_air_duration_logstring = "\n" + _('timer circulation air') + ": \t  \t" + str(circulation_air_duration_format) + ' ' + _("minutes")
    exhaust_air_period_format = int(exec_scope['exhaust_air_period'])/60
    exhaust_air_period_logstring = "\n" + _('timer exhaust air period every') + ": \t" + str(exhaust_air_period_format) + ' ' + _("minutes")
    exhaust_air_duration_format = int(exec_scope['exhaust_air_duration'])/60
    exhaust_air_duration_logstring = "\n" + _('timer exhausting air') + ": \t \t" + str(exhaust_air_duration_format) + ' ' + _("minutes")
    period_days_logstring="\n" + _('duration') + ": \t \t \t \t" + str(exec_scope['days']) + ' ' + _('days')
    sensor_logstring = _('sensortype') + ": \t \t \t" + sensorname + ' ' + _('value') + ': ' + str(sensortype)
    
    
    if pi_ager_debug.debugging == 'on':
        print ('DEBUG schreibe settings.json in if')
    pi_ager_database.write_settings(modus, exec_scope['setpoint_temperature'], exec_scope['setpoint_humidity'], exec_scope['circulation_air_period'], exec_scope['circulation_air_duration'], exec_scope['exhaust_air_period'], exec_scope['exhaust_air_duration'])
    period_endtime = datetime.datetime.now() + timedelta(days = duration) # days = parameter von timedelta
    logstring = operating_mode + setpoint_temperature_logstring + switch_on_cooling_compressor_logstring + switch_off_cooling_compressor_logstring + "\n" + sollfeuchtigkeit_logstring + switch_on_humidifier_logstring + switch_off_humidifier_logstring + delay_humidify_logstring + "\n" + circulation_air_period_logstring + circulation_air_duration_logstring + "\n" + exhaust_air_period_logstring + exhaust_air_duration_logstring + "\n" + period_days_logstring + "\n" + sensor_logstring + "\n" '---------------------------------------' + "\n"
    pi_ager_organization.write_verbose(logstring, False, True)
    
######################################################### Definition von Variablen
global exec_scope
global period_endtime
global duration_sleep
global current_json_file
global settings_json_file
global tables_json_file
global settings_json_file
global config_json_file
exec_scope = {}
#---------------------------------------------------------------------------------- Pfade zu den Dateien
website_path = pi_ager_paths.get_website_path()
csv_path = pi_ager_paths.get_csv_path()
logfile_txt_file = pi_ager_paths.get_path_logfile_txt_file()
#---------------------------------------------------------------------------------- Allgemeingueltige Werte aus config.json
sensortype = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.sensortype_key)
language = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.language_key)                                            # Sprache der Textausgabe
switch_on_cooling_compressor = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_cooling_compressor_key)
switch_off_cooling_compressor = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_off_cooling_compressor_key)
switch_on_humidifier = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_humidifier_key)
switch_off_humidifier = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_off_humidifier_key)
delay_humidify = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.delay_humidify_key)
#---------------------------------------------------------------------------------- Tabelle aus tables.json
agingtable = pi_ager_database.read_agingtable_name_from_config()    # Variable reifetablename = Name der Reifetabelle

#---------------------------------------------------------------------------------- bedingte Werte aus Variablen
#---------------------------------------------------------------------------------------------------------------- csv-datei
csv_file = agingtable + '.csv'                       # Variable csv_file = kompletter Dateiname
#---------------------------------------------------------------------------------------------------------------- Sensor
if sensortype == 1 :
    sensortype_txt = '1'
    sensorname = 'DHT11'
elif sensortype == 2 :
    sensortype_txt = '2'
    sensorname = 'DHT22'
elif sensortype == 3 :
    sensortype_txt = '3'
    sensorname = 'SHT'
#---------------------------------------------------------------------------------------------------------------- Sprache
# ####   Set up message catalog access
# # translation = gettext.translation('pi-ager', '/var/www/locale', fallback=True)
# # _ = translation.ugettext
# if language == 1:
    # translation = gettext.translation('pi-ager', '/var/www/locale', languages=['de_DE'], fallback=True)
# elif language == 2:
    # translation = gettext.translation('pi-ager', '/var/www/locale', languages=['en'], fallback=True)
# # else:
# translation.install()
pi_ager_init.set_language()
#---------------------------------------------------------------------------------- Variablen
if pi_ager_debug.debugging == 'on':
    day_in_seconds = 1  #zum testen ein Tag vergeht in einer Sekunde
else:
    day_in_seconds = 86400  #Anzahl der Sek. in einem Tag
    

######################################################### Hauptprogramm
########################################################################################################################
pi_ager_organization.write_verbose(pi_ager_init.logspacer, False, True)
logstring = "\n" + _('the climate values are now controlled by the automatic program % s') % (agingtable) + "\n" + "\n"
pi_ager_organization.write_verbose(logstring, False, True)

#---------------------------------------------------------------------------------- Auslesen der gesammten csv-Datei
csv_file = open(csv_path + csv_file,"r")   # Variable csv_file = csv-Datei oeffnen
csv_file_reader = csv.DictReader(csv_file)  # reader-Objekt liest csv in Dictionary ein
row_number = 0                              # Setzt Variable row_number auf 0
total_duration = 0                          # Setzt Variable duration auf 0

for row in csv_file_reader:
    if pi_ager_debug.debugging == 'on':
        print ('DEBUG' + str(row))
    total_duration += int(row["days"])                           # errechnet die Gesamtdauer
    build_dictionary = "dictionary%d = %s"%  (row_number,row)   # baut pro Zeile ein Dictionary
    exec(build_dictionary)                                      # baut pro Zeile das jeweilige Dictionary
    
    row_number += 1                                             # Zeilenanzahl wird hochgezaehlt (fuer Dictionary Nummer und total_periods)
    if pi_ager_debug.debugging == 'on':
        print ('DEBUG ' + str(total_duration))

total_periods = row_number - 1                                    # Variable total_periods = Anzahl der Perioden (0 basiert!), der Reifephasen (entspricht der Anzahl an Reihen)
if pi_ager_debug.debugging == 'on':
    print ('DEBUG ' + str(total_periods))
csv_file.close()
#---------------------------------------------------------------------------------- Lesen der Werte aus der CSV-Datei & Schreiben der Werte in die Konsole und das Logfile
period = 0              # setzt periodenzaehler zurueck
actual_dictionary = None  # setzt aktuelles Dictionary zurueck

while period <= total_periods:
    if pi_ager_debug.debugging == 'on':
        print ('DEBUG period : ' + str(period))
        print ('DEBUG total_periods : ' + str(total_periods))
    exec('{} = {}'.format("actual_dictionary", "dictionary" + str(period))) # Bsp: actual_dictionary =  {t:1, t:2, t:3}
    if pi_ager_debug.debugging == 'on':
        print ('DEBUG actual_dictionary : ' + str(actual_dictionary))
    if period == 0:
        logstring = time.strftime('%d.%m.%Y - %H:%M') + _(' oclock: ') + _('start values period 1 of %s') % (str(total_periods + 1)) + '\n'  
        pi_ager_organization.write_verbose(logstring, False, True)
        finaltime = datetime.datetime.now() + timedelta(days = total_duration)  # days = parameter von timedelta
        read_dictionary(actual_dictionary)
        logstring = _("next change of values: %s") % (period_endtime.strftime('%d.%m.%Y  %H:%M')) + '\n'
        pi_ager_organization.write_verbose(logstring, False, True)
        logstring = _("end of program: %s") % (finaltime.strftime('%d.%m.%Y  %H:%M')) + '\n'
        pi_ager_organization.write_verbose(logstring, False, True)
        
    elif period == total_periods:
        logstring = time.strftime('%d.%m.%Y - %H:%M') + _(' oclock: ') + _('new values for period %s of %s') % (str(period + 1), str(total_periods + 1))
        pi_ager_organization.write_verbose(logstring, False, True)
        read_dictionary(actual_dictionary)
        logstring = '\n' + _('Program "%s" ends the control.') % (agingtable) + '\n' + _('pi-ager continues to work with the last values.')
        pi_ager_organization.write_verbose(logstring, False, True)
        
    else:
        logstring = time.strftime('%d.%m.%Y - %H:%M') + _(' oclock: ') + _('new values for period %s of %s') % (str(period + 1), str(total_periods + 1))
        pi_ager_organization.write_verbose(logstring, False, True)
        read_dictionary(actual_dictionary)
        logstring = _("next change of values: %s") % (period_endtime.strftime('%d.%m.%Y  %H:%M'))
        pi_ager_organization.write_verbose(logstring, False, True)
        logstring = _("end of program: %s") % (finaltime.strftime('%d.%m.%Y  %H:%M'))
        pi_ager_organization.write_verbose(logstring, False, True)
    period += 1
    pi_ager_organization.write_verbose(pi_ager_init.logspacer, False, True)
    if period <= total_periods:
        time.sleep(duration_sleep)       # Wartezeit bis zur naechsten Periode
sys.exit(0)
