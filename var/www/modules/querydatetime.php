<?php
    $current_values = array();
    $current_values['server_date_time'] = exec('date +"%Y-%m-%d %H:%M"'); // date('Y-m-d H:i:s');  
    $server_ips = exec('hostname -I');
    $server_ips_list = explode(" ", $server_ips);
    $addresses_count = count($server_ips_list);
    if ($addresses_count == 0) {
        $current_values['server_ip'] = '';
    }
    elseif ($server_ips_list[0] === '10.0.0.1') {
        $current_values['server_ip'] = 'AP Mode';
    }
    else {
        $current_values['server_ip'] = 'Client Mode';
    }    
    echo json_encode($current_values);
?>