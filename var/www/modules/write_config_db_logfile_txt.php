<?php 
    $message_config='';
    # Prüfung der eingegebenen Werte
    if(!empty($_POST['config_form_submit']))
        {                       // ist das $_POST-Array gesetzt
        logger('DEBUG', 'button save configvalues pressed');
        $switch_on_cooling_compressor_config = $_POST['switch_on_cooling_compressor_config'];
        $switch_off_cooling_compressor_config = $_POST['switch_off_cooling_compressor_config'];
        $switch_on_humidifier_config = $_POST['switch_on_humidifier_config'];
        $switch_off_humidifier_config = $_POST['switch_off_humidifier_config'];
        $delay_humidify_config = $_POST['delay_humidify_config'];
        $uv_period_config = $_POST['uv_period_config'];
        $uv_duration_config = $_POST['uv_duration_config'];
        $switch_on_uv_hour_config = $_POST['switch_on_uv_hour_config'];
        $switch_on_uv_minute_config = $_POST['switch_on_uv_minute_config'];
        $light_period_config = $_POST['light_period_config'];
        $light_duration_config = $_POST['light_duration_config'];
        $switch_on_light_hour_config = $_POST['switch_on_light_hour_config'];
        $switch_on_light_minute_config = $_POST['switch_on_light_minute_config'];
        $uv_modus_config = $_POST['uv_modus_config'];
        $light_modus_config = $_POST['light_modus_config'];
        $dehumidifier_modus_config = $_POST['dehumidifier_modus_config'];
        $failure_temperature_delta_config = $_POST['failure_temperature_delta_config'];
        $failure_humidity_delta_config = $_POST['failure_humidity_delta_config'];
        $internal_temperature_low_limit = $_POST['internal_temperature_low_limit_config'];
        $internal_temperature_high_limit = $_POST['internal_temperature_high_limit_config'];
        $internal_temperature_hysteresis = $_POST['internal_temperature_hysteresis_config'];
        $shutdown_on_batlow_config = $_POST['shutdown_on_batlow_config'];
        
        $ConfigInputIsValid = TRUE;
        foreach ($_POST as $CheckInput) {                                  // Prüfen, ob nur Zahlen eingegeben wurden
            if ($CheckInput != 'config_form_submit') {
                if (!(preg_match('/^-?\d+$/', $CheckInput))) {
                    $message_config = _('unauthorized character - please use only integers!');
                    $ConfigInputIsValid = FALSE;
                }
            }
        }

        if ($ConfigInputIsValid == TRUE)
        {
            if ( $switch_on_cooling_compressor_config < 11 && $switch_on_cooling_compressor_config > -11 && ($switch_on_cooling_compressor_config != $switch_off_cooling_compressor_config) &&       // Prüfung Einschaltwert setpoint_temperature.
                ($switch_on_cooling_compressor_config > $switch_off_cooling_compressor_config) &&                                                              // Prüfung Einschaltwert setpoint_temperature.
                $switch_off_cooling_compressor_config < 11 && $switch_off_cooling_compressor_config > -11 &&                                                        // Prüfung Ausschaltwert setpoint_temperature.
                $switch_on_humidifier_config < 31 && $switch_on_humidifier_config > -31 && ($switch_on_humidifier_config != $switch_off_humidifier_config) &&          // Prüfung Einschaltwert Feuchte
                ($switch_on_humidifier_config > $switch_off_humidifier_config) &&                                                               // Prüfung Einschaltwert Feuchte
                $switch_off_humidifier_config < 31 && $switch_off_humidifier_config > -31 &&                                                          // Prüfung Ausschaltwert Feuchte
                $delay_humidify_config < 61 && $delay_humidify_config > -1 &&                                                            // Prüfung Verzögerung Feuchte
                $uv_period_config < 1441 && $uv_period_config > -1 &&  (($uv_period_config+$uv_duration_config) > 0) &&                 // Prüfung Intervall UV
                $uv_duration_config < 1441 && $uv_duration_config > -1  &&                              // Prüfung Dauer UV
                $switch_on_uv_hour_config >= 0 && $switch_on_uv_hour_config < 24 && $switch_on_uv_minute_config >= 0 && $switch_on_uv_minute_config < 60 && // UV Uhrzeit
                $light_period_config < 1441 && $light_period_config > -1 &&  (($light_period_config+$light_duration_config) > 0) &&                 // Prüfung Intervall Licht
                $light_duration_config < 1441 && $light_duration_config > -1  &&                              // Prüfung Dauer Licht
                $switch_on_light_hour_config >= 0 && $switch_on_light_hour_config < 24 && $switch_on_light_minute_config >= 0 && $switch_on_light_minute_config < 60 && // Licht Uhrzeit
                $internal_temperature_low_limit >= -11 && $internal_temperature_low_limit <= 70 && // Temperatur low limit Überwachung
                $internal_temperature_high_limit >= -11 && $internal_temperature_high_limit <= 70 && // Temperatur high limit Überwachung
                $internal_temperature_high_limit > $internal_temperature_low_limit &&
                $internal_temperature_hysteresis >= 1 && $internal_temperature_hysteresis <= 10  // Temperatur hysteresis für Event Generierung
            )
            {
                # Eingestellte Werte in config/config.json und logs/logfile.txt speichern
                write_config($switch_on_cooling_compressor_config, $switch_off_cooling_compressor_config,
                            $switch_on_humidifier_config, $switch_off_humidifier_config, $delay_humidify_config, $uv_modus_config, $uv_duration_config,
                            $uv_period_config, $switch_on_uv_hour_config, $switch_on_uv_minute_config, $light_modus_config, $light_duration_config,
                            $light_period_config, $switch_on_light_hour_config, $switch_on_light_minute_config, $dehumidifier_modus_config,
                            $failure_temperature_delta_config, $failure_humidity_delta_config, $internal_temperature_low_limit, $internal_temperature_high_limit, $internal_temperature_hysteresis,
                            $shutdown_on_batlow_config);
                logger('DEBUG', 'configvalues saved');
                # Formatierung für die Lesbarkeit im Logfile:
                # Modus
                if ($modus == 0) {
                    $operating_mode = _('cooling');
                    $switch_on_temperature_cooling = $setpoint_temperature + $switch_on_cooling_compressor_config;
                    $switch_off_temperature_cooling = $setpoint_temperature + $switch_off_cooling_compressor_config;
                }

                if ($modus == 1) {
                    $operating_mode = _('cooling with humidification');
                    $switch_on_temperature_cooling = $setpoint_temperature + $switch_on_cooling_compressor_config;
                    $switch_off_temperature_cooling = $setpoint_temperature + $switch_off_cooling_compressor_config;
                }

                if ($modus == 2) {
                    $operating_mode = _('heating with humidification');
                    $switch_on_temperature_cooling = $setpoint_temperature - $switch_on_cooling_compressor_config;
                    $switch_off_temperature_cooling = $setpoint_temperature - $switch_off_cooling_compressor_config;
                }
                if ($modus == 3) {
                    $operating_mode = _('automatic with humidification');
                    $switch_on_temperature_cooling = $setpoint_temperature + $switch_on_cooling_compressor_config;
                    $switch_off_temperature_cooling_cooling = $setpoint_temperature + $switch_off_cooling_compressor_config;
                    $switch_on_temperature_heating = $setpoint_temperature - $switch_on_cooling_compressor_config;
                    $switch_off_temperature_cooling_heating = $setpoint_temperature - $switch_off_cooling_compressor_config;
                }

                if ($modus == 4) {
                    $operating_mode = _('automatic with dehumidification and humidification');
                    $switch_on_temperature_cooling = $setpoint_temperature + $switch_on_cooling_compressor_config;
                    $switch_off_temperature_cooling_cooling = $setpoint_temperature + $switch_off_cooling_compressor_config;
                    $switch_on_temperature_heating = $setpoint_temperature - $switch_on_cooling_compressor_config;
                    $switch_off_temperature_cooling_heating = $setpoint_temperature - $switch_off_cooling_compressor_config;

                    $switch_on_humidify = $setpoint_humidity - $switch_on_humidifier_config;
                    $switch_off_humidify = $setpoint_humidity - $switch_off_humidifier_config;
                    $switch_on_dehumidify = $setpoint_humidity + $switch_on_humidifier_config;
                    $switch_off_dehumidify = $setpoint_humidity + $switch_off_humidifier_config;
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
                # Uv licht
                if ($uv_modus_config == 0) {
                    $uv_modus_name = _('off');
                    $logtext_uv = " ";
                    $logtext_uv_duration = " ";
                }
                if ($uv_modus_config == 1) {
                    $uv_modus_name = _('duration & period');
                    $logtext_uv = _('uv period') . ": " . $uv_period_config . " " . _('minutes');
                    $logtext_uv_duration = _('uv duration') . ": " . $uv_duration_config . " " . _('minutes');
                }
                if ($uv_modus_config == 2) {
                    $uv_modus_name = _('duration & timestamp');
                    $logtext_uv = _('uv timestamp') . ": ". $switch_on_uv_hour_config . ":" . $switch_on_uv_minute_config;
                    $logtext_uv_duration = _('uv duration') . ": ". $uv_duration_config . " " . _('minutes');
                }
                # Licht
                if ($light_modus_config == 0) {
                    $light_modus_name = _('off');
                    $logtext_light = " ";
                    $logtext_light_duration = " ";
                }
                if ($light_modus_config == 1) {
                    $light_modus_name = _('duration & period');
                    $logtext_light = _('light period').": ".$light_period_config ." "._('minutes');
                    $logtext_light_duration = _('light duration').": ".$light_duration_config ." "._('minutes');
                }
                if ($light_modus_config == 2) {
                    $light_modus_name = _('duration & timestamp');
                    $logtext_light = _('light timestamp').": ".$switch_on_light_hour_config.":".$switch_on_light_minute_config;
                    $logtext_light_duration = _('light duration').": ".$light_duration_config/60 ." "._('minutes');
                }
                // $circulation_air_duration = $circulation_air_duration/60;
                // $circulation_air_period = $circulation_air_period/60;
                // $exhausting_air_duration = $exhaust_air_duration/60;
                // $exhausting_air_period = $exhaust_air_period/60;
                $switch_on_humidity = $setpoint_humidity - $switch_on_humidifier_config;
                $switch_off_humidity = $setpoint_humidity - $switch_off_humidifier_config;
                
                $logstring = " \n ***********************************************";
                $logstring = $logstring . " \n " . _('configuration has been changed.') . " \n ";
                $logstring = $logstring . " \n " . _('operating mode') . ": " . $operating_mode;

                if ($modus == 0 || $modus == 1 || $modus == 2)  {
                    $logstring = $logstring . " \n " . _('setpoint temperature').": ".$setpoint_temperature." &deg;C";
                    $logstring = $logstring . " \n " . _('switch-off temperature').": ".$switch_off_cooling_compressor_config." &deg;C ("._('so at')." ".$switch_off_temperature_cooling." &deg;C)";
                    $logstring = $logstring . " \n " . _('switch-on temperature').": ".$switch_on_cooling_compressor_config." &deg;C ("._('so at')." ".$switch_on_temperature_cooling." &deg;C)";
                }
                
                if ($modus == 3 || $modus == 4)  {
                    $logstring = $logstring . " \n " . _('setpoint temperature').": ".$setpoint_temperature." &deg;C";
                    $logstring = $logstring . " \n " . _('switch-on heater').": ".$switch_on_cooling_compressor_config.' &deg;C ('._('so at')." ".$switch_on_temperature_heating.' &deg;C)';
                    $logstring = $logstring . " \n " . _('switch-off heater').": ".$switch_off_cooling_compressor_config." &deg;C ("._('so at')." ".$switch_off_temperature_cooling_heating." &deg;C)";
                    $logstring = $logstring . " \n " . _('switch-on cooler').": ".$switch_on_cooling_compressor_config." &deg;C ("._('so at')." ".$switch_on_temperature_cooling." &deg;C)";
                    $logstring = $logstring . " \n " . _('switch-off cooler').": ".$switch_off_cooling_compressor_config." &deg;C ("._('so at')." ".$switch_off_temperature_cooling_cooling." &deg;C)";
                }

                if ($modus == 1 || $modus == 2 || $modus == 3) {
                    $logstring = $logstring . " \n " . _('setpoint humidity').": ".$setpoint_humidity."% "."&phi;";
                    $logstring = $logstring . " \n " . _('switch-on humidifier').": ".$switch_on_humidifier_config." % &phi; ("._('so at')." ".$switch_on_humidity." % &phi;)";
                    $logstring = $logstring . " \n " . _('switch-off humidifier').": ".$switch_off_humidifier_config." % &phi; ("._('so at')." ".$switch_off_humidity." % &phi;)";
                    $logstring = $logstring . " \n " . _('delay humidifier')." ".$delay_humidify_config." "._('minutes');
                }

                if ($modus == 4) {
                    $logstring = $logstring . " \n " . _('setpoint humidity').": ".$setpoint_humidity."% &phi;";
                    $logstring = $logstring . " \n " . _('switch-on humidifier').": ".$switch_on_humidifier_config."% &phi; ("._('so at')." ".$switch_on_humidify."% &phi;)";
                    $logstring = $logstring . " \n " . _('switch-off humidifier').": ".$switch_off_humidifier_config."% &phi; ("._('so at')." ".$switch_off_humidify."% &phi;)";
                    $logstring = $logstring . " \n " . _('switch-on exhausting').": ".$switch_on_humidifier_config."% &phi; ("._('so at')." ".$switch_on_dehumidify."% &phi;)";
                    $logstring = $logstring . " \n " . _('switch-off exhausting').": ".$switch_off_humidifier_config."% &phi; ("._('so at')." ".$switch_off_dehumidify."% &phi;)";
                    $logstring = $logstring . " \n " . _('delay exhausting')." ".$delay_humidify_config ." ". _('minutes');
                }
                $logstring = $logstring . " \n " . _('circulation air period').": ".$circulation_air_period." "._('minutes');
                $logstring = $logstring . " \n " . _('circulation air duration').": ".$circulation_air_duration." "._('minutes');
                $logstring = $logstring . " \n " . _('exhausting air period')." ".$exhaust_air_period." "._('minutes');
                $logstring = $logstring . " \n " . _('exhausting air duration').": ".$exhaust_air_duration." "._('minutes');
                $logstring = $logstring . " \n " . _('dehumidify modus').": ".$dehumidifier_modus_name;
                $logstring = $logstring . " \n " . _('uv modus').": ".$uv_modus_name;
                $logstring = $logstring . " \n " . $logtext_uv;
                $logstring = $logstring . " \n " . $logtext_uv_duration;
                $logstring = $logstring . " \n " . _('light modus').": ".$light_modus_name;
                $logstring = $logstring . " \n " . $logtext_light;
                $logstring = $logstring . " \n " . $logtext_light_duration;
                $logstring = $logstring . " \n " . "***********************************************";
                logger('INFO', $logstring);

                $message_config = (_("values saved in file Database"));
            }
            else {
            logger('DEBUG', 'configvalues not in specified limits');
            $message_config = (_("values not in the specified limits!"));
            }
        }
        print '<script language="javascript"> alert("'. (_("general configuration")) . " : " .$message_config.'"); </script>';
    }
?>