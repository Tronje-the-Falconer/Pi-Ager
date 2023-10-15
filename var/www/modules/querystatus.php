<?php
    include 'names.php';
    include 'database.php';
    include 'logging.php';
    include 'read_gpio.php';
    include 'funclib.php';
    # Language festlegen
    
    #### BEGIN Language from DB

    $language = intval(get_table_value($config_settings_table, $language_key));
    if ($language == 1) {
        $language = 'de_DE.utf8';
    }
    elseif ($language == 2) {
        $language = 'en_GB.utf8';
    }
    setlocale(LC_ALL, $language);
    
    # Set the text domain as 'messages'
    $domain = 'pi-ager';
    bindtextdomain($domain, "/var/www/locale"); 
    textdomain($domain);    
    
    #### END Language from DB 
    
    // get pi-ager status to refresh statusboard on index.php via ajax
    
    $status_piager = intval(get_table_value($current_values_table, $status_piager_key));
    $status_defrost = intval(get_table_value($current_values_table, $status_defrost_key));
    $modus = intval(get_table_value($config_settings_table, $modus_key));
    $status_scale1 = intval(get_table_value($current_values_table, $status_scale1_key));
	$status_scale2 = intval(get_table_value($current_values_table, $status_scale2_key));
    $scale1_thread_alive = intval(get_table_value($current_values_table, $scale1_thread_alive_key));
    $scale2_thread_alive = intval(get_table_value($current_values_table, $scale2_thread_alive_key));
    $sensor_temperature = number_format(floatval(get_table_value($current_values_table, $sensor_temperature_key)), 1, '.', '');
    $sensor_humidity = round(get_table_value($current_values_table,$sensor_humidity_key), 0);
    $desired_maturity = read_agingtable_name_from_config();
    
    $bus = intval(get_table_value($config_settings_table, $sensorbus_key));
    $sensorsecondtype = intval(get_table_value($config_settings_table,$sensorsecondtype_key));
    $dehumidifier_modus = intval(get_table_value($config_settings_table,$dehumidifier_modus_key));
    $dewpoint_check = intval(get_table_value($config_settings_table, $dewpoint_check_key));
    $sensor_humidity_abs = get_table_value($current_values_table, $sensor_humidity_abs_key);
    $sensor_extern_humidity_abs = get_table_value($current_values_table, $sensor_extern_humidity_abs_key);
    $status_humidity_check = intval(get_table_value($current_values_table, $status_humidity_check_key));
    
    $uptime_row = get_table_row($time_meter_table, 1);
    $uv_uptime_seconds = $uptime_row[$uv_light_seconds_field];
    $uv_uptime_formatted = convert_seconds_to_hours($uv_uptime_seconds, 2);
    $pi_ager_uptime_seconds = $uptime_row[$pi_ager_seconds_field];
    $pi_ager_uptime_formatted = convert_seconds_to_hours($pi_ager_uptime_seconds, 2);
     
    $current_values = array();

    $current_values['grepmain'] = $grepmain;
    $current_values['modus'] = $modus;
    $current_values['status_piager'] = $status_piager;
    $current_values['grepagingtable'] = $grepagingtable;    
    $current_values['grepbackup'] = $grepbackup;
    $current_values['scale1_thread_alive'] = $scale1_thread_alive;
    $current_values['scale2_thread_alive'] = $scale2_thread_alive;
    $current_values['read_gpio_voltage'] = $read_gpio_voltage;
    $current_values['read_gpio_digital_switch'] = $read_gpio_digital_switch; 
    $current_values['read_gpio_battery'] = $read_gpio_battery;

    $current_values['cooler_on_off_png'] = $cooler_on_off_png;
    $current_values['heater_on_off_png'] = $heater_on_off_png;
    $current_values['circulating_on_off_png'] = $circulating_on_off_png;
    $current_values['exhausting_on_off_png'] = $exhausting_on_off_png;
    $current_values['humidifier_on_off_png'] = $humidifier_on_off_png;
    $current_values['uv_on_off_png'] = $uv_on_off_png;
    $current_values['light_on_off_png'] = $light_on_off_png;
    $current_values['dehumidifier_on_off_png'] = $dehumidifier_on_off_png;
        
    $current_values['agingtable_on_off_png'] = $agingtable_on_off_png;
    $current_values['pi_ager_on_off_png'] = $pi_ager_on_off_png;
    
