#!/usr/bin/python
# -*- coding: iso-8859-1 -*-
#Importieren der Module

import os;
import json;
import glob;
import time;
import Adafruit_DHT;
import time;
import RPi.GPIO as gpio;
import rrdtool;
from sht_sensor import Sht

# Konstanten ############################################################################################################
########################################################################################################################

PATH = '/var/www/';
SETTINGS_FILE = PATH+'settings.json';
CURRENT_FILE = PATH+'current.json';
PICPATH = '/var/www/pic/';
        



# Sensor should be set to Adafruit_DHT.DHT11,
# Adafruit_DHT22, or Adafruit_AM2302.
sensor = Adafruit_DHT.DHT11



# Sainsmart Relais vereinfachung 0 aktiv
RELAY_ON = False;
RELAY_OFF = (not RELAY_ON);

DELAY = 4;              #Wartezeit in der Schleife
count = 0;               #Zähler für die Verzögerung der Befeuchtung
evac = True
# Pinbelegung
BOARD_MODE = gpio.BCM;	# GPIO board mode
PIN_DHT = 17;           # Pin für Temp/Hum Sensor
PIN_HEATER = 27;	# Pin für Heizkabel
PIN_COOL = 22;		# Pin für Kühlschrankkompressor

PIN_FAN = 18;           # Pin für Umluftventilator
PIN_FAN1 = 23;          # Pin für Austauschlüfter
PIN_HUM = 24;           # Pin für Luftbefeuchter
sht = Sht(21, 20)
VERBOSE = True;

#RRD-Tool konfiguration
dbname = 'dht22'                        # Name fuer Grafiken etc
filename = dbname +'.rrd'               # Dateinamen mit Endung
steps = 10                              # Zeitintervall fuer die Messung in Sekunden
i = 0
z = 0



# Funktionen ############################################################################################################
########################################################################################################################

def goodbye():
	cleanup(); 
	writeVerbose('Goodbye!');
	
def cleanup():
	writeVerbose('Running cleanup script...');
	gpio.cleanup(); # GPIO zurücksetzen
	writeVerbose('Cleanup complete.', True);


def setupGPIO():
	global BOARD_MODE;
	global PIN_HEATER;
	global PIN_COOL;
	global PIN_FAN;
	global PIN_HUM;
	global PIN_FAN1;
	
	writeVerbose('Setting up GPIO...');
	gpio.setwarnings(False);
	
	# Board mode wird gesetzt
	gpio.setmode(BOARD_MODE);
	
	# Einstellen der GPIO PINS
	gpio.setup(PIN_HEATER,  	gpio.OUT);
	gpio.setup(PIN_COOL,		gpio.OUT);
	gpio.setup(PIN_FAN,             gpio.OUT);
        gpio.setup(PIN_HUM,             gpio.OUT);
        gpio.setup(PIN_FAN1,            gpio.OUT);
        
	gpio.output(PIN_HEATER, RELAY_OFF);
	gpio.output(PIN_COOL, RELAY_OFF);
	gpio.output(PIN_FAN, RELAY_OFF);
	gpio.output(PIN_HUM, RELAY_OFF);
	gpio.output(PIN_FAN1, RELAY_OFF);

	writeVerbose('GPIO setup complete.',True);




def writeVerbose(s, newLine=False):
	global VERBOSE;
	
	if(VERBOSE):
		print(s);
		if(newLine is True):
			print('');

def write_current(sensortemp, sensorhum):
	global CURRENT_FILE;	

	s = json.dumps({"temperatur":sensortemp, "heizung":gpio.input(PIN_HEATER), "luftaustausch":gpio.input(PIN_FAN1), "kuhlung":gpio.input(PIN_COOL), "umluft":gpio.input(PIN_FAN),"luftfeuchtigkeit":sensorhum, 'date':int(time.time())});
	with open(CURRENT_FILE, 'w') as file:
		file.write(s);

def readSettings():
	global SETTINGS_FILE;
	s = None;
	with open(SETTINGS_FILE, 'r') as file:
		s = file.read();
	data = json.loads(s);
	return data;

def plotten(a):#, b, c, d):
    
    

# Funktion zum Plotten der Grafiken
# a:   Wert, der geplottet werden soll
    
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
            title = 'Luftumwelzung'
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
                             '--watermark=leben-zwo-punkt-null.de',
                             "-w 800",
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
                            



