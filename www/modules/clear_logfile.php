<?php
    $timestamp = date('d.m.Y / H:i:s');
    $logfile = "logfile.txt";
    if (isset ($_POST['clear_logfile'])) {
        if (is_file($logfile)) {
            $fp = fopen($logfile, "w")
                or die ("Konnte Datei $logfile nicht anlegen");
            $input = "Datei neu angelegt am ".$timestamp." Uhr\n\n";
            fwrite($fp,$input);
            fclose ($fp);
        }
        # 3Sekunden Anzeige dass die Werte nicht gespeichert wurden
        else {
            print "<p id=\"info-message\" style=\"color: #ff0000; font-size: 20px;\"><b>Konnte ".$logfile." nicht finden!</b></p>
            <script language=\"javascript\">
            setTimeout(function(){document.getElementById('info-message').style.display='none'}, 3000)
            </script>";
        }
    }
?>