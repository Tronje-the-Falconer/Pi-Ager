<?php
    include 'names.php';                              // Variablen mit Strings
    include 'database.php';                           // Schnittstelle zur Datenbank
    include 'logging.php';                            //liest die Datei fuer das logging ein
    
    if(isset ($_POST['scale_wizzard3'])) {
        $scale_number = $_POST['scale_number'];
        $known_weight = $_POST['scale_wizzard_weight'];
        $current_scale1_status = $_POST['current_scale1_status'];
        $current_scale2_status = $_POST['current_scale2_status'];
        $logstring = _('scale_wizzard'). ' ' . _('attached weight'). ': ' . $known_weight;
        logger('INFO', $logstring);
        
        if ($scale_number == 1){
            $scale_status_key = $status_scale1_key;
            $scale_tara_key = $status_scale1_tara_key;
        }
        else{
            $scale_status_key = $status_scale2_key;
            $scale_tara_key = $status_scale2_tara_key;
        }
        // scale starten
        write_start_in_database($scale_status_key);
        
        // tara bei Scale machen (offset berechnen und in db schreiben)
        write_start_in_database($scale_tara_key);
        $logstring = 'performing tara due to calibrating on scale' . $scale_number;
        logger('DEBUG', $logstring);
        
        $done = NULL;
        $status_tara = 1;
        while ($done != 'done') {
            $status_tara = get_tara_status($scale_tara_key);
            if ($status_tara == 0){
                write_startstop_status_in_database($status_scale1_key, $current_scale1_status);
                write_startstop_status_in_database($status_scale2_key, $current_scale2_status);
                $logstring = _('calibration done');
                logger('INFO', $logstring);
                print '<script language="javascript"> alert("'. (_("scale wizzard")) . " : " . (_("calibration done")) .'"); window.location.href = "../settings.php";</script>';
                $done = 'done';
            }
            sleep(1);
            // Python macht tarafunktion in status = 1 über status 2 (Taramessung erfolgt und wert übergeben) bis wieder 0 gesetzt wird (tara zu ende)
        }
    }
?>