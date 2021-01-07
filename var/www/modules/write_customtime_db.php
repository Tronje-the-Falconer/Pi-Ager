<?php
    if (isset ($_POST['change_customtime'])){
        logger('DEBUG', 'button change customtime pressed');
        unset($_POST['change_customtime']);
        $years =  $_POST['years'];
        $months =$_POST['months'];
        $days = $_POST['days'];
        $hours = $_POST['hours'];
        $minutes =$_POST['minutes'];
        
        $time_in_seconds = $years * 31557600;
        $time_in_seconds = $time_in_seconds + ($months * 2628000);
        $time_in_seconds = $time_in_seconds + ($days * 86400);
        $time_in_seconds = $time_in_seconds + ($hours * 3600);
        $time_in_seconds = $time_in_seconds + ($minutes * 60);
        
        write_customtime($time_in_seconds);
    }
?>