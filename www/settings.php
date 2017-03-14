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
                                <h2 class="art-postheader"><?php echo _('operating values'); ?></h2>
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
                                                echo '<td><img src="images/operating_mode.png" alt="" style="padding: 10px;"></td><td><img src="images/led-off-green-20x20.png" alt="" style="padding-top: 10px;"></td><td style=""><button class="art-button" name="pi-ager_start">'._('start pi-ager').'</button></td>';
                                            }
                                            else {
                                                echo '<td><img src="images/operating_mode.png" alt="" style="padding: 10px;"></td><td><img src="images/led-on-green-20x20.png" alt="" style="padding-top: 10px;"></td><td><button class="art-button" name="pi-ager_hangingtable_stop" onclick="return confirm("'._("stop pi-ager?").'");">'._("stop pi-ager?").'</button></td>';
                                            }
                                            print ' </form>';
                                    ?>
                                    </tr></table>
                                    <hr>
                                    <!----------------------------------------------------------------------------------------Reifetabelle auswählen-->

                                    <table style="width: 100%" class="switching_state miniature_writing">
                                        <tr>
                                            <td><img src="images/hangingtable.png" alt="" style="padding-left: 10px;"></td>
                                            <td style=" text-align: left; padding-left: 20px;">
                                                <?php 
                                                    print '<form  method="post">';
                                                    foreach($csvfilename as $name) {
                                                        if ($name<>$desired_maturity){
                                                            echo '<input type="radio" name="hanging_table" value="'.$name.'"><label> '.$name.'</label><br>';
                                                        }
                                                        if ($name==$desired_maturity){
                                                            echo '<input type="radio" name="hanging_table" value="'.$name.'" checked="checked"><label> '.$name.'</label><br>';
                                                        }
                                                    }
                                                    echo '</td><td>';
                                                    if ($grephangingtable == NULL){
                                                        echo '<img src="images/led-off-green-20x20.png" alt="" style="padding-right: 20px;">';
                                                    }
                                                    else {
                                                        echo '<img src="images/led-on-green-20x20.png" alt="" style="padding-right: 20px;">';
                                                    }
                                                ?>
                                            <img src="images/hangingtable.png" alt=""></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td style=" text-align: left; padding-left: 20px;"><br>
                                                <?php 
                                                    echo '<input class="art-button" type="submit" value="'._("save").'" />';
                                                    echo '</form>';
                                                ?>
                                            </td>
                                            <td><br>
                                                <?php 
                                                    echo '<form  method="post">';
                                                    if ($grephangingtable == NULL){
                                                        echo "<button class=\"art-button\" name=\"pi-ager_hangingtable_start\" onclick=\"return confirm('"._('start hangingtable?')."\\n"._('manual values are overwritten!')."');\">"._('start hangingtable')."</button>";
                                                    }
                                                    else {
                                                        echo "<button class=\"art-button\" name=\"pi-ager_hangingtable_stop\" onclick=\"return confirm('"._('stop hangingtable?').'\\n'._('pi-ager continues with the last values of the hangingtable!')."');\">"._('stop hangingtable')."</button>";
                                                    }
                                                    echo '</form>';
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <table id="show_hangingtable" class="show_hangingtable">
                                        <tr style="background-color: #F0F5FB; border-bottom: 1px solid #000033">
                                            <td class="show_agingcell"><div class="tooltip">S%<span class="tooltiptext"><?php echo _('target humidity in %'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">D%<span class="tooltiptext"><?php echo _('delay of humidification in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">ON%<span class="tooltiptext"><?php echo _('switch-on value of humidification in %'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">OFF%<span class="tooltiptext"><?php echo _('Switch-off value of humidification in %'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">S°C<span class="tooltiptext"><?php echo _('target temperature in °C'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">ON°C<span class="tooltiptext"><?php echo _('switch-on value of the temperature in °C'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">OFF°C<span class="tooltiptext"><?php echo _('switch-off value of the temperature in °C'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">TUD<span class="tooltiptext"><?php echo _('timer of the recirculation duration in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">TUP<span class="tooltiptext"><?php echo _('timer of the recirculation period in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">TAD<span class="tooltiptext"><?php echo _('timer of the exhausting air duration in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">TAP<span class="tooltiptext"><?php echo _('timer of the exhausting air period in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">TAG<span class="tooltiptext"><?php echo _('duration of hanging phase in days'); ?></span></div></td>
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
                                                        echo '<h2 class=\"art-postheader\">'._('manual values'); '</h2>
                                                                <div class=\"hg_container\"><b>'._('manual adjustments are not possible').'</b><br>'._('during fully automatic aging.').'</div>';
                                                         }
                                                ?>
                                <h2 class="art-postheader"><?php echo _('system'); ?></h2>
                                <!----------------------------------------------------------------------------------------Reboot/Shutdown-->
                                <div class="hg_container">
                                    <form  method="post">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td><button class="art-button" name="reboot" onclick="return confirm('<?php echo _('ATTENTION: reboot system?');?>');"><?php echo _('reboot'); ?></button></td>
                                                <td><button class="art-button" name="shutdown" onclick="return confirm('<?php echo _('ATTENTION: shutdown system?');?>');"><?php echo _('shutdown'); ?></button></td>
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
