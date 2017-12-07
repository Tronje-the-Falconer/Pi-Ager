<?php 

    include 'names.php';
    include 'database.php';
 
    if (isset($_POST['turn_on_light'])){
        write_start_in_database($status_light_manual_key);
        $logstring = _('light turned on manualy');
        logger('INFO', $logstring);
    }
    if (isset($_POST['turn_off_light'])){
        write_stop_in_database($status_light_manual_key);
        $logstring = _('light turned off manualy');
        logger('INFO', $logstring);
     }
?>