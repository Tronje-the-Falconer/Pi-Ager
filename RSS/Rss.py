#!/usr/bin/python
# -*- coding: iso-8859-1 -*-
#-----------------------------------------------------------------------------------------Importieren der Module

import os
import json
import glob
import time
import Adafruit_DHT
import time
import RPi.GPIO as gpio
import rrdtool
from sht_sensor import Sht

# Konstanten ############################################################################################################
########################################################################################################################

PATH = '/var/www/'
SETTINGS_FILE = PATH+'settings.json'
CURRENT_FILE = PATH+'current.json'
PICPATH = '/var/www/pic/'

# Sensor sollte Adafruit_DHT.DHT11, Adafruit_DHT22, oder Adafruit_AM2302 sein
sensor = Adafruit_DHT.AM2302

#-----------------------------------------------------------------------------------------Sainsmart Relais Vereinfachung 0 aktiv
RELAY_ON = False
RELAY_OFF = (not RELAY_ON)

DELAY = 4               # Wartezeit in der Schleife
count = 0               # Zähler für die Verzögerung der Befeuchtung
evac = True             # Variable für die "Evakuierung" zur Feuchtereduzierung durch (Abluft-)Luftaustausch

#-----------------------------------------------------------------------------------------Pinbelegung
BOARD_MODE = gpio.BCM   # GPIO board mode
PIN_DHT = 17            # Pin für Temp/Hum Sensor
PIN_HEATER = 27         # Pin für Heizkabel
PIN_COOL = 22           # Pin für Kühlschrankkompressor

PIN_FAN = 18            # Pin für Umluftventilator
PIN_FAN1 = 23           # Pin für Austauschlüfter
PIN_HUM = 24            # Pin für Luftbefeuchter
sht = Sht(21, 20)
VERBOSE = True          # Dokumentiert interne Vorgänge wortreich

#-----------------------------------------------------------------------------------------RRD-Tool konfiguration
dbname = 'dht22'            # Name fuer Grafiken etc
filename = dbname +'.rrd'   # Dateinamen mit Endung
steps = 10                  # Zeitintervall fuer die Messung in Sekunden
i = 0
z = 0



# Funktionen ############################################################################################################
########################################################################################################################

def goodbye():
    cleanup() 
    writeVerbose('Goodbye!')
    
def cleanup():
    writeVerbose('Running cleanup script...')
    gpio.cleanup() # GPIO zurücksetzen
    writeVerbose('Cleanup complete.', True)

def setupGPIO():
    global BOARD_MODE
    global PIN_HEATER
    global PIN_COOL
    global PIN_FAN
    global PIN_HUM
    global PIN_FAN1
    
    writeVerbose('Setting up GPIO...')
    gpio.setwarnings(False)
    
    #-------------------------------------------------------------------------------------Board mode wird gesetzt
    gpio.setmode(BOARD_MODE)
    
    #-------------------------------------------------------------------------------------Einstellen der GPIO PINS (nichts verändern, auch die Leerzeichen spielen eine Rolle!)
    gpio.setup(PIN_HEATER,      gpio.OUT)
    gpio.setup(PIN_COOL,        gpio.OUT)
    gpio.setup(PIN_FAN,             gpio.OUT)
    gpio.setup(PIN_HUM,             gpio.OUT)
    gpio.setup(PIN_FAN1,            gpio.OUT)

    gpio.output(PIN_HEATER, RELAY_OFF)
    gpio.output(PIN_COOL, RELAY_OFF)
    gpio.output(PIN_FAN, RELAY_OFF)
    gpio.output(PIN_HUM, RELAY_OFF)
    gpio.output(PIN_FAN1, RELAY_OFF)

    writeVerbose('GPIO setup complete.',True)

def writeVerbose(s, newLine=False):
    global VERBOSE
    
    if(VERBOSE):
        print(s)
        if(newLine is True):
            print('')

