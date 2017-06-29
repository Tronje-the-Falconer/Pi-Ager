#!/usr/bin/python3
# -*- coding: iso-8859-1 -*-
######################################################### Importieren der Module
import os
import json
import glob
import time
import datetime
import Adafruit_DHT
import time
import RPi.GPIO as gpio
import rrdtool
import math
import gettext
from pi_sht1x import SHT1x
import json_interfaces
import sh

######################################################### Definieren von Funktionen
#---------------------------------------------------------------------------------- Function goodbye
def goodbye():
    cleanup()
    logstring = _('goodbye') + '!'
    write_verbose(logstring, False, False)

#---------------------------------------------------------------------------------- Function cleanup
def cleanup():
    logstring = _('running cleanup script') + '...'
    write_verbose(logstring, False, False)
    gpio.cleanup() # GPIO zuruecksetzen
    logstring = _('cleanup complete') + '.'
    write_verbose(logstring, True, False)

#---------------------------------------------------------------------------------- Function Setup GPIO
def setupGPIO():
    global board_mode
    global gpio_heater
    global gpio_cooling_compressor
    global gpio_circulating_air
    global gpio_humidifier
    global gpio_exhausting_air
    global gpio_light
    global gpio_uv
    global gpio_scale_data
    global gpio_scale_sync
    global gpio_sensor_data
    global gpio_sensor_sync
    global gpio_dehumidifier
    
    logstring = _('setting up GPIO') + '...'
    write_verbose(logstring, False, False)
    gpio.setwarnings(False)
#---------------------------------------------------------------------------------------------------------------- Board mode wird gesetzt
    gpio.setmode(board_mode)
#---------------------------------------------------------------------------------------------------------------- Einstellen der GPIO PINS
#------------------------------------------------------------------------------------------------------------------------------------------ Sensoren etc
    gpio.setup(gpio_scale_data, gpio.IN)           # Kabel Data ()
    gpio.setup(gpio_scale_sync, gpio.OUT)           # Kabel Sync ()
#------------------------------------------------------------------------------------------------------------------------------------------ Relaisboard
    gpio.setup(gpio_heater, gpio.OUT)                # Heizung setzen (config.json)
    #gpio.output(gpio_heater, relay_off)              # Heizung Relais standartmaessig aus
    gpio.setup(gpio_cooling_compressor, gpio.OUT)    # Kuehlung setzen (config.json)
    #gpio.output(gpio_cooling_compressor, relay_off)  # Kuehlung Relais standartmaessig aus
    gpio.setup(gpio_circulating_air, gpio.OUT)       # Umluft setzen (config.json)
    #gpio.output(gpio_circulating_air, relay_off)     # Umluft Relais standartmaessig aus
    gpio.setup(gpio_humidifier, gpio.OUT)            # Befeuchter setzen (config.json)
    #gpio.output(gpio_humidifier, relay_off)          # Befeuchter Relais standartmaessig aus
    gpio.setup(gpio_exhausting_air, gpio.OUT)        # Abluft setzen (config.json)
    #gpio.output(gpio_exhausting_air, relay_off)      # Abluft Relais standartmaessig aus
    gpio.setup(gpio_light, gpio.OUT)                  # Licht setzen (json.conf)
    #gpio.output(gpio_light, relay_off)               # Licht Relais standartmaessig aus
    gpio.setup(gpio_uv, gpio.OUT)               # UV-Licht setzen (json.conf)
    #gpio.output(gpio_uv, relay_off)            # UV-Licht Relais standartmaessig aus
    gpio.setup(gpio_dehumidifier, gpio.OUT)              # Reserve setzen (json.conf)
    #gpio.output(gpio_dehumidifier, relay_off)           # Reserve Relais standartmaessig aus
    logstring = _('GPIO setup complete') + '.'
    write_verbose(logstring, False, False)
    
def defaultGPIO():
    gpio.output(gpio_heater, relay_off)              # Heizung Relais standartmaessig aus
    gpio.output(gpio_cooling_compressor, relay_off)  # Kuehlung Relais standartmaessig aus
    gpio.output(gpio_circulating_air, relay_off)     # Umluft Relais standartmaessig aus
    gpio.output(gpio_humidifier, relay_off)          # Befeuchter Relais standartmaessig aus
    gpio.output(gpio_exhausting_air, relay_off)      # Abluft Relais standartmaessig aus
    gpio.output(gpio_light, relay_off)               # Licht Relais standartmaessig aus
    gpio.output(gpio_uv, relay_off)            # UV-Licht Relais standartmaessig aus
    gpio.output(gpio_dehumidifier, relay_off)          # Reserve Relais standartmaessig aus
    logstring = _('default GPIO setup complete') + '.'
    write_verbose(logstring, True, False)
#---------------------------------------------------------------------------------- Function write verbose
def write_verbose(logstring, newLine=False, print_in_logfile=False):
    global verbose
    
    if(verbose):
        print(logstring)
        if(newLine is True):
            print('')
    if (print_in_logfile is True):
        logfile_txt = open(logfile_txt_file, 'a')           # Variable target = logfile.txt oeffnen
        logfile_txt.write(logstring)
        logfile_txt.close
