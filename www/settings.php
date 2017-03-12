                                <?php 
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                    include 'modules/write_table.json.php';                     // Speichert die Auswahl der Reifetabelle
                                    include 'modules/write_settings.json_logfile.txt.php';      // Speichert die eingestelleten Werte (Temperaturregelung, Feuchte, Lüftung und deren Hysteresen)
                                    include 'modules/start_stop_program.php';                   // Startet / Stoppt das Reifeprogramm bzw. den ganzen Schrank
                                    include 'modules/read_settings_json.php';                   // Liest die Einstellungen (Temperaturregelung, Feuchte, Lüftung und deren Hysteresen) und Betriebsart des RSS
                                    include 'modules/read_current_json.php';                    // Liest die gemessenen Werte T/H und den aktuellen Zustand der Aktoren
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
                                            $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
                                            // Prüft, ob Prozess Reifetab läuft ()
                                            $grephangingtable = shell_exec('sudo /var/sudowebscript.sh grephangingtable');
                                            
                                            if($grepmain == NULL and $grephangingtable != NULL) { //wenn Prozess RSS läuft und Reifetab läuft nicht (korrekt)
                                                shell_exec('sudo /var/sudowebscript.sh pkillreifetab');
                                                $grephangingtable = shell_exec('sudo /var/sudowebscript.sh grephangingtable');
                                            }

                                            if ($grepmain == NULL){
                                                print '<td><img src="images/operating_mode.png" alt="" style="padding: 10px;"></td><td><img src="images/led-off-green-20x20.png" alt="" style="padding-top: 10px;"></td><td style=""><button class="art-button" name="rss_start">Start Reifeschrank</button></td>';
                                            }
                                            else {
                                                print '<td><img src="images/operating_mode.png" alt="" style="padding: 10px;"></td><td><img src="images/led-on-green-20x20.png" alt="" style="padding-top: 10px;"></td><td><button class="art-button" name="rss_reifetab_stop" onclick="return confirm("Reifeschrank stoppen?");">Stop Reifeschrank</button></td>';
                                            }
                                            print ' </form>';
                                    ?>
                                    </tr></table>
                                    <hr>
                                    <!----------------------------------------------------------------------------------------Reifetabelle auswählen-->

                                    <table style="width: 100%" class="schaltzustaende minischrift">
                                        <tr>
                                            <td><img src="images/hangingtable.png" alt="" style="padding-left: 10px;"></td>
                                            <td style=" text-align: left; padding-left: 20px;">
                                                <?php 
                                                    print '<form  method="post">';
                                                    foreach($csvfilename as $name) {
                                                        if ($name<>$desired_maturity){
                                                            echo '<input type="radio" name="Reifetab" value="'.$name.'"><label> '.$name.'</label><br>';
                                                        }
                                                        if ($name==$desired_maturity){
                                                            echo '<input type="radio" name="Reifetab" value="'.$name.'" checked="checked"><label> '.$name.'</label><br>';
                                                        }
                                                    }
                                                    print '</td><td>';
                                                    if ($grephangingtable == NULL){
                                                        print '<img src="images/led-off-green-20x20.png" alt="" style="padding-right: 20px;">';
                                                    }
                                                    else {
                                                        print '<img src="images/led-on-green-20x20.png" alt="" style="padding-right: 20px;">';
                                                    }
                                                ?>
                                            <img src="images/hangingtable.png" alt=""></td>
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
                                                    if ($grephangingtable == NULL){
                                                        print "<button class=\"art-button\" name=\"rss_reifetab_start\" onclick=\"return confirm('Reifeprogramm starten?\\nManuelle Werte werden überschrieben!');\">Start Tabelle</button>";
                                                    }
                                                    else {
                                                        print "<button class=\"art-button\" name=\"reifetab_stop\" onclick=\"return confirm('Reifeprogramm stoppen?\\nDer Schrank arbeitet mit den letzten Werten der Reifetabelle weiter!');\">Stop Tabelle</button>";
                                                    }
                                                    print '</form>';
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
                                            $chosen_hangingtable='csv/'.$desired_maturity.'.csv';
                                            $row = 1;
                                            if (($handle = fopen($chosen_hangingtable, "r")) !== FALSE) {
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
                                <?php 
                                    if ($grephangingtable == NULL){
                                        include ('manvals.php');
                                                    }
                                                    else {
                                                        print "<h2 class=\"art-postheader\">Manuelle Werte</h2>
                                                                <div class=\"hg_container\"><b>Während des vollautomatischen Reifebetriebes</b><br>sind manuelle Einstellungen nicht möglich.
                                                                </div>";
                                                         }
                                                ?>
                                <h2 class="art-postheader">System</h2>
                                <!----------------------------------------------------------------------------------------Reboot/Shutdown-->
                                <div class="hg_container">
                                    <form  method="post">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td><button class="art-button" name="Reboot" onclick="return confirm('ACHTUNG: System neustarten?');">Neustarten</button></td>
                                                <td><button class="art-button" name="Shutdown" onclick="return confirm('ACHTUNG: System herunterfahren?');">Herunterfahren</button></td>
                                            </tr>
                                        </table>
                                    </form>
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
