<?php
    # settings.json auslesen um sollwerte wieder zu geben
    $SET = file_get_contents('settings.json');
    $array1 = json_decode($SET, true);
    $modus=$array1['modus'];
    if ($modus==0) {
        $modus_name= '- '._('cooling');
        $checked_0 = 'checked="checked"';
    }
    else {
        $checked_0 = '';
    }
    if ($modus==1) {
        $modus_name='- '._('cooling').'<br>- '._('Befeuchten');
        $checked_1 = 'checked="checked"';
    }
    else {
        $checked_1 = '';
    }
    if ($modus==2) {
        $modus_name = '- '._('heating').'<br>- '._('humidify');
        $checked_2 = 'checked="checked"';
    }
    else {
        $checked_2 = '';
    }
    if ($modus==3) {
        $modus_name='- '._('cooling').'<br>- '._('heating').'<br>- '._('humidify');
        $checked_3 = 'checked="checked"';
    }
    else {
        $checked_3 = '';
    }
    if ($modus==4) {
        $modus_name='- '._('cooling').'<br>- '._('heating').'<br>- '._('humidify').'<br>- '._('dehumidify').'<br>- '._('circulating air').'<br>- '._('exhausting air');
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
    $setpoint_temperature = $array1['setpoint_temperature'];
    $setpoint_humdity = $array1['setpoint_humdity'];
    $delay_humidify = $array1['delay_humidify'];
    $circulation_air_duration = $array1['circulation_air_duration'];
    $circulation_air_period = $array1['circulation_air_period'];
    $exhaust_air_duration = $array1['exhaust_air_duration'];
    $exhaust_air_period = $array1['exhaust_air_period'];
    $switch_on_cooling_compressor = $array1['switch_on_cooling_compressor'];
    $switch_off_cooling_compressor = $array1['switch_off_cooling_compressor'];
    $switch_on_humidifier = $array1['switch_on_humidifier'];
    $switch_off_humidifier = $array1['switch_off_humidifier'];
    $circulation_air_period = $circulation_air_period/60;
    $circulation_air_duration = $circulation_air_duration/60;
    $exhaust_air_period = $exhaust_air_period/60;
    $exhaust_air_duration = $exhaust_air_duration/60;
?>