<?php 

    include 'names.php';
    include 'database.php';
 
    if (isset($_POST['turn_on_light'])){
        write_start_in_database($status_light_manual_key);
        $f=fopen('logs/logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." "._('light turned on manualy'));
        fclose($f);
    }
    if (isset($_POST['turn_off_light'])){
        write_stop_in_database($status_light_manual_key);
        $f=fopen('logs/logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." "._('light turned off manualy'));
        fclose($f);
     }
?>