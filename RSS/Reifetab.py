#!/usr/bin/python
# -*- coding: iso-8859-1 -*-

#-----------------------------------------------------------------------------------------Importieren der Module
import sys
import os
import json
import glob
import time
import datetime
import csv
from datetime import timedelta

global Reifetab

#-----------------------------------------------------------------------------------------Pfade zu den Dateien
PATH = '/var/www'
PATH_CSV = '/var/www/csv/'
SETTINGS_FILE = PATH+'/settings.json'
TABELS_FILE=PATH+'/tabels.json'
logfilename=PATH+'/logfile.txt' 

#-----------------------------------------------------------------------------------------Function Lesen der tabels.json
def readTabels():
    global TABELS_FILE;
    s = None;
    with open(TABELS_FILE, 'r') as file:
        s = file.read();
    data = json.loads(s);
    return data
    
#-----------------------------------------------------------------------------------------Function Lesen der settings.json
def readSettings():
    global SETTINGS_FILE
    s = None
    with open(SETTINGS_FILE, 'r') as file:
        s = file.read()
    data = json.loads(s)
    return data

#-----------------------------------------------------------------------------------------Function Schreiben der settings.json
def write_settings(mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay, sensortype):
    global SETTINGS_FILE

    s = json.dumps({"mod":mod, "temp":temp, "hum":hum, "tempoff":tempoff, "tempon":tempon, "tempoff1":tempoff1, "tempon1":tempon1, "temphyston":temphyston, "temphystoff":temphystoff, "humhyston":humhyston, "humhystoff":humhystoff, "humdelay":humdelay, 'date':int(time.time()), 'sensortype':sensortype})
    with open(SETTINGS_FILE, 'w') as file:
        file.write(s)

#----------------------- Sensor auslesen und variablen füllen
settings = readSettings()
sensortype = settings ['sensortype']
if sensortype == 1 :
    sensortype_txt = '1'
    sensorname='DHT11'
if sensortype == 2 :
    sensortype_txt = '2'
    sensorname='DHT22'
if sensortype == 3 :
    sensortype_txt = '3'
    sensorname='SHT75'
#-----------------------------------------------------------------------------------------Lesen der tabels.json, schreiben in logfile.txt
Reifetabs= readTabels()                # Function-Aufruf
tabelle=Reifetabs['Reifetab']          # Variable tabelle = Name der Reifetabelle
datei =tabelle +'.csv'                 # Variable datei = kompletter Dateiname
target = open(logfilename, 'a')           # Variable target = logfile.txt öffnen
target.write("\n"+ "***********************************************************************")
target.write ("\n"' Die Klima-Werte werden nun vom automatischen Programm "'+ tabelle +'" gesteuert')   # Schreibt in die logfile.txt
target.close()
print 'Die Klima-Werte werden nun vom automatischen Programm "'+ tabelle +'" gesteuert' # Schreibt in die Konsole
#-----------------------------------------------------------------------------------------Lesen der gesammten Programm-Dauer und der Anzahl Perioden in der gewaehlten CSV-Datei
f=open(PATH_CSV+datei,"rb")               # Variable f = csv-Datei oeffnen
reader=csv.reader(f)                      # reader-Objekt liest csv ein
rownum=0                                  # Setzt Variable rownum auf 0
dauer=0                                   # Setzt Variable dauer auf 0

for row in reader:                        # Durchlaeuft die einzelnen Reihen
    colnum=0                              # Setzt Variable colnum auf 0
    for col in row:                       # Durchlaeuft die einzelnen Spalten
        if rownum>0:                      # ... Header-Reihe ausschliessen
            if colnum==12:                # ... wenn in Spalte 12 angekommen [days]
                dauer=dauer + int(col)    # Addiere die Anzahl der Tage aus "col" zu "dauer" dazu und macht integer daraus
        colnum+=1                         # Zaehler fuer die Anzahl der Spalten
    rownum+=1                             # Zaehler fuer die Anzahl der Reihen
totalperiod=rownum-1                           # Variable totalperiod = Anzahl der Perioden, der Reifephasen (entspricht der Anzahl an Reihen)
totaldauer=dauer                            # Variable totaldauer = Gesamtdauer aller Perioden
#-----------------------------------------------------------------------------------------Lesen der Werte aus der CSV-Datei & Schreiben der Werte in die Konsole und das Logfile
#t=86400  #Anzahl der Sek. in einem Tag
t=1  #zum testen ein Tag vergeht in einer Sekunde


