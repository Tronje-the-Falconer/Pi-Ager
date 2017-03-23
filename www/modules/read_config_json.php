<?php
    # config.json auslesen um allgemeine Configurationswerte dsetzen
    $SET = file_get_contents('config.json');
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
	$gpio_cooling_compressor = $data_configjsonfile['gpio_cooling_compressor'];
	$gpio_heater = $data_configjsonfile['gpio_heater'];
	$gpio_humidifier = $data_configjsonfile['gpio_humidifier'];
	$gpio_circulating_air = $data_configjsonfile['gpio_circulating_air'];
	$gpio_exhausting_air = $data_configjsonfile['gpio_exhausting_air'];
	$gpio_uv_light = $data_configjsonfile['gpio_uv_light'];
	$gpio_light = $data_configjsonfile['gpio_light'];
	$gpio_reserved1 = $data_configjsonfile['gpio_reserved1'];
?>
