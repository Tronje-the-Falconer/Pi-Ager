<?php 
// include 'database.php';
    #loglevel in DB speichern
    if (isset ($_POST['save_loglevel'])){
        logger('DEBUG', 'button saveloglevel pressed');
        $chosen_loglevel_file = $_POST['loglevel_file'];
        $chosen_loglevel_console = $_POST['loglevel_console'];
        write_loglevel($chosen_loglevel_file, $chosen_loglevel_console);
        logger('DEBUG', 'loglevelvalues saved');
        print '<script language="javascript"> alert("'. (_("loglevel")) . " : " . (_("the selection is saved")) .'"); </script>';
    }
?>