#---------------------------------------------------------------------------------- Function Schreiben der current.json
def write_current_json(sensor_temperature, sensor_humidity):
    global current_json_file
    current_data = json.dumps({"sensor_temperature":sensor_temperature, "status_heater":gpio.input(gpio_heater), "status_exhaust_air":gpio.input(gpio_exhausting_air), "status_cooling_compressor":gpio.input(gpio_cooling_compressor), "status_circulating_air":gpio.input(gpio_circulating_air),"sensor_humidity":sensor_humidity, 'last_change':int(time.time())})
    with open(current_json_file, 'w') as currentjsonfile:
        currentjsonfile.write(current_data)
#---------------------------------------------------------------------------------- Function Lesen der settings.json
def read_settings_json():
    global settings_json_file
    settings_data = None
    with open(settings_json_file, 'r') as settingsjsonfile:
        settings_data = settingsjsonfile.read()
    data_settingsjsonfile = json.loads(settings_data)
    return data_settingsjsonfile
#---------------------------------------------------------------------------------- Function Lesen der config.json
def read_config_json():
    global config_json_file
    config_data = None
    with open(config_json_file, 'r') as configjsonfile:
        config_data = configjsonfile.read()
    data_configjsonfile = json.loads(config_data)
    return data_configjsonfile
#---------------------------------------------------------------------------------- Function zum Plotten der Grafiken
def ploting(plotting_value):
#---------------------------------------------------------------------------------------------------------------- Beschriftung fuer die Grafiken festlegen
    global rrd_dbname
    if debugging == 'on':
        print("DEBUG: in plotingfunction")
    if plotting_value == 'sensor_temperature':
        title = _('temperature')
        label = 'in C'
    elif plotting_value == 'sensor_humidity':
        title = _('humidity')
        label = 'in %'
    elif plotting_value == "stat_exhaust_air":
        title = _('exhaust air')
        label = _('on or off')
    elif plotting_value == "stat_circulate_air":
        title = _('circulatioon air')
        label = _('on or off')
    elif plotting_value == "stat_heater":
        title = _('heater')
        label = _('on or off')
    elif plotting_value == "stat_coolcompressor":
        title = _('cooling compressor')
        label = _('on or off')
    elif plotting_value == "status_humidifier":
        title = _('humidifier')
        label = _('on or off')
    elif plotting_value == "status_dehumidifier":
        title = _('dehumidifier')
        label = _('on or off')
    elif plotting_value == "status_light":
        title = _('light')
        label = _('on or off')
    elif plotting_value == "status_uv":
        title = _('uv-light')
        label = _('on or off')
#---------------------------------------------------------------------------------------------------------------- Aufteilung in drei Plots
    for plot in ['daily' , 'weekly', 'monthly', 'hourly']:
        if debugging == 'on':
            print ("DEBUG: in for schleife daily, weekly, monthly, hourly")
        if plot == 'weekly':
            period = 'w'
        elif plot == 'daily':
            period = 'd'
        elif plot == 'monthly':
            period = 'm'
        elif plot == 'hourly':
            period = 'h'
#---------------------------------------------------------------------------------------------------------------- Grafiken erzeugen
        ret = rrdtool.graph("%s%s_%s-%s.png" %(graphs_website_path,rrd_dbname,plotting_value,plot),
            "--start",
            "-1%s" % (period),
            "--title=%s (%s)" % (title, plot),
            "--vertical-label=%s" % (label),
            '--watermark=Grillsportverein',
            "-w 400",
            "--alt-autoscale",
            "--slope-mode",
            "DEF:%s=%s:%s:AVERAGE" % (plotting_value, rrd_filename, plotting_value),
            "DEF:%s=%s:sensor_temperature:AVERAGE" % (_('durch'), rrd_filename),
            "DEF:%s=%s:sensor_humidity:AVERAGE" % (_('durchhum'), rrd_filename),
            "GPRINT:%s:AVERAGE:%s\: %%3.2lf C" % (_('durch'), _('Temperatur')),
            "GPRINT:%s:AVERAGE:%s\: %%3.2lf" % (_('durchhum'), _('Luftfeuchtigkeit')), 
            "LINE1:%s#0000FF:%s_%s" % (plotting_value, rrd_dbname, plotting_value))

#---------------------------------------------------------------------------------- Function zum Setzen des Sensors
def set_sensortype():
    global sensor
    global sensorname
    global sensorvalue
    data_configjsonfile = read_config_json()
    sensortype = data_configjsonfile ['sensortype']
    if sensortype == 1: #DHT
        sensor = Adafruit_DHT.DHT11
        sensorname = 'DHT11'
        sensorvalue = 1
    elif sensortype == 2: #DHT22
        sensor = Adafruit_DHT.DHT22
        sensorname = 'DHT22'
        sensorvalue = 2
    elif sensortype == 3: #SHT
        #sensor = Adafruit_DHT.AM2302
        sensor = 'SHT'
        sensorname = 'SHT'
        sensorvalue = 3
