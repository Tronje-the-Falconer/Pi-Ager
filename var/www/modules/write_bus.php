<?php
// include 'database.php';
    #Bus Werte in Datenbank schreiben
    if(!empty($_POST['change_sensorbus_submit']))
    {                       // ist das $_POST-Array gesetzt
        logger('DEBUG', 'button save change_sensorbus pressed');
        unset($_POST['change_sensorbus_submit']);
        
        $old_sensorsecondtype = get_table_value($config_settings_table, $sensorsecondtype_key);
        $old_sensortype = get_table_value($config_settings_table, $sensortype_key);
        $mi_mac_last3bytes = get_table_value_from_field($atc_mi_thermometer_mac_table, NULL, $mi_mac_last3bytes_key);
        $must_restart = false;
        
        $bus = $_POST['bus'];
        $sensornum = $_POST['sensortype_admin'];
        $sensorsecondnum = $_POST['sensorsecondtype_admin'];
        
        if (isset($_POST['mac_last_3_bytes']) && ($sensorsecondnum == 6)) {
            # echo 'in mac address set <br>';
            $mac_last_3_bytes = $_POST['mac_last_3_bytes'];
            $mac_pattern = "/^[0-9a-f]{2}:[0-9a-f]{2}:[0-9a-f]{2}$/i";
            $match = preg_match($mac_pattern, $mac_last_3_bytes);
            if ($match == 0) {
                echo '<script language="javascript"> alert("' . _('Format error in MAC address last 3 bytes. Format must be e.g.: 2a:53:f9') . '"); </script>';
                goto end_write_bus;
            }
            # echo 'mac_last_3_bytes: ' . $mac_last_3_bytes . '<br>';
            # echo 'mi_mac_last3bytes: ' . $mi_mac_last3bytes . '<br>';
            write_table_value($atc_mi_thermometer_mac_table, $id_field, '1', $mi_mac_last3bytes_key, $mac_last_3_bytes);
            # write address into /home/pi/MiTemperature2/my_thermometer.txt format [ab:cd:ef:gh:ij:kl], first 3 bytes are fixed a4:c1:38 for this type of bluetooth thermometer
            # $macaddr = '[a4:c1:38:' . $mac_last_3_bytes . ']';
            shell_exec('echo "[a4:c1:38:' . $mac_last_3_bytes . ']" ' . '>/opt/MiTemperature2/my_thermometer.txt');
            if ($mac_last_3_bytes !== $mi_mac_last3bytes) {
                $must_restart = true;
            }
        }
        
        # check if combination of sensor selections is allowed
        if (($sensornum == 1 || $sensornum == 2 || $sensornum == 3) && ($sensorsecondnum == 4 || $sensorsecondnum == 5)) {
            echo '<script language="javascript"> alert("' . _('Can not combine selected internal and external sensors.\nExternal sensors SHT85 or SHT3x can only combined with internal sensors SHT85 or SNT3x,\nwhen I2C addresses are different.\nSee also help!') . '"); </script>';
        }
        else {
            write_sensorvalue($sensornum);
            write_sensorsecondvalue($sensorsecondnum);
        
            logger('DEBUG', 'sensortype saved');
            if (($sensornum == 1 || $sensornum == 2 || $sensornum == 3) && $bus  ==  0){
                echo '<script language="javascript"> alert("' . _('Change to 1-wire') . '"); </script>';
                write_busvalue(1);
                logger('DEBUG', 'sensorbus saved. changed to 1wire (1)');
                shell_exec('sudo /var/sudowebscript.sh sensorbus1wire > /dev/null 2>&1 &');
                header("Location: /shutdown.php");
                die();
            }
            else if (($sensornum == 4 || $sensornum == 5) && $bus  ==  1){
                echo '<script language="javascript"> alert("' . _('Change to I2C') . '"); </script>';
                write_busvalue(0);
                logger('DEBUG', 'sensorbus saved. changed to i2c (0)');
                shell_exec('sudo /var/sudowebscript.sh sensorbusi2c > /dev/null 2>&1 &');
                header("Location: /shutdown.php");
                die();
            }
            else if (($old_sensorsecondtype != $sensorsecondnum) || ($old_sensortype != $sensornum)) {
                echo '<script language="javascript"> alert("' . _('Sensor type changed') . '"); </script>';
                shell_exec('sudo /var/sudowebscript.sh shutdown > /dev/null 2>&1 &');
                header("Location: /shutdown.php");
                die(); 
            }
            else if ($must_restart == true) {
                echo '<script language="javascript"> alert("' . _('MiThermometer MAC address changed.\nSystem reboot needed!') . '"); </script>';
                shell_exec('sudo /var/sudowebscript.sh reboot > /dev/null 2>&1 &');
                header("Location: /reboot.php");
                die();
            }
            else {
                logger('DEBUG', 'no sensor changed, sensorbus is already correct');
            }
            echo '<script language="javascript"> alert("' . _('Nothing changed') . '"); </script>';
        }
        end_write_bus:
    }
?>
