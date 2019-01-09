<?php 
    # Auslesen der Raspberry Revision und zuordnung zur Raspberry Pi Version nach Tabellen
    # http://elinux.org/RPi_HardwareHistory#Board_Revision_History and 
    # https://www.raspberrypi-spy.co.uk/2012/09/checking-your-raspberry-pi-board-version/
    # https://www.raspberrypi.org/documentation/hardware/raspberrypi/revision-codes/README.md
    # Stand 21.06.2017
    global $piversion, $piager_version;
    $pirevision = get_table_value($system_table, $pi_revision_key);
    $piager_version = get_table_value($system_table, $pi_ager_version_key);
    
    
    switch ($pirevision){
        ### old revision codes  
        case 'Beta':
            return $piversion = 'Raspberry Pi Raspberry Pi B (Beta)';
        case '0002':
            return $piversion = 'Raspberry Pi B Rev 1.0 </br> (Egoman, China) </br> 256MB RAM';
        case '0003':
            return $piversion = 'Raspberry Pi B Rev 1.0 </br> ECN0001 (no fuses, D14 removed) </br> (Egoman, China) </br> 256MB RAM';
        case '0004':
            return $piversion = 'Raspberry Pi B Rev 2.0 </br> (Sony, UK) </br> 256MB RAM';
        case '0005':
            return $piversion = 'Raspberry Pi B Rev 2.0 </br> (Qisda, Taiwan) </br> 256MB RAM';
        case '0006':
            return $piversion = 'Raspberry Pi B Rev 2.0 </br> (Egoman, China) </br> 256MB RAM';
        case '0007':
            return $piversion = 'Raspberry Pi A Rev 2.0 </br> (Egoman, China) </br> 256MB RAM';
        case '0008':
            return $piversion = 'Raspberry Pi A Rev 2.0 </br> (Sony, UK) </br> 256MB RAM';
        case '0009':
            return $piversion = 'Raspberry Pi A Rev 2.0 </br> (Qisda, Taiwan) </br> 256MB RAM';
        case '000d':
            return $piversion = 'Raspberry Pi B Rev 2.0 </br> (Egoman, China) </br> 512MB RAM';
        case '000e':
            return $piversion = 'Raspberry Pi B Rev 2.0 </br> (Sony, UK) </br> 512MB RAM';
        case '000f':
            return $piversion = 'Raspberry Pi B Rev 2.0 </br> (Egoman, China) </br> 512MB RAM';
         case '0010':
            return $piversion = 'Raspberry Pi B+ Rev 1.0 </br> (Sony, UK)</br> 512MB RAM';
        case '0011':
            return $piversion = 'Raspberry Pi Compute Module 1 Rev 1.0</br> (Sony, UK) </br> 512MB RAM';
        case '0012':
            return $piversion = 'Raspberry Pi A+ Rev 1.1 </br> (Sony, UK) </br> 256MB RAM';
        case '0013':
            return $piversion = 'Raspberry Pi B+ Rev 1.2 </br> (Embest, China) v.1.2</br> 512MB RAM';
       case '0014':
            return $piversion = 'Raspberry Pi Compute Module 1 Rev 1.0</br> (Embest, China) </br> 512MB RAM';
        case '0015':
            return $piversion = 'Raspberry Pi A+ Rev 1.1</br> (Embest, China) </br> 256MB RAM';
        ### new revision codes    
        case '900021':
            return $piversion = 'Raspberry Pi A+ v.1.1 </br>(Embest, China) </br> 512MB RAM';
        case '900032':
            return $piversion = 'Raspberry Pi B+ v.1.2 </br> (Sony, UK)</br> 512MB RAM';
        case '900092':
            return $piversion = 'Raspberry Pi Zero v.1.2 </br> (Sony, UK) </br> 512MB RAM';
        case '900093':
            return $piversion = 'Raspberry Pi Zero v.1.3 </br> (Sony, UK) </br> 512MB RAM';
        case '9000c1':
            return $piversion = 'Raspberry Pi Zero W v.1.1 </br> (Sony, UK) </br> 512MB RAM';
        case '920093':
            return $piversion = 'Raspberry Pi Zero v.1.3 </br> (Embest, China) </br> 512MB RAM';
        case 'a01040':
            return $piversion = 'Raspberry Pi 2 Model B v.1.0 </br>(Sony, UK)<br> 1GB RAM';
        case 'a01041':
            return $piversion = 'Raspberry Pi 2 Model B v.1.1 </br>(Sony, UK)</br> 1GB RAM';
        case 'a02082':
            return $piversion = 'Raspberry Pi 3 Model B v.1.2</br> (Sony, UK) </br> 1GB RAM';
        case 'a020a0':
            return $piversion = 'Raspberry Pi Compute Module 3 (and CM3 Lite) v.1.0 </br> (Sony, UK) </br> 1GB RAM';
        case 'a21041':
            return $piversion = 'Raspberry Pi 2 Model B v1.1 </br>(Embest, China)</br> 1GB RAM';
        case 'a22042':
            return $piversion = 'Raspberry Pi 2 Model B v1.2 (with BCM2837) </br> Embest, China) </br> 1GB RAM'; 
        case 'a22082':
            return $piversion = 'Raspberry Pi 3 Model B v.1.2 </br> Embest, China) </br> 1GB RAM';
        case 'a32082':
            return $piversion = 'Raspberry Pi 3 Model B v.1.2 </br> (Sony, Japan) </br> 1GB RAM';
        case 'a52082':
            return $piversion = 'Raspberry Pi 3 Model B v.1.2 </br> (Stadium) </br> 1GB RAM';
        case 'a020d3':
            return $piversion = 'Raspberry Pi 3 Model B+ v.1.3 </br> (Sony, UK) </br> 1GB RAM';
        case '0000':
            return $piversion = _('model unknown! Please contact us.');
        default:
            return $piversion = _('model unknown! Please contact us.');
    }
    logger('DEBUG', 'read_systemdetails performed');
?>
