---
layout: installation
title: "Installation per Image"
---
# Imageinstallation

Diese Anleitung basiert auf Windows. Im Falle eines anderen Betriebssystems bedarf es z.B. beim Schreiben des Images anderer Programme und evtl. andere Vorgehensweise.

Im Falle eines andseren Betriebssystems, reicht es nicht aus, Windows als virtuelle Maschine zu benutzen. Hierbei haben wir die Erfahrung gemacht, dass die fertige SD-Karte nicht sauber im raspberry Pi startet.

Das Image muss unter [Releases](https://github.com/Tronje-the-Falconer/Pi-Ager/releases) downloaden und auf die SD-Karte schreiben.

Dafür läd man sich das Programm [Win 32 Disk Imager von Sourceforge](http://sourceforge.net/projects/win32diskimager/) oder [balena etcher](https://www.balena.io/etcher/) herunter und installiert dieses.

Jetzt legt man eine mind. 8GB große SD-Karte in einen Kartenleser ein und wartet bis Windows diese erkannt hat.
 
Danach startet man den »Win32DiskImager.exe« und wählt das Image [Image File] und den Laufwerksbuchstaben [Device] der Speicherkarte aus.
 
![Win32DiskManager GUI](https://elinux.org/images/4/41/Win_sel.png)
 
Den Schreibvorgang startet man mit einem Klick auf [Write] --> ACHTUNG nicht das falsche Laufwerk!

Wenn der Vorgang abgeschlossen ist öffnet man den Windows Dateiexplorer und geht auf die SD-Karte. Hier editiert man die Datei setup.txt im Ordner /root mit einem Texteditor. (Empfehlung [Notepad++](https://notepad-plus-plus.org), da hier Zeilenumbrüche und Dateikodierung berücksichtigt werden)

und gibt bei den Parametern hinter dem =-Zeichen die gewünschten Daten an. (Sofern nichts angegeben wird, sind die Passwörter standardmäßig raspberry) :


Hostname, wie soll der RaspberryPi im Netzwerk heißen:

    piname=

Passwort für Benutzer pi:

    pipass=

Passwort für Benutzer root:

    rootpass=

Passwort für Benutzer pi-ager auf der Webiste Settings, Admin und Webcam:

    webguipw=
    
Passwort für Zugriff auf Datenbankverwaltung phpliteadmin
      
    dbpw=

WLAN Netzwerkname: Sonderzeichen wie ! oder Leerzeichen muss ein Backslash '\\' vorangestelt werden:

    wlanssid=

WLAN Schluessel:

    wlankey=
    
ISO code your country, e.g. DE, GB    
    
    country=

angeschlossener interner Temperatur/Feuchtesensor Pi-Pager:
unterstüzte Typen : DHT11, DHT22, SHT75, SHT85, SHT3x

    sensor=

setup.txt nach boot loeschen? leer lassen für Löschen oder 1 für behalten

    keepconf=
     

Die Änderungen speichert man und wirft die Speicherkarte in Windows aus.

Jetzt steckt man die Speicherkarte in den Raspberry Pi. Sofern die Bauphase schon abgeschlossen ist und Platine, Relaisboard etc. fertig angeschlossen sind, kann das System mit Strom versorgt werden.

Der Raspberry Pi startet nun und nach einer Weile sollte die Webiste http://IPADRESSE/index.php erreichbar sein.
