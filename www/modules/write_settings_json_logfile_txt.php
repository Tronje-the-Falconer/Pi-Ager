<?php 
    include 'modules/read_config_json.php';                     // Liest die Grundeinstellungen Sensortyp, Hysteresen)
    #var_dump($_POST);
    # Prüfung der eingegebenen Werte
    if(isset ($_POST['modus_settings']) && $_POST['modus_settings'] <> NULL) {             // ist das $_POST-Array gesetzt
        $InputValid = '';
        foreach ($_POST as $CheckInput) {                                  // Prüfen, ob nur Zahlen eingegeben wurden
            if (preg_match('/[.\D]/', $CheckInput)) {
                $InputValid = _('unauthorized character - please use only positive integers!');
            }
        }

        if ($InputValid == ''){
            if ( $_POST['setpoint_temperature_settings']<23 &&  $_POST['setpoint_temperature_settings']>-1 &&                                                                    // Prüfung Soll-Temperatur
                $_POST['setpoint_humidity_settings']<100 && $_POST['setpoint_humidity_settings']>-1 &&                                                                      // Prüfung Soll-Feuchtigkeit
                $_POST['circulation_air_period_settings']<1441 && $_POST['circulation_air_period_settings']>-1 &&  (($_POST['circulation_air_period_settings']+$_POST['circulation_air_duration_settings'])>0) &&                 // Prüfung Intervall Umluft
                $_POST['circulation_air_duration_settings']<1441 && $_POST['circulation_air_duration_settings']>-1  &&                                                                // Prüfung Dauer Umluft
                $_POST['exhaust_air_period_settings']<1441 && $_POST['exhaust_air_period_settings']>-1 && (($_POST['exhaust_air_period_settings']+$_POST['exhaust_air_duration_settings'])>0) &&             // Prüfung Intervall Abluft
                $_POST['exhaust_air_duration_settings']<1441 && $_POST['exhaust_air_duration_settings']>-1                                                                  // Prüfung Dauer Abluft
            )
            {
                # Eingestellte Werte in config/settings.json und logs/logfile.txt speichern
                $timestamp = time();
                $array_settings_json = array( 'setpoint_temperature' => (float)$_POST['setpoint_temperature_settings'],
                    'modus' => (int)$_POST['modus_settings'],
                    'setpoint_humidity' => (float)$_POST['setpoint_humidity_settings'],
                    'circulation_air_duration' => (int)$_POST['circulation_air_duration_settings']*60,
                    'circulation_air_period' => (int)$_POST['circulation_air_period_settings']*60,
                    'exhaust_air_duration' => (int)$_POST['exhaust_air_duration_settings']*60,
                    'exhaust_air_period' => (int)$_POST['exhaust_air_period_settings']*60,
                    'last_change' => $timestamp);
                $jsoninput = json_encode($array_settings_json);
                file_put_contents('config/settings.json', $jsoninput);

                # Formatierung für die Lesbarkeit im Logfile:
                # Modus
                if ($array_settings_json['modus'] == 0) {
                    $operating_mode = _('cooling');
                    $switch_on_temperature_cooling = $array_settings_json['setpoint_temperature'] + $switch_on_cooling_compressor;
                    $switch_off_temperature_cooling = $array_settings_json['setpoint_temperature'] + $switch_off_cooling_compressor;
                }

                if ($array_settings_json['modus'] == 1) {
                    $operating_mode = _('cooling with humidification');
                    $switch_on_temperature_cooling = $array_settings_json['setpoint_temperature'] + $switch_on_cooling_compressor;
                    $switch_off_temperature_cooling = $array_settings_json['setpoint_temperature'] + $switch_off_cooling_compressor;
                }

                if ($array_settings_json['modus'] == 2) {
                    $operating_mode = _('heating with humidification');
                    $switch_on_temperature_cooling = $array_settings_json['setpoint_temperature'] - $switch_on_cooling_compressor;
                    $switch_off_temperature_cooling = $array_settings_json['setpoint_temperature'] - $switch_off_cooling_compressor;
                }
                if ($array_settings_json['modus'] == 3) {
                    $operating_mode = _('automatic with humidification');
                    $switch_on_temperature_cooling = $array_settings_json['setpoint_temperature'] + $switch_on_cooling_compressor;
                    $switch_off_temperature_cooling_cooling = $array_settings_json['setpoint_temperature'] + $switch_off_cooling_compressor;
                    $switch_on_temperature_heating = $array_settings_json['setpoint_temperature'] - $switch_on_cooling_compressor;
                    $switch_off_temperature_cooling_heating = $array_settings_json['setpoint_temperature'] - $switch_off_cooling_compressor;
                }

                if ($array_settings_json['modus'] == 4) {
                    $operating_mode = _('automatic with dehumidification and humidification');
                    $switch_on_temperature_cooling = $array_settings_json['setpoint_temperature'] + $switch_on_cooling_compressor;
                    $switch_off_temperature_cooling_cooling = $array_settings_json['setpoint_temperature'] + $switch_off_cooling_compressor;
                    $switch_on_temperature_heating = $array_settings_json['setpoint_temperature'] - $switch_on_cooling_compressor;
                    $switch_off_temperature_cooling_heating = $array_settings_json['setpoint_temperature'] - $switch_off_cooling_compressor;

                    $switch_on_humidify = $array_settings_json['setpoint_humidity'] - $switch_on_humidifier;
                    $switch_off_humidify = $array_settings_json['setpoint_humidity'] - $switch_off_humidifier;
                    $switch_on_dehumidify = $array_settings_json['setpoint_humidity'] + $switch_on_humidifier;
                    $switch_off_dehumidify = $array_settings_json['setpoint_humidity'] + $switch_off_humidifier;
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
                    $logtext_uv = _('uv period').": ".$uv_period /60 ." "._('minutes');
                    $logtext_uv_duration = _('uv duration').": ".$uv_duration/60 ." "._('minutes');
                }
                if ($uv_modus == 2) {
                    $uv_modus_name = _('duration & timestamp');
                    $logtext_uv = _('uv timestamp').": ".switch_on_uv_hour.":".$switch_on_uv_minute;
                    $logtext_uv_duration = _('uv duration').": ".$uv_duration/60 ." "._('minutes');
                    
                }
                # Licht
                if ($light_modus == 0) {
                    $light_modus_name = _('off');
                    $logtext_light = " ";
                    $logtext_light_duration = " ";
                }
                if ($light_modus == 1) {
                    $light_modus_name = _('duration & period');
                    $logtext_light = _('light period').": ".$light_period/60 ." "._('minutes');
                    $logtext_light_duration = _('light duration').": ".$light_duration/60 ." "._('minutes');
                }
                if ($light_modus == 2) {
                    $light_modus_name = _('duration & timestamp');
                    $logtext_light = _('light timestamp').": ".$switch_on_light_hour.":".$switch_on_light_minute;
                    $logtext_light_duration = _('light duration').": ".$light_duration/60 ." "._('minutes');
                    
                }
                $circulation_air_duration = $array_settings_json['circulation_air_duration']/60;
                $circulation_air_period = $array_settings_json['circulation_air_period']/60;
                $exhausting_air_duration = $array_settings_json['exhaust_air_duration']/60;
                $exhausting_air_period = $array_settings_json['exhaust_air_period']/60;
                $switch_on_humidity = $array_settings_json['setpoint_humidity'] - $switch_on_humidifier;
                $switch_off_humidity = $array_settings_json['setpoint_humidity'] - $switch_off_humidifier;

                $f=fopen('logs/logfile.txt','a');
                fwrite($f, "\n"."***********************************************");
                fwrite($f, "\n"._('sensor').": ".$sensorname);
                fwrite($f, "\n". _('operating mode').": ".$operating_mode);
                fwrite($f, "\n".date('d.m.Y H:i').' '._('values have been manually changed.'));
                fwrite($f, "\n");

                if ($array_settings_json['modus'] == 0 || $array_settings_json['modus'] == 1 || $array_settings_json['modus'] == 2)  {
                    fwrite($f, "\n"._('setpoint temperature').": ".$array_settings_json['setpoint_temperature']."&deg;C");
                    fwrite($f, "\n"._('switch-off temperature').": ".$switch_off_cooling_compressor."&deg;C ("._('so at')." ".$switch_off_temperature_cooling."&deg;C)");
                    fwrite($f, "\n"._('switch-on temperature').": ".$switch_on_cooling_compressor."&deg;C ("._('so at')." ".$switch_on_temperature."&deg;C)");
                }

                if ($array_settings_json['modus'] == 3 || $array_settings_json['modus'] == 4)  {
                    fwrite($f, "\n"._('setpoint temperature').": ".$array_settings_json['setpoint_temperature']."&deg;C");
                    fwrite($f, "\n"._('switch-on heater').": ".$switch_on_cooling_compressor.'&deg;C ('._('so at')." ".$switch_on_temperature_heating.'&deg;C)');
                    fwrite($f, "\n"._('switch-off heater').": ".$switch_off_cooling_compressor."&deg;C ("._('so at')." ".$switch_off_temperature_cooling_heating."&deg;C)");
                    fwrite($f, "\n"._('switch-on cooler').": ".$switch_on_cooling_compressor."&deg;C ("._('so at')." ".$switch_on_temperature_cooling."&deg;C)");
                    fwrite($f, "\n"._('switch-off cooler').": ".$switch_off_cooling_compressor."&deg;C ("._('so at')." ".$switch_off_temperature_cooling_cooling."&deg;C)");
                }

                fwrite($f, "\n");

                if ($array_settings_json['modus'] == 1 || $array_settings_json['modus'] == 2 || $array_settings_json['modus'] == 3) {
                    fwrite($f, "\n"._('setpoint humidity').": ".$array_settings_json['setpoint_humidity']."% "."&phi;");
                    fwrite($f, "\n"._('switch-on humidifier').": ".$switch_on_humidifier."% &phi; ("._('so at')." ".$switch_on_humidity."% &phi;)");
                    fwrite($f, "\n"._('switch-off humidifier').": ".$switch_off_humidifier."% &phi; ("._('so at')." ".$switch_off_humidity."% &phi;)");
                    fwrite($f, "\n"._('delay humidifier')." ".$delay_humidify._('minutes'));
                }

                if ($array_settings_json['modus'] == 4) {
                    fwrite($f, "\n"._('setpoint humidity').": ".$array_settings_json['setpoint_humidity']."% &phi;");
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
                print '<p id="info-message" style="color: #ff0000; font-size: 20px;"><b>'. (sprintf(_("values saved in file %s"), "config/settings.json")) .'</b></p>
                    <script language="javascript">
                        setTimeout(function(){document.getElementById("info-message").style.display="none"}, 3000)
                    </script>';
            }
            # 3Sekunden Anzeige dass die Werte nicht gespeichert wurden
            else {
                print '<p id="info-message" style="color: #ff0000; font-size: 20px;"><b>'. (_("values not in the specified limits!")) .'</b></p>
                    <script language="javascript">
                        setTimeout(function(){document.getElementById("info-message").style.display="none"}, 3000)
                    </script>';
            }
        }
        else {
            print '<p id="info-message" style="color: #ff0000; font-size: 20px;"><b>'.$InputValid.'</b></p>
                <script language="javascript">
                    setTimeout(function(){document.getElementById("info-message").style.display="none"}, 3000)
                </script>';
        }
    }
?>
