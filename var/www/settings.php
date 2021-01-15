<?php 
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                    include 'modules/names.php';
                                    include 'modules/database.php';
                                    include 'modules/logging.php';                            //liest die Datei fuer das logging ein
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
                                    
                                    
                                    
                                ?>
                                <h2 class="art-postheader"><?php echo _('operating values'); ?></h2>
                                <!----------------------------------------------------------------------------------------Programme starten/stoppen-->
                                <div class="hg_container">
                                    <h2>Pi-Ager</h2>
                                    <table style="width: 100%">
                                    <tr>
                                        <td width="100px"></td>
                                        <td width="100px"></td>
                                        <td width="200px"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                    <?php 
                                        print '<form  method="post">';
                                        // Prüft, ob Prozess RSS läuft ( NULL = Rss.py läuft nicht als Prozess, )
                                        //$grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
                                        // Prüft, ob Prozess Reifetab läuft ()
                                        //$grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
                                        //$grepbackup = shell_exec('sudo /var/sudowebscript.sh grepbackup');
                                        if ($grepbackup != NULL){ //wenn backup läuft
                                            echo '<td><img src="images/icons/operatingmode_backup_42x42.png" style="padding: 10px;"></td>
                                            <td></td>
                                            <td></td><td></td><td></td>';
                                            echo "Backup is currently running! ";
                                        }
                                        elseif ($grepmain == NULL){ // wenn main.py nicht läuft und der Status in DB aus ist
                                            
                                            echo '<td><img src="images/icons/operatingmode_fail_42x42.png" style="padding: 10px;"></td>
                                            <td></td>
                                            <td>';
                                            echo "now you have proof that developers are not perfect! ";
                                            echo " please go to: ";
                                            echo '<a href="'.$error_reporting_url.'" target="_blank">Error reporting</a>';
                                            echo '</td><td></td>';
                                        }
                                        elseif ($grepmain != NULL and $status_piager == 1){ // wenn main.py läuft und Status in DB eingeschaltet
                                            echo '<td><img src="images/icons/operating_42x42.gif" alt="" style="padding: 10px;"></td><td><img src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;"></td><td>';
                                            echo "<button class=\"art-button\" name=\"pi-ager_agingtable_stop\" value=\"pi-ager_agingtable_stop\" onclick=\"return confirm('"._('stop pi-ager?').' \\n '._('if agingtable is running, it will be stopped also!')."');\">"._('stop pi-ager')."</button>";
                                            echo '</td>';
                                        }
                                        elseif ($grepmain != NULL and $status_piager == 0){ //Wenn main.py  läuft und der Status in DB aus ist
                                            echo '<td><img src="images/icons/operatingmode_42x42.png" style="padding: 10px;"></td>
                                            <td><img src="images/icons/status_off_20x20.png" style="padding-top: 10px;"></td>
                                            <td>';
                                            echo "<button class=\"art-button\" name=\"main_start\" value=\"main_start\" onclick=\"return confirm('"._('start pi-ager?')."');\">"._('start pi-ager')."</button>";
                                            echo '</td>';
                                        }
                                        print ' </form>';
                                    ?>
                                        <td></td>
                                    </tr></table>
                                    <hr>
                                    <h2><?php echo _('scales') ?></h2>
                                    <table style="width: 100%">
                                        <tr>
                                            <td width="100px"></td>
                                            <td width="100px"></td>
                                            <td width="200px"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <?php
                                            print '<tr>';
                                            // Prüft, ob Prozess scale läuft ( NULL = scale.py läuft nicht als Prozess)
                                            //$grepscale = shell_exec('sudo /var/sudowebscript.sh grepscale');
                                            if ($grepbackup != NULL){ //wenn backup läuft
                                                echo '<td><img src="images/icons/operatingmode_backup_42x42.png" style="padding: 10px;"></td>
                                                <td></td>
                                                <td>';
                                                echo "Backup is currently running! ";
                                            }
                                            elseif ($grepscale == NULL){
                                                echo '<td><img src="images/icons/scale_fail_42x42.png" alt="" style="padding: 10px;"></td>
                                                <td></td>
                                                <td>';
                                                echo "now you have proof that developers are not perfect! ";
                                                echo " please go to: ";
                                                echo '<a href="'.$error_reporting_url.'" target="_blank">Error reporting</a>';
                                                echo '</td><td></td>';
                                                echo '</tr>';
                                            }
                                            elseif ($grepscale != NULL){
                                                if (intval(get_table_value($settings_scale1_table, $referenceunit_key)) == 0){
                                                    $tara_scale1_disabled = true;
                                                }
                                                else{
                                                    $tara_scale1_disabled = false;
                                                }
                                                if (intval(get_table_value($settings_scale2_table, $referenceunit_key)) == 0){
                                                    $tara_scale2_disabled = true;
                                                }
                                                else{
                                                    $tara_scale2_disabled = false;
                                                }
                                                print '<form  method="post">';
                                                if (intval(get_table_value($current_values_table,$status_scale1_key)) == 0){
                                                        echo '<td><img src="images/icons/scale_42x42.png" alt="" style="padding: 10px;"></td>
                                                        <td><img src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;"></td>
                                                        <td>';
                                                        if ($tara_scale1_disabled){
                                                            echo "<button class=\"art-button\" onclick=\"alert('"._('Reference unit is 0 in Scale').' 1! \\n '._('Please calibrate scale first!')."');\">"._('start scale')." 1</button>";
                                                        }
                                                        else{
                                                            echo "<button class=\"art-button\" name=\"scale1_start\" value=\"scale1_start\" onclick=\"return confirm('"._('start measurement on scale').' 1? \\n '._('please calibrate scale after first start !')."');\">"._('start scale')." 1</button>";
                                                        }
                                                        echo '</td><td></td></tr><tr>';
                                                    }
                                                    elseif (intval(get_table_value($current_values_table,$status_scale1_key)) == 1) {
                                                        echo '<td><img src="images/icons/scale_42x42.gif" alt="" style="padding: 10px;"></td>
                                                        <td><img src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;"></td>
                                                        <td>';
                                                        if ($tara_scale1_disabled){
                                                            echo "<button class=\"art-button\" onclick=\"alert('"._('Reference unit is 0 in Scale').' 1! \\n '._('Please calibrate scale first!')."');\">"._('tara scale')." 1</button>";
                                                        }
                                                        else{
                                                            echo "<button class=\"art-button\" name=\"scale1_tara\" value=\"scale1_tara\" onclick=\"return confirm('"._('tara on scale').' 1? \\n '._('please relieve the load cell completely')."!');\">"._('tara scale')." 1</button>";
                                                        }
                                                        echo '</td><td>';
                                                        echo "<button class=\"art-button\" name=\"scale1_stop\" value=\"scale1_stop\" onclick=\"return confirm('"._('stop measurement on scale')." 1?');\">"._('stop scale')." 1</button>";
                                                        echo '</td></tr><tr>';
                                                    }
                                                    if (intval(get_table_value($current_values_table,$status_scale2_key)) == 0){
                                                        echo '<td><img src="images/icons/scale_42x42.png" alt="" style="padding: 10px;"></td>
                                                        <td><img src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;"></td>
                                                        <td>';
                                                        if ($tara_scale2_disabled){
                                                            echo "<button class=\"art-button\" onclick=\"alert('"._('Reference unit is 0 in Scale').' 2! \\n '._('Please calibrate scale first!')."');\">"._('start scale')." 2</button>";
                                                        }
                                                        else{
                                                            echo "<button class=\"art-button\" name=\"scale2_start\" value=\"scale2_start\" onclick=\"return confirm('"._('start measurement on scale').' 2? \\n '._('please calibrate scale after first start !')."');\">"._('start scale')." 2</button>";
                                                        }
                                                        echo '</td><td></td>';
                                                    }
                                                    elseif (intval(get_table_value($current_values_table,$status_scale2_key)) == 1){
                                                        echo '<td><img src="images/icons/scale_42x42.gif" alt="" style="padding: 10px;"></td>
                                                        <td><img src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;"></td>
                                                        <td>';
                                                        if ($tara_scale2_disabled){
                                                            echo "<button class=\"art-button\" onclick=\"alert('"._('Reference unit is 0 in Scale').' 2! \\n '._('Please calibrate scale first!')."');\">"._('tara scale')." 2</button>";
                                                        }
                                                        else{
                                                            echo "<button class=\"art-button\" name=\"scale2_tara\" value=\"scale2_tara\" onclick=\"return confirm('"._('tara on scale').' 2? \\n '._('please relieve the load cell completely')."!');\">"._('tara scale')." 2</button>";
                                                        }
                                                        echo '</td><td>';
                                                        echo "<button class=\"art-button\" name=\"scale2_stop\" value=\"scale2_stop\" onclick=\"return confirm('"._('stop measurement on scale')." 2?');\">"._('stop scale')." 2</button>";
                                                        echo '</td>';
                                                    }
                                                    print ' </form>';
                                                    print '</tr>';
                                                    print '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
                                                    print '<tr><td></td><td style="text-align: right;"><form action="scale_wizzard.php" method="post"> <input type="radio" name="scale_wizzard_radiobutton" value="' . $scale1_key . '" checked="checked"><label> '._('scale').'&nbsp1</label><br><input type="radio" name="scale_wizzard_radiobutton" value="' . $scale2_key . '"><label> '._('scale').'&nbsp2</label> </td><td style="text-align: left;">';
                                                    echo '<td>';
                                                    echo "<button class=\"art-button\" name=\"scale_wizzard\" value=\"scale_wizzard\"  onclick=\"return confirm('"._('attention').' ! \\n '._('measurement on scales are stopped'). ' \\n ' . _('please relieve the load cell completely') . "!');\">"._('calibrate wizzard')."</button>";
                                                    print '</form></td></tr>';
                                            } 
                                        ?>
                                        
                                    </table>
                                    <hr>
                                    <h2><?php echo _('agingtable') ?></h2>
                                    <!----------------------------------------------------------------------------------------Reifetabelle auswählen-->
                                    <?php $agingtable_names = get_agingtable_names(); ?>
                                    <table style="width: 100%" class="switching_state miniature_writing">
                                        <tr>
                                            <td width="100px"></td>
                                            <td width="100px"></td>
                                            <td width="150px"></td>
                                            <td ></td>
                                            <td ></td>
                                        </tr>
                                        <form  method="post">
                                        <tr>
                                            <td>
                                                <?php
                                                    if ($grepagingtable == NULL){
                                                        echo '<img src="images/icons/agingtable_42x42.png" alt="" style="padding: 10px;">';
                                                        echo '</td><td>';
                                                        echo '<img src="images/icons/status_off_20x20.png" alt="" style="padding: 10px;">';
                                                    }
                                                    else {
                                                        echo '<img src="images/icons/agingtable_42x42.gif" alt="" style="padding: 10px;">';
                                                        echo '</td><td>';
                                                        echo '<img src="images/icons/status_on_20x20.png" alt="" style="padding: 10px;">';
                                                        
                                                    }
                                                ?>
                                            </td>
                                            <td style="text-align: left;">
                                                <?php 
                                                    if (isset ($agingtable_names)){
                                                        foreach($agingtable_names as $name) {
                                                            if ($name==$desired_maturity){
                                                                echo '<input type="radio" name="agingtable" value="'.$name.'" checked="checked"><label> '.$name.'</label><br>';
                                                            }
                                                            else
                                                            {
                                                                echo '<input type="radio" name="agingtable" value="'.$name.'"><label> '.$name.'</label><br>';

                                                            }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td align="left"><?php echo _('startphase') . ':' ?> <input width="50px" type="number" step="1" name="agingtable_startperiod"> <br>
                                            <?php echo _('startday') . ':' ?><input width="50px"type="number" step="1" name="agingtable_startday"></td>
                                            <td align="left"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align: left;">
                                                <?php
                                                    if (isset ($agingtable_names)){
                                                        echo "<button class=\"art-button\" name=\"select_agingtable\" value=\"select_agingtable\"onclick=\"return confirm('"._('select new agingtable?')."');\">"._('select')."</button>";
                                                    }
                                                ?>
                                            </td>
                                            <td style=" text-align: left; padding-left: 20px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align: left;">
                                                <?php
                                                    if (isset ($agingtable_names)){
                                                            if ($grepagingtable == NULL){
                                                                echo "<button class=\"art-button\" name=\"pi-ager_agingtable_start\" value=\"pi-ager_agingtable_start\" onclick=\"return confirm('"._('start agingtable?')." \\n "._('manual values will be overwritten in database!')."');\">"._('start agingtable')."</button>";
                                                            }
                                                            else {
                                                                echo "<button class=\"art-button\" name=\"agingtable_stop\" value=\"agingtable_stop\" onclick=\"return confirm('"._('stop agingtable?').' \\n '._('pi-ager continues with the last values of the agingtable!')."');\">"._('stop agingtable')."</button>";
                                                            }
                                                        }
                                                ?>
                                            </td>
                                            <td></td>
                                            <td align="left"></td>
                                        </tr>
                                    </table>
                                    </form>
                                            
                                            
                                    <table>
                                        <form  id="agingtable_edit" method="post">
                                        <tr>
                                            <td width="100px"></td>
                                            <td width="100px"></td>
                                            <td width="200px"></td>
                                            <td ></td>
                                            <td ></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td colspan=3><br>
                                                <?php 
                                                    if (isset ($agingtable_names)){
                                                        echo '<select name="agingtable_edit">';
                                                        foreach($agingtable_names as $name) {
                                                            if ($name!=$desired_maturity){
                                                                echo '<option value="'.$name.'">'.$name.'<br>';
                                                            }
                                                            else
                                                            {
                                                                echo '<option value="'.$name.'" selected>'.$name.'<br>';
                                                            }
                                                        }
                                                        echo '</select>';
                                                        echo "<button class=\"art-button\" id=\"edit_agingtable\" form=\"agingtable_edit\" formaction=\"/edit_agingtable.php\" name=\"edit_agingtable\" value=\"edit_agingtable\" onclick=\"return confirm('"._('edit agingtable?')."');\">"._('edit')."</button>";
                                                        echo "<button class=\"art-button\" id=\"delete_agingtable\" form=\"agingtable_edit\" name=\"delete_agingtable\" value=\"delete_agingtable\"onclick=\"return confirm('"._('delete agingtable?')."');\">"._('delete')."</button>";
                                                        echo "<button class=\"art-button\" id=\"export_agingtable\" form=\"agingtable_edit\" name=\"export_agingtable\" value=\"export_agingtable\"onclick=\"return confirm('"._('export agingtable?')."');\">"._('export')."</button>";
                                                    }
                                                    print '</form>';
                                                    
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="20px">&nbsp;</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td colspan=2 style=" text-align: left; >
                                                <?php
                                                    print '<form method="post" id="upload_new_agingtable" enctype="multipart/form-data">';
                                                    print '<input type="file" name="file" id="csv-file"  accept=".csv" onchange="enableButton()">';
                                                    echo '</form>';
                                                ?>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td style=" text-align: left; ">
                                                <script>
                                                    function enableButton() {
                                                        document.getElementById("upload_new_agingtable_button").disabled = false;
                                                    }
                                                </script>
                                                <?php 
                                                    echo "<button class=\"art-button\" disabled=\"true\" id=\"upload_new_agingtable_button\" form=\"upload_new_agingtable\" name=\"upload_new_agingtable\" value=\"upload_new_agingtable\" onclick=\"return confirm('"._('upload new agingtable?')."');\">"._('upload')."</button>";
                                                ?>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <?php
                                         $current_period = get_table_value($current_values_table, $agingtable_period_key);
                                     ?>
                                    <table style="width: 100%" class="switching_state miniature_writing">
                                        <tr>
                                            <td width="75px" colspan="2" align="left"><?php echo _('actual phase and day') ?></td>
                                        </tr>
                                        <tr>
                                            <td width="75px"><?php echo _('phase') ?></td><td align="left"><?php echo intval($current_period) + 1 ?></td>
                                        </tr>
                                        <tr>
                                            <td width="75px"><?php echo _('day')  ?></td><td align="left"><?php echo $current_period  ?></td>
                                        </tr>
                                    </table>
                                    <table id="show_agingtable" class="show_agingtable">
                                        <tr style="background-color: #F0F5FB; border-bottom: 1px solid #000033">
                                            <td class="show_agingcell"><div class="tooltip"><?php echo  _('phase') ?><span class="tooltiptext"><?php echo   _('phase') ?></span></div></td>
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
                                            if ($agingtable_rows != false){
                                                $firstrow = $agingtable_rows[0];
                                                $agingtable_comment = $firstrow[$agingtable_comment_field];
                                                if (!isset($agingtable_comment)){
                                                    $agingtable_comment = _('no comment');
                                                }
                                                
                                                //$current_period_0 = $current_period - 1;
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

                                                        if ($current_period == $index_row AND $grepagingtable != NULL){
                                                            echo '<tr bgcolor=#D19600 >';
                                                        }
                                                        else{
                                                            echo '<tr>';
                                                        }
                                                            echo '<td>'. ($index_row + 1) .'</td>';
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
                                            }
                                        ?>
                                    </table>
                                    <table style="width: 100%" class="switching_state miniature_writing">
                                        <tr>
                                        <?php 
                                            $agingtable_comment_with_carriage_return = nl2br($agingtable_comment);
                                            echo  '<td>' . _('comment:') . '</td><td>' . $agingtable_comment_with_carriage_return . '</td>';
                                        ?>
                                        </tr>
                                    </table>
                                </div>
                                <?php 
                                    if ($grepagingtable == NULL){
                                        include ('manvals.php');
                                        }
                                    else {
                                        echo '<h2 class="art-postheader">'._('manual values').'</h2>';
                                        echo '<div class="hg_container"><b>'._('manual adjustments are not possible').'</b><br>'._('during fully automatic aging.').'</div>';
                                         }
                                ?>
                                <h2 class="art-postheader"><?php echo _('general configuration'); ?></h2>
                                <?php 
                                    include ('config.php'); 
                                ?>
                                
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