rownum=0                                     # Setzt Variable rownum auf 0
f=open(PATH_CSV+datei,"rb")                  # Variable f = csv-Datei oeffnen
reader=csv.reader(f)                         # reader-Objekt liest csv ein
for row  in reader:                          # Durchlaeuft die einzelnen Reihen
    colnum = 0                               # Setzt Variable colnum (zurück) auf 0
    if rownum == 0:                          # Ermittelt die Kopfspalte [0]
        header=row                           # ... und speichert die Werte in der Variable header
        # print 'DEBUG Header ausgelesen'
    else:
        # print 'DEBUG beginne Zeilen auszulesen'
        if rownum == 1 :                     # Ermittelt die erste Periode/den Beginn des Programms
            # Schreibt in die Konsole
            # print 'DEBUG Bin in der ersten Zeile mit Werten'
            print time.strftime('%d.%m.%Y - %H:%M Uhr') + ": Startwerte Periode 1 von "+str(totalperiod)
            print
            # Schreibt in die logfile.txt
            target = open(logfilename, 'a')
            target.write("\n" + time.strftime('%d.%m.%Y - %H:%M Uhr') + ": Startwerte Periode 1 von "+ str(totalperiod))
            target.write("\n")
            target.close()
            # ...
            finaltime=datetime.datetime.now()+timedelta(days=totaldauer)
        else:                                              # Ermittelt alle anderen Perioden
            # print 'DEBUG bin nach der ersten Zeile mit Werten'
            # Schreibt in die Konsole
            print time.strftime('%d.%m.%Y - %H:%M Uhr') + ": Neue Werte für Periode " + str(rownum) + " von " + str(totalperiod)
            print
            # Schreibt in die logfile.txt
            target = open(logfilename, 'a')
            target.write("\n" + time.strftime('%d.%m.%Y - %H:%M Uhr')  +": Neue Werte für Periode " + str(rownum) + " von " + str(totalperiod))
            target.write("\n")
            target.close()
        #colnum = 0                                        # Setzt Variable colnum (zurück) auf 0
        wert = 0                                           # Setzt Variable wert auf 0
        for col in row:                                    # Durchlaeuft die einzelnen Spalten
            # print 'DEBUG in for col in row:'
            colnumname = header[colnum]                    # colnumname bekommt den Wert aus dem header-Array von der Position, wie der Wert von colnum gerade ist
            if colnumname == "days":                       # ... oder auch: wenn colnum == 12 ist ...
                # print 'DEBUG Colnum = Days'
                dauer = int(col)*t                         # Anzahl der Tage von "col" mit 86400 (Sekunden) multipliziert
            if colnumname != "":                           # Wenn col nicht "" ist...
                if col == "":
                    setting = readSettings()
                    col=settings[''+ colnumname + '']
                    wert = float(col)
                    col= str(col)
                    exec('%s = %d') % (colnumname,wert)    # füllt die jeweilige Variable mit altem Wert (wert = columname) 
                else:
                    wert=float(col)                        # formatiere col zu "float" und setze es in "wert" ein
                    exec('%s = %d') % (colnumname,wert)    # füllt die jeweilige Variable (columname = wert)
                print '%-12s:%s' % (colnumname,wert)       # Schreibt in die Konsole

                # Aufbereitung für die Lesbarkeit im Logfile und Füllen der Variablen
                if colnumname == 'mod':
                    mod = int(mod+0.5) # Rundet auf Ganzzahl, Integer da der Modus immer Integer sein sollte
                    if mod == 1:
                        betriebsart="\n" 'Betriebsart: Kühlen mit Befeuchtung'
                    elif mod == 2:
                        betriebsart="\n" 'Betriebsart: Heizen mit Befeuchtung'
                    elif mod == 3:
                        betriebsart="\n" 'Betriebsart: Automatik mit Befeuchtung'
                    elif mod == 4:
                        betriebsart="\n" 'Betriebsart: Automatik mit Be- und Entfeuchtung'
                    else:
                        betriebsart="\n" 'Betriebsart falsch oder nicht gesetzt'
                if colnumname=='temp':
                    solltemperatur="\n" 'Soll-Temperatur:' " \t" + col + "°C"
                if colnumname=='temphyston':
                    einschaltwerttemperatur="\n" 'Einschaltwert Temperatur:' " \t" + col + "°C"
                if colnumname=='temphystoff':
                    ausschaltwerttemperatur="\n" 'Ausschaltwert Temperatur:' " \t" + col + "°C"
                if colnumname=='hum':
                    sollfeuchtigkeit="\n" 'Soll-Feuchtigkeit:' " \t" + col + "%"
                if colnumname=='humhyston':
                    einschaltwertfeuchte="\n" 'Einschaltwert Feuchte:' " \t" + col + "%"
                if colnumname=='humhystoff':
                    ausschaltwertfeuchte="\n" 'Ausschaltwert Feuchte:' " \t" + col + "%"
                if colnumname=='humdelay':
                    befeuchtungsverzoegerung="\n" 'Befeuchtungsverzögerung:' " \t" + col + "min"
                if colnumname=='tempoff':
                    col_form=int(col)/60
                    timerumluftperiode="\n" 'Timer Umluftperiode: alle' " \t" + str(col_form) + "min"
                if colnumname=='tempon':
                    col_form=int(col)/60
                    timerumluftdauer="\n" 'Timer Umluftdauer:' " \t" + str(col_form) + "min"
                if colnumname=='tempoff1':
                    col_form=int(col)/60
                    timerabluftperiode="\n" 'Timer Abluftperiode alle:' " \t" + str(col_form) + "min"
                if colnumname=='tempon1':
                    col_form=int(col)/60
                    timerabluftdauer="\n" 'Timer Abluftdauer:' " \t" + str(col_form) + "min"
                if colnumname=='days':
                    tage="\n" 'Dauer:' " \t" + col + " Tage"
            colnum+=1                                     # Zaehler fuer die Anzahl der Spalten
        # Sensortyp für Log vorbereiten
        sensorlog = 'Sensortyp: ' + sensorname + ' Value: ' + str(sensortype)
        # print 'DEBUG schreibe in Logfile'
        target = open(logfilename, 'a')                     # Schreibt in die logfile.txt
        target.write(betriebsart)
        target.write(solltemperatur)
        target.write(einschaltwerttemperatur)
        target.write(ausschaltwerttemperatur)
        target.write("\n")
        target.write(sollfeuchtigkeit)
        target.write(einschaltwertfeuchte)
        target.write(ausschaltwertfeuchte)
        target.write(befeuchtungsverzoegerung)
        target.write("\n")
        target.write(timerumluftperiode)
        target.write(timerumluftdauer)
        target.write("\n")
        target.write(timerabluftperiode)
        target.write(timerabluftdauer)
        target.write("\n")
        target.write(tage)
        target.write("\n")
        target.write(sensorlog)
        target.write("\n" '---------------------------------------')
        target.close()
