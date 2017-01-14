#! /usr/bin/python
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


#Standard werte
temphyston = float(1);
temphystoff = float(0.5)
humhyston = float(5);
humhystoff = float(2);
humdelay = 5;
mod = 4;
tempoff1 = 82800;#Luftaustausch in Stunden ein
tempon1 = 3600; #Luftaustausch in Minuten ein:

z=120 #180; #Aufheizzeit 3 stunden
t=86400 ; #Zeit 86400 für einen Tag

#Funktionen##############################################################################################################
#########################################################################################################################



def writeVerbose(s, newLine=False):
	global VERBOSE;
	
	if(VERBOSE):
		print(s);
		if(newLine is True):
			print('');


def write_settings(mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay):
	global SETTINGS_FILE;	

	s = json.dumps({"mod":mod, "temp":temp, "hum":hum, "tempoff":tempoff, "tempon":tempon, "tempoff1":tempoff1, "tempon1":tempon1, "temphyston":temphyston, "temphystoff":temphystoff, "humhyston":humhyston, "humhystoff":humhystoff, "humdelay":humdelay, 'date':int(time.time())});
	with open(SETTINGS_FILE, 'w') as file:
		file.write(s);



# Hauptprogramm #################################################################################################################
########################################################################################################################

#os.system('clear'); # Bildschirm löschen
#Aufheizen

temp = 21;
hum = 93;
tempoff = 3600;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);



while z > 0:
        writeVerbose('************************************************************');
        writeVerbose('**************************Aufheizen*************************');
        writeVerbose('************************************************************');
        print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
        print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
        print ('Bitte Warten, es wird aufgeheizt, Restzeit in Minuten:')+str (z);
        time.sleep(60);
        z = z-1;
        

                                           


#Tag1

temp = 21;
hum = 93;
tempoff = 3600;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 1***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t);

#Tag2

temp = 20;
hum = 93;
tempoff = 3600;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 2***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t);


#Tag3

temp = 19;
hum = 92;
tempoff = 3600;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 3***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t);


#Tag4

temp = 19;
hum = 92;
tempoff = 3600;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 4***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t);



#Tag5

temp = 18;
hum = 91;
tempoff = 3600;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff,humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 5***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t);



#Tag6

temp = 18;
hum = 91;
tempoff = 3600;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 6***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t);


#Tag7

temp = 17;
hum = 90;
tempoff = 3600;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 7***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t);


#Tag8

temp = 17;
hum = 90;
tempoff = 5400;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 8***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t);



#Tag9

temp = 16;
hum = 89;
tempoff = 5400;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 9***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t);



#Tag10

temp = 16;
hum = 89;
tempoff = 5400;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 10***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t);

#Tag11

temp = 15;
hum = 88;
tempoff = 5400;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 11***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t);



#Tag12

temp = 15;
hum = 87;
tempoff = 5400;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 12***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t);



#Tag13

temp = 14;
hum = 86;
tempoff = 5400;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 13***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t);



#Tag14

temp = 13;
hum = 85;
tempoff = 5400;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 14***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t);

#Tag 15 bis 21

temp = 12;
hum = 80;
tempoff = 7200;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************TAG 15 bis 21***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);
time.sleep(t*7);


#Restreifung

temp = 12;
hum = 75;
tempoff = 10800;  #Luftumwelzung auszeit
tempon = 900; #Luftumwelzung einschaltzeit
#tempoff1 = 21600;#Luftaustausch in Stunden ein
#tempon1 = 900; #Luftaustausch in Minuten ein:
write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
writeVerbose('************************************************************');
writeVerbose('****************************Restreifung***************************');
writeVerbose('************************************************************');
print ('Eingestellter Temperaturwert in Celcius :')+str (temp);
print ('Eingestellter Luftfeuchtigwert in % :')+str (hum);
print ('Eingestellte Luftumwelzung Aus')+str (tempoff);
print ('Eingestellte Luftumwelzung Ein')+str (tempon);
print ('Eingestellter Luftaustausch Aus')+str (tempoff1);
print ('Eingestellter Luftaustausch Ein')+str (tempon1);



