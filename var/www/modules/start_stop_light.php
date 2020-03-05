<?php 
    if (isset($_POST['turn_on_light'])){
        write_start_in_database($status_light_manual_key);
        $logstring = _('light switched on manually');
        logger('INFO', $logstring);
    }
    if (isset($_POST['turn_off_light'])){
        write_stop_in_database($status_light_manual_key);
        $logstring = _('light switched off manually');
        logger('INFO', $logstring);
     }
?>