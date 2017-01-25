#!/usr/bin/python
# -*- coding: iso-8859-1 -*-
#Importieren der Module

import os;
import json;
import glob;
import time;

# Konstanten ############################################################################################################
########################################################################################################################

PATH = '/var/www/html';
SETTINGS_FILE = PATH+'/settings.json';
VERBOSE = True;


#Standard Hysteresewerte
temphyston = float(1.5);
temphystoff = float(0.5)
humhyston = float(5);
humhystoff = float(2);


#Funktionen##############################################################################################################
#########################################################################################################################



def writeVerbose(s, newLine=False):
	global VERBOSE;
	
	if(VERBOSE):
		print(s);
		if(newLine is True):
			print('');


def write_settings(mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay, sensortype):
	global SETTINGS_FILE;	

	s = json.dumps({"mod":mod, "temp":temp, "hum":hum, "tempoff":tempoff, "tempon":tempon, "tempoff1":tempoff1, "tempon1":tempon1, "temphyston":temphyston, "temphystoff":temphystoff, "humhyston":humhyston, "humhystoff":humhystoff,"humdelay":humdelay, 'sensortype':sensortype, 'date':int(time.time())});
	with open(SETTINGS_FILE, 'w') as file:
		file.write(s);



# Hauptprogramm #################################################################################################################
########################################################################################################################

#os.system('clear'); # Bildschirm löschen
writeVerbose('************************************************************');

                                           

#Vriablen Eingabe
mod = int(raw_input('Kühlmodus (1) Heizmodus(2) Automodus (3) Erweiteter Automodus(4):'))
temp = float(raw_input('Bitte geben sie die SollTemperatur ein (2-25):'))
hum = float(raw_input('Bitte geben sie die SollLuftfeuchtigkeit ein(40-95):'))
humdelay = int(raw_input('Bitte geben sie die Verzögerung für den Befeuchter in Minuten an :'))
writeVerbose('');
tempoff = int(raw_input('Bitte geben sie den Abstand für die Luftumwelzung in Stunden ein :'))
tempon = int(raw_input('Bitte geben sie die Einschaltzeit für die Luftumerlzung in Minuten ein:'))
writeVerbose('');
tempoff1 = int(raw_input('Bitte geben sie den Abstand für den Luftaustausch in Stunden ein :'))
tempon1 = int(raw_input('Bitte geben sie die Einschaltzeit für den Luftaustausch in Minuten ein:'))
writeVerbose('');
hystdef = int(raw_input('Wollen sie die Hysteresewerte anpassen (1)Ja (2)Nein ?'));
if hystdef == 1:
        temphyston = float(raw_input('Bitte geben sie den Hysteresewert für den Temperatureinschaltwert an:'))
        temphystoff = float(raw_input('Bitte geben sie den Hystereswert für den Temperaturausschaltwert an:'))
        writeVerbose('');
        humhyston = float(raw_input('Bitte geben sie den Hysteresewert für den Lft-einschaltwert an:'))
        humhystoff = float(raw_input('Bitte geben sie den Hysteresewert für den Lft-ausschaltwert an:'))
tempon = tempon*60
tempoff = tempoff*3600-tempon
tempon1 = tempon1*60
tempoff1 = tempoff1*3600-tempon1
sensortype = int(raw_input('DHT11 (1) DHT22(2) SHT75 (3):'))

write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay, sensortype,);
writeVerbose('************************************************************');
writeVerbose('**************Die Änderungen wurden gespeichert*************');
writeVerbose('************************************************************');


                 