#-----------------------------------------------------------------------------------------Schreiben der current.json
def write_current(sensortemp, sensorhum):
    global CURRENT_FILE

    s = json.dumps({"temperatur":sensortemp, "heizung":gpio.input(PIN_HEATER), "luftaustausch":gpio.input(PIN_FAN1), "kuhlung":gpio.input(PIN_COOL), "umluft":gpio.input(PIN_FAN),"luftfeuchtigkeit":sensorhum, 'date':int(time.time())})
    with open(CURRENT_FILE, 'w') as file:
        file.write(s)

#-----------------------------------------------------------------------------------------Auslesen der settings.json
def readSettings():
    global SETTINGS_FILE
    s = None
    with open(SETTINGS_FILE, 'r') as file:
        s = file.read()
    data = json.loads(s)
    return data

#-----------------------------------------------------------------------------------------Funktion zum Plotten der Grafiken
#a: Wert, der geplottet werden soll
def plotten(a):#, b, c, d):
    # Beschriftung für die Grafiken festlegen
    if a == 'sensortemp':
        title = 'Temperatur'
        label = 'in C'
    elif a == 'sensorhum':
        title = 'Luftfeuchtigkeit'
        label = 'in %'
    elif a == "lat":
        title = 'Luftaustausch'
        label = 'ein oder aus'
    elif a == "uml":
        title = 'Luftumwaelzung'
        label = 'ein oder aus'
    elif a == "heat":
        title = 'Heizung'
        label = 'ein oder aus'
    elif a == "cool":
        title = 'Kuehlung'
        label = 'ein oder aus'
    elif a == "lbf":
        title = 'Luftbefeuchter'
        label = 'ein oder aus'

    # Aufteilung in drei Plots
    for plot in ['daily' , 'weekly', 'monthly', 'hourly']:
        if plot == 'weekly':
            period = 'w'
        elif plot == 'daily':
            period = 'd'
        elif plot == 'monthly':
            period = 'm'
        elif plot == 'hourly':
            period = 'h'

        # Grafiken erzeugen
        ret = rrdtool.graph("%s%s_%s-%s.png" %(PICPATH,dbname,a,plot),
            "--start",
            "-1%s" %(period),
            "--title=%s (%s)" %(title,plot),
            "--vertical-label=%s" %(label),
            '--watermark=Grillsportverein',
            "-w 400",
            #"-h 600",
            "--alt-autoscale",
            "--slope-mode",
            "DEF:%s=%s:%s_%s:AVERAGE" %(a, filename, dbname, a),
            "DEF:durch=dht22.rrd:dht22_sensortemp:AVERAGE",
            "DEF:durchhum=dht22.rrd:dht22_sensorhum:AVERAGE",
            #"DEF:%s=%s:%s_%s:AVERAGE" %(c, filename, dbname, c),
            #"DEF:%s=%s:%s_%s:AVERAGE" %(d, filename, dbname, d),
            "GPRINT:durch:AVERAGE:Temperatur\: %3.2lf C",
            "GPRINT:durchhum:AVERAGE:Luftfeuchtigkeit\: %3.2lf", 
            "LINE1:%s#0000FF:%s_%s" %(a, dbname, a))
            #"LINE1:%s#00FF00:%s_%s" %(b, dbname, b),
            #"LINE1:%s#FF0000:%s_%s" %(c, dbname, c),
            #"LINE1:%s#000000:%s_%s" %(d, dbname, d))

# def set_sensortype():
    # global sensor
    # global sensorname
    # global sensorvalue
    # settings = readSettings()
    # sensortype = settings ['sensortype']
    ##Sensor should be set to Adafruit_DHT.DHT11,
    ##Adafruit_DHT22, or Adafruit_AM2302.
    ##sensor = Adafruit_DHT.AM2302
    # if sensortype == 1: #DHT11
        # sensor = Adafruit_DHT.DHT11
        # sensorname = 'DHT11'
        # sensorvalue = 1
    # elif sensortype == 2: #DHT22
        # sensor = Adafruit_DHT.DHT22
        # sensorname = 'DHT22'
        # sensorvalue = 2
    # elif sensortype == 3: #SHT75 sensor=22
        # sensor = Adafruit_DHT.AM2302
        # sensorname = 'SHT75'
        # sensorvalue = 3
