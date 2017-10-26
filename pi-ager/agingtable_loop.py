#!/usr/bin/python3

######################################################### Importieren der Module
import sys
# import glob
import time
import datetime
# import csv
# import gettext
from datetime import timedelta
import pi_ager_debug
import pi_ager_database
import pi_ager_organization
import pi_ager_init
import pi_ager_paths
import pi_ager_names
import pi_ager_logging

######################################################### Definieren von Funktionen
# #---------------------------------------------------------------------------------- Funktion zur uebersetzung von z.B. Listenobjekten z.B. animals = [N_('mollusk'), N_('albatross'), N_('rat')]
# def N_(message):
    # return message
#---------------------------------------------------------------------------------- Funktion zum Lesen des Dictionarys und setzen der Werte
def get_dictionary_out_of_sqliterow(row):
    
    dictionary = {}
    dictionary[pi_ager_names.agingtable_modus_field] = row[pi_ager_names.agingtable_modus_field]
    dictionary[pi_ager_names.agingtable_setpoint_temperature_field] = row[pi_ager_names.agingtable_setpoint_temperature_field]
    dictionary[pi_ager_names.agingtable_setpoint_humidity_field] = row[pi_ager_names.agingtable_setpoint_humidity_field]
    dictionary[pi_ager_names.agingtable_circulation_air_duration_field] = row[pi_ager_names.agingtable_circulation_air_duration_field]
    dictionary[pi_ager_names.agingtable_circulation_air_period_field] = row[pi_ager_names.agingtable_circulation_air_period_field]
    dictionary[pi_ager_names.agingtable_exhaust_air_duration_field] = row[pi_ager_names.agingtable_exhaust_air_duration_field]
    dictionary[pi_ager_names.agingtable_exhaust_air_period_field] = row[pi_ager_names.agingtable_exhaust_air_period_field]
    dictionary[pi_ager_names.agingtable_days_field] = row[pi_ager_names.agingtable_days_field]
    
    return dictionary

def read_dictionary_write_settings(dictionary):
    global period_endtime
    global period_starttime_seconds
    global day_in_seconds
    global switch_on_cooling_compressor
    global switch_off_cooling_compressor
    global switch_on_humidifier
    global switch_off_humidifier
    global delay_humidify
    global sensorname
    global sensortype
    from datetime import timedelta

    pi_ager_logging.logger_agingtable_loop.debug('start read_dictionary_write_settings()')
    # Variablen aus Dictionary setzen
    for key, value in iter(dictionary.items()):
        if value == None or value == '':                      # wenn ein Wert leer ist muss er aus der letzten settings.json ausgelesen  werden
            value = pi_ager_database.get_table_value(pi_ager_names.config_settings_table,key)
            exec('{} = {}'.format(key,value), period_settings)   # fuellt die jeweilige Variable mit altem Wert (value = columname)
        else:
            value = int(value)
            exec('{} = {}'.format(key,value), period_settings)
    duration = int (period_settings['days'])
    global duration_sleep
    duration_sleep = int(duration) * day_in_seconds    # Anzahl der Tage von "column" mit 86400 (Sekunden) multipliziert fuer wartezeit bis zur naechsten Periode
    
    #---------------------------------------------------------------------------------- Aufbereitung fuer die Lesbarkeit im Logfile und Fuellen der Variablen
    modus = int(period_settings['modus'] + 0.5)                # Rundet auf Ganzzahl, Integer da der Modus immer Integer sein sollte 
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
    setpoint_temperature_logstring = "\n" + _('setpoint temperature') + ": \t \t" + str(period_settings['setpoint_temperature']) + " C"
    switch_on_cooling_compressor_logstring = "\n" + _('switch-on value temperature') + ": \t" + str(switch_on_cooling_compressor) + " C"
    switch_off_cooling_compressor_logstring = "\n" + _('switch-off value temperature') + ": \t" + str(switch_off_cooling_compressor) + " C"
    sollfeuchtigkeit_logstring = "\n" + _('setpoint humidity') + ": \t \t" + str(period_settings['setpoint_humidity']) + "%"
    switch_on_humidifier_logstring = "\n" + _('switch-on value humidity') + ": \t \t" + str(switch_on_humidifier) + "%"
    switch_off_humidifier_logstring = "\n" + _('switch-off value humidity') + ": \t \t" + str(switch_off_humidifier) + "%"
    delay_humidify_logstring = "\n" + _('humidification delay') + ": \t" + str(delay_humidify) + ' ' + _("minutes")
    circulation_air_period_format = int(period_settings['circulation_air_period'])/60
    circulation_air_period_logstring = "\n" + _('timer circulation air period every') + ": \t" + str(circulation_air_period_format) + ' ' + _("minutes")
    circulation_air_duration_format = int(period_settings['circulation_air_duration'])/60
    circulation_air_duration_logstring = "\n" + _('timer circulation air') + ": \t  \t" + str(circulation_air_duration_format) + ' ' + _("minutes")
    exhaust_air_period_format = int(period_settings['exhaust_air_period'])/60
    exhaust_air_period_logstring = "\n" + _('timer exhaust air period every') + ": \t" + str(exhaust_air_period_format) + ' ' + _("minutes")
    exhaust_air_duration_format = int(period_settings['exhaust_air_duration'])/60
    exhaust_air_duration_logstring = "\n" + _('timer exhausting air') + ": \t \t" + str(exhaust_air_duration_format) + ' ' + _("minutes")
    period_days_logstring="\n" + _('duration') + ": \t \t \t \t" + str(period_settings['days']) + ' ' + _('days')
    sensor_logstring = _('sensortype') + ": \t \t \t" + sensorname + ' ' + _('value') + ': ' + str(sensortype)
    
    pi_ager_logging.logger_agingtable_loop.debug('schreibe settings in if')
    # if pi_ager_debug.debugging == 'on':
        # print ('DEBUG schreibe settings in if')
    pi_ager_database.write_settings(modus, period_settings['setpoint_temperature'], period_settings['setpoint_humidity'], period_settings['circulation_air_period'], period_settings['circulation_air_duration'], period_settings['exhaust_air_period'], period_settings['exhaust_air_duration'])
    period_starttime_seconds = pi_ager_database.get_current_time()
    pi_ager_database.write_current_value(pi_ager_names.agingtable_period_starttime_key, period_starttime_seconds)
    period_endtime = datetime.datetime.now() + timedelta(days = duration) # days = parameter von timedelta
    logstring = operating_mode + setpoint_temperature_logstring + switch_on_cooling_compressor_logstring + switch_off_cooling_compressor_logstring + "\n" + sollfeuchtigkeit_logstring + switch_on_humidifier_logstring + switch_off_humidifier_logstring + delay_humidify_logstring + "\n" + circulation_air_period_logstring + circulation_air_duration_logstring + "\n" + exhaust_air_period_logstring + exhaust_air_duration_logstring + "\n" + period_days_logstring + "\n" + sensor_logstring + "\n" '---------------------------------------' + "\n"
    # pi_ager_organization.write_verbose(logstring, False, True)
    pi_ager_logging.logger_agingtable_loop.debug(logstring)
    
