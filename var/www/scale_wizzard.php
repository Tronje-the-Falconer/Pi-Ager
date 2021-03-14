<?php
    include 'modules/names.php';                              // Variablen mit Strings
    include 'modules/database.php';                           // Schnittstelle zur Datenbank
    include 'modules/logging.php';                            //liest die Datei fuer das logging ein
    
    if(isset ($_POST['scale_wizzard'])) {
        $scale = $_POST['scale_wizzard_radiobutton'];
        if ($scale == $scale1_key){
            $scale_number = 1;
            $scale_status_key = $status_scale1_key;
            $scale_calibrate_key = $calibrate_scale1_key;   
            $current_scale1_status = get_table_value($current_values_table, $status_scale1_key);
            $current_scale2_status = get_table_value($current_values_table, $status_scale2_key);
        }
        else{
            $scale_number = 2;
            $scale_status_key = $status_scale2_key;
            $scale_calibrate_key = $calibrate_scale2_key;
            $current_scale1_status = get_table_value($current_values_table, $status_scale1_key);
            $current_scale2_status = get_table_value($current_values_table, $status_scale2_key);
        }
        write_stop_in_database($status_scale1_key);
        write_stop_in_database($status_scale2_key);
        $logstring = _('measuring on scales stopped due to calibrating scale') . $scale_number;
        logger('INFO', $logstring);
        write_start_in_database($scale_calibrate_key);
        $logstring = _('starting calibrate'). ' ' . _('scale'). ' ' . $scale_number;
        logger('INFO', $logstring);
        $scale_calibrate_status = 1;
        while ($scale_calibrate_status != 2) {
            $scale_calibrate_status = get_calibrate_status($scale_calibrate_key);
            sleep(1);
            // Python misst jetzt den Wert mit der Refunit = x (can be any value, but not 0)
        }
        if ($scale_calibrate_status == 2){
        // Seite aufbauen mit Button und eingabe von Gewicht in Gramm
            include 'header.php';                                     // Template-Kopf und Navigation
            echo '<h2 class="art-postheader">' . strtoupper(_('scale wizzard')) . '</h2>';
            echo '<div class="hg_container">';
            echo _('please attach a known weight to the loadcell'). ' ' . $scale_number . ' ' . _('now and enter the weight into the form below:'). '<br><br>';
            echo '<form action="calibrate_scale.php" method="post">';
            echo _('weight') . '<input type="number" name="scale_wizzard_weight" min="1" required> ' . _('gram') . '<br><br>';
            echo '<input type="hidden" name="scale_number" type="text" value="'. $scale_number . '">';
            echo '<input type="hidden" name="current_scale1_status" type="text" value="'. $current_scale1_status . '">';
            echo '<input type="hidden" name="current_scale2_status" type="text" value="'. $current_scale2_status . '">';
            # echo "<button class=\"art-button\" type=\"submit\" name=\"scale_wizzard2\" onclick=\"return confirm('"._('weight attatched').' ? '. "');\">"._('weight attatched')."</button>";
            echo '<button class="art-button" type="submit" name="scale_wizzard2" onclick="return confirm(\'' ._('weight attatched'). ' ? \');">'._('weight attatched').'</button>';
            echo '<button class="art-button" type="submit" name="scale_wizzard_cancel"  formnovalidate onclick="return confirm(\'' ._('cancel scale wizzard'). ' ? \');">'._('cancel').'</button>';
            # echo "<button class=\"art-button\" type=\"submit\" name=\"scale_wizzard_cancel\" formnovalidate onclick=\"return confirm('"._('cancel scale wizzard').' ? '. "');\">"._('cancel')."</button>";
            echo '</form>';
            echo '</div>';
            echo '</div></div></div></div></div></div>';
            include 'footer.php';
        }
    }
    else{
        write_startstop_status_in_database($calibrate_scale1_key, 0);
        write_startstop_status_in_database($calibrate_scale2_key, 0);
        print '<script language="javascript"> alert("'. (_("scale wizzard")) . " : " . (_("no scale selected")) .'"); window.location.href = "settings.php";</script>';
    }
?>