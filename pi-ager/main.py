#!/usr/bin/python
# -*- coding: iso-8859-1 -*-

######################################################### Importieren der Module
import os
import json
import glob
import time
import Adafruit_DHT
import time
import RPi.GPIO as gpio
import rrdtool
from sht_sensor import Sht

######################################################### Definieren von Funktionen
#---------------------------------------------------------------------------------- Function goodbye
def goodbye():
    cleanup() 
    write_verbose('Goodbye!')

#---------------------------------------------------------------------------------- Function cleanup
def cleanup():
    write_verbose('Running cleanup script...')
    gpio.cleanup() # GPIO zurücksetzen
    write_verbose('Cleanup complete.', True)

#---------------------------------------------------------------------------------- Function Setup GPIO
def setupGPIO():
    global board_mode
    global pin_heater
    global pin_cooling_compressor
    global pin_recirculation_fan
    global pin_humidifier
    global pin_exhaust_fan
    
    write_verbose('Setting up GPIO...')
    gpio.setwarnings(False)
    
#---------------------------------------------------------------------------------------------------------------- Board mode wird gesetzt
    gpio.setmode(board_mode)
    
#---------------------------------------------------------------------------------------------------------------- Einstellen der GPIO PINS
    gpio.setup(pin_heater, gpio.OUT)
    gpio.setup(pin_cooling_compressor, gpio.OUT)
    gpio.setup(pin_recirculation_fan, gpio.OUT)
    gpio.setup(pin_humidifier, gpio.OUT)
    gpio.setup(pin_exhaust_fan, gpio.OUT)

    gpio.output(pin_heater, relay_off)
    gpio.output(pin_cooling_compressor, relay_off)
    gpio.output(pin_recirculation_fan, relay_off)
    gpio.output(pin_humidifier, relay_off)
    gpio.output(pin_exhaust_fan, relay_off)

    write_verbose('GPIO setup complete.',True)

#---------------------------------------------------------------------------------- Function write verbose
def write_verbose(s, newLine=False):
    global verbose
    
    if(verbose):
        print(s)
        if(newLine is True):
            print('')

#---------------------------------------------------------------------------------- Function Schreiben der current.json
def write_current_json(sensor_temperature, sensor_humidity):
    global current_json_file

    current_data = json.dumps({"sensor_temperature":sensor_temperature, "status_heater":gpio.input(pin_heater), "status_exhaust_air":gpio.input(pin_exhaust_fan), "status_cooling_compressor":gpio.input(pin_cooling_compressor), "status_circulating_air":gpio.input(pin_recirculation_fan),"sensor_humidity":sensor_humidity, 'date':int(time.time())})
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
    
#---------------------------------------------------------------------------------- Function zum Plotten der Grafiken
#a: Wert, der geplottet werden soll
def ploting(a):#, b, c, d):
#---------------------------------------------------------------------------------------------------------------- Beschriftung für die Grafiken festlegen
    if a == 'sensor_temperature':
        title = 'Temperatur'
        label = 'in C'
    elif a == 'sensor_humidity':
        title = 'Luftfeuchtigkeit'
        label = 'in %'
    elif a == "status_exhaust_air":
        title = 'Luftaustausch'
        label = 'ein oder aus'
    elif a == "status_circulating_air":
        title = 'Luftumwaelzung'
        label = 'ein oder aus'
    elif a == "status_heater":
        title = 'Heizung'
        label = 'ein oder aus'
    elif a == "status_cooling_compressor":
        title = 'Kuehlung'
        label = 'ein oder aus'
    elif a == "status_humidifier":
        title = 'Luftbefeuchter'
        label = 'ein oder aus'

#---------------------------------------------------------------------------------------------------------------- Aufteilung in drei Plots
    for plot in ['daily' , 'weekly', 'monthly', 'hourly']:
        if plot == 'weekly':
            period = 'w'
        elif plot == 'daily':
            period = 'd'
        elif plot == 'monthly':
            period = 'm'
        elif plot == 'hourly':
            period = 'h'

