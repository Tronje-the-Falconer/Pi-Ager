#!/usr/bin/python
# -*- coding: iso-8859-1 -*-

######################################################### Importieren der Module
import sys
import os
import json
import glob
import time
import datetime
import csv
import gettext
from datetime import timedelta
######################################################### Definieren von Funktionen
#---------------------------------------------------------------------------------- Function Lesen der tables.json
def read_tables_json():
    global tables_json_file
    tables_data = None
    with open(tables_json_file, 'r') as tablesjsonfile:
        tables_data = tablesjsonfile.read();
    data_tablesjsonfile = json.loads(tables_data);
    return data_tablesjsonfile
#---------------------------------------------------------------------------------- Function Lesen der settings.json
def read_settings_json():
    global settings_json_file
    settings_data = None
    with open(settings_json_file, 'r') as settingsjsonfile:
        settings_data = settingsjsonfile.read()
    data_settingsjsonfile = json.loads(settings_data)
    return data_settingsjsonfile
#---------------------------------------------------------------------------------- Function Lesen der settings.json
def read_config_json():
    global config_json_file
    config_data = None
    with open(config_json_file, 'r') as configjsonfile:
        config_data = configjsonfile.read()
    data_configjsonfile = json.loads(config_data)
    return data_configjsonfile
#---------------------------------------------------------------------------------- Function Schreiben der settings.json
def write_settings_json(modus, setpoint_temperature, setpoint_humidity, circulation_air_period, circulation_air_duration, exhaust_air_period, exhaust_air_duration):
    global settings_json_file

    setting_data = json.dumps({"modus":modus, "setpoint_temperature":setpoint_temperature, "setpoint_humidity":setpoint_humidity, "circulation_air_period":circulation_air_period, "circulation_air_duration":circulation_air_duration, "exhaust_air_period":exhaust_air_period, "exhaust_air_duration":exhaust_air_duration, "switch_on_cooling_compressor":switch_on_cooling_compressor, "switch_off_cooling_compressor":switch_off_cooling_compressor, "switch_on_humidifier":switch_on_humidifier, "switch_off_humidifier":switch_off_humidifier, "delay_humidify":delay_humidify, 'date':int(time.time()), 'sensortype':sensortype})
    with open(settings_json_file, 'w') as settingsjsonfile:
        settingsjsonfile.write(setting_data)
#---------------------------------------------------------------------------------- Funktion zur Übersetzung einzelner Passagen ohne print-Befehl
def N_(message):
    return message
#---------------------------------------------------------------------------------- Funktion zur schreiben in das Logfile
def write_logfile(logtext):
    logfile_txt = open(logfile_txt_file, 'a')           # Variable target = logfile.txt öffnen
    logfile_txt.write(logtext)
    logfile_txt.close
    print logtext

#---------------------------------------------------------------------------------- Funktion zum Lesen des Dictionarys und setzen der Werte
def read_dictionary(dictionary):
    # print 'DEBUG read_dictionary()'
    # Variablen aus Dictionary setzen
    for key, value in dictionary.iteritems():
        if value == '':                 # wenn ein Wert leer ist muss er aus der letzten settings.json ausgelesen  werden
            data_settings_json = read_settings_json()
            value = data_settings_json['' + key + '']
            exec('%s = %d') % (key,value)    # füllt die jeweilige Variable mit altem Wert (value = columname)
        else:
            value = int(value)
            exec('%s = %d') % (key,value)
        
    duration = int (days)
    global duration_sleep
    duration_sleep = int(duration) * day_in_seconds    # Anzahl der Tage von "column" mit 86400 (Sekunden) multipliziert für wartezeit bis zur nächsten Periode