#---------------------------------------------------------------------------------- Function Mainloop
def doMainLoop():
    #global value
    global circulation_air_duration       #  Umluftdauer
    global circulation_air_period         #  Umluftperiode
    global circulation_air_start          #  Unix-Zeitstempel fuer den Zaehlstart des Timers Umluft
    global exhaust_air_duration           #  (Abluft-)luftaustauschdauer
    global exhaust_air_period             #  (Abluft-)luftaustauschperiode
    global exhaust_air_start              #  Unix-Zeitstempel fuer den Zaehlstart des Timers (Abluft-)Luftaustausch
    global sensor_temperature             #  Gemessene Temperatur am Sensor
    global sensor_humidity                #  Gemessene Feuchtigkeit am Sensor
    global switch_on_cooling_compressor   #  Einschalttemperatur
    global switch_off_cooling_compressor  #  Ausschalttemperatur
    global switch_on_humidifier           #  Einschaltfeuchte
    global switch_off_humidifier          #  Ausschaltfeuchte
    #global temperature
    global settings
    global status_circulating_air         #  Umluft
    global status_exhaust_air             #  (Abluft-)Luftaustausch
    global status_heater                  #  Heizung
    global status_cooling_compressor      #  Kuehlung
    global loopcounter                    #  Zaehlt die Durchlaeufe des Mainloops
    global status_humidifier              #  Luftbefeuchtung
    global counter_humidify               #  Zaehler Verzoegerung der Luftbefeuchtung
    global delay_humidify                 #  Luftbefeuchtungsverzoegerung
    global status_exhaust_fan             #  Variable fuer die "Evakuierung" zur Feuchtereduzierung durch (Abluft-)Luftaustausch
    global uv_modus                       #  Modus UV-Licht  (1 = Periode/Dauer; 2= Zeitstempel/Dauer)
    global status_uv                      #  UV-Licht
    global switch_on_uv_hour              #  Stunde wann das UV Licht angeschaltet werden soll
    global switch_on_uv_minute            #  Minute wann das UV Licht ausgeschaltet werden soll
    global uv_duration                    #  Dauer der UV_Belichtung
    global uv_period                      #  Periode für UV_Licht
    global uv_starttime                   #  Unix-Zeitstempel fuer den Zaehlstart des Timers UV-Light
    global uv_stoptime                    #  Unix-Zeitstempel fuer den Stop des UV-Light
    global light_modus                    #  ModusLicht  (1 = Periode/Dauer; 2= Zeitstempel/Dauer)
    global status_light                   #  Licht
    global switch_on_light_hour           #  Stunde wann Licht angeschaltet werden soll
    global switch_on_light_minute         #  Minute wann das Licht ausgeschaltet werden soll
    global light_duration                 #  Dauer für Licht
    global light_period                   #  Periode für Licht
    global light_starttime                #  Unix-Zeitstempel fuer den Zaehlstart des Timers licht
    global light_stoptime                 #  Unix-Zeitstempel fuer den Stop des UV-Light
    global dehumidifier_modus             #  Modus Entfeuchter  (1 = über Abluft, 2 = mit Abluft zusammen [unterstützend]; 3 = anstelle von Abluft)
    global status_dehumidifier            #  Entfeuchter
