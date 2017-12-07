<?php 
    $timestamp = date('d.m.Y / H:i:s');
    $logfile = 'logs/logfile.txt';
    if (isset ($_POST['clear_logfile'])) {
        $newfile = fopen($logfile, 'w');
        fclose($newfile);
        if (is_file($logfile)) {
            $logstring = _("new logfile created");
            logger('INFO', $logstring);
        }
        # 3Sekunden Anzeige dass die Werte nicht gespeichert wurden
        else {
            echo '<p id="info-message" style="color: #ff0000; font-size: 20px;"><b>';
            echo sprintf(_('could not find %s!'), $logfile);
            echo'</b></p>
                <script language="javascript">
                    setTimeout(function(){document.getElementById("info-message").style.display="none"}, 3000)
                </script>';
        }
    }
?>