#-----------------------------------------------------------------------------------------Definition der Variablen
def doMainLoop():
    global value
    global tempon               #  Umluftdauer
    global tempoff              #  Umluftperiode
    global tempstart            #  Unix-Zeitstempel für den Zählstart des Timers Umluft
    global tempon1              #  (Abluft-)luftaustauschdauer
    global tempoff1             #  (Abluft-)luftaustauschperiode
    global tempstart1           #  Unix-Zeitstempel für den Zählstart des Timers (Abluft-)Luftaustausch
    global sensortemp           #  Gemessene Temperatur am Sensor
    global sensorhum            #  Gemessene Feuchtigkeit am Sensor
    global temphyston           #  Einschalttemperatur
    global temphystoff          #  Ausschalttemperatur
    global humhyston            #  Einschaltfeuchte
    global humhystoff           #  Ausschaltfeuchte
    global temperature
    global settings
    global uml                  #  Umluft
    global lat                  #  (Abluft-)Luftaustausch
    global heat                 #  Heizung
    global cool                 #  Kühlung
    global z
    global lbf                  #  Luftbefeuchtung
    global count                #  Zähler Verzögerung der Luftbefeuchtung
    global humdelay             #  Luftbefeuchtungsverzögerung
    global evac                 #  Variable für die "Evakuierung" zur Feuchtereduzierung durch (Abluft-)Luftaustausch