#---------------------------------------------------------------------------------------------------------------- Pruefen Sensor, dann Settings einlesen
    while True:
        if debugging == 'on':
            print ("DEBUG: in While True")
            print ('DEBUG: ' + str(sensorname))
        if sensorname == 'DHT11': #DHT11
            if debugging == 'on':
                print ('DEBUG Sensorname:' + sensorname)
            sensor_humidity_big, sensor_temperature_big = Adafruit_DHT.read_retry(sensor, gpio_sensor_data)
            if debugging == 'on':
                print ("DEBUG: sensor_temperature: " + str(sensor_temperature_big))
                print ("DEBUG: sensor_humidity_big: " + str(sensor_humidity_big))
            atp = 17.271 # ermittelt aus dem Datenblatt DHT11 und DHT22
            btp = 237.7  # ermittelt aus dem Datenblatt DHT11 und DHT22
        elif sensorname == 'DHT22': #DHT22
            if debugging == 'on':
                print ('DEBUG Sensorname:' + sensorname)
            sensor_humidity_big, sensor_temperature_big = Adafruit_DHT.read_retry(sensor, gpio_sensor_data)
            if debugging == 'on':
                print ("DEBUG: sensor_temperature: " + str(sensor_temperature_big))
                print ("DEBUG: sensor_humidity_big: " + str(sensor_humidity_big))
            atp = 17.271 # ermittelt aus dem Datenblatt DHT11 und DHT22
            btp = 237.7  # ermittelt aus dem Datenblatt DHT11 und DHT22
        elif sensorname == 'SHT': #SHT
            if debugging == 'on':
                print ('DEBUG Sensorname:' + sensorname)
            sensor_sht = SHT1x(gpio_sensor_data, gpio_sensor_sync, gpio_mode=gpio.BCM)
            sensor_sht.read_temperature()
            sensor_sht.read_humidity()
            sensor_temperature_big = sensor_sht.temperature_celsius
            sensor_humidity_big = sensor_sht.humidity
            if debugging == 'on':
                print ('DEBUG sensor_temperature_big: ' + str(sensor_temperature_big) + ' sensor_humidity_big: ' + str(sensor_humidity_big))
        if sensor_humidity_big is not None and sensor_temperature_big is not None:
            if debugging == 'on':
                print ("DEBUG: in if sensor_humidity und sensor_temperature_big not None")
            sensor_temperature = round (sensor_temperature_big,2)
            sensor_humidity = round (sensor_humidity_big,2)
            if debugging == 'on':
                print ('DEBUG sensor_temperature: ' + str(sensor_temperature) + ' sensor_humidity_big: ' + str(sensor_humidity))
        else:
            if debugging == 'on':
                print ("DEBUG: in else Sensordaten leer")
            logstring = _('Failed to get reading. Try again!')
            write_verbose (logstring, False, False)
        try:
            if debugging == 'on':
                print ("DEBUG: in try read settings und config")
            data_settingsjsonfile = read_settings_json()
            data_configjsonfile = read_config_json()
        except:
            logstring = _('unable to read settings file, checking if in the blind.')
            write_verbose(logstring, False, False)
            continue
        modus = data_settingsjsonfile['modus']
        setpoint_temperature = data_settingsjsonfile['setpoint_temperature']
        setpoint_humidity = data_settingsjsonfile['setpoint_humidity']
        circulation_air_period = data_settingsjsonfile['circulation_air_period']
        circulation_air_duration = data_settingsjsonfile['circulation_air_duration']
        exhaust_air_period = data_settingsjsonfile['exhaust_air_period']
        exhaust_air_duration = data_settingsjsonfile['exhaust_air_duration']
        switch_on_cooling_compressor = data_configjsonfile['switch_on_cooling_compressor']
        switch_off_cooling_compressor = data_configjsonfile['switch_off_cooling_compressor']
        switch_on_humidifier = data_configjsonfile['switch_on_humidifier']
        switch_off_humidifier = data_configjsonfile['switch_off_humidifier']
        delay_humidify = data_configjsonfile ['delay_humidify']
        delay_humidify = delay_humidify * 10
        sensortype = data_configjsonfile ['sensortype']
        uv_modus = data_configjsonfile ['uv_modus']
        switch_on_uv_hour = data_configjsonfile ['switch_on_uv_hour']
        switch_on_uv_minute = data_configjsonfile ['switch_on_uv_minute']
        uv_duration = data_configjsonfile ['uv_duration']
        uv_period = data_configjsonfile ['uv_period']
        light_modus = data_configjsonfile ['light_modus']
        switch_on_light_hour = data_configjsonfile ['switch_on_light_hour']
        switch_on_light_minute = data_configjsonfile ['switch_on_light_minute']
        light_duration = data_configjsonfile ['light_duration']
        light_period = data_configjsonfile ['light_period']
        dehumidifier_modus = data_configjsonfile ['dehumidifier_modus']
        
        
        # An dieser Stelle sind alle settings eingelesen, Ausgabe auf Konsole
        # lastSettingsUpdate = settings['last_change']
        # lastConfigUpdate = config['last_change']
        os.system('clear') # Clears the terminal
        current_time = int(time.time())
        logstring = ' '
        write_verbose(logstring, False, False)
        write_verbose(logspacer, False, False)
        write_verbose(logstring, False, False)
        logstring = _('Main loop/Unix-Timestamp: (') + str(current_time)+ ')'
        write_verbose(logstring, False, False)
        write_verbose(logspacer2, False, False)
        logstring = _('target temperature') + ': ' + str(setpoint_temperature) + ' C'
        write_verbose(logstring, False, False)
        logstring = _('actual temperature') + ': ' + str(sensor_temperature) + ' C'
        write_verbose(logstring, False, False)
        write_verbose(logspacer2, False, False)
        logstring = _('target humidity') + ': ' + str(setpoint_humidity) + '%'
        write_verbose(logstring, False, False)
        logstring = _('actual humidity') + ': ' + str(sensor_humidity) + '%'
        write_verbose(logstring, False, False)
        write_verbose(logspacer2, False, False)
        logstring = _('selected sensor') + ': ' + str(sensorname)
        write_verbose(logstring, False, False)
        logstring = _('value in config.json') + ': ' + str(sensortype)
        write_verbose(logstring, False, False)
        write_verbose(logspacer2, False, False)
        
        gpio.setmode(board_mode)
        if debugging == 'on':
            print ("DEBUG: writing current.json")
        #setupGPIO()
        write_current_json(sensor_temperature, sensor_humidity)
        # Durch den folgenden Timer laeuft der Ventilator in den vorgegebenen Intervallen zusaetzlich zur generellen Umluft bei aktivem Heizen, Kuehlen oder Befeuchten
#---------------------------------------------------------------------------------------------------------------- Timer fuer Luftumwaelzung-Ventilator
        if circulation_air_period == 0:                          # gleich 0 ist an,  Dauer-Timer
            status_circulation_air = False
        if circulation_air_duration == 0:                        # gleich 0 ist aus, kein Timer
            status_circulation_air = True
        if circulation_air_duration > 0:
            if current_time < circulation_air_start + circulation_air_period:
                status_circulation_air = True                       # Umluft - Ventilator aus
                logstring = _('circulation air timer on (deactive)')
                write_verbose(logstring, False, False)
            if current_time >= circulation_air_start + circulation_air_period:
                status_circulation_air = False                      # Umluft - Ventilator an
                logstring = _('circulation air timer on (active)')
                write_verbose(logstring, False, False)
            if current_time >= circulation_air_start + circulation_air_period + circulation_air_duration:
                circulation_air_start = int(time.time())    # Timer-Timestamp aktualisiert
#---------------------------------------------------------------------------------------------------------------- Timer fuer (Abluft-)Luftaustausch-Ventilator
        if exhaust_air_period == 0:                      # gleich 0 ist an,  Dauer-Timer
            status_exhaust_air = False
        if exhaust_air_duration == 0:                        # gleich 0 ist aus, kein Timer
            status_exhaust_air = True
        if exhaust_air_duration > 0:                        # gleich 0 ist aus, kein Timer
            if current_time < exhaust_air_start + exhaust_air_period:
                status_exhaust_air = True                      # (Abluft-)Luftaustausch-Ventilator aus
                logstring = _('exhaust air timer on (deactive)')
                write_verbose(logstring, False, False)
            if current_time >= exhaust_air_start + exhaust_air_period:
                status_exhaust_air = False                     # (Abluft-)Luftaustausch-Ventilator an
                logstring = _('exhaust air timer on (active)')
                write_verbose(logstring, False, False)
            if current_time >= exhaust_air_start + exhaust_air_period + exhaust_air_duration:
                exhaust_air_start = int(time.time())   # Timer-Timestamp aktualisiert
