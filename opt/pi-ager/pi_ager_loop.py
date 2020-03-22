#!/usr/bin/python3
"""
    main loop for pi-ager
"""
import os
import subprocess
import stat
import time
import datetime
import Adafruit_DHT
import RPi.GPIO as gpio
import pi_sht1x
import pi_ager_database
import pi_ager_names
import pi_ager_paths
import pi_ager_init
# import pi_ager_logging
# import pi_ager_logging
import pi_ager_gpio_config
import pi_ager_organization
from time import ctime as convert

from main.pi_ager_cx_exception import (cx_i2c_sht_temperature_crc_error, cx_i2c_sht_humidity_crc_error, cx_i2c_bus_error) 
from messenger.pi_ager_cl_alarm import cl_fact_logic_alarm
from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type
from sensors.pi_ager_cl_sensor_fact import cl_fact_main_sensor
from sensors.pi_ager_cl_i2c_bus import  cl_fact_i2c_bus_logic

from main.pi_ager_cl_logger import cl_fact_logger

# global logger
# logger = pi_ager_logging.create_logger(__name__)
# logger.debug('logging initialised')

def autostart_loop():
    """
    starting loop. pi is startet. pi-ager is not startet. waiting for value 1 in database pi-ager-status
    """
    global status_pi_ager
    # global logger 
    try:
        while True:
            status_pi_ager = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key)
            status_agingtable = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_agingtable_key)
            current_agingtable_period = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.agingtable_period_key)
            check_and_set_light()
            
            # logger.debug('autostart_loop ' + time.strftime('%H:%M:%S', time.localtime()))
            cl_fact_logger.get_instance().debug('autostart_loop ' + time.strftime('%H:%M:%S', time.localtime()))
            if status_agingtable == 1:
                os.system('sudo /var/sudowebscript.sh startagingtable &')
                
                doMainLoop()
            elif status_pi_ager == 1:
                doMainLoop()
            cl_fact_logger.get_instance().check_website_logfile()
            time.sleep(5)
    except Exception as cx_error:
        cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
            
def get_sensordata(sht_exception_count, humidity_exception_count, temperature_exception_count, sensordata_exception_count):
    """
    try to read sensordata
    """
    # global logger
    global sensor_humidity_big
    global sensor_temperature_big 
    last_temperaure = None
    last_humidity   = None
    sensordata={}
    sensorname = cl_fact_main_sensor_type.get_instance().get_sensor_type_ui()
    # logger.debug("sensorname: " + str(sensorname))
    # logger.debug("sensortype: " + str(cl_fact_main_sensor_type.get_instance().get_sensor_type()))
    cl_fact_logger.get_instance().debug("sensorname: " + str(sensorname))
    cl_fact_logger.get_instance().debug("sensortype: " + str(cl_fact_main_sensor_type.get_instance().get_sensor_type()))
    
    
    try:
        if sensorname == 'DHT11' or sensorname == 'DHT22' or sensorname == 'AM2302' or sensorname == 'SHT75':
            try:
                main_sensor =  cl_fact_main_sensor().get_instance()
                main_sensor.execute()
                measured_data = main_sensor.get_current_data()
                (sensor_temperature_big, sensor_humidity_big, sensor_dewpoint_big) = measured_data
                cl_fact_logger.get_instance().debug('sensor_temperature_big: ' + str(sensor_temperature_big))
                cl_fact_logger.get_instance().debug('sensor_humidity_big: ' + str(sensor_humidity_big)) 
                cl_fact_logger.get_instance().debug('sensor_dewpoint_big: ' + str(sensor_dewpoint_big))   
            except pi_sht1x.sht1x.SHT1xError as cx_error:
                cl_fact_logic_messenger().get_instance().handle_exception(cx_error)

        elif sensorname == 'SHT3x' or sensorname == 'SHT85': #SH3x
            try:
                i2c_address_main_sensor = 0x44
                main_sensor =  cl_fact_main_sensor().get_instance(i_address = i2c_address_main_sensor)
                main_sensor.execute()
                measured_data = main_sensor.get_current_data()
                (sensor_temperature_big, sensor_humidity_big, sensor_dewpoint_big) = measured_data
                # logger.debug('sensor_temperature_big: ' + str(sensor_temperature_big))
                # logger.debug('sensor_humidity_big: ' + str(sensor_humidity_big)) 
                # logger.debug('sensor_dewpoint_big: ' + str(sensor_dewpoint_big))
                cl_fact_logger.get_instance().debug('sensor_temperature_big: ' + str(sensor_temperature_big))
                cl_fact_logger.get_instance().debug('sensor_humidity_big: ' + str(sensor_humidity_big)) 
                cl_fact_logger.get_instance().debug('sensor_dewpoint_big: ' + str(sensor_dewpoint_big))
                
            except OSError as cx_error:
                cl_fact_i2c_bus_logic().set_instance(None)
                cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
                   
                                
            except (cx_i2c_sht_temperature_crc_error,
                    cx_i2c_sht_humidity_crc_error,
                    cx_i2c_bus_error ) as cx_error:
                cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
        last_temperature = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_temperature_key)
        last_humidity = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_humidity_key)                
        if last_temperaure is not None and last_humidity is not None:
            sensor_temperature = round(sensor_temperature_big,2)
            sensor_humidity = round(sensor_humidity_big,2)
            
        
        elif sensor_humidity_big is not None and sensor_temperature_big is not None: # and last_temperaure is not None and last_humidity is not None:
            #sensor_temperature_big = float(sensor_temperature_big)
            sensor_temperature = round(sensor_temperature_big,2)
            sensor_humidity = round(sensor_humidity_big,2)
            if last_temperature == 0:
                deviation_temperature = sensor_temperature
            else:
                deviation_temperature = abs((sensor_temperature/last_temperature * 100) - 100)
            if last_humidity == 0:
                deviation_humidity = sensor_humidity
            else:
                deviation_humidity = abs((sensor_humidity/last_humidity * 100) - 100)
            
            if sensor_humidity > 100 or deviation_humidity > 20:
                if humidity_exception_count < 10:
                    countup_values = countup('humidity_exception', humidity_exception_count)
                    logstring = countup_values['logstring']
                    humidity_exception_count = countup_values['counter']
                    # logger.debug(logstring)
                    cl_fact_logger.get_instance().debug(logstring)
                    time.sleep(1)
                    recursion = get_sensordata(sht_exception_count, humidity_exception_count, temperature_exception_count, sensordata_exception_count)
                    return recursion
                else:
                    pass
            if sensor_temperature > 60 or deviation_temperature > 20:
                if temperature_exception_count < 10:
                    countup_values = countup('temperature_exception', temperature_exception_count)
                    logstring = countup_values['logstring']
                    temperature_exception_count = countup_values['counter']
                    # logger.debug(logstring)
                    cl_fact_logger.get_instance().debug(logstring)
                    time.sleep(1)
                    recursion = get_sensordata(sht_exception_count, humidity_exception_count, temperature_exception_count, sensordata_exception_count)
                    return recursion
                else:
                    pass
                    
        elif sensordata_exception_count < 10:
            sensor_temperature = None
            sensor_humidity = None
            countup_values = countup('sensordata_exception', sensordata_exception_count)
            logstring = countup_values['logstring']
            sensordata_exception_count = countup_values['counter']
            
            # logger.debug(logstring)
            cl_fact_logger.get_instance().debug(logstring)
            time.sleep(1)
            recursion = get_sensordata(sht_exception_count, humidity_exception_count, temperature_exception_count, sensordata_exception_count)
            return recursion
        
        else:
            sensor_temperature = None
            sensor_humidity = None
            logstring = _('Failed to get sensordata.')
            #logger.warning(logstring)
            cl_fact_logger.get_instance().warning(logstring)
            
       
        sensordata['sensor_temperature'] = sensor_temperature
        sensordata['sensor_humidity'] = sensor_humidity
    except Exception as cx_error:
        cl_fact_logic_messenger().get_instance().handle_exception(cx_error)

    return(sensordata)
    
