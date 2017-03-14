<?php 
#var_dump($_POST);
    # Prüfung der eingegebenen Werte
    if(isset ($_POST['modus']) && $_POST['modus'] <>NULL) {                       // ist das $_POST-Array gesetzt
        $InputValid = '';
        foreach ($_POST as $CheckInput) {                                  // Prüfen, ob nur Zahlen eingegeben wurden
            if (preg_match('/[.\D]/', $CheckInput)) {
            $InputValid = echo _('unauthorized character - please use only positive integers!');
            }
        }

        if ($InputValid == ''){
            if ( $_POST['setpoint_temperature']<23 &&  $_POST['setpoint_temperature']>-1 &&                                                                    // Prüfung Soll-Temperatur
                $_POST['switch_on_cooling_compressor']<11 && $_POST['switch_on_cooling_compressor']>-1 && ($_POST['switch_on_cooling_compressor'] != $_POST['switch_off_cooling_compressor']) &&       // Prüfung Einschaltwert setpoint_temperature.
                ($_POST['switch_on_cooling_compressor'] > $_POST['switch_off_cooling_compressor']) &&                                                              // Prüfung Einschaltwert setpoint_temperature.
                $_POST['switch_off_cooling_compressor']<11 && $_POST['switch_off_cooling_compressor']>-1 &&                                                        // Prüfung Ausschaltwert setpoint_temperature.
                $_POST['setpoint_humidity']<100 && $_POST['setpoint_humidity']>-1 &&                                                                      // Prüfung Soll-Feuchtigkeit
                $_POST['switch_on_humidifier']<11 && $_POST['switch_on_humidifier']>-1 && ($_POST['switch_on_humidifier'] != $_POST['switch_off_humidifier']) &&          // Prüfung Einschaltwert Feuchte
                ($_POST['switch_on_humidifier'] > $_POST['switch_off_humidifier']) &&                                                               // Prüfung Einschaltwert Feuchte
                $_POST['switch_off_humidifier']<11 && $_POST['switch_off_humidifier']>-1 &&                                                          // Prüfung Ausschaltwert Feuchte
                $_POST['delay_humidify']<61 && $_POST['delay_humidify']>-1 &&                                                             // Prüfung Verzögerung Feuchte
                $_POST['circulation_air_period']<1441 && $_POST['circulation_air_period']>-1 &&  (($_POST['circulation_air_period']+$_POST['circulation_air_duration'])>0) &&                 // Prüfung Intervall Umluft
                $_POST['circulation_air_duration']<1441 && $_POST['circulation_air_duration']>-1  &&                                                                // Prüfung Dauer Umluft
                $_POST['exhaust_air_period']<1441 && $_POST['exhaust_air_period']>-1 && (($_POST['exhaust_air_period']+$_POST['exhaust_air_duration'])>0) &&             // Prüfung Intervall Abluft
                $_POST['exhaust_air_duration']<1441 && $_POST['exhaust_air_duration']>-1                                                                  // Prüfung Dauer Abluft
            )
            {
                # Eingestellte Werte in settings.json und logfile.txt speichern
                $timestamp = time();
                $array = array( 'setpoint_temperature' => (float)$_POST['setpoint_temperature'],
                    'modus' => (int)$_POST['modus'],
                    'setpoint_humidity' => (float)$_POST['setpoint_humidity'],
                    'circulation_air_duration' => (int)$_POST['circulation_air_duration']*60,
                    'circulation_air_period' => (int)$_POST['circulation_air_period']*60,
                    'exhaust_air_duration' => (int)$_POST['exhaust_air_duration']*60,
                    'exhaust_air_period' => (int)$_POST['exhaust_air_period']*60,
                    'switch_on_cooling_compressor' => (float)$_POST['switch_on_cooling_compressor'],
                    'switch_off_cooling_compressor' => (float)$_POST['switch_off_cooling_compressor'],
                    'switch_on_humidifier' => (float)$_POST['switch_on_humidifier'],
                    'switch_off_humidifier' => (float)$_POST['switch_off_humidifier'],
                    'delay_humidify' => (float)$_POST['delay_humidify'],
                    'last_change' => $timestamp,
                    'sensortype' => (int)$_POST['sensortype']);
                $jsoninput = json_encode($array);
                file_put_contents('settings.json', $jsoninput);

                # Formatierung für die Lesbarkeit im Logfile:
                # Modus
                if ($array['modus'] == 0) {
                    $operating_mode = echo _('cooling');
                    $einschalttemperatur = $array['setpoint_temperature'] + $array['switch_on_cooling_compressor'];
                    $switch_off_temperature = $array['setpoint_temperature'] + $array['switch_off_cooling_compressor'];
                }

                if ($array['modus'] == 1) {
                    $operating_mode = echo _('cooling with humidification');
                    $einschalttemperatur = $array['setpoint_temperature'] + $array['switch_on_cooling_compressor'];
                    $switch_off_temperature = $array['setpoint_temperature'] + $array['switch_off_cooling_compressor'];
                }

                if ($array['modus'] == 2) {
                    $operating_mode = echo _('heating with humidification';
                    $einschalttemperatur = $array['setpoint_temperature'] - $array['switch_on_cooling_compressor'];
                    $switch_off_temperature = $array['setpoint_temperature'] - $array['switch_off_cooling_compressor'];
                }
                if ($array['modus'] == 3) {
                    $operating_mode = echo _('automatic with humidification';
                    $switch_on_temperature_cooling = $array['setpoint_temperature'] + $array['switch_on_cooling_compressor'];
                    $switch_off_temperature_cooling = $array['setpoint_temperature'] + $array['switch_off_cooling_compressor'];
                    $switch_on_temperature_heating = $array['setpoint_temperature'] - $array['switch_on_cooling_compressor'];
                    $switch_off_temperature_heating = $array['setpoint_temperature'] - $array['switch_off_cooling_compressor'];
                }

                if ($array['modus'] == 4) {
                    $operating_mode = echo _('automatic with dehumidification and humidification';
                    $switch_on_temperature_cooling = $array['setpoint_temperature'] + $array['switch_on_cooling_compressor'];
                    $switch_off_temperature_cooling = $array['setpoint_temperature'] + $array['switch_off_cooling_compressor'];
                    $switch_on_temperature_heating = $array['setpoint_temperature'] - $array['switch_on_cooling_compressor'];
                    $switch_off_temperature_heating = $array['setpoint_temperature'] - $array['switch_off_cooling_compressor'];

                    $switch_on_humidify = $array['setpoint_humidity'] - $array['switch_on_humidifier'];
                    $switch_off_humidify = $array['setpoint_humidity'] - $array['switch_off_humidifier'];
                    $switch_on_dehumidify = $array['setpoint_humidity'] + $array['switch_on_humidifier'];
                    $switch_off_dehumidify = $array['setpoint_humidity'] + $array['switch_off_humidifier'];
                }
                # Sensor
                if ($array['sensortype'] == 1) {
                    $sensortype = 1;
                    $sensorname='DHT11';
                }
                if ($array['sensortype'] == 2) {
                    $sensortype = 2;
                    $sensorname='DHT22';
                }
                if ($array['sensortype'] == 3) {
                    $sensortype = 3;
                    $sensorname='SHT75';
                }
                
                $circulation_air_duration = $array['circulation_air_duration']/60;
                $circulation_air_period = $array['circulation_air_period']/60;
                $exhausting_air_duration = $array['exhaust_air_duration']/60;
                $exhausting_air_period = $array['exhaust_air_period']/60;
                $switch_on_humidity = $array['setpoint_humidity'] - $array['switch_on_humidifier'];
                $switch_off_humidity = $array['setpoint_humidity'] - $array['switch_off_humidifier'];

                $f=fopen('logfile.txt','a');
                fwrite($f, "\n"."***********************************************");
                fwrite($f, "\n".echo _('sensor').": ".$sensorname);
                fwrite($f, "\n".echo _('operating mode').": ".$operating_mode);
                fwrite($f, "\n".date('d.m.Y H:i').' '.echo _('values have been manually changed.');
                fwrite($f, "\n");

                if ($array['modus'] == 0 || $array['modus'] == 1 || $array['modus'] == 2)  {
                    fwrite($f, "\n".echo _('setpoint temperature').": ".$array['setpoint_temperature']."&deg;C");
                    fwrite($f, "\n".echo _('switch-off temperature').": ".$array['switch_off_cooling_compressor']."&deg;C (".echo _('so at')." ".$switch_off_temperature."&deg;C)");
                    fwrite($f, "\n".echo _('switch-on temperature').": ".$array['switch_on_cooling_compressor']."&deg;C (".echo _('so at')." ".$switch_on_temperature."&deg;C)");
                }

                if ($array['modus'] == 3 || $array['modus'] == 4)  {
                    fwrite($f, "\n".echo _('setpoint temperature').": ".$array['setpoint_temperature']."&deg;C");
                    fwrite($f, "\n".echo _('switch-on heater');": ".$array['switch_on_cooling_compressor']."&deg;C (".echo _('so at')." ".$switch_on_temperature_heating."&deg;C)");
                    fwrite($f, "\n".echo _('switch-off heater').": ".$array['switch_off_cooling_compressor']."&deg;C (".echo _('so at')." ".$switch_off_temperature_heating."&deg;C)");
                    fwrite($f, "\n".echo _('switch-on cooler').": ".$array['switch_on_cooling_compressor']."&deg;C (".echo _('so at')." ".$switch_on_temperature_cooling."&deg;C)");
                    fwrite($f, "\n".echo _('switch-off cooler').": ".$array['switch_off_cooling_compressor']."&deg;C (".echo _('so at')." ".$switch_off_temperature_cooling."&deg;C)");
                }

                fwrite($f, "\n");

                if ($array['modus'] == 1 || $array['modus'] == 2 || $array['modus'] == 3) {
                    fwrite($f, "\n".echo _('setpoint humidity').": ".$array['setpoint_humidity']."% "."&phi;");
                    fwrite($f, "\n".echo _('switch-on humidifier').": ".$array['switch_on_humidifier']."% &phi; (".echo _('so at')." ".$switch_on_humidity."% &phi;)");
                    fwrite($f, "\n".echo _('switch-off humidifier').": ".$array['switch_off_humidifier']."% &phi; (".echo _('so at')." ".$switch_off_humidity."% &phi;)");
                    fwrite($f, "\n".echo _('delay humidifier')." ".$array['delay_humidify'].echo _('minutes'));
                }

                if ($array['modus'] == 4) {
                    fwrite($f, "\n".echo _('setpoint humidity').": ".$array['setpoint_humidity']."% &phi;");
                    fwrite($f, "\n".echo _('switch-on humidifier').": ".$array['switch_on_humidifier']."% &phi; (".echo _('so at')." ".$switch_on_humidify."% &phi;)");
                    fwrite($f, "\n".echo _('switch-off humidifier').": ".$array['switch_off_humidifier']."% &phi; (".echo _('so at')." ".$switch_off_humidify."% &phi;)");
                    fwrite($f, "\n".echo _('switch-on exhausting').": ".$array['switch_on_humidifier']."% &phi; (".echo _('so at')." ".$switch_on_dehumidify."% &phi;)");
                    fwrite($f, "\n".echo _('switch-off exhausting').": ".$array['switch_off_humidifier']."% &phi; (".echo _('so at')." ".$switch_off_dehumidify."% &phi;)");
                    fwrite($f, "\n".echo _('delay exhausting')." ".$array['delay_humidify'].echo _('minutes'));
                }

                fwrite($f, "\n");
                fwrite($f, "\n".echo _('circulation air period').": ".$circulation_air_period.echo _('minutes'));
                fwrite($f, "\n".echo _('circulation air duration').": ".$circulation_air_duration.echo _('minutes'));


                fwrite($f, "\n");
                fwrite($f, "\n".echo _('exhausting air period')." ".$exhausting_air_period.echo _('minutes'));
                fwrite($f, "\n".echo _('exhausting air duration').": ".$exhausting_air_duration.echo _('minutes'));


                fwrite($f, "\n"."***********************************************");
                fclose($f);


                # 3Sekunden Anzeige dass die Werte gespeichert wurden
                print '<p id="info-message" style="color: #ff0000; font-size: 20px;"><b><?php echo _("values saved"); ?></b></p>
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