#---------------------------------------------------------------------------------------------------------------- Timer fuer UV-Licht
        if uv_modus == 1:                            # Modus 1 = Periode/Dauer
            if uv_period == 0:                      # gleich 0 ist an,  Dauer-Timer
                status_uv = False
            if uv_duration == 0:                        # gleich 0 ist aus, kein Timer
                status_uv = True
            if uv_duration > 0:                        # gleich 0 ist aus, kein Timer                
                if current_time >= uv_starttime and current_time <= uv_stoptime:
                    status_uv = False                     # UV-Licht an
                    logstring = _('uv-light timer on (active)')
                    write_verbose(logstring, False, False)
                else: 
                    status_uv = True                      # UV-Licht aus
                    logstring = _('uv-light timer on (deactive)')
                    write_verbose(logstring, False, False)
                    
                if current_time > uv_stoptime:
                    uv_starttime = int(time.time()) + uv_period  # Timer-Timestamp aktualisiert
                    uv_stoptime = uv_starttime + uv_duration

                    
        if uv_modus == 2:                         # Modus 2 Zeitstempel/Dauer
            now = datetime.datetime.now()
            year_now = now.year
            month_now = now.month
            day_now = now.day
            
            
            uv_starttime = datetime.datetime(year_now, month_now, day_now, switch_on_uv_hour, switch_on_uv_minute, 0, 0)
            uv_stoptime = uv_starttime + datetime.timedelta(0, uv_duration)
            
            print (uv_starttime)
            print (uv_stoptime)
            
            if now >= uv_starttime and now <= uv_stoptime:
                    status_uv = False                     # UV-Licht an
                    logstring = _('uv-light timestamp on (active)')
                    write_verbose(logstring, False, False)
            else: 
                status_uv = True                      # UV-Licht aus
                logstring = _('uv-light timestamp on (deactive)')
                write_verbose(logstring, False, False)
            
#---------------------------------------------------------------------------------------------------------------- Timer fuer Licht
        if light_modus == 1:                            # Modus 1 = Periode/Dauer
            if light_period == 0:                      # gleich 0 ist an,  Dauer-Timer
                status_light = False
            if light_duration == 0:                        # gleich 0 ist aus, kein Timer
                status_light = True
            if light_duration > 0:                        # gleich 0 ist aus, kein Timer                
                if current_time >= light_starttime and current_time <= light_stoptime:
                    status_light = False                     # Licht an
                    logstring = _('light timer on (active)')
                    write_verbose(logstring, False, False)
                else: 
                    status_light = True                      # Licht aus
                    logstring = _('light timer on (deactive)')
                    write_verbose(logstring, False, False)
                    
                if current_time > light_stoptime:
                    light_starttime = int(time.time()) + light_period  # Timer-Timestamp aktualisiert
                    light_stoptime = light_starttime + light_duration

                    
        if light_modus == 2:                         # Modus 2 Zeitstempel/Dauer
            now = datetime.datetime.now()
            year_now = now.year
            month_now = now.month
            day_now = now.day
            
            
            light_starttime = datetime.datetime(year_now, month_now, day_now, switch_on_light_hour, switch_on_light_minute, 0, 0)
            light_stoptime = light_starttime + datetime.timedelta(0, light_duration)
            
            if now >= light_starttime and now <= light_stoptime:
                    status_light = False                     # Licht an
                    logstring = _('light timestamp on (active)')
                    write_verbose(logstring, False, False)
            else: 
                status_light = True                      # Licht aus
                logstring = _('light timestamp on (deactive)')
                write_verbose(logstring, False, False)
            
#---------------------------------------------------------------------------------------------------------------- Kuehlen
        if modus == 0:
            status_exhaust_fan = False                              # Feuchtereduzierung Abluft aus
            gpio.output(gpio_heater, relay_off)                     # Heizung aus
            gpio.output(gpio_humidifier, relay_off)                 # Befeuchtung aus
            if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                gpio.output(gpio_cooling_compressor, relay_on)      # Kuehlung ein
            if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor :
                gpio.output(gpio_cooling_compressor, relay_off)     # Kuehlung aus
#---------------------------------------------------------------------------------------------------------------- Kuehlen mit Befeuchtung
        if modus == 1:
            status_exhaust_fan = False                     # Feuchtereduzierung Abluft aus
            gpio.output(gpio_heater, relay_off)      # Heizung aus
            if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                gpio.output(gpio_cooling_compressor, relay_on)     # Kuehlung ein
            if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor :
                gpio.output(gpio_cooling_compressor, relay_off)    # Kuehlung aus
            if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                gpio.output(gpio_humidifier, relay_on)      # Befeuchtung ein
            if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                gpio.output(gpio_humidifier, relay_off)     # Befeuchtung aus