def doMainLoop():
	global value
        global tempon;
        global tempoff;
        global tempstart;
        global tempon1;
        global tempoff1;
        global tempstart1;
	global sensortemp;
	global sensorhum;
	global temphyston;
	global temphystoff;
	global humhyston;
	global humhystoff;
	global temperature
	global settings
	global uml;
	global lat;
	global heat;
	global cool;
	global z;
	global lbf;
	global count;
	global humdelay;
	global evac;
        
        while True:

                #sensortemp1 = sht.read_t()
                #sensorhum1 = sht.read_rh()
 
                #dew_point = sht.read_dew_point(sensortemp1, sensorhum1)
                #dew_point = round (dew_point,1)
                sensorhum1, sensortemp1 = Adafruit_DHT.read_retry(sensor, PIN_DHT)
                
                if sensorhum1 is not None and sensortemp1 is not None:
                        sensortemp = round (sensortemp1,2)
                        sensorhum = round (sensorhum1,2)
                      

                else:
                    print 'Failed to get reading. Try again!'
                try:
			settings = readSettings();
		except:
			# An exception occurred and settings file cannot be reached.
			writeVerbose('Unable to read settings file, checking if in the blind.');
			
			continue;
		mod = settings['mod'];
                temp = settings['temp'];
                hum = settings['hum'];
                tempoff = settings['tempoff'];
                tempon = settings['tempon'];
                tempoff1 = settings['tempoff1'];
                tempon1 = settings['tempon1'];
                temphyston = settings['temphyston'];
                temphystoff = settings['temphystoff'];
                humhyston = settings['humhyston'];
                humhystoff = settings['humhystoff'];
                humdelay = settings ['humdelay'];
                humdelay = humdelay*10;
		
		# At this point, the settings have been read.
		# However we still need to ensure that the last update time isn't out of our emergency range.
		lastSettingsUpdate = settings['date'];
                os.system('clear'); # Clears the terminal
                t = int(time.time());
                writeVerbose(' ');
                writeVerbose('************************************************************');
                writeVerbose(' ');
                writeVerbose('Main loop... ('+str(t)+')');
                print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
                print ('Aktuelle Temperatur :')+str (sensortemp);
                print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
                print ('Aktuelle Luftfeuchtigkeit :')+str (sensorhum);
                print ('Aktuelle Taupunkt :')+str (dew_point);              
                
                write_current(sensortemp, sensorhum);

                #Timer für Luftumwelzung
                if tempoff > 0: 

                        t = int(time.time())
                      
                        if t < tempstart + tempoff:
                                #gpio.output(PIN_FAN, RELAY_OFF);
                                vent=True
                        if t >= tempstart + tempoff:
                                #gpio.output(PIN_FAN, RELAY_ON);
                                vent=False
                        if t >= tempstart + tempoff + tempon:
                                tempstart = int(time.time())
                                

                #Timer für Luftaustausch
                if tempoff1 > 0:

                        #t = int(time.time())
                      
                        if t < tempstart1 + tempoff1:
                                #gpio.output(PIN_FAN1, RELAY_OFF);
                                vent1=True
                                #print ('Timer umluft aus')
                        if t >= tempstart1 + tempoff1:
                                #gpio.output(PIN_FAN1, RELAY_ON);
                                vent1=False
                                #print ('Timer umluft ein')
                        if t >= tempstart1 + tempoff1 + tempon1:
                                tempstart1 = int(time.time())
                                
                        
                        
                
                if mod == 1: #Kühlmodus
                        evac=True
                        gpio.output(PIN_HEATER, RELAY_OFF);
                
                        if sensortemp >= temp + temphyston:                                
                                gpio.output(PIN_COOL, RELAY_ON);
                               
                        if sensortemp <= temp + temphystoff :    
                                gpio.output(PIN_COOL, RELAY_OFF);
                                

                        if sensorhum <= hum - humhyston:
                                gpio.output(PIN_HUM, RELAY_ON);
                                
                        if sensorhum >= hum - humhystoff:
                                gpio.output(PIN_HUM, RELAY_OFF);
                                
                                                                
                                                               
                                
                if mod == 2: #Heizmodus
                        evac=True

                        gpio.output(PIN_COOL, RELAY_OFF);
                        
                        if sensortemp <= temp - temphyston:
                                gpio.output(PIN_HEATER, RELAY_ON);

                        if sensortemp >= temp - temphystoff:
                                gpio.output(PIN_HEATER, RELAY_OFF);

                        if sensorhum <= hum - humhyston:
                                gpio.output(PIN_HUM, RELAY_ON);
                        if sensorhum >= hum - humhystoff:
                                gpio.output(PIN_HUM, RELAY_OFF);

                        
                        
                if mod == 3: #Automodus
                        evac=True
                        if sensortemp >= temp + temphyston: #Kühlung ein                               
                                gpio.output(PIN_COOL, RELAY_ON);
                               
                        if sensortemp <= temp + temphystoff:
                                gpio.output(PIN_COOL, RELAY_OFF);
                                



                        if sensortemp <= temp - temphyston: #Heizung ein                                
                                gpio.output(PIN_HEATER, RELAY_ON);

                        if sensortemp >= temp - temphystoff:#Heizung aus
                                gpio.output(PIN_HEATER, RELAY_OFF);
                                
                                

                  
                        if sensorhum <= hum - humhyston: #Luftbefeuchter ein
                                count = count+1
                                if count >= humdelay: 
                                        gpio.output(PIN_HUM, RELAY_ON);
                                
                        if sensorhum >= hum - humhystoff: # Luftbefeuchter aus
                                gpio.output(PIN_HUM, RELAY_OFF);
                                count = 0;



                if mod == 4: #Automodus erweitert


                        #Temperaturreglung
                        if sensortemp >= temp + temphyston: #Kühlung ein                               
                                gpio.output(PIN_COOL, RELAY_ON);
                               
                        if sensortemp <= temp + temphystoff: #Kühlung aus
                                gpio.output(PIN_COOL, RELAY_OFF);
                                



                        if sensortemp <= temp - temphyston: #Heizung ein                                
                                gpio.output(PIN_HEATER, RELAY_ON);

                        if sensortemp >= temp - temphystoff:#Heizung aus
                                gpio.output(PIN_HEATER, RELAY_OFF);



                                
                        #Luftfeuctigkeitsreglung        

                        if sensorhum <= hum - humhyston: #Luftbefeuchter ein
                                count = count+1
                                if count >= humdelay: 
                                        gpio.output(PIN_HUM, RELAY_ON);
                                
                        if sensorhum >= hum - humhystoff: # Luftbefeuchter aus
                                gpio.output(PIN_HUM, RELAY_OFF);
                                count = 0;

                        if sensorhum >= hum + humhyston: #Luftaustausch ein
                                #gpio.output(PIN_FAN1, RELAY_ON)
                                evac = False
                        if sensorhum <= hum + humhystoff: #Luftaustausch aus
                                evac = True
                                #gpio.output(PIN_FAN1, RELAY_OFF)

                        
                #Einschaltung und Abschaltung der Be und Entlüftung
                #Umluft
                if gpio.input(PIN_HEATER) or gpio.input(PIN_COOL) or gpio.input(PIN_HUM) or vent == False:
                        gpio.output(PIN_FAN, RELAY_ON)
                if gpio.input(PIN_HEATER) and gpio.input(PIN_COOL) and gpio.input(PIN_HUM) and vent == True:
                        gpio.output(PIN_FAN, RELAY_OFF);

                #Luftaustausch
                if vent1 ==False or evac == False:
                        gpio.output(PIN_FAN1, RELAY_ON);
                        #print('umluft ein.........')
                if evac and vent1 == True:
                        gpio.output(PIN_FAN1, RELAY_OFF)
                        #print('Umluft aus.........')

                        
                        
                if gpio.input(PIN_HEATER) == False:
                        writeVerbose('Heizung ein');
                        heat = 10
                else:       
                        writeVerbose('Heizung aus');
                        heat = 0

                if gpio.input(PIN_COOL) == False:
                        writeVerbose('Kühlung ein');
                        cool = 10
                else:       
                        writeVerbose('Kühlung aus');
                        cool = 0

                if gpio.input(PIN_HUM) == False:
                        writeVerbose('Luftbefeuchter ein');
                        lbf = 10
                else:       
                        writeVerbose('Luftbefeuchter aus');
                        lbf = 0;

                if gpio.input(PIN_FAN) == False:
                        writeVerbose('Umluft ein');
                        uml = 10
                else:       
                        writeVerbose('Umluft aus');
                        uml = 0

                if gpio.input(PIN_FAN1) == False:
                        writeVerbose('Luftaustausch ein');
                        lat = 10
                else:       
                        writeVerbose('Luftaustausch aus');
                        lat = 0
                #print ('evac')+str (evac);
                #print ('vent1')+str (vent1);
               
                                
               # Messwerte in die RRD-Datei schreiben
                from rrdtool import update as rrd_update
                ret = rrd_update('%s' %(filename), 'N:%s:%s:%s:%s:%s:%s:%s' %(sensortemp, sensorhum, lat, uml, heat, cool, lbf));
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
                        
                        z = 0;
                else:
                        z = z+1


                time.sleep(1)  
		# Mainloop fertig 
		writeVerbose('Loop complete.');
		time.sleep(3);



# Hauptprogramm #################################################################################################################
########################################################################################################################

os.system('clear'); # Bildschirm löschen
writeVerbose('************************************************************');
setupGPIO(); # GPIO initialisieren

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
writeVerbose('************************************************************');   

settings = readSettings();       
tempstart = int(time.time())
tempstart1 = tempstart



try:
	doMainLoop();

except KeyboardInterrupt:
	pass;

except Exception, e:
	writeVerbose('Exception occurred!!!', True);
	writeVerbose(str(e), True);
	pass;

goodbye();

