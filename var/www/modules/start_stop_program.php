<?php 

    include 'names.php';
// include 'database.php';

    #programme Rss.py und/oder Reifetab.py starten/stoppen
    if (isset($_POST['main_start'])){
        $grepmain = exec('pgrep -a python3 | grep main.py');
        if($grepmain == '') {
            shell_exec('sudo /var/sudowebscript.sh startmain');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $grepmain = exec('pgrep -a python3 | grep main.py');
            
            if($grepmain != '') {
                write_start_in_database($status_piager_key);
                $logstring = 'main.py '._('manually started');
                logger('INFO', $logstring);
                $logstring = 'Pi-Ager '._('started');
                logger('INFO', $logstring);
            }
            else{
                $logstring = 'main.py ' . _('could not be started');
                logger('INFO', $logstring);
            }
        }
        elseif($grepmain != ''){
            write_start_in_database($status_piager_key);
            $logstring = 'main.py ' . _('is running');
            logger('INFO', $logstring);
            $logstring = 'Pi-Ager '._('started');
            logger('INFO', $logstring);
        }
        else{
            $logstring = 'main.py '._('no idea what is happening');
            logger('INFO', $logstring);
        }
    }
    if (isset($_POST['pi-ager_agingtable_start'])){
        if (isset($_POST['agingtable_startperiod'])){
            $agingtable_startperiod = $_POST['agingtable_startperiod'];
            // $agingtable_startperiod = $agingtable_startperiod - 1;
        } else {
            $agingtable_startperiod = 1;
        }
        if (isset($_POST['agingtable_starthour'])){
            $agingtable_starthour = $_POST['agingtable_starthour'];
            // $agingtable_startday = $agingtable_startday;
        } else {
            $agingtable_starthour = 1;
        }
        $grepmain = exec('pgrep -a python3 | grep main.py');
        if($grepmain == '') {
            shell_exec('sudo /var/sudowebscript.sh startmain');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $grepmain = exec('pgrep -a python3 | grep main.py');
            if($grepmain != '') {                
                write_table_value($config_settings_table, $key_field, $agingtable_startperiod_key, $value_field, $agingtable_startperiod);
                write_table_value($config_settings_table, $key_field, $agingtable_starthour_key, $value_field, $agingtable_starthour);
                write_start_in_database($status_agingtable_key);
                sleep(2); //warten auf annahme der Startsequenz
                // prüfen ob main immer noch läuft und ob main im messloop
                // prüfen ob agingtable läuft
                $logstring = 'main.py '._('manually started');
                logger('INFO', $logstring);
                $logstring = 'Pi-Ager'._('started due to agingtable start');
                logger('INFO', $logstring);
                $logstring = _('agingtable started');
                logger('INFO', $logstring);
                # $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable'); # Reifetab hat sich geaändert also neu setzen
                $grepagingtable = intval(get_table_value($current_values_table, $status_agingtable_key));
            }
            else{
                $logstring = 'main.py '._('could not be started');
                logger('INFO', $logstring);
            }
        }
        elseif($grepmain != '') {
                write_table_value($config_settings_table, $key_field, $agingtable_startperiod_key, $value_field, $agingtable_startperiod);
                write_table_value($config_settings_table, $key_field, $agingtable_starthour_key, $value_field, $agingtable_starthour);
                write_start_in_database($status_agingtable_key);
                sleep( 2 );     // agingtable thread needs about 1s to detect start of agingtable
                # $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
                $grepagingtable = intval(get_table_value($current_values_table, $status_agingtable_key));
                //wenn agingtable läuft dann Log schreiben
                # $logstring = 'main.py '._('is already running');
                # logger('INFO', $logstring);
                # $logstring = 'Pi-Ager '._('is already running or started due to agingtable start');
                # logger('INFO', $logstring);
                if ($grepagingtable != 0) {
                    $logstring = _('agingtable started');
                    logger('INFO', $logstring);
                }
                else {   // wenn agingtable nicht läuft dann Fehler loggen
                    $logstring = _('agingtable could not be started');
                    logger('INFO', $logstring);
                }
        }
        else{
            $logstring = 'pi-ager_agingtable_start '._('no idea what is happening');
            logger('INFO', $logstring);
        }
    }
    if (isset($_POST['pi-ager_agingtable_stop'])){ //Pi Ager wird gestoppt während agingtable noch läuft
        # $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
        $grepagingtable = intval(get_table_value($current_values_table, $status_agingtable_key));
        write_stop_in_database($status_agingtable_key);
        if ($grepagingtable != 0){
            $logstring = _('agingtable stopped due to stopping') . " Pi-Ager";
            logger('INFO', $logstring);
        }
        write_stop_in_database($status_piager_key);
        # wait 5 seconds, needed by pi_ager_loop.py to detect stop request and turning off all gpio outputs
        sleep(5);
/*        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_cooling_compressor_value_to_1'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_heater_value_to_1'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_humidifier_value_to_1'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_circulating_air_value_to_1'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_exhausting_air_value_to_1'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_uv_value_to_1'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_light_value_to_1'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_dehumidifier_value_to_1'));
*/
        $logstring = 'Pi-Ager '._('stopped');
        logger('INFO', $logstring);
    }
    if (isset($_POST['agingtable_stop'])){
        write_stop_in_database($status_agingtable_key);
        $logstring = _('agingtable stopped');
        logger('INFO', $logstring);
    }
    # Scales
    if (isset($_POST['scale1_start']) OR isset($_POST['scale2_start']) OR isset($_POST['scale1_tara']) OR isset($_POST['scale2_tara'])){
        # $grepscale = shell_exec('sudo /var/sudowebscript.sh grepscale');
        $scale1_thread_alive = intval(get_table_value($current_values_table, $scale1_thread_alive_key));
        $scale2_thread_alive = intval(get_table_value($current_values_table, $scale2_thread_alive_key));
        if ($scale1_thread_alive == 1) {
            if (isset($_POST['scale1_start'])){
                write_start_in_database($status_scale1_key);
                $logstring = _('measuring scale'). ' 1 ' . _('started');
                logger('INFO', $logstring);
            }
            if (isset($_POST['scale1_tara'])){
                write_start_in_database($status_scale1_tara_key);
                $logstring = _('performing tara on scale') . ' 1';
                logger('INFO', $logstring);
            }        
        }
        else {
            $logstring = 'scale1 thread not running';
            logger('INFO', $logstring);
        }
        
        if ($scale2_thread_alive == 1) {
            if (isset($_POST['scale2_start'])){
                write_start_in_database($status_scale2_key);
                $logstring = _('measuring scale'). ' 2 ' . _('started');
                logger('INFO', $logstring);
            }
            if (isset($_POST['scale2_tara'])){
                write_start_in_database($status_scale2_tara_key);
                $logstring = _('performing tara on scale') . ' 2';
                logger('INFO', $logstring);
            }        
        }
        else {
            $logstring = 'scale2 thread not running';
            logger('INFO', $logstring);
        }        
    }
    
    if (isset($_POST['scale1_stop'])){
        write_stop_in_database($status_scale1_key);
        $logstring = _('measuring scale stopped'). ' ' . _('scale'). ' 1';
        logger('INFO', $logstring);
    }
    if (isset($_POST['scale2_stop'])){
        write_stop_in_database($status_scale2_key);
        $logstring = _('measuring scale stopped'). ' ' . _('scale'). ' 2';
        logger('INFO', $logstring);
    }
     
    if (isset($_POST['admin_start_main'])){
        $grepmain = exec('pgrep -a python3 | grep main.py');
        # echo "grepmain = " . $grepmain;
        if ($grepmain == '') {
            shell_exec('sudo /var/sudowebscript.sh startmain');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $grepmain = exec('pgrep -a python3 | grep main.py');
            if($grepmain != '') {
                $logstring = 'ADMIN main.py ' . _('manually started');
                logger('INFO', $logstring);
            }
            else{
                $logstring = 'ADMIN main.py ' . _('could not be started');
                logger('INFO', $logstring);
            }
        }
        else{
            $logstring = 'ADMIN main.py ' . _('already running');
            logger('INFO', $logstring);
        }
    }
    if (isset($_POST['admin_stop_main'])){
        shell_exec('sudo /var/sudowebscript.sh pkillmain');
        sleep (1); # 1 Sec auf start der Py-Datei warten
        $logstring = 'ADMIN main.py ' . _('killed');
        logger('INFO', $logstring);
    }
?>