#---------------------------------------------------------------------------------------------------------------- Heizen mit Befeuchtung
        if modus == 2:
            status_exhaust_fan = False                     # Feuchtereduzierung Abluft aus
            gpio.output(gpio_cooling_compressor, relay_off)        # Kuehlung aus
            if sensor_temperature <= setpoint_temperature - switch_on_cooling_compressor:
                gpio.output(gpio_heater, relay_on)   # Heizung ein
            if sensor_temperature >= setpoint_temperature - switch_off_cooling_compressor:
                gpio.output(gpio_heater, relay_off)  # Heizung aus
            if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                gpio.output(gpio_humidifier, relay_on)      # Befeuchtung ein
            if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                gpio.output(gpio_humidifier, relay_off)     # Befeuchtung aus
#---------------------------------------------------------------------------------------------------------------- Automatiktemperatur mit Befeuchtung
        if modus == 3:
            status_exhaust_fan = False                     # Feuchtereduzierung Abluft aus
            if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                gpio.output(gpio_cooling_compressor, relay_on)     # Kuehlung ein
            if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor:
                gpio.output(gpio_cooling_compressor, relay_off)    # Kuehlung aus
            if sensor_temperature <= setpoint_temperature - switch_on_cooling_compressor:
                gpio.output(gpio_heater, relay_on)   # Heizung ein
            if sensor_temperature >= setpoint_temperature - switch_off_cooling_compressor:
                gpio.output(gpio_heater, relay_off)  # Heizung aus
            if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                gpio.output(gpio_humidifier, relay_on)      # Befeuchtung ein
            if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                gpio.output(gpio_humidifier, relay_off)     # Befeuchtung aus
#---------------------------------------------------------------------------------------------------------------- Automatik mit Befeuchtung und Entfeuchtung durch (Abluft-)Luftaustausch
        if modus == 4:
            if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                gpio.output(gpio_cooling_compressor, relay_on)     # Kuehlung ein
            if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor:
                gpio.output(gpio_cooling_compressor, relay_off)    # Kuehlung aus
            if sensor_temperature <= setpoint_temperature - switch_on_cooling_compressor:
                gpio.output(gpio_heater, relay_on)   # Heizung ein
            if sensor_temperature >= setpoint_temperature - switch_off_cooling_compressor:
                gpio.output(gpio_heater, relay_off)  # Heizung aus
            if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                counter_humidify = counter_humidify + 1
                if counter_humidify >= delay_humidify:               # Verzoegerung der Luftbefeuchtung
                    gpio.output(gpio_humidifier, relay_on)  # Luftbefeuchter ein
            if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                gpio.output(gpio_humidifier, relay_off)     # Luftbefeuchter aus
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
#---------------------------------------------------------------------------------------------------------------- Schalten des Umluft - Ventilators
        if gpio.input(gpio_heater) or gpio.input(gpio_cooling_compressor) or gpio.input(gpio_humidifier) or status_circulation_air == False:
            gpio.output(gpio_circulating_air, relay_on)               # Umluft - Ventilator an
        if gpio.input(gpio_heater) and gpio.input(gpio_cooling_compressor) and gpio.input(gpio_humidifier) and status_circulation_air == True:
            gpio.output(gpio_circulating_air, relay_off)             # Umluft - Ventilator aus
#---------------------------------------------------------------------------------------------------------------- Schalten des Entfeuchters
        if status_dehumidifier == True:
            gpio.output(gpio_dehumidifier, relay_on)
        if status_dehumidifier == False:
            gpio.output(gpio_dehumidifier, relay_off)
#---------------------------------------------------------------------------------------------------------------- Schalten des (Abluft-)Luftaustausch-Ventilator
        if status_exhaust_air == False or status_exhaust_fan == True:
            gpio.output(gpio_exhausting_air, relay_on)
        if status_exhaust_fan == False and status_exhaust_air == True:
            gpio.output(gpio_exhausting_air, relay_off)
#---------------------------------------------------------------------------------------------------------------- Schalten des UV_Licht
        if status_uv == False:
            gpio.output(gpio_uv, relay_on)
        if status_uv == True:
            gpio.output(gpio_uv, relay_off)
#---------------------------------------------------------------------------------------------------------------- Schalten des Licht
        if status_light == False:
            gpio.output(gpio_light, relay_on)
        if status_light == True:
            gpio.output(gpio_light, relay_off)
#---------------------------------------------------------------------------------------------------------------- Lesen der Scales Json, sofern Scale 1 oder 2 läuft
        # ps ax | grep -v grep | grep scale1.py
        status_scale1 = sh.grep(sh.grep (sh.ps("ax"), '-v', 'grep'), 'scale1.py')
        #if status_scale1 != 0:
            # try:
                # if debugging == 'on':
                    # print ("DEBUG: in try read settings und config")
                # data_settingsjsonfile = read_settings_json()
                # data_configjsonfile = read_config_json()
            # except:
                # logstring = _('unable to read settings file, checking if in the blind.')
                # write_verbose(logstring, False, False)
                # continue
