#!/usr/bin/python3
"""
    thread for agingtable
    
    mainfile for handling automated aging by using aging tables
"""

import sys
import time
import datetime
import pi_ager_database
import pi_ager_init
import pi_ager_names
from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from main.pi_ager_cl_logger import cl_fact_logger

import threading

class cl_aging_thread( threading.Thread ):

    def __init__(self):
        super().__init__() 
        self.stop_received = False 

    def run(self):
        try:
#            cl_fact_logger.get_instance().debug('logging initialised __________________________')
            cl_fact_logger.get_instance().info(_('Starting aging loop at') + ' ' + time.strftime('%H:%M:%S', time.localtime()))
            pi_ager_database.write_current_value(pi_ager_names.aging_thread_alive_key, 1)

            self.doAgingtableLoop()

        except Exception as cx_error:
            cl_fact_logic_messenger().get_instance().handle_exception(cx_error)

        finally:
            pi_ager_database.write_current_value(pi_ager_names.aging_thread_alive_key, 0)
            cl_fact_logger.get_instance().info(_('Aging loop stopped at') + ' ' + time.strftime('%H:%M:%S', time.localtime()))
#            pi_ager_database.write_stop_in_database(pi_ager_names.status_agingtable_key)

    # Definieren von Funktionen
    # def N_(message):
    #    """
    #    Funktion zur uebersetzung von z.B. Listenobjekten z.B. animals = [N_('mollusk'), N_('albatross'), N_('rat')]
    #    """
    #     return message
    def get_dictionary_out_of_sqliterow(self, row):
        """
        function for reading the dictionary and setting the values
        """
        
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


    def get_duration_sleep(self, period_days):
        """
        function for reading the pause duration until a new value has to be written
        """
        global day_in_seconds
        sleep_time = period_days * day_in_seconds    # Anzahl der Tage von "column" mit 86400 (Sekunden) multipliziert fuer wartezeit bis zur naechsten Periode
        return sleep_time
    
    def continue_after_power_failure(self, current_dictionary):
        """
        after power failure, this function is performed to analyze states and failure values
        """
        failure_temperature_delta = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.failure_temperature_delta_key)     # Maximaler Temperatur-Unterschied
        failure_humidity_delta = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.failure_humidity_delta_key)     # Maximaler Feuchte-Unterschied
        period = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.agingtable_period_key)              # setzt periodenzaehler
    
        temperature_last_change = 0
        current_time = pi_ager_database.get_current_time()
        
        current_temperature = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_temperature_key)
        current_humidity = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_humidity_key)
        
        agingtable_temperature = current_dictionary[pi_ager_names.agingtable_setpoint_temperature_field]
        if agingtable_temperature == None:
            agingtable_temperature = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_temperature_key)
        
        agingtable_humidity = current_dictionary[pi_ager_names.agingtable_setpoint_humidity_field]
        if agingtable_humidity == None:
            agingtable_humidity = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_humidity_key)
            
        cl_fact_logger.get_instance().info(_('current period') + ': ' + str(int(period)))
        cl_fact_logger.get_instance().info('agingtable continues after power failure')
        cl_fact_logger.get_instance().debug(current_dictionary)
        cl_fact_logger.get_instance().debug('current_temperature - agingtable_temperature: ' + str(abs(current_temperature - agingtable_temperature)))
        cl_fact_logger.get_instance().debug('failure_temperature_delta: ' + str(failure_temperature_delta))
        cl_fact_logger.get_instance().debug('current_humidity - agingtable_humidity: ' + str(abs(current_humidity - agingtable_humidity)))
        cl_fact_logger.get_instance().debug('failure_humidity_delta: ' + str(failure_humidity_delta))
        
        if abs(current_temperature - agingtable_temperature) > failure_temperature_delta or abs(current_humidity - agingtable_humidity) > failure_humidity_delta:
            return False         # here False
        else:
            return True

    def read_dictionary_write_settings(self, period_dictionary, period_first_day = 0):
        """
        function for writing the settings into the DB
        """
        from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type
        
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
    
        cl_fact_logger.get_instance().debug('read_dictionary_write_settings()')
        
        # Variablen aus Dictionary setzen
        for key, value in iter(period_dictionary.items()):
            if value == None or value == '':                      # wenn ein Wert leer ist muss er aus der letzten settings.json ausgelesen  werden
                value = pi_ager_database.get_table_value(pi_ager_names.config_settings_table,key)
                period_dictionary[key] = value
            else:
                value = int(value)
                period_dictionary[key] = value
    
        global duration_sleep
        duration_sleep = self.get_duration_sleep(int (period_dictionary['days'])) # Anzahl der Tage von "column" mit 86400 (Sekunden) multipliziert fuer wartezeit bis zur naechsten Periode
        
        # Aufbereitung fuer die Lesbarkeit im Logfile und Fuellen der Variablen
        modus = int(period_dictionary['modus'] + 0.5)                # Rundet auf Ganzzahl, Integer da der Modus immer Integer sein sollte 
    
        #-------Logstring---------
        if modus == 0:
            operating_mode = "\n" + '.................................' + _('operation mode') + ': ' + _('cooling')
        elif modus == 1:
            operating_mode = "\n" + '.................................' + _('operation mode') + ': ' + _('cooling with humidify')
        elif modus == 2:
            operating_mode = "\n" + '.................................' + _('operation mode') + ': ' + _('heating with humidify')
        elif modus == 3:
            operating_mode = "\n" + '.................................' + _('operation mode') + ': ' + _('automatic with humidify')
        elif modus == 4:
            operating_mode = "\n" + '.................................' + _('operation mode') + ': ' + _('automatic with dehumidify and humidify')
        else:
            operating_mode = "\n" + '.................................' + _('operation mode wrong or set incorrectly')

        setpoint_temperature_logstring = "\n" + '.................................' + _('setpoint temperature') + ": " + str(period_dictionary['setpoint_temperature']) + " C"
        switch_on_cooling_compressor_logstring = "\n" + '.................................' + _('switch-on value temperature') + ": " + str(switch_on_cooling_compressor) + " C"
        switch_off_cooling_compressor_logstring = "\n" + '.................................' + _('switch-off value temperature') + ": " + str(switch_off_cooling_compressor) + " C"
        setpoint_humidity_logstring = "\n" + '.................................' + _('setpoint humidity') + ": " + str(period_dictionary['setpoint_humidity']) + "%"
        switch_on_humidifier_logstring = "\n" + '.................................' + _('switch-on value humidity') + ": " + str(switch_on_humidifier) + "%"
        switch_off_humidifier_logstring = "\n" + '.................................' + _('switch-off value humidity') + ": " + str(switch_off_humidifier) + "%"
        delay_humidify_logstring = "\n" + '.................................' + _('humidification delay') + ": " + str(delay_humidify) + ' ' + _("minutes")
        circulation_air_period_format = int(period_dictionary['circulation_air_period'])/60
        circulation_air_period_logstring = "\n" + '.................................' + _('timer circulation air period every') + ": " + str(circulation_air_period_format) + ' ' + _("minutes")
        circulation_air_duration_format = int(period_dictionary['circulation_air_duration'])/60
        circulation_air_duration_logstring = "\n" + '.................................' + _('timer circulation air') + ": " + str(circulation_air_duration_format) + ' ' + _("minutes")
        exhaust_air_period_format = int(period_dictionary['exhaust_air_period'])/60
        exhaust_air_period_logstring = "\n" + '.................................' + _('timer exhaust air period every') + ": " + str(exhaust_air_period_format) + ' ' + _("minutes")
        exhaust_air_duration_format = int(period_dictionary['exhaust_air_duration'])/60
        exhaust_air_duration_logstring = "\n" + '.................................' + _('timer exhausting air') + ": " + str(exhaust_air_duration_format) + ' ' + _("minutes")
        period_days_logstring="\n" + '.................................' + _('duration') + ": " + str(period_dictionary['days']) + ' ' + (_('days') if period_dictionary['days'] > 1 else _('day'))
        sensor_logstring = '.................................' + _('sensortype') + ": " + cl_fact_main_sensor_type().get_instance()._get_type_ui( )
        
        pi_ager_database.write_settings(modus, period_dictionary['setpoint_temperature'], period_dictionary['setpoint_humidity'], period_dictionary['circulation_air_period'], period_dictionary['circulation_air_duration'], period_dictionary['exhaust_air_period'], period_dictionary['exhaust_air_duration'])
    
        period_starttime_seconds = pi_ager_database.get_current_time() - period_first_day * day_in_seconds
        pi_ager_database.write_current_value(pi_ager_names.agingtable_period_starttime_key, period_starttime_seconds)
        period_endtime = datetime.datetime.now() + datetime.timedelta(days = period_dictionary['days'] - period_first_day) # days = parameter von datetime.timedelta
    
        logstring = _('values') + ': ' + operating_mode + setpoint_temperature_logstring + switch_on_cooling_compressor_logstring + switch_off_cooling_compressor_logstring + "\n" + setpoint_humidity_logstring + switch_on_humidifier_logstring + switch_off_humidifier_logstring + delay_humidify_logstring + "\n" + circulation_air_period_logstring + circulation_air_duration_logstring + "\n" + exhaust_air_period_logstring + exhaust_air_duration_logstring + "\n" + period_days_logstring + "\n" + sensor_logstring + "\n"
        cl_fact_logger.get_instance().info(logstring)
    
    def eval_final_time(self, rows, start_period, start_day):
        """
        evaluate final time, when aging starts in rows[period] and with first_day in period
        """
        row_number = 0                              # Setzt Variable row_number auf 0
        total_days = int(rows[int(start_period)]["days"]) - start_day    # Setzt Variable duration auf 0
        total_rows = len(rows)
        if total_rows > (start_period + 1):
            for var in list(range(int(start_period) + 1, int(total_rows))):
                total_days += int(rows[var]["days"])      # errechnet die Gesamtdauer
       
        finaltime = datetime.datetime.now() + datetime.timedelta(days = total_days)  # days = parameter von datetime.timedeltatotal_periods = row_number - 1
        return finaltime

    def doAgingtableLoop(self):
        """
        main function for the agingtable
        """
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
        global sensortype

        while True:
            time.sleep(1)
            if self.stop_received:   # or not threading.main_thread().is_alive():
                break   # stop thread
                
            status_agingtable = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_agingtable_key)
            if status_agingtable == 0:
                continue
                
            last_period = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.agingtable_period_key))              # remember last period 
            last_period_day = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.agingtable_period_day_key))      # remember last period_day
            
            manual_start_period = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.agingtable_startperiod_key))
            manual_start_day = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.agingtable_startday_key))
            pi_ager_database.update_value_in_table(pi_ager_names.config_settings_table, pi_ager_names.agingtable_startday_key, 1)
            pi_ager_database.update_value_in_table(pi_ager_names.config_settings_table, pi_ager_names.agingtable_startperiod_key, 1)
            
            period_settings = {}
            # Allgemeingueltige Werte aus Datenbank
            sensortype = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.sensortype_key)
