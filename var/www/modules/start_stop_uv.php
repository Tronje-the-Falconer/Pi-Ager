<?php 
    if (isset($_POST['turn_on_uv'])){
        write_start_in_database($status_uv_manual_key);
        $logstring = _('uv switched on manually');
        logger('INFO', $logstring);
        $status_uv_manual = get_table_value($current_values_table, $status_uv_manual_key);
    }
    if (isset($_POST['turn_off_uv'])){
        write_stop_in_database($status_uv_manual_key);
        $logstring = _('uv switched off manually');
        logger('INFO', $logstring);
        $status_uv_manual = get_table_value($current_values_table, $status_uv_manual_key);
     }
?>