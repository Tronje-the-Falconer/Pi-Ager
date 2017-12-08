<?php
    include 'names.php';
    include 'database.php';

    $current_values = get_current_values_for_monitoring();
    echo json_encode($current_values);
    logger('DEBUG', 'monitor_query performed');
?>