#---------------------------------------------------------------------------------------------------------------- Ausgabe der Werte auf der Konsole
        write_verbose(logspacer2, False, False)
        if gpio.input(gpio_heater) == False:
            logstring = _('heater on')
            write_verbose(logstring, False, False)
            status_heater = 10
        else:
            logstring = _('heater off')
            write_verbose(logstring, False, False)
            status_heater = 0
        if gpio.input(gpio_cooling_compressor) == False:
            logstring = _('cooling compressor on')
            write_verbose(logstring, False, False)
            status_cooling_compressor = 10
        else:
            logstring = _('cooling compressor off')
            write_verbose(logstring, False, False)
            status_cooling_compressor = 0
        if gpio.input(gpio_humidifier) == False:
            logstring = _('humidifier on')
            write_verbose(logstring, False, False)
            status_humidifier = 10
        else:
            logstring = _('humidifier off')
            write_verbose(logstring, False, False)
            status_humidifier = 0
        if gpio.input(gpio_circulating_air) == False:
            logstring = _('circulation air on')
            write_verbose(logstring, False, False)
            status_circulating_air = 10
        else:
            logstring = _('circulation air off')
            write_verbose(logstring, False, False)
            status_circulating_air = 0
        if gpio.input(gpio_exhausting_air) == False:
            logstring = _('exhaust air on')
            write_verbose(logstring, False, False)
            status_exhaust_air = 10
        else:
            logstring = _('exhaust air off')
            write_verbose(logstring, False, False)
            status_exhaust_air = 0
        if gpio.input(gpio_dehumidifier) == False:
            logstring = _('dehumidifier on')
            write_verbose(logstring, False, False)
            status_dehumidifier = 10
        else:
            logstring = _('dehumidifier off')
            write_verbose(logstring, False, False)
            status_dehumidifier = 0
        if gpio.input(gpio_light) == False:
            logstring = _('light on')
            write_verbose(logstring, False, False)
            status_light = 10
        else:
            logstring = _('light off')
            write_verbose(logstring, False, False)
            status_light = 0
        if gpio.input(gpio_uv) == False:
            logstring = _('uv-light on')
            write_verbose(logstring, False, False)
            status_uv= 10
        else:
            logstring = _('uv-light off')
            write_verbose(logstring, False, False)
            status_uv = 0
        write_verbose(logspacer2, False, False)
#---------------------------------------------------------------------------------------------------------------- Messwerte in die RRD-Datei schreiben
        from rrdtool import update as rrd_update
        ret = rrd_update('%s' %(rrd_filename), 'N:%s:%s:%s:%s:%s:%s:%s:%s:%s:%s' %(sensor_temperature, sensor_humidity, status_exhaust_air, status_circulating_air, status_heater, status_cooling_compressor, status_humidifier, status_dehumidifier, status_light, status_uv))
        #array fuer graph     
        # Grafiken erzeugen
        if loopcounter % 3 == 0:
            logstring = _("creating graphs")
            write_verbose(logstring, False, False)
            if debugging == 'on':
                print ("DEBUG: ploting sensor_temperature")
            ploting('sensor_temperature')#', 'status_heater', 'status_cooling_compressor', 'status_circulating_air')
            if debugging == 'on':
                print ("DEBUG: ploting sensor_humidity")
            ploting('sensor_humidity')#, 'status_humidifier', 'status_circulating_air', 'status_exhaust_air')
            if debugging == 'on':
                print ("DEBUG: ploting status_circulating_air")
            ploting('stat_circulate_air')#, 'status_exhaust_air')
            if debugging == 'on':
                print ("DEBUG: ploting status_exhaust_air")
            ploting('stat_exhaust_air')
            if debugging == 'on':
                print ("DEBUG: ploting status_heater")
            ploting('stat_heater')
            if debugging == 'on':
                print ("DEBUG: ploting status_cooling_compressor")
            ploting('stat_coolcompressor')
            if debugging == 'on':
                print ("DEBUG: ploting status_humidifier")
            ploting('status_humidifier')
            if debugging == 'on':
                print ("DEBUG: ploting status_dehumidifier")
            ploting('status_dehumidifier')
            if debugging == 'on':
                print ("DEBUG: ploting status_light")
            ploting('status_light')
            if debugging == 'on':
                print ("DEBUG: ploting status_uv")
            ploting('status_uv')
        if debugging == 'on':
            print ('DEBUG Loopnumber: ' + str(loopcounter))

        time.sleep(1)  
        # Mainloop fertig
        logstring = _('loop complete.')
        write_verbose(logstring, False, False)
        # time.sleep(3)
        loopcounter += 1
    
