<?php 
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                    include 'modules/names.php';
                                    include 'modules/database.php';
                                    include 'modules/start_stop_program.php';                   // Startet / Stoppt das Reifeprogramm bzw. den ganzen Schrank
                                    
                                    include 'modules/write_table_db.php';                       // Speichert die Auswahl der Reifetabelle
                                    //include 'modules/write_settings_db_logfile_txt.php';        // Speichert die eingestelleten Werte (Temperaturregelung, Feuchte, Lüftung)
                                    //include 'modules/write_config_db_logfile_txt.php';          // Speichert die eingestelle Configuration (Hysteresen, Sensortyp, GPIO's)
                                    
                                    include 'modules/read_settings_db.php';                     // Liest die Einstellungen (Temperaturregelung, Feuchte, Lüftung und deren Hysteresen) und Betriebsart des RSS
                                    include 'modules/read_config_db.php';                       // Liest die Grundeinstellungen Sensortyp, Hysteresen, GPIO's)
                                    include 'modules/read_current_db.php';                      // Liest die gemessenen Werte T/H und den aktuellen Zustand der Aktoren
                                    include 'modules/read_operating_mode_db.php';               // Liest die Art der Reifesteuerung
                                    include 'modules/read_gpio.php';                            // Liest den aktuellen Zustand der GPIO-E/A
                                    // include 'modules/read_csv_dir.php';                         // Liest das Verezichnis mit den Reifeprogrammtabellen ein
                                    
                                    include 'modules/system_reboot.php';                        // Startet das System neu
                                    include 'modules/system_shutdown.php';                      // Fährt das System herunter
                                    
                                    include 'modules/database_empty_statistic_tables.php';      // leert die Statistik-Tabellen (Status, data)
                                    

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
                                        $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
                                        
                                        if($grepmain == NULL and $grepagingtable != NULL) { //wenn main.py nicht läuft und agingtable.py läuft
                                            shell_exec('sudo /var/sudowebscript.sh pkillagingtable'); // Reifetabelle beenden
                                            // Status auf 0 setzen??!!
                                            $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable'); // überprüfen ob reifetabelle wirklich nicht läuft
                                        }

                                        if ($grepmain == NULL and $status_piager == 0){ // wenn main.py nicht läuft und der Status in DB aus ist
                                            echo '<td><img src="images/icons/operatingmode_42x42.png" style="padding: 10px;"></td>
                                            <td><img src="images/icons/status_off_20x20.png" style="padding-top: 10px;"></td>
                                            <td>';
                                            echo "<button class=\"art-button\" name=\"main_start\" onclick=\"return confirm('"._('main.py is not running!'). "\\n". _('start mainprocess and pi-ager?')."');\">"._('start pi-ager')."</button>";
                                            echo '</td>';
                                        }
                                        elseif ($grepmain != NULL and $status_piager == 1){ // wenn main.py läuft und Status in DB eingeschaltet
                                            echo '<td><img src="images/icons/operating_42x42.gif" alt="" style="padding: 10px;"></td><td><img src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;"></td><td>';
                                            echo "<button class=\"art-button\" name=\"pi-ager_agingtable_stop\" onclick=\"return confirm('"._('stop pi-ager?').' \\n '._('if agingtable is running, it will be stopped also!')."');\">"._('stop pi-ager')."</button>";
                                            echo '</td>';
                                        }
                                        elseif ($grepmain != NULL and $status_piager == 0){ //Wenn main.py  läuft und der Status in DB aus ist
                                            echo '<td><img src="images/icons/operatingmode_42x42.png" style="padding: 10px;"></td>
                                            <td><img src="images/icons/status_off_20x20.png" style="padding-top: 10px;"></td>
                                            <td>';
                                            echo "<button class=\"art-button\" name=\"main_start\" onclick=\"return confirm('"._('start pi-ager?')."');\">"._('start pi-ager')."</button>";
                                            echo '</td>';
                                        }
                                        else{ // wenn main.py nicht läuft, aber in DB eingeschaltet ist
                                            echo '<td><img src="images/icons/operatingmode_42x42.png" style="padding: 10px;"></td>
                                            <td><img src="images/icons/status_off_20x20.png" style="padding-top: 10px;"></td>
                                            <td>';
                                            echo "<button class=\"art-button\" name=\"main_start\" onclick=\"return confirm('"._('main.py is not running!'). " \\n ". _('start mainprocess and pi-ager?')."');\">"._('start pi-ager')."</button>";
                                            echo '</td>';
                                        }
                                        print ' </form>';
                                    ?>
                                    </tr></table>
                                    <hr>
                                    <h2><?php echo _('scales') ?></h2>
                                    <table style="width: 100%">
                                        <tr>
                                            <?php 
                                                    print '<form  method="post">';
                                                    // Prüft, ob Prozess scale läuft ( NULL = scale.py läuft nicht als Prozess)
                                                    $grepscale = shell_exec('sudo /var/sudowebscript.sh grepscale');
                                                    if ($grepscale != NULL){
                                                        if (intval(get_table_value($current_values_table,$status_scale1_key)) == 0){
                                                            echo '<td><img src="images/icons/scale_42x42.png" alt="" style="padding: 10px;"></td>
                                                            <td><img src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;"></td>
                                                            <td>';
                                                            echo "<button class=\"art-button\" name=\"scale1_start\" value=\"scale1_start\"onclick=\"return confirm('"._('start measurement on scale').' 1? \\n '._('please tara scale after first start !')."');\">"._('start scale')." 1</button>";
                                                            echo '</td>';
                                                        }
                                                        else {
                                                            echo '<td><img src="images/icons/scale_42x42.gif" alt="" style="padding: 10px;"></td>
                                                            <td><img src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;"></td>
                                                            <td>';
                                                            echo "<button class=\"art-button\" name=\"scale1_tara\" value=\"scale1_tara\"onclick=\"return confirm('"._('tara scale').' 1? \\n '._('please attach the weight to the load cell after a few seconds !')."');\">"._('tara scale')." 1</button>";
                                                            echo '</td><td>';
                                                            echo "<button class=\"art-button\" name=\"scale1_stop\" value=\"scale1_stop\" onclick=\"return confirm('"._('stop measurement on scale')." 1?');\">"._('stop scale')." 1</button>";
                                                            echo '</td>';
                                                        }
                                                    }
                                                    elseif ($grepscale == NULL){
                                                        if (intval(get_table_value($current_values_table,$status_scale1_key)) == 0){
                                                            echo '<td><img src="images/icons/scale_42x42.png" alt="" style="padding: 10px;"></td>
                                                            <td><img src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;"></td>
                                                            <td>';
                                                            echo "<button class=\"art-button\" name=\"scale1_start\" value=\"scale1_start\"onclick=\"return confirm('"._('scaleprocess is not running!'). " \\n " . _('start measurement on scale').' 1? \\n '._('please tara scale after first start !')."');\">"._('start scale')." 1</button>";
                                                            echo '</td>';
                                                        }
                                                    }
                                                    print ' </form>';
                                            ?>
                                        </tr>
                                            <?php 
                                                    print '<form  method="post">';
                                                    // Prüft, ob Prozess scale läuft ( NULL = scale.py läuft nicht als Prozess)
                                                    $grepscale = shell_exec('sudo /var/sudowebscript.sh grepscale');
                                                    if ($grepscale != NULL){
                                                        if (intval(get_table_value($current_values_table,$status_scale2_key)) == 0){
                                                            echo '<td><img src="images/icons/scale_42x42.png" alt="" style="padding: 10px;"></td>
                                                            <td><img src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;"></td>
                                                            <td>';
                                                            echo "<button class=\"art-button\" name=\"scale2_start\" value=\"scale2_start\"onclick=\"return confirm('"._('start measurement on scale').' 2? \\n '._('please tara scale after first start !')."');\">"._('start scale')." 2</button>";
                                                            echo '</td>';
                                                        }
                                                        else {
                                                            echo '<td><img src="images/icons/scale_42x42.gif" alt="" style="padding: 10px;"></td>
                                                            <td><img src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;"></td>
                                                            <td>';
                                                            echo "<button class=\"art-button\" name=\"scale2_tara\" value=\"scale1_tara\"onclick=\"return confirm('"._('tara scale').' 2? \\n '._('please attach the weight to the load cell after a few seconds !')."');\">"._('tara scale')." 2</button>";
                                                            echo '</td><td>';
                                                            echo "<button class=\"art-button\" name=\"scale2_stop\" value=\"scale1_stop\" onclick=\"return confirm('"._('stop measurement on scale')." 2?');\">"._('stop scale')." 2</button>";
                                                            echo '</td>';
                                                        }
                                                    }
                                                    elseif ($grepscale == NULL){
                                                        if (intval(get_table_value($current_values_table,$status_scale2_key)) == 0){
                                                            echo '<td><img src="images/icons/scale_42x42.png" alt="" style="padding: 10px;"></td>
                                                            <td><img src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;"></td>
                                                            <td>';
                                                            echo "<button class=\"art-button\" name=\"scale2_start\" value=\"scale2_start\"onclick=\"return confirm('"._('scaleprocess is not running!'). " \\n " . _('start measurement on scale').' 2? \\n '._('please tara scale after first start !')."');\">"._('start scale')." 2</button>";
                                                            echo '</td>';
                                                        }
                                                    }
                                                    print ' </form>';
                                            ?>
                                        </tr>
                                    </table>
                                    <hr>
                                    <!----------------------------------------------------------------------------------------Reifetabelle auswählen-->

                                    <table style="width: 100%" class="switching_state miniature_writing">
                                        <tr>
                                            <td>
                                                <img src="images/icons/agingtable_42x42.png" alt="">
                                            </td>
                                            <td style=" text-align: left; padding-left: 20px;">
                                                <?php 
                                                    print '<form  method="post">';
                                                    $agingtable_names = get_agingtable_names();
                                                    foreach($agingtable_names as $name) {
                                                        if ($name==$desired_maturity){
                                                            echo '<input type="radio" name="agingtable" value="'.$name.'" checked="checked"><label> '.$name.'</label><br>';
                                                        }
                                                        else
                                                        {
                                                            echo '<input type="radio" name="agingtable" value="'.$name.'"><label> '.$name.'</label><br>';

                                                        }
                                                    }
                                                    echo '</td><td>';
                                                    if ($grepagingtable == NULL){
                                                        echo '<img src="images/icons/status_off_20x20.png" alt="" style="padding-right: 20px;">';
                                                    }
                                                    else {
                                                        echo '<img src="images/icons/status_on_20x20.png" alt="" style="padding-right: 20px;">';
                                                    }
                                                ?>
                                            <?php
                                                    if ($grepagingtable == NULL){
                                                            echo '<img src="images/icons/agingtable_42x42.png" alt="" style="padding-left: 10px;">';
                                                        }
                                                        else {
                                                            echo '<img src="images/icons/agingtable_42x42.gif" alt="" style="padding-left: 10px;">';
                                                        }
                                                ?></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td style=" text-align: left; padding-left: 20px;"><br>
                                                <?php 
                                                    echo "<button class=\"art-button\" name=\"save_agingtable\" value=\"save_agingtable\"onclick=\"return confirm('"._('save new agingtable?')."');\">"._('save')."</button>";
                                                    echo '</form>';
                                                ?>
                                            </td>
                                            <td><br>
                                                <?php 
                                                    echo '<form  method="post">';
                                                    if ($grepagingtable == NULL){
                                                        echo "<button class=\"art-button\" name=\"pi-ager_agingtable_start\" onclick=\"return confirm('"._('start agingtable?')." \\n "._('manual values will be overwritten!')."');\">"._('start agingtable')."</button>";
                                                    }
                                                    else {
                                                        echo "<button class=\"art-button\" name=\"agingtable_stop\" onclick=\"return confirm('"._('stop agingtable?').' \\n '._('pi-ager continues with the last values of the agingtable!')."');\">"._('stop agingtable')."</button>";
                                                    }
                                                    echo '</form>';
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <table id="show_agingtable" class="show_agingtable">
                                        <tr style="background-color: #F0F5FB; border-bottom: 1px solid #000033">
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('modus') ?><span class="tooltiptext"><?php echo _('aging-modus'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">&phi;<span class="tooltiptext"><?php echo _('target humidity in %'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">°C<span class="tooltiptext"><?php echo _('target temperature in °C'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('timer circulate d') ?><span class="tooltiptext"><?php echo _('timer of the circulation air duration in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('timer circulate p') ?><span class="tooltiptext"><?php echo _('timer of the circulation air period in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('timer exhaust d') ?><span class="tooltiptext"><?php echo _('timer of the exhausting air duration in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('timer exhaust p') ?><span class="tooltiptext"><?php echo _('timer of the exhausting air period in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('days') ?><span class="tooltiptext"><?php echo _('duration of hanging phase in days'); ?></span></div></td>
                                        </tr>
                                        <?php 
                                            // Gewählte Agingtable aus DB auslesen und als Tabelle beschreiben
                                            $index_row = 0;
                                            $agingtable_rows = get_agingtable_dataset($desired_maturity);
                                            try {
                                                $number_rows = count($agingtable_rows);
                                                while ($index_row < $number_rows) {
                                                    $dataset = $agingtable_rows[$index_row];
                                                    // $num = count($dataset);
                                                    if (!empty($dataset[$agingtable_modus_field])){
                                                        $data_modus = $dataset[$agingtable_modus_field];
                                                    } else {$data_modus = '..';}
                                                    if (!empty($dataset[$agingtable_setpoint_humidity_field])){
                                                        $data_setpoint_humidity = $dataset[$agingtable_setpoint_humidity_field];
                                                    } else {$data_setpoint_humidity = '..';}
                                                    if (!empty($dataset[$agingtable_setpoint_temperature_field])){
                                                        $data_setpoint_temperature = $dataset[$agingtable_setpoint_temperature_field];
                                                    } else {$data_setpoint_temperature = '..';}
                                                    if (!empty($dataset[$agingtable_circulation_air_duration_field])){
                                                        $data_circulation_air_duration = $dataset[$agingtable_circulation_air_duration_field]/60;
                                                    } else {$data_circulation_air_duration = '..';}
                                                    if (!empty($dataset[$agingtable_circulation_air_period_field])){
                                                        $data_circulation_air_period = $dataset[$agingtable_circulation_air_period_field]/60;
                                                    } else {$data_circulation_air_period = '..';}
                                                    if (!empty($dataset[$agingtable_exhaust_air_duration_field])){
                                                        $data_exhaust_air_duration = $dataset[$agingtable_exhaust_air_duration_field]/60;
                                                    } else {$data_exhaust_air_duration = '..';}
                                                    if (!empty($dataset[$agingtable_exhaust_air_period_field])){
                                                        $data_exhaust_air_period = $dataset[$agingtable_exhaust_air_period_field]/60;
                                                    } else {$data_exhaust_air_period = '..';}
                                                    if (!empty($dataset[$agingtable_days_field])){
                                                        $data_days = $dataset[$agingtable_days_field];
                                                    } else {$data_days = '..';}
                                                    echo '<tr>';
                                                        echo '<td>'. $data_modus .'</td>';
                                                        echo '<td>'. $data_setpoint_humidity .'</td>';
                                                        echo '<td>'. $data_setpoint_temperature .'</td>';
                                                        echo '<td>'. $data_circulation_air_duration .'</td>';
                                                        echo '<td>'. $data_circulation_air_period .'</td>';
                                                        echo '<td>'. $data_exhaust_air_duration .'</td>';
                                                        echo '<td>'. $data_exhaust_air_period .'</td>';
                                                        echo '<td>'. $data_days .'</td>';
                                                    echo '</tr>';
                                                    $index_row++;
                                                } 
                                             }
                                             catch (Exception $e) {
                                                }
                                        ?>
                                    </table>
                                </div>
                                <?php 
                                    if ($grepagingtable == NULL){
                                        include ('manvals.php');
                                        }
                                    else {
                                        echo '<h2 class=\"art-postheader\">'._('manual values'); '</h2>
                                                <div class=\"hg_container\"><b>'._('manual adjustments are not possible').'</b><br>'._('during fully automatic aging.').'</div>';
                                         }
                                ?>
                                <h2 class="art-postheader"><?php echo _('general configuration'); ?></h2>
                                <?php 
                                    include ('config.php'); 
                                ?>
                                <hr>
                                <h2 class="art-postheader"><?php echo _('system'); ?></h2>
                                <!----------------------------------------------------------------------------------------Reboot/Shutdown-->
                                <div class="hg_container">
                                    <form  method="post" name="boot">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td><button class="art-button" name="reboot" onclick="return confirm('<?php echo _('ATTENTION: reboot system?');?>');"><?php echo _('reboot'); ?></button></td>
                                                <td><button class="art-button" name="shutdown" onclick="return confirm('<?php echo _('ATTENTION: shutdown system?');?>');"><?php echo _('shutdown'); ?></button></td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                                <hr>
                                <h2 class="art-postheader"><?php echo _('database'); ?></h2>
                                <!----------------------------------------------------------------------------------------Database-->
                                <div class="hg_container" >
                                    
                                        <table style="width: 100%;">
                                            <tr>
                                                <form method="post" name="database">
                                                    <td><button class="art-button" name="empty_statistic_tables" value="empty_statistic_tables" onclick="return confirm('<?php echo _('ATTENTION: empty statistic tables?');?>');"><?php echo _('empty statistic tables'); ?></button></td>
                                                </form>
                                                <td><button class="art-button" name="database_administration" onclick="window.location.href='/phpliteadmin.php'"><?php echo _('database administration'); ?></button></td>
                                            </tr>
                                        </table>
                                    
                                    
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