#---------------------------------------------------------------------------------- Aufbereitung für die Lesbarkeit im Logfile und Füllen der Variablen
    modus = int(modus + 0.5)                # Rundet auf Ganzzahl, Integer da der Modus immer Integer sein sollte 
    if modus == 0:
        operating_mode = "\n" + N_('Betriebsart: Kühlen')
    elif modus == 1:
        operating_mode = "\n" + N_('Betriebsart: Kühlen mit Befeuchtung')
    elif modus == 2:
        operating_mode = "\n" + N_('Betriebsart: Heizen mit Befeuchtung')
    elif modus == 3:
        operating_mode = "\n" + N_('Betriebsart: Automatik mit Befeuchtung')
    elif modus == 4:
        operating_mode = "\n" + N_('Betriebsart: Automatik mit Be- und Entfeuchtung')
    else:
        operating_mode = "\n" + N_('Betriebsart falsch oder nicht gesetzt')
    setpoint_temperature_logtext = "\n" + N_('Soll-Temperatur:') + " \t \t" + str(setpoint_temperature) + "°C"
    switch_on_cooling_compressor_logtext = "\n" + N_('Einschaltwert Temperatur:') + " \t" + str(switch_on_cooling_compressor) + "°C"
    switch_off_cooling_compressor_logtext = "\n" + N_('Ausschaltwert Temperatur:') + " \t" + str(switch_off_cooling_compressor) + "°C"
    sollfeuchtigkeit_logtext = "\n" + N_('Soll-Feuchtigkeit:') + " \t \t" + str(setpoint_humidity) + "%"
    switch_on_humidifier_logtext = "\n" + N_('Einschaltwert Feuchte:') + " \t \t" + str(switch_on_humidifier) + "%"
    switch_off_humidifier_logtext = "\n" + N_('Ausschaltwert Feuchte:') + " \t \t" + str(switch_off_humidifier) + "%"
    delay_humidify_logtext = "\n" + N_('Befeuchtungsverzögerung:') + " \t" + str(delay_humidify) + "min"
    circulation_air_period_format = int(circulation_air_period)/60
    circulation_air_period_logtext = "\n" + N_('Timer Umluftperiode alle:') + " \t" + str(circulation_air_period_format) + "min"
    circulation_air_duration_format = int(circulation_air_duration)/60
    circulation_air_duration_logtext = "\n" + N_('Timer Umluftdauer:') + " \t  \t" + str(circulation_air_duration_format) + "min"
    exhaust_air_period_format = int(exhaust_air_period)/60
    exhaust_air_period_logtext = "\n" + N_('Timer Abluftperiode alle:') + " \t" + str(exhaust_air_period_format) + "min"
    exhaust_air_duration_format = int(exhaust_air_duration)/60
    exhaust_air_duration_logtext = "\n" + N_('Timer Abluftdauer:') + " \t \t" + str(exhaust_air_duration_format) + "min"
    period_days_logtext="\n" + N_('Dauer:') + " \t \t \t \t" + str(days) + N_(' Tage')
    sensor_logtext = N_('Sensortyp: ') + " \t \t \t" + sensorname + ' Value: ' + str(sensortype)
    
    
    # print 'DEBUG schreibe settings.json in if'
    write_settings_json (modus, setpoint_temperature, setpoint_humidity, circulation_air_period, circulation_air_duration, exhaust_air_period, exhaust_air_duration)
    global period_endtime
    period_endtime = datetime.datetime.now() + timedelta(days = duration) # days = parameter von timedelta
    logtext = operating_mode + setpoint_temperature_logtext + switch_on_cooling_compressor_logtext + switch_off_cooling_compressor_logtext + "\n" + sollfeuchtigkeit_logtext + switch_on_humidifier_logtext + switch_off_humidifier_logtext + delay_humidify_logtext + "\n" + circulation_air_period_logtext + circulation_air_duration_logtext + "\n" + exhaust_air_period_logtext + exhaust_air_duration_logtext + "\n" + period_days_logtext + "\n" + sensor_logtext + "\n" '---------------------------------------'
    write_logfile(logtext)
    
    
######################################################### Definition von Variablen
#---------------------------------------------------------------------------------- Pfade zu den Dateien
website_path = '/var/www'
csv_path = website_path + '/csv/'
settings_json_file = website_path+'/settings.json'
tables_json_file = website_path + '/tables.json'
config_json_file = website_path + '/config.json'
logfile_txt_file = website_path + '/logfile.txt'
#---------------------------------------------------------------------------------- Allgemeingültige Werte aus config.json
data_config_json = read_config_json()
sensortype = data_config_json ['sensortype']                                        # Sensortyp
language = data_config_json ['language']                                            # Sprache der Textausgabe
switch_on_cooling_compressor = data_config_json ['switch_on_cooling_compressor']    # Einschalttemperatur
switch_off_cooling_compressor = data_config_json ['switch_off_cooling_compressor']  # Ausschalttemperatur
switch_on_humidifier = data_config_json ['switch_on_humidifier']                    # Einschaltfeuchte
switch_off_humidifier = data_config_json ['switch_off_humidifier']                  # Ausschaltfeuchte
delay_humidify = data_config_json ['delay_humidify']                                # Luftbefeuchtungsverzögerung

#---------------------------------------------------------------------------------- Tabelle aus tables.json
hangingtable_json = read_tables_json()                   # Function-Aufruf
hangingtable_name = hangingtable_json['hangingtable']    # Variable reifetablename = Name der Reifetabelle

#---------------------------------------------------------------------------------- bedingte Werte aus Variablen
#---------------------------------------------------------------------------------------------------------------- csv-datei
csv_file = hangingtable_name + '.csv'                       # Variable csv_file = kompletter Dateiname
#---------------------------------------------------------------------------------------------------------------- Sensor
if sensortype == 1 :
    sensortype_txt = '1'
    sensorname = 'DHT'
if sensortype == 2 :
    sensortype_txt = '2'
    sensorname = 'SHT'
