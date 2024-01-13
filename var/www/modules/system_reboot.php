<?php 
    #System neustarten
    if (isset ($_POST['reboot'])){
        logger('DEBUG', 'button reboot pressed');
        unset($_POST['reboot']);
        # shell_exec('sudo /var/sudowebscript.sh reboot > /dev/null 2>&1 &');
        # echo '<script> window.location.href = "reboot.php?rand=" + Math.random();</script>';
        header("Location: ../reboot.php?rand=" . rand() );
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
        
        if (strlen($wlancountry) == 2 and ctype_upper($wlancountry)) {
            exec("sudo raspi-config nonint do_wifi_country " . $wlancountry, $exec_data, $exec_status);
            # echo 'return status from do_wifi_country : ' . $exec_status . '<br>';
        }
        else {
            echo '<script> alert("'. (_("WLAN setup")) . " : " . (_("WLAN country code must be uppercase with a length of 2 characters")) .'"); </script>';
            goto end;
        }

        if ($selected_ssid != '' and strlen($wlanpassword) >= 8) {
            $cmd = "sudo nmcli device wifi connect " . "'" . $selected_ssid . "' password " . "'" . $wlanpassword . "' ifname wlan0";
            $htmlcmd = base64_encode($cmd);
            $randnum = rand();
            # echo '<script> window.location.href = \'reboot_set_nm.php?htmlcmd=' . $htmlcmd . '&rand=' . $randnum . '\'' . ';</script>';
            header("Location: ../reboot_set_nm.php?htmlcmd=" . $htmlcmd . "&rand=" . $randnum );
        }
        else {
            print '<script> alert("'. (_("WLAN setup")) . " : " . (_("WLAN SSID missing or length of password less than 8 characters")) .'"); </script>';
        }
        
        end:
        
    }

    # save accesspoint password and restart system
    if (isset ($_POST['set_new_password'])){
        unset($_POST['set_new_password']);
        $new_password = $_POST['new_password'];
        logger('DEBUG', 'button set_new_password pressed.');
        $nmcli_set_password_cmd = "sudo nmcli con modify PI_AGER_AP 802-11-wireless-security.psk " . "'" . $new_password . "'";
        $htmlcmd = base64_encode($nmcli_set_password_cmd);
        $randnum = rand();
        # echo '<script> window.location.href = \'reboot_set_ap_password.php?htmlcmd=' . $htmlcmd . '&rand=' . $randnum . '\'' . ';</script>';
        header("Location: ../reboot_set_ap_password.php?htmlcmd=" . $htmlcmd . "&rand=" . rand() );
    }    
?>
