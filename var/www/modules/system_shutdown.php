<?php 
    #System herunterfahren
    if (isset ($_POST['shutdown'])){
        logger('DEBUG', 'button shutdown pressed');
        unset($_POST['shutdown']);
        $date = date('d.m.Y H:i:s');
        shell_exec('sudo /var/sudowebscript.sh shutdown > /dev/null 2>&1 &');
        header("Location: /shutdown.php");
#        print '<p id=\'info-message\' style=\'color: #ff0000; font-size: 20px;\'><b>'. (_("system shutdown in 10 seconds")) .'</b><br>' . date("m/d/y h:i:s a") . ' </p>';        
        die();
#3 Sekunden anzeigen, dass System heruntergefahren wird
#        print '<p id="info-message" style="color: #ff0000; font-size: 20px;"><b>'.  (_("system shutdown in 10 seconds")) .'</b><br>' . $date . ' </p>
#            <script language="javascript">
#                setTimeout(function(){document.getElementById("info-message").style.display="none"}, 10000);
#            </script>';
    }
?>
