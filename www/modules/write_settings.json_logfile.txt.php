<?PHP
#var_dump($_POST);
    # Prüfung der eingegebenen Werte
    if(isset ($_POST['mod']) && $_POST['mod'] <>NULL) {                       // ist das $_POST-Array gesetzt
        $InputValid = '';
        foreach ($_POST as $CheckInput) {                                  // Prüfen, ob nur Zahlen eingegeben wurden
            if (preg_match('/[.\D]/', $CheckInput)) {
            $InputValid = 'Nicht erlaubtes Zeichen - Bitte nur positive ganze Zahlen verwenden!';
            }
        }

        if ($InputValid == ''){
            if ( $_POST['temp']<23 &&  $_POST['temp']>-1 &&                                                                    // Prüfung Soll-Temperatur
                $_POST['temphyston']<11 && $_POST['temphyston']>-1 && ($_POST['temphyston'] != $_POST['temphystoff']) &&       // Prüfung Einschaltwert Temp.
                ($_POST['temphyston'] > $_POST['temphystoff']) &&                                                              // Prüfung Einschaltwert Temp.
                $_POST['temphystoff']<11 && $_POST['temphystoff']>-1 &&                                                        // Prüfung Ausschaltwert Temp.
                $_POST['hum']<100 && $_POST['hum']>-1 &&                                                                      // Prüfung Soll-Feuchtigkeit
                $_POST['humhyston']<11 && $_POST['humhyston']>-1 && ($_POST['humhyston'] != $_POST['humhystoff']) &&          // Prüfung Einschaltwert Feuchte
                ($_POST['humhyston'] > $_POST['humhystoff']) &&                                                               // Prüfung Einschaltwert Feuchte
                $_POST['humhystoff']<11 && $_POST['humhystoff']>-1 &&                                                          // Prüfung Ausschaltwert Feuchte
                $_POST['humdelay']<61 && $_POST['humdelay']>-1 &&                                                             // Prüfung Verzögerung Feuchte
                $_POST['tempoff']<1441 && $_POST['tempoff']>-1 &&  (($_POST['tempoff']+$_POST['tempon'])>0) &&                 // Prüfung Intervall Umluft
                $_POST['tempon']<1441 && $_POST['tempon']>-1  &&                                                                // Prüfung Dauer Umluft
                $_POST['tempoff1']<1441 && $_POST['tempoff1']>-1 && (($_POST['tempoff1']+$_POST['tempon1'])>0) &&             // Prüfung Intervall Abluft
                $_POST['tempon1']<1441 && $_POST['tempon1']>-1                                                                  // Prüfung Dauer Abluft
            )
            {
                # Eingestellte Werte in settings.json und logfile.txt speichern
                $timestamp = time();
                $array = array( 'temp' => (float)$_POST['temp'],
                    'mod' => (int)$_POST['mod'],
                    'hum' => (float)$_POST['hum'],
                    'tempon' => (int)$_POST['tempon']*60,
                    'tempoff' => (int)$_POST['tempoff']*60,
                    'tempon1' => (int)$_POST['tempon1']*60,
                    'tempoff1' => (int)$_POST['tempoff1']*60,
                    'temphyston' => (float)$_POST['temphyston'],
                    'temphystoff' => (float)$_POST['temphystoff'],
                    'humhyston' => (float)$_POST['humhyston'],
                    'humhystoff' => (float)$_POST['humhystoff'],
                    'humdelay' => (float)$_POST['humdelay'],
                    'date' => $timestamp,
                    'sensortype' => (int)$_POST['sensortype']);
                $jsoninput = json_encode($array);
                file_put_contents('settings.json', $jsoninput);

                # Formatierung für die Lesbarkeit im Logfile:
                # Modus
                if ($array['mod'] == 0) {
                    $betriebsart='Kuehlen';
                    $einschalttemperatur = $array['temp'] + $array['temphyston'];
                    $ausschalttemperatur = $array['temp'] + $array['temphystoff'];
                }

                if ($array['mod'] == 1) {
                    $betriebsart='Kuehlen mit Befeuchtung';
                    $einschalttemperatur = $array['temp'] + $array['temphyston'];
                    $ausschalttemperatur = $array['temp'] + $array['temphystoff'];
                }

                if ($array['mod'] == 2) {
                    $betriebsart='Heizen mit Befeuchtung';
                    $einschalttemperatur = $array['temp'] - $array['temphyston'];
                    $ausschalttemperatur = $array['temp'] - $array['temphystoff'];
                }
                if ($array['mod'] == 3) {
                    $betriebsart='Automatik mit Befeuchtung';
                    $einschalttemperatur_k = $array['temp'] + $array['temphyston'];
                    $ausschalttemperatur_k = $array['temp'] + $array['temphystoff'];
                    $einschalttemperatur_h = $array['temp'] - $array['temphyston'];
                    $ausschalttemperatur_h = $array['temp'] - $array['temphystoff'];
                }

                if ($array['mod'] == 4) {
                    $betriebsart='Automatik mit Be- und Entfeuchtung';
                    $einschalttemperatur_k = $array['temp'] + $array['temphyston'];
                    $ausschalttemperatur_k = $array['temp'] + $array['temphystoff'];
                    $einschalttemperatur_h = $array['temp'] - $array['temphyston'];
                    $ausschalttemperatur_h = $array['temp'] - $array['temphystoff'];

                    $einschaltfeuchte_bef = $array['hum'] - $array['humhyston'];
                    $ausschaltfeuchte_bef = $array['hum'] - $array['humhystoff'];
                    $einschaltfeuchte_entf = $array['hum'] + $array['humhyston'];
                    $ausschaltfeuchte_entf = $array['hum'] + $array['humhystoff'];
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
                
                $umluftdauer = $array['tempon']/60;
                $umluftperiode = $array['tempoff']/60;
                $luftaustauschdauer = $array['tempon1']/60;
                $luftaustauschperiode = $array['tempoff1']/60;
                $einschaltfeuchte = $array['hum'] - $array['humhyston'];
                $ausschaltfeuchte = $array['hum'] - $array['humhystoff'];

                $f=fopen('logfile.txt','a');
                fwrite($f, "\n"."***********************************************");
                fwrite($f, "\n"."Sensor: ".$sensorname);
                fwrite($f, "\n"."Betriebsart: ".$betriebsart);
                fwrite($f, "\n".date('d.m.Y H:i')." Werte wurden manuell ge&auml;ndert.");
                fwrite($f, "\n");

                if ($array['mod'] == 0 || $array['mod'] == 1 || $array['mod'] == 2)  {
                    fwrite($f, "\n"."Soll-Temperatur: ".$array['temp']."&deg;C");
                    fwrite($f, "\n"."Ausschalt-Temperatur: ".$array['temphystoff']."&deg;C (also bei ".$ausschalttemperatur."&deg;C)");
                    fwrite($f, "\n"."Einschalt-Temperatur: ".$array['temphyston']."&deg;C (also bei ".$einschalttemperatur."&deg;C)");
                }

                if ($array['mod'] == 3 || $array['mod'] == 4)  {
                    fwrite($f, "\n"."Soll-Temperatur: ".$array['temp']."&deg;C");
                    fwrite($f, "\n"."Einschaltwert Heizung: ".$array['temphyston']."&deg;C (also bei ".$einschalttemperatur_h."&deg;C)");
                    fwrite($f, "\n"."Ausschaltwert Heizung: ".$array['temphystoff']."&deg;C (also bei ".$ausschalttemperatur_h."&deg;C)");
                    fwrite($f, "\n"."Einschaltwert Kühlung: ".$array['temphyston']."&deg;C (also bei ".$einschalttemperatur_k."&deg;C)");
                    fwrite($f, "\n"."Ausschaltwert Kühlung: ".$array['temphystoff']."&deg;C (also bei ".$ausschalttemperatur_k."&deg;C)");
                }

                fwrite($f, "\n");

                if ($array['mod'] == 1 || $array['mod'] == 2 || $array['mod'] == 3) {
                    fwrite($f, "\n"."Soll-Feuchtigkeit: ".$array['hum']."% rLF");
                    fwrite($f, "\n"."Einschaltwert Befeuchtung: ".$array['humhyston']."% rLF (also bei ".$einschaltfeuchte."% rLF)");
                    fwrite($f, "\n"."Ausschaltwert Befeuchtung: ".$array['humhystoff']."% rLF (also bei ".$ausschaltfeuchte."% rLF)");
                    fwrite($f, "\n"."Befeuchter-Schaltverz&ouml;gerung ".$array['humdelay']."min");
                }

                if ($array['mod'] == 4) {
                    fwrite($f, "\n"."Soll-Feuchtigkeit: ".$array['hum']."% rLF");
                    fwrite($f, "\n"."Einschaltwert Befeuchtung: ".$array['humhyston']."% rLF (also bei ".$einschaltfeuchte_bef."% rLF)");
                    fwrite($f, "\n"."Ausschaltwert Befeuchtung: ".$array['humhystoff']."% rLF (also bei ".$ausschaltfeuchte_bef."% rLF)");
                    fwrite($f, "\n"."Einschaltwert Entfeuchtung: ".$array['humhyston']."% rLF (also bei ".$einschaltfeuchte_entf."% rLF)");
                    fwrite($f, "\n"."Ausschaltwert Entfeuchtung: ".$array['humhystoff']."% rLF (also bei ".$ausschaltfeuchte_entf."% rLF)");
                    fwrite($f, "\n"."Befeuchter-Schaltverz&ouml;gerung ".$array['humdelay']."min");
                }

                fwrite($f, "\n");
                fwrite($f, "\n"."Umluftperiode: ".$umluftperiode."min");
                fwrite($f, "\n"."Umluftdauer: ".$umluftdauer."min");


                fwrite($f, "\n");
                fwrite($f, "\n"."Abluftperiode ".$luftaustauschperiode."min");
                fwrite($f, "\n"."Abluftdauer: ".$luftaustauschdauer."min");


                fwrite($f, "\n"."***********************************************");
                fclose($f);


                # 3Sekunden Anzeige dass die Werte gespeichert wurden
                print '<p id="info-message" style="color: #ff0000; font-size: 20px;"><b>Werte gespeichert</b></p>
                    <script language="javascript">
                        setTimeout(function(){document.getElementById("info-message").style.display="none"}, 3000)
                    </script>';
            }
        # 3Sekunden Anzeige dass die Werte nicht gespeichert wurden
            else {
                print '<p id="info-message" style="color: #ff0000; font-size: 20px;"><b>Eingaben nicht in den vorgegebenen Wertgrenzen!</b></p>
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