#!/usr/bin/python3
"""
    main loop for pi-ager
"""
import os
# import subprocess
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

import pi_ager_gpio_config
# import pi_ager_organization
import pi_ager_mcp3204
from time import ctime as convert

from main.pi_ager_cx_exception import (cx_i2c_sht_temperature_crc_error, cx_i2c_sht_humidity_crc_error, cx_i2c_bus_error) 
from messenger.pi_ager_cl_alarm import cl_fact_logic_alarm
from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type, cl_fact_second_sensor_type
from sensors.pi_ager_cl_active_sensor import cl_fact_active_main_sensor, cl_fact_active_second_sensor
from sensors.pi_ager_cl_i2c_bus import cl_fact_i2c_bus_logic

from main.pi_ager_cl_logger import cl_fact_logger
from pi_ager_cl_nextion import cl_fact_nextion
from threading import Timer
 
import globals

system_shutdown = False

#-------------------------------------------------------------------------------------
#
# for temperature outside high or low limit, generate event, hysteresis used to prevent lots of events, these flags 
# store limit reached state
internal_temperature_low_limit_reached = False
internal_temperature_high_limit_reached = False
internal_temperature_low_limit = None
internal_temperature_high_limit = None
internal_temperature_hysteresis = None
#
#-------------------------------------------------------------------------------------

#-------------------------------------------------------------------------------------
# for UPS Bat Low, Power Monitor and Switch, check to issue events
ups_bat_low = True      # akku ok
bat_low_true_count = 0  # counter for bat_low true
bat_low_false_count = 0 # counter for bat_low false
bat_low_count_max = 3   # max count for bat_low true and false 
power_monitor = True    # power supply ok
pi_switch = True        # switch open
#-------------------------------------------------------------------------------------

def start_mi_thermometer():
    # check if /home/pi/MiTemperature2/LYWSD03MMC.py is allready running, if not then start this process
    stream = os.popen('pgrep -lf python3 | grep LYWSD03MMC.py | wc -l')
    output = stream.read().rstrip('\n')
    cl_fact_logger.get_instance().debug('/opt/MiTemperature2/LYWSD03MMC.py checking: ' + str(output))
    if (output == '0'):
        cl_fact_logger.get_instance().debug('/home/pi/MiTemperature2/LYWSD03MMC.py starting')
        # os.system('/home/pi/MiTemperature2/LYWSD03MMC.py --atc --callback MiCallback.sh --devicelistfile /home/pi/MiTemperature2/my_thermometer.txt -odl >/home/pi/MiTemperature2/LYWSD03MMC.log &')
        os.system('/opt/MiTemperature2/LYWSD03MMC.py --atc --callback MiCallback.sh --devicelistfile /opt/MiTemperature2/my_thermometer.txt -odl >/dev/null 2>/dev/null &')
        cl_fact_logger.get_instance().debug('/opt/MiTemperature2/LYWSD03MMC.py started')
        
def autostart_loop():
    """
    starting loop. pi is startet. pi-ager is not startet. waiting for value 1 in database pi-ager-status
    """
    global status_pi_ager
    global system_shutdown
    # global logger 
    try:
        while not system_shutdown:
            status_pi_ager = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key)
            second_sensor_type = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.sensorsecondtype_key))
            cl_fact_logger.get_instance().update_logger_loglevels()
            cl_fact_logger.get_instance().debug('autostart_loop ' + time.strftime('%H:%M:%S', time.localtime()))
            # enter main loop when start is true
            if status_pi_ager == 1:
                if (second_sensor_type == 6):
                    start_mi_thermometer()
                doMainLoop()

            cl_fact_logger.get_instance().check_website_logfile()
            # init defrost state off
            pi_ager_database.write_startstop_status_in_database(pi_ager_names.status_defrost_key, 0)
            time.sleep(5)
    except Exception as cx_error:
        cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
            
def get_sensordata(sht_exception_count, humidity_exception_count, temperature_exception_count, dewpoint_exception_count, humidity_abs_exception_count, sensordata_exception_count):
    """
    try to read sensordata
    """
    global sensor_humidity_big
    global sensor_temperature_big 
    global sensor_dewpoint_big
    global sensor_humidity_abs_big
    global second_sensor_humidity_big
    global second_sensor_temperature_big 
    global second_sensor_dewpoint_big    
    global second_sensor_humidity_abs_big
    
    last_temperature = None
    last_humidity   = None
    last_dewpoint   = None
    last_humidity_abs = None
    
    second_sensor_humidity_big = None
    second_sensor_temperature_big = None
    second_sensor_dewpoint_big = None
    second_sensor_humidity_abs_big = None
    
    sensordata={}
    sensorname = cl_fact_main_sensor_type.get_instance().get_sensor_type_ui()
    second_sensorname = None
    cl_fact_logger.get_instance().debug("sensorname: " + str(sensorname))
    cl_fact_logger.get_instance().debug("sensortype: " + str(cl_fact_main_sensor_type.get_instance().get_sensor_type()))
    
    try:
        if sensorname == 'DHT11' or sensorname == 'DHT22' or sensorname == 'SHT75':
            try:
                main_sensor =  cl_fact_active_main_sensor().get_instance()
                main_sensor.execute()
                measured_data = main_sensor.get_current_data()
                (sensor_temperature_big, sensor_humidity_big, sensor_dewpoint_big, sensor_humidity_abs_big) = measured_data
                cl_fact_logger.get_instance().debug('sensor_temperature_big: ' + str(sensor_temperature_big))
                cl_fact_logger.get_instance().debug('sensor_humidity_big: ' + str(sensor_humidity_big)) 
                cl_fact_logger.get_instance().debug('sensor_dewpoint_big: ' + str(sensor_dewpoint_big))   
                cl_fact_logger.get_instance().debug('sensor_humidity_abs_big: ' + str(sensor_humidity_abs_big))
                
            except pi_sht1x.sht1x.SHT1xError as cx_error:
                cl_fact_logic_messenger().get_instance().handle_exception(cx_error)

        elif sensorname == 'SHT3x' or sensorname == 'SHT85': #SH3x
            try:
                i2c_address_main_sensor = 0x44 #0x45
                main_sensor =  cl_fact_active_main_sensor().get_instance(i_address = i2c_address_main_sensor)
                main_sensor.execute()
                measured_data = main_sensor.get_current_data()
                (sensor_temperature_big, sensor_humidity_big, sensor_dewpoint_big, sensor_humidity_abs_big) = measured_data

                # cl_fact_logger.get_instance().debug('sensor_temperature_big: ' + str(sensor_temperature_big))
                # cl_fact_logger.get_instance().debug('sensor_humidity_big: ' + str(sensor_humidity_big)) 
                # cl_fact_logger.get_instance().debug('sensor_dewpoint_big: ' + str(sensor_dewpoint_big))
                # cl_fact_logger.get_instance().debug('sensor_humidity_abs_big: ' + str(sensor_humidity_abs_big))
                
            except OSError as cx_error:
                cl_fact_i2c_bus_logic().set_instance(None)
                cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
                
            except (cx_i2c_sht_temperature_crc_error,
                    cx_i2c_sht_humidity_crc_error,
                    cx_i2c_bus_error ) as cx_error:
                cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
                
        """
        Zweiter Sensor SHT3x oder SHT85
        """ 
        second_sensorname = cl_fact_second_sensor_type.get_instance().get_sensor_type_ui()        
        cl_fact_logger.get_instance().debug('Second sensor is: ' + str(second_sensorname))    
                                                                                                                
        if second_sensorname == 'SHT3x' or second_sensorname == 'SHT85':
                
            try:
                i2c_address_second_sensor = 0x45
                second_sensor =  cl_fact_active_second_sensor().get_instance(i_address = i2c_address_second_sensor)
                second_sensor.execute()
                measured_second_data = second_sensor.get_current_data()
                (second_sensor_temperature_big, second_sensor_humidity_big, second_sensor_dewpoint_big, second_sensor_humidity_abs_big) = measured_second_data

                # cl_fact_logger.get_instance().debug('second_sensor_temperature_big: ' + str(second_sensor_temperature_big))
                # cl_fact_logger.get_instance().debug('second_sensor_humidity_big: ' + str(second_sensor_humidity_big)) 
                # cl_fact_logger.get_instance().debug('second_sensor_dewpoint_big: ' + str(second_sensor_dewpoint_big))
                # cl_fact_logger.get_instance().debug('second_sensor_humidity_abs_big: ' + str(second_sensor_humidity_abs_big))
                    
            except OSError as cx_error:
                cl_fact_i2c_bus_logic().set_instance(None)
                cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
                    
            except (cx_i2c_sht_temperature_crc_error, cx_i2c_sht_humidity_crc_error, cx_i2c_bus_error ) as cx_error:
                cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
            
        if second_sensorname == 'MiThermometer':
            second_sensor =  cl_fact_active_second_sensor().get_instance()
            # second_sensor.execute()
            measured_second_data = second_sensor.get_current_data()
            (second_sensor_temperature_big, second_sensor_humidity_big, second_sensor_dewpoint_big, second_sensor_humidity_abs_big) = measured_second_data
            
        #cl_fact_logger.get_instance().debug('Second sensor end: ' + str(second_sensorname))  
        """
        Zweiter Sensor SHT3x oder SHT85 Ende
        """
        #cl_fact_logger.get_instance().debug('After Second sensor end: ' + str(second_sensorname))  

        last_temperature = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_temperature_key)
        last_humidity = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_humidity_key)         
        last_dewpoint = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_dewpoint_key)  
        last_humidity_abs = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_humidity_abs_key)
        
        if last_temperature is not None and last_humidity is not None:
            sensor_temperature = round(sensor_temperature_big,2)
            sensor_humidity = round(sensor_humidity_big,2)
            sensor_dewpoint = round(sensor_dewpoint_big,2)
            sensor_humidity_abs = round(sensor_humidity_abs_big,2)
            second_sensor_temperature = round(second_sensor_temperature_big,2) if second_sensor_temperature_big is not None else None
            second_sensor_humidity = round(second_sensor_humidity_big,2) if second_sensor_humidity_big is not None else None
            second_sensor_dewpoint = round(second_sensor_dewpoint_big,2) if second_sensor_dewpoint_big is not None else None 
            second_sensor_humidity_abs = round(second_sensor_humidity_abs_big,2) if second_sensor_humidity_abs_big is not None else None 
        elif sensor_humidity_big is not None and sensor_temperature_big is not None and sensor_dewpoint_big is not None: # and last_temperaure is not None and last_humidity is not None:
            #sensor_temperature_big = float(sensor_temperature_big)
            sensor_temperature = round(sensor_temperature_big,2)
            sensor_humidity = round(sensor_humidity_big,2)
            sensor_dewpoint = round(sensor_dewpoint_big,2)
            sensor_humidity_abs = round(sensor_humidity_abs_big,2)
            second_sensor_temperature = round(second_sensor_temperature_big,2) if second_sensor_temperature_big is not None else None
            second_sensor_humidity = round(second_sensor_humidity_big,2) if second_sensor_humidity_big is not None else None
            second_sensor_dewpoint = round(second_sensor_dewpoint_big,2) if second_sensor_dewpoint_big is not None else None 
            second_sensor_humidity_abs = round(second_sensor_humidity_abs_big,2) if second_sensor_humidity_abs_big is not None else None 
            
            #temperature
            if last_temperature == 0:
                deviation_temperature = sensor_temperature
            else:
                deviation_temperature = abs((sensor_temperature/last_temperature * 100) - 100)
            
            #humidity
            if last_humidity == 0:
                deviation_humidity = sensor_humidity
            else:
                deviation_humidity = abs((sensor_humidity/last_humidity * 100) - 100)
            
            #dewpoint
            if last_dewpoint == 0:
                deviation_dewpoint = sensor_dewpoint
            else:
                deviation_dewpoint = abs((sensor_dewpoint/last_dewpoint * 100) - 100)
                
            #humidity abs
            if last_humidity_abs == 0:
                deviation_humidity_abs = sensor_humidity_abs
            else:
                deviation_humidity_abs = abs((sensor_humidity_abs/last_humidity_abs * 100) - 100)  
                
            #temperature
            if sensor_temperature > 80 or deviation_temperature > 20:
                if temperature_exception_count < 10:
                    countup_values = countup('temperature_exception', temperature_exception_count)
                    logstring = countup_values['logstring']
                    temperature_exception_count = countup_values['counter']
                    cl_fact_logger.get_instance().debug(logstring)
                    time.sleep(1)
                    recursion = get_sensordata(sht_exception_count, humidity_exception_count, temperature_exception_count, dewpoint_exception_count, humidity_abs_exception_count, sensordata_exception_count)
                    return recursion
                else:
                    pass
                    
            #humidity
            if sensor_humidity > 100 or deviation_humidity > 20:
                if humidity_exception_count < 10:
                    countup_values = countup('humidity_exception', humidity_exception_count)
                    logstring = countup_values['logstring']
                    humidity_exception_count = countup_values['counter']
                    cl_fact_logger.get_instance().debug(logstring)
                    time.sleep(1)
                    recursion = get_sensordata(sht_exception_count, humidity_exception_count, temperature_exception_count, dewpoint_exception_count, humidity_abs_exception_count, sensordata_exception_count)
                    return recursion
                else:
                    pass
                    
            #dewpoint
            if sensor_dewpoint > 60 or deviation_dewpoint > 20:
                if dewpoint_exception_count < 10:
                    countup_values = countup('dewpoint_exception', dewpoint_exception_count)
                    logstring = countup_values['logstring']
                    dewpoint_exception_count = countup_values['counter']
                    cl_fact_logger.get_instance().debug(logstring)
                    time.sleep(1)
                    recursion = get_sensordata(sht_exception_count, humidity_exception_count, temperature_exception_count, dewpoint_exception_count, humidity_abs_exception_count, sensordata_exception_count)
                    return recursion
                else:
                    pass
                    
            #humidity abs
            if sensor_humidity_abs > 100 or deviation_humidity_abs > 20:
                if humidity_abs_exception_count < 10:
                    countup_values = countup('humidity_abs_exception', humidity_abs_exception_count)
                    logstring = countup_values['logstring']
                    humidity_abs_exception_count = countup_values['counter']
                    cl_fact_logger.get_instance().debug(logstring)
                    time.sleep(1)
                    recursion = get_sensordata(sht_exception_count, humidity_exception_count, temperature_exception_count, dewpoint_exception_count, humidity_abs_exception_count, sensordata_exception_count)
                    return recursion
                else:
                    pass
                                        
        elif sensordata_exception_count < 10:
            sensor_temperature = None
            sensor_humidity =    None
            sensor_dewpoint =    None
            sensor_humidity_abs = None
            
            countup_values = countup('sensordata_exception', sensordata_exception_count)
            logstring = countup_values['logstring']
            sensordata_exception_count = countup_values['counter']
            
            cl_fact_logger.get_instance().debug(logstring)
            time.sleep(1)
            recursion = get_sensordata(sht_exception_count, humidity_exception_count, temperature_exception_count, dewpoint_exception_count, humidity_abs_exception_count, sensordata_exception_count)
            return recursion
        
        else:
            sensor_temperature = None
            sensor_humidity =    None
            sensor_dewpoint    = None
            sensor_humidity_abs = None
            
            logstring = _('Failed to get sensordata.')
            cl_fact_logger.get_instance().warning(logstring)
            
       
        sensordata['sensor_temperature'] = sensor_temperature
        sensordata['sensor_humidity'] = sensor_humidity
        sensordata['sensor_dewpoint'] = sensor_dewpoint
        sensordata['sensor_humidity_abs'] = sensor_humidity_abs       
        sensordata['second_sensor_temperature'] = second_sensor_temperature
        sensordata['second_sensor_humidity'] = second_sensor_humidity
        sensordata['second_sensor_dewpoint'] = second_sensor_dewpoint
        sensordata['second_sensor_humidity_abs'] = second_sensor_humidity_abs
        cl_fact_logger.get_instance().debug( sensordata)
        
        #exit()
        
    except Exception as cx_error:
        cl_fact_logic_messenger().get_instance().handle_exception(cx_error)

    return(sensordata)
    
