<?php
    include 'modules/names.php';
    include 'modules/database.php';
    include 'modules/logging.php';                            //liest die Datei fuer das logging ein
    
    // get the q parameter from URL
    $request = $_REQUEST["q"];        //$request = $_GET['q'];
    
    $tft_display_type = get_table_value($config_settings_table, $tft_display_type_key);
    if ($tft_display_type == 1) {
        $filename = '/var/www/nextion/NX3224K028/pi-ager.tft'; //Das Upload-Verzeichnis NX3224K028
    }
    else if ($tft_display_type == 2) {
        $filename = '/var/www/nextion/NX3224F028/pi-ager.tft'; //Das Upload-Verzeichnis NX3224F028
    }
    else {
        $filename = '/var/www/nextion/NX3224T028/pi-ager.tft'; //Das Upload-Verzeichnis NX3224T028
    }  
    
    if ($request == 'check') {
        // $filename = '/var/www/nextion/pi-ager.tft';
        if (!file_exists( $filename )) {
            echo 'Firmware file ' . $filename . ' missing.';
            exit;
        }
        
        $grep_firmware_upload = exec('pgrep -a python3 | grep piager_upload_firmware.py');
        if ($grep_firmware_upload == 0) {
            // echo 'Firmware upload not running. Stopping pi-ager service';
            shell_exec('sudo /var/sudowebscript.sh pkillmain');
            while (exec('pgrep -a python3 | grep main.py') != 0) {
                sleep( 1 );
            }
                    
            $logstring = 'ADMIN main.py ' . _('killed');
            logger('INFO', $logstring);
            
            update_nextion_table( 0, 'running' );
            // start firmware programming
            echo 'ready';
            //session_write_close();
            shell_exec('sudo /var/sudowebscript.sh startfirmwareprog ' . $filename);
        } 
        else {
            echo 'firmware allready programming';
        }
    }
    
    if ($request == 'program') {
        $dataset = get_nextion_dataset();
        //echo 'programming status: ' . $dataset['status'];
        echo json_encode($dataset);
    }

    if ($request == 'finalize') {
        sleep (3); # 3 Sec auf start der Py-Datei warten, bis Display ready ist
        shell_exec('sudo /var/sudowebscript.sh startmain');
        write_start_in_database($status_piager_key);
        echo 'ready';
    }
?>
