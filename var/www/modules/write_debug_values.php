<?php 
// include 'database.php';
    #Debug Werte in Datenbank schreiben
    if (isset ($_POST['save_debug_values'])){
        logger('DEBUG', 'button save debugvalues pressed');
        $chosen_agingtable_days_in_seconds_debug = $_POST['agingtable_days_in_seconds_debug'];
        $chosen_measuring_interval_debug = 30;   #$_POST['measuring_interval_debug'];
        sleep(2);
        write_debug_values($chosen_measuring_interval_debug, $chosen_agingtable_days_in_seconds_debug);
        logger('DEBUG', 'debugvalues saved');
        print '<script language="javascript"> alert("'. (_("debug values")) . " : " . (_("values saved")) .'"); </script>';
    }
?>