#---------------------------------------------------------------------------------------------------------------- Sprache
####   Set up message catalog access
# translation = gettext.translation('pi_ager', '/var/www/locale', fallback=True)
# _ = translation.ugettext
if language == 'de':
    translation = gettext.translation('pi_ager', '/var/www/locale', languages=['en'], fallback=True)
elif language == 'en':
    translation = gettext.translation('pi_ager', '/var/www/locale', languages=['de'], fallback=True)
# else:
    
translation.install()



print _('This message is in the script. normal print')
e = N_('This message is in the script. Variable e')
print e

#---------------------------------------------------------------------------------- Variablen
#day_in_seconds = 86400  #Anzahl der Sek. in einem Tag
day_in_seconds = 1  #zum testen ein Tag vergeht in einer Sekunde
logspacer = "\n"+ "***********************************************"

######################################################### Hauptprogramm
########################################################################################################################
write_logfile(logspacer)
logtext = "\n" + N_('Die Klima-Werte werden nun vom automatischen Programm "%s" gesteuert') % (hangingtable_name)
write_logfile(logtext)

#---------------------------------------------------------------------------------- Auslesen der gesammten csv-Datei
csv_file = open(csv_path + csv_file,"rb")   # Variable csv_file = csv-Datei oeffnen
csv_file_reader = csv.DictReader(csv_file)  # reader-Objekt liest csv in Dictionary ein
row_number = 0                              # Setzt Variable row_number auf 0
total_duration = 0                          # Setzt Variable duration auf 0

for row in csv_file_reader:
    # print 'DEBUG' + str(row)
    total_duration += int(row["days"])                           # errechnet die Gesamtdauer
    build_dictionary = "dictionary%d = %s"%  (row_number,row)   # baut pro Zeile ein Dictionary
    exec(build_dictionary)                                      # baut pro Zeile das jeweilige Dictionary
    
    row_number += 1                                             # Zeilenanzahl wird hochgezählt (für Dictionary Nummer und total_periods)
    # print 'DEBUG ' + str(total_duration)

total_periods = row_number - 1                                    # Variable total_periods = Anzahl der Perioden (0 basiert!), der Reifephasen (entspricht der Anzahl an Reihen)
# print 'DEBUG ' + str(total_periods)
csv_file.close()
#---------------------------------------------------------------------------------- Lesen der Werte aus der CSV-Datei & Schreiben der Werte in die Konsole und das Logfile
period = 0              # setzt periodenzähler zurück
actual_dictionary = ""  # setzt aktuelles Dictionary zurück

while period <= total_periods:
    # print 'DEBUG period = ' + str(period)
    # print 'DEBUG total_periods ' + str(total_periods)
    exec('%s = %s') % ("actual_dictionary", "dictionary" + str(period))
    # print 'DEBUG' + str(actual_dictionary)
    if period == 0:
        logtext = time.strftime('%d.%m.%Y - %H:%M Uhr') + N_(': Startwerte Periode 1 von %s') % (str(total_periods + 1)) + '\n'  
        write_logfile(logtext)
        finaltime = datetime.datetime.now() + timedelta(days = total_duration)  # days = parameter von timedelta
        read_dictionary(actual_dictionary)
        logtext = N_("Nächste Änderung der Werte: %s") % (period_endtime.strftime('%d.%m.%Y  %H:%M'))
        write_logfile(logtext)
        logtext = N_("Programmende: %s") % (finaltime.strftime('%d.%m.%Y  %H:%M'))
        write_logfile(logtext)
        
    elif period == total_periods:
        logtext = time.strftime('%d.%m.%Y - %H:%M Uhr') + N_(': Neue Werte für Periode %s von %s') % (str(period + 1), str(total_periods + 1))
        write_logfile(logtext)
        read_dictionary(actual_dictionary)
        logtext = '\n' + N_('Programm "%s " beendet die Kontrolle.') % (hangingtable_name) + '\n' + N_('Der Reifeschrank funktioniert weiter mit den letzten Werten.')
        write_logfile(logtext)
        
    else:
        logtext = time.strftime('%d.%m.%Y - %H:%M Uhr') + N_(': Neue Werte für Periode %s von %s') % (str(period + 1), str(total_periods + 1))
        write_logfile(logtext)
        read_dictionary(actual_dictionary)
        logtext = N_("Nächste Änderung der Werte: %s") % (period_endtime.strftime('%d.%m.%Y  %H:%M'))
        write_logfile(logtext)
        logtext = N_("Programmende: %s") % (finaltime.strftime('%d.%m.%Y  %H:%M'))
        write_logfile(logtext)
    period += 1
    logtext = "***********************************************"
    write_logfile(logtext)
    if period <= total_periods:
        time.sleep(duration_sleep)       # Wartezeit bis zur nächsten Periode
sys.exit(0)
