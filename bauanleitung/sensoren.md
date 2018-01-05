---
layout: bauanleitung
title: "Sensoren"
---
## Inhalt 

Je nachdem welcher Sensor zum Einsatz kommen soll, muss ein Widerstand verbaut werden. Dazu gibt es hier eine Anleitung

1. [Bau des Sensorkabels](#bau-des-sensorkabels)
1. [AOSONG DHT22](#aosong-dht22)
    1. [Pinbelegung](#pinbelegung-dht22)
    1. [Widerstand](#widerstand-dht22)
    1. [Anschluss an den Raspberry Pi](#anschluss-an-den-raspberry-pi-dht22)
1. [SENSIRION SHT11](#sensirion-sht11)
    1. [Pinbelegung](#pinbelegung-sht11)
    1. [Widerstand](#widerstand-sht11)
    1. [Anschluss an den Raspberry Pi](#anschluss-an-den-raspberry-pi-sht11)
1. [SENSIRION SHT75](#sensirion-sht75)
    1. [Pinbelegung](#pinbelegung-sht75)
    1. [Widerstand](#widerstand-sht75)
    1. [Anschluss an den Raspberry Pi](#anschluss-an-den-raspberry-pi-sht75)
1. Raspberry Pi's
    1. [Raspberry Pi Zero](#raspberry-pi-zero)
    1. [Raspberry Pi 3 Model B](#raspberry-pi-3-model-b)
    1. [Raspberry Pi 2 Model B](#raspberry-pi-2-model-b)
    1. [Raspberry Pi B+](#raspberry-pi-b)

## Bau des Sensorkabels

Zum Anschließen des Sensors benutzen wir ein geschrimtes Lankabel, wobei die Schirmung auf beiden Seiten auf Masse gelegt werden sollte.

Um das ganze sauber an den Raspberry anzuschliessen einfach paar Stecker von den Jumperkabel abschneiden und anlöten.

Zwischen den Sensor und dem LAN-Kabel bietet es sich an ein kleine Platine zu basteln, auf der auch der Widerstand verbaut ist. Dadurch haben wir die Möglichkeit einen evtl. defekten Sensor oder Widerstand leicht mal auszutauschen.

<img src="https://github.com/Tronje-the-Falconer/Reifeschrank/blob/resources/wiki/sensor_board_rueckseite.jpg" alt="Board Rückseite" width="100">
<img src="https://github.com/Tronje-the-Falconer/Reifeschrank/blob/resources/wiki/sht75_auf_board.jpg" alt="SHT75 auf  Board gesteckt" width="100">
<img src="https://github.com/Tronje-the-Falconer/Reifeschrank/blob/resources/wiki/sht75_board_front.jpg" alt="SHT75 mit abgestecktem Board" width="100">

Des Weiteren kann bzw. sollte man den Empfindlichen SHT75 durch eine Kappe schützen. Dafür gibt es bereits eine fertige Lösung:

<img src="https://github.com/Tronje-the-Falconer/Reifeschrank/blob/resources/wiki/sht75-kappe-offen.jpg" alt="SHT75 offene Kappe" width="100">
<img src="https://github.com/Tronje-the-Falconer/Reifeschrank/blob/resources/wiki/sht75-kappe-offen2.jpg" alt="SHT75 offene Kappe 2" width="100">
<img src="https://github.com/Tronje-the-Falconer/Reifeschrank/blob/resources/wiki/sht75-geschlossene-kappe.jpg" alt="SHT75 geschlossene Kappe" width="100">

Oder man baut sich dies selber:

<img src="https://github.com/Tronje-the-Falconer/Reifeschrank/blob/resources/wiki/sht75-eigenbau1.jpg" alt="SHT75 Eigenbau 1" width="100">
<img src="https://github.com/Tronje-the-Falconer/Reifeschrank/blob/resources/wiki/sht75-eigenbau2.jpg" alt="SHT75 Eigenbau 2" width="100">
<img src="https://github.com/Tronje-the-Falconer/Reifeschrank/blob/resources/wiki/sht75-eigenbau3.jpg" alt="SHT75 Eigenbau 3" width="100">
<img src="https://github.com/Tronje-the-Falconer/Reifeschrank/blob/resources/wiki/sht75-eigenbau4.jpg" alt="SHT75 Eigenbau 4" width="100">
<img src="https://github.com/Tronje-the-Falconer/Reifeschrank/blob/resources/wiki/sht75-eigenbau5.jpg" alt="SHT75 Eigenbau 5" width="100">
<img src="https://github.com/Tronje-the-Falconer/Reifeschrank/blob/resources/wiki/sht75-eigenbau6.jpg" alt="SHT75 Eigenbau 6" width="100">

[nach oben](#inhalt)

Größenvergleich der Sensoren SHT75 und DHT22:

<img src="https://github.com/Tronje-the-Falconer/Reifeschrank/blob/resources/wiki/vergleich-sht75-dht22.jpg" alt="Größenvergleich SHT75 vs DHT22" width="100">


### AOSONG DHT22

<img src="http://www.hobbytronics.co.za/content/images/thumbs/0003724_dht-22-digital-temperature-and-humidity-sensor.jpeg" alt="DHT22" width="100">

#### Pinbelegung DHT22

<img src="http://www.mikrocontroller-elektronik.de/wp-content/uploads/2015/05/dht22_800_pinbelegung.jpg" alt="Pinbelegung DHT22" width="400">

#### Widerstand DHT22

Zwischen PIN 1 (Power +3,3-5,5V) und Pin 2 (DATA) des Sensors müssen wir einen 4,7-10KOhm Widerstand anbringen.

#### Anschluss an den Raspberry Pi DHT22

    Sensor                  --> Raspberry Pi
    Pin 1 (Power +3,3-5,5V) --> 3,3V
    Pin 2 (Data)            --> GPIO 17
    Pin 3 (GND)             --> wird nicht angeschlossen
    Pin 4 (GND)             --> GND

[nach oben](#inhalt)

### SENSIRION SHT11

<img src="https://img.conrad.de/medias/global/ce/5000_5999/5000/5030/5034/503486_LB_00_FB.EPS.jpg" alt="SHT11" width="100">

#### Pinbelegung SHT11

<img src="http://diginic.net/fa/wp-content/uploads/2015/06/sht11-pinout.jpg" alt="SHT11 Pinbelegung" width="400">


#### Widerstand SHT11

Zwischen Pin 2 (DATA) und Pin 4 (VDD) des Sensors müssen wir einen 10KOhm Widerstand anbringen.

#### Anschluss an den Raspberry Pi SHT11

    Sensor       --> Raspberry Pi
    Pin 1 (GND)  --> GND
    Pin 2 (DATA) --> GPIO 20 
    Pin 3 (SCK)  --> GPIO 21
    Pin 4 (VDD)  --> 3,3v

[nach oben](#inhalt)

### SENSIRION SHT75

<img src="https://img.conrad.de/medias/global/ce/5000_5999/5000/5030/5034/503492_LB_00_FB.EPS_1000.jpg" alt="SHT75" width="100">

#### Pinbelegung SHT75

<img src="http://embedded-lab.com/blog/wp-content/uploads/2011/05/SHT751.jpg" alt="SHT 75 Pinbelegung" width="400">

#### Widerstand SHT75

Zwischen Pin 2 (VDD) und Pin 4 (DATA) des Sensors müssen wir einen 10KOhm Widerstand anbringen.

#### Anschluss an den Raspberry Pi SHT75

    Sensor        --> Raspberry Pi
    Pin 1 (SCK)   --> GPIO 21
    Pin 2 (VDD)   --> 3,3v
    Pin 3 (GND)   --> GND
    Pin 4 (DATA)  --> GPIO 20 

## Raspberry Pi's

### Raspberry Pi Zero

<img src="http://pi4j.com/images/j8header-photo-zero.png" alt="Expansion Header Raspberry Pi Zero" width="350"><img src="https://i2.wp.com/indibit.de/wp-content/uploads/2015/08/Raspberry-Pi-2-Model-B-GPIO-Belegung.png" alt="J8 Pinout (40-pin Header) Raspberry Zero" width="350">

[nach oben](#inhalt)

### Raspberry Pi 3 Model B

<img src="http://pi4j.com/images/j8header-photo.png" alt="Expansion Header Raspberry Pi 3 Model B" width="350"><img src="https://i2.wp.com/indibit.de/wp-content/uploads/2015/08/Raspberry-Pi-2-Model-B-GPIO-Belegung.png" alt="J8 Pinout (40-pin Header) Raspberry Pi 3 Model B" width="350">

[nach oben](#inhalt)

### Raspberry Pi 2 Model B

<img src="http://pi4j.com/images/j8header-photo.png" alt="Expansion Header Raspberry Pi 2 Model B" width="350"><img src="https://i2.wp.com/indibit.de/wp-content/uploads/2015/08/Raspberry-Pi-2-Model-B-GPIO-Belegung.png" alt="J8 Pinout (40-pin Header)Raspberry Pi 2 Model B" width="350">

[nach oben](#inhalt)

### Raspberry Pi B+

<img src="http://pi4j.com/images/j8header-photo.png" alt="Expansion Header Raspberry Pi B+" width="350"><img src="https://i0.wp.com/indibit.de/wp-content/uploads/2015/08/Raspberry-Pi-Model-B-GPIO-Belegung.png" alt="J8 Pinout (40-pin Header)Raspberry Pi B+" width="350">

[nach oben](#inhalt)
