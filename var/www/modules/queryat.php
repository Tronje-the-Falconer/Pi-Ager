<?php
    include 'names.php';
    include 'database.php';
    include 'logging.php';
    
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
    
// get data for ajax to refresh agingtable row on index.php

    $desired_maturity = read_agingtable_name_from_config();
    $grepagingtable = intval(get_table_value($current_values_table, $status_agingtable_key));

    if ($grepagingtable){
        $maturity_type = $desired_maturity;
    }
    else {
        $maturity_type = _('none');
    }

    $current_period = get_table_value($current_values_table, $agingtable_period_key);
    $current_period_day = get_table_value($current_values_table, $agingtable_period_day_key);
    
    $index_row = 0;
    $agingtable_rows = get_agingtable_dataset($desired_maturity);

    $data_modus = 0;
    $data_setpoint_humidity = 0;
    $data_setpoint_temperature = 0;
    $data_circulation_air_duration = 0;
    $data_circulation_air_period = 0;
    $data_exhaust_air_duration = 0;
    $data_exhaust_air_period = 0;
    $data_days = 0;  
   
    if ($agingtable_rows != false){

        try {
            $number_rows = count($agingtable_rows);
            while ($index_row < $number_rows) {
                $dataset = $agingtable_rows[$index_row];
               // $num = count($dataset);
                if (!empty($dataset[$agingtable_modus_field])){
                   $data_modus = $dataset[$agingtable_modus_field];
                }// else {$data_modus = '..';}
                if (!empty($dataset[$agingtable_setpoint_humidity_field])){
                    $data_setpoint_humidity = $dataset[$agingtable_setpoint_humidity_field];
                }// else {$data_setpoint_humidity = '..';}
                if (!empty($dataset[$agingtable_setpoint_temperature_field])){
                    $data_setpoint_temperature = $dataset[$agingtable_setpoint_temperature_field];
                }// else {$data_setpoint_temperature = '..';}
                if (!empty($dataset[$agingtable_circulation_air_duration_field])){
                    $data_circulation_air_duration = $dataset[$agingtable_circulation_air_duration_field]/60;
                }// else {$data_circulation_air_duration = '..';}
                if (!empty($dataset[$agingtable_circulation_air_period_field])){
                    $data_circulation_air_period = $dataset[$agingtable_circulation_air_period_field]/60;
                }// else {$data_circulation_air_period = '..';}
                if (!empty($dataset[$agingtable_exhaust_air_duration_field])){
                    $data_exhaust_air_duration = $dataset[$agingtable_exhaust_air_duration_field]/60;
                }// else {$data_exhaust_air_duration = '..';}
                if (!empty($dataset[$agingtable_exhaust_air_period_field])){
                    $data_exhaust_air_period = $dataset[$agingtable_exhaust_air_period_field]/60;
                }// else {$data_exhaust_air_period = '..';}
                if (!empty($dataset[$agingtable_days_field])){
                    $data_days = $dataset[$agingtable_days_field];
                }// else {$data_days = '..';}

                if ($current_period == $index_row AND $grepagingtable != NULL){
                    $current_period = $current_period + 1;
                    break;
                }
                $index_row++;
            } 
        }
        catch (Exception $e) {
        }
    }

    $current_values = array();
    $current_values['grepagingtable'] = $grepagingtable;
    $current_values['current_period'] = $current_period;
    $current_values['current_period_day'] = $current_period_day;
    $current_values['data_modus'] = $data_modus;
    $current_values['data_setpoint_humidity'] = $data_setpoint_humidity;
    $current_values['data_setpoint_temperature'] = $data_setpoint_temperature;
    $current_values['data_circulation_air_duration'] = $data_circulation_air_duration;
    $current_values['data_circulation_air_period'] = $data_circulation_air_period;
    $current_values['data_exhaust_air_duration'] = $data_exhaust_air_duration;
    $current_values['data_exhaust_air_period'] = $data_exhaust_air_period;
    $current_values['data_days'] = $data_days;
    
    echo json_encode($current_values);

    logger('DEBUG', 'querycv finished');
?>