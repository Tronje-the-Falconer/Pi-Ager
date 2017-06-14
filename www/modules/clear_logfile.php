<?php 
    $timestamp = date('d.m.Y / H:i:s');
    $logfile = '/logs/logfile.txt';
    if (isset ($_POST['clear_logfile'])) {
        if (is_file($logfile)) {
            $fp = fopen($logfile, 'w')
                or die (sprintf(_('could not create %s'), $logfile));
            $input = sprintf (_('New file created on %s oclock'), $timestamp); echo ' \n\n';
            fwrite($fp,$input);
            fclose ($fp);
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