#-----------------------------------------------------------------------------------------Prüfen Sensor, dann Settings einlesen
    while True:
        # set_sensortype()
        # if sensorname == 'DHT11': #DHT11
            # print sensorname
            # sensorhum1, sensortemp1 = Adafruit_DHT.read_retry(sensor, PIN_DHT)
            # atp = 17.271 # ermittelt aus dem Datenblatt DHT11 und DHT22
            # btp = 237.7  # ermittelt aus dem Datenblatt DHT11 und DHT22
            # dew_point_temp = (atp * sensortemp1) / (btp + sensortemp1) + log(sensorhum1 / 100)
            # dew_point = (btp * dew_point_temp) / (atp - dew_point_temp)
            # dew_point = round (dew_point,1)
        # elif sensorname == 'DHT22': #DHT22
            # print sensorname
            # sensorhum1, sensortemp1 = Adafruit_DHT.read_retry(sensor, PIN_DHT)
            # atp = 17.271 # ermittelt aus dem Datenblatt DHT11 und DHT22
            # btp = 237.7  # ermittelt aus dem Datenblatt DHT11 und DHT22
            # dew_point_temp = (atp * sensortemp1) / (btp + sensortemp1) + log(sensorhum1 / 100)
            # dew_point = (btp * dew_point_temp) / (atp - dew_point_temp)
            # dew_point = round (dew_point,1)
        # elif sensorname == 'SHT75': #SHT75
            # sensortemp1 = sht.read_t()
            # sensorhum1 = sht.read_rh()
            # dew_point = sht.read_dew_point(sensortemp1, sensorhum1)
            # dew_point = round (dew_point,1)
        sensortemp1 = sht.read_t()
        sensorhum1 = sht.read_rh()
        dew_point = sht.read_dew_point(sensortemp1, sensorhum1)
        dew_point = round (dew_point,1)
        if sensorhum1 is not None and sensortemp1 is not None:
            sensortemp = round (sensortemp1,2)
            sensorhum = round (sensorhum1,2)
            print 'in if'
        else:
            print ('Failed to get reading. Try again!')
        try:
            settings = readSettings()
        except:
            writeVerbose('Unable to read settings file, checking if in the blind.')
            continue
        mod = settings['mod']
        temp = settings['temp']
        hum = settings['hum']
        tempoff = settings['tempoff']
        tempon = settings['tempon']
        tempoff1 = settings['tempoff1']
        tempon1 = settings['tempon1']
        temphyston = settings['temphyston']
        temphystoff = settings['temphystoff']
        humhyston = settings['humhyston']
        humhystoff = settings['humhystoff']
        humdelay = settings ['humdelay']
        # sensortype = settings ['sensortype']
        humdelay = humdelay*10
        print'variablen gefüllt'
        # An dieser Stelle sind alle settings eingelesen, Ausgabe auf Konsole
        lastSettingsUpdate = settings['date']
        os.system('clear') # Clears the terminal
        t = int(time.time())
        writeVerbose(' ')
        writeVerbose('************************************************************')
        writeVerbose(' ')
        writeVerbose('Main loop/Unix-Timestamp: ('+str(t)+')')
        print ('-------------------------------------------------------')
        print ('Eingestellte Soll-Temperatur: ')+str (temp)+('°C')
        print ('Gemessene Ist-Temperatur : ')+str (sensortemp)+('°C')
        print ('-------------------------------------------------------')
        print ('Eingestellte Soll-Luftfeuchtigkeit: ')+str (hum)+('%')
        print ('Gemessene Ist-Luftfeuchtigkeit :')+str (sensorhum)+('%')
        print ('-------------------------------------------------------')
        #print ('Eingestellter Sensor:')+str (sensorname)
        write_current(sensortemp, sensorhum)

        # Durch den folgenden Timer läuft der Ventilator in den vorgegebenen Intervallen zusätzlich zur generellen Umluft bei aktivem Heizen, Kühlen oder Befeuchten
        #-------------------------------------------------------------------------Timer für Luftumwälzung-Ventilator
        if tempoff > 0:                         # gleich 0 ist aus, kein Timer
            #Unix Timestamp
            t = int(time.time())
            if t < tempstart + tempoff:
                vent=True                       # Umluft - Ventilator aus
                print ('Umluft-Timer laeuft (inaktiv)')
            if t >= tempstart + tempoff:
                vent=False                      # Umluft - Ventilator an
                print ('Umluft-Timer laeuft (aktiv)')
            if t >= tempstart + tempoff + tempon:
                tempstart = int(time.time())    # Timer-Timestamp aktualisiert
        else:
            vent=True
            #  print ('Abluft-Timer augeschaltet')

        #-------------------------------------------------------------------------Timer für (Abluft-)Luftaustausch-Ventilator
        if tempoff1 > 0:                        # gleich 0 ist aus, kein Timer
            if t < tempstart1 + tempoff1:
                vent1=True                      # (Abluft-)Luftaustausch-Ventilator aus
                print ('Abluft-Timer laeuft (inaktiv)')
            if t >= tempstart1 + tempoff1:
                vent1=False                     # (Abluft-)Luftaustausch-Ventilator an
                print ('Abluft-Timer laeuft (aktiv)')
            if t >= tempstart1 + tempoff1 + tempon1:
                tempstart1 = int(time.time())   # Timer-Timestamp aktualisiert
        else:
            vent1=True
            # print('Abluft-Timer augeschaltet')

        #-------------------------------------------------------------------------Kühlen mit Befeuchtung
        if mod == 1:
            evac=True                                  # Feuchtereduzierung Abluft aus
            gpio.output(PIN_HEATER, RELAY_OFF)        # Heizung aus
            if sensortemp >= temp + temphyston:
                gpio.output(PIN_COOL, RELAY_ON)   # Kühlung ein
            if sensortemp <= temp + temphystoff :
                gpio.output(PIN_COOL, RELAY_OFF)  # Kühlung aus
            if sensorhum <= hum - humhyston:
                gpio.output(PIN_HUM, RELAY_ON)    # Befeuchtung ein
            if sensorhum >= hum - humhystoff:
                gpio.output(PIN_HUM, RELAY_OFF)   # Befeuchtung aus

        #-------------------------------------------------------------------------Heizen mit Befeuchtung
        if mod == 2:
            evac=True                                    # Feuchtereduzierung Abluft aus
            gpio.output(PIN_COOL, RELAY_OFF)            # Kühlung aus
            if sensortemp <= temp - temphyston:
                gpio.output(PIN_HEATER, RELAY_ON)   # Heizung ein
            if sensortemp >= temp - temphystoff:
                gpio.output(PIN_HEATER, RELAY_OFF)  # Heizung aus
            if sensorhum <= hum - humhyston:
                gpio.output(PIN_HUM, RELAY_ON)      # Befeuchtung ein
            if sensorhum >= hum - humhystoff:
                gpio.output(PIN_HUM, RELAY_OFF)     # Befeuchtung aus

        #-------------------------------------------------------------------------Automatiktemperatur mit Befeuchtung
        if mod == 3:
            evac=True                                    # Feuchtereduzierung Abluft aus
            if sensortemp >= temp + temphyston:
                gpio.output(PIN_COOL, RELAY_ON)     # Kühlung ein
            if sensortemp <= temp + temphystoff:
                gpio.output(PIN_COOL, RELAY_OFF)    # Kühlung aus
            if sensortemp <= temp - temphyston:
                gpio.output(PIN_HEATER, RELAY_ON)   # Heizung ein
            if sensortemp >= temp - temphystoff:
                gpio.output(PIN_HEATER, RELAY_OFF)  # Heizung aus
            if sensorhum <= hum - humhyston:
                gpio.output(PIN_HUM, RELAY_ON)      # Befeuchtung ein
            if sensorhum >= hum - humhystoff:
                gpio.output(PIN_HUM, RELAY_OFF)     # Befeuchtung aus

        #-------------------------------------------------------------------------Automatik mit Befeuchtung und Entfeuchtung durch (Abluft-)Luftaustausch
        if mod == 4:
            if sensortemp >= temp + temphyston:
                gpio.output(PIN_COOL, RELAY_ON)     # Kühlung ein
            if sensortemp <= temp + temphystoff:
                gpio.output(PIN_COOL, RELAY_OFF)    # Kühlung aus
            if sensortemp <= temp - temphyston:
                gpio.output(PIN_HEATER, RELAY_ON)   # Heizung ein
            if sensortemp >= temp - temphystoff:
                gpio.output(PIN_HEATER, RELAY_OFF)  # Heizung aus
            if sensorhum <= hum - humhyston:
                count = count+1
                if count >= humdelay:                # Verzögerung der Luftbefeuchtung
                    gpio.output(PIN_HUM, RELAY_ON)  # Luftbefeuchter ein
            if sensorhum >= hum - humhystoff:
                gpio.output(PIN_HUM, RELAY_OFF)     # Luftbefeuchter aus
                count = 0
            if sensorhum >= hum + humhyston:
                evac = False                         # Feuchtereduzierung Abluft-Ventilator ein
            if sensorhum <= hum + humhystoff:
                evac = True                          # Feuchtereduzierung Abluft-Ventilator aus

        #-------------------------------------------------------------------------Schalten des Umluft - Ventilators
        if gpio.input(PIN_HEATER) or gpio.input(PIN_COOL) or gpio.input(PIN_HUM) or vent == False:
            gpio.output(PIN_FAN, RELAY_ON)               # Umluft - Ventilator an
        if gpio.input(PIN_HEATER) and gpio.input(PIN_COOL) and gpio.input(PIN_HUM) and vent == True:
            gpio.output(PIN_FAN, RELAY_OFF)             # Umluft - Ventilator aus

        #-------------------------------------------------------------------------Schalten des (Abluft-)Luftaustausch-Ventilator
        if vent1 ==False or evac == False:
            gpio.output(PIN_FAN1, RELAY_ON)
        if evac and vent1 == True:
            gpio.output(PIN_FAN1, RELAY_OFF)

        #-------------------------------------------------------------------------Ausgabe der Werte auf der Konsole
        print ('-------------------------------------------------------')
        if gpio.input(PIN_HEATER) == False:
            writeVerbose('Heizung ein')
            heat = 10
        else:
            writeVerbose('Heizung aus')
            heat = 0
        if gpio.input(PIN_COOL) == False:
            writeVerbose('Kuehlung ein')
            cool = 10
        else:
            writeVerbose('Kuehlung aus')
            cool = 0
        if gpio.input(PIN_HUM) == False:
            writeVerbose('Luftbefeuchter ein')
            lbf = 10
        else:
            writeVerbose('Luftbefeuchter aus')
            lbf = 0
        if gpio.input(PIN_FAN) == False:
            writeVerbose('Umluft ein')
            uml = 10
        else:
            writeVerbose('Umluft aus')
            uml = 0
        if gpio.input(PIN_FAN1) == False:
            writeVerbose('Abluft ein')
            lat = 10
        else:
            writeVerbose('Abluft aus')
            lat = 0
        print ('-------------------------------------------------------')
       #--------------------------------------------------------------------------Messwerte in die RRD-Datei schreiben
        from rrdtool import update as rrd_update
        ret = rrd_update('%s' %(filename), 'N:%s:%s:%s:%s:%s:%s:%s' %(sensortemp, sensorhum, lat, uml, heat, cool, lbf))
        #array für graph     
        # Grafiken erzeugen
        if z >= 2:
            print "Erzeuge Grafiken"
            plotten('sensortemp')#', 'heat', 'cool', 'uml')
            plotten('sensorhum')#, 'lbf', 'uml', 'lat')
            plotten('uml')#, 'lat')
            plotten('lat')
            plotten('heat')
            plotten('cool')
            plotten('lbf')
            z = 0
        else:
            z = z+1

        time.sleep(1)  
        # Mainloop fertig
        writeVerbose('Loop complete.')
        time.sleep(3)



