import sys
import os;
import json;
import glob;
import time;
import datetime
import csv
from datetime import timedelta

global Reifetab

PATH = '/var/www/html';
SETTINGS_FILE = PATH+'/settings.json';
TABELS_FILE=PATH+'/tabels.json';
filename=PATH+'/logfile.txt' ;

def readSettings():
	global TABELS_FILE;
	s = None;
	with open(TABELS_FILE, 'r') as file:
		s = file.read();
	data = json.loads(s);
	return data


def write_settings(mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay):
	global SETTINGS_FILE;	

	s = json.dumps({"mod":mod, "temp":temp, "hum":hum, "tempoff":tempoff, "tempon":tempon, "tempoff1":tempoff1, "tempon1":tempon1, "temphyston":temphyston, "temphystoff":temphystoff, "humhyston":humhyston, "humhystoff":humhystoff, "humdelay":humdelay, 'date':int(time.time())});
	with open(SETTINGS_FILE, 'w') as file:
		file.write(s);

Reifetabs= readSettings();     
tabelle=Reifetabs['Reifetab']
datei =tabelle +'.csv';
target = open(filename, 'a')
target.write ("\n" +time.strftime('%d.%m.%Y  %H:%M') + ': Kontrolle der Werte von '+tabelle +' uebernommen')
target.close()
        
f=open('/opt/RSS1.0/'+datei,"rb")
reader=csv.reader(f)
rownum=0
dauer=0

for row in reader:
        colnum=0
        for col in row:
                if rownum>0:     
                        if colnum==12:                               
                                dauer=dauer + int(col)
                colnum+=1
        rownum+=1
totper=rownum-1
totdauer=dauer


f=open('/opt/RSS1.0/' + datei,"rb")
reader=csv.reader(f)

mod = 4
temp = 5
hum = 75
tempoff = 3000
tempon = 600
tempoff1 = 82800
tempon1 = 3600
temphyston = 1
temphystoff = 0.5
humhyston = 5
humhystoff = 2
humdelay = 5
dauer = 86400

t=86400 ; #Anzahl der Sek. in einem Tag
#t=1 ; #zum testen; ein Tag vergeht in einer Sekunde
rownum=0
for row  in reader:
    colnum=0  
    if rownum==0:
        header=row      
    else:
         if rownum==1 :           
             print "****************************************************************************"                 
             print time.strftime('%d.%m.%Y  %H:%M') + ": Initialwerte fur Periode 1/"+str(totper) + " vom Reifeprogramm " + tabelle +":"
             print
             target = open(filename, 'a')
             target.write("\n"+ "****************************************************************************")                 
             target.write("\n" + time.strftime('%d.%m.%Y  %H:%M') + ": Initialwerte fur Periode 1/"+str(totper) + " vom Reifeprogramm " + tabelle +":")
             target.write("\n")
             target.close()
             finaltime=datetime.datetime.now()+timedelta(days=totdauer)
         else:
             print "*****************************************************************************"
             print time.strftime('%d.%m.%Y  %H:%M') + ": Veraenderungen fur Periode " + str(rownum) + "/" + str(totper) + " vom Reifeprogramm " + tabelle +":"
             print
             target = open(filename, 'a')
             target.write("\n"+ "****************************************************************************")                 
             target.write("\n" + time.strftime('%d.%m.%Y  %H:%M')  +": Veraenderungen fur Periode " + str(rownum) + "/" + str(totper)+ " vom Reifeprogramm " + tabelle +":")
            
             target.close()
         colnum=0
         wert=0
         for col in row:
           colnumname=header[colnum]      
           if colnumname=="days":
               dauer=int(col)*t             
           if col<>"":
                if colnumname=='temphystoff':
                        temphystoff=float(col)
                else:
                       wert=float(col)          
                       exec('%s=%d') % (colnumname,wert)            
                       print '%-12s:%s' % (colnumname,wert)
                       target = open(filename, 'a')
                       target.write("\n" +colnumname + " \t" +col)
                       target.close()
           colnum+=1          
    rownum+=1
  
    write_settings (mod, temp, hum, tempoff, tempon, tempoff1, tempon1, temphyston, temphystoff, humhyston, humhystoff, humdelay);
    if rownum>1 :
      endtime=datetime.datetime.now()+timedelta(days=dauer/t)
      print
      if rownum<totper+1:
       print "Naechste Aenderung der Werte: " + endtime.strftime('%d.%m.%Y  %H:%M')
       target = open(filename, 'a')
       target.write("\n" +"Naechste Aenderung der Werte: " + endtime.strftime('%d.%m.%Y  %H:%M'))
       target.close()
      print "Programmende: " +finaltime.strftime('%d.%m.%Y  %H:%M')
      target = open(filename, 'a')
      target.write("\n" + "Programmende: " +finaltime.strftime('%d.%m.%Y  %H:%M'))
      target.close()      
      if rownum==totper+1 :
        print "Nach Programmende funktioniert der Reifeschrank weiter mit den letzten Werten"
        target = open(filename, 'a')
        target.write("\n" + tabelle +" beendet die Kontrolle."+"\n"+"Der Reifeschrank funktioniert weiter mit den letzten Werten.")
        target.close()     
      print "*****************************************************************************"
      target = open(filename, 'a')
      target.write("\n" + "*****************************************************************************")
      target.close()      
      if rownum<=totper:
       time.sleep(dauer)
      
f.close()
    
sys.exit(0)