#            language = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.language_key)                    # Sprache der Textausgabe
            switch_on_cooling_compressor = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_cooling_compressor_key)
            switch_off_cooling_compressor = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_off_cooling_compressor_key)
            switch_on_humidifier = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_humidifier_key)
            switch_off_humidifier = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_off_humidifier_key)
            delay_humidify = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.delay_humidify_key)
            
            # Reifetabelle aus Datenbank
            agingtable = pi_ager_database.read_agingtable_name_from_config()    # Variable agingtable = Name der Reifetabelle
            agingtable = agingtable.lower()
            finaltime = None

            debug_modus = pi_ager_database.get_table_value(pi_ager_names.debug_table, pi_ager_names.loglevel_console_key)
            if debug_modus == 10:
                day_in_seconds = pi_ager_database.get_table_value(pi_ager_names.debug_table, pi_ager_names.agingtable_days_in_seconds_debug_key)  # zum testen Wert aus DB holen: ein Tag vergeht in einer Sekunde
            else:
                day_in_seconds = 86400  #Anzahl der Sek. in einem Tag

            # Hauptprogramm
            
            cl_fact_logger.get_instance().info(pi_ager_names.logspacer)
            logstring = _('the climate values are now controlled by the automatic program') + ' ' + agingtable
            cl_fact_logger.get_instance().info(logstring)
            rows = pi_ager_database.get_agingtable_as_rows(agingtable)
    
            row_number = 0                              # Setzt Variable row_number auf 0
            total_duration = 0                          # Setzt Variable duration auf 0
            dict_agingtable={}
            
            for row in rows:
                total_duration += int(row["days"])                           # errechnet die Gesamtdauer
                dict_agingtable[row_number] = self.get_dictionary_out_of_sqliterow(row)
                row_number += 1                                             # Zeilenanzahl wird hochgezaehlt (fuer Dictionary Nummer und total_periods)
          
            total_periods = row_number - 1
          
            cl_fact_logger.get_instance().debug('total duration (days): ' + str(total_duration))
            cl_fact_logger.get_instance().debug('total periods: ' + str(total_periods + 1))
            
            # make shure not to start with a period out of range
            if (last_period > total_periods):
                last_period = 0
                pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, last_period)
                pi_ager_database.write_current_value(pi_ager_names.agingtable_period_day_key, 1)

            # how many days in last_period
            actual_dictionary = dict_agingtable[last_period] #ausgewaehlte periode aus dem Reifetabellendictionary auswaehlen
            days_in_period = int(actual_dictionary['days'])
            if (last_period_day > days_in_period):
                last_period_day = days_in_period
                pi_ager_database.write_current_value(pi_ager_names.agingtable_period_day_key, last_period_day)
                
            period = last_period         
            start_day = last_period_day
            cl_fact_logger.get_instance().debug('Last period: ' + str(period + 1) + '. Last start_day: ' + str(start_day))
           
            # Start der Reifetabelle ab bestimmter Zeit oder wenn Reifevorgang unterbrochen wurde
            continue_agingtable = False
            
            # Start Reifetabelle vom Anfang
            begin_agingtable = False
            
            if manual_start_day != 1 or manual_start_period != 1: # Wenn ueber das Frontend ein Starttag oder eine Startperiode eingetragen wurde
                if (manual_start_period > (total_periods + 1)):
                    manual_start_period = total_periods + 1
                actual_dictionary = dict_agingtable[manual_start_period - 1] #ausgewaehlte periode aus dem Reifetabellendictionary auswaehlen
                days_in_period = int(actual_dictionary['days'])
                if (manual_start_day > days_in_period):
                    manual_start_day = days_in_period
                duration_sleep = self.get_duration_sleep(days_in_period) #Laufzeit der Periode auslesen
                period_starttime_seconds = pi_ager_database.get_current_time() - (manual_start_day - 1) * day_in_seconds # eigentlicher Startzeitpunkt der Periode zur Berechnung der Sleeptime        
                period = manual_start_period - 1
                start_day = manual_start_day
                cl_fact_logger.get_instance().debug('Manual period: ' + str(period) + '. Manual start_day: ' + str(start_day))
                continue_agingtable = True # Boolean fuer das erkennen der manuellen Startzeit
    
            # Wenn period == 0 und start_day == 1 wird die Reifetabelle neu gestartet, ansonsten an der aktuellen period fortgesetzt
            elif period == 0 and start_day == 1:
                period_starttime_seconds = 0
                duration_sleep = 0
                actual_dictionary = None  # setzt aktuelles Dictionary zurueck
                begin_agingtable = True
                
