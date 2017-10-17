<?php 
    #Statistiktabellen leeren
    if (isset ($_POST['empty_statistic_tables'])){
        $date = date('d.m.Y H:i:s');
        delete_statistic_tables();
        print '<script language="javascript"> alert("'. (_("statistic tables emptied")) . '"); </script>';
    }
?>