<?php
// include 'database.php';
    #Bus Werte in Datenbank schreiben
    if(!empty($_POST['change_sensorbus_submit']))
    {                       // ist das $_POST-Array gesetzt
        logger('DEBUG', 'button save change_sensorbus pressed');
        unset($_POST['change_sensorbus_submit']);
        
        $bus = $_POST['bus'];
        $sensornum = $_POST['sensortype_admin'];
        $sensorsecondnum = $_POST['sensorsecondtype_admin'];
        write_sensorvalue($sensornum);
        write_sensorsecondvalue($sensorsecondnum);
        logger('DEBUG', 'sensortype saved');
        #        $bus_value = $_POST['bustype_admin'];
        if (($sensornum == 1 || $sensornum == 2 || $sensornum == 3) && $bus  ==  0){
            write_busvalue(1);
            logger('DEBUG', 'sensorbus saved. changed to 1wire (1)');
            shell_exec('sudo /var/sudowebscript.sh sensorbus1wire > /dev/null 2>&1 &');
            header("Location: /shutdown.php");
            die();
        }
        else if (($sensornum == 4 || $sensornum == 5) && $bus  ==  1){
            write_busvalue(0);
            logger('DEBUG', 'sensorbus saved. changed to i2c (0)');
            shell_exec('sudo /var/sudowebscript.sh sensorbusi2c > /dev/null 2>&1 &');
            header("Location: /shutdown.php");
            die();
        }
#        print '<p id=\'info-message\' style=\'color: #ff0000; font-size: 20px;\'><b>'. (_("system shutdown in 10 seconds")) .'</b><br>' . date("m/d/y h:i:s a") . ' </p>';

    # 10 Sekunden anzeigen, dass System heruntergefahren wird
#        print '<p id=\'info-message\' style=\'color: #ff0000; font-size: 20px;\'><b>'. (_("system shutdown in 10 seconds")) .'</b><br>' . date("m/d/y h:i:s a") . ' </p>
#            <script language="javascript">
#                setTimeout(function(){document.getElementById("info-message").style.display="none"}, 10000); 
#            </script>';
    }
?>
