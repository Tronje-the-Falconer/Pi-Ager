<?php
    include 'names.php';
    include 'database.php';
    include 'logging.php';
    include 'read_gpio.php';
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
    $modus = intval(get_table_value($config_settings_table, $modus_key));
    $status_scale1 = intval(get_table_value($current_values_table, $status_scale1_key));
	$status_scale2 = intval(get_table_value($current_values_table, $status_scale2_key));
    $sensor_temperature = number_format(floatval(get_table_value($current_values_table, $sensor_temperature_key)), 1, '.', '');
    $sensor_humidity = round(get_table_value($current_values_table,$sensor_humidity_key), 0);
    $desired_maturity = read_agingtable_name_from_config();
    
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

    $switch_on_cooling_compressor = round(get_table_value($config_settings_table,$switch_on_cooling_compressor_key), 1);
    $switch_off_cooling_compressor = round(get_table_value($config_settings_table,$switch_off_cooling_compressor_key), 1);
    $switch_on_humidifier = get_table_value($config_settings_table,$switch_on_humidifier_key);
    $switch_off_humidifier = get_table_value($config_settings_table,$switch_off_humidifier_key);
    $setpoint_temperature = round(get_table_value($config_settings_table,$setpoint_temperature_key), 1);
    $setpoint_humidity = round(get_table_value($config_settings_table,$setpoint_humidity_key), 1);  
    
    if ($modus == 0 || $modus == 1) {
        $current_values['mod_type_line1'] = 'images/icons/cooling_42x42.png';
        $current_values['mod_stat_line1'] = $cooler_on_off_png;
        $current_values['mod_name_line1'] = strtoupper(_('cooler'));
        $current_values['mod_current_line1'] = $sensor_temperature;
        $current_values['mod_setpoint_line1'] = $setpoint_temperature;
        $current_values['mod_on_line1'] = $setpoint_temperature + $switch_on_cooling_compressor;
        $current_values['mod_off_line1'] = $setpoint_temperature + $switch_off_cooling_compressor;
    }
    else if ($modus == 2) {
        $current_values['mod_type_line1'] = 'images/icons/heating_42x42.png';
        $current_values['mod_stat_line1'] = $heater_on_off_png;
        $current_values['mod_name_line1'] = strtoupper(_('heater'));
        $current_values['mod_current_line1'] = $sensor_temperature;
        $current_values['mod_setpoint_line1'] = $setpoint_temperature;
        $current_values['mod_on_line1'] = $setpoint_temperature - $switch_on_cooling_compressor;
        $current_values['mod_off_line1'] = $setpoint_temperature - $switch_off_cooling_compressor;
    }
    else {
        $current_values['mod_type_line1'] = 'images/icons/cooling_42x42.png';
        $current_values['mod_stat_line1'] = $cooler_on_off_png;
        $current_values['mod_name_line1'] = strtoupper(_('cooler'));
        $current_values['mod_current_line1'] = $sensor_temperature;
        $current_values['mod_setpoint_line1'] = $setpoint_temperature;
        $current_values['mod_on_line1'] = $setpoint_temperature + $switch_on_cooling_compressor;
        $current_values['mod_off_line1'] = $setpoint_temperature + $switch_off_cooling_compressor;
        
        $current_values['mod_type_line2'] = 'images/icons/heating_42x42.png';
        $current_values['mod_stat_line2'] = $heater_on_off_png;
        $current_values['mod_name_line2'] = strtoupper(_('heater'));
        $current_values['mod_current_line2'] = $sensor_temperature;
        $current_values['mod_setpoint_line2'] = $setpoint_temperature;
        $current_values['mod_on_line2'] = $setpoint_temperature - $switch_on_cooling_compressor;
        $current_values['mod_off_line2'] = $setpoint_temperature - $switch_off_cooling_compressor;        
    }

    if ($modus == 1 || $modus == 2 || $modus == 3) {
        $current_values['mod_type_line3'] = 'images/icons/humidification_42x42.png';
        $current_values['mod_stat_line3'] = $humidifier_on_off_png;
        $current_values['mod_name_line3'] = strtoupper(_('humidification'));
        $current_values['mod_current_line3'] = $sensor_humidity;
        $current_values['mod_setpoint_line3'] = $setpoint_humidity;
        $current_values['mod_on_line3'] = $setpoint_humidity - $switch_on_humidifier;
        $current_values['mod_off_line3'] = $setpoint_humidity - $switch_off_humidifier;
    }
    else {
        $current_values['mod_type_line3'] = 'images/icons/humidification_42x42.png';
        $current_values['mod_stat_line3'] = $humidifier_on_off_png;
        $current_values['mod_name_line3'] = strtoupper(_('humidification'));
        $current_values['mod_current_line3'] = $sensor_humidity;
        $current_values['mod_setpoint_line3'] = $setpoint_humidity;
        $current_values['mod_on_line3'] = $setpoint_humidity - $switch_on_humidifier;
        $current_values['mod_off_line3'] = $setpoint_humidity - $switch_off_humidifier;
        
        $current_values['mod_type_line4'] = 'images/icons/exhausting_42x42.png';
        $current_values['mod_stat_line4'] = $exhausting_on_off_png;
        $current_values['mod_name_line4'] = strtoupper(_('exhausting'));
        $current_values['mod_current_line4'] = $sensor_humidity;
        $current_values['mod_setpoint_line4'] = $setpoint_humidity;
        $current_values['mod_on_line4'] = ((($setpoint_humidity + $switch_on_humidifier) <= 100) ? ($setpoint_humidity + $switch_on_humidifier) : 100);
        $current_values['mod_off_line4'] = ((($setpoint_humidity + $switch_off_humidifier) <= 100) ? ($setpoint_humidity + $switch_off_humidifier) : 100);     
        
        $current_values['mod_type_line5'] = 'images/icons/dehumidification_42x42.png';
        $current_values['mod_stat_line5'] = $dehumidifier_on_off_png;
        $current_values['mod_name_line5'] = strtoupper(_('dehumidification'));
        $current_values['mod_current_line5'] = $sensor_humidity;
        $current_values['mod_setpoint_line5'] = $setpoint_humidity;
        $current_values['mod_on_line5'] = ((($setpoint_humidity + $switch_on_humidifier) <= 100) ? ($setpoint_humidity + $switch_on_humidifier) : 100);
        $current_values['mod_off_line5'] = ((($setpoint_humidity + $switch_off_humidifier) <= 100) ? ($setpoint_humidity + $switch_off_humidifier) : 100);         
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
  
    echo json_encode($current_values);
    logger('DEBUG', 'querystatus finished');
?>