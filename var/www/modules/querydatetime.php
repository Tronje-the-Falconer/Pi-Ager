<?php
    $current_values = array();
    $current_values['server_date_time'] = exec('date +"%Y-%m-%d %T"'); // date('Y-m-d H:i:s');    
    $ap_client_mode = exec('hostname -I'); // server ip address, in AP mode : 10.0.0.5
    if ($ap_client_mode === '10.0.0.5') {
        $current_values['server_ip'] = 'AP Mode'; 
    }
    else {
        $current_values['server_ip'] = 'Client Mode'; 
    }
    echo json_encode($current_values);
?>