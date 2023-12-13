<?php
    # read all settings from DB for admin page
    $status_uv_manual = intval(get_table_value($current_values_table, $status_uv_manual_key));
    
    # sensor selection
    $sensortype = intval(get_table_value($config_settings_table,$sensortype_key));
    $sensorsecondtype = intval(get_table_value($config_settings_table,$sensorsecondtype_key)); 
    $ATC_device_name = get_table_value_from_field('atc_device_name', NULL, 'name');
    $bus = intval(get_table_value($config_settings_table,$sensorbus_key));
    if ($bus  ==  0) {
        $bus_name = 'i2c';
        $checked_bus_0 = 'checked="checked"';
        $checked_bus_1 = '';
    }
    if ($bus  ==  1) {
        $bus_name = '1wire';
        $checked_bus_1 = 'checked="checked"';
        $checked_bus_0 = '';
    }
    
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
    if ($sensortype == 6) {
        $sensorname = 'AHT2x';
        $checked_sens_6 = 'checked="checked"';
        $sens_second_active = '';
    }
    else {
        $checked_sens_6 = '';
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
        $sensorsecondname = 'AHT2x';
        $checked_senssecond_6 = 'checked="checked"';
    }
    else {
        $checked_senssecond_6 = '';
    }    
    if ($sensorsecondtype == 7) {
        $sensorsecondname = 'MiThermometer';
        $checked_senssecond_7 = 'checked="checked"';
    }
    else {
        $checked_senssecond_7 = '';
    }    

    # scales
    
    $referenceunit_scale1 = number_format(floatval(get_table_value($settings_scale1_table,$referenceunit_key)), 1, '.', '');
    $offset_scale1 = number_format(floatval(get_table_value($settings_scale1_table, $offset_key)), 1, '.', '');
    $measuring_interval_scale1 = get_table_value($settings_scale1_table,$scale_measuring_interval_key);
    $measuring_duration_scale1 = get_table_value($settings_scale1_table,$measuring_duration_key);
    $saving_period_scale1 = get_table_value($settings_scale1_table,$saving_period_key);
    $samples_scale1 = get_table_value($settings_scale1_table,$samples_key);
    $spikes_scale1 = get_table_value($settings_scale1_table,$spikes_key);
    $take_off_weight_scale1 = intval(get_table_value($config_settings_table, $take_off_weight_scale1_key));
    
    $referenceunit_scale2 = number_format(floatval(get_table_value($settings_scale2_table,$referenceunit_key)), 1, '.', '');
    $offset_scale2 = number_format(floatval(get_table_value($settings_scale2_table, $offset_key)), 1, '.', '');    
    $measuring_interval_scale2 = get_table_value($settings_scale2_table,$scale_measuring_interval_key);
    $measuring_duration_scale2 = get_table_value($settings_scale2_table,$measuring_duration_key);
    $saving_period_scale2 = get_table_value($settings_scale2_table,$saving_period_key);
    $samples_scale2 = get_table_value($settings_scale2_table,$samples_key);
    $spikes_scale2 = get_table_value($settings_scale2_table,$spikes_key);
    $take_off_weight_scale2 = intval(get_table_value($config_settings_table, $take_off_weight_scale2_key));    

    # meat sensors
    
    $meat1_sensortype = get_table_value($config_settings_table, $meat1_sensortype_key);
    $meat2_sensortype = get_table_value($config_settings_table, $meat2_sensortype_key);   
    $meat3_sensortype = get_table_value($config_settings_table, $meat3_sensortype_key);   
    $meat4_sensortype = get_table_value($config_settings_table, $meat4_sensortype_key);   
    
    # language 
    $language = intval(get_table_value($config_settings_table,$language_key));
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

    # switch control
    $switch_control_uv_light = intval(get_table_value($config_settings_table, $switch_control_uv_light_key));
    $switch_control_light = intval(get_table_value($config_settings_table, $switch_control_light_key));
    
    # tft display 
    $tft_display_type = intval(get_table_value($config_settings_table, $tft_display_type_key));
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

   logger('DEBUG', 'read_current_db performed');
?>
