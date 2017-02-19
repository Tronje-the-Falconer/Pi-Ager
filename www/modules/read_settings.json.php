<?php
    # settings.json auslesen um sollwerte wieder zu geben
    $SET = file_get_contents('settings.json');
    $array1 = json_decode($SET, true);
    $mod=$array1['mod'];
    if ($mod==0) {
        $modus='- Kühlen';
        $checked_0 = 'checked="checked"';
    }
    else {
        $checked_0 = '';
    }
    if ($mod==1) {
        $modus='- Kühlen<br>- Befeuchten';
        $checked_1 = 'checked="checked"';
    }
    else {
        $checked_1 = '';
    }
    if ($mod==2) {
        $modus='- Heizen<br>- Befeuchten';
        $checked_2 = 'checked="checked"';
    }
    else {
        $checked_2 = '';
    }
    if ($mod==3) {
        $modus='- Kühlen<br>- Heizen<br>- Befeuchten';
        $checked_3 = 'checked="checked"';
    }
    else {
        $checked_3 = '';
    }
    if ($mod==4) {
        $modus='- Kühlen<br>- Heizen<br>- Befeuchten<br>- Entfeuchten<br>- Umluft<br>- Abluft';
        $checked_4 = 'checked="checked"';
    }
    else {
        $checked_4 = '';
    }
    $sensortype=$array1['sensortype'];
    if ($sensortype==1) {
        $sensorname='DHT11';
        $checked_sens_1 = 'checked="checked"';
    }
    else {
        $checked_sens_1 = '';
    }
    if ($sensortype==2) {
        $sensorname='DHT22';
        $checked_sens_2 = 'checked="checked"';
    }
    else {
        $checked_sens_2 = '';
    }
    if ($sensortype==3) {
        $sensorname='SHT75';
        $checked_sens_3 = 'checked="checked"';
    }
    else {
        $checked_sens_3 = '';
    }
    $tempsoll_float = $array1['temp'];
    $humsoll_float = $array1['hum'];
    $humdelay = $array1['humdelay'];
    $tempon = $array1['tempon'];
    $tempoff = $array1['tempoff'];
    $tempon1 = $array1['tempon1'];
    $tempoff1 = $array1['tempoff1'];
    $temphyston = $array1['temphyston'];
    $temphystoff = $array1['temphystoff'];
    $humhyston = $array1['humhyston'];
    $humhystoff = $array1['humhystoff'];
    $tempoff = $tempoff/60+$tempon/60;
    $tempon = $tempon/60;
    $tempoff1 = $tempoff1/60+$tempon1/60;
    $tempon1 = $tempon1/60;
?>