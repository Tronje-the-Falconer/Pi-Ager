<?php
    $modus = round(get_table_value($config_settings_table,$modus_key), 1);
    $setpoint_temperature = round(get_table_value($config_settings_table,$setpoint_temperature_key), 1);
    $setpoint_humidity = round(get_table_value($config_settings_table,$setpoint_humidity_key), 1);
    $circulation_air_duration = round(get_table_value($config_settings_table,$circulation_air_duration_key), 1)/60;
    $circulation_air_period = round(get_table_value($config_settings_table,$circulation_air_period_key), 1)/60;
    $exhaust_air_duration = round(get_table_value($config_settings_table,$exhaust_air_duration_key), 1)/60;
    $exhaust_air_period = round(get_table_value($config_settings_table,$exhaust_air_period_key), 1)/60;

    
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
    logger('DEBUG', 'read_settings_db performed');
?>
