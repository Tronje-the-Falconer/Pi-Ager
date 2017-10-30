#!/usr/bin/python3

import sys
import time
import datetime
from datetime import timedelta
import pi_ager_debug
import pi_ager_database
import pi_ager_organization
import pi_ager_init
import pi_ager_paths
import pi_ager_names
import pi_ager_logging

# Definieren von Funktionen
# Funktion zur uebersetzung von z.B. Listenobjekten z.B. animals = [N_('mollusk'), N_('albatross'), N_('rat')]
# def N_(message):
    # return message
# Funktion zum Lesen des Dictionarys und setzen der Werte
def get_dictionary_out_of_sqliterow(row):
    
    period_dictionary = {}
    period_dictionary[pi_ager_names.agingtable_modus_field] = row[pi_ager_names.agingtable_modus_field]
    period_dictionary[pi_ager_names.agingtable_setpoint_temperature_field] = row[pi_ager_names.agingtable_setpoint_temperature_field]
    period_dictionary[pi_ager_names.agingtable_setpoint_humidity_field] = row[pi_ager_names.agingtable_setpoint_humidity_field]
    period_dictionary[pi_ager_names.agingtable_circulation_air_duration_field] = row[pi_ager_names.agingtable_circulation_air_duration_field]
    period_dictionary[pi_ager_names.agingtable_circulation_air_period_field] = row[pi_ager_names.agingtable_circulation_air_period_field]
    period_dictionary[pi_ager_names.agingtable_exhaust_air_duration_field] = row[pi_ager_names.agingtable_exhaust_air_duration_field]
    period_dictionary[pi_ager_names.agingtable_exhaust_air_period_field] = row[pi_ager_names.agingtable_exhaust_air_period_field]
    period_dictionary[pi_ager_names.agingtable_days_field] = row[pi_ager_names.agingtable_days_field]
    
    return period_dictionary

def get_sensortype():
    global sensortype
    
    if sensortype == 1 :
        sensorname = 'DHT11'
    elif sensortype == 2 :
        sensorname = 'DHT22'
    elif sensortype == 3 :
        sensorname = 'SHT'
    
    return sensorname

def get_duration_sleep(period_days):
    global day_in_seconds
    sleep_time = period_days * day_in_seconds    # Anzahl der Tage von "column" mit 86400 (Sekunden) multipliziert fuer wartezeit bis zur naechsten Periode
    return sleep_time

def continue_after_power_failure(current_dictionary):
    failure_temperature_delta = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.failure_temperature_delta_key)     # Maximaler Temperatur-Unterschied
    failure_humidity_delta = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.failure_humidity_delta_key)     # Maximaler Feuchte-Unterschied
    
    temperature_last_change = 0
    current_time = pi_ager_database.get_current_time()
    
    # Solange keine aktuelle Temperatur da ist, soll er in der Schleife warten
    # while current_time - temperature_last_change > 60:
        # temperature_last_change = pi_ager_database.get_last_change(pi_ager_names.current_values_table, pi_ager_names.sensor_temperature_key)
        # current_time = pi_ager_database.get_current_time()
    
    #Zum Testen
    # current_temperature = 18
    # current_humidity = 55
    current_temperature = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_temperature_key)
    current_humidity = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_humidity_key)
    agingtable_temperature = current_dictionary[pi_ager_names.agingtable_setpoint_temperature_field]
    agingtable_humidity = current_dictionary[pi_ager_names.agingtable_setpoint_humidity_field]
    
    pi_ager_logging.logger_agingtable_loop.debug('continue_after_power_failure: ')
        
    pi_ager_logging.logger_agingtable_loop.debug('continue_after_power_failure: ')
    pi_ager_logging.logger_agingtable_loop.debug('current_temperature - agingtable_temperature: ' + str(abs(current_temperature - agingtable_temperature)))
    pi_ager_logging.logger_agingtable_loop.debug('current_humidity - agingtable_humidity: ' + str(abs(current_humidity - agingtable_humidity)))
    if abs(current_temperature - agingtable_temperature) > failure_temperature_delta or abs(current_humidity - agingtable_humidity) > failure_humidity_delta:
        return False
    else:
        return True
