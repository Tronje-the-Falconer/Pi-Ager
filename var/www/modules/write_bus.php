<?php 
// include 'database.php';
    #Bus Werte in Datenbank schreiben
    if(isset($_POST['message']) && ($_POST['message'] != $_SESSION['message'])) {
        if(!empty($_POST['change_sensorbus_submit']))
        {                       // ist das $_POST-Array gesetzt
            logger('DEBUG', 'button save change_sensorbus pressed');
            $bus_value = $_POST['bustype_admin'];
            if ($bus_value == 1){
                write_busvalue(1);
                logger('DEBUG', 'sensorbus saved. changed to 1wire (1)');
                shell_exec('sudo /var/sudowebscript.sh sensorbus1wire');
            }
            else if ($bus_value == 0){
                write_busvalue(0);
                logger('DEBUG', 'sensorbus saved. changed to i2c (0)');
                shell_exec('sudo /var/sudowebscript.sh sensorbusi2c');
            }
            $_SESSION['message'] = $_POST['message'];
        }
    #3 Sekunden anzeigen, dass System heruntergefahren wird
        print '<p id=\'info-message\' style=\'color: #ff0000; font-size: 20px;\'><b>'. (_("system shutdown in 10 seconds")) .'</b><br>' . $date . ' </p>
            <script language="javascript">
                setTimeout(function(){document.getElementById("info-message").style.display="none"}, 3000)
                setTimeout(function(){window.location.href= "/index.php";},3000); 
            </script>';
    }
?>