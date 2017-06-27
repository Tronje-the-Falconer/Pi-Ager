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
    if ($language == 1) {
        $checked_language_1 = 'checked="checked"';
    }
    else {
        $checked_language_1 = '';
    }
    if ($language == 2) {
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
    $uv_modus = $data_configjsonfile['uv_modus'];
    if ($uv_modus == 1) {
        $checked_uv_1 = 'checked="checked"';
    }
    else {
        $checked_uv_1 = '';
    }
    if ($uv_modus == 2) {
        $checked_uv_2 = 'checked="checked"';
    }
    else {
        $checked_uv_2 = '';
    }
    if ($uv_modus == 3) {
        $checked_uv_3 = 'checked="checked"';
    }
    else {
        $checked_uv_3 = '';
    }
    $uv_duration = $data_configjsonfile['uv_duration']/60;
    $uv_period = $data_configjsonfile['uv_period']/60;
    $switch_on_uv_hour = $data_configjsonfile['switch_on_uv_hour'];
    $switch_on_uv_minute = $data_configjsonfile['switch_on_uv_minute'];
    $light_modus = $data_configjsonfile['light_modus'];
    if ($light_modus == 1) {
        $checked_light_1 = 'checked="checked"';
    }
    else {
        $checked_light_1 = '';
    }
    if ($light_modus == 2) {
        $checked_light_2 = 'checked="checked"';
    }
    else {
        $checked_light_2 = '';
    }
    if ($light_modus == 3) {
        $checked_light_3 = 'checked="checked"';
    }
    else {
        $checked_light_3 = '';
    }
    $light_duration = $data_configjsonfile['light_duration']/60;
    $light_period = $data_configjsonfile['light_period']/60;
    $switch_on_light_hour = $data_configjsonfile['switch_on_light_hour'];
    $switch_on_light_minute = $data_configjsonfile['switch_on_light_minute'];
    $dehumidifier_modus = $data_configjsonfile['dehumidifier_modus'];
    if ($dehumidifier_modus == 1) {
        $checked_dehumidify_1 = 'checked="checked"';
    }
    else {
        $checked_dehumidify_1 = '';
    }
    if ($dehumidifier_modus == 2) {
        $checked_dehumidify_2 = 'checked="checked"';
    }
    else {
        $checked_dehumidify_2 = '';
    }
    if ($dehumidifier_modus == 3) {
        $checked_dehumidify_3 = 'checked="checked"';
    }
    else {
        $checked_dehumidify_3 = '';
    }
?>
