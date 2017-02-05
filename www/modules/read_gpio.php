<?php
    # Status auslesen
    $val22 = shell_exec("/usr/local/bin/gpio -g read 22"); #Kühlung
    $val27 = shell_exec("/usr/local/bin/gpio -g read 27"); #Heizung
    $val24 = shell_exec("/usr/local/bin/gpio -g read 24"); #luftbefeuchter
    $val18 = shell_exec("/usr/local/bin/gpio -g read 18"); #Umluft
    $val23 = shell_exec("/usr/local/bin/gpio -g read 23"); #Abluft

    #Prüfen ob Programme laufen
    $valtab = shell_exec("ps ax | grep -v grep | grep Reifetab.py"); #Reifetab.py
    $valrss = shell_exec("ps ax | grep -v grep | grep Rss.py"); #Rss.py

    #Schaltzustände setzen
    if($val22 == 0) {
        $cool = "images/led-on-green-20x20.png";
        }
    if($val22 == 1) {
        $cool = "images/led-off-green-20x20.png";
        }
    if($val27 == 0) {
        $heat = "images/led-on-green-20x20.png";
        }
    if($val27 == 1) {
        $heat = "images/led-off-green-20x20.png";
        }
    if($val18 == 0) {
        $uml = "images/led-on-green-20x20.png";
        }
    if($val18 == 1) {
        $uml = "images/led-off-green-20x20.png";
        }
    if($val23 == 0) {
        $lat = "images/led-on-green-20x20.png";
        }
    if($val23 == 1) {
        $lat = "images/led-off-green-20x20.png";
        }
    if($val24 == 0) {
        $lbf = "images/led-on-green-20x20.png";
        }
    if($val24 == 1) {
        $lbf = "images/led-off-green-20x20.png";
        }
    if($valtab == 0) {
        $tabelle = "images/led-off-green-20x20.png";
    }
    else {
        $tabelle = "images/led-on-green-20x20.png";
    }
    if($valrss == 0) {
        $rss = "images/led-off-green-20x20.png";
    }
    else {
        $rss = "images/led-on-green-20x20.png";
    }
?>