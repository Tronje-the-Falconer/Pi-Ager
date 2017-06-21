<?php
    # config/config.json auslesen um allgemeine Configurationswerte dsetzen
    $SET = file_get_contents('config/config.json');
    $data_configjsonfile = json_decode($SET, true);    
    $sensortype = $data_configjsonfile['sensortype'];
    if ($sensortype == 1) {
        $sensorname = 'DHT11';
        $checked_sens_1 = 'checked="checked"';
    }
    else {
        $checked_sens_1 = '';
    }
    if ($sensortype == 2) {
        $sensorname = 'DHT22';
        $checked_sens_2 = 'checked="checked"';
    }
    else {
        $checked_sens_2 = '';
    }
    if ($sensortype == 3) {
        $sensorname = 'SHT75';
        $checked_sens_3 = 'checked="checked"';
    }
    else {
        $checked_sens_3 = '';
    }
    $language = $data_configjsonfile['language'];
    if ($language == 'de_DE') {
        $checked_language_1 = 'checked="checked"';
    }
    else {
        $checked_language_1 = '';
    }
    if ($language == 'en_EN') {
        $checked_language_2 = 'checked="checked"';
    }
    else {
        $checked_language_2 = '';
    }
    $delay_humidify = $data_configjsonfile['delay_humidify'];
    $switch_on_cooling_compressor = $data_configjsonfile['switch_on_cooling_compressor'];
    $switch_off_cooling_compressor = $data_configjsonfile['switch_off_cooling_compressor'];
    $switch_on_humidifier = $data_configjsonfile['switch_on_humidifier'];
    $switch_off_humidifier = $data_configjsonfile['switch_off_humidifier'];
    $uv_duration = $data_configjsonfile['uv_duration'];
    $uv_period = $data_configjsonfile['uv_period'];
    $light_duration = $data_configjsonfile['light_duration'];
    $light_period = $data_configjsonfile['light_period'];
?>