def read_dictionary_write_settings(period_dictionary):
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
    for key, value in iter(period_dictionary.items()):
        if value == None or value == '':                      # wenn ein Wert leer ist muss er aus der letzten settings.json ausgelesen  werden
            value = pi_ager_database.get_table_value(pi_ager_names.config_settings_table,key)
            period_dictionary[key] = value
 #           exec('{} = {}'.format(key,value), period_settings)   # fuellt die jeweilige Variable mit altem Wert (value = columname)
        else:
            value = int(value)
            period_dictionary[key] = value
#            exec('{} = {}'.format(key,value), period_settings)
    pi_ager_logging.logger_agingtable_loop.debug(str(period_dictionary))
    global duration_sleep
    duration_sleep = get_duration_sleep(int (period_dictionary['days'])) # Anzahl der Tage von "column" mit 86400 (Sekunden) multipliziert fuer wartezeit bis zur naechsten Periode
    
    # Aufbereitung fuer die Lesbarkeit im Logfile und Fuellen der Variablen
    modus = int(period_dictionary['modus'] + 0.5)                # Rundet auf Ganzzahl, Integer da der Modus immer Integer sein sollte 

    #-------Logstring---------
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
    pi_ager_logging.logger_agingtable_loop.debug('erzeuge logstring...')
    setpoint_temperature_logstring = "\n" + _('setpoint temperature') + ": \t \t" + str(period_dictionary['setpoint_temperature']) + " C"
    switch_on_cooling_compressor_logstring = "\n" + _('switch-on value temperature') + ": \t" + str(switch_on_cooling_compressor) + " C"
    switch_off_cooling_compressor_logstring = "\n" + _('switch-off value temperature') + ": \t" + str(switch_off_cooling_compressor) + " C"
    sollfeuchtigkeit_logstring = "\n" + _('setpoint humidity') + ": \t \t" + str(period_dictionary['setpoint_humidity']) + "%"
    switch_on_humidifier_logstring = "\n" + _('switch-on value humidity') + ": \t \t" + str(switch_on_humidifier) + "%"
    switch_off_humidifier_logstring = "\n" + _('switch-off value humidity') + ": \t \t" + str(switch_off_humidifier) + "%"
    delay_humidify_logstring = "\n" + _('humidification delay') + ": \t" + str(delay_humidify) + ' ' + _("minutes")
    circulation_air_period_format = int(period_dictionary['circulation_air_period'])/60
    circulation_air_period_logstring = "\n" + _('timer circulation air period every') + ": \t" + str(circulation_air_period_format) + ' ' + _(" minutes")
    circulation_air_duration_format = int(period_dictionary['circulation_air_duration'])/60
    circulation_air_duration_logstring = "\n" + _('timer circulation air') + ": \t  \t" + str(circulation_air_duration_format) + ' ' + _(" minutes")
    exhaust_air_period_format = int(period_dictionary['exhaust_air_period'])/60
    exhaust_air_period_logstring = "\n" + _('timer exhaust air period every') + ": \t" + str(exhaust_air_period_format) + ' ' + _(" minutes")
    exhaust_air_duration_format = int(period_dictionary['exhaust_air_duration'])/60
    exhaust_air_duration_logstring = "\n" + _('timer exhausting air') + ": \t \t" + str(exhaust_air_duration_format) + ' ' + _(" minutes")
    period_days_logstring="\n" + _('duration') + ": \t \t \t \t" + str(period_dictionary['days']) + ' ' + _('days')
    sensor_logstring = _('sensortype') + ": \t \t \t" + sensorname + ' ' + _('value') + ': ' + str(sensortype)
    
    pi_ager_logging.logger_agingtable_loop.debug('schreibe settings in if')

    
    pi_ager_database.write_settings(modus, period_dictionary['setpoint_temperature'], period_dictionary['setpoint_humidity'], period_dictionary['circulation_air_period'], period_dictionary['circulation_air_duration'], period_dictionary['exhaust_air_period'], period_dictionary['exhaust_air_duration'])

    period_starttime_seconds = pi_ager_database.get_current_time()
    pi_ager_database.write_current_value(pi_ager_names.agingtable_period_starttime_key, period_starttime_seconds)
    period_endtime = datetime.datetime.now() + timedelta(days = period_dictionary['days']) # days = parameter von timedelta

    logstring = operating_mode + setpoint_temperature_logstring + switch_on_cooling_compressor_logstring + switch_off_cooling_compressor_logstring + "\n" + sollfeuchtigkeit_logstring + switch_on_humidifier_logstring + switch_off_humidifier_logstring + delay_humidify_logstring + "\n" + circulation_air_period_logstring + circulation_air_duration_logstring + "\n" + exhaust_air_period_logstring + exhaust_air_duration_logstring + "\n" + period_days_logstring + "\n" + sensor_logstring + "\n" '---------------------------------------' + "\n"
    pi_ager_logging.logger_agingtable_loop.debug(logstring)
    
