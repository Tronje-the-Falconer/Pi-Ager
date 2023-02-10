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
    $agingtable_comment = ' ';
    
    if ($grepagingtable){
        $maturity_type = _('agingtable') . ' - ' . $desired_maturity;
        $current_period = intval(get_table_value($current_values_table, $agingtable_period_key));
        $current_period_day = intval(get_table_value($current_values_table, $agingtable_period_day_key));
    }
    else {
        $maturity_type = _('agingtable') . ' - ' . _('none');
        $current_period = ' ';
        $current_period_day = ' ';
    }

    
    $data_modus = '&nbsp;';
    $data_setpoint_humidity = '&nbsp;';
    $data_setpoint_temperature = '&nbsp;';
    $data_circulation_air_duration = '&nbsp;';
    $data_circulation_air_period = '&nbsp;';
    $data_exhaust_air_duration = '&nbsp;';
    $data_exhaust_air_period = '&nbsp;';
    $data_days = '&nbsp;'; 
    
    if ($grepagingtable) {    

        $index_row = 0;
        $agingtable_rows = get_agingtable_dataset($desired_maturity);
    
        if ($agingtable_rows != false){
            $firstrow = $agingtable_rows[0];
            $agingtable_comment = $firstrow[$agingtable_comment_field];
            if (!isset($agingtable_comment)){
                $agingtable_comment = _('no comment');
            }

            try {
                $number_rows = count($agingtable_rows);
                while ($index_row < $number_rows) {
                    $dataset = $agingtable_rows[$index_row];
                    if (!empty($dataset[$agingtable_modus_field])){
                        $data_modus = $dataset[$agingtable_modus_field];
                    }
                    if (!empty($dataset[$agingtable_setpoint_humidity_field])){
                        $data_setpoint_humidity = $dataset[$agingtable_setpoint_humidity_field];
                    }
                    if (!empty($dataset[$agingtable_setpoint_temperature_field])){
                        $data_setpoint_temperature = $dataset[$agingtable_setpoint_temperature_field];
                    }
                    if (!empty($dataset[$agingtable_circulation_air_duration_field])){
                        $data_circulation_air_duration = $dataset[$agingtable_circulation_air_duration_field]/60;
                    }
                    if (!empty($dataset[$agingtable_circulation_air_period_field])){
                        $data_circulation_air_period = $dataset[$agingtable_circulation_air_period_field]/60;
                    }
                    if (!empty($dataset[$agingtable_exhaust_air_duration_field])){
                        $data_exhaust_air_duration = $dataset[$agingtable_exhaust_air_duration_field]/60;
                    }
                    if (!empty($dataset[$agingtable_exhaust_air_period_field])){
                        $data_exhaust_air_period = $dataset[$agingtable_exhaust_air_period_field]/60;
                    }
                    if (!empty($dataset[$agingtable_hours_field])){
                        $data_hours = $dataset[$agingtable_hours_field];
                    }
                    if ($current_period == $index_row AND $grepagingtable != 0){
                        $current_period = $current_period + 1;
                        break;
                    }
                    $index_row++;
                } 
            }
            catch (Exception $e) {
            }
        }
    }
    else {
        $data_modus = '&nbsp;';
        $data_setpoint_humidity = '&nbsp;';
        $data_setpoint_temperature = '&nbsp;';
        $data_circulation_air_duration = '&nbsp;';
        $data_circulation_air_period = '&nbsp;';
        $data_exhaust_air_duration = '&nbsp;';
        $data_exhaust_air_period = '&nbsp;';
        $data_hours = '&nbsp;';          
    } 
    
    $current_values = array();
    $current_values['maturity_type'] = $maturity_type;    
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
    $current_values['data_hours'] = $data_hours;
    $current_values['agingtable_comment'] = nl2br($agingtable_comment);
    echo json_encode($current_values);

    logger('DEBUG', 'querycv finished');
?>