---
layout: bauanleitung
title: "steuerung"
---
# Inhalt

1. [Stromversorgung](#stromversorgung)
1. [Anschluss an den Raspberry Pi](#anschluss-an-den-raspberry-pi)



## Stromversorgung

Bei der Auslieferung des Relaysboards sitzt rechts, diesen müssen wir entfernen.

An den Anschluss _GND _ schließen wir die Masse und an _JD-VCC_ die 5 Volt des USB Kabels an. Dafür müssen wir den Stecker bschneiden und eine Steckverbinder z.B. von den beim Temperatursensor verwendeteten Jumperkabeln, anlöten.

Die 5V werden vom zweiten Ausgang des USB-Netzteils gespeist. Die vom Raspberry gelieferten 5V führen leider nicht genug Strom um das Relaysboard zu betreiben.

## Anschluss an den Raspberry Pi

Wie in [3.i Bau und Anschluss des Feuchtigkeits und Temperatursensors](https://github.com/Tronje-the-Falconer/Reifeschrank/wiki/3.i-Bau-und-Anschluss-des-Feuchtigkeits-und-Temperatursensors) zu sehen, schließen wir die Relais wie folgt an den Raspberry Pi:

    BOARD_MODE = gpio.BCM; # GPIO board mode
    
    Variable im Programm    Raspberry GPIO      Relaisboard
    PIN_HEATER              GPIO27              Pin IN1        Pin für Heizkabel
    PIN_COOL                GPIO22              Pin IN2        Pin für Kühlschrankkompressor
    PIN_FAN                 GPIO18              Pin IN5        Pin für Umluftventilator
    PIN_FAN1                GPIO23              Pin IN4        Pin für Austauschlüfter
    PIN_HUM                 GPIO24              Pin IN3        Pin für Luftbefeuchter
                            3.3V DC Power       Pin VCC