# Definition von Variablen
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
        period = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.agingtable_period_key)              # setzt periodenzaehler
        
        period_settings = {}
        # Allgemeingueltige Werte aus Datenbank
        sensortype = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.sensortype_key)
        language = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.language_key)                    # Sprache der Textausgabe
        switch_on_cooling_compressor = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_cooling_compressor_key)
        switch_off_cooling_compressor = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_off_cooling_compressor_key)
        switch_on_humidifier = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_humidifier_key)
        switch_off_humidifier = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_off_humidifier_key)
        delay_humidify = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.delay_humidify_key)
        # Reifetabelle aus Datenbank
        agingtable = pi_ager_database.read_agingtable_name_from_config()    # Variable agingtable = Name der Reifetabelle
        agingtable = agingtable.lower()

        # bedingte Werte aus Variablen
        # Sensor
        sensorname = get_sensortype()
        pi_ager_init.set_language()
        # Variablen
        debug_modus = pi_ager_database.get_table_value(pi_ager_names.debug_table, pi_ager_names.loglevel_console_key)
        if debug_modus == 10:
            day_in_seconds = pi_ager_database.get_table_value(pi_ager_names.debug_table, pi_ager_names.agingtable_days_in_seconds_debug_key)  # zum testen Wert aus DB holen: ein Tag vergeht in einer Sekunde
        else:
            day_in_seconds = 86400  #Anzahl der Sek. in einem Tag
            
 
        # Hauptprogramm
        pi_ager_logging.logger_agingtable_loop.info(pi_ager_init.logspacer)
        logstring = "\n" + _('the climate values are now controlled by the automatic program % s') % (agingtable) + "\n"
        pi_ager_logging.logger_agingtable_loop.info(logstring)
        rows = pi_ager_database.get_agingtable_as_rows(agingtable)

        row_number = 0                              # Setzt Variable row_number auf 0
        total_duration = 0                          # Setzt Variable duration auf 0
        dict_agingtable={}
        
        for row in rows:
            total_duration += int(row["days"])                           # errechnet die Gesamtdauer
            dict_agingtable[row_number] = get_dictionary_out_of_sqliterow(row)
            
            row_number += 1                                             # Zeilenanzahl wird hochgezaehlt (fuer Dictionary Nummer und total_periods)
       
        total_periods = row_number - 1
        pi_ager_logging.logger_agingtable_loop.debug('total duration (days): ' + str(total_duration))
        pi_ager_logging.logger_agingtable_loop.debug('total periods: ' + str(total_periods))

        # Wenn period = 0 wird die Reifetabelle neu gestartet, ansonsten an der aktuellen period fortgesetzt
        if period == 0:

            # Sprache
            # ####   Set up message catalog access
            # # translation = gettext.translation('pi-ager', '/var/www/locale', fallback=True)
            # # _ = translation.ugettext
            # if language == 1:
                # translation = gettext.translation('pi-ager', '/var/www/locale', languages=['de_DE'], fallback=True)
            # elif language == 2:
                # translation = gettext.translation('pi-ager', '/var/www/locale', languages=['en'], fallback=True)
            # # else:
            # translation.install()
            period_starttime_seconds = 0
            duration_sleep = 0
            actual_dictionary = None  # setzt aktuelles Dictionary zurueck
            
        elif not continue_after_power_failure(dict_agingtable[period]):
            
            # To Do: ALARM (Piezo) einfügen
            logstring = 'Unterbrechung Reifetabelle "' + agingtable + '" in Periode ' + str(period) + '!!!'
            pi_ager_logging.logger_agingtable_loop.critical(logstring)
            pi_ager_database.write_stop_in_database(pi_ager_names.status_agingtable_key)
            status_agingtable = 0
            pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, status_agingtable)
            
        else: #Sensorwerte sind im Toleranzbereich, Reifetabelle kann normal fortgesetzt werden
            #eventuell noch Prüfung, ob der Periodenwechsel vor zu langer Zeit hätte stattfinden müssen
            period_starttime_seconds = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.agingtable_period_starttime_key)
            actual_dictionary = dict_agingtable[period]
            duration_sleep = get_duration_sleep(int (actual_dictionary['days']))

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
                    
                elif period == total_periods:
                    logstring = time.strftime('%d.%m.%Y - %H:%M') + _(' oclock: ') + _('new values for period %s of %s') % (str(period + 1), str(total_periods + 1))
                    pi_ager_logging.logger_agingtable_loop.info(logstring)
                    read_dictionary_write_settings(actual_dictionary)
                    logstring = '\n' + _('Program "%s" ends the control.') % (agingtable) + '\n' + _('pi-ager continues to work with the last values.')
                    pi_ager_logging.logger_agingtable_loop.info(logstring)
                    
                else:
                    logstring = time.strftime('%d.%m.%Y - %H:%M') + _(' oclock: ') + _('new values for period %s of %s') % (str(period + 1), str(total_periods + 1))
                    pi_ager_logging.logger_agingtable_loop.info(logstring)
                    read_dictionary_write_settings(actual_dictionary)
                    logstring = _("next change of values: %s") % (period_endtime.strftime('%d.%m.%Y  %H:%M'))
                    pi_ager_logging.logger_agingtable_loop.info(logstring)
                    # finaltime für diesen Fall neu berechnen
                    #logstring = _("end of program: %s") % (finaltime.strftime('%d.%m.%Y  %H:%M'))
                    pi_ager_logging.logger_agingtable_loop.info(logstring)
                period += 1
                pi_ager_logging.logger_agingtable_loop.info(pi_ager_init.logspacer)
                # if period <= total_periods:
                    # time.sleep(duration_sleep)       # Wartezeit bis zur naechsten Periode
                    
            else:
                pi_ager_logging.logger_agingtable_loop.info('in agingtable duration_sleep-loop. duration_sleep: ' + str(duration_sleep) + ' sec.')
                
        pi_ager_database.write_stop_in_database(pi_ager_names.status_agingtable_key)
        pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, 0)
        sys.exit(0)

    except Exception as e:
        pi_ager_database.write_stop_in_database(pi_ager_names.status_agingtable_key)
        pi_ager_logging.logger_agingtable_loop.critical(e)

    except KeyboardInterrupt:
        pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, 0)
        pi_ager_logging.logger_agingtable_loop.critical('file stopped by user')
        sys.exit(0)