//    $current_values['sensor_temperature'] = $sensor_temperature;
//    $current_values['sensor_humidity'] = $sensor_humidity;
    
    if ($read_gpio_voltage == 1){
        $current_values['powersupply_text'] = _('power supply ok');
        $current_values['powersupply_img'] = 'images/icons/5v_42x42.png';
        $current_values['powersupply_text_color'] = 'black';
    }
    else {
        $current_values['powersupply_text'] = _('no power supply! batterymode');
        $current_values['powersupply_img'] = 'images/icons/5v_fail_42x42.png';
        $current_values['powersupply_text_color'] = 'red';
    }
    
    if ($read_gpio_battery == 1){
        $current_values['battery_text'] = _('battery voltage ok');
        $current_values['battery_img'] = 'images/icons/battery_42x42.png';
        $current_values['battery_text_color'] = 'black';
    }
    else {
        $current_values['battery_text'] = _('battery voltage low !!');
        $current_values['battery_img'] = 'images/icons/battery_fail_42x42.png';
        $current_values['battery_text_color'] = 'red';
    }
    
    if ($read_gpio_digital_switch == 0){
        $current_values['switch_text'] = _('Switch is on');
        $current_values['switch_img'] = 'images/icons/status_on_20x20.png';
    }
    else {
        $current_values['switch_text'] = _('Switch is off');
        $current_values['switch_img'] = 'images/icons/status_off_20x20.png';
    }
    
    if ($status_defrost == 1){
        $current_values['defrost_text'] = _('Defrost in progress');
        $current_values['defrost_img'] = 'images/icons/status_on_20x20.png';
    }
    else {
        $current_values['defrost_text'] = _('Defrost currently off');
        $current_values['defrost_img'] = 'images/icons/status_off_20x20.png';
    }

    if ($modus  ==  0) {
        $modus_name = '- '. _('cooling');
    }

    if ($modus  ==  1) {
        $modus_name = '- '. _('cooling') .'<br>- '. _('humidify');
    }

    if ($modus == 2) {
        $modus_name = '- '. _('heating').'<br>- '. _('humidify');
    }

    if ($modus == 3) {
        $modus_name = '- '. _('cooling').'<br>- '. _('heating') .'<br>- '._('humidify');
    }

    if ($modus == 4) {
        $modus_name = '- '. _('cooling').'<br>- '._('heating').'<br>- '._('humidify').'<br>- '._('dehumidify').'<br>- '._('circulating air').'<br>- '._('exhausting air');
    }
   
    if ($grepmain == 0){
        $current_values['main_status_text'] = strtoupper(_('see settings'));
    }
    elseif ($grepmain != 0 and $status_piager == 0){
        $current_values['main_status_text'] = strtoupper(_('off'));
    }
    else {    // ($grepmain != 0 and $status_piager == 1){
        $current_values['main_status_text'] = $modus_name;
    }
    
    if ($grepmain == 0 || $status_piager == 0) {
        $sensor_temperature = '--.-';
        $sensor_humidity = '--.-';
    }
    
    $cooling_hysteresis = get_table_value($config_settings_table ,$cooling_hysteresis_key);
    $heating_hysteresis = get_table_value($config_settings_table ,$heating_hysteresis_key);
    $internal_temperature = get_table_value($current_values_table, $sensor_temperature_key);
    $external_temperature = get_table_value($current_values_table, $sensor_extern_temperature_key);
    
    if ($external_temperature !== null && $internal_temperature !== null && $external_temperature < $sensor_temperature && ($modus == 3 || $modus == 4)) {  // heating in mode 3 or 4
        $switch_on_cooling_compressor = $heating_hysteresis/2;
        $switch_off_cooling_compressor = -$heating_hysteresis/2;
        $switch_on_heater = $cooling_hysteresis/2;
        $switch_off_heater = -$cooling_hysteresis/2;
    }
    else {
        $switch_on_cooling_compressor = $cooling_hysteresis/2;
        $switch_off_cooling_compressor = -$cooling_hysteresis/2;
        $switch_on_heater = $heating_hysteresis/2;
        $switch_off_heater = -$heating_hysteresis/2;
    }
    
    $humidifier_hysteresis = intval(get_table_value($config_settings_table,$humidifier_hysteresis_key));
    $dehumidifier_hysteresis = intval(get_table_value($config_settings_table,$dehumidifier_hysteresis_key));
    $hysteresis_offset = round(get_table_value($config_settings_table,$hysteresis_offset_key), 1);

    $setpoint_temperature = round(get_table_value($config_settings_table,$setpoint_temperature_key), 1);
    $setpoint_humidity = round(get_table_value($config_settings_table,$setpoint_humidity_key), 1);  
    $saturation_point = intval(get_table_value($config_settings_table,$saturation_point_key));
    
    if ($modus == 0 || $modus == 1) {
        $current_values['mod_type_line1'] = 'images/icons/cooling_42x42.png';
        $current_values['mod_stat_line1'] = $cooler_on_off_png;
        $current_values['mod_name_line1'] = strtoupper(_('cooler'));
        $current_values['mod_current_line1'] = $sensor_temperature;
        $current_values['mod_setpoint_line1'] = number_format(floatval($setpoint_temperature), 1, '.', '');
        $current_values['mod_on_line1'] = number_format(floatval($setpoint_temperature + $switch_on_cooling_compressor), 2, '.', '');
        $current_values['mod_off_line1'] = number_format(floatval($setpoint_temperature + $switch_off_cooling_compressor), 2, '.', '');
    }
    else if ($modus == 2) {
        $current_values['mod_type_line1'] = 'images/icons/heating_42x42.png';
        $current_values['mod_stat_line1'] = $heater_on_off_png;
        $current_values['mod_name_line1'] = strtoupper(_('heater'));
        $current_values['mod_current_line1'] = $sensor_temperature;
        $current_values['mod_setpoint_line1'] = number_format(floatval($setpoint_temperature), 1, '.', '');
        $current_values['mod_on_line1'] = number_format(floatval($setpoint_temperature - $switch_on_cooling_compressor), 2, '.', '');
        $current_values['mod_off_line1'] = number_format(floatval($setpoint_temperature - $switch_off_cooling_compressor), 2, '.', '');
    }
    else {
        $current_values['mod_type_line1'] = 'images/icons/cooling_42x42.png';
        $current_values['mod_stat_line1'] = $cooler_on_off_png;
        $current_values['mod_name_line1'] = strtoupper(_('cooler'));
        $current_values['mod_current_line1'] = $sensor_temperature;
        $current_values['mod_setpoint_line1'] = number_format(floatval($setpoint_temperature), 1, '.', '');
        $current_values['mod_on_line1'] = number_format(floatval($setpoint_temperature + $switch_on_cooling_compressor), 2, '.', '');
        $current_values['mod_off_line1'] = number_format(floatval($setpoint_temperature + $switch_off_cooling_compressor), 2, '.', '');
        
        $current_values['mod_type_line2'] = 'images/icons/heating_42x42.png';
        $current_values['mod_stat_line2'] = $heater_on_off_png;
        $current_values['mod_name_line2'] = strtoupper(_('heater'));
        $current_values['mod_current_line2'] = $sensor_temperature;
        $current_values['mod_setpoint_line2'] = number_format(floatval($setpoint_temperature), 1, '.', '');
        $current_values['mod_on_line2'] = number_format(floatval($setpoint_temperature - $switch_on_heater), 2, '.', '');
        $current_values['mod_off_line2'] = number_format(floatval($setpoint_temperature - $switch_off_heater), 2, '.', '');  
    }

    if ($modus == 1 || $modus == 2 || $modus == 3) {
        $current_values['mod_type_line3'] = 'images/icons/humidification_42x42.png';
        $current_values['mod_stat_line3'] = $humidifier_on_off_png;
        $current_values['mod_name_line3'] = strtoupper(_('humidification'));
        $current_values['mod_current_line3'] = $sensor_humidity;
        $current_values['mod_setpoint_line3'] = $setpoint_humidity;
        $current_values['mod_on_line3'] = eval_switch_on_humidity( $setpoint_humidity, $humidifier_hysteresis, $hysteresis_offset );
        $current_values['mod_off_line3'] = eval_switch_off_humidity( $setpoint_humidity, $humidifier_hysteresis, $hysteresis_offset, $saturation_point );
    }
    else {
        $current_values['mod_type_line3'] = 'images/icons/humidification_42x42.png';
        $current_values['mod_stat_line3'] = $humidifier_on_off_png;
        $current_values['mod_name_line3'] = strtoupper(_('humidification'));
        $current_values['mod_current_line3'] = $sensor_humidity;
        $current_values['mod_setpoint_line3'] = $setpoint_humidity;
        $current_values['mod_on_line3'] = eval_switch_on_humidity($setpoint_humidity, $humidifier_hysteresis, $hysteresis_offset );
        $current_values['mod_off_line3'] = eval_switch_off_humidity($setpoint_humidity, $humidifier_hysteresis, $hysteresis_offset, $saturation_point);
        
        $current_values['mod_type_line4'] = 'images/icons/exhausting_42x42.png';
        $current_values['mod_stat_line4'] = $exhausting_on_off_png;
        $current_values['mod_name_line4'] = strtoupper(_('exhausting'));
//        $current_values['mod_current_line4'] = $sensor_humidity;
//        $current_values['mod_setpoint_line4'] = $setpoint_humidity;
//        $current_values['mod_on_line4'] = ((($setpoint_humidity + $switch_on_humidifier) <= 100) ? ($setpoint_humidity + $switch_on_humidifier) : 100);
//        $current_values['mod_off_line4'] = ((($setpoint_humidity + $switch_off_humidifier) <= 100) ? ($setpoint_humidity + $switch_off_humidifier) : 100);     
        
        $current_values['mod_type_line5'] = 'images/icons/dehumidification_42x42.png';
        $current_values['mod_stat_line5'] = $dehumidifier_on_off_png;
        $current_values['mod_name_line5'] = strtoupper(_('dehumidification'));
        $current_values['mod_current_line5'] = $sensor_humidity;
        $current_values['mod_setpoint_line5'] = $setpoint_humidity;
        $current_values['mod_on_line5'] = eval_switch_on_dehumidity($setpoint_humidity, $dehumidifier_hysteresis, $hysteresis_offset, $saturation_point ); 
        $current_values['mod_off_line5'] = eval_switch_off_dehumidity($setpoint_humidity,  $dehumidifier_hysteresis, $hysteresis_offset );

        if ($sensorsecondtype != 0) {  // show abs. humidity check aktive only with second sensor
            if ($dehumidifier_modus == 3) {             // only dehumidification
                $current_values['mod_stat_line6'] = 'images/icons/status_off_20x20.png';
            }
            else {
                // if ($sensor_humidity_abs < $sensor_extern_humidity_abs) {
                if ($status_humidity_check == 1) {    
                    $current_values['mod_stat_line6'] = 'images/icons/status_on_red_20x20.png';
                }
                else {
                    $current_values['mod_stat_line6'] = 'images/icons/status_on_20x20.png';
                }
            }
            $strtemp = mb_strtoupper(_('abs. humidity check') . ': ');
            if ($dewpoint_check == 1) {
                $strtemp = $strtemp . strtoupper(_('on'));
            }
            else {
                $strtemp = $strtemp . strtoupper(_('off'));
            }
            $current_values['mod_name_line6'] = $strtemp;  
        }
        else {
            $current_values['mod_stat_line6'] = 'images/icons/status_off_20x20.png';
            $current_values['mod_name_line6'] = '';  
        }
    }
    
    if ($grepagingtable){
        $maturity_type = $desired_maturity;
    }
    else {
        $maturity_type = _('none');
    }
    $current_values['maturity_type'] = $maturity_type;

    // circulation air timer
    $circulation_air_duration = round(get_table_value($config_settings_table, $circulation_air_duration_key), 1)/60;
    $circulation_air_period = round(get_table_value($config_settings_table, $circulation_air_period_key), 1)/60;
    if ($circulation_air_duration == 0) {
        $current_values['circulation_air_duration_class'] = 'transpng';
    }
    else {
        $current_values['circulation_air_duration_class'] = '';
    }
    $current_values['circulating_on_off_png'] = $circulating_on_off_png;
    $timer_name_line1 = strtoupper(_('circulating air'));
    if ($circulation_air_duration > 0 && $circulation_air_period > 0) {
        $timer_name_line1 .= ', ' . strtoupper(_('timer on'));
    }
    elseif ($circulation_air_period == 0) {
        $timer_name_line1 .= ' ' . strtoupper(_('always on'));
    }
    elseif ($circulation_air_duration == 0) {
        $timer_name_line1 .= ', ' . strtoupper(_('timer off'));
    }
    $current_values['timer_name_line1'] = $timer_name_line1;
    $current_values['timer_period_line1'] = $circulation_air_period . ' ' . _('minutes');    
    $current_values['timer_dur_line1'] = $circulation_air_duration . ' ' . _('minutes');
    
    // exhaust air timer
    $exhausting_air_duration = round(get_table_value($config_settings_table, $exhaust_air_duration_key), 1)/60;
    $exhausting_air_period = round(get_table_value($config_settings_table, $exhaust_air_period_key), 1)/60;
    if ($exhausting_air_duration == 0) {
        $current_values['exhausting_air_duration_class'] = 'transpng';
    }
    else {
        $current_values['exhausting_air_duration_class'] = '';
    }
    $current_values['exhausting_on_off_png'] = $exhausting_on_off_png;
    $timer_name_line2 = strtoupper(_('exhausting air'));
    if ($exhausting_air_duration > 0 && $exhausting_air_period > 0) {
        $timer_name_line2 .= ', ' . strtoupper(_('timer on'));
    }
    elseif ($exhausting_air_period == 0) {
        $timer_name_line2 .= ' ' . strtoupper(_('always on'));
    }
    elseif ($exhausting_air_duration == 0) {
        $timer_name_line2 .= ', ' . strtoupper(_('timer off'));
    }
    $current_values['timer_name_line2'] = $timer_name_line2;
    $current_values['timer_period_line2'] = $exhausting_air_period . ' ' . _('minutes');    
    $current_values['timer_dur_line2'] = $exhausting_air_duration . ' ' . _('minutes');
        
    // uv-light timer
    $uv_duration = get_table_value($config_settings_table, $uv_duration_key)/60;
    $uv_period = get_table_value($config_settings_table, $uv_period_key)/60;
    if ($uv_duration == 0) {
        $current_values['uv_duration_class'] = 'transpng';
    }
    else {
        $current_values['uv_duration_class'] = '';
    }
    $status_uv_manual = get_table_value($current_values_table, $status_uv_manual_key);
    if ($status_uv_manual == 0) {
        $current_values['timer_stat_line3'] = 'images/icons/status_off_manual_20x20.png';
    }
    else {
        $current_values['timer_stat_line3'] = $uv_on_off_png;
    }
    $uv_modus = get_table_value($config_settings_table, $uv_modus_key);    
    $timer_name_line3 = strtoupper(_('uv-light'));
    if ($uv_modus == 0) {
        $timer_name_line3 .= ' ' . strtoupper(_('timer off'));
    }
    elseif ($uv_modus == 1) {
        $timer_name_line3 .= ' ' . strtoupper(_('timer on'));
    }
    if ($status_uv_manual == 0) {
        $timer_name_line3 .= ', ' . strtoupper(_('manual switch off'));
    }    
    $current_values['timer_name_line3'] = $timer_name_line3;
    $current_values['timer_period_line3'] = $uv_period . ' ' . _('minutes');    
    $current_values['timer_dur_line3'] = $uv_duration . ' ' . _('minutes');

        
    // light timer
    $light_duration = get_table_value($config_settings_table,$light_duration_key)/60;
    $light_period = get_table_value($config_settings_table,$light_period_key)/60;
    if ($light_duration == 0) {
        $current_values['light_duration_class'] = 'transpng';
    }
    else {
        $current_values['light_duration_class'] = '';
    }
    $status_light_manual = get_table_value($current_values_table, $status_light_manual_key);
    if ($status_light_manual == 1) {
        $current_values['timer_stat_line4'] = '/images/icons/status_on_manual_20x20.png';
    }
    else {
        $current_values['timer_stat_line4'] = $light_on_off_png;
    }
    $light_modus = get_table_value($config_settings_table, $light_modus_key);
    $timer_name_line4 = strtoupper(_('light'));
    if ($light_modus == 0) {
        $timer_name_line4 .= ' ' . strtoupper(_('timer off'));
    }
    elseif ($light_modus == 1) {
        $timer_name_line4 .= ' ' . strtoupper(_('timer on'));
    }
    if ($status_light_manual == 1) {
        $timer_name_line4 .= ', ' . strtoupper(_('manual switch on'));
    }    
    $current_values['timer_name_line4'] = $timer_name_line4;
    $current_values['timer_period_line4'] = $light_period . ' ' . _('minutes');    
    $current_values['timer_dur_line4'] = $light_duration . ' ' . _('minutes');

    
    if ($scale1_thread_alive == 0){
        $current_values['scale1_img_id'] = 'images/icons/scale_fail_42x42.gif';
        $current_values['scale1_onoff_status_id'] = 'images/icons/status_off_20x20.png';
        $current_values['scale1_status_text_id'] = strtoupper(_('see settings'));
    }
    elseif ($status_scale1 == 0){
        $current_values['scale1_img_id'] = 'images/icons/scale_42x42.png';
        $current_values['scale1_onoff_status_id'] = 'images/icons/status_off_20x20.png';
        $current_values['scale1_status_text_id'] = strtoupper(_('scale1'));
    }
    else {
        $current_values['scale1_img_id'] = 'images/icons/scale_42x42.gif';
        $current_values['scale1_onoff_status_id'] = 'images/icons/status_on_20x20.png';
        $current_values['scale1_status_text_id'] = strtoupper(_('scale1'));
    }

    if ($scale2_thread_alive == 0){
        $current_values['scale2_img_id'] = 'images/icons/scale_fail_42x42.gif';
        $current_values['scale2_onoff_status_id'] = 'images/icons/status_off_20x20.png';
        $current_values['scale2_status_text_id'] = strtoupper(_('see settings'));
    }
    elseif ($status_scale2 == 0){
        $current_values['scale2_img_id'] = 'images/icons/scale_42x42.png';
        $current_values['scale2_onoff_status_id'] = 'images/icons/status_off_20x20.png';
        $current_values['scale2_status_text_id'] = strtoupper(_('scale2'));
    }
    else {
        $current_values['scale2_img_id'] = 'images/icons/scale_42x42.gif';
        $current_values['scale2_onoff_status_id'] = 'images/icons/status_on_20x20.png';
        $current_values['scale2_status_text_id'] = strtoupper(_('scale2'));
    }
  
    $current_values['uv_uptime_formatted'] = $uv_uptime_formatted;
    $current_values['pi_ager_uptime_formatted'] = $pi_ager_uptime_formatted;
    
    echo json_encode($current_values);
    logger('DEBUG', 'querystatus finished');
?>