# Hauptprogramm #################################################################################################################
########################################################################################################################

os.system('clear') # Bildschirm löschen
writeVerbose('************************************************************')
setupGPIO() # GPIO initialisieren

# RRD-Datenbank anlegen, wenn nicht vorhanden
try:
    with open(filename): pass
    print "Datenbankdatei gefunden: " + filename
    i=1
except IOError:
    print "Ich erzeuge eine neue Datenbank: " + filename
    ret = rrdtool.create("%s" %(filename),
        "--step","%s" %(steps),
        "--start",'0',
        "DS:dht22_sensortemp:GAUGE:2000:U:U",
        "DS:dht22_sensorhum:GAUGE:2000:U:U",
        "DS:dht22_lat:GAUGE:2000:U:U",
        "DS:dht22_uml:GAUGE:2000:U:U",
        "DS:dht22_heat:GAUGE:2000:U:U",
        "DS:dht22_cool:GAUGE:2000:U:U",
        "DS:dht22_lbf:GAUGE:2000:U:U",
        "RRA:AVERAGE:0.5:1:2160",
        "RRA:AVERAGE:0.5:5:2016",
        "RRA:AVERAGE:0.5:15:2880",
        "RRA:AVERAGE:0.5:60:8760",)
    i=1
writeVerbose('************************************************************')
settings = readSettings()       
tempstart = int(time.time())
tempstart1 = tempstart
try:
    doMainLoop()
except KeyboardInterrupt:
    pass

except Exception, e:
    writeVerbose('Exception occurred!!!', True)
    writeVerbose(str(e), True)
    pass

goodbye()

