<?php
    #Prüfen ob main läuft
    $exec_retval = exec('pgrep -a python3 | grep main.py');
    $status_main = 0;
    if ($exec_retval != '') {
        $status_main = 1;
    }
    
    $status_agingtable = intval(get_table_value($current_values_table, $status_agingtable_key));
    $status_piager = intval(get_table_value($current_values_table,$status_piager_key));
    
    $exec_retval = shell_exec('ps ax | grep -v grep | grep pi-ager_backup.sh');
    $status_backup = 0;
    if ($exec_retval != '') {
        $status_backup = 1;
    }
    
    # check if scale threads are alive
    $scale1_thread_alive = intval(get_table_value($current_values_table, $scale1_thread_alive_key));
    $scale2_thread_alive = intval(get_table_value($current_values_table, $scale2_thread_alive_key));
    $aging_thread_alive = intval(get_table_value($current_values_table, $aging_thread_alive_key));
    
    # check if scales already calibrated
    $scale1_refunit = intval(get_table_value($settings_scale1_table, $referenceunit_key));
    $scale2_refunit = intval(get_table_value($settings_scale2_table, $referenceunit_key));
    $status_scale1 = intval(get_table_value($current_values_table, $status_scale1_key));
    $status_scale2 = intval(get_table_value($current_values_table, $status_scale2_key));
    
    $desired_maturity = read_agingtable_name_from_config();

    logger('DEBUG', 'read_all_settings_db performed');
?>