#---------------------------------------------------------------------------------------------------------------- Grafiken erzeugen
        ret = rrdtool.graph("%s%s_%s-%s.png" %(picture_website_path,rrd_dbname,a,plot),
            "--start",
            "-1%s" %(period),
            "--title=%s (%s)" %(title,plot),
            "--vertical-label=%s" %(label),
            '--watermark=Grillsportverein',
            "-w 400",
            "--alt-autoscale",
            "--slope-mode",
            "DEF:%s=%s:%s_%s:AVERAGE" %(a, rrd_filename, rrd_dbname, a),
            "DEF:durch=rss.rrd:rss_sensor_temperature:AVERAGE",
            "DEF:durchhum=rss.rrd:rss_sensor_humidity:AVERAGE",
            "GPRINT:durch:AVERAGE:Temperatur\: %3.2lf C",
            "GPRINT:durchhum:AVERAGE:Luftfeuchtigkeit\: %3.2lf", 
            "LINE1:%s#0000FF:%s_%s" %(a, rrd_dbname, a))

#---------------------------------------------------------------------------------- Function zum Setzen des Sensors
def set_sensortype():
    global sensor
    global sensorname
    global sensorvalue
    settings = read_settings_json()
    sensortype = settings ['sensortype']
    if sensortype == 1: #DHT
        sensor = Adafruit_DHT.DHT11
        sensorname = 'DHT11'
        sensorvalue = 1
    elif sensortype == 2: #SHT
        sensor = Adafruit_DHT.AM2302
        sensorname = 'DHT22'
        sensorvalue = 2
    elif sensortype == 3: #SHT75 sensor=22
        sensor = Adafruit_DHT.AM2302
        sensorname = 'SHT75'
        sensorvalue = 3
    
######################################################### Definition von Variablen
#---------------------------------------------------------------------------------- Pfade zu den Dateien
website_path = '/var/www/'
settings_json_file = website_path + 'settings.json'
current_json_file = website_path + 'current.json'
picture_website_path = website_path + 'pic/'
config_json_file = website_path + '/config.json'
logfile_txt_file = website_path + '/logfile.txt'


# sensor = Adafruit_DHT.AM2302
logspacer = "\n"+ "***********************************************"    
##########################################################################################################################################################################    

# Konstanten ############################################################################################################
########################################################################################################################

#-----------------------------------------------------------------------------------------Sainsmart Relais Vereinfachung 0 aktiv
relay_on = False
relay_off = (not relay_on)

delay = 4               # Wartezeit in der Schleife
counter_humidify = 0               # Zähler für die Verzögerung der Befeuchtung
status_exhaust_fan = False     # Variable für die "Evakuierung" zur Feuchtereduzierung durch (Abluft-)Luftaustausch

#-----------------------------------------------------------------------------------------Pinbelegung
board_mode = gpio.BCM         # GPIO board mode (BCM = Broadcom SOC channel number - numbers after GPIO [GPIO.BOARD = Pin by number])
pin_sensor_dht = 17           # Pin für Data Temperatur/Humidity Sensor DHT
pins_sensor_sht = Sht(21, 20) # Pins für Temperatur/Humidity Sensor SHT (GPIO 21 für Synchronisierung, GPIO 20 für DATA)
pin_heater = 27               # Pin für Heizkabel
pin_cooling_compressor = 22   # Pin für Kühlschrankkompressor

pin_recirculation_fan = 18    # Pin für Umluftventilator
pin_exhaust_fan = 23          # Pin für Austauschlüfter
pin_humidifier = 24           # Pin für Luftbefeuchter
verbose = True                # Dokumentiert interne Vorgänge wortreich

#-----------------------------------------------------------------------------------------RRD-Tool konfiguration
rrd_dbname = 'rss'                  # Name fuer Grafiken etc
rrd_filename = rrd_dbname + '.rrd'   # Dateinamen mit Endung
measurement_time_interval = 10      # Zeitintervall fuer die Messung in Sekunden
i = 0
z = 0

