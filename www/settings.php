                                <?php
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                    include 'modules/write_table.json.php';                     // Speichert die Auswahl der Reifetabelle
                                    include 'modules/write_settings.json_logfile.txt.php';      // Speichert die eingestelleten Werte (Temperaturregelung, Feuchte, Lüftung und deren Hysteresen)
                                    include 'modules/start_stop_program.php';                   // Startet / Stoppt das Reifeprogramm bzw. den ganzen Schrank
                                    include 'modules/read_settings.json.php';                   // Liest die Einstellungen (Temperaturregelung, Feuchte, Lüftung und deren Hysteresen) und Betriebsart des RSS
                                    include 'modules/read_current.json.php';                    // Liest die gemessenen Werte T/H und den aktuellen Zustand der Aktoren
                                    include 'modules/read_operating_mode.php';                  // Liest die Art der Reifesteuerung
                                    include 'modules/read_gpio.php';                            // Liest den aktuellen Zustand der GPIO-E/A
                                    include 'modules/read_csv_dir.php';                         // Liest das Verezichnis mit den Reifeprogrammtabellen ein
                                    include 'modules/system_reboot.php';                        // Startet das System neu
                                    include 'modules/system_shutdown.php';                      // Fährt das System herunter
                                ?>
                                <h2 class="art-postheader">Betrieb</h2>
                                <!----------------------------------------------------------------------------------------Programme starten/stoppen-->
                                <div class="hg_container">
                                    <table style="width: 100%"><tr>
                                    <?php
                                            print '<form  method="post">';
                                            // Prüft, ob Prozess RSS läuft ( NULL = Rss.py läuft nicht als Prozess, )
                                            $valrs = shell_exec('sudo /var/sudowebscript.sh greprss');
                                            // Prüft, ob Prozess Reifetab läuft ()
                                            $valtab = shell_exec('sudo /var/sudowebscript.sh grepreifetab');
                                            
                                            if($valrs == NULL and $valtab != NULL) { //wenn Prozess RSS läuft und Reifetab läuft nicht (korrekt)
                                                shell_exec('sudo /var/sudowebscript.sh pkillreifetab');
                                                $valtab = shell_exec('sudo /var/sudowebscript.sh grepreifetab');
                                            }

                                            if ($valrs == NULL){
                                                print '<td><img src="images/betriebsart.png" alt="" style="padding: 10px;"></td><td><img src="images/led-off-green-20x20.png" alt="" style="padding-top: 10px;"></td><td style=""><button class="art-button" name="rss_start">Start Reifeschrank</button></td>';
                                            }
                                            else {
                                                print '<td><img src="images/betriebsart.png" alt="" style="padding: 10px;"></td><td><img src="images/led-on-green-20x20.png" alt="" style="padding-top: 10px;"></td><td><button class="art-button" name="rss_reifetab_stop" onclick="return confirm("Reifeschrank stoppen?");">Stop Reifeschrank</button></td>';
                                            }
                                            print ' </form>';
                                    ?>
                                    </tr></table>
                                    <hr>
                                    <!----------------------------------------------------------------------------------------Reifetabelle auswählen-->

                                    <table style="width: 100%">
                                        <tr>
                                            <td><img src="images/reifung.png" alt="" style="padding-left: 10px;"></td>
                                            <td style=" text-align: left; padding-left: 20px;">
                                                <?php
                                                    print '<form  method="post">';
                                                    foreach($csvfilename as $name) {
                                                        if ($name<>$wunschreife){
                                                            echo '<input type="radio" name="Reifetab" value="'.$name.'"><label> '.$name.'</label><br>';
                                                        }
                                                        if ($name==$wunschreife){
                                                            echo '<input type="radio" name="Reifetab" value="'.$name.'" checked="checked"><label> '.$name.'</label><br>';
                                                        }
                                                    }
                                                    print '</td><td>';
                                                    if ($valtab == NULL){
                                                        print '<img src="images/led-off-green-20x20.png" alt="" style="padding-right: 20px;">';
                                                    }
                                                    else {
                                                        print '<img src="images/led-on-green-20x20.png" alt="" style="padding-right: 20px;">';
                                                    }
                                                ?>
                                            <img src="images/reifung.png" alt=""></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td style=" text-align: left; padding-left: 20px;"><br>
                                                <?php
                                                    print '<input class="art-button" type="submit" value="Speichern" />';
                                                    print '</form>';
                                                ?>
                                            </td>
                                            <td><br>
                                                <?php
                                                    print '<form  method="post">';
                                                    if ($valtab == NULL){
                                                        print '<button class="art-button" name="rss_reifetab_start">Start Tabelle</button> ';
                                                    }
                                                    else {
                                                        print '<button class="art-button" name="reifetab_stop" onclick="return confirm("Reifeprogramm stoppen?");">Stop Tabelle<button>';
                                                    }
                                                    print ' </form>';
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <table id="show_agingtab" class="show_agingtab">
                                        <tr style="background-color: #F0F5FB; border-bottom: 1px solid #000033">
                                            <td class="show_agingcell"><div class="tooltip">S%<span class="tooltiptext">Soll-Feuchtigkeit in %</span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">D%<span class="tooltiptext">Verzögerung der Befeuchtung in Minuten</span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">ON%<span class="tooltiptext">Einschaltwert der Befeuchtung in %</span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">OFF%<span class="tooltiptext">Ausschaltwert der Befeuchtung in %</span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">S°C<span class="tooltiptext">Soll-Temperatur in °C</span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">ON°C<span class="tooltiptext">Einschaltwert der Temperatur in °C</span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">OFF°C<span class="tooltiptext">Ausschaltwert der Temperatur in °C</span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">TUD<span class="tooltiptext">Timer der Umluftdauer in Minuten</span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">TUP<span class="tooltiptext">Timer der Umluftperiode in Minuten</span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">TAD<span class="tooltiptext">Timer der Abluftdauer in Minuten</span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">TAP<span class="tooltiptext">Timer der Abluftperiode in Minuten</span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">TAG<span class="tooltiptext">Dauer der Reifephase in Tagen</span></div></td>
                                        </tr>
                                        <?php
                                            // Gewählte CSV-Datei auslesen und als Array anlegen
                                            $CSV_FILE='csv/'.$wunschreife.'.csv';
                                            $row = 1;
                                            if (($handle = fopen($CSV_FILE, "r")) !== FALSE) {
                                                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                                                    $num = count($data);
                                                    echo '<tr>';
                                                    $row++;
                                                    if ($row > 2){
                                                        for ($c=0; $c < $num; $c++) {
                                                            if ($c != 0){
                                                                if ($data[$c] == ''){
                                                                    $data[$c] = '..';
                                                                }
                                                                elseif ($c == 8 || $c == 9 || $c == 10 || $c == 11){
                                                                    $data[$c] = round($data[$c]/60, 0);
                                                                }
                                                                echo '<td>'.$data[$c].'</td>';
                                                            }
                                                        }
                                                    echo '</tr>';
                                                    }
                                                }
                                                fclose($handle);
                                            }
                                        ?>
                                    </table>
                                </div>


                                <h2 class="art-postheader">Manuelle Werte</h2>
                                <!----------------------------------------------------------------------------------------Betriebsart-->
                                <form method="post">
                                    <div class="hg_container">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td class="td_png_icon"><h3>Betriebsart</h3><img src="images/betriebsart.png" alt=""><button class="art-button" type="button" onclick="help_betriebsart_blockFunction()">Hilfe</button></td>
                                                <td style=" text-align: left; padding-left: 20px;">
                                                <input type="radio" name="mod" value="1" <?= $checked_1 ?>/><label> Kühlen mit Befeuchtung</label><br>
                                                <input type="radio" name="mod" value="2" <?= $checked_2 ?>/><label> Heizen mit Befeuchtung</label><br>
                                                <input type="radio" name="mod" value="3" <?= $checked_3 ?>/><label> Automatik mit Befeuchtung</label><br>
                                                <input type="radio" name="mod" value="4" <?= $checked_4 ?>/><label> Automatik mit Be- und Entfeuchtung</label><br><br>
                                                <b>Umluft- und Ablufttimer</b> können unabhängig vom gewählten Modus genutzt werden.
                                                </td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_betriebsart_blockFunction() {
                                                document.getElementById('help_betriebsart').style.display = 'block';
                                            }
                                            function help_betriebsart_noneFunction() {
                                                document.getElementById('help_betriebsart').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_betriebsart" class="help_p">
                                            <b>Kühlen mit Befeuchtung:</b>  Es wird auf die eingestellte Temperatur mit Umluft gekühlt und es wird befeuchtet,
                                            die Heizung wird nie angesteuert.<br><br>
                                            <b>Heizen mit Befeuchtung:</b> Es wird auf die eingestellte Temperatur mit Umluft geheizt und und es wird befeuchtet,
                                            die Kühlung wird nie angesteuert.<br><br>
                                            <b>Automatik mit Befeuchtung:</b> Der Reifeschrank kühlt oder heizt mit Umluft je nach eingestelltem Wert und es wird befeuchtet.<br><br>
                                            <b>Automatik mit Be- und Entfeuchtung:</b> Wie Automatik mit Befeuchtung, Zusatz: beim Überschreiten der Luftfeuchte schaltet die Abluft ein, bis das Feuchte-Soll wieder erreicht ist.
                                            Da es sich um eine passive Entfeuchtung handelt, hängt die minimal erreichbare Luftfeuchte von der Trockenheit der Umgebungsluft ab.
                                            Um ein wildes Ein- und Ausschalten zu vermeiden, sollte die Befeuchtung unbedingt verzögert werden (5-10min)!
                                            <br><br>
                                            <button class="art-button" type="button" onclick="help_betriebsart_noneFunction()">Schließen</button>
                                        </p>
                                        
                                        <hr>
                                <!----------------------------------------------------------------------------------------Sensortyp-->
                                        <table style="width: 100%;">
                                            <tr>
                                                <td class="td_png_icon"><h3>Sensortyp</h3><img src="images/sensortyp.png" alt="">
                                                    <button class="art-button" type="button" onclick="help_sensortyp_blockFunction()">Hilfe</button>
                                                </td>
                                                <td style=" text-align: left; padding-left: 20px;">
                                                    <input type="radio" name="sensortype" value="1" <?= $checked_sens_1 ?>/><label> DHT11</label><br>
                                                    <input type="radio" name="sensortype" value="2" <?= $checked_sens_2 ?>/><label> DHT22</label><br>
                                                    <input type="radio" name="sensortype" value="3" <?= $checked_sens_3 ?>/><label> SHT75</label><br>
                                                    <br>
                                                </td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_sensortyp_blockFunction() {
                                                document.getElementById('help_sensortyp').style.display = 'block';
                                            }
                                            function help_sensortyp_noneFunction() {
                                                document.getElementById('help_sensortyp').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_sensortyp" class="help_p">
                                            <b>hier steht Etxt</b>
                                            <br><br>
                                            <button class="art-button" type="button" onclick="help_sensortyp_noneFunction()">Schließen</button>
                                        </p>
                                        <hr>
                                        <!----------------------------------------------------------------------------------------Temperatur-->
                                        <table style="width: 100%;">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3>Temperatur</h3><img src="images/heiz_kuehl.png" alt=""><button class="art-button" type="button" onclick="help_temperatur_blockFunction()">Hilfe</button></td>
                                                <td class="text_left_padding">Soll-Temperatur:</td>
                                                <td class="text_left_padding"><input name="temp" maxlength="4" size="2" type="text" value=<?=$tempsoll_float?>>°C <span style="font-size: xx-small"></span></td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding">Einschaltwert:</td>
                                                <td class="text_left_padding"><input name="temphyston" type="text" maxlength="4" size="2" value=<?=$temphyston?>>°C
                                                    <span style="font-size: xx-small">(Ein
                                                        <?
                                                            if($mod == 0 || $mod == 1){
                                                                echo $tempsoll_float+$temphyston;
                                                            }
                                                            elseif($mod == 2){
                                                                echo $tempsoll_float-$temphyston;
                                                            }
                                                            else {
                                                                echo '+/-';
                                                            }
                                                        ?>
                                                        °C)
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding">Ausschaltwert:</td>
                                                <td class="text_left_padding"><input name="temphystoff" type="text" maxlength="4" size="2" value= <?=$temphystoff?>>°C
                                                    <span style="font-size: xx-small">(Aus
                                                        <?
                                                            if($mod == 0 || $mod == 1){
                                                                echo $tempsoll_float+$temphystoff;
                                                            }
                                                            elseif($mod == 2){
                                                                echo $tempsoll_float-$temphystoff;
                                                            }
                                                            else {
                                                                echo '+/-';
                                                            }
                                                        ?>
                                                        °C)
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_temperatur_blockFunction() {
                                                document.getElementById('help_temperatur').style.display = 'block';
                                            }
                                            function help_temperatur_noneFunction() {
                                                document.getElementById('help_temperatur').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_temperatur" class="help_p">
                                            <b>Solltemperatur:</b> Hier wird die gewünschte Temperatur eingestellt. Der minimale Wert beträgt -2°C, der maximale +22°C.
                                            Technisch bedingt können nicht alle Werte in jeder Betriebsart angefahren werden. Die Umluft ist während der Kühl-oder Heizphasen immer aktiv.<br><br>
                                            <b><u>Schalthysterese</u></b><br>
                                            <b>Einschaltwert:</b> ist der Wert, bei dem die Regelung aktiv wird (Wertgrenze: +/- 5°C)<br>
                                            <b>Ausschaltwert:</b> ist der Wert, bei dem die Regelung inaktiv wird (Wertgrenze: +/- 5°C)<br>
                                            Das ergibt eine Gesamthysterese von 10°C. Um ein wildes Ein- und Ausschalten zu vermeiden, dürfen die Werte nicht gleich sein.
                                            <br><br>
                                            <b>Beispiel für Kühlung:</b> <i>Solltemperatur: 12°C; Einschaltwert: 2°C; Ausschaltwert 0.5°C</i><br>
                                            Einschalttemperatur = Solltemperatur + Einschaltwert --> 12°C + 2°C = 14°C<br>
                                            Ausschalttemperatur = Solltemperatur + Ausschaltwert --> 12°C + 0.5°C = 12.5°C<br>
                                            Wenn also 14 Grad überschritten werden, kühlt der Schrank auf bis 12.5°C runter und schaltet dann ab, um ein zu starkes nachkühlen zu vermeiden.
                                            Das gesamte Verhalten ist von Schrank zu Schrank unterschiedlich und daher individuell zu ermitteln.
                                            <br><br>
                                            <b>Beispiel für Heizung:</b> <i>Solltemperatur: 22°C; Einschaltwert: 2°C; Ausschaltwert 0.5°C</i><br>
                                            Einschalttemperatur = Solltemperatur - Einschaltwert --> 22°C - 2°C = 20°C<br>
                                            Ausschalttemperatur = Solltemperatur - Ausschaltwert --> 22°C - 0.5°C = 21.5°C<br>
                                            Wenn also 20 Grad unterschritten werden, heizt der Schrank auf bis 21.5°C auf und schaltet dann ab, um ein zu starkes nachheizen zu vermeiden.
                                            Das gesamte Verhalten ist von Schrank zu Schrank unterschiedlich und daher individuell zu ermitteln.
                                            <br><br>
                                            <b>Um die Schalthysterese zu vergrößern</b>, kann auch das Vorzeichen umgekehrt werden:
                                            <b>Beispiel für Kühlung:</b> <i>Solltemperatur: 12°C; Einschaltwert: 2°C; Ausschaltwert -0.5°C</i><br>
                                            Einschalttemperatur = Solltemperatur + Einschaltwert --> 12°C + 2°C = 14°C<br>
                                            Ausschalttemperatur = Solltemperatur + Ausschaltwert --> 12°C + (-0.5°C) = 11.5°C<br>
                                            Wenn also 14 Grad überschritten werden, kühlt der Schrank auf bis 11.5°C runter und schaltet dann ab.
                                            <br><br>
                                            <b>Automatikmodus:</b> In jedem Automatikmodus wird die Temperatur vollständig automatisch geregelt.
                                            Zunächst wird die aktuelle Temperatur ermittelt. Dann entscheidet sich, welches Verfahren (Kühlen oder Heizen) geeignet ist,
                                            die eingestellte Solltemperatur zu erreichen. Das bedeutet aber auch, dass die Schaltwerte der Hysterese nicht zu eng beieinander
                                            liegen dürfen. Anderenfalls könnten Kühlung und Heizung immer im Wechsel an und ausgeschaltet werden.
                                            <br><br>
                                            <b>Beispiel Automatik:</b> Jetzt wird es spannend!<br>
                                            <i>Solltemperatur: 15°C; Einschaltwert: 5°C; Ausschaltwert 3°C</i><br>
                                            1. Fall: SensorTemperatur >= (Solltemperatur + Einschaltwert [=20°C]) = Kühlung an<br>
                                            2. Fall: SensorTemperatur <= (Solltemperatur + Ausschaltwert [=18°C]) = Kühlung aus<br>
                                            3. Fall: SensorTemperatur >= (Solltemperatur - Einschaltwert [=10°C]) = Heizung an<br>
                                            4. Fall: SensorTemperatur <= (Solltemperatur - Ausschaltwert [=12°C]) = Heizung aus
                                            <br><br>
                                            <b>EMPFEHLUNG:</b> Kontrolle der gespeicherten Werte im Logfile!
                                            <br><br>
                                            <b>ACHTUNG: KEINE KOMMAS VERWENDEN!</b><br>
                                            Als dezimale Stelle bitte nur den Punkt "." verwenden.<br><br>
                                            <button class="art-button" type="button" onclick="help_temperatur_noneFunction()">Schließen</button>
                                        </p>
                                        <hr>
                                        <!----------------------------------------------------------------------------------------Luftfeuchte-->
                                        <table style="width: 100%;">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3>Luftfeuchte</h3><img src="images/befeuchtung.png" alt=""><button class="art-button" type="button" onclick="help_befeuchtung_blockFunction()">Hilfe</button></td>
                                                <td class="text_left_padding">Soll-Feuchtigkeit</td>
                                                <td class="text_left_padding"><input name="hum" maxlength="4" size="2" type="text" value=<?=$humsoll_float?>>% <span style="font-size: xx-small"></span></td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding">Einschaltwert:</td>
                                                <td class="text_left_padding"><input name="humhyston" maxlength="3" size="2" type="text" value=<?=$humhyston?>>%  <span style="font-size: xx-small">(Ein <?=$humsoll_float-$humhyston?>%)</span></td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding">Ausschaltwert:</td>
                                                <td class="text_left_padding"><input name="humhystoff" maxlength="3" size="2" type="text" value=<?=$humhystoff?>>% <span style="font-size: xx-small">(Aus <?=$humsoll_float-$humhystoff?>%)</span></td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding">Verzögerung:</td>
                                                <td class="text_left_padding"><input name="humdelay" maxlength="2" size="2" type="text" value=<?=$humdelay?>> Minuten</td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_befeuchtung_blockFunction() {
                                                document.getElementById('help_befeuchtung').style.display = 'block';
                                            }
                                            function help_befeuchtung_noneFunction() {
                                                document.getElementById('help_befeuchtung').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_befeuchtung" class="help_p">
                                            <b>Sollfeuchtigkeit:</b> Hier wird die gewünschte Luftfeuchte eingestellt. Der minimale Wert beträgt theoretisch 0% und maximal 100%.
                                            Diese Werte werden aber in der Regel nie erreicht werden. Die Umluft ist während der Befeuchtung immer aktiv. Die Wirksamkeit der Entfeuchtung (Automatikmodus mit mit Be- und Entfeuchtung) ist abhängig von der Umgebungsluftfeuchte, da nur eine passive Entfeuchtung durch Abluft stattfindet.<br><br>
                                            <b><u>Schalthysterese</u></b><br>
                                            <b>Einschaltwert:</b> ist der Wert, bei dem die Regelung aktiv wird (Wertgrenze: +/- 5%)<br>
                                            <b>Ausschaltwert:</b> ist der Wert, bei dem die Regelung inaktiv wird (Wertgrenze: +/- 5%)<br>
                                            Das ergibt eine Gesamthysterese von 10%. Um ein wildes Ein- und Ausschalten zu vermeiden, dürfen die Werte nicht gleich sein.
                                            <br><br>
                                            <b>Verzögerung:</b> hier wird die Verzögerungszeit eingestellt, bis der Befeuchter bei zu niedriger Luftfeuchtigkeit einschaltet.
                                            Damit kann die kurzeitig fallende Luftfeuchtigkeit beim "Kühlen", beim "Timer-Abluft" oder "Entfeuchten" ausgeblendet werden.
                                            <br><br>
                                            <b>Beispiel:</b> <i>Sollfeuchtigkeit: 75%; Einschaltwert: 5%; Ausschaltwert 1%</i><br>
                                            Einschaltfeuchtigkeit = Sollfeuchtigkeit - Einschaltwert --> 75% - 5% = 70%<br>
                                            Ausschaltfeuchtigkeit = Sollfeuchtigkeit - Ausschaltwert --> 75% - 1% = 74%<br>
                                            Verzögerung = 5 Minuten<br>
                                            Wenn also 70% Luftfeuchtigkeit unterschritten werden, wartet die Regelung 5 Minuten ab. Erst dann befeuchtet der Schrank die Luft bis auf 74% und schaltet anschließend Befeuchtung wieder ab.
                                            <br><br>
                                            <b>Beispiel Automatikmodus mit mit Be- und Entfeuchtung:</b> In diesem Automatikmodus wird die Luftfeuchtigkeit vollständig automatisch geregelt.
                                            Zunächst wird die aktuelle Luftfeuchtigkeit ermittelt. Dann entscheidet sich, welches Verfahren (Be- und Entfeuchtung) geeignet ist,
                                            die eingestellte Soll-Feuchtigkeit zu erreichen. Das bedeutet aber auch, dass die Schaltwerte der Hysterese nicht zu eng beieinander
                                            liegen dürfen. Anderenfalls könnten Befeuchtung und Entfeuchtung immer im Wechsel an und ausgeschaltet werden.
                                            <br><br>
                                            <b>ACHTUNG: KEINE KOMMAS VERWENDEN!</b><br>
                                            Als dezimale Stelle bitte nur den Punkt "." verwenden.<br><br>
                                            <button class="art-button" type="button" onclick="help_befeuchtung_noneFunction()">Schließen</button>
                                        </p>
                                        <hr>
                                        <!----------------------------------------------------------------------------------------Umluft-->
                                        <table style="width: 100%;">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3>Timer Umluft</h3><img src="images/luftumwaelzung.png" alt=""><button class="art-button" type="button" onclick="help_umluft_blockFunction()">Hilfe</button></td>
                                                <td class="text_left_padding">Periode alle </td>
                                                <td class="text_left_padding"><input type="text" size="4" maxlength="4" name="tempoff" value=<?=round($tempoff)?>>Minuten <span style="font-size: xx-small"></span></td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding">für die Dauer von</td>
                                                <td class="text_left_padding"><input type="text" maxlength="4" size="4" name="tempon" value=<?=$tempon?>>Minuten <span style="font-size: xx-small"></span></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_umluft_blockFunction() {
                                                document.getElementById('help_umluft').style.display = 'block';
                                            }
                                            function help_umluft_noneFunction() {
                                                document.getElementById('help_umluft').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_umluft" class="help_p">
                                            <b>Periode:</b> Hier wird die Pausenzeit eingestellt, die bis zum erneuten Einschalten der Umluft abgewartet wird (genau genommen Periode - Dauer). Bei Wert 0 ist die Umluft-Timerfunktion ausgeschaltet. Der maximale Wert liegt bei 1440min.
                                            <br><br>
                                            <b>Dauer:</b> Hier wird die Umluftdauer eingestellt, in während der der Lüfter läuft. Bei Wert 0 ist die Umluft-Timerfunktion ausgeschaltet. Der maximale Wert beträgt 1439min und muss immer kleiner als die Periode sein.
                                            <br><br>
                                            <b>Hinweis:</b> Der Umluft-Lüfter läuft unabhängig von den Timereinstellungen außerdem auch während der Kühlung, Heizung und Befeuchtung.
                                            <br><br>
                                            <b>ACHTUNG: KEINE KOMMAS VERWENDEN!</b><br>
                                            Als dezimale Stelle bitte nur den Punkt "." verwenden.<br><br>
                                            <button class="art-button" type="button" onclick="help_umluft_noneFunction()">Schließen</button>
                                        </p>
                                        <hr>
                                        <!----------------------------------------------------------------------------------------Abluft-->
                                        <table style="width: 100%;">
                                            <tr><td rowspan="31" class="td_png_icon"><h3>Timer Abluft</h3><img src="images/luftaustausch.png" alt=""><button class="art-button" type="button" onclick="help_abluft_blockFunction()">Hilfe</button></td>
                                                <td class="text_left_padding">Periode alle </td>
                                                <td class="text_left_padding"><input type="text" size="4" maxlength="4" name="tempoff1" value=<?=round($tempoff1)?>>Minuten</td>
                                            </tr>
                                            <tr><td class="text_left_padding">für die Dauer von</td>
                                                <td class="text_left_padding"><input type="text" maxlength="4" size="4" name="tempon1" value=<?=$tempon1?>>Minuten</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td class="td_submitbutton">
                                                    <input class="art-button" type="submit" value="Speichern" />
                                                </td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_abluft_blockFunction() {
                                                document.getElementById('help_abluft').style.display = 'block';
                                            }
                                            function help_abluft_noneFunction() {
                                                document.getElementById('help_abluft').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_abluft" class="help_p">
                                            <b>Periode:</b> Hier wird die Pausenzeit eingestellt, die bis zum erneuten Einschalten der Abluft abgewartet wird (genau genommen Periode - Dauer). Bei Wert 0 ist die Abluft-Timerfunktion ausgeschaltet. Der maximale Wert liegt bei 1440min.
                                            <br><br>
                                            <b>Dauer:</b> Hier wird die Abluftdauer eingestellt, in während der der Lüfter läuft. Bei Wert 0 ist die Abluft-Timerfunktion ausgeschaltet. Der maximale Wert beträgt 1439min und muss immer kleiner als die Periode sein.
                                            <br><br>
                                            <b>Hinweis:</b> Der Abluft-Lüfter läuft unabhängig von den Timereinstellungen außerdem auch während der Entfeuchtung im Modus "Automatik mit Be- und Entfeuchtung".
                                            <br><br>
                                            <b>ACHTUNG: KEINE KOMMAS VERWENDEN!</b><br>
                                            Als dezimale Stelle bitte nur den Punkt "." verwenden.<br><br>
                                            <button class="art-button" type="button" onclick="help_abluft_noneFunction()">Schließen</button>
                                        </p>
                                    </div>
                                </form>
                                <h2 class="art-postheader">System Reboot/Shutdown</h2>
                                <!----------------------------------------------------------------------------------------Reboot/Shutdown-->
                                <div class="hg_container">
                                    <form  method="post"><button class="art-button" name="Reboot" onclick="return confirm('System neustarten?');">System neustarten</button></td></form><br>
                                    <form  method="post"><button class="art-button" name="Shutdown" onclick="return confirm('System herunterfahren?');">System herunterfahren</button></td></form>
                                </div>
                                <!----------------------------------------------------------------------------------------Content Ende-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            include 'footer.php';
        ?>