def countup(countername, counter):
    counter += 1
    if countername == 'sht_exception':
        logstring = 'SHT1xError occured, trying again, current number of retries: '
    elif countername == 'humidity_exception':
        logstring = 'no plausible humidity value [> 100 or too much deviation], trying again, current number of retries: '
    elif countername == 'humidity_abs_exception':
        logstring = 'no plausible humidity abs value [> 100 or too much deviation], trying again, current number of retries: '       
    elif countername == 'temperature_exception':
        logstring = 'no plausible temperature value [> 60 or too much deviation], trying again, current number of retries: '
    elif countername == 'dewpoint_exception':
        logstring = 'no plausible dewpoint value [> 60 or too much deviation], trying again, current number of retries: '        
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

def control_heater(relay_state):
    """
    setting gpio for heater
    """
    global defrost_status
    if not defrost_status:
        gpio.output(pi_ager_gpio_config.gpio_heater, relay_state)
            
def control_cooling_compressor(relay_state):
    """
    setting gpio for cooler
    """
    global defrost_status
    if not defrost_status:
        gpio.output(pi_ager_gpio_config.gpio_cooling_compressor, relay_state)
            
def control_circulating_air(relay_state):
    """
    setting gpio for circulation air
    """
    global defrost_status
    if not defrost_status:
        gpio.output(pi_ager_gpio_config.gpio_circulating_air, relay_state)
    
def switch_light(relay_state):
    """
    setting gpio for light
    """
    if not globals.hands_off_light_switch:
        #set_gpio_value(pi_ager_gpio_config.gpio_light, relay_state)
        globals.requested_state_light = relay_state
        # cl_fact_logger.get_instance().info(f"switch light state = {relay_state}")
        
def switch_uv_light(relay_state):
    """
    setting gpio for uv_light
    """
    # if globals.switch_control_uv_light == 0:
        # set_gpio_value(pi_ager_gpio_config.gpio_uv, relay_state)
    globals.requested_state_uv_light = relay_state
        
def get_global_switch_setting():
    globals.switch_control_light = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_control_light_key))
    globals.switch_control_uv_light = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_control_uv_light_key))

def control_defrost():
    """
    control defrost process
    """
    global defrost_status
    global status_pi_ager
    global defrost_cycle_elapsed
    global sensor_temperature
    
    if (status_pi_ager == 0):
        if (defrost_status == 1):
            cl_fact_logger.get_instance().info(_('defrost process stopped'))
            cl_fact_logic_messenger().get_instance().handle_event('Defrost_stopped') 
        defrost_status = 0
        defrost_cycle_elapsed = False
        pi_ager_database.write_current_value(pi_ager_names.status_defrost_key, 0)
        return
        
    defrost_active = int(pi_ager_database.get_table_value_from_field(pi_ager_names.defrost_table, pi_ager_names.defrost_active_field))
    if (defrost_active == 0):
        if (defrost_status == 1):
            cl_fact_logger.get_instance().info(_('defrost process stopped'))
            cl_fact_logic_messenger().get_instance().handle_event('Defrost_stopped') 
        defrost_status = 0
        defrost_cycle_elapsed = False 
        pi_ager_database.write_current_value(pi_ager_names.status_defrost_key, 0)
        return