# Funktionen ############################################################################################################
########################################################################################################################




#-----------------------------------------------------------------------------------------Definition der Variablen
def doMainLoop():
    #global value
    global circulation_air_duration       #  Umluftdauer
    global circulation_air_period         #  Umluftperiode
    global circulation_air_start          #  Unix-Zeitstempel für den Zählstart des Timers Umluft
    global exhaust_air_duration           #  (Abluft-)luftaustauschdauer
    global exhaust_air_period             #  (Abluft-)luftaustauschperiode
    global exhaust_air_start              #  Unix-Zeitstempel für den Zählstart des Timers (Abluft-)Luftaustausch
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
    global status_cooling_compressor      #  Kühlung
    global z
    global status_humidifier              #  Luftbefeuchtung
    global counter_humidify               #  Zähler Verzögerung der Luftbefeuchtung
    global delay_humidify                 #  Luftbefeuchtungsverzögerung
    global status_exhaust_fan             #  Variable für die "Evakuierung" zur Feuchtereduzierung durch (Abluft-)Luftaustausch

#-----------------------------------------------------------------------------------------Prüfen Sensor, dann Settings einlesen
    while True:
        set_sensortype()
        if sensorname == 'DHT11': #DHT11
            print sensorname
            sensor_humidity_big, sensor_temperature_big = Adafruit_DHT.read_retry(sensor, pin_sensor_dht)
            atp = 17.271 # ermittelt aus dem Datenblatt DHT11 und DHT22
            btp = 237.7  # ermittelt aus dem Datenblatt DHT11 und DHT22
        elif sensorname == 'DHT22': #DHT22
            print sensorname
            sensor_humidity_big, sensor_temperature_big = Adafruit_DHT.read_retry(sensor, pin_sensor_dht)
            atp = 17.271 # ermittelt aus dem Datenblatt DHT11 und DHT22
            btp = 237.7  # ermittelt aus dem Datenblatt DHT11 und DHT22
        elif sensorname == 'SHT75': #SHT75
            sensor_temperature_big = pins_sensor_sht.read_t()
            sensor_humidity_big = pins_sensor_sht.read_rh()
        if sensor_humidity_big is not None and sensor_temperature_big is not None:
            sensor_temperature = round (sensor_temperature_big,2)
            sensor_humidity = round (sensor_humidity_big,2)
        else:
            print ('Failed to get reading. Try again!')
        try:
            settings = read_settings_json()
        except:
            write_verbose('Unable to read settings file, checking if in the blind.')
            continue
        modus = settings['modus']
        setpoint_temperature = settings['setpoint_temperature']
        setpoint_humidity = settings['setpoint_humidity']
        circulation_air_period = settings['circulation_air_period']
        circulation_air_duration = settings['circulation_air_duration']
        exhaust_air_period = settings['exhaust_air_period']
        exhaust_air_duration = settings['exhaust_air_duration']
        switch_on_cooling_compressor = settings['switch_on_cooling_compressor']
        switch_off_cooling_compressor = settings['switch_off_cooling_compressor']
        switch_on_humidifier = settings['switch_on_humidifier']
        switch_off_humidifier = settings['switch_off_humidifier']
        delay_humidify = settings ['delay_humidify']
        delay_humidify = delay_humidify * 10
        sensortype = settings ['sensortype']
        # An dieser Stelle sind alle settings eingelesen, Ausgabe auf Konsole
        lastSettingsUpdate = settings['last_change']
        os.system('clear') # Clears the terminal
        current_time = int(time.time())
        write_verbose(' ')
        write_verbose(logspacer)
        write_verbose(' ')
        write_verbose('Main loop/Unix-Timestamp: (' + str(current_time)+ ')')
        print ('-------------------------------------------------------')
        print ('Eingestellte Soll-Temperatur: ') + str(setpoint_temperature) + ('°C')
        print ('Gemessene Ist-Temperatur : ') + str(sensor_temperature) + ('°C')
        print ('-------------------------------------------------------')
        print ('Eingestellte Soll-Luftfeuchtigkeit: ') + str(setpoint_humidity) + ('%')
        print ('Gemessene Ist-Luftfeuchtigkeit :') + str(sensor_humidity) + ('%')
        print ('-------------------------------------------------------')
        print ('Eingestellter Sensor: ') + str(sensorname)
        print ('Wert in settings.json: ') + str(sensortype)
        print ('-------------------------------------------------------')
        write_current_json(sensor_temperature, sensor_humidity)

        # Durch den folgenden Timer läuft der Ventilator in den vorgegebenen Intervallen zusätzlich zur generellen Umluft bei aktivem Heizen, Kühlen oder Befeuchten
        #-------------------------------------------------------------------------Timer für Luftumwälzung-Ventilator
        if circulation_air_period == 0:                       # gleich 0 ist an,  Dauer-Timer
            status_circulation_air = False
        if circulation_air_duration == 0:                        # gleich 0 ist aus, kein Timer
            status_circulation_air = True
        if circulation_air_duration > 0:
            if current_time < circulation_air_start + circulation_air_period:
                status_circulation_air = True                       # Umluft - Ventilator aus
                print ('Umluft-Timer laeuft (inaktiv)')
            if current_time >= circulation_air_start + circulation_air_period:
                status_circulation_air = False                      # Umluft - Ventilator an
                print ('Umluft-Timer laeuft (aktiv)')
            if current_time >= circulation_air_start + circulation_air_period + circulation_air_duration:
                circulation_air_start = int(time.time())    # Timer-Timestamp aktualisiert

        #-------------------------------------------------------------------------Timer für (Abluft-)Luftaustausch-Ventilator
        if exhaust_air_period == 0:                      # gleich 0 ist an,  Dauer-Timer
            status_exhaust_air = False
        if exhaust_air_duration == 0:                        # gleich 0 ist aus, kein Timer
            status_exhaust_air = True
        if exhaust_air_duration > 0:                        # gleich 0 ist aus, kein Timer
            if current_time < exhaust_air_start + exhaust_air_period:
                status_exhaust_air = True                      # (Abluft-)Luftaustausch-Ventilator aus
                print ('Abluft-Timer laeuft (inaktiv)')
            if current_time >= exhaust_air_start + exhaust_air_period:
                status_exhaust_air = False                     # (Abluft-)Luftaustausch-Ventilator an
                print ('Abluft-Timer laeuft (aktiv)')
            if current_time >= exhaust_air_start + exhaust_air_period + exhaust_air_duration:
                exhaust_air_start = int(time.time())   # Timer-Timestamp aktualisiert

        #-------------------------------------------------------------------------Kühlen
        if modus == 0:
            status_exhaust_fan = False                     # Feuchtereduzierung Abluft aus
            gpio.output(pin_heater, relay_off)      # Heizung aus
            gpio.output(pin_humidifier, relay_off)         # Befeuchtung aus
            if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                gpio.output(pin_cooling_compressor, relay_on)     # Kühlung ein
            if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor :
                gpio.output(pin_cooling_compressor, relay_off)    # Kühlung aus

        #-------------------------------------------------------------------------Kühlen mit Befeuchtung
        if modus == 1:
            status_exhaust_fan = False                     # Feuchtereduzierung Abluft aus
            gpio.output(pin_heater, relay_off)      # Heizung aus
            if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                gpio.output(pin_cooling_compressor, relay_on)     # Kühlung ein
            if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor :
                gpio.output(pin_cooling_compressor, relay_off)    # Kühlung aus
            if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                gpio.output(pin_humidifier, relay_on)      # Befeuchtung ein
            if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                gpio.output(pin_humidifier, relay_off)     # Befeuchtung aus

        #-------------------------------------------------------------------------Heizen mit Befeuchtung
        if modus == 2:
            status_exhaust_fan = False                     # Feuchtereduzierung Abluft aus
            gpio.output(pin_cooling_compressor, relay_off)        # Kühlung aus
            if sensor_temperature <= setpoint_temperature - switch_on_cooling_compressor:
                gpio.output(pin_heater, relay_on)   # Heizung ein
            if sensor_temperature >= setpoint_temperature - switch_off_cooling_compressor:
                gpio.output(pin_heater, relay_off)  # Heizung aus
            if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                gpio.output(pin_humidifier, relay_on)      # Befeuchtung ein
            if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                gpio.output(pin_humidifier, relay_off)     # Befeuchtung aus

        #-------------------------------------------------------------------------Automatiktemperatur mit Befeuchtung
        if modus == 3:
            status_exhaust_fan = False                     # Feuchtereduzierung Abluft aus
            if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                gpio.output(pin_cooling_compressor, relay_on)     # Kühlung ein
            if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor:
                gpio.output(pin_cooling_compressor, relay_off)    # Kühlung aus
            if sensor_temperature <= setpoint_temperature - switch_on_cooling_compressor:
                gpio.output(pin_heater, relay_on)   # Heizung ein
            if sensor_temperature >= setpoint_temperature - switch_off_cooling_compressor:
                gpio.output(pin_heater, relay_off)  # Heizung aus
            if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                gpio.output(pin_humidifier, relay_on)      # Befeuchtung ein
            if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                gpio.output(pin_humidifier, relay_off)     # Befeuchtung aus

        #-------------------------------------------------------------------------Automatik mit Befeuchtung und Entfeuchtung durch (Abluft-)Luftaustausch
        if modus == 4:
            if sensor_temperature >= setpoint_temperature + switch_on_cooling_compressor:
                gpio.output(pin_cooling_compressor, relay_on)     # Kühlung ein
            if sensor_temperature <= setpoint_temperature + switch_off_cooling_compressor:
                gpio.output(pin_cooling_compressor, relay_off)    # Kühlung aus
            if sensor_temperature <= setpoint_temperature - switch_on_cooling_compressor:
                gpio.output(pin_heater, relay_on)   # Heizung ein
            if sensor_temperature >= setpoint_temperature - switch_off_cooling_compressor:
                gpio.output(pin_heater, relay_off)  # Heizung aus
            if sensor_humidity <= setpoint_humidity - switch_on_humidifier:
                counter_humidify = counter_humidify + 1
                if counter_humidify >= delay_humidify:               # Verzögerung der Luftbefeuchtung
                    gpio.output(pin_humidifier, relay_on)  # Luftbefeuchter ein
            if sensor_humidity >= setpoint_humidity - switch_off_humidifier:
                gpio.output(pin_humidifier, relay_off)     # Luftbefeuchter aus
                counter_humidify = 0
            if sensor_humidity >= setpoint_humidity + switch_on_humidifier:
                status_exhaust_fan = True                        # Feuchtereduzierung Abluft-Ventilator ein
            if sensor_humidity <= setpoint_humidity + switch_off_humidifier:
                status_exhaust_fan = False                         # Feuchtereduzierung Abluft-Ventilator aus

        #-------------------------------------------------------------------------Schalten des Umluft - Ventilators
        if gpio.input(pin_heater) or gpio.input(pin_cooling_compressor) or gpio.input(pin_humidifier) or status_circulation_air == False:
            gpio.output(pin_recirculation_fan, relay_on)               # Umluft - Ventilator an
        if gpio.input(pin_heater) and gpio.input(pin_cooling_compressor) and gpio.input(pin_humidifier) and status_circulation_air == True:
            gpio.output(pin_recirculation_fan, relay_off)             # Umluft - Ventilator aus

        #-------------------------------------------------------------------------Schalten des (Abluft-)Luftaustausch-Ventilator
        if status_exhaust_air == False or status_exhaust_fan == True:
            gpio.output(pin_exhaust_fan, relay_on)
        if status_exhaust_fan = False and status_exhaust_air == True:
            gpio.output(pin_exhaust_fan, relay_off)

        #-------------------------------------------------------------------------Ausgabe der Werte auf der Konsole
        print ('-------------------------------------------------------')
        if gpio.input(pin_heater) == False:
            write_verbose('Heizung ein')
            status_heater = 10
        else:
            write_verbose('Heizung aus')
            status_heater = 0
        if gpio.input(pin_cooling_compressor) == False:
            write_verbose('Kuehlung ein')
            status_cooling_compressor = 10
        else:
            write_verbose('Kuehlung aus')
            status_cooling_compressor = 0
        if gpio.input(pin_humidifier) == False:
            write_verbose('Luftbefeuchter ein')
            status_humidifier = 10
        else:
            write_verbose('Luftbefeuchter aus')
            status_humidifier = 0
        if gpio.input(pin_recirculation_fan) == False:
            write_verbose('Umluft ein')
            status_circulating_air = 10
        else:
            write_verbose('Umluft aus')
            status_circulating_air = 0
        if gpio.input(pin_exhaust_fan) == False:
            write_verbose('Abluft ein')
            status_exhaust_air = 10
        else:
            write_verbose('Abluft aus')
            status_exhaust_air = 0
        print ('-------------------------------------------------------')
       #--------------------------------------------------------------------------Messwerte in die RRD-Datei schreiben
        from rrdtool import update as rrd_update
        ret = rrd_update('%s' %(rrd_filename), 'N:%s:%s:%s:%s:%s:%s:%s' %(sensor_temperature, sensor_humidity, status_exhaust_air, status_circulating_air, status_heater, status_cooling_compressor, status_humidifier))
        #array für graph     
        # Grafiken erzeugen
        if z >= 2:
            print "Erzeuge Grafiken"
            ploting('sensor_temperature')#', 'status_heater', 'status_cooling_compressor', 'status_circulating_air')
            ploting('sensor_humidity')#, 'status_humidifier', 'status_circulating_air', 'status_exhaust_air')
            ploting('status_circulating_air')#, 'status_exhaust_air')
            ploting('status_exhaust_air')
            ploting('status_heater')
            ploting('status_cooling_compressor')
            ploting('status_humidifier')
            z = 0
        else:
            z = z + 1

        time.sleep(1)  
        # Mainloop fertig
        write_verbose('Loop complete.')
        time.sleep(3)



