<?php 
// include 'database.php';
    #Namen der Reifetabelle in config/tables.json speichern
    if (isset ($_POST['save_agingtable'])){
        $chosen_agingtable = $_POST['agingtable'];
        write_agingtable($chosen_agingtable);
        logger('INFO', 'new agingtable saved in Database');
        print '<script language="javascript"> alert("'. (_("agingtable")) . " : " . (_("the selection is saved")) .'"); </script>';
    }
?>
