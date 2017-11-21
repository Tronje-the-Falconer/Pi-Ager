<?php 
    // include 'modules/read_settings_db.php';                     // Liest die manuellen Werte aus
    #var_dump($_POST);
    
    $message_config='';
    # Prüfung der eingegebenen Werte
    if(!empty($_POST['config_form_submit']))
        {                       // ist das $_POST-Array gesetzt
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
        $sensortype_config = $_POST['sensortype_config'];
        $language_config = $_POST['language_config'];
        $uv_modus_config = $_POST['uv_modus_config'];
        $light_modus_config = $_POST['light_modus_config'];
        $dehumidifier_modus_config = $_POST['dehumidifier_modus_config'];
        $failure_temperature_delta_config = $_POST['failure_temperature_delta_config'];
        $failure_humidity_delta_config = $_POST['failure_humidity_delta_config'];
        $referenceunit_scale1_config = $_POST['referenceunit_scale1_config'];
        $measuring_interval_scale1_config = $_POST['measuring_interval_scale1_config'];
        $measuring_duration_scale1_config = $_POST['measuring_duration_scale1_config'];
        $saving_period_scale1_config = $_POST['saving_period_scale1_config'];
        $samples_scale1_config = $_POST['samples_scale1_config'];
        $spikes_scale1_config = $_POST['spikes_scale1_config'];
        $referenceunit_scale2_config = $_POST['referenceunit_scale2_config'];
        $measuring_interval_scale2_config = $_POST['measuring_interval_scale2_config'];
        $measuring_duration_scale2_config = $_POST['measuring_duration_scale2_config'];
        $saving_period_scale2_config = $_POST['saving_period_scale2_config'];
        $samples_scale2_config = $_POST['samples_scale2_config'];
        $spikes_scale2_config = $_POST['spikes_scale2_config'];

        $ConfigInputIsValid = TRUE;
        foreach ($_POST as $CheckInput) {                                  // Prüfen, ob nur Zahlen eingegeben wurden
            if ($CheckInput != 'config_form_submit') {
                if (preg_match('/[.\D]/', $CheckInput)) {
                    $message_config = _('unauthorized character - please use only positive integers!');
                    $ConfigInputIsValid = FALSE;
                }
            }
        }

        if ($ConfigInputIsValid == TRUE)
        {
            if ( $switch_on_cooling_compressor_config<11 && $switch_on_cooling_compressor_config>-1 && ($switch_on_cooling_compressor_config != $switch_off_cooling_compressor_config) &&       // Prüfung Einschaltwert setpoint_temperature.
                ($switch_on_cooling_compressor_config > $switch_off_cooling_compressor_config) &&                                                              // Prüfung Einschaltwert setpoint_temperature.
                $switch_off_cooling_compressor_config<11 && $switch_off_cooling_compressor_config>-1 &&                                                        // Prüfung Ausschaltwert setpoint_temperature.
                $switch_on_humidifier_config<11 && $switch_on_humidifier_config>-1 && ($switch_on_humidifier_config != $switch_off_humidifier_config) &&          // Prüfung Einschaltwert Feuchte
                ($switch_on_humidifier_config > $switch_off_humidifier_config) &&                                                               // Prüfung Einschaltwert Feuchte
                $switch_off_humidifier_config<11 && $switch_off_humidifier_config>-1 &&                                                          // Prüfung Ausschaltwert Feuchte
                $delay_humidify_config<61 && $delay_humidify_config>-1 &&                                                            // Prüfung Verzögerung Feuchte
                $uv_period_config<1441 && $uv_period_config>-1 &&  (($uv_period_config+$uv_duration_config)>0) &&                 // Prüfung Intervall UV
                $uv_duration_config<1441 && $uv_duration_config>-1  &&                              // Prüfung Dauer UV
                $switch_on_uv_hour_config>=0 && $switch_on_uv_hour_config<24 && $switch_on_uv_minute_config>=0 && $switch_on_uv_minute_config<60 && // UV Uhrzeit
                $light_period_config<1441 && $light_period_config>-1 &&  (($light_period_config+$light_duration_config)>0) &&                 // Prüfung Intervall Licht
                $light_duration_config<1441 && $light_duration_config>-1  &&                              // Prüfung Dauer Licht
                $switch_on_light_hour_config>=0 && $switch_on_light_hour_config<24 && $switch_on_light_minute_config>=0 && $switch_on_light_minute_config<60 // Licht Uhrzeit
            )
            {
                # Eingestellte Werte in config/config.json und logs/logfile.txt speichern
                write_config($sensortype_config, $language_config, $switch_on_cooling_compressor_config, $switch_off_cooling_compressor_config,
                            $switch_on_humidifier_config, $switch_off_humidifier_config, $delay_humidify_config, $uv_modus_config, $uv_duration_config,
                            $uv_period_config, $switch_on_uv_hour_config, $switch_on_uv_minute_config, $light_modus_config, $light_duration_config,
                            $light_period_config, $switch_on_light_hour_config, $switch_on_light_minute_config, $dehumidifier_modus_config,
                            $failure_temperature_delta_config, $failure_humidity_delta_config,
                            $referenceunit_scale1_config, $measuring_interval_scale1_config, $measuring_duration_scale1_config, $saving_period_scale1_config, $samples_scale1_config, $spikes_scale1_config,
                            $referenceunit_scale2_config, $measuring_interval_scale2_config, $measuring_duration_scale2_config, $saving_period_scale2_config, $samples_scale2_config, $spikes_scale2_config);

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
                # Sensor
                if ($sensortype_config == 1) {
                    $sensortype = 1;
                    $sensorname='DHT11';
                }
                if ($sensortype_config == 2) {
                    $sensortype = 2;
                    $sensorname='DHT22';
                }
                if ($sensortype_config == 3) {
                    $sensortype = 3;
                    $sensorname='SHT75';
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
                #Language
                if ($language_config == 1) {
                    $language_name = _('de_DE');
                }
                if ($language_config == 2) {
                    $language_name = _('en_EN');
                }
                
                $circulation_air_duration = $circulation_air_duration/60;
                $circulation_air_period = $circulation_air_period/60;
                $exhausting_air_duration = $exhaust_air_duration/60;
                $exhausting_air_period = $exhaust_air_period/60;
                $switch_on_humidity = $setpoint_humidity - $switch_on_humidifier_config;
                $switch_off_humidity = $setpoint_humidity - $switch_off_humidifier_config;
                
                

                $f=fopen('logs/logfile.txt','a');
                fwrite($f, "\n"."***********************************************");
                fwrite($f, "\n"._('sensor').": ".$sensorname);
                fwrite($f, "\n". _('operating mode').": ".$operating_mode);
                fwrite($f, "\n".date('d.m.Y H:i:s').' '._('configuration has been changed.'));
                fwrite($f, "\n");

                if ($modus == 0 || $modus == 1 || $modus == 2)  {
                    fwrite($f, "\n"._('setpoint temperature').": ".$setpoint_temperature."&deg;C");
                    fwrite($f, "\n"._('switch-off temperature').": ".$switch_off_cooling_compressor_config."&deg;C ("._('so at')." ".$switch_off_temperature_cooling."&deg;C)");
                    fwrite($f, "\n"._('switch-on temperature').": ".$switch_on_cooling_compressor_config."&deg;C ("._('so at')." ".$switch_on_temperature."&deg;C)");
                }

                if ($modus == 3 || $modus == 4)  {
                    fwrite($f, "\n"._('setpoint temperature').": ".$setpoint_temperature."&deg;C");
                    fwrite($f, "\n"._('switch-on heater').": ".$switch_on_cooling_compressor_config.'&deg;C ('._('so at')." ".$switch_on_temperature_heating.'&deg;C)');
                    fwrite($f, "\n"._('switch-off heater').": ".$switch_off_cooling_compressor_config."&deg;C ("._('so at')." ".$switch_off_temperature_cooling_heating."&deg;C)");
                    fwrite($f, "\n"._('switch-on cooler').": ".$switch_on_cooling_compressor_config."&deg;C ("._('so at')." ".$switch_on_temperature_cooling."&deg;C)");
                    fwrite($f, "\n"._('switch-off cooler').": ".$switch_off_cooling_compressor_config."&deg;C ("._('so at')." ".$switch_off_temperature_cooling_cooling."&deg;C)");
                }

                fwrite($f, "\n");

                if ($modus == 1 || $modus == 2 || $modus == 3) {
                    fwrite($f, "\n"._('setpoint humidity').": ".$setpoint_humidity."% "."&phi;");
                    fwrite($f, "\n"._('switch-on humidifier').": ".$switch_on_humidifier_config."% &phi; ("._('so at')." ".$switch_on_humidity."% &phi;)");
                    fwrite($f, "\n"._('switch-off humidifier').": ".$switch_off_humidifier_config."% &phi; ("._('so at')." ".$switch_off_humidity."% &phi;)");
                    fwrite($f, "\n"._('delay humidifier')." ".$delay_humidify_config._('minutes'));
                }

                if ($modus == 4) {
                    fwrite($f, "\n"._('setpoint humidity').": ".$setpoint_humidity."% &phi;");
                    fwrite($f, "\n"._('switch-on humidifier').": ".$switch_on_humidifier_config."% &phi; ("._('so at')." ".$switch_on_humidify."% &phi;)");
                    fwrite($f, "\n"._('switch-off humidifier').": ".$switch_off_humidifier_config."% &phi; ("._('so at')." ".$switch_off_humidify."% &phi;)");
                    fwrite($f, "\n"._('switch-on exhausting').": ".$switch_on_humidifier_config."% &phi; ("._('so at')." ".$switch_on_dehumidify."% &phi;)");
                    fwrite($f, "\n"._('switch-off exhausting').": ".$switch_off_humidifier_config."% &phi; ("._('so at')." ".$switch_off_dehumidify."% &phi;)");
                    fwrite($f, "\n"._('delay exhausting')." ".$delay_humidify_config._('minutes'));
                }

                fwrite($f, "\n");
                fwrite($f, "\n"._('circulation air period').": ".$circulation_air_period." "._('minutes'));
                fwrite($f, "\n"._('circulation air duration').": ".$circulation_air_duration._('minutes'));


                fwrite($f, "\n");
                fwrite($f, "\n"._('exhausting air period')." ".$exhausting_air_period." "._('minutes'));
                fwrite($f, "\n"._('exhausting air duration').": ".$exhausting_air_duration." "._('minutes'));


                fwrite($f, "\n");
                fwrite($f, "\n"._('dehumidify modus').": ".$dehumidifier_modus_name);


                fwrite($f, "\n");
                fwrite($f, "\n"._('uv modus').": ".$uv_modus_name);
                fwrite($f, "\n".$logtext_uv);
                fwrite($f, "\n".$logtext_uv_duration);


                fwrite($f, "\n");
                fwrite($f, "\n"._('light modus').": ".$light_modus_name);
                fwrite($f, "\n".$logtext_light);
                fwrite($f, "\n".$logtext_light_duration);

                fwrite($f, "\n");
                fwrite($f, "\n"._('reference unit scale1').": ".$referenceunit_scale1_config);
                fwrite($f, "\n"._('reference unit scale2').": ".$referenceunit_scale2_config);

                fwrite($f, "\n");
                fwrite($f, "\n"._('language').": ".$language_name);

                fwrite($f, "\n"."***********************************************");
                fclose($f);
                
                $message_config = (_("values saved in file Database"));
            }
            else {
            $message_config = (_("values not in the specified limits!"));
            }
        }
        print '<script language="javascript"> alert("'. (_("general configuration")) . " : " .$message_config.'"); </script>';
    }
?>