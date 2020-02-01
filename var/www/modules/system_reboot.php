<?php 
    #System neustarten
    if (isset ($_POST['reboot'])){
        logger('DEBUG', 'button reboot pressed');
        $date = date('d.m.Y H:i:s');
        shell_exec('sudo /var/sudowebscript.sh reboot');
        header("Location: /index.php");
        die();
#3 Sekunden anzeigen, dass System neugestartet wird
        print '<p id=\'info-message\' style=\'color: #ff0000; font-size: 20px;\'><b>'. (_("system shutdown in 10 seconds")) .'</b><br>' . $date . ' </p>
            <script language="javascript">
                setTimeout(function(){document.getElementById("info-message").style.display="none"}, 10000)
            </script>';
    }
?>
