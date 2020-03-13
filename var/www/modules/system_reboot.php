<?php 
    #System neustarten
    if (isset ($_POST['reboot'])){
        logger('DEBUG', 'button reboot pressed');
        unset($_POST['reboot']);
        $date = date('d.m.Y H:i:s');
        shell_exec('sudo /var/sudowebscript.sh reboot > /dev/null 2>&1 &');
        header("Location: /reboot.php");
#        print '<p id=\'info-message\' style=\'color: #ff0000; font-size: 20px;\'><b>'. (_("system reboot in 10 seconds")) .'</b><br>' . date("m/d/y h:i:s a") . ' </p>';      
        die();
#3 Sekunden anzeigen, dass System neugestartet wird
#        print '<p id=\'info-message\' style=\'color: #ff0000; font-size: 20px;\'><b>'. (_("system shutdown in 10 seconds")) .'</b><br>' . $date . ' </p>
#            <script language="javascript">
#                setTimeout(function(){document.getElementById("info-message").style.display="none"}, 10000)
#            </script>';
    }
?>
