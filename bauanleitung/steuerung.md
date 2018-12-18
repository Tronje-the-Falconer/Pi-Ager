---
layout: bauanleitung
title: "Steuerung"
---

## !!!! Achtung !!!! diese Seite ist im Aufbau !

# Steuerung
## Inhalt

1. [Platine](#platine)
1. [Relaisboard](#relaisboard)

#### Platine

Die aktuelle Version der Platine: 

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/platine.jpg" alt="platine" width="800">


Hier ist dargestellt wie die Platine in Grundsatz aufgebaut ist. 
Jeder fabig martkierte Bereich kann weggelassen werden, die nicht markierten Komponennten sind für die Grundfunktion des Pi-Agers erforderlich.


<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/platine_aufteilung.jpg" alt="platine_aufteilung" width="800">

##### NTC Temperatursensoren:
Sollte man zusätzliche Temperaturmessungen (Fleischtemepratur, Umgebungstemperatur des Kühlschrenks, ...)benötigen, stehen hier 4 Messkanäle zur Verfügung. 

##### Externer Hardwareschalter:
Hier kann z.B. der potentialfreie Türkontakt des Kühlschranks angeschlossen werden mit hilfe dessen man z.B. das Licht einschalten kann sobald man die tür öffent. 
... oder sobald die tür geöffnet wird , wird ein stiller Alarm ausgelößt ;-)
Naja ... mal schauen welche Funktionen im Laufe der Zeit implementeirt werden

##### USV Modul:
Esbesteht die Option ein USV (unterbrechungsfreie Spannungsversorgung) Modul einzusetzen, damit bei einem möglichen Spannungsausfall ein Alarm ausgelösst, oder  z.B. eine Nachricht verschickt werden kann.
In Abhängigkeit des eingesetzen Akkus läuft der Raspberry , die Temperatur und Feuchtemessung und der Alarmsummer eine entsprechende Zeit weiter.

#### Alarm Summer:

#### Waagen:

#### Display:



Die einzelene Anschlüsse bzw. Jumpermöglichkeiten werden hier erklärt:

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/platine_uebersicht.jpg" alt="latine_aufteilung" width="800">



### Relaisboard

Zur Steuerung der Pi-Agers ist auch ein Relaisboard notwendig. Da in der Anfängen des Reifeschrankes von "kleben gebliebenen" Relaiskontakten berichtet wurde, wird empfohen, die Eignung der eingesetzten Relais zum Schalten des entsprechenden Gerätes (Kompressor, Heizung, ...) von einer entsprechenden Elektrofachkraft  zu prüfen. Sollten die originalen Relais vom gekauften Relaisboard für eure Aufgabe nicht geeigent sein kann man die Relais wie unten dragestellt austauschen oder z.B. ein entsprechendes Schütz dazwischen geschaltet werden.

Ursprungszustand:

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/relaisboard1.JPG" alt="relaisboard1" width="800">

Relais müssen ausgelötet werden:

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/relaisboard2.JPG" alt="relaisboard2" width="800">

die neuen, geeigneten Relais werden wieder eingelötet:

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/relaisboard3.JPG" alt="relaisboard3" width="800">

