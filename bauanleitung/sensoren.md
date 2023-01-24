---
layout: bauanleitung
title: "Sensoren"
---
# Sensoren
### Inhalt 

Je nachdem welcher Sensor zum Einsatz kommen soll, muss ein Widerstand verbaut werden. Dazu gibt es hier eine Anleitung

Empfohlen sind die Sensoren SHT3x oder SHT85, da diese mit dem I2C-Bus stabiler funktionieren und nicht so schnell Feuchtigkeitsgesättigt sind. Der SHT3x ist relativ günstig zu bekommen und ausreichend.

1. [Bau des Sensorkabels](#bau-des-sensorkabels)
1. [AOSONG DHT22](#aosong-dht22)
    1. [Pinbelegung](#pinbelegung-dht22)
    1. [Widerstand](#widerstand-dht22)
1. [SENSIRION SHT11](#sensirion-sht11)
    1. [Pinbelegung](#pinbelegung-sht11)
    1. [Widerstand](#widerstand-sht11)
1. [SENSIRION SHT75](#sensirion-sht75)
    1. [Pinbelegung](#pinbelegung-sht75)
    1. [Widerstand](#widerstand-sht75)
1. [SENSIRION SHT3x](#sensirion-sht3x)
    1. [Pinbelegung](#pinbelegung-sht3x)
    1. [Widerstand](#widerstand-sht3x)
1. [SENSIRION SHT85](#sensirion-sht85)
    1. [Pinbelegung](#pinbelegung-sht85)
    1. [Widerstand](#widerstand-sht85)

#### Bau des Sensorkabels

Zum Anschließen des Sensors benutzen wir ein geschrimtes Lankabel, wobei die Schirmung nur auf der Seite der Pi-Ager Platine bzw. Raspberry Pi auf Masse gelegt werden sollte. Auch ein USB Kabel hat sich in diesem Anwendungsfall bewährt.

Zum einfacheren Anschließen der Sensoren SHT75 und SHT85 wurde eine kleine Paltine entwickelt, die man entweder selbst anfertiegen lassen oder im Forum über das beziehen kann.

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/wiki/sensor_board_rueckseite.jpg" alt="Board Rückseite" width="100">
<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/wiki/sht75_auf_board.jpg" alt="SHT75 auf  Board gesteckt" width="100">
<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/wiki/sht75_board_front.jpg" alt="SHT75 mit abgestecktem Board" width="100">

Des Weiteren kann bzw. sollte man den Empfindlichen SHT75 durch eine Kappe schützen. Dafür gibt es bereits eine fertige Lösung:

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/wiki/sht75-kappe-offen.jpg" alt="SHT75 offene Kappe" width="100">
<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/wiki/sht75-kappe-offen2.jpg" alt="SHT75 offene Kappe 2" width="100">
<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/wiki/sht75-geschlossene-kappe.jpg" alt="SHT75 geschlossene Kappe" width="100">

Oder man baut sich dies selber:

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/wiki/sht75-eigenbau1.jpg" alt="SHT75 Eigenbau 1" width="100">
<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/wiki/sht75-eigenbau2.jpg" alt="SHT75 Eigenbau 2" width="100">
<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/wiki/sht75-eigenbau3.jpg" alt="SHT75 Eigenbau 3" width="100">
<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/wiki/sht75-eigenbau4.jpg" alt="SHT75 Eigenbau 4" width="100">
<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/wiki/sht75-eigenbau5.jpg" alt="SHT75 Eigenbau 5" width="100">
<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/wiki/sht75-eigenbau6.jpg" alt="SHT75 Eigenbau 6" width="100">

[nach oben](#inhalt)

Größenvergleich der Sensoren SHT75 und DHT22:

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/wiki/vergleich-sht75-dht22.jpg" alt="Größenvergleich SHT75 vs DHT22" width="100">


#### AOSONG DHT22

<img src="http://www.hobbytronics.co.za/content/images/thumbs/0003724_dht-22-digital-temperature-and-humidity-sensor.jpeg" alt="DHT22" width="100">

##### Pinbelegung DHT22

<img src="http://www.mikrocontroller-elektronik.de/wp-content/uploads/2015/05/dht22_800_pinbelegung.jpg" alt="Pinbelegung DHT22" width="400">

##### Widerstand DHT22

Zwischen PIN 1 (Power +3,3-5,5V) und Pin 2 (DATA) des Sensors müssen wir einen 4,7-10KOhm Widerstand anbringen.

[nach oben](#inhalt)

#### SENSIRION SHT11
<!--
<img src="https://img.conrad.de/medias/global/ce/5000_5999/5000/5030/5034/503486_LB_00_FB.EPS.jpg" alt="SHT11" width="100">
-->
##### Pinbelegung SHT11

<img src="http://diginic.net/fa/wp-content/uploads/2015/06/sht11-pinout.jpg" alt="SHT11 Pinbelegung" width="400">


##### Widerstand SHT11

Zwischen Pin 2 (DATA) und Pin 4 (VDD) des Sensors müssen wir einen 10KOhm Widerstand anbringen.

[nach oben](#inhalt)

#### SENSIRION SHT75
<!--
<img src="https://img.conrad.de/medias/global/ce/5000_5999/5000/5030/5034/503492_LB_00_FB.EPS_1000.jpg" alt="SHT75" width="100">
-->
##### Pinbelegung SHT75

<img src="http://embedded-lab.com/blog/wp-content/uploads/2011/05/SHT751.jpg" alt="SHT 75 Pinbelegung" width="400">

##### Widerstand SHT75

Zwischen Pin 2 (VDD) und Pin 4 (DATA) des Sensors müssen wir einen 10KOhm Widerstand anbringen.


#### SENSIRION SHT3x

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/SHT3x.JPG" alt="SHT3x" width="150">
<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/SHT3x2.JPG" alt="SHT3x2" width="150">

##### Für den internen Sensor (Hauptsensor) muss die Adresse nicht umgestellt werden, der Sensor kann so genutzt werden wie er geliefert wurde. (in diesem Fall hat der die Busadresse 44)


##### Adresse umstellen für den externen Sensor:

Der Pin AD ist im Lieferzustend über einen 10kOhm Widerstand auf Masse verbunden und hat somit ein Lo Level und die Adresse 44.
Möchte man die Adresse ändern, muss man an den Pin AD ein High Level anlegen. 

Die empfohlene Methode:
man entfernt den 10kOhm Widerstand zur Masse und lötet einen 10kOhm Widerstand zw. den Pin AD und VCC (3,3V).
In diesem Fall erhält der Sensor die Adresse 45 und kann als externer Sensor betrieben werden.

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/SHT3xAdresse.jpeg" alt="SHT3x_Adresse" width="150">
<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/SHT3x_7.jpg" alt="SHT3x_7" width="150">
<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/SHT3x_4.jpg" alt="SHT3x_4" width="150">
<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/sht3x_5.jpg" alt="SHT3x_5" width="150">
<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/SHT3x_6.jpg" alt="SHT3x_6" width="150">


#### SENSIRION SHT85

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/SHT85.jpg" alt="SHT85" width="180">

##### Pinbelegung SHT85

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/SHT85pin.JPG" alt="SHT85 Pinbelegung" width="300">

##### Widerstand SHT85

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/SHT85sch.jpg" alt="SHT85 Pinbelegung" width="300">


[nach oben](#inhalt)
