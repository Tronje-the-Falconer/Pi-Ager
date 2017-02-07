<?PHP
    # Auslesen der Raspberry Revision und zuordnung zur Raspberry Pi Version nach Tabelle (http://elinux.org/RPi_HardwareHistory#Board_Revision_History)
    global $piversion;
    $getpirevision = shell_exec('sudo /var/sudowebscript.sh getpirevision');
    $pirevision = trim($getpirevision);
    if ($pirevision == 'Beta'){
        $piversion = 'Raspberry Pi Raspberry Pi B (Beta)';
    }
    elseif ($pirevision=='0002'){
        $piversion = 'Raspberry Pi B';
    }
    elseif ($pirevision == '0003'){
        $piversion = 'Raspberry Pi B (ECN0001)';
    }
    elseif ($pirevision == '0004'){
        $piversion = 'Raspberry Pi B';
    }
    elseif ($pirevision == '0005'){
        $piversion = 'Raspberry Pi B';
    }
    elseif ($pirevision == '0006'){
        $piversion = 'Raspberry Pi B';
    }
    elseif ($pirevision == '0007'){
        $piversion = 'Raspberry Pi A';
    }
    elseif ($pirevision == '0008'){
        $piversion = 'Raspberry Pi A';
    }
    elseif ($pirevision == '0009'){
        $piversion = 'Raspberry Pi A';
    }
    elseif ($pirevision == '000d'){
        $piversion = 'Raspberry Pi B';
    }
    elseif ($pirevision == '000e'){
        $piversion = 'Raspberry Pi B';
    }
    elseif ($pirevision == '000f'){
        $piversion = 'Raspberry Pi B';
    }
    elseif ($pirevision == '0010'){
        $piversion = 'Raspberry Pi B+';
    }
    elseif ($pirevision == '0011'){
        $piversion = 'Raspberry Pi Compute Module 1';
    }
    elseif ($pirevision == '0012'){
        $piversion = 'Raspberry Pi A+';
    }
    elseif ($pirevision == '0013'){
        $piversion = 'Raspberry Pi B+';
    }
    elseif ($pirevision == '0014'){
        $piversion = 'Raspberry Pi Compute Module 1';
    }
    elseif ($pirevision == '0015'){
        $piversion = 'Raspberry Pi A+';
    }
    elseif ($pirevision == 'a01040'){
        $piversion = 'Raspberry Pi 2 Model B';
    }
    elseif ($pirevision == 'a01041'){
        $piversion = 'Raspberry Pi 2 Model B';
    }
    elseif ($pirevision == 'a21041'){
    #elseif (strcmp($pirevision, $test) == 0){
        $piversion = 'Raspberry Pi 2 Model B';
    }
    elseif ($pirevision == 'a22042'){
        $piversion = 'Raspberry Pi 2 Model B (with BCM2837)';
    }
    elseif ($pirevision == '900021'){
        $piversion = 'Raspberry Pi A+';
    }
    elseif ($pirevision == '900092'){
        $piversion = 'Raspberry Pi Zero';
    }
    elseif ($pirevision == '900093'){
        $piversion = 'Raspberry Pi Zero';
    }
    elseif ($pirevision == '920093'){
        $piversion = 'Raspberry Pi Zero';
    }
    elseif ($pirevision == 'a02082'){
        $piversion = 'Raspberry Pi 3 Model B';
    }
    elseif ($pirevision == 'a020a0'){
        $piversion = 'Raspberry Pi Compute Module 3 (and CM3 Lite)';
    }
    elseif ($pirevision == 'a22082'){
        $piversion = 'Raspberry Pi 3 Model B';
    }
    elseif ($pirevision == 'a32082'){
        $piversion = 'Raspberry Pi 3 Model B';
    }
    else{$piversion = 'Modell unbekannt!';
    }
?>
