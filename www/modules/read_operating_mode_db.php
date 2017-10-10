<?php 
    $desired_maturity = read_agingtable_name_from_config();
    // Derzeitige Reife-Art, manuell oder nach Tabelle...
    $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
    if ($grepagingtable){
        $maturity_type = $desired_maturity;

    }
    else {
        $maturity_type = _('none');
    }
?>
