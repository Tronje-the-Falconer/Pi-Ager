#!/usr/bin/python3
import os
import time
import datetime
import Adafruit_DHT
import RPi.GPIO as gpio
from pi_sht1x import SHT1x
import pi_ager_database
import pi_ager_names
import pi_ager_init
from pi_ager_logging import create_logger
import pi_ager_gpio_config
import pi_ager_organization

global logger
logger = create_logger(__name__)
logger.debug('logging initialised')

def autostart_loop():
    global status_pi_ager
    global logger
    
    pi_ager_gpio_config.setupGPIO() # GPIO initialisieren
    pi_ager_gpio_config.defaultGPIO()
    while True:
        status_pi_ager = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key)
        status_agingtable = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_agingtable_key)
        current_agingtable_period = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.agingtable_period_key)
        check_and_set_light()
        
        logger.debug('autostart_loop ' + time.strftime('%H:%M:%S', time.localtime()))
        if status_agingtable == 1:
            os.system('sudo /var/sudowebscript.sh startagingtable &')
            doMainLoop()
        elif status_pi_ager == 1:
            doMainLoop()
        time.sleep(5)

def get_sensordata():
    global logger
    
    if pi_ager_init.sensorname == 'DHT11' or pi_ager_init.sensorname == 'DHT22': 
        sensor_humidity_big, sensor_temperature_big = Adafruit_DHT.read_retry(pi_ager_init.sensor, pi_ager_names.gpio_sensor_data)
        logger.debug("sensor_temperature: " + str(sensor_temperature_big))
        logger.debug("sensor_humidity_big: " + str(sensor_humidity_big))
        # atp = 17.271  ermittelt aus dem Datenblatt DHT11 und DHT22
        # btp = 237.7   ermittelt aus dem Datenblatt DHT11 und DHT22
    
    elif pi_ager_init.sensorname == 'SHT': #SHT
        sensor_sht = SHT1x(pi_ager_names.gpio_sensor_data, pi_ager_names.gpio_sensor_sync, gpio_mode=pi_ager_names.board_mode)
        sensor_sht.read_temperature()
        sensor_sht.read_humidity()
        sensor_temperature_big = sensor_sht.temperature_celsius
        sensor_humidity_big = sensor_sht.humidity
        logger.debug('sensor_temperature_big: ' + str(sensor_temperature_big))
        logger.debug('sensor_humidity_big: ' + str(sensor_humidity_big))

    if sensor_humidity_big is not None and sensor_temperature_big is not None:
        sensor_temperature = round (sensor_temperature_big,2)
        sensor_humidity = round (sensor_humidity_big,2)
    else:
        logstring = _('Failed to get reading. Try again!')
        logger.warning(logstring)
        
    sensordata={}
    sensordata['sensor_temperature'] = sensor_temperature
    sensordata['sensor_humidity'] = sensor_humidity
    
    return sensordata
    
def set_gpio_value(gpio_number, value):
    gpio.output(gpio_number, value)
    
def get_gpio_value(gpio_number):
    value = gpio.input(gpio_number)
    return value
    
def switch_light(relay_state):
    set_gpio_value(pi_ager_names.gpio_light, relay_state)

def check_and_set_light():
    #   Manueller "Lichtschalter"
    if pi_ager_database.get_status_light_manual() == 1:
        duration_light_on = pi_ager_database.get_current_time() - pi_ager_database.get_last_change(pi_ager_names.current_values_table, pi_ager_names.status_light_manual_key)
        if duration_light_on <= 600:    #   manuelles Licht ist keine 10 Minuten an
            if get_gpio_value(pi_ager_names.gpio_light) == pi_ager_names.relay_off:
                switch_light(pi_ager_names.relay_on)
        else:
            pi_ager_database.write_stop_in_database(pi_ager_names.status_light_manual_key)
    elif get_gpio_value(pi_ager_names.gpio_light) == pi_ager_names.relay_on:
        switch_light(pi_ager_names.relay_off)
        


