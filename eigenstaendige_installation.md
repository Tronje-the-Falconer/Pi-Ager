#Inhalt

* [Betriebssystem RASPBIAN JESSIE LITE](#betriebssystem-raspian-jessie-lite)
* [WiFi-Verbindung](#wifi-verbindung)
* [Softwarepakete](#softwarepakete)
    * [Apache Server](#apache-server)
    * [PHP 5](#php-5)
    * [RRD-Tool](#rrd-tool)
    * [pip](#pip)
    * [Sht-Sensor](#sht-sensor)
    * [Adafruit_DHT](#adafruit_dht)
    * [Wiring Pi](#wiring-pi)
* [Programm Reifeschranksteuerung](#programm-reifeschranksteuerung)


# Betriebssystem RASPBIAN JESSIE LITE

Download [RASPBIAN JESSIE LITE](https://www.raspberrypi.org/downloads/raspbian/)

Das Image muss nun auf die SD-Karte geschrieben werden.

Dafür laden wir uns das Programm [Win 32 Disk Imager von Sourceforge](http://sourceforge.net/projects/win32diskimager/) herunter und installieren dieses.

Entpacken beide Downloads

Legen mind. eine 8GB SD Karte in einen Kartenleser ein und wartet bis Windows diese erkannt hat.

Danach starten wir den »Win32DiskImager.exe« und wählen das Image [_Image File_] und den Laufwerksbuchstaben [_Device_] der Speicherkarte aus

![Win32DiskManager GUI](https://sourceforge.net/p/win32diskimager/screenshot/win32-imagewriter.png)

und starten den Schreibvorgang mit einem Klick auf [_Write_] --> ACHTUNG nicht das falsche Laufwerk!

Wenn der Vorgang abgeschlossen ist, werfen wir die Speicherkarte in Windows aus.

Die Speicherkarte stecken wir in den Raspberry PI und schließen eine USB Tastatur, einen HDMI Monitor (Fernseher) und ein mind. 1A starkes Micro USB Netzteil an. Sofern der Raspberry PI einen Ethernetanschluss hat, schließen wir zudem ein Netzwerk-Kabel an. 

Danach wird das USB Netzteil an den Strom angeschlossen.

Der Raspberry Pi startet nun und auf dem Monitor sollte der Vorgang sichtbar sein.

Einloggen mit (Achtung! Tastatur noch falsch konfiguriert [z=y]):

> Benutzername: pi

> Passwort: raspberry

Danach führen wir den Assistenten aus

    sudo raspi-config (Achtung! Tastatur noch falsch konfiguriert [-=ß])

und richten folgende Einstellungen ein (die einzelnen Punkt können ggf. unter anderen Nummern stehen):

    7 Advanced Options
        A0 Update (sofern wir keinen Ethernetanschluss zur Verfügung haben, fällt dieser Punkt weg bzw. kann zu einem späteren Zeitpunkt nachgeholt werden)
    1 Expand Filesystem
    2 Change User Password
    4 Internationalisation Options
        I1 Standard Locale »de_DE.UTF-8 UTF-8« hinzufügen und als Standard auswählen
        I2 Als Zeitzone »Europe / Berlin« auswählen
        I3 Als Tastatur "Generic 105-key (Intel) PC" bestätigen und als Sprache "other" / "German" auswählen, alle anderen Optionen auf Standardwerten belassen
        I4 DE als WiFi auswählen
    5 Interface Options
        P2 SSH »enable«
        P6 Serial »disable« (Also auf NEIN klicken!!)
    9 Advanced Options
        A2 Hostname ändern auf z.B. »rpi-Reifeschrank«
        A3 Memory Split auf »8« damit mehr RAM für die Ausführung der Dienste zur Verfügung steht
    Finish

Danach sollten wir gefragt werden, ob wir neu starten wollen. Dies beantworten wir mit Ja.

Sollte die Frage nicht gestellt werden müssen wir den Raspberry manuell starten.

    sudo sync
    sudo reboot

Sofern kein Ethernetanschluss gegeben ist, aber ein WLAN-Stick zur Verfügung steht, sollte jetzt Punkt [WiFi-Verbindung](#wifi-verbindung) vorgeszogen werden.

Ab jetzt ist es möglich auch mittels PC und Zusatzprogramm wie z.B. [Putty](http://www.putty.org/) auf den Raspberry PI zuzugreifen. Dafür müssen wir in unserem Router nachsehen, welche IP vergeben wurde. Mitels dieser können wir uns dann per Putty verbinden. Der Port ist 22

Sobald wir uns wieder eingeloggt haben, machen wir ein Update (Sofern kein LAN-Kabel angeschlossen ist, oder kann (Bsp.Rapberry PI zero zuerst die Anleitung [WiFi-Verbindung](#wifi-verbindung) weiter unten befolgen!)

    sudo apt-get update && sudo apt-get upgrade -y && sudo apt-get dist-upgrade

Jetzt aktivieren wir den "root" User:

    sudo passwd

Und falls wir uns mittels SSH als root einloggen wollen, dann müssen wir die config noch anpassen:

    sudo nano /etc/ssh/sshd_config

Hier suchen wir nach folgender Zeile:

    #Authentication:
    LoginGraceTime 120
    PermitRootLogin without-password
    StrictMode yes

und ändert diese wie folgt ab

    #Authentication:
    LoginGraceTime 120
    PermitRootLogin yes
    StrictMode yes

Jetzt Speichern wir mit "_STRG+o_", "_RETURN_" und schließen mit "_STRG+x_"

Einmal noch neu starten

    sudo sync
    sudo reboot

[nach oben](#inhalt)

# WiFi-Verbindung

Den USB-WIFI-Stick nur anstecken, wenn der PI ausgeschaltet ist oder wir einen aktiven USB HUB verwenden --> sonst startet der Raspberry PI durch den Spannungseinbruch unschön von selbst...

Wenn der USB-WIFI-Stick angesteckt und der PI hochgefahren ist, geben wir folgendes ein um zu sehen, ob er als USB-Device  erkannt wurde:

    lsusb

Es sollte dann in etwa dieses angezeigt werden:

    Bus 001 Device 002: ID 0424:9512 Standard Microsystems Corp.
    Bus 001 Device 001: ID 1d6b:0002 Linux Foundation 2.0 root hub
    Bus 001 Device 003: ID 0424:ec00 Standard Microsystems Corp.
    Bus 001 Device 004: ID 046a:0023 Cherry GmbH CyMotion Master Linux Keyboard
    Bus 001 Device 005: ID 0bda:8176 Realtek Semiconductor Corp. RTL8188CUS 802.11n WLAN Adapter (o.ä.)

Danach testen wir ob der Stick auch als USB-WIFI-Stick erkannt wurde:

    iwconfig wlan0

Es sollte in etwa so aussehen:

    wlan0     unassociated  Nickname:"<WIFI@REALTEK>"
              Mode:Managed  Frequency=2.412 GHz  Access Point: Not-Associated
              Sensitivity:0/0
              Retry:off   RTS thr:off   Fragment thr:off
              Power Management:off
              Link Quality:0  Signal level:0  Noise level:0
              Rx invalid nwid:0  Rx invalid crypt:0  Rx invalid frag:0
              Tx excessive retries:0  Invalid misc:0   Missed beacon:0


Mit folgendem Befehl können wir die verfügbaren Netzwerke auflisten:

    iwlist wlan0 scanning

Zum Schreiben des WLAN Keys sind höhere Rechte notwendig (root)

    sudo su -

Nun geben wir folgendes ein und passen die ESSID und die PASSPHRASE für unser WLAN an. Damit auch Leerzeichen in der ESSID oder Passwort erkannt werden, müssen diese mit " maskiert werden. (bsp. "MEINE ESSID MIT LEERZEICHEN" "MEINE PASSPHRASE MIT LEERZEICHEN")

    wpa_passphrase "ESSID" "PASSPHRASE" >> /etc/wpa_supplicant/wpa_supplicant.conf

mit [_STRG_] + [_D_] kehren wir wieder zum Benutzer PI zurück. Nun können wir uns ansehen, ob das WLan auch eingetragen wurde :

    sudo nano /etc/wpa_supplicant/wpa_supplicant.conf

Ergebnis in etwa so:

    ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev
    update_config=1
    network={
            ssid="ESIID"
            #psk="BlaBla-Echter Key"
            psk=lksdfj09o4pokpofdgkpß0jppkspdfkpsß09i4popok
    }

Sofern in der Datei Konfigurationen stehen, die sicher nicht benötigt werden, können wir diese herauslöschen und mittels "_STRG+o_", "_RETURN_" und schließen mit "_STRG+x_"

Nun aktivieren wir die WLAN-Konfiguration und sehen, ob es geklappt hat:

    sudo ifdown wlan0
    sudo ifup wlan0

Eingabe

    iwconfig wlan0

Ergebnis in etwa:

    wlan0     IEEE 802.11bgn  ESSID:"PK-NEW"  Nickname:"<WIFI@REALTEK>"
              Mode:Managed  Frequency:2.412 GHz  Access Point: DC:9F:DB:FD:E7:A0
              Bit Rate:150 Mb/s   Sensitivity:0/0
              Retry:off   RTS thr:off   Fragment thr:off
              Power Management:off
              Link Quality=83/100  Signal level=50/100  Noise level=0/100
              Rx invalid nwid:0  Rx invalid crypt:0  Rx invalid frag:0
              Tx excessive retries:0  Invalid misc:0   Missed beacon:0

Eingabe:

    ifconfig wlan0

Ergebnis in etwa:

    wlan0     Link encap:Ethernet  Hardware Adresse 64:70:02:23:ef:11
              inet Adresse:192.168.0.52  Bcast:192.168.200.255  Maske:255.255.255.0
              UP BROADCAST RUNNING MULTICAST  MTU:1500  Metrik:1
              RX packets:10 errors:0 dropped:17 overruns:0 frame:0
              TX packets:4 errors:0 dropped:0 overruns:0 carrier:0
              Kollisionen:0 SendewarteschlangenlÃ¤nge:1000
              RX bytes:2001 (1.9 KiB)  TX bytes:1036 (1.0 KiB)

Wenn wir eine IP aus eurem DHCP Bereich seht, haben wir es geschafft und können uns ab sofort auch über WLAN verbinden.

[nach oben](#inhalt)

# Softwarepakete

## Apache Server
Zuerst aklualisieren wir die Pakete, da ohne dem die Installation fehlschlagen wird.

    sudo apt-get update
    
Jetzt installieren wir Apache

    sudo apt-get install apache2

Die Frage ob wir das wirklich wollen, beantworten wir mit ja.

Damit wir auch bei anderen Betriebsversionen die Funktionalität gegeben ist, müssen wir das DocumentRoot-Verzeichnis für Apache umstellen. Standardmäßig ist der neue Pfad /var/www/html/, dies ändern wir auf /var/www/ (Früher war das so). Dazu editieren wir die Konfigurationsdatei 000-default.conf in /etc/apache2/sites-available/

    sudo nano /etc/apache2/sites-available/000-default.conf

und ändern den Parameter

    DocumentRoot /var/www/html/

nach 

    DocumentRoot /var/www/

und speichern dies mittels "_STRG+o_", "_RETURN_" und schließen mit "_STRG+x_"

nun müssen wir noch aktivieren, das es .htaccess-Dateien geben darf, die Passwortauthentifizierung ermöglichen. Dazu öffnen wir die apache2.conf

    sudo nano /etc/apache2/apache2.conf

und ändern die Zeile

    <Directory /var/www/>
	Options Indexes FollowSymLinks
        AllowOverride none
	Require all granted
    </Directory>

in

    <Directory /var/www/>
	Options Indexes FollowSymLinks
        AllowOverride AuthConfig
	Require all granted
    </Directory>

speichern dies mittels "_STRG+o_", "_RETURN_" und schließen mit "_STRG+x_"
und starten den Webserbver neu

    sudo /etc/init.d/apache2 restartsudo /etc/init.d/apache2 restart

Jetzt testen wir die Installation

Dazu geben wir in unserem Browser die IP Adresse unseres Raspberry PI ein. Folgendes solltet wir sehen:

![Apachestart](https://www.howtoforge.com/images/install-apache-with-php-and-mysql-on-debian-jessie/big/apache_default_index.png)    

Zur Info: Unsere Website finden wir derzeit in folgendem Verzeichnis /var/www/.

[nach oben](#inhalt)

## PHP 5

Jetzt installieren wir PHP5

    sudo apt-get install php5

Die Frage ob wir das wirklich wollen beantworten wir mit ja.

Jetzt testen wir die Installation, indem wir uns die phpinfo anzeigen lassen. Dazu erstellen wir, wie folgt, eine Datei auf dem Server:

Als erstes wechseln wir in das Verzeichnis /var/www

    cd /var/www

Nun erstellen wir die Datei phpinfo.php

    sudo nano phpinfo.php

Mit dem Nano Editor schreiben wir nun folgenden Text in die Datei 

    <?php
    phpinfo();
    ?>

und speichern dies mittels "_STRG+o_", "_RETURN_" und schließen mit "_STRG+x_"

Jetzt geben wir in unserem Browser die IP Adresse + /phpinfo.php ein.

und sehen in etwa dies:
![phpinfo](http://www.php-kurs.com/bilder/phpinfo-localhost.png)

Somit ist PHP erfolgreich auf unserem Raspberry PI installiert

[nach oben](#inhalt)


## RRD-Tool

Jetzt installieren wir das RRDtool für die  und  das Python Interface für RRDtool

    sudo apt-get install rrdtool python-rrdtool

Die Frage ob wir das wirklich wollen beantworten wir mit ja.

[nach oben](#inhalt)


## pip

Nun wird pip installiert

    sudo apt-get install python-pip

Die Frage ob wir das wirklich wollen beantworten wir mit ja.

mit dem wir dann .Json installieren

[nach oben](#inhalt)


## .Json

.Json wird mittels pip installiert

    sudo pip install simplejson

[nach oben](#inhalt)

## Sht-Sensor

jetzt benötigen wir noch die Ansteuerung für die SHT-Sensoren

    sudo pip install sht-sensor

[nach oben](#inhalt)

## Adafruit_DHT

jetzt installieren wir noch die Unterstützung für die DHT-Sensoren

zuerst benötigen wir git um das Repository zu klonen

    sudo apt-get install git

Die Frage ob wir das wirklich wollen beantworten wir mit ja.

Jetzt wechseln wir zurück in das Homeverzeichnis von pi

    cd /home/pi

Danach geht es mit dem Klonen weiter

    sudo git clone https://github.com/adafruit/Adafruit_Python_DHT.git
    cd Adafruit_Python_DHT

    sudo apt-get update
    sudo apt-get install build-essential python-dev python-openssl

Die Frage ob wir das wirklich wollen beantworten wir mit ja.

    sudo python setup.py install

[nach oben](#inhalt)

# Wiring Pi

Nun installieren wir noch Wiring Pi. Dies ist ein nützliches Framework, um die GPIO Ein-und Ausgänge am Raspberry Pi zu schalten.

Jetzt wechseln wir zurück in das Homeverzeichnis von pi

    cd /home/pi

und klonen wiringPi

    sudo git clone git://git.drogon.net/wiringPi
    cd wiringPi
    sudo ./build

Damit sind unsere Installationsvorbereitungen abgeschlossen und wir können uns nun der Reifeschranksteuerung widmen

[nach oben](#inhalt)

# Programm Reifeschranksteuerung

Jetzt erstellen einen Ordner (RSS) im Verzeichniss /opt:

    sudo mkdir /opt/RSS

Wir laden nun die im GitHub zur Verfügung gestellten Dateien aus dem Branch master herunter und entpacken diese.

[Download](https://github.com/Tronje-the-Falconer/Reifeschrank/archive/master.zip)

Auf unserem PC installieren wir uns eine FTP Software (z.B. [FileZilla](https://filezilla-project.org/)), mit der wir eine Verbindung zu unserem Raspberry Pi aufbauen.

Wir verbinden uns mit dem Benutzer root und dem dazugehörigen Passwort

und kopieren dann die Dateien aus dem Download-Verzeichnis RSS in das gleichnamige Verzeichnis auf dem Raspberry Pi unter /opt/RSS.

danach kopieren wir aus dem Download-Verzeichnis var die Dateien sudowebscript.sh und .htpasswd in das gleichnamige Verzeichnis auf dem Raspberry Pi unter /var.

Über putty müssen wir nun dieses Shellscript in /etc/sudoers eintragen, damit der www-data User (User der Website) dies ausführen darf. Da ich nano einfacher zum bearbeiten finde, setzen wir diesen zuerst als Standard-Editor

    export EDITOR=nano

öffnen dann etc/sudoers mit

    EDITOR=nano sudo -E visudo
    
und tragen dann in sudoers folgendes nach 

    ...
    #User privilege specification
    root    ALL=(ALL:ALL) ALL
    ...

ein:

    www-data ALL=NOPASSWD:/var/sudowebscript.sh

Und speichern mittels _STRG+O_ und beenden mit _STRG+X_

Als nächstes setzen wir das PASSWORT für die Settingsseite:

    sudo htpasswd -b /var/.htpasswd reifeschrank PASSWORT

Die Dateien aus dem Download-Verzeichnis www kopieren wir nach /var/www/

Jetzt müssen wir noch ein paar Schreibrechte über Putty oder über FileZilla auf bestimmten Dateien vergeben:

Hier die Befehle für Putty:

    sudo chmod 666 /var/www/current.json
    sudo chmod 666 /var/www/settings.json
    sudo chmod 666 /var/www/tables.json
    sudo chmod 666 /var/www/logfile.txt
    sudo chmod 555 /var/sudowebscript.sh

Der Benutzer 'pi' ist standardmäßig Mitglied in der Gruppe 'gpio' und hat daher Zugriff auf die virtuellen Dateien /sys/class/gpio/ ... Der Webserver läuft aber als Benutzer 'www-data' und ist nicht Mitglied in dieser speziellen Gruppe. Um das zu ändern muss man also den 'www-data' Benutzer der Gruppe 'gpio' hinzufügen und den Webserver neu starten:

    sudo usermod -G gpio -a www-data
    sudo service apache2 restart

jetzt fahren wir den Raspberry Pi mittels

    sudo halt

herunter und ziehen den Stecker vom Netzteil.

Nun wird der Sensor am Raspberry Pi angeschlossen. Siehe dazu die [Bau- und Anschlussanleitung](https://github.com/Tronje-the-Falconer/Reifeschrank/wiki/3.i-Bau-und-Anschluss-des-Feuchtigkeits-und-Temperatursensors)

Sobald dies erfolgt ist, können wir den Raspberry wieder starten, indem wir den Netzstecker wieder anschließen.

Wenn wir nun die Webiste http://IPADRESSE/index.php aufrufen, sollte alles funktionieren.

[nach oben](#inhalt)
