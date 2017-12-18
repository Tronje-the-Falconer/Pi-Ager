<?php
    include 'names.php';                              // Variablen mit Strings
    include 'database.php';                           // Schnittstelle zur Datenbank
    include 'logging.php';                            //liest die Datei fuer das logging ein
    
    if(isset ($_POST['scale_wizzard2'])) {
        $scale_number = $_POST['scale_number'];
        $known_weight = $_POST['scale_wizzard_weight'];
        $current_scale_status = $_POST['current_scale_status'];
        $logstring = _('scale_wizzard'). ' ' . _('attached weight'). ': ' . $known_weight;
        logger('INFO', $logstring);
        
        if ($scale_number == 1){
            $scale_status = $status_scale1_key;
            $scale_calibrate = $calibrate_scale1_key;
        }
        else{
            $scale_status = $status_scale2_key;
            $scale_calibrate = $calibrate_scale2_key;
        }
        
        // Gewicht wird in DB geschrieben
        // Button schreibt Wert 3 in Kalibrierung
        write_startstop_status_in_database($calibrate_weight_key, $known_weight);
        write_startstop_status_in_database($scale_calibrate, 3);
        $scale_calibrate_status = 3;
        while ($scale_calibrate_status != 4) {
            $scale_calibrate_status = get_calibrate_status($scale_calibrate);
            sleep(1);
            //write_startstop_status_in_database($scale_calibrate, 4);
            // Python misst nun den zweiten Wert mit Refunit = 1
        }
        if ($scale_calibrate_status == 4){
            write_startstop_status_in_database($calibrate_weight_key, 0);
            write_startstop_status_in_database($scale_calibrate, 0);
            write_startstop_status_in_database($current_scale_status);
            $logstring = _('calibration done');
            logger('INFO', $logstring);
            print '<script language="javascript"> alert("'. (_("scale wizzard")) . " : " . (_("calibration done")) .'"); window.location.href = "../settings.php";</script>';
        }
        else{
            $logstring = 'error on calibrating';
            logger('DEBUG', $logstring);
            print '<script language="javascript"> alert("'. (_("scale wizzard")) . " : " . (_("error on calibrating")) .'"); window.location.href = "../settings.php";</script>';
        }
    }

    
?>
