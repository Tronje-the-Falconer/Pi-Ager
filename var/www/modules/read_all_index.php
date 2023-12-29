<?php
    # read all settings and current data for index.php
    
    #Prüfen ob main läuft
    $exec_retval = exec('pgrep -a python3 | grep main.py');
    $status_main = 0;
    if ($exec_retval != '') {
        $status_main = 1;
    }
    $status_piager = intval(get_table_value($current_values_table, $status_piager_key));
    $status_scale1 = intval(get_table_value($current_values_table, $status_scale1_key));
    $status_scale2 = intval(get_table_value($current_values_table, $status_scale2_key));
    $sensortype = intval(get_table_value($config_settings_table,$sensortype_key));
    $sensorsecondtype = intval(get_table_value($config_settings_table,$sensorsecondtype_key));
    $MiSensor_battery = get_table_value($current_values_table, $MiSensor_battery_key);
    
    $SUPPORTED_MAIN_SENSOR_TYPES = [1 => "DHT11",
                                     2 => "DHT22",
                                     3 => "SHT75",
                                     4 => "SHT85",
                                     5 => "SHT3x",
                                     6 => "SHT3x-mod",
                                     7 => "AHT1x",
                                     8 => "AHT1x-mod",
                                     9 => "AHT2x",
                                     10 => "AHT30",
                                     11 => "SHT4x-A",
                                     12 => "SHT4x-B",
                                     13 => "SHT4x-C"];
                                     
    $SUPPORTED_SECOND_SENSOR_TYPES = [ 0 => "disabled",
                                        4 => "SHT85",
                                        5 => "SHT3x",
                                        6 => "SHT3x-mod",
                                        7 => "AHT1x",
                                        8 => "AHT1x-mod",
                                        9 => "AHT2x",
                                        10 => "AHT30",
                                        11 => "SHT4x-A",
                                        12 => "SHT4x-B",
                                        13 => "SHT4x-C",
                                        14 => "MiThermometer"];                                
                                     
    $sensorname = $SUPPORTED_MAIN_SENSOR_TYPES[$sensortype];
    $sensorsecondname = $SUPPORTED_SECOND_SENSOR_TYPES[$sensorsecondtype];

    $desired_maturity = read_agingtable_name_from_config();
    // Derzeitige Reife-Art, manuell oder nach Tabelle...
    // $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
    $status_agingtable = intval(get_table_value($current_values_table, $status_agingtable_key));
    if ($status_agingtable == 1){
        $maturity_type = $desired_maturity;
    }
    else {
        $maturity_type = _('none');
    }
    
    $read_gpio_cooling_compressor = shell_exec('gpio -g read ' . $gpio_cooling_compressor);         #Kühlung
    $read_gpio_heater = shell_exec('gpio -g read ' . $gpio_heater);                                 #Heizung
    $read_gpio_humidifier = shell_exec('gpio -g read ' . $gpio_humidifier);                         #Luftbefeuchter
    $read_gpio_circulating_air = shell_exec('gpio -g read ' . $gpio_circulating_air);               #Umluft
    $read_gpio_exhausting_air = shell_exec('gpio -g read ' . $gpio_exhausting_air);                 #Abluft
    $read_gpio_uv = shell_exec('gpio -g read ' . $gpio_uv_light);                                   #UV-Licht
    $read_gpio_light = shell_exec('gpio -g read ' . $gpio_light);                                   #Licht
    $read_gpio_dehumidifier = shell_exec('gpio -g read ' . $gpio_dehumidifier);                     #Entfeuchter    
    $read_gpio_voltage = shell_exec('gpio -g read ' . $gpio_voltage);                               #Spannung anliegend   
    $read_gpio_battery = shell_exec('gpio -g read ' . $gpio_battery);                               #Batterie schwach 
    $read_gpio_digital_switch = shell_exec('gpio -g read ' . $gpio_digital_switch);                 #Schalter
    
    #Schaltzustände setzen
    if($read_gpio_cooling_compressor == 0) {
        $cooler_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_cooling_compressor == 1) {
        $cooler_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($read_gpio_heater == 0) {
        $heater_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_heater == 1) {
        $heater_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($read_gpio_circulating_air == 0) {
        $circulating_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_circulating_air == 1) {
        $circulating_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($read_gpio_exhausting_air == 0) {
        $exhausting_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_exhausting_air == 1) {
        $exhausting_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($read_gpio_humidifier == 0) {
        $humidifier_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_humidifier == 1) {
        $humidifier_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($read_gpio_uv == 0) {
        $uv_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_uv == 1) {
        $uv_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($read_gpio_light == 0) {
        $light_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_light == 1) {
        $light_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($read_gpio_dehumidifier == 0) {
        $dehumidifier_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_dehumidifier == 1) {
        $dehumidifier_on_off_png = 'images/icons/status_off_20x20.png';
        }
        
    if($status_agingtable == 0) {
        $agingtable_on_off_png = 'images/icons/status_off_20x20.png';
    }
    else {
        $agingtable_on_off_png = 'images/icons/status_on_20x20.png';
    }
    if($status_main == 0) {
        $pi_ager_on_off_png = 'images/icons/status_off_20x20.png';
    }
    else {
        $pi_ager_on_off_png = 'images/icons/status_on_20x20.png';
    }
    
    $modus = intval(get_table_value($config_settings_table,$modus_key));
    $modus_name = '';
    if ($modus  ==  0) {
        $modus_name = '- '._('cooling');
    }
    else if ($modus  ==  1) {
        $modus_name = '- '._('cooling').'<br>- '._('humidify');
    }
    else if ($modus == 2) {
        $modus_name = '- '._('heating').'<br>- '._('humidify');
    }
    else if ($modus == 3) {
        $modus_name = '- '._('cooling').'<br>- '._('heating').'<br>- '._('humidify');
    }
    else if ($modus == 4) {
        $modus_name = '- '._('cooling').'<br>- '._('heating').'<br>- '._('humidify').'<br>- '._('dehumidify').'<br>- '._('circulating air').'<br>- '._('exhausting air');
    }

    $sensor_temperature = number_format(floatval(get_table_value($current_values_table,$sensor_temperature_key)), 1, '.', '');
    $setpoint_temperature = number_format(floatval(get_table_value($config_settings_table,$setpoint_temperature_key)), 1, '.', '');
    
    $cooling_hysteresis = get_table_value($config_settings_table ,$cooling_hysteresis_key);
    $heating_hysteresis = get_table_value($config_settings_table ,$heating_hysteresis_key);
    
    $sensor_humidity = round(get_table_value($current_values_table,$sensor_humidity_key), 0);   
    $setpoint_humidity = round(get_table_value($config_settings_table,$setpoint_humidity_key), 0);
    $humidifier_hysteresis = intval(get_table_value($config_settings_table,$humidifier_hysteresis_key));
    $dehumidifier_hysteresis = intval(get_table_value($config_settings_table,$dehumidifier_hysteresis_key));
    $hysteresis_offset = round(get_table_value($config_settings_table,$hysteresis_offset_key), 1);
    $saturation_point = get_table_value($config_settings_table, $saturation_point_key); 
    $dehumidifier_modus = intval(get_table_value($config_settings_table,$dehumidifier_modus_key));
    $dewpoint_check = intval(get_table_value($config_settings_table, $dewpoint_check_key));
    
    $circulation_air_duration = round(get_table_value($config_settings_table,$circulation_air_duration_key), 1)/60;
    $circulation_air_period = round(get_table_value($config_settings_table,$circulation_air_period_key), 1)/60;
    $exhaust_air_duration = round(get_table_value($config_settings_table,$exhaust_air_duration_key), 1)/60;
    $exhaust_air_period = round(get_table_value($config_settings_table,$exhaust_air_period_key), 1)/60;

    $uv_modus = intval(get_table_value($config_settings_table,$uv_modus_key));
    $uv_duration = intval(get_table_value($config_settings_table,$uv_duration_key)/60);
    $uv_period = intval(get_table_value($config_settings_table,$uv_period_key)/60);
    $status_uv_manual = intval(get_table_value($current_values_table, $status_uv_manual_key));
    
    $light_modus = intval(get_table_value($config_settings_table,$light_modus_key));
    $light_duration = intval(get_table_value($config_settings_table,$light_duration_key)/60);
    $light_period = intval(get_table_value($config_settings_table,$light_period_key)/60);
    $status_light_manual = intval(get_table_value($current_values_table,$status_light_manual_key));  
    
    $scale1_thread_alive = intval(get_table_value($current_values_table, $scale1_thread_alive_key));
    $scale2_thread_alive = intval(get_table_value($current_values_table, $scale2_thread_alive_key));
    $status_scale1 = intval(get_table_value($current_values_table,$status_scale1_key));
    $status_scale2 = intval(get_table_value($current_values_table,$status_scale2_key));

    logger('DEBUG', 'read_config_db performed');
?>