#-----------------------------------------------------------------------------------------Function write_settings() Schreiben der Werte in die settings.json
        # print 'DEBUG schreibe settings.json'
        write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay, sensortype)

    #if rownum>1 :
        endtime=datetime.datetime.now()+timedelta(days=dauer/t)
        # Schreibt in die Konsole
        print
        if rownum<totalperiod+1:
            print "Nächste Änderung der Werte: " + endtime.strftime('%d.%m.%Y  %H:%M')
            # Schreibt in die logfile.txt
            target = open(logfilename, 'a')
            target.write("\n" +"Nächste Änderung der Werte: " + endtime.strftime('%d.%m.%Y  %H:%M'))
            target.close()
        # Schreibt in die Konsole
        print "Programmende: " +finaltime.strftime('%d.%m.%Y  %H:%M')
        # Schreibt in die logfile.txt
        target = open(logfilename, 'a')
        target.write("\n" + "Programmende: " +finaltime.strftime('%d.%m.%Y  %H:%M'))
        target.close()
        if rownum==totalperiod+1 :
            print "Nach Programmende funktioniert der Reifeschrank weiter mit den letzten Werten"
            # Schreibt in die logfile.txt
            target = open(logfilename, 'a')
            target.write("\n" + tabelle +" beendet die Kontrolle."+"\n"+"Der Reifeschrank funktioniert weiter mit den letzten Werten.")
            target.close()
        print "***********************************************************************"
        # Schreibt in die logfile.txt
        target = open(logfilename, 'a')
        target.write("\n" + "***********************************************************************")
        target.close()
        if rownum<=totalperiod:
            time.sleep(dauer)       # Wartezeit bis zur nächsten Periode
    rownum+=1                                            # Zaehler fuer die Anzahl der Reihen
f.close()
sys.exit(0)
