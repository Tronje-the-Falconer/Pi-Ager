<?php
    include 'modules/names.php';                              // Variablen mit Strings
    include 'modules/database.php';                           // Schnittstelle zur Datenbank
    include 'modules/logging.php';                            //liest die Datei fuer das logging ein
    
    if(isset ($_POST['scale_wizzard2'])) {
        $scale_number = $_POST['scale_number'];
        $known_weight = $_POST['scale_wizzard_weight'];
        $current_scale1_status = $_POST['current_scale1_status'];
        $current_scale2_status = $_POST['current_scale2_status'];
        $logstring = _('scale_wizzard'). ' ' . _('attached weight'). ': ' . $known_weight;
        logger('INFO', $logstring);
        
        if ($scale_number == 1){
            $scale_status_key = $status_scale1_key;
            $scale_calibrate_key = $calibrate_scale1_key;
        }
        else{
            $scale_status_key = $status_scale2_key;
            $scale_calibrate_key = $calibrate_scale2_key;
        }
        
        // Gewicht wird in DB geschrieben
        // Button schreibt Wert 3 in Kalibrierung
        write_startstop_status_in_database($calibrate_weight_key, $known_weight);
        write_startstop_status_in_database($scale_calibrate_key, 3);
        $scale_calibrate_status = 3;
        while ($done != 'done') {
            $scale_calibrate_status = get_calibrate_status($scale_calibrate_key);
            if ($scale_calibrate_status == 4){
                $done = 'done';
            }
            if ($scale_calibrate_status == 5){
                $done = 'done';
            }
            sleep(1);
            // Python misst nun den zweiten Wert mit Refunit = 1
        }
        if ($scale_calibrate_status == 4){
            write_stop_in_database($calibrate_weight_key);
            write_stop_in_database($scale_calibrate_key);
            
            // Seite aufbauen mit OK Button
            include 'header.php';                                     // Template-Kopf und Navigation
            echo '<h2 class="art-postheader">' . strtoupper(_('scale wizzard'). ' - ' . _('tara scale')) . '</h2>';
            echo '<div class="hg_container">';
            echo _('please relieve the load cell completely'). ' ' . _('and press ok'). '<br><br>';
            echo '<form action="/modules/tara_scale.php" method="post">';
            echo '<input type="hidden" name="scale_number" type="text" value="'. $scale_number . '">';
            echo '<input type="hidden" name="current_scale1_status" type="text" value="'. $current_scale1_status . '">';
            echo '<input type="hidden" name="current_scale2_status" type="text" value="'. $current_scale2_status . '">';
            echo '<button class="art-button" name="scale_wizzard3" value="scale_wizzard3"  onclick="return confirm("' ._('relieve the load cell completely'). '?");">'._('ok'). '</button>';
            echo '<button class="art-button" name="scale_wizzard_cancel"  formnovalidate formaction="settings.php" onclick="return confirm("' ._('cancel scale wizzard? referenceunit is set, tara will not be done!'). '?");">'._('cancel'). '</button>';
            echo '</form>';
            echo '</div>';
            echo '</div></div></div></div></div></div>';
            include 'footer.php';
        }
        elseif ($scale_calibrate_status == 5){
            write_startstop_status_in_database($calibrate_weight_key, 0);
            write_startstop_status_in_database($scale_calibrate_key, 0);
            write_startstop_status_in_database($status_scale1_key, $current_scale1_status);
            write_startstop_status_in_database($status_scale2_key, $current_scale2_status);
            $logstring = _('calibration failed') . '! ' . _('calculated reference unit is 0') . ' ' . _('referenceunit is set to old value') . '!' . _('please try again');
            logger('WARNING', $logstring);
            print '<script language="javascript"> alert("'. (_("scale wizzard")) . " : " . (_("calibration failed")) . "! \\n " . (_("calculated reference unit is 0")) . ". \\n " . (_("referenceunit is set to old value")) . "! \\n " . (_("please try again")) . '"); window.location.href = "../settings.php";</script>';
        }
        else{
            $logstring = 'error on calibrating';
            logger('DEBUG', $logstring);
            print '<script language="javascript"> alert("'. (_("scale wizzard")) . " : " . (_("error on calibrating")) .'"); window.location.href = "../settings.php";</script>';
        }
    }
?>
