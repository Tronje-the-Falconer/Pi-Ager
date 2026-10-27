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
        
        if (!(strlen($wlancountry) == 2 and ctype_upper($wlancountry))) {
            echo '<script> alert("'. (_("WLAN setup")) . " : " . (_("WLAN country code must be uppercase with a length of 2 characters")) .'"); </script>';
            goto end;
        }

        if ($selected_ssid != '' and strlen($wlanpassword) >= 8) {
            $htmlpwd = base64_encode($wlanpassword);
            $htmlssid = base64_encode($selected_ssid);
            $htmlcountry = base64_encode($wlancountry);
            $randnum = rand();
            header("Location: ../reboot_set_nm.php?pwd=" . $htmlpwd . "&ssid=" . $htmlssid . "&country=" . $htmlcountry . "&rand=" . $randnum );
        #    echo '<script> window.location.href = "../reboot_set_nm.php?pwd=' . $htmlpwd . '&ssid=' . $htmlssid . '&country='. $htmlcountry . '&rand=' . $randnum . '";</script>';
        }
        else {
            print '<script> alert("'. (_("WLAN setup")) . " : " . (_("WLAN SSID missing or length of password less than 8 characters")) .'"); </script>';
        }
        
        end:
        
    }

    # save accesspoint password
    if (isset ($_POST['set_new_password'])){
        unset($_POST['set_new_password']);
        $new_password = $_POST['new_password'];
        logger('DEBUG', 'button set_new_password pressed.');
        if ((strlen($new_password) < 8 )) {
            echo '<script> alert("'. (_("WLAN setup")) . ' : ' . (_("WLAN password must have at least 8 characters")) . '"); </script>';
        }
        else {
            $escaped_connection = escapeshellarg('PI_AGER_AP');
            $escaped_password   = escapeshellarg($new_password);

            $script = <<<BASH
                sudo nmcli connection modify $escaped_connection wifi-sec.psk $escaped_password &&
                sudo nmcli connection down $escaped_connection &&
                sudo nmcli connection up $escaped_connection ifname wlan1 &&
                sleep 3 &&
                ip -4 addr show wlan1 | awk '/inet / {print \$2}'
            BASH;
            
            $exec_data = [];
            $exec_status = 0;
            exec("bash -c " . escapeshellarg($script) . " 2>&1", $exec_data, $exec_status);

            if ($exec_status === 0) {
                $ip = addslashes(trim(end($exec_data))); // letzte Zeile = IP-Adresse
                echo '<script> alert("' . addslashes(_("Accesspoint IP")) . ' ' . $ip . '\\n' . addslashes(_("accepted a new password")) . '"); </script>';
            } else {
                $error = addslashes(implode('\\n', $exec_data));
                echo '<script> alert("' . addslashes(_("Error")) . ": " . $error . '"); </script>';
            }  

#           $htmlcmd = base64_encode($nmcli_set_password_cmd);
#           $randnum = rand();
#            # echo '<script> window.location.href = \'reboot_set_ap_password.php?htmlcmd=' . $htmlcmd . '&rand=' . $randnum . '\'' . ';</script>';
#           header("Location: ../reboot_set_ap_password.php?htmlcmd=" . $htmlcmd . "&rand=" . rand() );
        }
    }    
?>
