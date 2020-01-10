<?php 
// include 'database.php';
    #Debug Werte in Datenbank schreiben
    if (isset ($_POST['change_sensorbus'])){
        logger('DEBUG', 'button save change_sensorbus pressed');
        if ($bus_name == 'i2c'){
            write_busvalue(1);
            logger('DEBUG', 'sensorbus saved. changed to 1wire (1)');
            
            shell_exec('sudo /var/sudowebscript.sh sensorbus');
            print '<script language="javascript"> alert("'. (_("sensorbus changed to i2c. please unplug and plug in the power supply for the restart after shutdown.")) . " : " . (_("shutdown")) .'"); </script>';
        }
        if ($bus_name == '1wire'){
            write_busvalue(0);
            logger('DEBUG', 'sensorbus saved. changed to i2c (0)');
            
            shell_exec('sudo /var/sudowebscript.sh sensorbus');
            print '<script language="javascript"> alert("'. (_("sensorbus changed to 1wire. please unplug and plug in the power supply for the restart after shutdown.")) . " : " . (_("shutdown")) .'"); </script>';
        }
    }
?>