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
        $exec_data = [];
        $exec_status = null;
        logger('DEBUG', 'button WLAN save pressed');
        unset($_POST['setWLANconfig']);
        $selected_ssid = $_POST['ssid_selected'];
        if (!isset($selected_ssid)) {
            $selected_ssid = '';
        }
        $wlanpassword = $_POST['wlanpassword'];
        if (!isset($wlanpassword)) {
            $wlanpassword = '';
        }
        $wlancountry = $_POST['wlancountry'];
        if (!isset($wlancountry)) {
            $wlancountry = '';
        }
        if ($wlancountry != '') {
            exec("sudo raspi-config nonint do_wifi_country " . $wlancountry, $exec_data, $exec_status);
            # echo 'return status from do_wifi_country : ' . $exec_status . '<br>';
        }
        
        if ($selected_ssid != '' and $wlanpassword != '') {
            # exec("sudo raspi-config nonint do_wifi_ssid_passphrase " . "'" . $selected_ssid . "' " . "'" . $wlanpassword . "'", $exec_data, $exec_status );
            exec("sudo nmcli device wifi connect " . "'" . $selected_ssid . "' password " . "'" . $wlanpassword . "'" . ' ifname wlan0', $exec_data, $exec_status );
            # echo 'SSID = ' . $selected_ssid . ' password = ' . $wlanpassword . '<br>';
            # echo 'return status from nmcli : ' . $exec_status . '<br>';
            print '<script> alert("'. (_("WLAN setup")) . " : " . (_("WLAN configured with new SSID, password and country code")) .'"); </script>';
            // exec("sudo /var/updatessid.sh " . "'" . $selected_ssid . "' " . "'" . $wlanpassword . "'");
            // sleep(2);
            // shell_exec('sudo /var/sudowebscript.sh reboot > /dev/null 2>&1 &');
            // header("Location: /reboot.php");
            // die();
        }
        else {
            print '<script> alert("'. (_("WLAN setup")) . " : " . (_("WLAN SSID or password missing")) .'"); </script>';
        }
    }    
?>
