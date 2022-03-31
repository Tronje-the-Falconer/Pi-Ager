<?php
    # $sensor_temperature = round(get_table_value($current_values_table,$sensor_temperature_key), 1);
    $sensortype = get_table_value($config_settings_table,$sensortype_key);
    $sensorsecondtype = get_table_value($config_settings_table,$sensorsecondtype_key);
    $language = get_table_value($config_settings_table,$language_key);
    $switch_on_cooling_compressor = number_format(floatval(get_table_value($config_settings_table,$switch_on_cooling_compressor_key)), 1, '.', '');
    $switch_off_cooling_compressor = number_format(floatval(get_table_value($config_settings_table,$switch_off_cooling_compressor_key)), 1, '.', '');
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
    $failure_humidity_delta = get_table_value($config_settings_table, $failure_humidity_delta_key);
    $failure_temperature_delta = get_table_value($config_settings_table, $failure_temperature_delta_key);
    $tft_display_type = get_table_value($config_settings_table, $tft_display_type_key);
    $internal_temperature_low_limit = get_table_value($config_settings_table, $internal_temperature_low_limit_key);
    $internal_temperature_high_limit = get_table_value($config_settings_table, $internal_temperature_high_limit_key);
    $internal_temperature_hysteresis = get_table_value($config_settings_table, $internal_temperature_hysteresis_key);
    $shutdown_on_batlow =  intval(get_table_value($config_settings_table, $shutdown_on_batlow_key));
    $delay_cooler = intval(get_table_value($config_settings_table, $delay_cooler_key));
    $dewpoint_check = intval(get_table_value($config_settings_table, $dewpoint_check_key));
    
    $referenceunit_scale1 = number_format(floatval(get_table_value($settings_scale1_table,$referenceunit_key)), 1, '.', '');
    $offset_scale1 = number_format(floatval(get_table_value($settings_scale1_table, $offset_key)), 1, '.', '');
    $measuring_interval_scale1 = get_table_value($settings_scale1_table,$scale_measuring_interval_key);
    $measuring_duration_scale1 = get_table_value($settings_scale1_table,$measuring_duration_key);
    $saving_period_scale1 = get_table_value($settings_scale1_table,$saving_period_key);
    $samples_scale1 = get_table_value($settings_scale1_table,$samples_key);
    $spikes_scale1 = get_table_value($settings_scale1_table,$spikes_key);
    
    $referenceunit_scale2 = number_format(floatval(get_table_value($settings_scale2_table,$referenceunit_key)), 1, '.', '');
    $offset_scale2 = number_format(floatval(get_table_value($settings_scale2_table, $offset_key)), 1, '.', '');    
    $measuring_interval_scale2 = get_table_value($settings_scale2_table,$scale_measuring_interval_key);
    $measuring_duration_scale2 = get_table_value($settings_scale2_table,$measuring_duration_key);
    $saving_period_scale2 = get_table_value($settings_scale2_table,$saving_period_key);
    $samples_scale2 = get_table_value($settings_scale2_table,$samples_key);
    $spikes_scale2 = get_table_value($settings_scale2_table,$spikes_key);

    $meat1_sensortype = get_table_value($config_settings_table, $meat1_sensortype_key);
    $meat2_sensortype = get_table_value($config_settings_table, $meat2_sensortype_key);   
    $meat3_sensortype = get_table_value($config_settings_table, $meat3_sensortype_key);   
    $meat4_sensortype = get_table_value($config_settings_table, $meat4_sensortype_key);   
    
    $mi_mac_last3bytes = get_table_value_from_field($atc_mi_thermometer_mac_table, NULL, $mi_mac_last3bytes_key);
    
    if ($sensortype == 1) {
        $sensorname = 'DHT11';
        $checked_sens_1 = 'checked="checked"';
        $sens_second_active = 'disabled = "true"';
    }
    else {
        $checked_sens_1 = '';
    }
    if ($sensortype == 2) {
        $sensorname = 'DHT22';
        $checked_sens_2 = 'checked="checked"';
        $sens_second_active = 'disabled = "true"';
    }
    else {
        $checked_sens_2 = '';
    }
    if ($sensortype == 3) {
        $sensorname = 'SHT75';
        $checked_sens_3 = 'checked="checked"';
        $sens_second_active = 'disabled = "true"';
    }
    else {
        $checked_sens_3 = '';
    }
    if ($sensortype == 4) {
        $sensorname = 'SHT85';
        $checked_sens_4 = 'checked="checked"';
        $sens_second_active = '';
    }
    else {
        $checked_sens_4 = '';
    }
    if ($sensortype == 5) {
        $sensorname = 'SHT3x';
        $checked_sens_5 = 'checked="checked"';
        $sens_second_active = '';
    }
    else {
        $checked_sens_5 = '';
    }
    if ($sensorsecondtype == 0) {
        $sensorsecondname = 'disabled';
        $checked_senssecond_0 = 'checked="checked"';
    }
    else {
        $checked_senssecond_0 = '';
    }

    if ($sensorsecondtype == 4) {
        $sensorsecondname = 'SHT85';
        $checked_senssecond_4 = 'checked="checked"';
    }
    else {
        $checked_senssecond_4 = '';
    }
    if ($sensorsecondtype == 5) {
        $sensorsecondname = 'SHT3x';
        $checked_senssecond_5 = 'checked="checked"';
    }
    else {
        $checked_senssecond_5 = '';
    }
    if ($sensorsecondtype == 6) {
        $sensorsecondname = 'MiThermometer';
        $checked_senssecond_6 = 'checked="checked"';
    }
    else {
        $checked_senssecond_6 = '';
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
    if ($uv_modus == 0) {
        $checked_uv_0 = 'checked="checked"';
    }
    else {
        $checked_uv_0 = '';
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
    if ($light_modus == 0) {
        $checked_light_0 = 'checked="checked"';
    }
    else {
        $checked_light_0 = '';
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

    if ($tft_display_type == 1) {
        $checked_tft_display_type_1 = 'checked="checked"';
    }
    else {
        $checked_tft_display_type_1 = '';
    }
    if ($tft_display_type == 2) {
        $checked_tft_display_type_2 = 'checked="checked"';
    }
    else {
        $checked_tft_display_type_2 = '';
    }
    if ($tft_display_type == 3) {
        $checked_tft_display_type_3 = 'checked="checked"';
    }
    else {
        $checked_tft_display_type_3 = '';
    }
    
    logger('DEBUG', 'read_config_db performed');
?>