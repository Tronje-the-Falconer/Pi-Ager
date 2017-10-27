<?php
// include 'database.php';

    // $database = new SQLite3("/var/www/config/pi-ager.sqlite3");
    // $result = $database->query('SELECT key, value FROM config WHERE "key"="sensortype" OR "key"="language" OR "key"="switch_on_cooling_compressor" OR "key" = "switch_off_cooling_compressor"OR "key"="switch_on_humidifier" OR "key" = "switch_off_humidifier" OR "key" = "delay_humidify" OR "key" = "uv_modus" OR "key" = "uv_duration" OR "key" = "uv_period" OR "key" = "switch_on_uv_hour" OR "key" = "switch_on_uv_minute" OR "key" = "light_modus" OR "key" = "light_duration" OR "key" = "light_period" OR "key" = "switch_on_light_hour" OR "key" = "switch_on_light_minute" OR "key" = "dehumidifier_modus" OR "key" = "referenceunit_scale1" OR "key" = "referenceunit_scale2"');
    // $items_config = array();
    // while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
        // {
        // $items_config[$dataset['key']] = $dataset['value'];
        // }
    // $database->close();

    $sensor_temperature = round(get_table_value($current_values_table,$sensor_temperature_key), 1);
    $sensortype = get_table_value($config_settings_table,$sensortype_key);
    $language = get_table_value($config_settings_table,$language_key);
    $switch_on_cooling_compressor = get_table_value($config_settings_table,$switch_on_cooling_compressor_key);
    $switch_off_cooling_compressor = get_table_value($config_settings_table,$switch_off_cooling_compressor_key);
    $switch_on_humidifier = get_table_value($config_settings_table,$switch_on_humidifier_key);
    $switch_off_humidifier = get_table_value($config_settings_table,$switch_off_humidifier_key);
    $delay_humidify = get_table_value($config_settings_table,$delay_humidify_key);
    $uv_modus = get_table_value($config_settings_table,$uv_modus_key);
    $uv_duration = get_table_value($config_settings_table,$uv_duration_key)/60;
    $uv_period = get_table_value($config_settings_table,$uv_period_key)/60;
    $switch_on_uv_hour = get_table_value($config_settings_table,$switch_on_uv_hour_key);
    $switch_on_uv_minute = get_table_value($config_settings_table,$switch_on_uv_minute_key);
    $light_modus = get_table_value($config_settings_table,$light_modus_key);
    $light_duration = get_table_value($config_settings_table,$light_duration_key)/60;
    $light_period = get_table_value($config_settings_table,$light_period_key)/60;
    $switch_on_light_hour = get_table_value($config_settings_table,$switch_on_light_hour_key);
    $switch_on_light_minute = get_table_value($config_settings_table,$switch_on_light_minute_key);
    $dehumidifier_modus = get_table_value($config_settings_table,$dehumidifier_modus_key);
    $referenceunit_scale1 = get_table_value($settings_scale1_table,$referenceunit_key);
    $referenceunit_scale2 = get_table_value($settings_scale2_table,$referenceunit_key);
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
