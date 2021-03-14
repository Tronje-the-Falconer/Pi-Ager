<?php 
    $desired_maturity = read_agingtable_name_from_config();
    // Derzeitige Reife-Art, manuell oder nach Tabelle...
    // $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
    $grepagingtable = intval(get_table_value($current_values_table, $status_agingtable_key));
    if ($grepagingtable){
        $maturity_type = $desired_maturity;

    }
    else {
        $maturity_type = _('none');
    }
    logger('DEBUG', 'read_operating_mode_db performed');
?>