# check if setpoint temperature greater than defrost_temp_limit, then stop defrost cycle
    defrost_temp_limit =  pi_ager_database.get_table_value_from_field(pi_ager_names.defrost_table, pi_ager_names.defrost_temp_limit_field)  # defrost temperature limit
    setpoint_temperature = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_temperature_key)    # setpoint temperature
    if (setpoint_temperature >= defrost_temp_limit):
        if (defrost_status == 1):
            cl_fact_logger.get_instance().info(_('defrost process stopped'))
            cl_fact_logic_messenger().get_instance().handle_event('Defrost_stopped') 
        defrost_status = 0
        defrost_cycle_elapsed = False 
        pi_ager_database.write_current_value(pi_ager_names.status_defrost_key, 0)
        return
        
    if (defrost_cycle_elapsed == False):
        if (defrost_status == 1):
            cl_fact_logger.get_instance().info(_('defrost process stopped'))
            cl_fact_logic_messenger().get_instance().handle_event('Defrost_stopped') 
        defrost_status = 0
        pi_ager_database.write_current_value(pi_ager_names.status_defrost_key, 0)
        return
    
    #  here defrost cycle elapsed and defrost is active and status_pi_ager is True: check if control can be taken over
    defrost_temperature = pi_ager_database.get_table_value_from_field(pi_ager_names.defrost_table, pi_ager_names.defrost_temperature_field)     # defrost temperature offset
    defrost_temperature += pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_temperature_key)        # absolute value defrost temperature = offset + setpoint_temperature
    if (sensor_temperature == None):    # check on valid temperature
        return
    if (sensor_temperature >= defrost_temperature): # can now finish defrost
        if (defrost_status == 1):
            cl_fact_logger.get_instance().info(_('defrost process stopped'))
            cl_fact_logic_messenger().get_instance().handle_event('Defrost_stopped') 
        defrost_status = 0
        pi_ager_database.write_current_value(pi_ager_names.status_defrost_key, 0)
        defrost_cycle_elapsed = False
        cl_fact_logger.get_instance().debug('defrost finished')
        return
        
    # here set defrost_status to 1,  turn on circulation air, turn on heater, turn off cooler: defrost now active
    if (defrost_status == 0):
        cl_fact_logger.get_instance().info(_('defrost process started'))
        cl_fact_logic_messenger().get_instance().handle_event('Defrost_started') 
    defrost_status = 1
    pi_ager_database.write_current_value(pi_ager_names.status_defrost_key, 1)
    gpio.output(pi_ager_gpio_config.gpio_heater, pi_ager_names.relay_on)
    gpio.output(pi_ager_gpio_config.gpio_cooling_compressor, pi_ager_names.relay_off)
    circulate_air = int(pi_ager_database.get_table_value_from_field(pi_ager_names.defrost_table, pi_ager_names.defrost_circulate_air_field))
    if (circulate_air == 1):
        gpio.output(pi_ager_gpio_config.gpio_circulating_air, pi_ager_names.relay_on)
    else:
        gpio.output(pi_ager_gpio_config.gpio_circulating_air, pi_ager_names.relay_off)
    cl_fact_logger.get_instance().debug('defrost in progress')
        
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
    check if value has changed, return also string with states on or off 
    changed state is in red
    """
    global status_circulating_air         #  Umluft
    global status_exhaust_air             #  (Abluft-)Luftaustausch
    global status_heater                  #  Heizung
    global status_cooling_compressor      #  Kuehlung
    global status_humidifier              #  Luftbefeuchtung
    global status_dehumidifier            #  Entfeuchter
    global status_uv                      #  UV-Licht
    global status_light                   #  Licht
    global sensor_temperature
    
    changed = pi_ager_init.loopcounter == 0
    log_string_html = pi_ager_names.logspacer2 + '\n' + _('GPIO states') + ':'
    log_string = log_string_html[:]
    
    current_value_rows = pi_ager_database.get_current(pi_ager_names.current_values_table, True)
    current_values = {}
    for current_row in current_value_rows:
        current_values[current_row[pi_ager_names.key_field]] = current_row[pi_ager_names.value_field]
    
    if gpio.input(pi_ager_gpio_config.gpio_heater) == False:
        status_heater = 1
        # simulation
#        sensor_temperature += 0.05
        if status_heater != current_values[pi_ager_names.status_heater_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('heater') + ' ' + _('on') + '</span>'
            log_string = log_string + ' \n*' + _('heater') + ' ' + _('on')
        else:
            log_string_html = log_string_html + ' \n ' + _('heater') + ' ' + _('on')
            log_string = log_string + ' \n ' + _('heater') + ' ' + _('on')
    else:
        status_heater = 0
        if status_heater != current_values[pi_ager_names.status_heater_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('heater') + ' ' + _('off') + '</span>'
            log_string = log_string + ' \n*' + _('heater') + ' ' + _('off')
        else:
            log_string_html = log_string_html + ' \n ' + _('heater') + ' ' + _('off')
            log_string = log_string + ' \n ' + _('heater') + ' ' + _('off')
        
    if gpio.input(pi_ager_gpio_config.gpio_cooling_compressor) == False:
        status_cooling_compressor = 1
        #simulation
#        sensor_temperature -= 0.05
        if status_cooling_compressor != current_values[pi_ager_names.status_cooling_compressor_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('cooling compressor') + ' ' + _('on') + '</span>'
            log_string = log_string + ' \n*' + _('cooling compressor') + ' ' + _('on')
        else:
            log_string_html = log_string_html + ' \n ' + _('cooling compressor') + ' ' + _('on')
            log_string = log_string + ' \n ' + _('cooling compressor') + ' ' + _('on')
    else:
        status_cooling_compressor = 0
        if status_cooling_compressor != current_values[pi_ager_names.status_cooling_compressor_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('cooling compressor') + ' ' + _('off') + '</span>'
            log_string = log_string + ' \n*' + _('cooling compressor') + ' ' + _('off')
        else:
            log_string_html = log_string_html + ' \n ' + _('cooling compressor') + ' ' + _('off')
            log_string = log_string + ' \n ' + _('cooling compressor') + ' ' + _('off')
        
    if gpio.input(pi_ager_gpio_config.gpio_humidifier) == False:
        status_humidifier = 1
        if status_humidifier != current_values[pi_ager_names.status_humidifier_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('humidifier') + ' ' + _('on') + '</span>'
            log_string = log_string + ' \n*' + _('humidifier') + ' ' + _('on')
        else:
            log_string_html = log_string_html + ' \n ' + _('humidifier') + ' ' + _('on')
            log_string = log_string + ' \n ' + _('humidifier') + ' ' + _('on')
    else:
        status_humidifier = 0
        if status_humidifier != current_values[pi_ager_names.status_humidifier_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('humidifier') + ' ' + _('off') + '</span>'
            log_string = log_string + ' \n*' + _('humidifier') + ' ' + _('off')
        else:
            log_string_html = log_string_html + ' \n ' + _('humidifier') + ' ' + _('off')
            log_string = log_string + ' \n ' + _('humidifier') + ' ' + _('off')
        
    if gpio.input(pi_ager_gpio_config.gpio_circulating_air) == False:
        status_circulating_air = 1
        if status_circulating_air != current_values[pi_ager_names.status_circulating_air_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('circulation air') + ' ' + _('on') + '</span>'
            log_string = log_string + ' \n*' + _('circulation air') + ' ' + _('on')
        else:
            log_string_html = log_string_html + ' \n ' + _('circulation air') + ' ' + _('on')
            log_string = log_string + ' \n ' + _('circulation air') + ' ' + _('on')
    else:
        status_circulating_air = 0
        if status_circulating_air != current_values[pi_ager_names.status_circulating_air_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('circulation air') + ' ' + _('off') + '</span>'
            log_string = log_string + ' \n*' + _('circulation air') + ' ' + _('off')
        else:
            log_string_html = log_string_html + ' \n ' + _('circulation air') + ' ' + _('off')            
            log_string = log_string + ' \n ' + _('circulation air') + ' ' + _('off')
        
    if gpio.input(pi_ager_gpio_config.gpio_exhausting_air) == False:
        status_exhaust_air = 1
        if status_exhaust_air != current_values[pi_ager_names.status_exhaust_air_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('exhaust air') + ' ' + _('on') + '</span>'
            log_string = log_string + ' \n*' + _('exhaust air') + ' ' + _('on')
        else:
            log_string_html = log_string_html + ' \n ' + _('exhaust air') + ' ' + _('on')
            log_string = log_string + ' \n ' + _('exhaust air') + ' ' + _('on')
    else:
        status_exhaust_air = 0
        if status_exhaust_air != current_values[pi_ager_names.status_exhaust_air_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('exhaust air') + ' ' + _('off') + '</span>'
            log_string = log_string + ' \n*' + _('exhaust air') + ' ' + _('off')
        else:
            log_string_html = log_string_html + ' \n ' + _('exhaust air') + ' ' + _('off')            
            log_string = log_string + ' \n ' + _('exhaust air') + ' ' + _('off')
        
    if gpio.input(pi_ager_gpio_config.gpio_dehumidifier) == False:
        status_dehumidifier = 1
        if status_dehumidifier != current_values[pi_ager_names.status_dehumidifier_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('dehumidifier') + ' ' + _('on') + '</span>'
            log_string = log_string + ' \n*' + _('dehumidifier') + ' ' + _('on')
        else:
            log_string_html = log_string_html + ' \n ' + _('dehumidifier') + ' ' + _('on')
            log_string = log_string + ' \n ' + _('dehumidifier') + ' ' + _('on')
    else:
        status_dehumidifier = 0
        if status_dehumidifier != current_values[pi_ager_names.status_dehumidifier_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('dehumidifier') + ' ' + _('off') + '</span>'
            log_string = log_string + ' \n*' + _('dehumidifier') + ' ' + _('off')
        else:
            log_string_html = log_string_html + ' \n ' + _('dehumidifier') + ' ' + _('off')            
            log_string = log_string + ' \n ' + _('dehumidifier') + ' ' + _('off')
        
    if gpio.input(pi_ager_gpio_config.gpio_light) == False:
        status_light = 1
        if status_light != current_values[pi_ager_names.status_light_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('light') + ' ' + _('on') + '</span>'
            log_string = log_string + ' \n*' + _('light') + ' ' + _('on')
        else:
            log_string_html = log_string_html + ' \n ' + _('light') + ' ' + _('on')
            log_string = log_string + ' \n ' + _('light') + ' ' + _('on')
    else:
        status_light = 0
        if status_light != current_values[pi_ager_names.status_light_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('light') + ' ' + _('off') + '</span>'
            log_string = log_string + ' \n*' + _('light') + ' ' + _('off')
        else:
            log_string_html = log_string_html + ' \n ' + _('light') + ' ' + _('off')            
            log_string = log_string + ' \n ' + _('light') + ' ' + _('off')
        
    if gpio.input(pi_ager_gpio_config.gpio_uv) == False:
        status_uv = 1
        if status_uv != current_values[pi_ager_names.status_uv_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('uv-light') + ' ' + _('on') + '</span>'
            log_string = log_string + ' \n*' + _('uv-light') + ' ' + _('on')
        else:
            log_string_html = log_string_html + ' \n ' + _('uv-light') + ' ' + _('on')
            log_string = log_string + ' \n ' + _('uv-light') + ' ' + _('on')
    else:
        status_uv = 0
        if status_uv != current_values[pi_ager_names.status_uv_key]:
            changed = True
            log_string_html = log_string_html + ' \n ' + '<span style="color: red;">' + _('uv-light') + ' ' + _('off') + '</span>'
            log_string = log_string + ' \n*' + _('uv-light') + ' ' + _('off')
        else:
            log_string_html = log_string_html + ' \n ' + _('uv-light') + ' ' + _('off')            
            log_string = log_string + ' \n ' + _('uv-light') + ' ' + _('off')

    return changed, log_string, log_string_html

def get_temp_sensor_data(sensor_config, adc_channel):
    """
    read temperature/current from mcp3204 ADC-Channel depending on sensor_configuration  
    """
    value = None
    unit = None
    with pi_ager_mcp3204.CONVERT_MCP() as convertMCP:
        value, unit = convertMCP.getValue(sensor_config, adc_channel)
            
    return value

def do_system_shutdown():
     global system_shutdown
     cl_fact_logger.get_instance().debug('in do_system_shutdown')
     system_shutdown = True
     
def invoke_off_failure_event(repeat_event_cycle):
    global next_start_time_off_failure
    global cooler_failure_off_repeat_counter
    
    current_time = time.time()
    if cooler_failure_off_repeat_counter == False:
        cooler_failure_off_repeat_counter = True
        cl_fact_logic_messenger().get_instance().handle_event('cooler_turned_off_failure')
        next_start_time_off_failure = current_time + repeat_event_cycle # every n seconds repeat event
    else:
        if (current_time >= next_start_time_off_failure):
            next_start_time_off_failure = current_time + repeat_event_cycle
            cl_fact_logic_messenger().get_instance().handle_event('cooler_turned_off_failure')
            
def invoke_on_failure_event(repeat_event_cycle):
    global next_start_time_on_failure
    global cooler_failure_on_repeat_counter
    
    current_time = time.time()
    if cooler_failure_on_repeat_counter == False:
        cooler_failure_on_repeat_counter = True
        cl_fact_logic_messenger().get_instance().handle_event('cooler_turned_on_failure')
        next_start_time_on_failure = current_time + repeat_event_cycle # every n seconds repeat event
    else:
        if (current_time >= next_start_time_on_failure):
            next_start_time_on_failure = current_time + repeat_event_cycle
            cl_fact_logic_messenger().get_instance().handle_event('cooler_turned_on_failure')

def generate_cooler_failure_events():
    global cooler_failure_off_repeat_counter
    global cooler_failure_on_repeat_counter
    global temp_sensor4_data        # contains AC Current
    
    if (temp_sensor4_data == None):
        return
        
    meat4_sensortype = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.meat4_sensortype_key))
    if (meat4_sensortype != 18):    # must be ac current sensor
        return
    
    current_check_row = pi_ager_database.get_table_row(pi_ager_names.config_current_check_table, 1)
    status_current_check = current_check_row[pi_ager_names.current_check_active_field]
    
    if (status_current_check == 0): # current check must be enabled
        cooler_failure_off_repeat_counter = False
        cooler_failure_on_repeat_counter = False
        return
        
    current_threshold = current_check_row[pi_ager_names.current_threshold_field]
    repeat_event_cycle = current_check_row[pi_ager_names.repeat_event_cycle_field] * 60     # convert to seconds
    ac_current = temp_sensor4_data
    # cl_fact_logger.get_instance().info(f"cooler check parameter : status = {status_current_check}, threshold = {current_threshold}, repeat cycle = {repeat_event_cycle}")
    cooler_status = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_cooling_compressor_key))
    cooler_pio_status = gpio.input(pi_ager_gpio_config.gpio_cooling_compressor) # false --> relay_on, true --> relay_off
    
    # check on cooler turned off
    if (cooler_status == 0 and cooler_pio_status == True):
        if (ac_current > current_threshold):
            # cl_fact_logger.get_instance().info(f"cooler should be turned off but is not. AC current is {ac_current} A")
            invoke_off_failure_event(repeat_event_cycle)
            return
        else:
            cooler_failure_off_repeat_counter = False
    else:
        cooler_failure_off_repeat_counter = False
        
    # check on cooler turned on
    if (cooler_status == 1 and cooler_pio_status == False):
        if (ac_current <= current_threshold):
            # cl_fact_logger.get_instance().info(f"cooler should be turned on but is not. AC current is {ac_current} A")
            invoke_on_failure_event(repeat_event_cycle)
            return 
        else:
            cooler_failure_on_repeat_counter = False        
    else:
        cooler_failure_on_repeat_counter = False
    
def generate_ups_bat_events():
    global ups_bat_low          # akku ok
    global bat_low_true_count   # counter for bat_low true
    global bat_low_false_count  # counter for bat_low false
    global bat_low_count_max    # max count for bat_low true and false 
    
    shutdown_on_batlow = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.shutdown_on_batlow_key))
    ups_bat_low_temp = pi_ager_gpio_config.check_ups_bat_low()
    cl_fact_logger.get_instance().debug('UPS battery state is ' + ('ok' if ups_bat_low_temp else 'low'))
    
    if ups_bat_low_temp == False:
        bat_low_false_count += 1
        bat_low_true_count = 0
    else:
        bat_low_true_count += 1
        bat_low_false_count = 0
        
    if ups_bat_low == True and bat_low_false_count >= bat_low_count_max:
        # generate event
        ups_bat_low = False
        try:
            cl_fact_logger.get_instance().info(_('UPS battery is low'))
            cl_fact_logic_messenger().get_instance().handle_event('ups_bat_low') #if the second parameter is empty, the value is taken from the field envent_text in table config_messenger_event 
            if shutdown_on_batlow == 1:
                cl_fact_logger.get_instance().info(_('Shutdown Pi-Ager now'))
                time.sleep(3)
                # do_system_shutdown()
                os.system("shutdown -h now")
        except Exception as cx_error:
            exception_known = cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
            pass
        
    if ups_bat_low == False and bat_low_true_count >= bat_low_count_max:
        # generate event
        ups_bat_low = True
        try:
            cl_fact_logger.get_instance().info(_('UPS battery is ok'))
            cl_fact_logic_messenger().get_instance().handle_event('ups_bat_ok') #if the second parameter is empty, the value is taken from the field envent_text in table config_messenger_event 
        except Exception as cx_error:
            exception_known = cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
            pass
                        
def generate_power_monitor_events():
    global power_monitor
    
    power_monitor_temp = pi_ager_gpio_config.check_power_monitor()
    cl_fact_logger.get_instance().debug('Power monitor state is ' + ('powergood' if power_monitor_temp else 'powerfail'))
    if power_monitor == True and power_monitor_temp == False:
        # generate event
        try:
            cl_fact_logger.get_instance().info(_('Power monitor signals powerfail'))
            cl_fact_logic_messenger().get_instance().handle_event('powerfail') #if the second parameter is empty, the value is taken from the field envent_text in table config_messenger_event 
        except Exception as cx_error:
            exception_known = cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
            pass
            
    if power_monitor == False and power_monitor_temp == True:
        # generate event
        try:
            cl_fact_logger.get_instance().info(_('Power monitor signals powergood'))
            cl_fact_logic_messenger().get_instance().handle_event('powergood')  #if the second parameter is empty, the value is taken from the field envent_text in table config_messenger_event 
            # cl_fact_nextion.get_instance().reset_page_after_powergood()         #activate last current page
        except Exception as cx_error:
            exception_known = cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
            pass
            
    power_monitor = power_monitor_temp 
    
def generate_switch_event():
    global pi_switch
    
    pi_switch_temp = pi_ager_gpio_config.check_switch()
    cl_fact_logger.get_instance().debug('Switch state is ' + ('off' if pi_switch_temp else 'on'))
    if pi_switch == True and pi_switch_temp == False:
        # generate event
        try:
            cl_fact_logger.get_instance().info(_('Switch is shorted to GND'))
            cl_fact_logic_messenger().get_instance().handle_event('switch_on') #if the second parameter is empty, the value is taken from the field envent_text in table config_messenger_event 
        except Exception as cx_error:
            exception_known = cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
            pass
            
    if pi_switch == False and pi_switch_temp == True:
        # generate event
        try:
            cl_fact_logger.get_instance().info(_('Switch is open'))
            cl_fact_logic_messenger().get_instance().handle_event('switch_off') #if the second parameter is empty, the value is taken from the field envent_text in table config_messenger_event 
        except Exception as cx_error:
            exception_known = cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
            pass            
            
    pi_switch = pi_switch_temp 
    
def generate_low_limit_reached_event(event_msg):
    # generate event
    try:
        cl_fact_logic_messenger().get_instance().handle_event('Int_Temp_Low_Limit', event_msg) #if the second parameter is empty, the value is taken from the field envent_text in table config_messenger_event 
    except Exception as cx_error:
        exception_known = cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
        pass
        
def generate_high_limit_reached_event(event_msg):
    # generate event
    try:
        cl_fact_logic_messenger().get_instance().handle_event('Int_Temp_High_Limit', event_msg) #if the second parameter is empty, the value is taken from the field envent_text in table config_messenger_event 
    except Exception as cx_error:
        exception_known = cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
        pass
        
def check_internal_temperature_limits():
    global internal_temperature_low_limit_reached
    global internal_temperature_high_limit_reached
    global internal_temperature_low_limit
    global internal_temperature_high_limit
    global internal_temperature_hysteresis
    global sensor_temperature
    
    # check if settings in config table changed
    internal_temperature_low_limit_temp = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.internal_temperature_low_limit_key)
    internal_temperature_high_limit_temp = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.internal_temperature_high_limit_key)
    internal_temperature_hysteresis_temp = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.internal_temperature_hysteresis_key) 
    if internal_temperature_hysteresis_temp != internal_temperature_hysteresis:
        internal_temperature_low_limit_reached = False
        internal_temperature_high_limit_reached = False
    if internal_temperature_low_limit_temp != internal_temperature_low_limit:
        internal_temperature_low_limit_reached = False
    if internal_temperature_high_limit_temp != internal_temperature_high_limit:
        internal_temperature_high_limit_reached = False

    internal_temperature_low_limit = internal_temperature_low_limit_temp
    internal_temperature_high_limit = internal_temperature_high_limit_temp
    internal_temperature_hysteresis = internal_temperature_hysteresis_temp
    
    if sensor_temperature != None:
        if sensor_temperature <= internal_temperature_low_limit and internal_temperature_low_limit_reached == False:
            internal_temperature_low_limit_reached = True
            event_msg = 'Internal temperature low limit ' + str(internal_temperature_low_limit) + '°C ' + 'reached'
            generate_low_limit_reached_event(event_msg)
            cl_fact_logger.get_instance().info(event_msg)
        if sensor_temperature >= (internal_temperature_low_limit + internal_temperature_hysteresis):
            internal_temperature_low_limit_reached = False
            cl_fact_logger.get_instance().debug('Internal temperature ' + str(sensor_temperature) + '°C higher than ' + str(internal_temperature_low_limit + internal_temperature_hysteresis) + '°C. (low limit + hysteresis)')
        if sensor_temperature >= internal_temperature_high_limit and internal_temperature_high_limit_reached == False:
            internal_temperature_high_limit_reached = True
            event_msg = 'Internal temperature high limit ' + str(internal_temperature_high_limit) + '°C ' + 'reached'
            generate_high_limit_reached_event(event_msg)
            cl_fact_logger.get_instance().info(event_msg)
        if sensor_temperature <= (internal_temperature_high_limit - internal_temperature_hysteresis):
            internal_temperature_high_limit_reached = False            
            cl_fact_logger.get_instance().debug('Internal temperature ' + str(sensor_temperature) + '°C lower than ' + str(internal_temperature_high_limit - internal_temperature_hysteresis) + '°C. (high limit - hysteresis)')

cooling_compressor_request = False
cooling_Delay_timer = None
cooling_Delay_timer_running = False

def delay_cooling_compressor_callback():
    global cooling_compressor_request
    global cooling_Delay_timer_running
    if (cooling_compressor_request == False):   # turn on compressor
        control_cooling_compressor(pi_ager_names.relay_on)
        # gpio.output(pi_ager_gpio_config.gpio_cooling_compressor, False)
    cooling_Delay_timer_running = False
#    cl_fact_logger.get_instance().info('delay_cooling_compressor_callback finished')
    
def delay_cooling_compressor(  level ):
    """
    when cooling compressor should turned off then next turn-on request will be delayed
    level false == relay_on == cooler active
    """
    global cooling_compressor_request
    global cooling_Delay_timer_running
    global cooling_Delay_timer
    
    # if compressor is currently on and new request (level) should turn off compressor
    current_state = gpio.input(pi_ager_gpio_config.gpio_cooling_compressor) # if false, compressor is on
    delay_cooler = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.delay_cooler_key))
    if (current_state == False and level == True and cooling_Delay_timer_running == False):  # start timer
        cooling_Delay_timer = Timer(delay_cooler, delay_cooling_compressor_callback)
        cooling_Delay_timer.start()
        cooling_Delay_timer_running = True
        # gpio.output(pi_ager_gpio_config.gpio_cooling_compressor, level)
        control_cooling_compressor(level)
#        cl_fact_logger.get_instance().info('cooling compressor delay timer started')
 
    cooling_compressor_request = level
    if (cooling_Delay_timer_running == False):
        # gpio.output(pi_ager_gpio_config.gpio_cooling_compressor, level)
        control_cooling_compressor(level)
#    cl_fact_logger.get_instance().info('delay_cooling_compressor function finished')

def check_int_ext_humidity():
    """
    Check difference between internal and external abs. humidity.
    If external abs. humidity is greater than internal abs. humidity and if abs. humidity check is enabled
    return True, means: turn off fan
    """
    global second_sensor_dewpoint       #  Gerechneter Taupunkt externer Sensor
    global second_sensor_humidity_abs   #  absolute Feuchte externer Sensor
    global sensor_dewpoint              #  Gerechneter Taupunkt interner Sensor
    global sensor_humidity_abs          #  absolute Feuchte interner Sensor
    global last_humidity_check_state    #  
    
    humidity_check_hysteresis = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.humidity_check_hysteresis_key)
    
    # check if we have a second sensor
    second_sensorname = cl_fact_second_sensor_type.get_instance().get_sensor_type_ui()
    # check if dewpoint_check is enabled
    dewpoint_check = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.dewpoint_check_key))
    # cl_fact_logger.get_instance().debug('Second sensor name : ' + second_sensorname)
    # cl_fact_logger.get_instance().debug('dewpoint_check flag : ' + str(dewpoint_check))
    # cl_fact_logger.get_instance().debug('hum_abs_ext : ' + str(second_sensor_humidity_abs))
    # cl_fact_logger.get_instance().debug('hum_abs_int : ' + str(sensor_humidity_abs))
    
    if (dewpoint_check == 0):
        # cl_fact_logger.get_instance().debug('check_int_ext_humidity determins fan untouched ')
        last_humidity_check_state = False
        pi_ager_database.write_current_value(pi_ager_names.status_humidity_check_key, 0)
        return last_humidity_check_state # if check is turned off, normal mode is allowed, exhaust fan can be turned on or off
        
    if second_sensorname != 'disabled' and second_sensor_humidity_abs != None:
        # cl_fact_logger.get_instance().debug('in check_int_ext_humidity') 
        # if second_sensor_humidity_abs >= (sensor_humidity_abs + humidity_check_hysteresis/2.0):
        if second_sensor_humidity_abs >= sensor_humidity_abs:
            cl_fact_logger.get_instance().debug('check_int_ext_humidity determins fan off ')
            pi_ager_database.write_current_value(pi_ager_names.status_humidity_check_key, 1)
            last_humidity_check_state = True    # exhaust fan off
            
        # if second_sensor_humidity_abs <= (sensor_humidity_abs - humidity_check_hysteresis/2.0):
        if second_sensor_humidity_abs <= (sensor_humidity_abs - humidity_check_hysteresis):
            cl_fact_logger.get_instance().debug('check_int_ext_humidity determins fan untouched ')
            pi_ager_database.write_current_value(pi_ager_names.status_humidity_check_key, 0)
            last_humidity_check_state = False    # exhaust fan untouched          
            
    return last_humidity_check_state 
            
def generate_status_change_event(logstring):
    # generate event
    try:
        cl_fact_logic_messenger().get_instance().handle_event('GPIO_status_changed', logstring) #if the second parameter is empty, the value is taken from the field envent_text in table config_messenger_event 
    except Exception as cx_error:
        exception_known = cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
        pass    

def simple_cooler_temperature_control():
    # simple 2-point temperature control for cooler
    global sensor_temperature
    global setpoint_temperature
    global switch_on_cooling_compressor
    global switch_off_cooling_compressor
    
    # check if cooler must be set on or off
    if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
        delay_cooling_compressor( pi_ager_names.relay_on)      # Kuehlung ein
    if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor:
        delay_cooling_compressor( pi_ager_names.relay_off)     # Kuehlung aus
                
def simple_heater_temperature_control(): 
    # simple 2-point temperature control for heater
    global sensor_temperature
    global setpoint_temperature
    global switch_on_cooling_compressor
    global switch_off_cooling_compressor
    
    # check if heater must be set on or off
    if sensor_temperature <= setpoint_temperature - switch_on_cooling_compressor:
        control_heater(pi_ager_names.relay_on)
    if sensor_temperature >= setpoint_temperature - switch_off_cooling_compressor:
        control_heater(pi_ager_names.relay_off)

def auto_temperature_control():
    # Automatic 2-point temperature control with 2 actuators, one for cooling and one for heating
    # If an external temperature sensor is available, it will be included into the control algorithm
    global sensor_temperature
    global setpoint_temperature
    global second_sensor_temperature
                    
    cooling_hysteresis = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.cooling_hysteresis_key)
    heating_hysteresis = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.heating_hysteresis_key)
    
    # check if an external sensor exists and if so check if external temperature is greater than setpoint temperature:
    # then activate cooling algorithm
    if (second_sensor_temperature == None or second_sensor_temperature > setpoint_temperature):     
        switch_on_cooling_compressor = cooling_hysteresis/2
        switch_off_cooling_compressor = -cooling_hysteresis/2
        switch_on_heater = heating_hysteresis/2
        switch_off_heater = -heating_hysteresis/2
    else:   # activate heating alhorithm by exchanging primary and secondary hysteresis values
        switch_on_cooling_compressor = heating_hysteresis/2
        switch_off_cooling_compressor = -heating_hysteresis/2
        switch_on_heater = cooling_hysteresis/2
        switch_off_heater = -cooling_hysteresis/2
        
    # check if cooler or heater must be set on or off
    if (sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor) or (sensor_temperature <= setpoint_temperature + switch_off_heater):
        delay_cooling_compressor( pi_ager_names.relay_off)                          # Kuehlung aus
    if (sensor_temperature >= setpoint_temperature - switch_off_heater) or (sensor_temperature >= setpoint_temperature - switch_off_cooling_compressor ):
        control_heater(pi_ager_names.relay_off)                                     # Heizung aus
                        
    if (sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor): # or (sensor_temperature >= setpoint_temperature + switch_on_heater):
        delay_cooling_compressor( pi_ager_names.relay_on)                           # Kuehlung ein
        if gpio.input(pi_ager_gpio_config.gpio_heater) == False:                    # only if heater is on, turn off heater
            control_heater(pi_ager_names.relay_off)
                            
    if (sensor_temperature <= setpoint_temperature - switch_on_heater): # or (sensor_temperature <= setpoint_temperature - switch_on_cooling_compressor ):
        control_heater(pi_ager_names.relay_on)                                      # turn on heater
        if gpio.input(pi_ager_gpio_config.gpio_cooling_compressor) == False:        # only if cooler is on, turn off cooler
            delay_cooling_compressor( pi_ager_names.relay_off)                      # Kuehlung aus
                       
def doMainLoop():
    """
    mainloop, pi-ager is running
    """
    global setpoint_temperature           #  target temperature
    global circulation_air_duration       #  Umluftdauer
    global circulation_air_period         #  Umluftperiode
    global exhaust_air_duration           #  (Abluft-)luftaustauschdauer
    global exhaust_air_period             #  (Abluft-)luftaustauschperiode
    
    global sensor_temperature             #  Gemessene Temperatur am Sensor
    global sensor_humidity                #  Gemessene Feuchtigkeit am Sensor
    global sensor_dewpoint                #  Gerechneter Taupunkt
    global sensor_humidity_abs 
    
    global second_sensor_temperature      #  Gemessene Temperatur am Sensor
    global second_sensor_humidity         #  Gemessene Feuchtigkeit am Sensor
    global second_sensor_dewpoint         #  Gerechneter Taupunkt
    global second_sensor_humidity_abs
    global last_humidity_check_state        # für hysterese int/ext abs. humidity check
    
    global switch_on_cooling_compressor   #  Einschalttemperatur
    global switch_off_cooling_compressor  #  Ausschalttemperatur
    global switch_on_heater                 # Einschalttemperatur
    global switch_off_heater                # Ausschalttemperatur
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
    
    global dehumidifier_modus             #  Modus Entfeuchter  (1 = über Abluft, 2 = mit Abluft zusammen [unterstützend]; 3 = anstelle von Abluft)
    global status_dehumidifier            #  Entfeuchter
    global status_pi_ager
    global temp_sensor1_data
    global temp_sensor2_data 
    global temp_sensor3_data
    global temp_sensor4_data 
    global system_shutdown

    global cooling_Delay_timer
    global cooling_Delay_timer_running
    #--------------------------------------------
    # for event generation
    global internal_temperature_low_limit_reached
    global internal_temperature_high_limit_reached
    global internal_temperature_low_limit
    global internal_temperature_high_limit
    global internal_temperature_hysteresis
    
    global ups_bat_low          # akku ok
    global bat_low_true_count   # counter for bat_low true
    global bat_low_false_count  # counter for bat_low false
    global bat_low_count_max    # max count for bat_low true and false 
    global power_monitor        # power supply ok
    global pi_switch            # switch open
    
    global defrost_status
    global defrost_cycle_seconds
    global defrost_cycle_elapsed
    
    global cooler_failure_off_repeat_counter
    global cooler_failure_on_repeat_counter
    global next_start_time_off_failure
    global next_start_time_on_failure
    
    #-------------------------------------------
    
    # Pruefen Sensor, dann Settings einlesen

    pi_ager_database.write_start_in_database(pi_ager_names.status_pi_ager_key)
    status_pi_ager = 1
    count_continuing_emergency_loops = 0
    humidify_delay_switch = False
    status_exhaust_fan = False          # status set bei mode 4
    status_exhaust_air = False          # relais status --> this is later calculated by status_fan and status_timer and check_int_ext_dewpoint
    status_exhaust_air_timer = False    # status set by timer
    
    last_humidity_check_state = False
    pi_ager_database.write_current_value(pi_ager_names.status_humidity_check_key, 0)
    
    #Here get instance of Deviation class
    cl_fact_logger.get_instance().debug('in doMainLoop()')
    
    # Here init uv_period and light_period to detect change in loop
    uv_period = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_period_key))
    uv_duration = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_duration_key))
    uv_modus = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_modus_key))
    
    light_period = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.light_period_key))
    light_duration = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.light_duration_key))
    light_modus = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.light_modus_key))  
    
    # init temperature limits, generate events if temperature is out of limits
    internal_temperature_low_limit_reached = False
    internal_temperature_high_limit_reached = False
    internal_temperature_low_limit = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.internal_temperature_low_limit_key)
    internal_temperature_high_limit = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.internal_temperature_high_limit_key)
    internal_temperature_hysteresis = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.internal_temperature_hysteresis_key) 

    # for UPS Bat Low, Power Monitor and Switch, check to issue events
    ups_bat_low = True      # akku ok
    bat_low_true_count = 0  # counter for bat_low true
    bat_low_false_count = 0 # counter for bat_low false
    bat_low_count_max = 3   # max count for bat_low true and false 
    power_monitor = True    # power supply ok
    pi_switch = True        # switch open
    
    # init settings to detect change
    exhaust_air_period = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.exhaust_air_period_key))
    exhaust_air_duration = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.exhaust_air_duration_key))
    circulation_air_period = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.circulation_air_period_key))
    circulation_air_duration = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.circulation_air_duration_key))

    defrost_status = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_defrost_key))
    defrost_cycle_seconds = int(pi_ager_database.get_table_value_from_field(pi_ager_names.defrost_table, pi_ager_names.defrost_cycle_hours_field)) * 3600
    defrost_cycle_elapsed = False
    
    cooler_failure_off_repeat_counter = False
    cooler_failure_on_repeat_counter = False
    next_start_time_off_failure = 0
    next_start_time_on_failure = 0
    
    # cabinet simulation    
#    sensor_temperature = 20.0   # start value for test
    
    # mi_data = pi_ager_database.get_table_value_from_field(pi_ager_names.atc_mi_thermometer_data_table, pi_ager_names.mi_data_key)
    # cl_fact_logger.get_instance().info(mi_data)
    # splited_data = mi_data.split(' ')
    # cl_fact_logger.get_instance().info(splited_data)   
    
    try:
        while status_pi_ager == 1 and not system_shutdown:
            #Here check Deviation of measurement
            # check_and_set_light()
            status_pi_ager = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key)
            # update logging levels if changed by FE
            cl_fact_logger.get_instance().update_logger_loglevels()

    #Settings
            # global switch control for light and uv light
            get_global_switch_setting()
            
            # control defrost process
            control_defrost()
            
            # Meat temperature sensors
            temp_sensor_type_index1 = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.meat1_sensortype_key)
            temp_sensor_type_index2 = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.meat2_sensortype_key)
            temp_sensor_type_index3 = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.meat3_sensortype_key)
            temp_sensor_type_index4 = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.meat4_sensortype_key)

            sensor1_parameter = pi_ager_database.get_meatsensor_parameter_row( int(temp_sensor_type_index1) )
            sensor2_parameter = pi_ager_database.get_meatsensor_parameter_row( int(temp_sensor_type_index2) )
            sensor3_parameter = pi_ager_database.get_meatsensor_parameter_row( int(temp_sensor_type_index3) )
            sensor4_parameter = pi_ager_database.get_meatsensor_parameter_row( int(temp_sensor_type_index4) )
 
            temp_sensor1_data = get_temp_sensor_data(sensor1_parameter, 0)
            temp_sensor2_data = get_temp_sensor_data(sensor2_parameter, 1)
            temp_sensor3_data = get_temp_sensor_data(sensor3_parameter, 2)
            temp_sensor4_data = get_temp_sensor_data(sensor4_parameter, 3)
                
            #Sensor
            sht_exception_count = 0
            humidity_exception_count = 0
            sensordata_exception_count = 0
            temperature_exception_count = 0
            dewpoint_exception_count = 0
            humidity_abs_exception_count = 0
            
            #sensortype = int(pi_ager_init.sensortype)
            sensordata = []
            second_sensor_temperature = None
            second_sensor_humidity = None
            second_sensor_dewpoint = None
            second_sensor_humidity_abs = None
            
            sensortype = cl_fact_main_sensor_type.get_instance().get_sensor_type()
            sensordata = get_sensordata(sht_exception_count, humidity_exception_count, temperature_exception_count, dewpoint_exception_count, humidity_abs_exception_count, sensordata_exception_count)
            # cabinet simulation
            sensor_temperature = sensordata['sensor_temperature']
            sensor_humidity = sensordata['sensor_humidity']
            sensor_dewpoint = sensordata['sensor_dewpoint']
            sensor_humidity_abs = sensordata['sensor_humidity_abs']
            
            cl_fact_logger.get_instance().debug("sensor_temperature = " + str(sensor_temperature))
            cl_fact_logger.get_instance().debug("sensor_humidity    = " + str(sensor_humidity))
            cl_fact_logger.get_instance().debug("sensor_dewpoint    = " + str(sensor_dewpoint))
            cl_fact_logger.get_instance().debug("sensor_humidity_abs    = " + str(sensor_humidity_abs))
            
            second_sensor_temperature = sensordata['second_sensor_temperature']
            second_sensor_humidity = sensordata['second_sensor_humidity']
            second_sensor_dewpoint = sensordata['second_sensor_dewpoint']
            second_sensor_humidity_abs = sensordata['second_sensor_humidity_abs']
            
            cl_fact_logger.get_instance().debug("external sensor_temperature = " + str(second_sensor_temperature))
            cl_fact_logger.get_instance().debug("external sensor_humidity    = " + str(second_sensor_humidity))
            cl_fact_logger.get_instance().debug("external sensor_dewpoint    = " + str(second_sensor_dewpoint))
            cl_fact_logger.get_instance().debug("external sensor_humidity_abs    = " + str(second_sensor_humidity_abs))
            
            # Prüfen, ob Sensordaten empfangen wurden und falls nicht, auf Notfallmodus wechseln
            if sensor_temperature != None and sensor_humidity != None and sensor_dewpoint != None:
                count_continuing_emergency_loops = 0
                #weitere Settings
                modus = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.modus_key)
                setpoint_temperature = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_temperature_key)
                setpoint_humidity = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_humidity_key))
                
                circulation_air_period_temp = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.circulation_air_period_key))
                circulation_air_duration_temp = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.circulation_air_duration_key))
                
                exhaust_air_period_temp = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.exhaust_air_period_key))
                exhaust_air_duration_temp = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.exhaust_air_duration_key))
                defrost_cycle_seconds_temp = int(pi_ager_database.get_table_value_from_field(pi_ager_names.defrost_table, pi_ager_names.defrost_cycle_hours_field)) * 3600
                
                cooling_hysteresis = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.cooling_hysteresis_key)
                heating_hysteresis = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.heating_hysteresis_key)
                switch_on_cooling_compressor = cooling_hysteresis/2
                switch_off_cooling_compressor = -cooling_hysteresis/2
                switch_on_heater = heating_hysteresis/2
                switch_off_heater = -heating_hysteresis/2
                    
                switch_on_humidifier = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_humidifier_key)
                switch_off_humidifier = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_off_humidifier_key)
                
                delay_humidify = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.delay_humidify_key))
                delay_humidify = delay_humidify * 60
                
                uv_modus_temp = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_modus_key))
                switch_on_uv_hour = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_uv_hour_key))
                switch_on_uv_minute = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_uv_minute_key))
                uv_duration_temp = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_duration_key))
                uv_period_temp = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_period_key)) 
                uv_check = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_check_key))
                
                current_time = pi_ager_database.get_current_time()
                
                # check if uv settings changed
                if (uv_period_temp != uv_period or uv_duration_temp != uv_duration or uv_modus_temp != uv_modus):
                    uv_period = uv_period_temp
                    uv_modus = uv_modus_temp
                    uv_duration = uv_duration_temp
                    pi_ager_init.uv_starttime = current_time
                    pi_ager_init.uv_stoptime = pi_ager_init.uv_starttime + uv_duration
                
                # check if exhaust_air period or duration changed  
                if (exhaust_air_period_temp != exhaust_air_period or exhaust_air_duration_temp != exhaust_air_duration):
                    exhaust_air_period = exhaust_air_period_temp
                    exhaust_air_duration = exhaust_air_duration_temp
                    pi_ager_init.exhaust_air_start = current_time   # Timer-Timestamp aktualisiert

                # check if circulation_air period or duration changed
                if (circulation_air_period_temp != circulation_air_period or circulation_air_duration_temp != circulation_air_duration):
                    circulation_air_period = circulation_air_period_temp
                    circulation_air_duration = circulation_air_duration_temp
                    pi_ager_init.circulation_air_start = current_time   # Timer-Timestamp aktualisiert
                
                # check if defrost_cycle_seconds changed
                if (defrost_cycle_seconds_temp != defrost_cycle_seconds):
                    defrost_cycle_seconds = defrost_cycle_seconds_temp
                    pi_ager_init.defrost_cycle_start = current_time   # Timer-Timestamp aktualisiert
                    
                light_modus_temp = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.light_modus_key))
                switch_on_light_hour = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_light_hour_key))
                switch_on_light_minute = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_light_minute_key))
                light_duration_temp = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.light_duration_key))
                light_period_temp = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.light_period_key))
                
                # check if light settings changed
                if (light_period_temp != light_period or light_duration_temp != light_duration or light_modus_temp != light_modus):
                    light_period = light_period_temp
                    light_modus = light_modus_temp
                    light_duration = light_duration_temp
                    pi_ager_init.light_starttime = current_time
                    pi_ager_init.light_stoptime = pi_ager_init.light_starttime + light_duration
                    
                dehumidifier_modus = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.dehumidifier_modus_key))
    
                # An dieser Stelle sind alle settings eingelesen, Ausgabe auf Konsole
                
                logstring = ' \n ' + pi_ager_names.logspacer
                # logstring = logstring + ' \n ' + 'Main loop/Unix-Timestamp: (' + str(current_time)+ ')'
                #cl_fact_logger.get_instance().debug(logstring)
                # logstring = logstring + ' \n ' + pi_ager_names.logspacer2
                #cl_fact_logger.get_instance().debug(logstring)
                logstring = logstring + ' \n ' +  _('selected internal sensor') + ': ' + str(cl_fact_main_sensor_type.get_instance().get_sensor_type_ui())
                
                logstring = logstring + ' \n ' + _('target temperature') + ': ' + str(setpoint_temperature) + ' °C'
                #cl_fact_logger.get_instance().debug(logstring)
                logstring = logstring + ' \n ' +  _('actual temperature') + ': ' + f'{sensor_temperature:.2f}' + ' °C'
                #cl_fact_logger.get_instance().debug(logstring)
                logstring = logstring + ' \n ' + pi_ager_names.logspacer2
                logstring = logstring + ' \n ' +  _('target humidity') + ': ' + str(setpoint_humidity) + ' %'
                logstring = logstring + ' \n ' +  _('actual humidity') + ': ' + str(sensor_humidity) + ' %'
                #cl_fact_logger.get_instance().debug(logstring)
                logstring = logstring + ' \n ' +  _('actual dewpoint') + ': ' + str(sensor_dewpoint) + ' °C'
                logstring = logstring + ' \n ' +  _('actual humidity abs') + ': ' + str(sensor_humidity_abs) + ' g/m³'
                #cl_fact_logger.get_instance().debug(logstring)
                logstring = logstring + ' \n ' + pi_ager_names.logspacer2
                if (str(cl_fact_second_sensor_type.get_instance().get_sensor_type_ui()) != 'disabled'):
                    logstring = logstring + ' \n ' +  _('selected external sensor') + ': ' + str(cl_fact_second_sensor_type.get_instance().get_sensor_type_ui())
                    logstring = logstring + ' \n ' +  _('actual temperature') + ': ' + str(second_sensor_temperature) + ' °C'
                    logstring = logstring + ' \n ' +  _('actual humidity') + ': ' + str(second_sensor_humidity) + ' %'
                    logstring = logstring + ' \n ' +  _('actual dewpoint') + ': ' + str(second_sensor_dewpoint) + ' °C'  
                    logstring = logstring + ' \n ' +  _('actual humidity abs') + ': ' + str(second_sensor_humidity_abs) + ' g/m³'
                    logstring = logstring + ' \n ' + pi_ager_names.logspacer2
               
                #cl_fact_logger.get_instance().debug(_('value in database') + ': ' + str(sensortype))
                # logger.info(pi_ager_names.logspacer2)
                
                # gpio.setmode(pi_ager_names.board_mode)
                
                # Durch den folgenden Timer laeuft der Ventilator in den vorgegebenen Intervallen zusaetzlich zur generellen Umluft bei aktivem Heizen, Kuehlen oder Befeuchten
                # Timer fuer Luftumwaelzung-Ventilator
                if (circulation_air_period == 0 and circulation_air_duration == 0):
                    status_circulating_air = False  # no timer
                    logstring = logstring + ' \n ' +  _('circulation air timer inactive') + ' (' + _('fan permanent off') +')'
                elif circulation_air_period == 0:                          # gleich 0 ist an,  Dauer-EIN
                    status_circulating_air = True
                    logstring = logstring + ' \n ' +  _('circulation air timer inactive') + ' (' + _('fan permanent on') +')'
                elif circulation_air_duration == 0:                        # gleich 0 ist aus, Dauer-AUS
                    status_circulating_air = False
                    logstring = logstring + ' \n ' +  _('circulation air timer inactive') + ' (' + _('fan permanent off') +')'
                else:
                    if current_time < pi_ager_init.circulation_air_start + circulation_air_duration:
                        status_circulating_air = True                       # Umluft - Ventilator an
                        logstring = logstring + ' \n ' +  _('circulation air timer active') + ' (' + _('fan on') +')'
                        # cl_fact_logger.get_instance().debug(logstring)
                    if current_time >= pi_ager_init.circulation_air_start + circulation_air_duration:
                        status_circulating_air = False                      # Umluft - Ventilator aus
                        logstring = logstring + ' \n ' +  _('circulation air timer active') + ' (' + _('fan off') +')'
                        # cl_fact_logger.get_instance().debug(logstring)
                    if current_time >= pi_ager_init.circulation_air_start + circulation_air_period + circulation_air_duration:
                        pi_ager_init.circulation_air_start = current_time    # Timer-Timestamp aktualisiert
                        
                # Timer fuer (Abluft-)Luftaustausch-Ventilator
                if (exhaust_air_period == 0 and exhaust_air_duration == 0):
                    status_exhaust_air_timer = False
                    logstring = logstring + ' \n ' +  _('exhaust air timer inactive') + ' (' + _('fan permanent off') +')'
                elif exhaust_air_period == 0:                      # gleich 0 ist an,  Dauer-Timer
                    status_exhaust_air_timer = True
                    logstring = logstring + ' \n ' +  _('exhaust air timer inactive') + ' (' + _('fan permanent on') +')'
                elif exhaust_air_duration == 0:                        # gleich 0 ist aus, kein Timer
                    status_exhaust_air_timer = False
                    logstring = logstring + ' \n ' +  _('exhaust air timer inactive') + ' (' + _('fan permanent off') +')'
                else:
                    if current_time < pi_ager_init.exhaust_air_start + exhaust_air_duration:
                        status_exhaust_air_timer = True                      # (Abluft-)Luftaustausch-Ventilator aus
                        logstring = logstring + ' \n ' +  _('exhaust air timer active') + ' (' + _('fan on') +')'
                        # cl_fact_logger.get_instance().debug(logstring)
                    if current_time >= pi_ager_init.exhaust_air_start + exhaust_air_duration:
                        status_exhaust_air_timer = False                     # (Abluft-)Luftaustausch-Ventilator an
                        logstring = logstring + ' \n ' +  _('exhaust air timer active') + ' (' + _('fan off') +')'
                        # cl_fact_logger.get_instance().debug(logstring)
                    if current_time >= pi_ager_init.exhaust_air_start + exhaust_air_period + exhaust_air_duration:
                        pi_ager_init.exhaust_air_start = current_time       # Timer-Timestamp aktualisiert
                        
                # Timer fuer UV-Licht
                if uv_modus == 0:                               # Modus 0 UV-Licht aus
                    status_uv = False                           # UV-Licht aus
                    logstring = logstring + ' \n ' +  _('modus uv-light') + ': ' + _('off')
                    # cl_fact_logger.get_instance().debug(logstring)
                
                if uv_modus == 1:                               # Modus 1 = EIN/AUS Dauer
                    logstring = logstring + ' \n ' +  _('modus uv-light') + ': ' + _('on')
                    if (uv_period == 0 and uv_duration == 0):   # beide 0: kein Timer
                        status_uv = False
                        logstring = logstring + ' \n ' +  _('uv-light timer inactive') + ' (' + _('uv-light permanent off') +')'
                    elif uv_duration == 0:                      # EIN Dauer gleich 0, kein Timer, dauernd AUS
                        status_uv = False
                        logstring = logstring + ' \n ' +  _('uv-light timer inactive') + ' (' + _('uv-light permanent off') +')'
                    elif uv_period == 0:                        # AUS Dauer gleich 0, kein Timer, dauernd EIN
                        status_uv = True
                        logstring = logstring + ' \n ' +  _('uv-light timer inactive') + ' (' + _('uv-light permanent on') +')'
                    else:                                       # beide nicht null, Timer                
                        if pi_ager_init.uv_stoptime == pi_ager_init.system_starttime:           # first run
                            pi_ager_init.uv_stoptime = pi_ager_init.uv_starttime + uv_duration
                            status_uv = False
                        if current_time >= pi_ager_init.uv_starttime and current_time <= pi_ager_init.uv_stoptime:
                            status_uv = True                     # UV-Licht an
                            logstring = logstring + ' \n ' +  _('uv-light timer active') + ' (' + _('uv-light on') +')'
                            # cl_fact_logger.get_instance().debug(logstring)
                            # cl_fact_logger.get_instance().debug('UV-Licht Startzeit: ' + convert(pi_ager_init.uv_starttime))
                            # cl_fact_logger.get_instance().debug('UV-Licht Stoppzeit: ' + convert(pi_ager_init.uv_stoptime))
                            # cl_fact_logger.get_instance().debug('UV-Licht duration: ' + str(uv_duration))
                        else: 
                            status_uv = False                      # UV-Licht aus
                            logstring = logstring + ' \n ' +  _('uv-light timer active') + ' (' + _('uv-light off') +')'
                            # cl_fact_logger.get_instance().debug(logstring)
                            # cl_fact_logger.get_instance().debug('UV-Licht Stoppzeit: ' + convert(pi_ager_init.uv_stoptime))
                            # cl_fact_logger.get_instance().debug('UV-Licht Startzeit: ' + convert(pi_ager_init.uv_starttime))
                            # cl_fact_logger.get_instance().debug('UV-Licht period: ' + str(uv_period))
    
                        if current_time > pi_ager_init.uv_stoptime:
                            pi_ager_init.uv_starttime = current_time + uv_period  # Timer-Timestamp aktualisiert
                            pi_ager_init.uv_stoptime = pi_ager_init.uv_starttime + uv_duration
    
                if uv_modus == 2:                         # Modus 2 Zeitstempel/Dauer
                    if (uv_duration == 0):
                        status_uv = False                 # UV-Licht aus
                        logstring = logstring + ' \n ' +  _('uv-light timestamp inactive') + ' (' + _('uv-light permanent off') +')'
                    else:
                        now = datetime.datetime.now()
                        year_now = now.year
                        month_now = now.month
                        day_now = now.day
    
                        pi_ager_init.uv_starttime = datetime.datetime(year_now, month_now, day_now, switch_on_uv_hour, switch_on_uv_minute, 0, 0)
                        pi_ager_init.uv_stoptime = pi_ager_init.uv_starttime + datetime.timedelta(0, uv_duration)
    
                        if now >= pi_ager_init.uv_starttime and now <= pi_ager_init.uv_stoptime:
                            status_uv = True                     # UV-Licht an
                            logstring = logstring + ' \n ' +  _('uv-light timestamp active') + ' (' + _('uv-light on') +')'
                            # cl_fact_logger.get_instance().debug(logstring)
                        else: 
                            status_uv = False                      # UV-Licht aus
                            logstring = logstring + ' \n ' +  _('uv-light timestamp active') + ' (' + _('uv-light off') +')'
                            # cl_fact_logger.get_instance().debug(logstring)
    
                # Timer fuer Licht
                if light_modus == 0:                            # Modus 0 Licht aus
                    status_light = False                        # Licht aus
                    logstring = logstring + ' \n ' +  _('modus light') + ': ' + _('off')
                    # cl_fact_logger.get_instance().debug(logstring)
                
                if light_modus == 1:                            # Modus 1 = EIN/AUS Dauer
                    logstring = logstring + ' \n ' +  _('modus light') + ': ' + _('on')
                    if (light_period == 0 and light_duration == 0):   # beide 0: kein Timer
                        status_light = False
                        logstring = logstring + ' \n ' +  _('light timer inactive') + ' (' + _('light permanent off') +')'
                        # cl_fact_logger.get_instance().info("Light period and duration are both 0")
                    elif light_duration == 0:                   # EIN Dauer gleich 0, kein Timer, dauernd AUS
                        status_light = False
                        logstring = logstring + ' \n ' +  _('light timer inactive') + ' (' + _('light permanent off') +')'
                        # cl_fact_logger.get_instance().info("Only duration is 0")
                    elif light_period == 0:                     # AUS Dauer gleich 0, kein Timer, dauernd EIN
                        status_light = True
                        logstring = logstring + ' \n ' +  _('light timer inactive') + ' (' + _('light permanent on') +')'
                        # cl_fact_logger.get_instance().info("Only period is 0")
                    else:                                       # beide nicht null, Timer                 
                        if pi_ager_init.light_stoptime == pi_ager_init.system_starttime:         # first run
                            pi_ager_init.light_stoptime = pi_ager_init.light_starttime + light_duration
                            status_light = False
                        if current_time >= pi_ager_init.light_starttime and current_time <= pi_ager_init.light_stoptime:
                            status_light = True                     # Licht an
                            logstring = logstring + ' \n ' +  _('light timer active') + ' (' + _('light on') +')'
                            # cl_fact_logger.get_instance().debug(logstring)
                            # cl_fact_logger.get_instance().debug('Licht Startzeit: ' + str(pi_ager_init.light_starttime))
                            # cl_fact_logger.get_instance().debug('Licht Stoppzeit: ' + str(pi_ager_init.light_stoptime))
                            # cl_fact_logger.get_instance().debug('Licht duration: ' + str(light_duration))
                        else: 
                            status_light = False                    # Licht aus
                            logstring = logstring + ' \n ' +  _('light timer active') + ' (' + _('light off') +')'
                            # cl_fact_logger.get_instance().debug(logstring)
                            # cl_fact_logger.get_instance().debug('Licht Stoppzeit: ' + str(pi_ager_init.light_stoptime))
                            # cl_fact_logger.get_instance().debug('Licht Startzeit: ' + str(pi_ager_init.light_starttime))
                            # cl_fact_logger.get_instance().debug('Licht period: ' + str(light_period))
    
                        if current_time > pi_ager_init.light_stoptime:
                            pi_ager_init.light_starttime = current_time + light_period  # Timer-Timestamp aktualisiert
                            pi_ager_init.light_stoptime = pi_ager_init.light_starttime + light_duration
                        
                        # cl_fact_logger.get_instance().info(f"Both not 0, state is {status_light}")
                        
                if light_modus == 2:                         # Modus 2 Zeitstempel/Dauer
                    if (light_duration == 0):
                        status_light = False                 # Licht aus
                        logstring = logstring + ' \n ' +  _('light timestamp inactive') + ' (' + _('light permanent off') +')'
                    else:
                        now = datetime.datetime.now()
                        year_now = now.year
                        month_now = now.month
                        day_now = now.day
    
                        pi_ager_init.light_starttime = datetime.datetime(year_now, month_now, day_now, switch_on_light_hour, switch_on_light_minute, 0, 0)
                        pi_ager_init.light_stoptime = pi_ager_init.light_starttime + datetime.timedelta(0, light_duration)
    
                        if now >= pi_ager_init.light_starttime and now <= pi_ager_init.light_stoptime:
                            status_light = True                     # Licht an
                            logstring = logstring + ' \n ' +  _('light timestamp active') + ' (' + _('light on') +')'
                            # cl_fact_logger.get_instance().debug(logstring)
                        else: 
                            status_light = False                      # Licht aus
                            logstring = logstring + ' \n ' +  _('light timestamp active') + ' (' + _('light off') +')'
                            # cl_fact_logger.get_instance().debug(logstring)
                
                # timer for defrost              
                if current_time >= pi_ager_init.defrost_cycle_start + defrost_cycle_seconds:
                    pi_ager_init.defrost_cycle_start = current_time    # Timer-Timestamp aktualisiert
                    defrost_cycle_elapsed = True
                    cl_fact_logger.get_instance().debug('defrost cycle elapsed')
                
                #-------------------------------
                # Dry aging mode processing
                #-------------------------------
                if (modus != 4):
                    last_humidity_check_state = False
                    pi_ager_database.write_current_value(pi_ager_names.status_humidity_check_key, 0)
                    
                # Kuehlen
                if modus == 0:
                    # turn off states possibly set by other modes
                    status_exhaust_fan = False         # Feuchtereduzierung Abluft aus
                    status_dehumidifier = False        # Entfeuchter aus
                    # gpio.output(pi_ager_gpio_config.gpio_heater, pi_ager_names.relay_off)                   # Heizung aus
                    control_heater(pi_ager_names.relay_off)
                    gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_off)                 # Befeuchtung aus
                    
                    simple_cooler_temperature_control()

                # Kuehlen mit Befeuchtung
                if modus == 1:
                    # turn off states possibly set by other modes
                    status_exhaust_fan = False         # Feuchtereduzierung Abluft aus
                    status_dehumidifier = False        # Entfeuchter aus
                    # gpio.output(pi_ager_gpio_config.gpio_heater, pi_ager_names.relay_off)      # Heizung aus
                    control_heater(pi_ager_names.relay_off)
                    
                    simple_cooler_temperature_control()

                    # check if humidifier must be set on or off    
                    if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                        # check if delay time passed
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
                    # turn off states possibly set by other modes
                    status_exhaust_fan = False         # Feuchtereduzierung Abluft aus
                    status_dehumidifier = False        # Entfeuchter aus
                    delay_cooling_compressor( pi_ager_names.relay_off)        # Kuehlung aus

                    simple_heater_temperature_control()
                    
                    # check if humidifier must be set on or off   
                    if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                        # check if delay time passed
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
                    # turn off states possibly set by other modes
                    status_exhaust_fan = False         # Feuchtereduzierung Abluft aus
                    status_dehumidifier = False        # Entfeuchter aus
                    
                    auto_temperature_control()
                            
                    # check if humidifier must be set on or off  
                    if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                        if not humidify_delay_switch:
                            humidify_delay_switch = True
                            humidify_delay_starttime = pi_ager_database.get_current_time()
                        if pi_ager_database.get_current_time() >= humidify_delay_starttime + delay_humidify:      # Verzoegerung der Luftbefeuchtung
                            gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_on)  # Luftbefeuchter ein
                    if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                        gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_off)     # Luftbefeuchter aus
                        humidify_delay_switch = False
    
                # Automatik mit Befeuchtung und Entfeuchtung durch (Abluft-)Luftaustausch
                if modus == 4:
                    # Wenn in der toten Zone der Hysterese, nehmen wir den alten Wert
                    status_dehumidifier = not gpio.input(pi_ager_gpio_config.gpio_dehumidifier)       # Entfeuchter alter Wert
                    status_exhaust_fan = not gpio.input(pi_ager_gpio_config.gpio_exhausting_air)      # Abluft alter Wert
                    
                    auto_temperature_control()

                    # check if humidifier must be set on or off     
                    if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                        if not humidify_delay_switch:
                            humidify_delay_switch = True
                            humidify_delay_starttime = pi_ager_database.get_current_time()
                        if pi_ager_database.get_current_time() >= humidify_delay_starttime + delay_humidify:      # Verzoegerung der Luftbefeuchtung
                            gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_on)  # Luftbefeuchter ein
                    if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                        gpio.output(pi_ager_gpio_config.gpio_humidifier, pi_ager_names.relay_off)     # Luftbefeuchter aus
                        humidify_delay_switch = False

                    # cl_fact_logger.get_instance().debug(_('Sensor humidity =') + str(sensor_humidity))
                    # cl_fact_logger.get_instance().debug(_('Setpoint humidity =') + str(setpoint_humidity))
                    # cl_fact_logger.get_instance().debug(_('Switch on humidifier =') + str(switch_on_humidifier))
                    
                    # check if humidity too high => dehumifify
                    if sensor_humidity >= setpoint_humidity + switch_on_humidifier:
                        # cl_fact_logger.get_instance().debug('sensor_humidity >= setpoint_humidity + switch_on_humidifier:')
                        # cl_fact_logger.get_instance().debug('Dehumidifier modus =' + ': ' + str(dehumidifier_modus))
                        if dehumidifier_modus == 1 or dehumidifier_modus == 2:  # entweder nur über Abluft oder Abluft mit Unterstützung von Entfeuchter
                            # cl_fact_logger.get_instance().debug(_('Dehumidifier Modus 1 or 2: Status Exhaust fan = On'))
                            status_exhaust_fan = True                           # Feuchtereduzierung Abluft-Ventilator ein
                            if dehumidifier_modus == 2:                         # Entfeuchter zur Unterstützung
                                # cl_fact_logger.get_instance().debug(_('Status Dehumidifier = On'))
                                status_dehumidifier = True                      # Entfeuchter unterstützend ein
                            else:
                                # cl_fact_logger.get_instance().debug(_('Status Dehumidifier = Off'))
                                status_dehumidifier = False                     # Entfeuchter aus
                                
                        if dehumidifier_modus == 3:                             # rein über entfeuchtung
                            status_exhaust_fan = False                          # Abluft aus
                            status_dehumidifier = True                          # Entfeuchter ein
                            # cl_fact_logger.get_instance().debug(_('Dehumidifier modus 3: Status Exhaust fan = Off, Status Dehumidifier = On'))
                            
                    if sensor_humidity <= setpoint_humidity + switch_off_humidifier:
                        cl_fact_logger.get_instance().debug('sensor_humidity <= setpoint_humidity + switch_off_humidifier')
                        if dehumidifier_modus == 1 or dehumidifier_modus == 2:
                           status_exhaust_fan = False         # Feuchtereduzierung Abluft-Ventilator aus
                           status_dehumidifier = False        # Entfeuchter aus
                        else:
                           status_dehumidifier = False        # Entfeuchter aus
                
                
                # Schalten des UV_Licht
                if status_uv == True and pi_ager_database.get_status_uv_manual() == 1:
                    switch_uv_light(pi_ager_names.relay_on)
                    # gpio.output(pi_ager_gpio_config.gpio_uv, pi_ager_names.relay_on)
                else:   #if status_uv == False or pi_ager_database.get_status_uv_manual() == 0:
                    switch_uv_light(pi_ager_names.relay_off)
                    status_uv = False
                    # gpio.output(pi_ager_gpio_config.gpio_uv, pi_ager_names.relay_off)
                
                # Schalten des Licht
                # cl_fact_logger.get_instance().info(f"Status light check befor switch_light is {status_light}")
                if status_light == True or pi_ager_database.get_status_light_manual() == 1:
                    switch_light(pi_ager_names.relay_on)
                    # gpio.output(pi_ager_names.gpio_light, pi_ager_names.relay_on)
                else:   #if status_light == False:   #  and pi_ager_database.get_status_light_manual() == 0:
                    switch_light(pi_ager_names.relay_off)
                    status_light = False
                    # gpio.output(pi_ager_names.gpio_light, pi_ager_names.relay_off)
                
                # turn on circulation air when uv_check is true and UV-Light is ON
                
                if (gpio.input(pi_ager_gpio_config.gpio_uv) == False and uv_check == True):
                    status_circulating_air = True
                    
                # Schalten des Umluft - Ventilators
                if not gpio.input(pi_ager_gpio_config.gpio_heater) or not gpio.input(pi_ager_gpio_config.gpio_cooling_compressor) or not gpio.input(pi_ager_gpio_config.gpio_humidifier) or status_circulating_air == True:
                    # gpio.output(pi_ager_gpio_config.gpio_circulating_air, pi_ager_names.relay_on)               # Umluft - Ventilator an
                    control_circulating_air( pi_ager_names.relay_on )
                    status_circulating_air = True
                else:       # if gpio.input(pi_ager_gpio_config.gpio_heater) and gpio.input(pi_ager_gpio_config.gpio_cooling_compressor) and gpio.input(pi_ager_gpio_config.gpio_humidifier) and status_circulating_air == False:
                    # gpio.output(pi_ager_gpio_config.gpio_circulating_air, pi_ager_names.relay_off)             # Umluft - Ventilator aus
                    control_circulating_air( pi_ager_names.relay_off )
                    
                # Schalten des Entfeuchters
                if status_dehumidifier == True:
                    gpio.output(pi_ager_gpio_config.gpio_dehumidifier, pi_ager_names.relay_on)
                else:
                    gpio.output(pi_ager_gpio_config.gpio_dehumidifier, pi_ager_names.relay_off)
                
                # Schalten des (Abluft-)Luftaustausch-Ventilator
                # Einschalten immer, wenn der status_exhaust_air_timer true ist. Der status_exhaust_fan wird
                # verknüpft mit check_int_ext_humidity, d.h.wenn externe abs. Feuchte höher ist als interne abs. Feuchte und
                # die Feuchteprüfung aktiv ist, dann wird der Luftaustausch-Ventilator nicht eingeschaltet.
                # Priorität hat jedoch der xhaust_air_timer
                # 
                cl_fact_logger.get_instance().debug("status_exhaust_air = %r" % status_exhaust_air_timer) 
                cl_fact_logger.get_instance().debug("status_exhaust_fan = %r" % status_exhaust_fan)
                
                # verknüpfe check_int_ext_humidity mit status_exhaust_fan aus der Regelung
                check_result = check_int_ext_humidity()
                status_exhaust_fan = status_exhaust_fan and not check_result
                
                if (status_exhaust_air_timer == True or status_exhaust_fan == True):
                    gpio.output(pi_ager_gpio_config.gpio_exhausting_air, pi_ager_names.relay_on)
                    status_exhaust_air = True
                else:
                    gpio.output(pi_ager_gpio_config.gpio_exhausting_air, pi_ager_names.relay_off)
                    status_exhaust_air = False

                # Lesen der Scales Daten
                # scale1_row = pi_ager_database.get_scale_table_row(pi_ager_names.data_scale1_table)
                scale1_row = pi_ager_database.get_table_value_last_change(pi_ager_names.current_values_table, pi_ager_names.scale1_key)
                if scale1_row == None:
                    scale1_data = None
                else:
                    scale1_value = scale1_row[pi_ager_names.value_field]
#                    if scale1_value == None:
#                        scale1_value = 0
                    scale1_last_change = scale1_row[pi_ager_names.last_change_field]
                    timediff_scale1 = pi_ager_database.get_current_time() - scale1_last_change
                    if timediff_scale1 < 12: # (ist nicht zuverlässig, wenn scale measure loop = ca 5s. Da brauchen wir etwas anderes...) pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table,pi_ager_names.scale_measuring_interval_key):
                        scale1_data = scale1_value
                    else:
                        scale1_data = None
    
                # scale2_row = pi_ager_database.get_scale_table_row(pi_ager_names.data_scale2_table)
                scale2_row = pi_ager_database.get_table_value_last_change(pi_ager_names.current_values_table, pi_ager_names.scale2_key)
                if scale2_row == None:
                    scale2_data = None
                else:
                    scale2_value = scale2_row[pi_ager_names.value_field]
#                    if scale2_value == None:
#                        scale2_value = 0
                    scale2_last_change = scale2_row[pi_ager_names.last_change_field]
                    timediff_scale2 = pi_ager_database.get_current_time() - scale2_last_change
                    if timediff_scale2 < 12: # (ist nicht zuverlässig, wenn scale measure loop = ca 5s. Da brauchen wir etwas anderes...) pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table,pi_ager_names.scale_measuring_interval_key):
                        scale2_data = scale2_value
                    else:
                        scale2_data = None
                
                # Ausgabe der Werte auf der Konsole
                # logger.info(pi_ager_names.logspacer2)
                logstring = logstring + ' \n ' + pi_ager_names.logspacer2

                if scale1_data == None:
                    logstring = logstring + ' \n ' +  _('weight scale') + ' 1: ' + '------'
                else:
                    logstring = logstring + ' \n ' +  _('weight scale') + ' 1: ' + str(round(scale1_data)) + ' g'
                    
                if scale2_data == None:
                    logstring = logstring + ' \n ' +  _('weight scale') + ' 2: ' + '------'
                else:
                    logstring = logstring + ' \n ' +  _('weight scale') + ' 2: ' + str(round(scale2_data)) + ' g'
                
                (log_changed, log, log_html) = status_value_has_changed()
                
                log_html = logstring + ' \n ' + log_html + '\n' + pi_ager_names.logspacer2 + '\n'
                log_event = logstring + ' \n ' + log + '\n' + pi_ager_names.logspacer2 + '\n'
                cl_fact_logger.get_instance().debug(log_event)
                if log_changed:
                    # Logstring komplett schreiben
                    cl_fact_logger.get_instance().info(log_html)
                    generate_status_change_event(log_event)
                
                # check on cooler failure
                generate_cooler_failure_events()
                
                # Messwerte in die RRD-Datei schreiben
                # Schreiben der aktuellen Status-Werte
                pi_ager_database.write_current(sensor_temperature, status_heater, status_exhaust_air, status_cooling_compressor, status_circulating_air, sensor_humidity, sensor_dewpoint, sensor_humidity_abs, second_sensor_temperature, second_sensor_humidity, second_sensor_dewpoint, second_sensor_humidity_abs, status_uv, status_light, status_humidifier, status_dehumidifier, temp_sensor1_data, temp_sensor2_data, temp_sensor3_data, temp_sensor4_data)
                pi_ager_database.write_all_sensordata(pi_ager_init.loopcounter, sensor_temperature, sensor_humidity, sensor_dewpoint, second_sensor_temperature, second_sensor_humidity, second_sensor_dewpoint, temp_sensor1_data, temp_sensor2_data, temp_sensor3_data, temp_sensor4_data, sensor_humidity_abs, second_sensor_humidity_abs)    
                cl_fact_logger.get_instance().debug('writing current values in database performed')
                
                # check if events should be issued
                cl_fact_logger.get_instance().debug('checking internal temperature sensor limits')
                check_internal_temperature_limits()
                cl_fact_logger.get_instance().debug('checking UPS state')
                generate_ups_bat_events()
                cl_fact_logger.get_instance().debug('checking power monitor')
                generate_power_monitor_events()
                cl_fact_logger.get_instance().debug('checking switch')
                generate_switch_event()
                # cabinet simulation
#                if (second_sensor_temperature == None or second_sensor_temperature > sensor_temperature):     
#                    sensor_temperature += 0.005 # simulate increasing cabinet temperature over time
#                else:
#                    sensor_temperature -= 0.005 # simulate decreasing cabinet temperature over time
                    
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
    
            # every 5 seconds a new measurement
            time.sleep(5)  
            
            # Logfile auf Rechte prüfen und evtl. neu setzen
            
            # filepermission = oct(os.stat(pi_ager_paths.logfile_txt_file)[stat.ST_MODE])[-3:]
            # if (filepermission != '666'):
                # os.chmod(pi_ager_paths.get_path_logfile_txt_file(), stat.S_IWOTH|stat.S_IWGRP|stat.S_IWUSR|stat.S_IROTH|stat.S_IRGRP|stat.S_IRUSR)
            #pi_ager_logging.check_website_logfile()
            #  cl_fact_logger.get_instance().check_website_logfile() 
            # Mainloop fertig
            cl_fact_logger.get_instance().debug('loop complete')
            pi_ager_init.loopcounter += 1

            
    # Ende While-Schleife
        cl_fact_logger.get_instance().debug('status != 1 or shutdown')

        # reflect i/o status in DB
        switch_light(pi_ager_names.relay_off)
        switch_uv_light(pi_ager_names.relay_off)
        pi_ager_database.write_current(sensor_temperature, 0, 0, 0, 0, sensor_humidity, sensor_dewpoint, sensor_humidity_abs, second_sensor_temperature, second_sensor_humidity, second_sensor_dewpoint, second_sensor_humidity_abs, 0, 0, 0, 0, temp_sensor1_data, temp_sensor2_data, temp_sensor3_data, temp_sensor4_data) 
        cl_fact_logger.get_instance().debug('in loop try end -----------------------------------------------------')
        
    except Exception as cx_error:
        cl_fact_logger.get_instance().debug('in loop exception-----------------------------------------------------')
        cl_fact_logic_messenger().get_instance().handle_exception(cx_error)

    finally:
        cl_fact_logger.get_instance().debug('in loop finally-----------------------------------------------------')  
        if (cooling_Delay_timer_running):
            cooling_Delay_timer.cancel()
            
        pi_ager_gpio_config.defaultGPIO()
        # reflect i/o status in DB
        # pi_ager_database.write_current(sensor_temperature, 0, 0, 0, 0, sensor_humidity, sensor_dewpoint, sensor_humidity_abs, second_sensor_temperature, second_sensor_humidity, second_sensor_dewpoint, second_sensor_humidity_abs, 0, 0, 0, 0, temp_sensor1_data, temp_sensor2_data, temp_sensor3_data, temp_sensor4_data)     