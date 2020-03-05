<?php 
// include 'database.php';
    #Bus Werte in Datenbank schreiben
    if(!empty($_POST['change_sensorbus_submit']))
    {                       // ist das $_POST-Array gesetzt
        logger('DEBUG', 'button save change_sensorbus pressed');
        unset($_POST['change_sensorbus_submit']);
        
        $sensornum = $_POST['sensortype_admin'];
        write_sensorvalue($sensornum);
        logger('DEBUG', 'sensortype saved');
        #        $bus_value = $_POST['bustype_admin'];
        if ($sensornum == 1 || $sensornum == 2 || $sensornum == 3){
            write_busvalue(1);
            logger('DEBUG', 'sensorbus saved. changed to 1wire (1)');
            shell_exec('sudo /var/sudowebscript.sh sensorbus1wire > /dev/null 2>&1 &');
        }
        else {
            write_busvalue(0);
            logger('DEBUG', 'sensorbus saved. changed to i2c (0)');
            shell_exec('sudo /var/sudowebscript.sh sensorbusi2c > /dev/null 2>&1 &');
        }
        print '<p id=\'info-message\' style=\'color: #ff0000; font-size: 20px;\'><b>'. (_("system shutdown in 10 seconds")) .'</b><br>' . date("m/d/y h:i:s a") . ' </p>';        
        die();
    #10 Sekunden anzeigen, dass System heruntergefahren wird
#        print '<p id=\'info-message\' style=\'color: #ff0000; font-size: 20px;\'><b>'. (_("system shutdown in 10 seconds")) .'</b><br>' . date("m/d/y h:i:s a") . ' </p>
#            <script language="javascript">
#                setTimeout(function(){document.getElementById("info-message").style.display="none"}, 10000); 
#            </script>';
    }
?>
