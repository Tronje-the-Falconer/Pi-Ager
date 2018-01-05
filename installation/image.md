---
layout: installation
title: "Update"
---

Das Image müssen wir unter [Releases](https://github.com/Tronje-the-Falconer/Reifeschrank/releases) downloaden und auf die SD-Karte schreiben. Dafür laden wir uns das Programm [Win 32 Disk Imager von Sourceforge](http://sourceforge.net/projects/win32diskimager/) herunter und installieren dieses.

Jetzt legen wir eine mind. 2GB große SD-Karte in einen Kartenleser ein und wartet bis Windows diese erkannt hat.
 
Danach starten wir den »Win32DiskImager.exe« und wählen das Image [Image File] und den Laufwerksbuchstaben [Device] der Speicherkarte aus.
 
![Win32DiskManager GUI](https://camo.githubusercontent.com/f8720a4f5c9b32cb374ec2a64200d1cf07cf0d5b/68747470733a2f2f736f75726365666f7267652e6e65742f702f77696e33326469736b696d616765722f73637265656e73686f742f77696e33322d696d6167657772697465722e706e67)
 
Den Schreibvorgang starten wir mit einem Klick auf [Write] --> ACHTUNG nicht das falsche Laufwerk!

Wenn der Vorgang abgeschlossen ist öffnen wir den Windows Dateiexplorer und gehen auf die die SD-Karte. Hier editieren wir die Datei setup.txt im Ordner /root mit einem texteditor. (Empfehlung [Notepad++](https://notepad-plus-plus.org), da hier Zeilenumbrüche und Dateikodierung berücksichtigt werden)

und geben bei den Parametern hinter dem =-Zeichen die gewünschten Daten an. (Sofern nichts angegeben wird sind die Passwörter standardmäßig raspberry) :

Hostname, wie soll der RaspberryPi im Netzwerk heißen:

    piname=

Passwort für Benutzer pi:

    pipass=

Passwort für Benutzer root:

    rootpass=

Passwort für Benutzer reifeschrank auf der Webiste Settings:

    webguipw=

WLAN Netzwerkname:

    wlanssid=

WLAN Schluessel:

    wlankey=

setup.txt nach boot loeschen? leer lassen für Löschen oder 1 für Behalten

     keepconf=


Die Änderungen speichern wir und werfen die Speicherkarte in Windows aus.

Jetzt stecken wir die Speicherkarte in den Raspberry PI, schließen das Netzteil, den USB-WLan-Stick, den Sensor und das Relaisboard an.

Danach wird das USB Netzteil an den Strom angeschlossen.

Der Raspberry Pi startet nun und nach einer Weile sollte die Webiste http://IPADRESSE/index.php erreichbar sein.
