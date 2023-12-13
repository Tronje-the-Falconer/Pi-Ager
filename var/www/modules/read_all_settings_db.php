<?php
    #Prüfen ob main läuft
    $exec_retval = exec('pgrep -a python3 | grep main.py');
    $status_main = 0;
    if ($exec_retval != '') {
        $status_main = 1;
    }
    
    $status_agingtable = intval(get_table_value($current_values_table, $status_agingtable_key));
    $status_piager = intval(get_table_value($current_values_table,$status_piager_key));
    
    $exec_retval = shell_exec('ps ax | grep -v grep | grep pi-ager_backup.sh');
    $status_backup = 0;
    if ($exec_retval != '') {
        $status_backup = 1;
    }
    
    # check if scale threads are alive
    $scale1_thread_alive = intval(get_table_value($current_values_table, $scale1_thread_alive_key));
    $scale2_thread_alive = intval(get_table_value($current_values_table, $scale2_thread_alive_key));
    $aging_thread_alive = intval(get_table_value($current_values_table, $aging_thread_alive_key));
    
    # check if scales already calibrated
    $scale1_refunit = intval(get_table_value($settings_scale1_table, $referenceunit_key));
    $scale2_refunit = intval(get_table_value($settings_scale2_table, $referenceunit_key));
    $status_scale1 = intval(get_table_value($current_values_table, $status_scale1_key));
    $status_scale2 = intval(get_table_value($current_values_table, $status_scale2_key));
    
    $modus = intval(get_table_value($config_settings_table,$modus_key));
    $desired_maturity = read_agingtable_name_from_config();
    
    $setpoint_temperature = number_format(floatval(get_table_value($config_settings_table,$setpoint_temperature_key)), 1, '.', '');
    $setpoint_humidity = round(get_table_value($config_settings_table,$setpoint_humidity_key), 0);
    $circulation_air_duration = round(get_table_value($config_settings_table,$circulation_air_duration_key), 1)/60;
    $circulation_air_period = round(get_table_value($config_settings_table,$circulation_air_period_key), 1)/60;
    $exhaust_air_duration = round(get_table_value($config_settings_table,$exhaust_air_duration_key), 1)/60;
    $exhaust_air_period = round(get_table_value($config_settings_table,$exhaust_air_period_key), 1)/60;

    $modus_name = '';
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
    
    # settings for config.php
    # temperature control
    $cooling_hysteresis = number_format(floatval(get_table_value($config_settings_table, $cooling_hysteresis_key)), 1, '.', '');
    $heating_hysteresis = number_format(floatval(get_table_value($config_settings_table, $heating_hysteresis_key)), 1, '.', '');
    $delay_cooler = intval(get_table_value($config_settings_table, $delay_cooler_key));   
    
    # humidification control
    $humidifier_hysteresis = intval(get_table_value($config_settings_table,$humidifier_hysteresis_key));
    $dehumidifier_hysteresis = intval(get_table_value($config_settings_table,$dehumidifier_hysteresis_key));
    $hysteresis_offset = number_format(floatval(get_table_value($config_settings_table,$hysteresis_offset_key)), 1, '.', '');
    $saturation_point = intval(get_table_value($config_settings_table, $saturation_point_key));                                                                                       
    $delay_humidify = intval(get_table_value($config_settings_table,$delay_humidify_key)); 
    
    # dehumidification
    $dehumidifier_modus = intval(get_table_value($config_settings_table,$dehumidifier_modus_key));
    $dewpoint_check = intval(get_table_value($config_settings_table, $dewpoint_check_key));
    $sensorsecondtype = intval(get_table_value($config_settings_table, $sensorsecondtype_key));
    
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
    
    # uv light control
    $uv_modus = intval(get_table_value($config_settings_table,$uv_modus_key));
    $uv_duration = intval(get_table_value($config_settings_table,$uv_duration_key)/60);
    $uv_period = intval(get_table_value($config_settings_table,$uv_period_key)/60);
    $switch_on_uv_hour = intval(get_table_value($config_settings_table,$switch_on_uv_hour_key));
    $switch_on_uv_minute = intval(get_table_value($config_settings_table,$switch_on_uv_minute_key));
    $uv_check = intval(get_table_value($config_settings_table,$uv_check_key));
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
    
    # light control
    $light_modus = intval(get_table_value($config_settings_table,$light_modus_key));
    $light_duration = intval(get_table_value($config_settings_table,$light_duration_key)/60);
    $light_period = intval(get_table_value($config_settings_table,$light_period_key)/60);
    $switch_on_light_hour = intval(get_table_value($config_settings_table,$switch_on_light_hour_key));
    $switch_on_light_minute = intval(get_table_value($config_settings_table,$switch_on_light_minute_key));    
    
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

    # internal limits for temperature control
    $internal_temperature_low_limit = get_table_value($config_settings_table, $internal_temperature_low_limit_key);
    $internal_temperature_high_limit = get_table_value($config_settings_table, $internal_temperature_high_limit_key);
    $internal_temperature_hysteresis = get_table_value($config_settings_table, $internal_temperature_hysteresis_key);
    
    # humidifier monitoring
    $delay_monitoring_humidifier = intval(get_table_value($config_settings_table,$delay_monitoring_humidifier_key));
    $tolerance_monitoring_humidifier = intval(get_table_value($config_settings_table,$tolerance_monitoring_humidifier_key));
    $check_monitoring_humidifier = intval(get_table_value($config_settings_table,$check_monitoring_humidifier_key));
        
    # shutdown on battlow
    $shutdown_on_batlow = intval(get_table_value($config_settings_table, $shutdown_on_batlow_key));
    
    logger('DEBUG', 'read_settings_db performed');
?>
