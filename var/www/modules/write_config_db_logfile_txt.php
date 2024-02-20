<?php
    //include 'modules/funclib.php';
    
    $message_config='';
    # Prüfung der eingegebenen Werte
    if(!empty($_POST['config_form_submit']))
        {                       // ist das $_POST-Array gesetzt
        logger('DEBUG', 'button save configvalues pressed');
        $cooling_hysteresis_config = $_POST['cooling_hysteresis_config'];
        $heating_hysteresis_config = $_POST['heating_hysteresis_config'];
        $cooling_hysteresis_offset_config = $_POST['cooling_hysteresis_offset_config'];
        $heating_hysteresis_offset_config = $_POST['heating_hysteresis_offset_config'];
        $humidifier_hysteresis_config = $_POST['humidifier_hysteresis_config'];
        $dehumidifier_hysteresis_config = $_POST['dehumidifier_hysteresis_config'];
        $humidifier_hysteresis_offset_config = $_POST['humidifier_hysteresis_offset_config'];
        $dehumidifier_hysteresis_offset_config = $_POST['dehumidifier_hysteresis_offset_config'];
        $saturation_point_config = $_POST['saturation_point_config'];
        $delay_humidify_config = $_POST['delay_humidify_config'];
        $uv_period_config = $_POST['uv_period_config'];
        $uv_duration_config = $_POST['uv_duration_config'];
        $switch_on_uv_hour_config = $_POST['switch_on_uv_hour_config'];
        $switch_on_uv_minute_config = $_POST['switch_on_uv_minute_config'];
        $uv_check_config = $_POST['uv_check_config'];
        $light_period_config = $_POST['light_period_config'];
        $light_duration_config = $_POST['light_duration_config'];
        $switch_on_light_hour_config = $_POST['switch_on_light_hour_config'];
        $switch_on_light_minute_config = $_POST['switch_on_light_minute_config'];
        $uv_modus_config = $_POST['uv_modus_config'];
        $light_modus_config = $_POST['light_modus_config'];
        $dehumidifier_modus_config = $_POST['dehumidifier_modus_config'];
        $failure_temperature_delta_config = 10;  // $_POST['failure_temperature_delta_config'];
        $failure_humidity_delta_config = 4; // $_POST['failure_humidity_delta_config'];
        $internal_temperature_low_limit = $_POST['internal_temperature_low_limit_config'];
        $internal_temperature_high_limit = $_POST['internal_temperature_high_limit_config'];
        $internal_temperature_hysteresis = $_POST['internal_temperature_hysteresis_config'];
        $shutdown_on_batlow_config = $_POST['shutdown_on_batlow_config'];
        $delay_cooler_config = $_POST['delay_cooler_config'];
        $dewpoint_check_config = $_POST['dewpoint_check_config'];
        $reset_uptime_config = $_POST['reset_uptime_config'];
        $init_uv_uptime_config = $_POST['init_uv_uptime_config'];  // hours
        $delay_monitoring_humidifier = $_POST['delay_monitoring_humidifier_config'];
        $tolerance_monitoring_humidifier = $_POST['tolerance_monitoring_humidifier_config'];
        $check_monitoring_humidifier = $_POST['check_monitoring_humidifier_config'];
        
        $ConfigInputIsValid = TRUE;
        foreach ($_POST as $key => $value) {  // Prüfen, ob nur Zahlen eingegeben wurden
            if ($key == 'config_form_submit') {
                continue;
            }
            else if ($key == 'cooling_hysteresis_config' || $key == 'heating_hysteresis_config' || 
                    $key == 'humidifier_hysteresis_offset_config' || $key == 'dehumidifier_hysteresis_offset_config' || 
                    $key == 'cooling_hysteresis_offset_config' || $key == 'heating_hysteresis_offset_config') {
                if (is_numeric($value) == FALSE) {
                    $message_config = _('unauthorized character - please use only integers or floats!');
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

        if ($ConfigInputIsValid == TRUE)
        {
            if ($cooling_hysteresis_config != $heating_hysteresis_config &&                         // hysteresis must differ.
                $saturation_point_config >= 80 && $saturation_point_config <= 100 &&                // check saturation_point
                $delay_humidify_config <= 60 && $delay_humidify_config >= 0 &&                                                            // Prüfung Verzögerung Feuchte
                $uv_period_config < 1441 && $uv_period_config > -1 && // (($uv_period_config+$uv_duration_config) > 0) &&                 // Prüfung Intervall UV
                $uv_duration_config < 1441 && $uv_duration_config > -1  &&                              // Prüfung Dauer UV
                $switch_on_uv_hour_config >= 0 && $switch_on_uv_hour_config < 24 && $switch_on_uv_minute_config >= 0 && $switch_on_uv_minute_config < 60 && // UV Uhrzeit
                $light_period_config < 1441 && $light_period_config > -1 &&  // (($light_period_config+$light_duration_config) > 0) &&                 // Prüfung Intervall Licht
                $light_duration_config < 1441 && $light_duration_config > -1  &&                              // Prüfung Dauer Licht
                $switch_on_light_hour_config >= 0 && $switch_on_light_hour_config < 24 && $switch_on_light_minute_config >= 0 && $switch_on_light_minute_config < 60 && // Licht Uhrzeit
                $internal_temperature_low_limit >= -11 && $internal_temperature_low_limit <= 70 && // Temperatur low limit Überwachung
                $internal_temperature_high_limit >= -11 && $internal_temperature_high_limit <= 70 && // Temperatur high limit Überwachung
                $internal_temperature_high_limit > $internal_temperature_low_limit &&
                $internal_temperature_hysteresis >= 1 && $internal_temperature_hysteresis <= 10 && // Temperatur hysteresis für Event Generierung
                $delay_cooler_config >= 0 && $delay_cooler_config <= 120   // cooler delay if cooler turned off and should turned on again
            )
            {
                # Eingestellte Werte in config/config.json und logs/logfile.txt speichern
                write_config($cooling_hysteresis_config, $heating_hysteresis_config, $cooling_hysteresis_offset_config, $heating_hysteresis_offset_config, 
                            $humidifier_hysteresis_config, $dehumidifier_hysteresis_config, $humidifier_hysteresis_offset_config, $dehumidifier_hysteresis_offset_config, $saturation_point_config, $delay_humidify_config, $uv_modus_config, $uv_duration_config,
                            $uv_period_config, $switch_on_uv_hour_config, $switch_on_uv_minute_config, $light_modus_config, $light_duration_config,
                            $light_period_config, $switch_on_light_hour_config, $switch_on_light_minute_config, $dehumidifier_modus_config,
                            $failure_temperature_delta_config, $failure_humidity_delta_config, $internal_temperature_low_limit, $internal_temperature_high_limit, $internal_temperature_hysteresis,
                            $shutdown_on_batlow_config, $delay_cooler_config, $dewpoint_check_config, $uv_check_config, $delay_monitoring_humidifier, $tolerance_monitoring_humidifier, $check_monitoring_humidifier);
                logger('DEBUG', 'configvalues saved');
                
                # settings used by manvals.php but used also in config.php, when manvals.php is hidden
                # when an aging table is active
                $modus = intval(get_table_value($config_settings_table,$modus_key));
                $setpoint_temperature = number_format(floatval(get_table_value($config_settings_table,$setpoint_temperature_key)), 1, '.', '');
                $setpoint_humidity = round(get_table_value($config_settings_table,$setpoint_humidity_key), 0);
                $circulation_air_duration = round(get_table_value($config_settings_table,$circulation_air_duration_key), 1)/60;
                $circulation_air_period = round(get_table_value($config_settings_table,$circulation_air_period_key), 1)/60;
                $exhaust_air_duration = round(get_table_value($config_settings_table,$exhaust_air_duration_key), 1)/60;
                $exhaust_air_period = round(get_table_value($config_settings_table,$exhaust_air_period_key), 1)/60;

                # evaluate and set humidifier limits for log
                
                $switch_on_humidity = eval_switch_on_humidity( $setpoint_humidity, $humidifier_hysteresis_config, $humidifier_hysteresis_offset_config );
                $switch_off_humidity = eval_switch_off_humidity( $setpoint_humidity, $humidifier_hysteresis_config, $humidifier_hysteresis_offset_config, $saturation_point_config );
                $switch_on_dehumidity = eval_switch_on_dehumidity( $setpoint_humidity, $dehumidifier_hysteresis_config, $dehumidifier_hysteresis_offset_config, $saturation_point_config );
                $switch_off_dehumidity = eval_switch_off_dehumidity( $setpoint_humidity, $dehumidifier_hysteresis_config, $dehumidifier_hysteresis_offset_config );
                
                # Formatierung für die Lesbarkeit im Logfile:
                # Modus
                if ($modus == 0) {
                    $operating_mode = _('cooling');
                }

                if ($modus == 1) {
                    $operating_mode = _('cooling with humidification');
                }

                if ($modus == 2) {
                    $operating_mode = _('heating with humidification');
                }
                if ($modus == 3) {
                    $operating_mode = _('automatic with humidification');
                }

                if ($modus == 4) {
                    $operating_mode = _('automatic with dehumidification and humidification');
                }
                
                # Dehumidify-Modus
                if ($dehumidifier_modus_config == 1) {
                    $dehumidifier_modus_name = _('only exhaust');
                }
                if ($dehumidifier_modus_config == 2) {
                    $dehumidifier_modus_name = _('exhaust & dehumidifier');
                }
                if ($dehumidifier_modus_config == 3) {
                    $dehumidifier_modus_name = _('only dehumidifier');
                }
                # UV licht
                if ($uv_modus_config == 0) {
                    $uv_modus_name = _('off');
                    $logtext_uv = " ";
                    $logtext_uv_duration = " ";
                }
                if ($uv_modus_config == 1) {
                    $uv_modus_name = _('UV ON/OFF duration');
                    $logtext_uv = _('UV OFF duration') . ": " . $uv_period_config . " " . _('minutes');
                    $logtext_uv_duration = _('UV ON duration') . ": " . $uv_duration_config . " " . _('minutes');
                }
                if ($uv_modus_config == 2) {
                    $uv_modus_name = _('UV ON duration & timestamp');
                    $logtext_uv = _('UV timestamp') . ": ". $switch_on_uv_hour_config . ":" . $switch_on_uv_minute_config;
                    $logtext_uv_duration = _('UV ON duration') . ": ". $uv_duration_config . " " . _('minutes');
                }
                # Licht
                if ($light_modus_config == 0) {
                    $light_modus_name = _('off');
                    $logtext_light = " ";
                    $logtext_light_duration = " ";
                }
                if ($light_modus_config == 1) {
                    $light_modus_name = _('Light ON/OFF duration');
                    $logtext_light = _('Light OFF duration').": ".$light_period_config ." "._('minutes');
                    $logtext_light_duration = _('Light ON duration').": ".$light_duration_config ." "._('minutes');
                }
                if ($light_modus_config == 2) {
                    $light_modus_name = _('Light ON duration & timestamp');
                    $logtext_light = _('Light timestamp').": ".$switch_on_light_hour_config.":".$switch_on_light_minute_config;
                    $logtext_light_duration = _('Light ON duration').": ".$light_duration_config ." "._('minutes');
                }
                // $circulation_air_duration = $circulation_air_duration/60;
                // $circulation_air_period = $circulation_air_period/60;
                // $exhausting_air_duration = $exhaust_air_duration/60;
                // $exhausting_air_period = $exhaust_air_period/60;

                
                $logstring = " \n ***********************************************";
                $logstring = $logstring . " \n " . _('configuration has been changed.') . " \n ";
                $logstring = $logstring . " \n " . _('operating mode') . ": " . $operating_mode;

                if ($modus == 0 || $modus == 1)  {
                    $logstring = $logstring . " \n " . _('setpoint temperature') . ": " . $setpoint_temperature . " &deg;C";
                    $logstring = $logstring . " \n " . _('primary control hysteresis') . ": " . $cooling_hysteresis_config . " &deg;C";
                    $logstring = $logstring . " \n " . _('cooler control hysteresis offset') . ": " . $cooling_hysteresis_offset_config . " &deg;C";
                }
                
                if ($modus == 2)  {
                    $logstring = $logstring . " \n " . _('setpoint temperature') . ": " . $setpoint_temperature . " &deg;C";
                    $logstring = $logstring . " \n " . _('primary control hysteresis') . ": " . $cooling_hysteresis_config . " &deg;C";
                    $logstring = $logstring . " \n " . _('heater control hysteresis offset') . ": " . $heating_hysteresis_offset_config . " &deg;C";
                }
                                
                if ($modus == 3 || $modus == 4)  {
                    $logstring = $logstring . " \n " . _('setpoint temperature') . ": " . $setpoint_temperature . " &deg;C";
                    $logstring = $logstring . " \n " . _('primary control hysteresis') . ": " . $cooling_hysteresis_config . " &deg;C";
                    $logstring = $logstring . " \n " . _('secondary control hysteresis') . ": " . $heating_hysteresis_config . " &deg;C";
                    $logstring = $logstring . " \n " . _('cooler control hysteresis offset') . ": " . $cooling_hysteresis_offset_config . " &deg;C";                    
                    $logstring = $logstring . " \n " . _('heater control hysteresis offset') . ": " . $heating_hysteresis_offset_config . " &deg;C";                    
                }

                if ($modus == 1 || $modus == 2 || $modus == 3) {
                    $logstring = $logstring . " \n " . _('setpoint humidity') . ": " . $setpoint_humidity . "% " . "&phi;";
                    $logstring = $logstring . " \n " . _('humidifier control hysteresis') . ": " . $humidifier_hysteresis_config . "% " . "&phi;";
                    $logstring = $logstring . " \n " . _('humidifier control hysteresis offset') . ": " . $humidifier_hysteresis_offset_config . "% " . "&phi;";
                    $logstring = $logstring . " \n " . _('saturation point') . ": " . $saturation_point_config . "% " . "&phi;";
                //    $logstring = $logstring . " \n " . _('switch-on humidifier') . ": " . $switch_on_humidity . " % &phi;";
                //    $logstring = $logstring . " \n " . _('switch-off humidifier') . ": " . $switch_off_humidity. " % &phi;";
                    $logstring = $logstring . " \n " . _('delay humidifier') . ": " . $delay_humidify_config . " " . _('minutes');
                }

                if ($modus == 4) {
                    $logstring = $logstring . " \n " . _('setpoint humidity') . ": " . $setpoint_humidity . "% &phi;";
                    $logstring = $logstring . " \n " . _('humidifier control hysteresis') . ": " . $humidifier_hysteresis_config . "% " . "&phi;";
                    $logstring = $logstring . " \n " . _('dehumidifier control hysteresis') . ": " . $dehumidifier_hysteresis_config . "% " . "&phi;";                   
                    $logstring = $logstring . " \n " . _('humidifier control hysteresis offset') . ": " . $humidifier_hysteresis_offset_config . "% " . "&phi;";
                    $logstring = $logstring . " \n " . _('dehumidifier control hysteresis offset') . ": " . $dehumidifier_hysteresis_offset_config . "% " . "&phi;";                      
                    $logstring = $logstring . " \n " . _('saturation point') . ": " . $saturation_point_config . "% " . "&phi;";
                    // $logstring = $logstring . " \n " . _('switch-on humidifier') . ": " . $switch_on_humidity . "% &phi;";
                    // $logstring = $logstring . " \n " . _('switch-off humidifier') . ": " . $switch_off_humidity . "% &phi;";
                    // $logstring = $logstring . " \n " . _('switch-on dehumidifier') . ": " . $switch_on_dehumidity . "% &phi;";
                    // $logstring = $logstring . " \n " . _('switch-off dehumidifier') . ": " . $switch_off_dehumidity . "% &phi;";
                    $logstring = $logstring . " \n " . _('delay humidifier') . ": " . $delay_humidify_config . " " . _('minutes');
                    $logstring = $logstring . " \n " . _('abs. humidity check aktive') . ": " . (($dewpoint_check_config == 0) ? _('off') : _('on'));
                }
                $logstring = $logstring . " \n " . _('circulation air OFF duration') . ": " . $circulation_air_period . " " . _('minutes');
                $logstring = $logstring . " \n " . _('circulation air ON duration') . ": " . $circulation_air_duration . " " . _('minutes');
                $logstring = $logstring . " \n " . _('exhausting air OFF duration') . ": " . $exhaust_air_period . " " . _('minutes');
                $logstring = $logstring . " \n " . _('exhausting air ON duration') . ": " . $exhaust_air_duration . " " . _('minutes');
                $logstring = $logstring . " \n " . _('dehumidify modus') . ": " . $dehumidifier_modus_name;
                $logstring = $logstring . " \n " . _('UV modus') . ": " . $uv_modus_name;
                if ($uv_modus_config != 0) {
                    $logstring = $logstring . " \n " . $logtext_uv;
                    $logstring = $logstring . " \n " . $logtext_uv_duration;
                }
                $logstring = $logstring . " \n " . _('activate circulating air') . ": " . (($uv_check_config == 0) ? _('off') : _('on'));
                $logstring = $logstring . " \n " . _('Light modus') . ": " . $light_modus_name;
                if ($light_modus_config != 0) {
                    $logstring = $logstring . " \n " . $logtext_light;
                    $logstring = $logstring . " \n " . $logtext_light_duration;
                }
                $logstring = $logstring . " \n " . _('low temperature limit') . ": " . $internal_temperature_low_limit . " &deg;C";
                $logstring = $logstring . " \n " . _('high temperature limit') . ": " . $internal_temperature_high_limit . " &deg;C";
                $logstring = $logstring . " \n " . _('hysteresis') . ": " . $internal_temperature_hysteresis . " &deg;C";
                $logstring = $logstring . " \n " . _('humidifier monitoring delay') . ": " . $delay_monitoring_humidifier . ' ' . _('minutes');
                $logstring = $logstring . " \n " . _('monitoring tolerance') . ": " . $tolerance_monitoring_humidifier . "% &phi;";
                $logstring = $logstring . " \n " . _('monitoring') . ": " . (($check_monitoring_humidifier == 0) ? _('off') : _('on'));
                $logstring = $logstring . " \n " . _('shutdown on battery low') . ": " . (($shutdown_on_batlow_config == 0) ? _('off') : _('on'));
                $logstring = $logstring . " \n " . "***********************************************";
                logger('INFO', $logstring);

                $message_config = (_("values saved in file Database"));
            }
            else {
                logger('DEBUG', 'configvalues not in specified limits');
                $message_config = (_("values not within the specified limits!"));
            }
            // set up uptime if checked
            if ($reset_uptime_config == 1) {
                write_table_value($time_meter_table, $id_field, 1, $uv_light_seconds_field, intval($init_uv_uptime_config * 3600));
            }
        }
        print '<script language="javascript"> alert("' . (_("general configuration")) . " : " . $message_config . '"); </script>';
    }
?>