<?php 
    // include 'modules/read_config_db.php';                     // Liest die Grundeinstellungen Sensortyp, Hysteresen)
    #var_dump($_POST);
    
    $message_settings='';
    # Prüfung der eingegebenen Werte
    if(!empty($_POST['manvals_form_submit']))
        {
            $modus_setting = $_POST['modus_settings'];
            $setpoint_temperature_setting = $_POST['setpoint_temperature_settings'];
            $setpoint_humidity_setting = $_POST['setpoint_humidity_settings'];
            $circulation_air_period_setting = $_POST['circulation_air_period_settings'];
            $circulation_air_duration_setting = $_POST['circulation_air_duration_settings'];
            $exhaust_air_period_setting = $_POST['exhaust_air_period_settings'];
            $exhaust_air_duration_setting = $_POST['exhaust_air_duration_settings'];
        
        // if(isset ($modus_setting) && $modus_setting <> NULL) {             // ist das $_POST-Array gesetzt
        if($modus_setting != NULL) {             // ist das $_POST-Array gesetzt
            $SettingsInputIsValid = TRUE;
            foreach ($_POST as $CheckInput) {                                  // Prüfen, ob nur Zahlen eingegeben wurden
                if ($CheckInput != 'manvals_form_submit') {
                    if (preg_match('/[.\D]/', $CheckInput)) {
                        $message_settings = _('unauthorized character - please use only positive integers!');
                        $SettingsInputIsValid = FALSE;
                    }
                }
            }
            
            if ($SettingsInputIsValid == TRUE)
                {
                    if ( $setpoint_temperature_setting<23 &&  $setpoint_temperature_setting>-1 &&                                                                    // Prüfung Soll-Temperatur
                        $setpoint_humidity_setting<100 && $setpoint_humidity_setting>-1 &&                                                                      // Prüfung Soll-Feuchtigkeit
                        $circulation_air_period_setting<1441 && $circulation_air_period_setting>-1 &&  (($circulation_air_period_setting+$circulation_air_duration_setting)>0) &&                 // Prüfung Intervall Umluft
                        $circulation_air_duration_setting<1441 && $circulation_air_duration_setting>-1  &&                                                                // Prüfung Dauer Umluft
                        $exhaust_air_period_setting<1441 && $exhaust_air_period_setting>-1 && (($exhaust_air_period_setting+$exhaust_air_duration_setting)>0) &&             // Prüfung Intervall Abluft
                        $exhaust_air_duration_setting<1441 && $exhaust_air_duration_setting>-1                                                                  // Prüfung Dauer Abluft
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
                        if ($modus_setting == 0) {
                            $operating_mode = _('cooling');
                            $switch_on_temperature_cooling = $setpoint_temperature_setting + $switch_on_cooling_compressor;
                            $switch_off_temperature_cooling = $setpoint_temperature_setting + $switch_off_cooling_compressor;
                        }

                        if ($modus_setting == 1) {
                            $operating_mode = _('cooling with humidification');
                            $switch_on_temperature_cooling = $setpoint_temperature_setting + $switch_on_cooling_compressor;
                            $switch_off_temperature_cooling = $setpoint_temperature_setting + $switch_off_cooling_compressor;
                        }

                        if ($modus_setting == 2) {
                            $operating_mode = _('heating with humidification');
                            $switch_on_temperature_cooling = $setpoint_temperature_setting - $switch_on_cooling_compressor;
                            $switch_off_temperature_cooling = $setpoint_temperature_setting - $switch_off_cooling_compressor;
                        }
                        if ($modus_setting == 3) {
                            $operating_mode = _('automatic with humidification');
                            $switch_on_temperature_cooling = $setpoint_temperature_setting + $switch_on_cooling_compressor;
                            $switch_off_temperature_cooling_cooling = $setpoint_temperature_setting + $switch_off_cooling_compressor;
                            $switch_on_temperature_heating = $setpoint_temperature_setting - $switch_on_cooling_compressor;
                            $switch_off_temperature_cooling_heating = $setpoint_temperature_setting - $switch_off_cooling_compressor;
                        }

                        if ($modus_setting == 4) {
                            $operating_mode = _('automatic with dehumidification and humidification');
                            $switch_on_temperature_cooling = $setpoint_temperature_setting + $switch_on_cooling_compressor;
                            $switch_off_temperature_cooling_cooling = $setpoint_temperature_setting + $switch_off_cooling_compressor;
                            $switch_on_temperature_heating = $setpoint_temperature_setting - $switch_on_cooling_compressor;
                            $switch_off_temperature_cooling_heating = $setpoint_temperature_setting - $switch_off_cooling_compressor;

                            $switch_on_humidify = $setpoint_humidity_setting - $switch_on_humidifier;
                            $switch_off_humidify = $setpoint_humidity_setting - $switch_off_humidifier;
                            $switch_on_dehumidify = $setpoint_humidity_setting + $switch_on_humidifier;
                            $switch_off_dehumidify = $setpoint_humidity_setting + $switch_off_humidifier;
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
                        # Uv licht
                        if ($uv_modus == 0) {
                            $uv_modus_name = _('off');
                            $logtext_uv = " ";
                            $logtext_uv_duration = " ";
                        }
                        if ($uv_modus == 1) {
                            $uv_modus_name = _('duration & period');
                            $logtext_uv = _('uv period').": ".$uv_period ." "._('minutes');
                            $logtext_uv_duration = _('uv duration').": ".$uv_duration ." "._('minutes');
                        }
                        if ($uv_modus == 2) {
                            $uv_modus_name = _('duration & timestamp');
                            $logtext_uv = _('uv timestamp').": ".switch_on_uv_hour.":".$switch_on_uv_minute;
                            $logtext_uv_duration = _('uv duration').": ".$uv_duration ." "._('minutes');
                            
                        }
                        # Licht
                        if ($light_modus == 0) {
                            $light_modus_name = _('off');
                            $logtext_light = " ";
                            $logtext_light_duration = " ";
                        }
                        if ($light_modus == 1) {
                            $light_modus_name = _('duration & period');
                            $logtext_light = _('light period').": ".$light_period ." "._('minutes');
                            $logtext_light_duration = _('light duration').": ".$light_duration ." "._('minutes');
                        }
                        if ($light_modus == 2) {
                            $light_modus_name = _('duration & timestamp');
                            $logtext_light = _('light timestamp').": ".$switch_on_light_hour.":".$switch_on_light_minute;
                            $logtext_light_duration = _('light duration').": ".$light_duration ." "._('minutes');
                            
                        }
                        $circulation_air_duration = $circulation_air_duration_setting;
                        $circulation_air_period = $circulation_air_period_setting;
                        $exhausting_air_duration = $exhaust_air_duration_setting;
                        $exhausting_air_period = $exhaust_air_period_setting;
                        $switch_on_humidity = $setpoint_humidity_setting - $switch_on_humidifier;
                        $switch_off_humidity = $setpoint_humidity_setting - $switch_off_humidifier;

                        $f=fopen('logs/logfile.txt','a');
                        fwrite($f, "\n"."***********************************************");
                        fwrite($f, "\n"._('sensor').": ".$sensorname);
                        fwrite($f, "\n". _('operating mode').": ".$operating_mode);
                        fwrite($f, "\n".date('d.m.Y H:i:s').' '._('values have been manually changed.'));
                        fwrite($f, "\n");

                        if ($modus_setting == 0 || $modus_setting == 1 || $modus_setting == 2)  {
                            fwrite($f, "\n"._('setpoint temperature').": ".$setpoint_temperature_setting."&deg;C");
                            fwrite($f, "\n"._('switch-off temperature').": ".$switch_off_cooling_compressor."&deg;C ("._('so at')." ".$switch_off_temperature_cooling."&deg;C)");
                            fwrite($f, "\n"._('switch-on temperature').": ".$switch_on_cooling_compressor."&deg;C ("._('so at')." ".$switch_on_temperature_cooling."&deg;C)");
                        }

                        if ($modus_setting == 3 || $modus_setting == 4)  {
                            fwrite($f, "\n"._('setpoint temperature').": ".$setpoint_temperature_setting."&deg;C");
                            fwrite($f, "\n"._('switch-on heater').": ".$switch_on_cooling_compressor.'&deg;C ('._('so at')." ".$switch_on_temperature_heating.'&deg;C)');
                            fwrite($f, "\n"._('switch-off heater').": ".$switch_off_cooling_compressor."&deg;C ("._('so at')." ".$switch_off_temperature_cooling_heating."&deg;C)");
                            fwrite($f, "\n"._('switch-on cooler').": ".$switch_on_cooling_compressor."&deg;C ("._('so at')." ".$switch_on_temperature_cooling."&deg;C)");
                            fwrite($f, "\n"._('switch-off cooler').": ".$switch_off_cooling_compressor."&deg;C ("._('so at')." ".$switch_off_temperature_cooling_cooling."&deg;C)");
                        }

                        fwrite($f, "\n");

                        if ($modus_setting == 1 || $modus_setting == 2 || $modus_setting == 3) {
                            fwrite($f, "\n"._('setpoint humidity').": ".$setpoint_humidity_setting."% "."&phi;");
                            fwrite($f, "\n"._('switch-on humidifier').": ".$switch_on_humidifier."% &phi; ("._('so at')." ".$switch_on_humidity."% &phi;)");
                            fwrite($f, "\n"._('switch-off humidifier').": ".$switch_off_humidifier."% &phi; ("._('so at')." ".$switch_off_humidity."% &phi;)");
                            fwrite($f, "\n"._('delay humidifier')." ".$delay_humidify._('minutes'));
                        }

                        if ($modus_setting == 4) {
                            fwrite($f, "\n"._('setpoint humidity').": ".$setpoint_humidity_setting."% &phi;");
                            fwrite($f, "\n"._('switch-on humidifier').": ".$switch_on_humidifier."% &phi; ("._('so at')." ".$switch_on_humidify."% &phi;)");
                            fwrite($f, "\n"._('switch-off humidifier').": ".$switch_off_humidifier."% &phi; ("._('so at')." ".$switch_off_humidify."% &phi;)");
                            fwrite($f, "\n"._('switch-on exhausting').": ".$switch_on_humidifier."% &phi; ("._('so at')." ".$switch_on_dehumidify."% &phi;)");
                            fwrite($f, "\n"._('switch-off exhausting').": ".$switch_off_humidifier."% &phi; ("._('so at')." ".$switch_off_dehumidify."% &phi;)");
                            fwrite($f, "\n"._('delay exhausting')." ".$delay_humidify._('minutes'));
                        }

                        fwrite($f, "\n");
                        fwrite($f, "\n"._('circulation air period').": ".$circulation_air_period." "._('minutes'));
                        fwrite($f, "\n"._('circulation air duration').": ".$circulation_air_duration." "._('minutes'));


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
                        fwrite($f, "\n"._('language').": ".$language);


                        fwrite($f, "\n"."***********************************************");
                        fclose($f);

                        # 3Sekunden Anzeige dass die Werte gespeichert wurden
                        $message_settings = (_("values saved in file Database"));
                    }
                    else {
                        $message_settings = (_("values not in the specified limits!"));
                        }
                }
        }
    }
    # 3Sekunden Anzeige der message_settings
    print '<p id="info-message_settings" style="color: #ff0000; font-size: 20px;"><b>'.$message_settings.'</b></p>
                <script language="javascript">
                    setTimeout(function(){document.getElementById("info-message_settings").style.display="none"}, 3000)
                </script>';
    // usleep (500000);

?>