def countup(countername, counter):
    counter += 1
    if countername == 'sht_exception':
        logstring = 'SHT1xError occured, trying again, current number of retries: '
    elif countername == 'humidity_exception':
        logstring = 'no plausible humidity value [> 100 or too much deviation], trying again, current number of retries: '
    elif countername == 'temperature_exception':
        logstring = 'no plausible temperature value [> 60 or too much deviation], trying again, current number of retries: '
    elif countername == 'sensordata_exception':
        logstring = 'sensordata has NULL values, trying again, current number of retries: '
    else:
        logstring = 'An Error occured'
    logstring = logstring + str(counter)
    
    countresult = {}
    countresult['counter'] = counter
    countresult['logstring'] = logstring
    return countresult
    
def set_gpio_value(gpio_number, value):
    """
    setting gpio value
    """
    gpio.output(gpio_number, value)
    
def get_gpio_value(gpio_number):
    """
    reading gpio value
    """
    value = gpio.input(gpio_number)
    return value
    
def switch_light(relay_state):
    """
    setting gpio for light
    """
    set_gpio_value(pi_ager_gpio_config.gpio_light, relay_state)

def status_light_in_current_values_is_on():
    """
    check for light current value
    """
    try:
        current_value_rows = pi_ager_database.get_current(pi_ager_names.current_values_table, True)
        current_values = {}
        for current_row in current_value_rows:
            current_values[current_row[pi_ager_names.key_field]] = current_row[pi_ager_names.value_field]
        
        if current_values[pi_ager_names.status_light_key] == 1:
            return True
        else:
            return False
    except Exception as cx_error:
        cl_fact_logic_messenger().get_instance(cx_error).send()
        
def check_status_agingtable():
    """
    check status of agingtable
    """
    try:
        status_agingtable = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_agingtable_key)
        process_agingtable = subprocess.getstatusoutput('ps ax | grep -v grep | grep agingtable.py &')
        # (0, '16114 pts/0    R+     0:01 python3 /opt/pi-ager/agingtable.py\n16238 pts/1    S+     0:00 sudo python3 agingtable.py\n16256 pts/1    R+     0:00 python3 agingtable.py')
        # läuft nicht Exitcode 0
        # (1, '')
        # läuft nicht Exitcode 1
        
        if process_agingtable[1] == '':
            process_agingtable_running = False
        else:
            process_agingtable_running = True
        if status_agingtable == 1 and process_agingtable_running == False:
            os.system('sudo /var/sudowebscript.sh startagingtable &')
    except Exception as cx_error:
        cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
def check_and_set_light():
    """
    manual light switch
    """
    try:
        #   Manueller "Lichtschalter"
        if pi_ager_database.get_status_light_manual() == 1:
            duration_light_on = pi_ager_database.get_current_time() - pi_ager_database.get_last_change(pi_ager_names.current_values_table, pi_ager_names.status_light_manual_key)
            if duration_light_on <= 600:    #   manuelles Licht ist keine 10 Minuten an
                if get_gpio_value(pi_ager_gpio_config.gpio_light) == pi_ager_names.relay_off:
                    switch_light(pi_ager_names.relay_on)
            else:
                pi_ager_database.write_stop_in_database(pi_ager_names.status_light_manual_key)
        elif get_gpio_value(pi_ager_gpio_config.gpio_light) == pi_ager_names.relay_on and not status_light_in_current_values_is_on():
            switch_light(pi_ager_names.relay_off)
    except Exception as cx_error:
        cl_fact_logic_messenger().get_instance().handle_exception(cx_error)

def status_value_has_changed():
    """
    check if value has changed
    """
    global status_circulating_air         #  Umluft
    global status_exhaust_air             #  (Abluft-)Luftaustausch
    global status_heater                  #  Heizung
    global status_cooling_compressor      #  Kuehlung
    global status_humidifier              #  Luftbefeuchtung
    global status_dehumidifier            #  Entfeuchter
    global status_uv                      #  UV-Licht
    global status_light                   #  Licht
    
    current_value_rows = pi_ager_database.get_current(pi_ager_names.current_values_table, True)
    current_values = {}
    for current_row in current_value_rows:
        current_values[current_row[pi_ager_names.key_field]] = current_row[pi_ager_names.value_field]
    
    if (status_circulating_air != current_values[pi_ager_names.status_circulating_air_key]
        or status_exhaust_air != current_values[pi_ager_names.status_exhaust_air_key]
        or status_heater != current_values[pi_ager_names.status_heater_key]
        or status_cooling_compressor != current_values[pi_ager_names.status_cooling_compressor_key]
        or status_humidifier != current_values[pi_ager_names.status_humidifier_key]
        or status_dehumidifier != current_values[pi_ager_names.status_dehumidifier_key]
        or status_uv != current_values[pi_ager_names.status_uv_key]
        or status_light != current_values[pi_ager_names.status_light_key]
        or pi_ager_init.loopcounter == 0):
        return True
    else:
        return False


