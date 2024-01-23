<?php
    // include 'modules/funclib.php';
    // include 'modules/read_config_db.php';                     // Liest die Grundeinstellungen Sensortyp, Hysteresen)
    #var_dump($_POST);
    function limit_humidity( $value ) {
        $min_humidity = 0;
        $max_humidity = 97;
        if ($value > $max_humidity) {
            $value = $max_humidity;
        }
        else if ($value < $min_humidity) {
            $value = $min_humidity;
        }
        return $value;
    }
    
    $message_settings='';
    # Prüfung der eingegebenen Werte
    if(!empty($_POST['manvals_form_submit']))
        {
            logger('DEBUG', 'button save manvals pressed');
            $modus_setting = $_POST['modus_settings'];
            $setpoint_temperature_setting = $_POST['setpoint_temperature_settings'];
            $setpoint_humidity_setting = $_POST['setpoint_humidity_settings'];
            $circulation_air_period_setting = $_POST['circulation_air_period_settings'];
            $circulation_air_duration_setting = $_POST['circulation_air_duration_settings'];
            $exhaust_air_period_setting = $_POST['exhaust_air_period_settings'];
            $exhaust_air_duration_setting = $_POST['exhaust_air_duration_settings'];
        
        // if(isset ($modus_setting) && $modus_setting <> NULL) {      // ist das $_POST-Array gesetzt
        if($modus_setting != NULL) {             // ist das $_POST-Array gesetzt
            $SettingsInputIsValid = TRUE;
            foreach ($_POST as $key => $value) {  // Prüfen, ob nur Zahlen eingegeben wurden
                if ($key == 'manvals_form_submit') {
                    continue;
                }
                else if ($key == 'setpoint_temperature_settings') {
                    if (is_numeric($value) == FALSE) {
                        $message_config = _('unauthorized character - please use only integers!');
                        $ConfigInputIsValid = FALSE;
                    }
                }
                else {
                    if (!(preg_match('/^-?\d+$/', $value))) {
                        $message_config = _('unauthorized character - please use only integers!');
                        $ConfigInputIsValid = FALSE;
                    }
                }
            }
            
            if ($SettingsInputIsValid == TRUE)
                {
                    if ( $setpoint_temperature_setting <= 70 &&  $setpoint_temperature_setting >= -11 &&        // Prüfung Soll-Temperatur
                        $setpoint_humidity_setting <= 95 && $setpoint_humidity_setting >= 0 &&                  // Prüfung Soll-Feuchtigkeit
                        $circulation_air_period_setting < 1441 && $circulation_air_period_setting > -1 &&       // Prüfung Intervall Umluft
                        $circulation_air_duration_setting < 1441 && $circulation_air_duration_setting > -1  &&  // Prüfung Dauer Umluft
                        $exhaust_air_period_setting < 1441 && $exhaust_air_period_setting > -1 &&             // Prüfung Intervall Abluft
                        $exhaust_air_duration_setting < 1441 && $exhaust_air_duration_setting > -1            // Prüfung Dauer Abluft
                    )
                    {
                        # Eingestellte Werte in config/settings.json und logs/logfile.txt speichern
                        write_settings($modus_setting,$setpoint_temperature_setting,$setpoint_humidity_setting,$circulation_air_period_setting,
                                        $circulation_air_duration_setting,$exhaust_air_period_setting,$exhaust_air_duration_setting);

                        // $timestamp = time();
                        // $database = new SQLite3("/var/www/config/pi-ager.sqlite3");
                        
                        // $database->exec('UPDATE config SET "value" = "' . $_POST['modus_settings'] . '" , "last_change" = ' .(string)$timestamp . ' WHERE "key"="modus"');
                        // $database->exec('UPDATE config SET "value" = "' . $_POST['setpoint_temperature_settings'] . '" , "last_change" = ' . (string)$timestamp . ' WHERE "key" = "setpoint_temperature"');
                        // $database->exec('UPDATE config SET "value" = "' . $_POST['setpoint_humidity_settings'] . '" , "last_change" = ' . (string)$timestamp . ' WHERE "key"="setpoint_humidity"');
                        // $database->exec('UPDATE config SET "value" = "' . ($_POST['circulation_air_period_settings']*60) . '" , "last_change" = ' . (string)$timestamp . ' WHERE "key"="circulation_air_period"');
                        // $database->exec('UPDATE config SET "value" = "' . ($_POST['circulation_air_duration_settings']*60) . '" , "last_change" = ' . (string)$timestamp . ' WHERE "key"="circulation_air_duration"');
                        // $database->exec('UPDATE config SET "value" = "' . ($_POST['exhaust_air_period_settings']*60) . '" , "last_change" = ' . (string)$timestamp . ' WHERE "key"="exhaust_air_period"');
                        // $database->exec('UPDATE config SET "value" = "' . ($_POST['exhaust_air_duration_settings']*60) . '" , "last_change" = ' . (string)$timestamp . ' WHERE "key"="exhaust_air_duration"');
                        // $jsoninput = json_encode($_POST);
                        // file_put_contents('config/settings.json', $jsoninput);

                        # Formatierung für die Lesbarkeit im Logfile:
                        # Modus
                        $internal_temperature = get_table_value($current_values_table, $sensor_temperature_key);
                        $external_temperature = get_table_value($current_values_table, $sensor_extern_temperature_key);
                        $cooling_hysteresis = number_format(floatval(get_table_value($config_settings_table, $cooling_hysteresis_key)), 1, '.', '');
                        $heating_hysteresis = number_format(floatval(get_table_value($config_settings_table, $heating_hysteresis_key)), 1, '.', '');
        
                        $dehumidifier_modus = intval(get_table_value($config_settings_table,$dehumidifier_modus_key));
                        $humidifier_hysteresis = intval(get_table_value($config_settings_table,$humidifier_hysteresis_key));
                        $dehumidifier_hysteresis = intval(get_table_value($config_settings_table,$dehumidifier_hysteresis_key));
                        $humidifier_hysteresis_offset = round(get_table_value($config_settings_table,$humidifier_hysteresis_offset_key), 1);
                        $dehumidifier_hysteresis_offset = round(get_table_value($config_settings_table,$dehumidifier_hysteresis_offset_key), 1);
                        
                        $setpoint_temperature = round(get_table_value($config_settings_table,$setpoint_temperature_key), 1);
                        $setpoint_humidity = round(get_table_value($config_settings_table,$setpoint_humidity_key), 1);  
                        $saturation_point = intval(get_table_value($config_settings_table,$saturation_point_key));
                        $delay_humidify = intval(get_table_value($config_settings_table,$delay_humidify_key));
                        
                        if ($external_temperature !== null && $internal_temperature !== null && $external_temperature < $setpoint_temperature && ($modus_setting == 3 || $modus_setting == 4)) {
                            $cooler_on = number_format(floatval($setpoint_temperature_setting + $heating_hysteresis/2), 2, '.', '');
                            $cooler_off = number_format(floatval($setpoint_temperature_setting - $heating_hysteresis/2), 2, '.', '');
                            $heater_on = number_format(floatval($setpoint_temperature_setting - $cooling_hysteresis/2), 2, '.', '');
                            $heater_off = number_format(floatval($setpoint_temperature_setting + $cooling_hysteresis/2), 2, '.', '');
                        }
                        else {
                            $cooler_on = number_format(floatval($setpoint_temperature_setting + $cooling_hysteresis/2), 2, '.', '');
                            $cooler_off = number_format(floatval($setpoint_temperature_setting - $cooling_hysteresis/2), 2, '.', '');
                            $heater_on = number_format(floatval($setpoint_temperature_setting - $heating_hysteresis/2), 2, '.', '');
                            $heater_off = number_format(floatval($setpoint_temperature_setting + $heating_hysteresis/2), 2, '.', '');
                        }
                        if ($modus_setting == 0) {
                            $operating_mode = _('cooling');
                        }

                        if ($modus_setting == 1) {
                            $operating_mode = _('cooling with humidification');
                        }

                        if ($modus_setting == 2) {
                            $operating_mode = _('heating with humidification');
                        }
                        
                        if ($modus_setting == 3) {
                            $operating_mode = _('automatic with humidification');
                        }

                        if ($modus_setting == 4) {
                            $operating_mode = _('automatic with dehumidification and humidification');
                            $switch_on_dehumidify = eval_switch_on_dehumidity( $setpoint_humidity_setting, $dehumidifier_hysteresis, $dehumidifier_hysteresis_offset, $saturation_point );
                            $switch_off_dehumidify = eval_switch_off_dehumidity( $setpoint_humidity_setting,  $dehumidifier_hysteresis, $dehumidifier_hysteresis_offset );
                        }
                        # Dehumidify-Modus
                        if ($dehumidifier_modus == 1) {
                            $dehumidifier_modus_name = _('only exhaust');
                        }
                        if ($dehumidifier_modus == 2) {
                            $dehumidifier_modus_name = _('exhaust & dehumidifier');
                        }
                        if ($dehumidifier_modus == 3) {
                            $dehumidifier_modus_name = _('only dehumidifier');
                        }

                        $circulation_air_duration = $circulation_air_duration_setting;
                        $circulation_air_period = $circulation_air_period_setting;
                        $exhausting_air_duration = $exhaust_air_duration_setting;
                        $exhausting_air_period = $exhaust_air_period_setting;
                        
                        $switch_on_humidify = eval_switch_on_humidity( $setpoint_humidity_setting, $humidifier_hysteresis, $humidifier_hysteresis_offset );
                        $switch_off_humidify = eval_switch_off_humidity( $setpoint_humidity_setting, $humidifier_hysteresis, $humidifier_hysteresis_offset , $saturation_point );
                       
                        $sensortype = intval(get_table_value($config_settings_table,$sensortype_key));
                            
                        if ($sensortype == 1) {
                            $sensorname = 'DHT11';
                        }
                        else if ($sensortype == 2) {
                            $sensorname = 'DHT22';
                        }
                        else if ($sensortype == 3) {
                            $sensorname = 'SHT75';
                        }
                        else if ($sensortype == 4) {
                            $sensorname = 'SHT85';
                        }
                        else if ($sensortype == 5) {
                            $sensorname = 'SHT3x';
                        }
                        else if ($sensortype == 6) {
                            $sensorname = 'SHT3x-mod';
                        }
                        else if ($sensortype == 7) {
                            $sensorname = 'AHT1x';
                        }                        
                        else if ($sensortype == 8) {
                            $sensorname = 'AHT1x-mod';
                        }                        
                        else if ($sensortype == 9) {
                            $sensorname = 'AHT2x';
                        }                                                       
                        else if ($sensortype == 10) {
                            $sensorname = 'AHT30';
                        }                                                       
                        else if ($sensortype == 11) {
                            $sensorname = 'SHT4x-A';
                        }
                        else if ($sensortype == 12) {
                            $sensorname = 'SHT4x-B';
                        }
                        else if ($sensortype == 13) {
                            $sensorname = 'SHT4x-C';
                        }                         
                        else {
                            $sensorname = 'undefined';
                        }

                        $logstring = " \n ***********************************************";
                        $logstring = $logstring . " \n " . _('values have been manually changed.');
                        $logstring = $logstring . " \n " . _('sensor').": ".$sensorname;
                        $logstring = $logstring . " \n " . _('operating mode').": ".$operating_mode;
                        
                        if ($modus_setting == 0 || $modus_setting == 1 || $modus_setting == 2)  {
                            $logstring = $logstring . " \n " . _('setpoint temperature').": ".$setpoint_temperature_setting."&deg;C";
                            $logstring = $logstring . " \n " . _('switch-off temperature').": ".$cooler_off."&deg;C";
                            $logstring = $logstring . " \n " . _('switch-on temperature').": ".$cooler_on."&deg;C";
                        }

                        if ($modus_setting == 3 || $modus_setting == 4)  {
                            $logstring = $logstring . " \n " . _('setpoint temperature').": ".$setpoint_temperature_setting."&deg;C";
                            $logstring = $logstring . " \n " . _('switch-on heater').": ".$heater_on.'&deg;C';
                            $logstring = $logstring . " \n " . _('switch-off heater').": ".$heater_off."&deg;C";
                            $logstring = $logstring . " \n " . _('switch-on cooler').": ".$cooler_on."&deg;C";
                            $logstring = $logstring . " \n " . _('switch-off cooler').": ".$cooler_off."&deg;C";
                        }

                        if ($modus_setting == 1 || $modus_setting == 2 || $modus_setting == 3) {
                            $logstring = $logstring . " \n " . _('setpoint humidity').": ".$setpoint_humidity_setting."% "."&phi;";
                            $logstring = $logstring . " \n " . _('saturation point').": ".$saturation_point."% "."&phi;";
                            $logstring = $logstring . " \n " . _('switch-on humidifier').": ".$switch_on_humidify . "% " . "&phi;";
                            $logstring = $logstring . " \n " . _('switch-off humidifier').": ".$switch_off_humidify . "% " . "&phi;";
                            $logstring = $logstring . " \n " . _('delay humidifier').": ".$delay_humidify." "._('minutes');
                        }

                        if ($modus_setting == 4) {
                            $logstring = $logstring . " \n " . _('setpoint humidity').": ".$setpoint_humidity_setting."% &phi;";
                            $logstring = $logstring . " \n " . _('saturation point').": ".$saturation_point."% "."&phi;";
                            $logstring = $logstring . " \n " . _('switch-on humidifier').": ".$switch_on_humidify."% " . "&phi;";
                            $logstring = $logstring . " \n " . _('switch-off humidifier').": ".$switch_off_humidify."% " . "&phi;";
                            $logstring = $logstring . " \n " . _('switch-on dehumidifier').": ".$switch_on_dehumidify."% " . "&phi;";
                            $logstring = $logstring . " \n " . _('switch-off dehumidifier').": ".$switch_off_dehumidify."% " . "&phi;";
                            $logstring = $logstring . " \n " . _('delay humidifier').": ".$delay_humidify." "._('minutes');
                        }

                        $logstring = $logstring . " \n " . _('circulation air OFF duration').": ".$circulation_air_period." "._('minutes');
                        $logstring = $logstring . " \n " . _('circulation air ON duration').": ".$circulation_air_duration." "._('minutes');
                        $logstring = $logstring . " \n " . _('exhausting air OFF duration').": ".$exhausting_air_period." "._('minutes');
                        $logstring = $logstring . " \n " . _('exhausting air ON duration').": ".$exhausting_air_duration." "._('minutes');
                        $logstring = $logstring . " \n " . _('dehumidify modus').": ".$dehumidifier_modus_name;

                        $logstring = $logstring . " \n " . "***********************************************";
                        logger('INFO', $logstring);

                        # 3Sekunden Anzeige dass die Werte gespeichert wurden
                        $message_settings = (_("values saved in file Database"));
                    }
                    else {
                        $message_settings = (_("values not in the specified limits!"));
                        }
                }
        }
        logger('DEBUG', 'manual values: ' . $message_settings);
        print '<script language="javascript"> alert("'. (_("manual values")) . " : " .$message_settings.'"); </script>';
    }
?>
