<?php 
    #Statistiktabellen leeren
    if (isset ($_POST['empty_statistic_tables'])){
        logger('DEBUG', 'Button empty_statistic_tables pressed');
        $date = date('d.m.Y H:i:s');
        delete_statistic_tables();
        logger('DEBUG', 'statistic tables emptied');
        print '<script language="javascript"> alert("'. (_("statistic tables emptied")) . '"); </script>';
    }
?>