def doMainLoop():
    """
    mainloop, pi-ager is running
    """
    global circulation_air_duration       #  Umluftdauer
    global circulation_air_period         #  Umluftperiode
    global exhaust_air_duration           #  (Abluft-)luftaustauschdauer
    global exhaust_air_period             #  (Abluft-)luftaustauschperiode
    global sensor_temperature             #  Gemessene Temperatur am Sensor
    global sensor_humidity                #  Gemessene Feuchtigkeit am Sensor
    global switch_on_cooling_compressor   #  Einschalttemperatur
    global switch_off_cooling_compressor  #  Ausschalttemperatur
    global switch_on_humidifier           #  Einschaltfeuchte
    global switch_off_humidifier          #  Ausschaltfeuchte
    global settings
    global status_circulating_air         #  Umluft
    global status_exhaust_air             #  (Abluft-)Luftaustausch
    global status_heater                  #  Heizung
    global status_cooling_compressor      #  Kuehlung
    global status_humidifier              #  Luftbefeuchtung
    #global counter_humidify               #  Zaehler Verzoegerung der Luftbefeuchtung
    #counter_humidify = 0
    global delay_humidify                 #  Luftbefeuchtungsverzoegerung
    global status_exhaust_fan             #  Variable fuer die "Evakuierung" zur Feuchtereduzierung durch (Abluft-)Luftaustausch
    global uv_modus                       #  Modus UV-Licht  (1 = Periode/Dauer; 2= Zeitstempel/Dauer)
    global status_uv                      #  UV-Licht
    global switch_on_uv_hour              #  Stunde wann das UV Licht angeschaltet werden soll
    global switch_on_uv_minute            #  Minute wann das UV Licht ausgeschaltet werden soll
    global uv_duration                    #  Dauer der UV_Belichtung
    global uv_period                      #  Periode für UV_Licht
    global light_modus                    #  ModusLicht  (1 = Periode/Dauer; 2= Zeitstempel/Dauer)
    global status_light                   #  Licht
    global switch_on_light_hour           #  Stunde wann Licht angeschaltet werden soll
    global switch_on_light_minute         #  Minute wann das Licht ausgeschaltet werden soll
    global light_duration                 #  Dauer für Licht
    global light_period                   #  Periode für Licht
    global light_stoptime                 #  Unix-Zeitstempel fuer den Stop des UV-Light
    global dehumidifier_modus             #  Modus Entfeuchter  (1 = über Abluft, 2 = mit Abluft zusammen [unterstützend]; 3 = anstelle von Abluft)
    global status_dehumidifier            #  Entfeuchter
    global status_pi_ager
    # global logger

    # Pruefen Sensor, dann Settings einlesen

    pi_ager_database.write_start_in_database(pi_ager_names.status_pi_ager_key)
    status_pi_ager = 1
    count_continuing_emergency_loops = 0
    humidify_delay_switch = False
    status_exhaust_fan = False
    status_exhaust_air = False
    
    #Here get instance of Deviation class
    cl_fact_logger.get_instance().debug('doMainLoop()')
    # Here init uv_period and light_period to detect change in loop
    uv_period = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_period_key))
    light_period = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.light_period_key))
    uv_modus = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_modus_key))
    light_modus = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.light_modus_key))  
          
    try:
        while status_pi_ager == 1:
            #Here check Deviation of measurement
            check_and_set_light()
            check_status_agingtable()
            status_pi_ager = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key)
    
    #Settings
            #Sensor
            sht_exception_count = 0
            humidity_exception_count = 0
            sensordata_exception_count = 0
            temperature_exception_count = 0
            #sensortype = int(pi_ager_init.sensortype)
            sensordata = []
            sensortype = cl_fact_main_sensor_type.get_instance().get_sensor_type()
            sensordata = get_sensordata(sht_exception_count, humidity_exception_count, temperature_exception_count, sensordata_exception_count)
            sensor_temperature = sensordata['sensor_temperature']
            sensor_humidity = sensordata['sensor_humidity']
            # logger.debug("sensor_temperature = " + str(sensor_temperature))
            # logger.debug("sensor_humidity    = " + str(sensor_humidity))
            cl_fact_logger.get_instance().debug("sensor_temperature = " + str(sensor_temperature))
            cl_fact_logger.get_instance().debug("sensor_humidity    = " + str(sensor_humidity))
            # Prüfen, ob Sensordaten empfangen wurden und falls nicht, auf Notfallmodus wechseln
            if sensor_temperature != None and sensor_humidity != None:
                count_continuing_emergency_loops = 0
                #weitere Settings
                modus = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.modus_key)
                setpoint_temperature = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_temperature_key))
                setpoint_humidity = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_humidity_key))
                circulation_air_period = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.circulation_air_period_key))
                # logger.debug("circulation_air_period = " + str(circulation_air_period))
                cl_fact_logger.get_instance().debug("circulation_air_period = " + str(circulation_air_period))
                circulation_air_duration = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.circulation_air_duration_key))
                # logger.debug("circulation_air_duration = "+ str(circulation_air_duration))
                cl_fact_logger.get_instance().debug("circulation_air_duration = "+ str(circulation_air_duration))
                exhaust_air_period = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.exhaust_air_period_key))
                exhaust_air_duration = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.exhaust_air_duration_key))
                switch_on_cooling_compressor = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_cooling_compressor_key))
                switch_off_cooling_compressor = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_off_cooling_compressor_key))
                switch_on_humidifier = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_humidifier_key))
                switch_off_humidifier = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_off_humidifier_key))
                delay_humidify = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.delay_humidify_key))
                delay_humidify = delay_humidify * 60
                uv_modus_temp = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_modus_key))
                switch_on_uv_hour = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_uv_hour_key))
                switch_on_uv_minute = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_uv_minute_key))
                uv_duration = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_duration_key))
                # check if uv_period changed
                uv_period_temp = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_period_key))
                if (uv_period_temp != uv_period or uv_modus_temp != uv_modus):
                    current_time = pi_ager_database.get_current_time()
                    uv_period = uv_period_temp
                    uv_modus = uv_modus_temp
                    pi_ager_init.uv_starttime = current_time
                    pi_ager_init.uv_stoptime = pi_ager_init.uv_starttime + uv_duration
                    
                light_modus_temp = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.light_modus_key))
                switch_on_light_hour = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_light_hour_key))
                switch_on_light_minute = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_light_minute_key))
                light_duration = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.light_duration_key))
                # check if light_period changed
                light_period_temp = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.light_period_key))                
                if (light_period_temp != light_period or light_modus_temp != light_modus):
                    current_time = pi_ager_database.get_current_time()
                    light_period = light_period_temp
                    light_modus = light_modus_temp
                    pi_ager_init.light_starttime = current_time
                    pi_ager_init.light_stoptime = pi_ager_init.light_starttime + light_duration
                    
                dehumidifier_modus = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.dehumidifier_modus_key))
    
                # An dieser Stelle sind alle settings eingelesen, Ausgabe auf Konsole
                os.system('clear') # Clears the terminal
                current_time = pi_ager_database.get_current_time()
                # logger.info(pi_ager_names.logspacer)
                logstring = ' \n ' + pi_ager_names.logspacer
                logstring = logstring + ' \n ' + 'Main loop/Unix-Timestamp: (' + str(current_time)+ ')'
                cl_fact_logger.get_instance().debug(logstring)
                logstring = logstring + ' \n ' + pi_ager_names.logspacer2
                cl_fact_logger.get_instance().debug(logstring)
                logstring = logstring + ' \n ' + _('target temperature') + ': ' + str(setpoint_temperature) + ' C'
                cl_fact_logger.get_instance().debug(logstring)
                logstring = logstring + ' \n ' +  _('actual temperature') + ': ' + str(sensor_temperature) + ' C'
                cl_fact_logger.get_instance().debug(logstring)
                # logger.info(pi_ager_names.logspacer2)
                logstring = logstring + ' \n ' + pi_ager_names.logspacer2
                logstring = logstring + ' \n ' +  _('target humidity') + ': ' + str(setpoint_humidity) + '%'
                # logger.info(logstring)
                logstring = logstring + ' \n ' +  _('actual humidity') + ': ' + str(sensor_humidity) + '%'
                cl_fact_logger.get_instance().debug(logstring)
                # logger.info(pi_ager_names.logspacer2)
                #logstring = logstring + ' \n ' + pi_ager_names.logspacer2
                logstring = logstring + ' \n ' +  _('selected sensor') + ': ' + str(cl_fact_main_sensor_type.get_instance().get_sensor_type_ui())
                cl_fact_logger.get_instance().debug(logstring)
                # logstring = _('value in database') + ': ' + str(sensortype)
                cl_fact_logger.get_instance().debug(_('value in database') + ': ' + str(sensortype))
                logstring = logstring + ' \n ' + pi_ager_names.logspacer2
                # logger.info(pi_ager_names.logspacer2)
    
                # gpio.setmode(pi_ager_names.board_mode)
                
                # Durch den folgenden Timer laeuft der Ventilator in den vorgegebenen Intervallen zusaetzlich zur generellen Umluft bei aktivem Heizen, Kuehlen oder Befeuchten
                # Timer fuer Luftumwaelzung-Ventilator
                if circulation_air_period == 0:                          # gleich 0 ist an,  Dauer-Timer
                    status_circulation_air = True
                if circulation_air_duration == 0:                        # gleich 0 ist aus, kein Timer
                    status_circulation_air = False
                if circulation_air_duration > 0:
                    if current_time < pi_ager_init.circulation_air_start + circulation_air_period:
                        status_circulation_air = False                       # Umluft - Ventilator aus
                        logstring = logstring + ' \n ' +  _('circulation air timer active') + ' (' + _('fan off') +')'
                        cl_fact_logger.get_instance().debug(logstring)
                    if current_time >= pi_ager_init.circulation_air_start + circulation_air_period:
                        status_circulation_air = True                      # Umluft - Ventilator an
                        logstring = logstring + ' \n ' +  _('circulation air timer active') + ' (' + _('fan on') +')'
                        cl_fact_logger.get_instance().debug(logstring)
                    if current_time >= pi_ager_init.circulation_air_start + circulation_air_period + circulation_air_duration:
                        pi_ager_init.circulation_air_start = int(time.time())    # Timer-Timestamp aktualisiert
                # Timer fuer (Abluft-)Luftaustausch-Ventilator
                if exhaust_air_period == 0:                      # gleich 0 ist an,  Dauer-Timer
                    status_exhaust_air = True
                if exhaust_air_duration == 0:                        # gleich 0 ist aus, kein Timer
                    status_exhaust_air = False
                if exhaust_air_duration > 0:                        # gleich 0 ist aus, kein Timer
                    if current_time < pi_ager_init.exhaust_air_start + exhaust_air_period:
                        status_exhaust_air = False                      # (Abluft-)Luftaustausch-Ventilator aus
                        logstring = logstring + ' \n ' +  _('exhaust air timer active') + ' (' + _('fan off') +')'
                        cl_fact_logger.get_instance().debug(logstring)
                    if current_time >= pi_ager_init.exhaust_air_start + exhaust_air_period:
                        status_exhaust_air = True                     # (Abluft-)Luftaustausch-Ventilator an
                        logstring = logstring + ' \n ' +  _('exhaust air timer active') + ' (' + _('fan on') +')'
                        cl_fact_logger.get_instance().debug(logstring)
                    if current_time >= pi_ager_init.exhaust_air_start + exhaust_air_period + exhaust_air_duration:
                        pi_ager_init.exhaust_air_start = int(time.time())   # Timer-Timestamp aktualisiert
                # Timer fuer UV-Licht
                if uv_modus == 0:                         # Modus 0 UV-Licht aus
                    status_uv = False                      # UV-Licht aus
                    logstring = logstring + ' \n ' +  _('modus uv-light') + ': ' + _('off')
                    cl_fact_logger.get_instance().debug(logstring)
                
                if uv_modus == 1:                            # Modus 1 = Periode/Dauer
                    logstring = logstring + ' \n ' +  _('modus uv-light') + ': ' + _('on')
                    if uv_period == 0:                      # gleich 0 ist an,  Dauer-Timer
                        status_uv = True
                    if uv_duration == 0:                        # gleich 0 ist aus, kein Timer
                        status_uv = False
                    if uv_duration > 0:                        # gleich 0 ist aus, kein Timer                
                        if pi_ager_init.uv_stoptime == pi_ager_init.system_starttime:
                            pi_ager_init.uv_stoptime = pi_ager_init.uv_starttime + uv_duration
                            status_uv = True
                        if current_time >= pi_ager_init.uv_starttime and current_time <= pi_ager_init.uv_stoptime:
                            status_uv = True                     # UV-Licht an
                            logstring = logstring + ' \n ' +  _('uv-light timer active') + ' (' + _('uv-light on') +')'
                            cl_fact_logger.get_instance().debug(logstring)

                            cl_fact_logger.get_instance().debug('UV-Licht Startzeit: ' + convert(pi_ager_init.uv_starttime))
                            cl_fact_logger.get_instance().debug('UV-Licht Stoppzeit: ' + convert(pi_ager_init.uv_stoptime))
                            #logger.debug('UV-Licht Startzeit: ' + pi_ager_init.uv_starttime.strftime('%d %B %Y %H:%M:%S'))
                            #logger.debug('UV-Licht Stoppzeit: ' + pi_ager_init.uv_stoptime.strftime('%Y-%m-%d %H:%M:%S'))
                            # logger.debug('UV-Licht duration: ' + str(uv_duration))
                            cl_fact_logger.get_instance().debug('UV-Licht duration: ' + str(uv_duration))
                        else: 
                            status_uv = False                      # UV-Licht aus
                            logstring = logstring + ' \n ' +  _('uv-light timer active') + ' (' + _('uv-light off') +')'
                            # logger.info(logstring)
                            # logger.debug('UV-Licht Stoppzeit: ' + convert(pi_ager_init.uv_stoptime))
                            # logger.debug('UV-Licht Startzeit: ' + convert(pi_ager_init.uv_starttime))
                            # logger.debug('UV-Licht period: ' + str(uv_period))
                            cl_fact_logger.get_instance().debug('UV-Licht Stoppzeit: ' + convert(pi_ager_init.uv_stoptime))
                            cl_fact_logger.get_instance().debug('UV-Licht Startzeit: ' + convert(pi_ager_init.uv_starttime))
                            cl_fact_logger.get_instance().debug('UV-Licht period: ' + str(uv_period))
    
                        if current_time > pi_ager_init.uv_stoptime:
                            pi_ager_init.uv_starttime = pi_ager_init.uv_starttime + uv_period  # Timer-Timestamp aktualisiert
                            pi_ager_init.uv_stoptime = pi_ager_init.uv_starttime + uv_duration
    
                if uv_modus == 2:                         # Modus 2 Zeitstempel/Dauer
                    now = datetime.datetime.now()
                    year_now = now.year
                    month_now = now.month
                    day_now = now.day
    
                    pi_ager_init.uv_starttime = datetime.datetime(year_now, month_now, day_now, switch_on_uv_hour, switch_on_uv_minute, 0, 0)
                    pi_ager_init.uv_stoptime = pi_ager_init.uv_starttime + datetime.timedelta(0, uv_duration)
                    cl_fact_logger.get_instance().debug(pi_ager_init.uv_starttime)
                    cl_fact_logger.get_instance().debug(pi_ager_init.uv_stoptime)
                    #cl_fact_logger.get_instance().debug(pi_ager_init.uv_starttime)
                    #cl_fact_logger.get_instance().debug(pi_ager_init.uv_stoptime)
    
                    if now >= pi_ager_init.uv_starttime and now <= pi_ager_init.uv_stoptime:
                        status_uv = True                     # UV-Licht an
                        logstring = logstring + ' \n ' +  _('uv-light timestamp active') + ' (' + _('uv-light on') +')'
                        # logger.info(logstring)
                    else: 
                        status_uv = False                      # UV-Licht aus
                        logstring = logstring + ' \n ' +  _('uv-light timestamp active') + ' (' + _('uv-light off') +')'
                        # logger.info(logstring)
    
                # Timer fuer Licht
                if light_modus == 0:                         # Modus 0 Licht aus
                    status_light = False                      # Licht aus
                    logstring = logstring + ' \n ' +  _('modus light') + ': ' + _('off')
                    # logger.info(logstring)
                
                if light_modus == 1:                            # Modus 1 = Periode/Dauer
                    if light_period == 0:                      # gleich 0 ist an,  Dauer-Timer
                        status_light = True
                    if light_duration == 0:                        # gleich 0 ist aus, kein Timer
                        status_light = False
                    if light_duration > 0:                        # es ist eine Dauer eingetragen                
                        if pi_ager_init.light_stoptime == pi_ager_init.system_starttime:
                            pi_ager_init.light_stoptime = pi_ager_init.light_starttime + light_duration
                            status_light = True
                        if current_time >= pi_ager_init.light_starttime and current_time <= pi_ager_init.light_stoptime:
                            if not status_light == True:
                                status_light = True                     # Licht an
                            logstring = logstring + ' \n ' +  _('light timer active') + ' (' + _('light on') +')'
                            # logger.info(logstring)
                            # logger.debug('Licht Startzeit: ' + str(pi_ager_init.light_starttime))
                            # logger.debug('Licht Stoppzeit: ' + str(pi_ager_init.light_stoptime))
                            # logger.debug('Licht duration: ' + str(light_duration))
                            cl_fact_logger.get_instance().debug('Licht Startzeit: ' + str(pi_ager_init.light_starttime))
                            cl_fact_logger.get_instance().debug('Licht Stoppzeit: ' + str(pi_ager_init.light_stoptime))
                            cl_fact_logger.get_instance().debug('Licht duration: ' + str(light_duration))
                        else: 
                            status_light = False                      # Licht aus
                            logstring = logstring + ' \n ' +  _('light timer active') + ' (' + _('light off') +')'
                            cl_fact_logger.get_instance().debug(logstring)
                            # logger.debug('Licht Stoppzeit: ' + str(pi_ager_init.light_stoptime))
                            # logger.debug('Licht Startzeit: ' + str(pi_ager_init.light_starttime))
                            # logger.debug('Licht period: ' + str(light_period))
                            cl_fact_logger.get_instance().debug('Licht Stoppzeit: ' + str(pi_ager_init.light_stoptime))
                            cl_fact_logger.get_instance().debug('Licht Startzeit: ' + str(pi_ager_init.light_starttime))
                            cl_fact_logger.get_instance().debug('Licht period: ' + str(light_period))
    
                        if current_time > pi_ager_init.light_stoptime:
                            pi_ager_init.light_starttime = pi_ager_init.light_starttime + light_period  # Timer-Timestamp aktualisiert
                            pi_ager_init.light_stoptime = pi_ager_init.light_starttime + light_duration
    
                if light_modus == 2:                         # Modus 2 Zeitstempel/Dauer
                    now = datetime.datetime.now()
                    year_now = now.year
                    month_now = now.month
                    day_now = now.day
    
                    pi_ager_init.light_starttime = datetime.datetime(year_now, month_now, day_now, switch_on_light_hour, switch_on_light_minute, 0, 0)
                    pi_ager_init.light_stoptime = pi_ager_init.light_starttime + datetime.timedelta(0, light_duration)
    
                    if now >= pi_ager_init.light_starttime and now <= pi_ager_init.light_stoptime:
                            status_light = True                     # Licht an
                            logstring = logstring + ' \n ' +  _('light timestamp active') + ' (' + _('light on') +')'
                            cl_fact_logger.get_instance().debug(logstring)
                    else: 
                        status_light = False                      # Licht aus
                        logstring = logstring + ' \n ' +  _('light timestamp active') + ' (' + _('light off') +')'
                        cl_fact_logger.get_instance().debug(logstring)
    
                # Kuehlen
                if modus == 0:
                    status_exhaust_fan = False                              # Feuchtereduzierung Abluft aus
                    status_dehumidifier = False        # Entfeuchter aus
                    gpio.output(pi_ager_gpio_config.gpio_heater, pi_ager_names.relay_off)                     # Heizung aus
                    gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_off)                 # Befeuchtung aus
                    if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                        gpio.output(pi_ager_gpio_config.gpio_cooling_compressor, pi_ager_names.relay_on)      # Kuehlung ein
                    if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor :
                        gpio.output(pi_ager_gpio_config.gpio_cooling_compressor, pi_ager_names.relay_off)     # Kuehlung aus
                
                # Kuehlen mit Befeuchtung
                if modus == 1:
                    status_exhaust_fan = False                     # Feuchtereduzierung Abluft aus
                    status_dehumidifier = False        # Entfeuchter aus
                    gpio.output(pi_ager_gpio_config.gpio_heater, pi_ager_names.relay_off)      # Heizung aus
                    if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                        gpio.output(pi_ager_gpio_config.gpio_cooling_compressor, pi_ager_names.relay_on)     # Kuehlung ein
                    if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor :
                        gpio.output(pi_ager_gpio_config.gpio_cooling_compressor, pi_ager_names.relay_off)    # Kuehlung aus
                    #if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                    #    gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_on)      # Befeuchtung ein
                    #if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                    #    gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_off)     # Befeuchtung aus
                    if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                        if not humidify_delay_switch:
                            humidify_delay_switch = True
                            humidify_delay_starttime = pi_ager_database.get_current_time()
                        if pi_ager_database.get_current_time() >= humidify_delay_starttime + delay_humidify:      # Verzoegerung der Luftbefeuchtung
                            gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_on)  # Luftbefeuchter ein
                    if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                        gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_off)     # Luftbefeuchter aus
                        humidify_delay_switch = False
           
                # Heizen mit Befeuchtung
                if modus == 2:
                    status_exhaust_fan = False                     # Feuchtereduzierung Abluft aus
                    status_dehumidifier = False        # Entfeuchter aus
                    gpio.output(pi_ager_gpio_config.gpio_cooling_compressor, pi_ager_names.relay_off)        # Kuehlung aus
                    if sensor_temperature <= setpoint_temperature - switch_on_cooling_compressor:
                        gpio.output(pi_ager_gpio_config.gpio_heater, pi_ager_names.relay_on)   # Heizung ein
                    if sensor_temperature >= setpoint_temperature - switch_off_cooling_compressor:
                        gpio.output(pi_ager_gpio_config.gpio_heater, pi_ager_names.relay_off)  # Heizung aus
                    #if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                    #    gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_on)      # Befeuchtung ein
                    #if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                    #    gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_off)     # Befeuchtung aus
                    if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                        if not humidify_delay_switch:
                            humidify_delay_switch = True
                            humidify_delay_starttime = pi_ager_database.get_current_time()
                        if pi_ager_database.get_current_time() >= humidify_delay_starttime + delay_humidify:      # Verzoegerung der Luftbefeuchtung
                            gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_on)  # Luftbefeuchter ein
                    if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                        gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_off)     # Luftbefeuchter aus
                        humidify_delay_switch = False
                           
                # Automatiktemperatur mit Befeuchtung
                if modus == 3:
                    status_exhaust_fan = False                     # Feuchtereduzierung Abluft aus
                    status_dehumidifier = False        # Entfeuchter aus
                    if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                        gpio.output(pi_ager_gpio_config.gpio_cooling_compressor, pi_ager_names.relay_on)     # Kuehlung ein
                    if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor:
                        gpio.output(pi_ager_gpio_config.gpio_cooling_compressor, pi_ager_names.relay_off)    # Kuehlung aus
                    if sensor_temperature <= setpoint_temperature - switch_on_cooling_compressor:
                        gpio.output(pi_ager_gpio_config.gpio_heater, pi_ager_names.relay_on)   # Heizung ein
                    if sensor_temperature >= setpoint_temperature - switch_off_cooling_compressor:
                        gpio.output(pi_ager_gpio_config.gpio_heater, pi_ager_names.relay_off)  # Heizung aus
                    #if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                    #    gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_on)      # Befeuchtung ein
                    #if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                    #    gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_off)     # Befeuchtung aus
                    if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                        if not humidify_delay_switch:
                            humidify_delay_switch = True
                            humidify_delay_starttime = pi_ager_database.get_current_time()
                        if pi_ager_database.get_current_time() >= humidify_delay_starttime + delay_humidify:      # Verzoegerung der Luftbefeuchtung
                            gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_on)  # Luftbefeuchter ein
                    if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                        gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_off)     # Luftbefeuchter aus
                        humidify_delay_switch = False
           
                    # logger.info(_('dehumidifier_modus') + ': ' + str(dehumidifier_modus))
                    # logger.info(_('sensor_humidity') + ': ' + str(sensor_humidity))
                    # logger.info(_('setpoint_humidity') + ': ' + str(setpoint_humidity))
                    # logger.info(_('switch_on_humidifier') + ': ' + str(switch_on_humidifier))
                    # logger.info(_('status_dehumidifier') + ': ' + str(status_dehumidifier))
    
                # Automatik mit Befeuchtung und Entfeuchtung durch (Abluft-)Luftaustausch
                if modus == 4:
                    database_value_status_exhaust_fan = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_exhaust_air_key))
                    if database_value_status_exhaust_fan == 0:
                        status_exhaust_fan = False                                   # Abluft ist aktuell aus
                    else:
                        status_exhaust_fan = True                                   # Abluft ist aktuell an
                    if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                        gpio.output(pi_ager_gpio_config.gpio_cooling_compressor, pi_ager_names.relay_on)     # Kuehlung ein
                    if sensor_temperature <= setpoint_temperature - switch_on_cooling_compressor:
                        gpio.output(pi_ager_gpio_config.gpio_heater, pi_ager_names.relay_on)   # Heizung ein
                    if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor:
                        gpio.output(pi_ager_gpio_config.gpio_cooling_compressor, pi_ager_names.relay_off)    # Kuehlung aus
                    if sensor_temperature >= setpoint_temperature - switch_off_cooling_compressor:
                        gpio.output(pi_ager_gpio_config.gpio_heater, pi_ager_names.relay_off)  # Heizung aus
                    if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                        if not humidify_delay_switch:
                            humidify_delay_switch = True
                            humidify_delay_starttime = pi_ager_database.get_current_time()
                        if pi_ager_database.get_current_time() >= humidify_delay_starttime + delay_humidify:      # Verzoegerung der Luftbefeuchtung
                            gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_on)  # Luftbefeuchter ein
                    if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                        gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_off)     # Luftbefeuchter aus
                        humidify_delay_switch = False
                    cl_fact_logger.get_instance().debug(_('Sensor humidity =') + ': ' + str(sensor_humidity))
                    cl_fact_logger.get_instance().debug(_('Setpoint humidity =') + ': ' + str(setpoint_humidity))
                    cl_fact_logger.get_instance().debug(_('Switch on humidifier =') + ': ' + str(switch_on_humidifier))
                    
                    if sensor_humidity >= setpoint_humidity + switch_on_humidifier:
                        cl_fact_logger.get_instance().debug(_('Dehumidifier modus =') + ': ' + str(dehumidifier_modus))
                        if dehumidifier_modus == 1 or dehumidifier_modus == 2:  # entweder nur über Abluft oder mit unterstützung von Entfeuchter
                            cl_fact_logger.get_instance().debug(_('Exhaust fan = True'))
                            status_exhaust_fan = True                           # Feuchtereduzierung Abluft-Ventilator ein
                            if dehumidifier_modus == 2:                         # Entfeuchter zur Unterstützung
                                cl_fact_logger.get_instance().debug(_('Exhaust fan = True'))
                                status_dehumidifier = True                      # Entfeuchter unterstützend ein
                            else:
                                cl_fact_logger.get_instance().debug(_('Exhaust fan = False'))
                                status_dehumidifier = False                     # Entfeuchter aus
                        if dehumidifier_modus == 3:                             # rein über entfeuchtung
                            status_exhaust_fan = False                          # Abluft aus
                            status_dehumidifier = True                          # Entfeuchter ein
                    if sensor_humidity <= setpoint_humidity + switch_off_humidifier:
                        if dehumidifier_modus == 1 or dehumidifier_modus == 2:
                            status_exhaust_fan = False         # Feuchtereduzierung Abluft-Ventilator aus
                            status_dehumidifier = False        # Entfeuchter aus
                        else:
                            status_dehumidifier = False        # Entfeuchter aus
                
                # Schalten des Umluft - Ventilators
                if gpio.input(pi_ager_gpio_config.gpio_heater) or gpio.input(pi_ager_gpio_config.gpio_cooling_compressor) or gpio.input(pi_ager_gpio_config.gpio_humidifier) or status_circulation_air == True:
                    gpio.output(pi_ager_gpio_config.gpio_circulating_air, pi_ager_names.relay_on)               # Umluft - Ventilator an
                if gpio.input(pi_ager_gpio_config.gpio_heater) and gpio.input(pi_ager_gpio_config.gpio_cooling_compressor) and gpio.input(pi_ager_gpio_config.gpio_humidifier) and status_circulation_air == False:
                    gpio.output(pi_ager_gpio_config.gpio_circulating_air, pi_ager_names.relay_off)             # Umluft - Ventilator aus
                
                # Schalten des Entfeuchters
                if status_dehumidifier == True:
                    gpio.output(pi_ager_gpio_config.gpio_dehumidifier, pi_ager_names.relay_on)
                if status_dehumidifier == False:
                    gpio.output(pi_ager_gpio_config.gpio_dehumidifier, pi_ager_names.relay_off)
                
                # Schalten des (Abluft-)Luftaustausch-Ventilator
                cl_fact_logger.get_instance().debug("status_exhaust_air: ")
                cl_fact_logger.get_instance().debug("%r" % status_exhaust_air)
                
                #cl_fact_logger.get_instance().debug("status_exhaust_air =")
                #cl_fact_logger.get_instance().debug(status_exhaust_air + '%s' %(value or ''))
                #cl_fact_logger.get_instance().debug(_('status_exhaust_fan =') + (status_exhaust_air + '%s' %(value or '')))
                
                #cl_fact_logger.get_instance().debug('status_exhaust_air = ' + status_exhaust_air)
                #cl_fact_logger.get_instance().debug('status_exhaust_fan = ' + status_exhaust_air)
                
                if status_exhaust_air == True or status_exhaust_fan == True:
                    gpio.output(pi_ager_gpio_config.gpio_exhausting_air, pi_ager_names.relay_on)
                if status_exhaust_air == False and status_exhaust_fan == False:
                    gpio.output(pi_ager_gpio_config.gpio_exhausting_air, pi_ager_names.relay_off)
                
                # Schalten des UV_Licht
                if status_uv == True and pi_ager_database.get_status_uv_manual() == 1:
                    gpio.output(pi_ager_gpio_config.gpio_uv, pi_ager_names.relay_on)
    
                if status_uv == False or pi_ager_database.get_status_uv_manual() == 0:
                    gpio.output(pi_ager_gpio_config.gpio_uv, pi_ager_names.relay_off)
                
                # Schalten des Licht
                if status_light == True:
                    switch_light(pi_ager_names.relay_on)
                    # gpio.output(pi_ager_names.gpio_light, pi_ager_names.relay_on)
                if status_light == False and pi_ager_database.get_status_light_manual() == 0:
                    switch_light(pi_ager_names.relay_off)
                    # gpio.output(pi_ager_names.gpio_light, pi_ager_names.relay_off)
                
                # Lesen der Scales Daten
                scale1_row = pi_ager_database.get_scale_table_row(pi_ager_names.data_scale1_table)
                if scale1_row == None:
                    scale1_data = 0
                else:
                    scale1_value = scale1_row[pi_ager_names.value_field]
                    scale1_last_change = scale1_row[pi_ager_names.last_change_field]
                    timediff_scale1 = pi_ager_database.get_current_time() - scale1_last_change
                    if timediff_scale1 < pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table,pi_ager_names.scale_measuring_interval_key):
                        scale1_data = scale1_value
                    else:
                        scale1_data = 0
    
                scale2_row = pi_ager_database.get_scale_table_row(pi_ager_names.data_scale2_table)
                if scale2_row == None:
                    scale2_data = 0
                else:
                    scale2_value = scale2_row[pi_ager_names.value_field]
                    scale2_last_change = scale2_row[pi_ager_names.last_change_field]
                    timediff_scale2 = pi_ager_database.get_current_time() - scale2_last_change
                    if timediff_scale2 < pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table,pi_ager_names.scale_measuring_interval_key):
                        scale2_data = scale2_value
                    else:
                        scale2_data = 0
                
                # Ausgabe der Werte auf der Konsole
                # logger.info(pi_ager_names.logspacer2)
                logstring = logstring + ' \n ' + pi_ager_names.logspacer2
                if gpio.input(pi_ager_gpio_config.gpio_heater) == False:
                    logstring = logstring + ' \n ' +  _('heater') + ' ' + _('on')
                    # logger.info(logstring)
                    status_heater = 1
                else:
                    logstring = logstring + ' \n ' +  _('heater') + ' ' + _('off')
                    # logger.info(logstring)
                    status_heater = 0
                if gpio.input(pi_ager_gpio_config.gpio_cooling_compressor) == False:
                    logstring = logstring + ' \n ' +  _('cooling compressor') + ' ' + _('on')
                    # logger.info(logstring)
                    status_cooling_compressor = 1
                else:
                    logstring = logstring + ' \n ' +  _('cooling compressor') + ' ' + _('off')
                    # logger.info(logstring)
                    status_cooling_compressor = 0
                if gpio.input(pi_ager_gpio_config.gpio_humidifier) == False:
                    logstring = logstring + ' \n ' +  _('humidifier') + ' ' + _('on')
                    # logger.info(logstring)
                    status_humidifier = 1
                else:
                    logstring = logstring + ' \n ' +  _('humidifier') + ' ' + _('off')
                    # logger.info(logstring)
                    status_humidifier = 0
                if gpio.input(pi_ager_gpio_config.gpio_circulating_air) == False:
                    logstring = logstring + ' \n ' +  _('circulation air') + ' ' + _('on')
                    # logger.info(logstring)
                    status_circulating_air = 1
                else:
                    logstring = logstring + ' \n ' +  _('circulation air') + ' ' + _('off')
                    # logger.info(logstring)
                    status_circulating_air = 0
                if gpio.input(pi_ager_gpio_config.gpio_exhausting_air) == False:
                    logstring = logstring + ' \n ' +  _('exhaust air') + ' ' + _('on')
                    # logger.info(logstring)
                    status_exhaust_air = 1
                else:
                    logstring = logstring + ' \n ' +  _('exhaust air') + ' ' + _('off')
                    # logger.info(logstring)
                    status_exhaust_air = 0
                if gpio.input(pi_ager_gpio_config.gpio_dehumidifier) == False:
                    logstring = logstring + ' \n ' +  _('dehumidifier') + ' ' + _('on')
                    # logger.info(logstring)
                    status_dehumidifier = 1
                else:
                    logstring = logstring + ' \n ' +  _('dehumidifier') + ' ' + _('off')
                    # logger.info(logstring)
                    status_dehumidifier = 0
                if get_gpio_value(pi_ager_gpio_config.gpio_light) == False:
                    logstring = logstring + ' \n ' +  _('light') + ' ' + _('on')
                    # logger.info(logstring)
                    status_light = 1
                else:
                    logstring = logstring + ' \n ' +  _('light') + ' ' + _('off')
                    # logger.info(logstring)
                    status_light = 0
                if gpio.input(pi_ager_gpio_config.gpio_uv) == False:
                    logstring = logstring + ' \n ' +  _('uv-light') + ' ' + _('on')
                    # logger.info(logstring)
                    status_uv= 1
                else:
                    logstring = logstring + ' \n ' +  _('uv-light') + ' ' + _('off')
                    # logger.info(logstring)
                    status_uv = 0
                if scale1_data > 0:
                    logstring = logstring + ' \n ' +  _('weight scale') + ' 1: ' + str(scale1_data)
                    # logger.info(logstring)
                if scale2_data > 0:
                    logstring = logstring + ' \n ' +  _('weight scale') + ' 2: ' + str(scale2_data)
                    # logger.info(logstring)
    
                # logger.info(pi_ager_names.logspacer2)
                logstring = logstring + ' \n ' + pi_ager_names.logspacer2
    
                if status_value_has_changed():
                    # logger.info(logstring)
                    cl_fact_logger.get_instance().info(logstring)
                
                # Messwerte in die RRD-Datei schreiben
                # Schreiben der aktuellen Status-Werte
                pi_ager_database.write_current_sensordata(pi_ager_init.loopcounter, sensor_temperature, sensor_humidity)
                pi_ager_database.write_current(sensor_temperature, status_heater, status_exhaust_air, status_cooling_compressor, status_circulating_air, sensor_humidity, status_uv, status_light, status_humidifier, status_dehumidifier)
    
                #logger.debug('writing current values in database performed')
                cl_fact_logger.get_instance().debug('writing current values in database performed')
            
            else:
                count_continuing_emergency_loops += 1
                # logger.debug('loopnumber: ' + str(pi_ager_init.loopcounter) + ' without sensordata!!')
                # logger.warning('loopnumber: ' + str(pi_ager_init.loopcounter) + ' is loop ' + str(count_continuing_emergency_loops) + ' without sensor response!')
                cl_fact_logger.get_instance().debug('loopnumber: ' + str(pi_ager_init.loopcounter) + ' without sensordata!!')
                cl_fact_logger.get_instance().warning('loopnumber: ' + str(pi_ager_init.loopcounter) + ' is loop ' + str(count_continuing_emergency_loops) + ' without sensor response!')
                if count_continuing_emergency_loops == 10:
                    # logger.info('Because of ' + str(count_continuing_emergency_loops) + ' loops without sensordata the system will be rebooted now!')
                    cl_fact_logger.get_instance().info('Because of ' + str(count_continuing_emergency_loops) + ' loops without sensordata the system will be rebooted now!')
                    os.system('sudo /var/sudowebscript.sh reboot')
            
            # logger.debug('loopnumber: ' + str(pi_ager_init.loopcounter))
            cl_fact_logger.get_instance().debug('loopnumber: ' + str(pi_ager_init.loopcounter))
    
            time.sleep(10)  
            
            # Logfile auf Rechte prüfen und evtl. neu setzen
            
            # filepermission = oct(os.stat(pi_ager_paths.logfile_txt_file)[stat.ST_MODE])[-3:]
            # if (filepermission != '666'):
                # os.chmod(pi_ager_paths.get_path_logfile_txt_file(), stat.S_IWOTH|stat.S_IWGRP|stat.S_IWUSR|stat.S_IROTH|stat.S_IRGRP|stat.S_IRUSR)
            #pi_ager_logging.check_website_logfile()
            cl_fact_logger.get_instance().check_website_logfile()
            # Mainloop fertig
            # logger.debug('loop complete')
            cl_fact_logger.get_instance().debug('loop complete')
            pi_ager_init.loopcounter += 1
            
            
            
    # Ende While-Schleife
        # logger.debug('status!= 1')
        cl_fact_logger.get_instance().debug('status!= 1')
        #Here clean Deviation time_counter
        pi_ager_gpio_config.defaultGPIO()
 
    except Exception as cx_error:
       cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
