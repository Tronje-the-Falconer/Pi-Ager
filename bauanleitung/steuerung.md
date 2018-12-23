---
layout: bauanleitung
title: "Steuerung"
---

### !!!! Achtung !!!! diese Seite ist im Aufbau !

# Steuerung
## Inhalt

1. [Platine](#platine)
1. [Relaisboard](#relaisboard)

#### Platine

##### Aktuelle Version der Platine: 

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/platine.jpg" alt="platine" width="800">


#### Grundsätzlicher Aufbau: 
Jeder fabig martkierte Bereich kann weggelassen werden, die nicht markierten Komponennten sind für die Grundfunktion des Pi-Agers erforderlich.


<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/platine_aufteilung.jpg" alt="platine_aufteilung" width="800">

##### NTC Temperatursensoren:  (Diese Funktion ist in der aktuellen Software Version 3.1.0 noch nicht implemetiert)
Sollte man zusätzliche Temperaturmessungen ( z.B. Fleischtemperatur, Umgebungstemperatur des Kühlschrenks, ...) benötigen, stehen hier 4 Messkanäle zur Verfügung. 

##### Externer Hardwareschalter:  (Diese Funktion ist in der aktuellen Software Version 3.1.0 noch nicht implemetiert)
Hier kann z.B. der potentialfreie Türkontakt des Kühlschranks angeschlossen werden, mit Hilfe dessen man z.B. das Licht einschalten kann, sobald man die Tür öffenet. 
... oder sobald die Tür geöffnet wird , wird ein stiller Alarm ausgelößt ;-)
Naja ... mal schauen welche Funktionen im Laufe der Zeit implementeirt werden

##### USV Modul:  (Diese Funktion ist in der aktuellen Software Version 3.1.0 noch nicht implemetiert)
Es besteht die Option ein USV (unterbrechungsfreie Spannungsversorgung) Modul einzusetzen, damit bei einem möglichen Spannungsausfall ein Alarm ausgelöst, oder  z.B. eine Nachricht verschickt werden kann.
In Abhängigkeit des eingesetzen Akkus läuft der Raspberry, die Temperatur und Feuchtemessung und der Alarmsummer eine entsprechende Zeit weiter.
Da das USV Modul nur einen begrenzten Strom liefern kann, muss man entsprehend vorsichtig sein wenn man weitere Komponenten direkt an den Raspberry anschließt. Optimal für den Einsatz eines USV Moduls ist die Verwendung des Raspberry Zero, da dieser eine geringere Strohmaufnahme hat.

##### Alarm Summer:  (Diese Funktion ist in der aktuellen Software Version 3.1.0 noch nicht implemetiert)
Ab Version 1.7 wurde ein Alarmsummer integriert, mit dessen Hilfe entsprechende Zuständen das Systems akustisch gemeldet werden können.

##### Waagen:
Die Gewichtmessung ist auf der Basis des HX 711 Moduls aufgebaut. Wer das Gewicht des zu reifenden Fleisches über einen längeren Zeitraum beobachten möchte, kann bis zu 2 Wägezellen an dem System beteiben.

##### Display:  (Diese Funktion ist in der aktuellen Software Version 3.1.0 noch nicht implemetiert)
Mit Hilfe des Dispays kann man die wichtigsten Funktionen vor Ort direkt am Kühlschrank bedienen und beobachten.



#### Anschlüsse bzw. Jumpermöglichkeiten:

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/platine_uebersicht.jpg" alt="latine_aufteilung" width="800">



#### Relaisboard

Zur Steuerung der Pi-Agers ist auch ein Relaisboard notwendig. Da in der Anfängen des Reifeschrankes von "kleben gebliebenen" Relaiskontakten berichtet wurde, wird empfohlen, die Eignung der eingesetzten Relais zum Schalten des entsprechenden Gerätes (Kompressor, Heizung, ...) von einer entsprechenden Elektrofachkraft zu prüfen. Sollten die originalen Relais vom gekauften Relaisboard für eure Aufgabe nicht geeigent sein kann man die Relais wie unten dragestellt austauschen oder z.B. ein entsprechendes Schütz dazwischen geschaltet werden.


#### !!!!  Der 230V Bereich des Relaisboards kann hier nicht betrachtet werden, diesen Teil muss jeder in Eigenverantwortung zusammen mit einer Elektrofachkraft planen und verdrahten !!!!

##### Ursprungszustand:

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/relaisboard1.JPG" alt="relaisboard1" width="800">

##### Relais müssen ausgelötet werden:

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/relaisboard2.JPG" alt="relaisboard2" width="800">

##### Neue, geeignete Relais werden wieder eingelötet:

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/bilder/relaisboard3.JPG" alt="relaisboard3" width="800">

##### Relaisbeschriftung:

<img src="https://raw.githubusercontent.com/Tronje-the-Falconer/Pi-Ager/resources/wiki/relaisbeschriftung.png" alt="relaisbeschriftung" width="800">

Kann hier runtergeladen werden:  [Relaisbeschriftung PI-Ager](https://github.com/Tronje-the-Falconer/Pi-Ager/raw/resources/wiki/relaisbeschriftung.png) 