######################################################### Hauptprogramm
########################################################################################################################

os.system('clear') # Bildschirm löschen
write_verbose(logspacer)
setupGPIO() # GPIO initialisieren

# RRD-Datenbank anlegen, wenn nicht vorhanden
try:
    with open(rrd_filename): pass
    print "Datenbankdatei gefunden: " + rrd_filename
    i = 1
except IOError:
    print "Ich erzeuge eine neue Datenbank: " + rrd_filename
    ret = rrdtool.create("%s" %(rrd_filename),
        "--step","%s" %(measurement_time_interval),
        "--start",'0',
        "DS:rss_sensor_temperature:GAUGE:2000:U:U",
        "DS:rss_sensor_humidity:GAUGE:2000:U:U",
        "DS:rss_status_exhaust_air:GAUGE:2000:U:U",
        "DS:rss_status_circulating_air:GAUGE:2000:U:U",
        "DS:rss_status_heater:GAUGE:2000:U:U",
        "DS:rss_status_cooling_compressor:GAUGE:2000:U:U",
        "DS:rss_status_humidifier:GAUGE:2000:U:U",
        "RRA:AVERAGE:0.5:1:2160",
        "RRA:AVERAGE:0.5:5:2016",
        "RRA:AVERAGE:0.5:15:2880",
        "RRA:AVERAGE:0.5:60:8760",)
    i = 1
write_verbose(logspacer)
settings = read_settings_json()       
circulation_air_start = int(time.time())
exhaust_air_start = circulation_air_start
try:
    doMainLoop()
except KeyboardInterrupt:
    pass

except Exception, e:
    write_verbose('Exception occurred!!!', True)
    write_verbose(str(e), True)
    pass

goodbye()
