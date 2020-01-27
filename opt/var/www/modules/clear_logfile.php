<?php 
    $timestamp = date('d.m.Y / H:i:s');
    $logfile = 'logs/logfile.txt';
    if (isset ($_POST['clear_logfile'])) {
        $newfile = fopen($logfile, 'w');
        fclose($newfile);
        if (is_file($logfile)) {
            $logstring = _("new logfile manually created");
            logger('INFO', $logstring);
            print '<script language="javascript"> alert("'. (_("clear logfile")) . " : " . (_("new logfile created")) .'"); window.location.href = "../logs.php";</script>';
        }
        # 3Sekunden Anzeige dass die Werte nicht gespeichert wurden
        else {
            logger('DEBUG', 'could not find logfile');
            echo '<p id="info-message" style="color: #ff0000; font-size: 20px;"><b>';
            echo sprintf(_('could not find %s!'), $logfile);
            echo'</b></p>
                <script language="javascript">
                    setTimeout(function(){document.getElementById("info-message").style.display="none"}, 3000)
                </script>';
        }
    }
?>