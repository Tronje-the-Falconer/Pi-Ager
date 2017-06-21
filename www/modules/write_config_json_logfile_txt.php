<?php 
#var_dump($_POST);
    # Prüfung der eingegebenen Werte
    if(isset $modus && $modus <>NULL) {                       // ist das $_POST-Array gesetzt
        $InputValid = '';
        foreach ($_POST as $CheckInput) {                                  // Prüfen, ob nur Zahlen eingegeben wurden
            if (preg_match('/[.\D]/', $CheckInput)) {
                $InputValid = _('unauthorized character - please use only positive integers!');
            }
        }

        if ($InputValid == ''){
            if ( $_POST['switch_on_cooling_compressor_config']<11 && $_POST['switch_on_cooling_compressor_config']>-1 && ($_POST['switch_on_cooling_compressor_config'] != $_POST['switch_off_cooling_compressor_config']) &&       // Prüfung Einschaltwert setpoint_temperature.
                ($_POST['switch_on_cooling_compressor_config'] > $_POST['switch_off_cooling_compressor_config']) &&                                                              // Prüfung Einschaltwert setpoint_temperature.
                $_POST['switch_off_cooling_compressor_config']<11 && $_POST['switch_off_cooling_compressor_config']>-1 &&                                                        // Prüfung Ausschaltwert setpoint_temperature.
                $_POST['switch_on_humidifier_config']<11 && $_POST['switch_on_humidifier_config']>-1 && ($_POST['switch_on_humidifier_config'] != $_POST['switch_off_humidifier_config']) &&          // Prüfung Einschaltwert Feuchte
                ($_POST['switch_on_humidifier_config'] > $_POST['switch_off_humidifier_config']) &&                                                               // Prüfung Einschaltwert Feuchte
                $_POST['switch_off_humidifier_config']<11 && $_POST['switch_off_humidifier_config']>-1 &&                                                          // Prüfung Ausschaltwert Feuchte
                $_POST['delay_humidify_config']<61 && $_POST['delay_humidify_config']>-1                                                             // Prüfung Verzögerung Feuchte
            )
            {
                # Eingestellte Werte in config/config.json und logs/logfile.txt speichern
                $timestamp = time();
                $array_config_json = array( 'switch_on_cooling_compressor' => (float)$_POST['switch_on_cooling_compressor_config'],
                    'switch_off_cooling_compressor' => (float)$_POST['switch_off_cooling_compressor_config'],
                    'switch_on_humidifier' => (float)$_POST['switch_on_humidifier_config'],
                    'switch_off_humidifier' => (float)$_POST['switch_off_humidifier_config'],
                    'delay_humidify' => (float)$_POST['delay_humidify_config'],
                    'sensortype' => (int)$_POST['sensortype_config'],
                    'language' => $_POST['language_config'],
                    'last_change' => $timestamp);
                $configjsoninput = json_encode($array_config_json);
                file_put_contents('config/config.json', $configjsoninput);

                # Formatierung für die Lesbarkeit im Logfile:
                # Modus
                if ($modus == 0) {
                    $operating_mode = _('cooling');
                    $switch_on_temperature_cooling = $setpoint_temperature + $array_config_json['switch_on_cooling_compressor'];
                    $switch_off_temperature_cooling = $setpoint_temperature + $array_config_json['switch_off_cooling_compressor'];
                }

                if ($modus == 1) {
                    $operating_mode = _('cooling with humidification');
                    $switch_on_temperature_cooling = $setpoint_temperature + $array_config_json['switch_on_cooling_compressor'];
                    $switch_off_temperature_cooling = $setpoint_temperature + $array_config_json['switch_off_cooling_compressor'];
                }

                if ($modus == 2) {
                    $operating_mode = _('heating with humidification');
                    $switch_on_temperature_cooling = $setpoint_temperature - $array_config_json['switch_on_cooling_compressor'];
                    $switch_off_temperature_cooling = $setpoint_temperature - $array_config_json['switch_off_cooling_compressor'];
                }
                if ($modus == 3) {
                    $operating_mode = _('automatic with humidification');
                    $switch_on_temperature_cooling = $setpoint_temperature + $array_config_json['switch_on_cooling_compressor'];
                    $switch_off_temperature_cooling_cooling = $setpoint_temperature + $array_config_json['switch_off_cooling_compressor'];
                    $switch_on_temperature_heating = $setpoint_temperature - $array_config_json['switch_on_cooling_compressor'];
                    $switch_off_temperature_cooling_heating = $setpoint_temperature - $array_config_json['switch_off_cooling_compressor'];
                }

                if ($modus == 4) {
                    $operating_mode = _('automatic with dehumidification and humidification');
                    $switch_on_temperature_cooling = $setpoint_temperature + $array_config_json['switch_on_cooling_compressor'];
                    $switch_off_temperature_cooling_cooling = $setpoint_temperature + $array_config_json['switch_off_cooling_compressor'];
                    $switch_on_temperature_heating = $setpoint_temperature - $array_config_json['switch_on_cooling_compressor'];
                    $switch_off_temperature_cooling_heating = $setpoint_temperature - $array_config_json['switch_off_cooling_compressor'];

                    $switch_on_humidify = $setpoint_humidity - $array_config_json['switch_on_humidifier'];
                    $switch_off_humidify = $setpoint_humidity - $array_config_json['switch_off_humidifier'];
                    $switch_on_dehumidify = $setpoint_humidity + $array_config_json['switch_on_humidifier'];
                    $switch_off_dehumidify = $setpoint_humidity + $array_config_json['switch_off_humidifier'];
                }
                # Sensor
                if ($array_config_json['sensortype'] == 1) {
                    $sensortype = 1;
                    $sensorname='DHT11';
                }
                if ($array_config_json['sensortype'] == 2) {
                    $sensortype = 2;
                    $sensorname='DHT22';
                }
                if ($array_config_json['sensortype'] == 3) {
                    $sensortype = 3;
                    $sensorname='SHT75';
                }
                
                $circulation_air_duration = $circulation_air_duration/60;
                $circulation_air_period = $circulation_air_period/60;
                $exhausting_air_duration = $exhaust_air_duration/60;
                $exhausting_air_period = $exhaust_air_period/60;
                $switch_on_humidity = $setpoint_humidity - $array_config_json['switch_on_humidifier'];
                $switch_off_humidity = $setpoint_humidity - $array_config_json['switch_off_humidifier'];
                

                $f=fopen('logs/logfile.txt','a');
                fwrite($f, "\n"."***********************************************");
                fwrite($f, "\n"._('sensor').": ".$sensorname);
                fwrite($f, "\n". _('operating mode').": ".$operating_mode);
                fwrite($f, "\n".date('d.m.Y H:i').' '._('values have been manually changed.'));
                fwrite($f, "\n");

                if ($modus == 0 || $modus == 1 || $modus == 2)  {
                    fwrite($f, "\n"._('setpoint temperature').": ".$setpoint_temperature."&deg;C");
                    fwrite($f, "\n"._('switch-off temperature').": ".$array_config_json['switch_off_cooling_compressor']."&deg;C ("._('so at')." ".$switch_off_temperature_cooling."&deg;C)");
                    fwrite($f, "\n"._('switch-on temperature').": ".$array_config_json['switch_on_cooling_compressor']."&deg;C ("._('so at')." ".$switch_on_temperature."&deg;C)");
                }

                if ($modus == 3 || $modus == 4)  {
                    fwrite($f, "\n"._('setpoint temperature').": ".$setpoint_temperature."&deg;C");
                    fwrite($f, "\n"._('switch-on heater').": ".$array_config_json['switch_on_cooling_compressor'].'&deg;C ('._('so at')." ".$switch_on_temperature_heating.'&deg;C)');
                    fwrite($f, "\n"._('switch-off heater').": ".$array_config_json['switch_off_cooling_compressor']."&deg;C ("._('so at')." ".$switch_off_temperature_cooling_heating."&deg;C)");
                    fwrite($f, "\n"._('switch-on cooler').": ".$array_config_json['switch_on_cooling_compressor']."&deg;C ("._('so at')." ".$switch_on_temperature_cooling."&deg;C)");
                    fwrite($f, "\n"._('switch-off cooler').": ".$array_config_json['switch_off_cooling_compressor']."&deg;C ("._('so at')." ".$switch_off_temperature_cooling_cooling."&deg;C)");
                }

                fwrite($f, "\n");

                if ($modus == 1 || $modus == 2 || $modus == 3) {
                    fwrite($f, "\n"._('setpoint humidity').": ".$setpoint_humidity."% "."&phi;");
                    fwrite($f, "\n"._('switch-on humidifier').": ".$array_config_json['switch_on_humidifier']."% &phi; ("._('so at')." ".$switch_on_humidity."% &phi;)");
                    fwrite($f, "\n"._('switch-off humidifier').": ".$array_config_json['switch_off_humidifier']."% &phi; ("._('so at')." ".$switch_off_humidity."% &phi;)");
                    fwrite($f, "\n"._('delay humidifier')." ".$array_config_json['delay_humidify']._('minutes'));
                }

                if ($modus == 4) {
                    fwrite($f, "\n"._('setpoint humidity').": ".$setpoint_humidity."% &phi;");
                    fwrite($f, "\n"._('switch-on humidifier').": ".$array_config_json['switch_on_humidifier']."% &phi; ("._('so at')." ".$switch_on_humidify."% &phi;)");
                    fwrite($f, "\n"._('switch-off humidifier').": ".$array_config_json['switch_off_humidifier']."% &phi; ("._('so at')." ".$switch_off_humidify."% &phi;)");
                    fwrite($f, "\n"._('switch-on exhausting').": ".$array_config_json['switch_on_humidifier']."% &phi; ("._('so at')." ".$switch_on_dehumidify."% &phi;)");
                    fwrite($f, "\n"._('switch-off exhausting').": ".$array_config_json['switch_off_humidifier']."% &phi; ("._('so at')." ".$switch_off_dehumidify."% &phi;)");
                    fwrite($f, "\n"._('delay exhausting')." ".$array_config_json['delay_humidify']._('minutes'));
                }

                fwrite($f, "\n");
                fwrite($f, "\n"._('circulation air period').": ".$circulation_air_period._('minutes'));
                fwrite($f, "\n"._('circulation air duration').": ".$circulation_air_duration._('minutes'));


                fwrite($f, "\n");
                fwrite($f, "\n"._('exhausting air period')." ".$exhausting_air_period._('minutes'));
                fwrite($f, "\n"._('exhausting air duration').": ".$exhausting_air_duration._('minutes'));


                fwrite($f, "\n");
                fwrite($f, "\n"._('language').": ".$array_config_json['language']);

                fwrite($f, "\n"."***********************************************");
                fclose($f);


                # 3Sekunden Anzeige dass die Werte gespeichert wurden
                print '<p id="info-message" style="color: #ff0000; font-size: 20px;"><b><?php echo sprintf(_("values saved in file %s"), "config/config.json"); ?></b></p>
                    <script language="javascript">
                        setTimeout(function(){document.getElementById("info-message").style.display="none"}, 3000)
                    </script>';
            }
        # 3Sekunden Anzeige dass die Werte nicht gespeichert wurden
            else {
                print '<p id="info-message" style="color: #ff0000; font-size: 20px;"><b><?php echo _("values not in the specified limits!"); ?></b></p>
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
