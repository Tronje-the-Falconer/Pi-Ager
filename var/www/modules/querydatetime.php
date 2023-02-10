<?php
    $current_values = array();
    $current_values['server_date_time'] = date('Y-m-d H:i:s');    

    echo json_encode($current_values);
?>