######################################################### Definition von Variablen
debugging = 'on'      # Debugmodus 'on'
#---------------------------------------------------------------------------------- Pfade zu den Dateien
website_path = '/var/www'
settings_json_file = website_path + '/config/settings.json'
current_json_file = website_path + '/config/current.json'
graphs_website_path = website_path + '/images/graphs/'
config_json_file = website_path + '/config/config.json'
logfile_txt_file = website_path + '/logs/logfile.txt'
#---------------------------------------------------------------------------------- allgemeine Variablen
# sensor = Adafruit_DHT.AM2302
logspacer = "\n" + "***********************************************"
logspacer2 = "\n" + '-------------------------------------------------------'
delay = 4                      # Wartezeit in der Schleife
counter_humidify = 0           # Zaehler fuer die Verzoegerung der Befeuchtung
status_exhaust_fan = False     # Variable fuer die "Evakuierung" zur Feuchtereduzierung durch (Abluft-)Luftaustausch
verbose = True                # Dokumentiert interne Vorgaenge wortreich
#---------------------------------------------------------------------------------- Allgemeingueltige Werte aus config.json
data_configjsonfile = read_config_json()
sensortype = data_configjsonfile ['sensortype']                                        # Sensortyp
language = data_configjsonfile ['language']                                            # Sprache der Textausgabe
switch_on_cooling_compressor = data_configjsonfile ['switch_on_cooling_compressor']    # Einschalttemperatur
switch_off_cooling_compressor = data_configjsonfile ['switch_off_cooling_compressor']  # Ausschalttemperatur
switch_on_humidifier = data_configjsonfile ['switch_on_humidifier']                    # Einschaltfeuchte
switch_off_humidifier = data_configjsonfile ['switch_off_humidifier']                  # Ausschaltfeuchte
delay_humidify = data_configjsonfile ['delay_humidify']                                # Luftbefeuchtungsverzoegerung
#---------------------------------------------------------------------------------- Sainsmart Relais Vereinfachung 0 aktiv
relay_on = False               # negative Logik!!! des Relay's, Schaltet bei 0 | GPIO.LOW  | False  ein
relay_off = (not relay_on)     # negative Logik!!! des Relay's, Schaltet bei 1 | GPIO.High | True aus
#---------------------------------------------------------------------------------- RRD-Tool
rrd_dbname = 'pi-ager'                   # Name fuer Grafiken etc
rrd_filename = rrd_dbname + '.rrd'   # Dateinamen mit Endung
measurement_time_interval = 10       # Zeitintervall fuer die Messung in Sekunden
# i = 0
loopcounter = 0                      #  Zaehlt die Durchlaeufe des Mainloops
#-----------------------------------------------------------------------------------------Pinbelegung
board_mode = gpio.BCM              # GPIO board mode (BCM = Broadcom SOC channel number - numbers after GPIO Bsp. GPIO12=12 [GPIO.BOARD = Pin by number Bsp: GPIO12=32])
gpio_cooling_compressor = 4        # GPIO fuer Kuehlschrankkompressor
gpio_heater = 3                    # GPIO fuer Heizkabel
gpio_humidifier = 18               # GPIO fuer Luftbefeuchter
gpio_circulating_air = 24          # GPIO fuer Umluftventilator
gpio_exhausting_air = 23           # GPIO fuer Austauschluefter
gpio_uv = 25                       # GPIO fuer UV Licht
gpio_light = 8                     # GPIO fuer Licht
gpio_dehumidifier = 7              # GPIO fuer Entfeuchter
gpio_sensor_data = 17              # GPIO fuer Data Temperatur/Humidity Sensor
gpio_sensor_sync = 27              # GPIO fuer Sync Temperatur/Humidity Sensor
gpio_scale_data = 10               # GPIO fuer Waage Data
gpio_scale_sync = 9                # GPIO fuer Waage Sync
gpio_recerved1 = 2                 # GPIO Reserve 1
gpio_recerved2 = 11                # GPIO Reserve 2
#---------------------------------------------------------------------------------------------------------------- Sprache
####   Set up message catalog access
# translation = gettext.translation('pi_ager', '/var/www/locale', fallback=True)
# _ = translation.ugettext
if language == 1:
    translation = gettext.translation('pi_ager', '/var/www/locale', languages=['en'], fallback=True)
elif language == 2:
    translation = gettext.translation('pi_ager', '/var/www/locale', languages=['de'], fallback=True)
# else:
    
translation.install()

######################################################### Hauptprogramm
########################################################################################################################

os.system('clear') # Bildschirm loeschen
write_verbose(logspacer, False, False)
setupGPIO() # GPIO initialisieren
defaultGPIO() 

#---------------------------------------------------------------------------------- RRD-Datenbank anlegen, wenn nicht vorhanden
try:
    with open(rrd_filename): pass
    logstring = _("database file found") + ": " + rrd_filename
    write_verbose(logstring, False, False)
#    i = 1
except IOError:
    logstring = _("creating a new database") + ": " + rrd_filename
    write_verbose(logstring, False, False)
    ret = rrdtool.create("%s" %(rrd_filename),
        "--step","%s" %(measurement_time_interval),
        "--start",'0',
        "DS:sensor_temperature:GAUGE:2000:U:U",
        "DS:sensor_humidity:GAUGE:2000:U:U",
        "DS:stat_exhaust_air:GAUGE:2000:U:U",
        "DS:stat_circulate_air:GAUGE:2000:U:U",
        "DS:stat_heater:GAUGE:2000:U:U",
        "DS:stat_coolcompressor:GAUGE:2000:U:U",
        "DS:status_humidifier:GAUGE:2000:U:U",
        "DS:status_dehumidifier:GAUGE:2000:U:U",
        "DS:status_light:GAUGE:2000:U:U",
        "DS:status_uv:GAUGE:2000:U:U",
        "RRA:AVERAGE:0.5:1:2160",
        "RRA:AVERAGE:0.5:5:2016",
        "RRA:AVERAGE:0.5:15:2880",
        "RRA:AVERAGE:0.5:60:8760",)

#    i = 1
write_verbose(logspacer, False, False)
settings = read_settings_json()
config = read_config_json()
set_sensortype()
system_starttime = int(time.time())
circulation_air_start = system_starttime
exhaust_air_start = system_starttime
uv_starttime = system_starttime
#uv_duration=0    #Initial-Füllung der Variablen
uv_stoptime = uv_starttime
light_starttime = system_starttime
light_stoptime = light_starttime

try:
    doMainLoop()
except KeyboardInterrupt:
    pass

except Exception as e:
    logstring = _('exception occurred') + '!!!'
    write_verbose(logstring, True, False)
    write_verbose(str(e), True, False)
    pass

goodbye()
