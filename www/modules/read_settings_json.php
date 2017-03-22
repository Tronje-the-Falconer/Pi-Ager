<?php
    # settings.json auslesen um sollwerte wieder zu geben
    $SET = file_get_contents('settings.json');
    $data_settingsjsonfile = json_decode($SET, true);
    $modus = $data_settingsjsonfile['modus'];
    if ($modus  ==  0) {
        $modus_name = '- '._('cooling');
        $checked_0 = 'checked="checked"';
    }
    else {
        $checked_0 = '';
    }
    if ($modus  ==  1) {
        $modus_name = '- '._('cooling').'<br>- '._('humidify');
        $checked_1 = 'checked="checked"';
    }
    else {
        $checked_1 = '';
    }
    if ($modus == 2) {
        $modus_name = '- '._('heating').'<br>- '._('humidify');
        $checked_2 = 'checked="checked"';
    }
    else {
        $checked_2 = '';
    }
    if ($modus == 3) {
        $modus_name = '- '._('cooling').'<br>- '._('heating').'<br>- '._('humidify');
        $checked_3 = 'checked="checked"';
    }
    else {
        $checked_3 = '';
    }
    if ($modus == 4) {
        $modus_name = '- '._('cooling').'<br>- '._('heating').'<br>- '._('humidify').'<br>- '._('dehumidify').'<br>- '._('circulating air').'<br>- '._('exhausting air');
        $checked_4 = 'checked="checked"';
    }
    else {
        $checked_4 = '';
    }
    $setpoint_temperature = $data_settingsjsonfile['setpoint_temperature'];
    $setpoint_humdity = $data_settingsjsonfile['setpoint_humdity'];
    $circulation_air_duration = $data_settingsjsonfile['circulation_air_duration'];
    $circulation_air_period = $data_settingsjsonfile['circulation_air_period'];
    $exhaust_air_duration = $data_settingsjsonfile['exhaust_air_duration'];
    $exhaust_air_period = $data_settingsjsonfile['exhaust_air_period'];
    $circulation_air_period = $circulation_air_period/60;
    $circulation_air_duration = $circulation_air_duration/60;
    $exhaust_air_period = $exhaust_air_period/60;
    $exhaust_air_duration = $exhaust_air_duration/60;
?>
