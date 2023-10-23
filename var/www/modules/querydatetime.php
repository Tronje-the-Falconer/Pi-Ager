<?php
    $current_values = array();
    $current_values['server_date_time'] = exec('date +"%Y-%m-%d %H:%M"'); // date('Y-m-d H:i:s');  
    $local_ip = $_SERVER['REMOTE_ADDR'];
    if (strpos($local_ip, '10.0.0') !== false) {
        $current_values['server_ip'] = 'AP Mode';
    }
    else {
        $current_values['server_ip'] = 'Client Mode';
    }    
    echo json_encode($current_values);
?>