def doMainLoop():
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
    global loopcounter                    #  Zaehlt die Durchlaeufe des Mainloops
    global status_humidifier              #  Luftbefeuchtung
    global counter_humidify               #  Zaehler Verzoegerung der Luftbefeuchtung
    counter_humidify = 0
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
    global logger

    # Pruefen Sensor, dann Settings einlesen

    pi_ager_database.write_start_in_database(pi_ager_names.status_pi_ager_key)
    status_pi_ager = 1
    
    logger.debug('doMainLoop()')

    while status_pi_ager == 1:
        check_and_set_light()
        status_pi_ager = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key)

#Settings
        #Sensor
        sensortype = int(pi_ager_init.sensortype)
        sensordata = get_sensordata()
        sensor_temperature = sensordata['sensor_temperature']
        sensor_humidity = sensordata['sensor_humidity']

        # weitere Settings
        modus = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.modus_key)
        setpoint_temperature = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_temperature_key))
        setpoint_humidity = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_humidity_key))
        circulation_air_period = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.circulation_air_period_key))
        logger.debug("circulation_air_period = " + str(circulation_air_period))
        circulation_air_duration = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.circulation_air_duration_key))
        logger.debug("circulation_air_duration = "+ str(circulation_air_duration))
        exhaust_air_period = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.exhaust_air_period_key))
        exhaust_air_duration = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.exhaust_air_duration_key))
        switch_on_cooling_compressor = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_cooling_compressor_key))
        switch_off_cooling_compressor = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_off_cooling_compressor_key))
        switch_on_humidifier = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_humidifier_key))
        switch_off_humidifier = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_off_humidifier_key))
        delay_humidify = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.delay_humidify_key))
        delay_humidify = delay_humidify * 10
        uv_modus = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_modus_key))
        switch_on_uv_hour = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_uv_hour_key))
        switch_on_uv_minute = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_uv_minute_key))
        uv_duration = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_duration_key))
        uv_period = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.uv_period_key))
        light_modus = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.light_modus_key))
        switch_on_light_hour = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_light_hour_key))
        switch_on_light_minute = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_light_minute_key))
        light_duration = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.light_duration_key))
        light_period = float(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.light_period_key))
        dehumidifier_modus = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.dehumidifier_modus_key))

        # An dieser Stelle sind alle settings eingelesen, Ausgabe auf Konsole
        os.system('clear') # Clears the terminal
        current_time = pi_ager_database.get_current_time()
        logger.info(pi_ager_names.logspacer)
        logstring = 'Main loop/Unix-Timestamp: (' + str(current_time)+ ')'
        logger.info(logstring)
        logger.info(pi_ager_names.logspacer2)
        logstring = _('target temperature') + ': ' + str(setpoint_temperature) + ' C'
        logger.info(logstring)
        logstring = _('actual temperature') + ': ' + str(sensor_temperature) + ' C'
        logger.info(logstring)
        logger.info(pi_ager_names.logspacer2)
        logstring = _('target humidity') + ': ' + str(setpoint_humidity) + '%'
        logger.info(logstring)
        logstring = _('actual humidity') + ': ' + str(sensor_humidity) + '%'
        logger.info(logstring)
        logger.info(pi_ager_names.logspacer2)
        logstring = _('selected sensor') + ': ' + str(pi_ager_init.sensorname)
        logger.info(logstring)
        logstring = _('value in database') + ': ' + str(sensortype)
        logger.debug(logstring)
        logger.info(pi_ager_names.logspacer2)

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
                logstring = _('circulation air timer active') + ' (' + _('fan off') +')'
                logger.info(logstring)
            if current_time >= pi_ager_init.circulation_air_start + circulation_air_period:
                status_circulation_air = True                      # Umluft - Ventilator an
                logstring = _('circulation air timer active') + ' (' + _('fan on') +')'
                logger.info(logstring)
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
                logstring = _('exhaust air timer active') + ' (' + _('fan off') +')'
                logger.info(logstring)
            if current_time >= pi_ager_init.exhaust_air_start + exhaust_air_period:
                status_exhaust_air = True                     # (Abluft-)Luftaustausch-Ventilator an
                logstring = _('exhaust air timer active') + ' (' + _('fan on') +')'
                logger.info(logstring)
            if current_time >= pi_ager_init.exhaust_air_start + exhaust_air_period + exhaust_air_duration:
                pi_ager_init.exhaust_air_start = int(time.time())   # Timer-Timestamp aktualisiert
        # Timer fuer UV-Licht
        if uv_modus == 0:                         # Modus 0 UV-Licht aus
            status_uv = False                      # UV-Licht aus
            logstring = _('modus uv-light') + ': ' + _('off')
            logger.info(logstring)
        
        if uv_modus == 1:                            # Modus 1 = Periode/Dauer
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
                    logstring = _('uv-light timer active') + ' (' + _('uv-light on') +')'
                    logger.info(logstring)
                    logger.debug('UV-Licht Startzeit: ' + str(pi_ager_init.uv_starttime))
                    logger.debug('UV-Licht Stoppzeit: ' + str(pi_ager_init.uv_stoptime))
                    logger.debug('UV-Licht duration: ' + str(uv_duration))
                else: 
                    status_uv = False                      # UV-Licht aus
                    logstring = _('uv-light timer active') + ' (' + _('uv-light off') +')'
                    logger.info(logstring)
                    logger.debug('UV-Licht Stoppzeit: ' + str(pi_ager_init.uv_stoptime))
                    logger.debug('UV-Licht Startzeit: ' + str(pi_ager_init.uv_starttime))
                    logger.debug('UV-Licht period: ' + str(uv_period))

                if current_time > pi_ager_init.uv_stoptime:
                    pi_ager_init.uv_starttime = int(time.time()) + uv_period  # Timer-Timestamp aktualisiert
                    pi_ager_init.uv_stoptime = pi_ager_init.uv_starttime + uv_duration

        if uv_modus == 2:                         # Modus 2 Zeitstempel/Dauer
            now = datetime.datetime.now()
            year_now = now.year
            month_now = now.month
            day_now = now.day

            pi_ager_init.uv_starttime = datetime.datetime(year_now, month_now, day_now, switch_on_uv_hour, switch_on_uv_minute, 0, 0)
            pi_ager_init.uv_stoptime = pi_ager_init.uv_starttime + datetime.timedelta(0, uv_duration)
            logger.debug(pi_ager_init.uv_starttime)
            logger.debug(pi_ager_init.uv_stoptime)

            if now >= pi_ager_init.uv_starttime and now <= pi_ager_init.uv_stoptime:
                status_uv = True                     # UV-Licht an
                logstring = _('uv-light timestamp active') + ' (' + _('uv-light on') +')'
                logger.info(logstring)
            else: 
                status_uv = False                      # UV-Licht aus
                logstring = _('uv-light timestamp active') + ' (' + _('uv-light off') +')'
                logger.info(logstring)

        # Timer fuer Licht
        if light_modus == 0:                         # Modus 0 Licht aus
            status_light = False                      # Licht aus
            logstring = _('modus light') + ': ' + _('off')
            logger.info(logstring)
        
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
                    logstring = _('light timer active') + ' (' + _('light on') +')'
                    logger.info(logstring)
                    logger.debug('Licht Startzeit: ' + str(pi_ager_init.light_starttime))
                    logger.debug('Licht Stoppzeit: ' + str(pi_ager_init.light_stoptime))
                    logger.debug('Licht duration: ' + str(light_duration))
                else: 
                    status_light = False                      # Licht aus
                    logstring = _('light timer active') + ' (' + _('light off') +')'
                    logger.info(logstring)
                    logger.debug('Licht Stoppzeit: ' + str(pi_ager_init.light_stoptime))
                    logger.debug('Licht Startzeit: ' + str(pi_ager_init.light_starttime))
                    logger.debug('Licht period: ' + str(light_period))

                if current_time > pi_ager_init.light_stoptime:
                    pi_ager_init.light_starttime = int(time.time()) + light_period  # Timer-Timestamp aktualisiert
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
                    logstring = _('light timestamp active') + ' (' + _('light on') +')'
                    logger.info(logstring)
            else: 
                status_light = False                      # Licht aus
                logstring = _('light timestamp active') + ' (' + _('light off') +')'
                logger.info(logstring)

        # Kuehlen
        if modus == 0:
            status_exhaust_fan = False                              # Feuchtereduzierung Abluft aus
            status_dehumidifier = False        # Entfeuchter aus
            gpio.output(pi_ager_names.gpio_heater, pi_ager_names.relay_off)                     # Heizung aus
            gpio.output(pi_ager_names.gpio_humidifier, pi_ager_names.relay_off)                 # Befeuchtung aus
            if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                gpio.output(pi_ager_names.gpio_cooling_compressor, pi_ager_names.relay_on)      # Kuehlung ein
            if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor :
                gpio.output(pi_ager_names.gpio_cooling_compressor, pi_ager_names.relay_off)     # Kuehlung aus
        
        # Kuehlen mit Befeuchtung
        if modus == 1:
            status_exhaust_fan = False                     # Feuchtereduzierung Abluft aus
            status_dehumidifier = False        # Entfeuchter aus
            gpio.output(pi_ager_names.gpio_heater, pi_ager_names.relay_off)      # Heizung aus
            if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                gpio.output(pi_ager_names.gpio_cooling_compressor, pi_ager_names.relay_on)     # Kuehlung ein
            if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor :
                gpio.output(pi_ager_names.gpio_cooling_compressor, pi_ager_names.relay_off)    # Kuehlung aus
            if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                gpio.output(pi_ager_names.gpio_humidifier, pi_ager_names.relay_on)      # Befeuchtung ein
            if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                gpio.output(pi_ager_names.gpio_humidifier, pi_ager_names.relay_off)     # Befeuchtung aus
        
        # Heizen mit Befeuchtung
        if modus == 2:
            status_exhaust_fan = False                     # Feuchtereduzierung Abluft aus
            status_dehumidifier = False        # Entfeuchter aus
            gpio.output(pi_ager_names.gpio_cooling_compressor, pi_ager_names.relay_off)        # Kuehlung aus
            if sensor_temperature <= setpoint_temperature - switch_on_cooling_compressor:
                gpio.output(pi_ager_names.gpio_heater, pi_ager_names.relay_on)   # Heizung ein
            if sensor_temperature >= setpoint_temperature - switch_off_cooling_compressor:
                gpio.output(pi_ager_names.gpio_heater, pi_ager_names.relay_off)  # Heizung aus
            if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                gpio.output(pi_ager_names.gpio_humidifier, pi_ager_names.relay_on)      # Befeuchtung ein
            if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                gpio.output(pi_ager_names.gpio_humidifier, pi_ager_names.relay_off)     # Befeuchtung aus
        
        # Automatiktemperatur mit Befeuchtung
        if modus == 3:
            status_exhaust_fan = False                     # Feuchtereduzierung Abluft aus
            status_dehumidifier = False        # Entfeuchter aus
            if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                gpio.output(pi_ager_names.gpio_cooling_compressor, pi_ager_names.relay_on)     # Kuehlung ein
            if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor:
                gpio.output(pi_ager_names.gpio_cooling_compressor, pi_ager_names.relay_off)    # Kuehlung aus
            if sensor_temperature <= setpoint_temperature - switch_on_cooling_compressor:
                gpio.output(pi_ager_names.gpio_heater, pi_ager_names.relay_on)   # Heizung ein
            if sensor_temperature >= setpoint_temperature - switch_off_cooling_compressor:
                gpio.output(pi_ager_names.gpio_heater, pi_ager_names.relay_off)  # Heizung aus
            if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                gpio.output(pi_ager_names.gpio_humidifier, pi_ager_names.relay_on)      # Befeuchtung ein
            if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                gpio.output(pi_ager_names.gpio_humidifier, pi_ager_names.relay_off)     # Befeuchtung aus
            logger.info(_('dehumidifier_modus') + ': ' + str(dehumidifier_modus))
            logger.info(_('sensor_humidity') + ': ' + str(sensor_humidity))
            logger.info(_('setpoint_humidity') + ': ' + str(setpoint_humidity))
            logger.info(_('switch_on_humidifier') + ': ' + str(switch_on_humidifier))
            logger.info(_('status_dehumidifier') + ': ' + str(status_dehumidifier))

        # Automatik mit Befeuchtung und Entfeuchtung durch (Abluft-)Luftaustausch
        if modus == 4:
            if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                gpio.output(pi_ager_names.gpio_cooling_compressor, pi_ager_names.relay_on)     # Kuehlung ein
            if sensor_temperature <= setpoint_temperature - switch_on_cooling_compressor:
                gpio.output(pi_ager_names.gpio_heater, pi_ager_names.relay_on)   # Heizung ein
            if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor:
                gpio.output(pi_ager_names.gpio_cooling_compressor, pi_ager_names.relay_off)    # Kuehlung aus
            if sensor_temperature >= setpoint_temperature - switch_off_cooling_compressor:
                gpio.output(pi_ager_names.gpio_heater, pi_ager_names.relay_off)  # Heizung aus
            if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                counter_humidify = counter_humidify + 1
                if counter_humidify >= delay_humidify:               # Verzoegerung der Luftbefeuchtung
                    gpio.output(pi_ager_names.gpio_humidifier, pi_ager_names.relay_on)  # Luftbefeuchter ein
            if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                gpio.output(pi_ager_names.gpio_humidifier, pi_ager_names.relay_off)     # Luftbefeuchter aus
                counter_humidify = 0
            if sensor_humidity >= setpoint_humidity + switch_on_humidifier:
                if dehumidifier_modus == 1 or dehumidifier_modus == 2:  # entweder nur über Abluft oder mit unterstützung von Entfeuchter
                    status_exhaust_fan = True                           # Feuchtereduzierung Abluft-Ventilator ein
                    if dehumidifier_modus == 2:                         # Entfeuchter zur Unterstützung
                        status_dehumidifier = True                      # Entfeuchter unterstützend ein
                    else:
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
        if gpio.input(pi_ager_names.gpio_heater) or gpio.input(pi_ager_names.gpio_cooling_compressor) or gpio.input(pi_ager_names.gpio_humidifier) or status_circulation_air == True:
            gpio.output(pi_ager_names.gpio_circulating_air, pi_ager_names.relay_on)               # Umluft - Ventilator an
        if gpio.input(pi_ager_names.gpio_heater) and gpio.input(pi_ager_names.gpio_cooling_compressor) and gpio.input(pi_ager_names.gpio_humidifier) and status_circulation_air == False:
            gpio.output(pi_ager_names.gpio_circulating_air, pi_ager_names.relay_off)             # Umluft - Ventilator aus
        
        # Schalten des Entfeuchters
        if status_dehumidifier == True:
            gpio.output(pi_ager_names.gpio_dehumidifier, pi_ager_names.relay_on)
        if status_dehumidifier == False:
            gpio.output(pi_ager_names.gpio_dehumidifier, pi_ager_names.relay_off)
        
        # Schalten des (Abluft-)Luftaustausch-Ventilator
        if status_exhaust_air == True or status_exhaust_fan == True:
            gpio.output(pi_ager_names.gpio_exhausting_air, pi_ager_names.relay_on)
        if status_exhaust_air == False and status_exhaust_fan == False:
            gpio.output(pi_ager_names.gpio_exhausting_air, pi_ager_names.relay_off)
        
        # Schalten des UV_Licht
        if status_uv == True:
            gpio.output(pi_ager_names.gpio_uv, pi_ager_names.relay_on)
        if status_uv == False:
            gpio.output(pi_ager_names.gpio_uv, pi_ager_names.relay_off)
        
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
        logger.info(pi_ager_names.logspacer2)
        if gpio.input(pi_ager_names.gpio_heater) == False:
            logstring = _('heater') + ' ' + _('on')
            logger.info(logstring)
            status_heater = 1
        else:
            logstring = _('heater') + ' ' + _('off')
            logger.info(logstring)
            status_heater = 0
        if gpio.input(pi_ager_names.gpio_cooling_compressor) == False:
            logstring = _('cooling compressor') + ' ' + _('on')
            logger.info(logstring)
            status_cooling_compressor = 1
        else:
            logstring = _('cooling compressor') + ' ' + _('off')
            logger.info(logstring)
            status_cooling_compressor = 0
        if gpio.input(pi_ager_names.gpio_humidifier) == False:
            logstring = _('humidifier') + ' ' + _('on')
            logger.info(logstring)
            status_humidifier = 1
        else:
            logstring = _('humidifier') + ' ' + _('off')
            logger.info(logstring)
            status_humidifier = 0
        if gpio.input(pi_ager_names.gpio_circulating_air) == False:
            logstring = _('circulation air') + ' ' + _('on')
            logger.info(logstring)
            status_circulating_air = 1
        else:
            logstring = _('circulation air') + ' ' + _('off')
            logger.info(logstring)
            status_circulating_air = 0
        if gpio.input(pi_ager_names.gpio_exhausting_air) == False:
            logstring = _('exhaust air') + ' ' + _('on')
            logger.info(logstring)
            status_exhaust_air = 1
        else:
            logstring = _('exhaust air') + ' ' + _('off')
            logger.info(logstring)
            status_exhaust_air = 0
        if gpio.input(pi_ager_names.gpio_dehumidifier) == False:
            logstring = _('dehumidifier') + ' ' + _('on')
            logger.info(logstring)
            status_dehumidifier = 1
        else:
            logstring = _('dehumidifier') + ' ' + _('off')
            logger.info(logstring)
            status_dehumidifier = 0
        if get_gpio_value(pi_ager_names.gpio_light) == False:
            logstring = _('light') + ' ' + _('on')
            logger.info(logstring)
            status_light = 1
        else:
            logstring = _('light') + ' ' + _('off')
            logger.info(logstring)
            status_light = 0
        if gpio.input(pi_ager_names.gpio_uv) == False:
            logstring = _('uv-light') + ' ' + _('on')
            logger.info(logstring)
            status_uv= 1
        else:
            logstring = _('uv-light') + ' ' + _('off')
            logger.info(logstring)
            status_uv = 0
        if scale1_data > 0:
            logstring = _('weight scale') + ' 1: ' + str(scale1_data)
            logger.info(logstring)
        if scale2_data > 0:
            logstring = _('weight scale') + ' 2: ' + str(scale2_data)
            logger.info(logstring)

        logger.info(pi_ager_names.logspacer2)
        
        # Messwerte in die RRD-Datei schreiben
        # Schreiben der aktuellen Status-Werte
        pi_ager_database.write_current(pi_ager_init.loopcounter, sensor_temperature, status_heater, status_exhaust_air, status_cooling_compressor, status_circulating_air, sensor_humidity, status_uv, status_light, status_humidifier, status_dehumidifier)
        logger.debug('writing current values in database performed')

        logger.debug('loopnumber: ' + str(pi_ager_init.loopcounter))

        time.sleep(1)  
        
        # Mainloop fertig
        logger.debug('loop complete')
        pi_ager_init.loopcounter += 1
        
# Ende While-Schleife
    logger.debug('status!= 1')
    pi_ager_gpio_config.defaultGPIO()