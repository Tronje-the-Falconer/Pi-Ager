<?PHP
    # Auslesen der Raspberry Revision und zuordnung zur Raspberry Pi Version nach Tabelle (http://elinux.org/RPi_HardwareHistory#Board_Revision_History)
    $pirevision = shell exec('sudo /var/sudowebscript.sh getpirevision')
    if ($pirevision == 'Beta'){
        $piversion = 'Raspberry Pi Raspberry Pi B (Beta)'
    }
    elif ($pirevision == '0002'){
        $piversion = 'Raspberry Pi B'
    }
    elif ($pirevision == '0003'){
        $piversion = 'Raspberry Pi B (ECN0001)'
    }
    elif ($pirevision == '0004'){
        $piversion = 'Raspberry Pi B'
    }
    elif ($pirevision == '0005'){
        $piversion = 'Raspberry Pi B'
    }
    elif ($pirevision == '0006'){
        $piversion = 'Raspberry Pi B'
    }
    elif ($pirevision == '0007'){
        $piversion = 'Raspberry Pi A'
    }
    elif ($pirevision == '0008'){
        $piversion = 'Raspberry Pi A'
    }
    elif ($pirevision == '0009'){
        $piversion = 'Raspberry Pi A'
    }
    elif ($pirevision == '000d'){
        $piversion = 'Raspberry Pi B'
    }
    elif ($pirevision == '000e'){
        $piversion = 'Raspberry Pi B'
    }
    elif ($pirevision == '000f'){
        $piversion = 'Raspberry Pi B'
    }
    elif ($pirevision == '0010'){
        $piversion = 'Raspberry Pi B+'
    }
    elif ($pirevision == '0011'){
        $piversion = 'Raspberry Pi Compute Module 1'
    }
    elif ($pirevision == '0012'){
        $piversion = 'Raspberry Pi A+'
    }
    elif ($pirevision == '0013'){
        $piversion = 'Raspberry Pi B+'
    }
    elif ($pirevision == '0014'){
        $piversion = 'Raspberry Pi Compute Module 1'
    }
    elif ($pirevision == '0015'){
        $piversion = 'Raspberry Pi A+'
    }
    elif ($pirevision == 'a01040'){
        $piversion = 'Raspberry Pi 2 Model B'
    }
    elif ($pirevision == 'a01041'){
        $piversion = 'Raspberry Pi 2 Model B'
    }
    elif ($pirevision == 'a21041'){
        $piversion = 'Raspberry Pi 2 Model B'
    }
    elif ($pirevision == 'a22042'){
        $piversion = 'Raspberry Pi 2 Model B (with BCM2837)'
    }
    elif ($pirevision == '900021'){
        $piversion = 'Raspberry Pi A+'
    }
    elif ($pirevision == '900092'){
        $piversion = 'Raspberry Pi Zero'
    }
    elif ($pirevision == '900093'){
        $piversion = 'Raspberry Pi Zero'
    }
    elif ($pirevision == '920093'){
        $piversion = 'Raspberry Pi Zero'
    }
    elif ($pirevision == 'a02082'){
        $piversion = 'Raspberry Pi 3 Model B'
    }
    elif ($pirevision == 'a020a0'){
        $piversion = 'Raspberry Pi Compute Module 3 (and CM3 Lite)'
    }
    elif ($pirevision == 'a22082'){
        $piversion = 'Raspberry Pi 3 Model B'
    }
    elif ($pirevision == 'a32082'){
        $piversion = 'Raspberry Pi 3 Model B'
    }
?>
