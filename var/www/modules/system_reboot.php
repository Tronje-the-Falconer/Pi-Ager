<?php 
    #System neustarten
    if (isset ($_POST['reboot'])){
        logger('DEBUG', 'button reboot pressed');
        unset($_POST['reboot']);
        $date = date('d.m.Y H:i:s');
        shell_exec('sudo /var/sudowebscript.sh reboot > /dev/null 2>&1 &');
        header("Location: /reboot.php");
        die();
    }

    if (isset ($_POST['setWLANconfig'])){
        logger('DEBUG', 'button WLAN save and reboot pressed');
        unset($_POST['setWLANconfig']);
        $selected_ssid = $_POST['ssid_selected'];
        if (!isset($selected_ssid)) {
            $selected_ssid = '';
        }
        $wlanpassword = $_POST['wlanpassword'];
        if (!isset($wlanpassword)) {
            $wlanpassword = '';
        }
        exec("sudo /var/updatessid.sh " . "'" . $selected_ssid . "' " . "'" . $wlanpassword . "'");
        sleep(2);
        header("Location: /reboot.php");
        die();
    }    
?>