######################################################### Definition von Variablen
def doAgingtableLoop():

    global period_settings
    global period_starttime_seconds
    global period_endtime
    global duration_sleep
    global day_in_seconds
    global switch_on_cooling_compressor
    global switch_off_cooling_compressor
    global switch_on_humidifier
    global switch_off_humidifier
    global delay_humidify
    global sensorname
    global sensortype

    # pi_ager_logging.create_logger('agingtable_loop.py')

    try:
        pi_ager_database.write_start_in_database(pi_ager_names.status_agingtable_key)
        status_agingtable = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_agingtable_key)

        period_settings = {}
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
        agingtable = agingtable.lower()

        #---------------------------------------------------------------------------------- bedingte Werte aus Variablen
        #---------------------------------------------------------------------------------------------------------------- csv-datei
        # csv_file = agingtable + '.csv'                       # Variable csv_file = kompletter Dateiname
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
        #pi_ager_organization.write_verbose(pi_ager_init.logspacer, False, True)
        pi_ager_logging.logger_agingtable_loop.info(pi_ager_init.logspacer)
        logstring = "\n" + _('the climate values are now controlled by the automatic program % s') % (agingtable) + "\n"
        pi_ager_logging.logger_agingtable_loop.info(logstring)
        # pi_ager_organization.write_verbose(logstring, False, True)

        #---------------------------------------------------------------------------------- Auslesen der gesammten csv-Datei
        rows = pi_ager_database.get_agingtable_as_rows(agingtable)
        row_number = 0                              # Setzt Variable row_number auf 0
        total_duration = 0                          # Setzt Variable duration auf 0
        period_starttime_seconds = 0
        duration_sleep = 0
        dict_agingtable={}
        
        for row in rows:
            total_duration += int(row["days"])                           # errechnet die Gesamtdauer
            dict_agingtable[row_number] = get_dictionary_out_of_sqliterow(row)
            # build_dictionary = "dictionary%d = %s"%  (row_number, dict_row)   # baut pro Zeile ein Dictionary
            # pi_ager_logging.logger_agingtable_loop.debug(build_dictionary)
            # exec(build_dictionary)                                      # baut pro Zeile das jeweilige Dictionary
            
            row_number += 1                                             # Zeilenanzahl wird hochgezaehlt (fuer Dictionary Nummer und total_periods)

        total_periods = row_number - 1
        pi_ager_logging.logger_agingtable_loop.debug('total duration (days): ' + str(total_duration))
        pi_ager_logging.logger_agingtable_loop.debug('total periods: ' + str(total_periods))
        #---------------------------------------------------------------------------------- Lesen der Werte aus der CSV-Datei & Schreiben der Werte in die Konsole und das Logfile
        period = 0              # setzt periodenzaehler zurueck
        actual_dictionary = None  # setzt aktuelles Dictionary zurueck

        while period <= total_periods and status_agingtable == 1:
            status_agingtable = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_agingtable_key)
            current_time = pi_ager_database.get_current_time()
            if (period_starttime_seconds == 0 and duration_sleep == 0 and period == 0) or current_time >= period_starttime_seconds + duration_sleep:
                pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, period)
                pi_ager_logging.logger_agingtable_loop.debug('period: ' + str(period))
                pi_ager_logging.logger_agingtable_loop.debug('total periods: ' + str(total_periods))
                actual_dictionary = dict_agingtable[period]
                pi_ager_logging.logger_agingtable_loop.debug('actual_dictionary: ' + str(actual_dictionary))
                if period == 0:
                    logstring = time.strftime('%d.%m.%Y - %H:%M') + _(' oclock: ') + _('start values period 1 of %s') % (str(total_periods + 1))
                    pi_ager_logging.logger_agingtable_loop.info(logstring)
                    finaltime = datetime.datetime.now() + timedelta(days = total_duration)  # days = parameter von timedelta
                    read_dictionary_write_settings(actual_dictionary)
                    logstring = _("next change of values: %s") % (period_endtime.strftime('%d.%m.%Y  %H:%M'))
                    pi_ager_logging.logger_agingtable_loop.info(logstring)
                    logstring = _("end of program: %s") % (finaltime.strftime('%d.%m.%Y  %H:%M'))
                    pi_ager_logging.logger_agingtable_loop.info(logstring)
                    #pi_ager_organization.write_verbose(logstring, False, True)
                    
                elif period == total_periods:
                    logstring = time.strftime('%d.%m.%Y - %H:%M') + _(' oclock: ') + _('new values for period %s of %s') % (str(period + 1), str(total_periods + 1))
                    pi_ager_logging.logger_agingtable_loop.info(logstring)
                    #pi_ager_organization.write_verbose(logstring, False, True)
                    read_dictionary_write_settings(actual_dictionary)
                    logstring = '\n' + _('Program "%s" ends the control.') % (agingtable) + '\n' + _('pi-ager continues to work with the last values.')
                    pi_ager_logging.logger_agingtable_loop.info(logstring)
                    #pi_ager_organization.write_verbose(logstring, False, True)
                    
                else:
                    logstring = time.strftime('%d.%m.%Y - %H:%M') + _(' oclock: ') + _('new values for period %s of %s') % (str(period + 1), str(total_periods + 1))
                    pi_ager_logging.logger_agingtable_loop.info(logstring)
                    #pi_ager_organization.write_verbose(logstring, False, True)
                    read_dictionary_write_settings(actual_dictionary)
                    logstring = _("next change of values: %s") % (period_endtime.strftime('%d.%m.%Y  %H:%M'))
                    pi_ager_logging.logger_agingtable_loop.info(logstring)
                    #pi_ager_organization.write_verbose(logstring, False, True)
                    logstring = _("end of program: %s") % (finaltime.strftime('%d.%m.%Y  %H:%M'))
                    pi_ager_logging.logger_agingtable_loop.info(logstring)
                    #pi_ager_organization.write_verbose(logstring, False, True)
                period += 1
                pi_ager_logging.logger_agingtable_loop.info(pi_ager_init.logspacer)
                #pi_ager_organization.write_verbose(pi_ager_init.logspacer, False, True)
                # if period <= total_periods:
                    # time.sleep(duration_sleep)       # Wartezeit bis zur naechsten Periode
                    
        pi_ager_database.write_stop_in_database(pi_ager_names.status_agingtable_key)
        pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, 0)
        sys.exit(0)

    except Exception as e:
        pi_ager_database.write_stop_in_database(pi_ager_names.status_agingtable_key)
        pi_ager_logging.logger_agingtable_loop.critical(e)

    except KeyboardInterrupt:
        pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, 0)
        pi_ager_logging.logger_agingtable_loop.critical('File stopped by user')
        sys.exit(0)
