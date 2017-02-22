<?php
    # Status auslesen
    $val22 = shell_exec('sudo /var/sudowebscript.sh read22'); #Kühlung
    $val27 = shell_exec('sudo /var/sudowebscript.sh read27'); #Heizung
    $val24 = shell_exec('sudo /var/sudowebscript.sh read24'); #luftbefeuchter
    $val18 = shell_exec('sudo /var/sudowebscript.sh read18'); #Umluft
    $val23 = shell_exec('sudo /var/sudowebscript.sh read23'); #Abluft

    #Prüfen ob Programme laufen
    $valtab = shell_exec('sudo /var/sudowebscript.sh grepreifetab'); #Reifetab.py
    $valrss = shell_exec('sudo /var/sudowebscript.sh greprss'); #Rss.py

    #Schaltzustände setzen
    if($val22 == 0) {
        $cool = 'images/led-on-green-20x20.png';
        }
    if($val22 == 1) {
        $cool = 'images/led-off-green-20x20.png';
        }
    if($val27 == 0) {
        $heat = 'images/led-on-green-20x20.png';
        }
    if($val27 == 1) {
        $heat = 'images/led-off-green-20x20.png';
        }
    if($val18 == 0) {
        $uml = 'images/led-on-green-20x20.png';
        }
    if($val18 == 1) {
        $uml = 'images/led-off-green-20x20.png';
        }
    if($val23 == 0) {
        $lat = 'images/led-on-green-20x20.png';
        }
    if($val23 == 1) {
        $lat = 'images/led-off-green-20x20.png';
        }
    if($val24 == 0) {
        $lbf = 'images/led-on-green-20x20.png';
        }
    if($val24 == 1) {
        $lbf = 'images/led-off-green-20x20.png';
        }
    if($valtab == 0) {
        $tabelle = 'images/led-off-green-20x20.png';
    }
    else {
        $tabelle = 'images/led-on-green-20x20.png';
    }
    if($valrss == 0) {
        $rss = 'images/led-off-green-20x20.png';
    }
    else {
        $rss = 'images/led-on-green-20x20.png';
    }
?>
