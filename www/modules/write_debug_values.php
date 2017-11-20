<?php 
// include 'database.php';
    #Debug Werte in Datenbank schreiben
    if (isset ($_POST['save_debug_values'])){
        $chosen_agingtable_days_in_seconds_debug = $_POST['agingtable_days_in_seconds_debug'];
        $chosen_measuring_interval_debug = $_POST['measuring_interval_debug'];
        write_debug_values($chosen_measuring_interval_debug, $chosen_agingtable_days_in_seconds_debug);
        print '<script language="javascript"> alert("'. (_("debug values")) . " : " . (_("values saved")) .'"); </script>';
    }
?>