#            elif not self.continue_after_power_failure(dict_agingtable[period]):
    
                # To Do: ALARM (Piezo) einfügen
#                logstring = 'interruption agingtable' + ' "' + agingtable + '" ' + 'in period ' + str(int(period)) + '!!!'
#                cl_fact_logger.get_instance().critical(logstring)
#                pi_ager_database.write_stop_in_database(pi_ager_names.status_agingtable_key)
#                pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, 0)
#                status_agingtable = 0
                
            else: # setze agingtable fort nach Unterbrechung. Wir machen keine Toleranzprüfung mehr. (Sensorwerte sind im Toleranzbereich, Reifetabelle kann normal fortgesetzt werden)
                duration_sleep = self.get_duration_sleep(days_in_period) # Laufzeit der Periode auslesen
                period_starttime_seconds = pi_ager_database.get_current_time() - (start_day - 1) * day_in_seconds # eigentlicher Startzeitpunkt der Periode zur Berechnung der Sleeptime
                continue_agingtable = True
    
            pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, period)
            pi_ager_database.write_current_value(pi_ager_names.agingtable_period_day_key, start_day) #Tag der Periode in DB schreiben
            old_current_day = start_day
            
            while True:            
                time.sleep(1)
                if self.stop_received:  # Shutdown received
                    break
                    
                status_agingtable = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_agingtable_key)
                if (status_agingtable == 0):    # user turned off aging table
                    pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, 0)
                    pi_ager_database.write_current_value(pi_ager_names.agingtable_period_day_key, 1)
                    break
                    
                current_time = pi_ager_database.get_current_time()
                duration_sleep_left = period_starttime_seconds + duration_sleep - current_time
                
                # check here if first or next period must be activated
                if begin_agingtable or continue_agingtable or (current_time >= period_starttime_seconds + duration_sleep):
                    
                    if period == total_periods + 1:
                        logstring = _('Program') + ' "' + agingtable + '" ' + _('ends the control.') + '\n Pi Ager ' + _('continues to work with the last values.')
                        cl_fact_logger.get_instance().info(logstring)
                        # aging table finished
                        pi_ager_database.write_stop_in_database(pi_ager_names.status_agingtable_key)
                        pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, 0)
                        pi_ager_database.write_current_value(pi_ager_names.agingtable_period_day_key, 1)
                        break
                    
                    pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, period)
                    cl_fact_logger.get_instance().debug('period' + ': ' + str(int(period + 1)))
                    actual_dictionary = dict_agingtable[period]
                    
                    if continue_agingtable: # if start with period within agingtable
                        old_current_day = start_day
                        pi_ager_database.write_current_value(pi_ager_names.agingtable_period_day_key, start_day) #Tag der Periode in DB schreiben
                        logstring = _('start with period ') + str(int(period + 1)) + ' ' + _('of') + ' ' + str(int(total_periods + 1))
                        cl_fact_logger.get_instance().info(logstring)
                        final_time = self.eval_final_time(rows, period, start_day - 1)
                        self.read_dictionary_write_settings(actual_dictionary, start_day - 1)
                        logstring = _('next change of values') + ': ' + period_endtime.strftime('%d.%m.%Y  %H:%M')
                        cl_fact_logger.get_instance().info(logstring)
                        logstring = _('end of program') + ': ' + final_time.strftime('%d.%m.%Y  %H:%M')
                        cl_fact_logger.get_instance().info(logstring) 
                        continue_agingtable = False
                        
                    elif begin_agingtable:   # if start with first period
                        logstring = _('start values period 1 of') + ' ' + str(int(total_periods + 1))
                        cl_fact_logger.get_instance().info(logstring)
                        finaltime = datetime.datetime.now() + datetime.timedelta(days = total_duration)  # days = parameter von datetime.timedelta
                        self.read_dictionary_write_settings(actual_dictionary)
                        logstring = _('next change of values') + ': ' + period_endtime.strftime('%d.%m.%Y  %H:%M')
                        cl_fact_logger.get_instance().info(logstring)
                        logstring = _('end of program') + ': ' + finaltime.strftime('%d.%m.%Y  %H:%M')
                        cl_fact_logger.get_instance().info(logstring)
                        begin_agingtable = False
                        
                    else:   # if any other period != 0
                        logstring = _('new values for period') + ' ' + str(int(period + 1)) + ' ' + _('of') + ' ' + str(int(total_periods + 1))
                        cl_fact_logger.get_instance().info(logstring)
                        self.read_dictionary_write_settings(actual_dictionary)
                        logstring = _('next change of values') + ': ' + (period_endtime.strftime('%d.%m.%Y  %H:%M'))
                        cl_fact_logger.get_instance().info(logstring)
                        
                        if finaltime == None:
                            period_starttime = datetime.datetime.fromtimestamp(period_starttime_seconds)
                            finaltime = period_starttime + datetime.timedelta(days = total_duration)
                            
                        logstring = _('end of program') + ': ' + finaltime.strftime('%d.%m.%Y  %H:%M')
                        cl_fact_logger.get_instance().info(logstring)
                        
                    period += 1
                    cl_fact_logger.get_instance().info(pi_ager_names.logspacer)
                        
                elif (duration_sleep_left % 3600) == 0:    # log every hour
                    cl_fact_logger.get_instance().info(_('in agingtable duration_sleep-loop. duration_sleep left') + ': ' + str(datetime.timedelta(seconds=duration_sleep_left)))
                else:
                    if (duration_sleep_left % 60) == 0:    # log every minute
                        cl_fact_logger.get_instance().debug('in agingtable duration_sleep-loop. duration_sleep left: ' + str(datetime.timedelta(seconds=duration_sleep_left)))
                
                # remaining_days = (period_starttime_seconds + duration_sleep - current_time - 1) // day_in_seconds 
                # current_day = int (actual_dictionary['days']) - remaining_days # aktueller Tag ist Tage aus Reifetabelle - die restlichen Tage
                current_day = (current_time - period_starttime_seconds) // day_in_seconds + 1
                if (current_day != old_current_day):
                    pi_ager_database.write_current_value(pi_ager_names.agingtable_period_day_key, current_day) # Tag der Periode in DB schreiben
                    old_current_day = current_day
                    cl_fact_logger.get_instance().debug('New current day : ' + str(current_day))
                    
#            pi_ager_database.write_stop_in_database(pi_ager_names.status_agingtable_key)
#            pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, 0)
#            pi_ager_database.write_current_value(pi_ager_names.agingtable_period_day_key, 1)
        # sys.exit(0)
        
        
def main():
#    pi_ager_init.set_language()
    aging_thread = cl_aging_thread()
    aging_thread.start()
    
    try:
        while True:
            time.sleep(1)
            
    except KeyboardInterrupt:
        print("Ctrl-c received! Sending Stop to thread...")
        aging_thread.stop_received = True

    aging_thread.join()
    print('finis.')
    
if __name__ == '__main__':
    main()
    