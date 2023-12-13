<?php
// include 'database.php';
    #Bus Werte in Datenbank schreiben
    
    function do_shutdown( $msg ) {
        echo '<script> alert("' . $msg . '\n' . _('Pi-Ager will be shutdown now. After the shutdown process is finished, you must put your Raspberry Pi device into power-off state and change your sensor wire connection!') . '"); window.location.href="shutdown.php"; </script>';
        //header("Location: /shutdown.php");
        //shell_exec('sudo /var/sudowebscript.sh shutdown > /dev/null 2>&1 &');
        //die();
    }
    
    function do_reboot( $msg ) {
        echo '<script> alert("' . $msg . '\n' . _('Pi-Ager will be rebooted now') . '"); window.location.href="reboot.php?rand=" + Math.random(); </script>';
        //header("Location: /reboot.php");
        //shell_exec('sudo /var/sudowebscript.sh reboot > /dev/null 2>&1 &');
        //die();
    }
        
    if (!empty($_POST['change_sensorbus_submit'])) {    // ist das $_POST-Array gesetzt
        logger('DEBUG', 'button save change_sensorbus pressed');
        unset($_POST['change_sensorbus_submit']);
        
        $old_sensorsecondtype = get_table_value($config_settings_table, $sensorsecondtype_key);
        $old_sensornum = get_table_value($config_settings_table, $sensortype_key);
        $old_ATC_device_name = get_table_value_from_field('atc_device_name', NULL, 'name');
        
        $atc_device_name_changed = false;
        
        $old_bus = $_POST['bus'];
        $sensornum = $_POST['sensortype_admin'];
        $sensorsecondnum = $_POST['sensorsecondtype_admin'];
        
        if (isset($_POST['atc_device_name'])) {
            $ATC_device_name = $_POST['atc_device_name'];
            write_table_value('atc_device_name', 'id', '1', 'name', $ATC_device_name);
            if ($old_ATC_device_name !== $ATC_device_name) {
                $atc_device_name_changed = true;
            }
        }
        
        # check if combination of sensor selections is allowed
        if (($sensornum == 1 || $sensornum == 2 || $sensornum == 3) && ($sensorsecondnum == 4 || $sensorsecondnum == 5 || $sensorsecondnum == 6)) {
            echo '<script> alert("' . _('Can not combine selected internal and external sensors!\nExternal sensors SHT85, SHT3x or AHT2x can only combined with internal sensors SHT85, SHT3x or AHT2x, when I2C addresses are different.\nSee also help!') . '"); </script>';
        }
        else if (($sensornum == 6) && ($sensorsecondnum == 6)) {
            echo '<script> alert("' . _('Can not combine selected internal and external sensors!\nExternal sensor AHT2x can not combined with internal sensors AHT2x. I2C addresses must be different.\nSee also help!') . '"); </script>';
        }
        else {
            # save new settings
            write_sensorvalue($sensornum);
            write_sensorsecondvalue($sensorsecondnum);
            logger('DEBUG', 'sensortype saved');
        
            # check if sensor changed
            if ($old_sensornum != $sensornum) { # primary sensor changed, check if 1-wire or i2c interface must change
                if (($sensornum == 1 || $sensornum == 2 || $sensornum == 3) && $old_bus  ==  0){
                    //echo '<script> alert("' . _('Change to 1-wire') . '"); </script>';
                    write_busvalue(1);
                    logger('DEBUG', 'sensorbus saved. changed to 1-wire (1)');
                    shell_exec('sudo /var/sudowebscript.sh sensorbus1wire > /dev/null 2>&1');
                    do_shutdown(_('Change to 1-wire sensor'));
                }
                if (($sensornum == 4 || $sensornum == 5 || $sensornum == 6) && $old_bus  ==  1){
                    //echo '<script> alert("' . _('Change to I2C') . '"); </script>';
                    write_busvalue(0);
                    logger('DEBUG', 'sensorbus saved. changed to i2c (0)');
                    shell_exec('sudo /var/sudowebscript.sh sensorbusi2c > /dev/null 2>&1');
                    do_shutdown(_('Change to I2C sensor'));
                }
                if ($sensornum != $old_sensornum) {
                    logger('DEBUG', 'internal sensor changed');
                    do_shutdown(_('Internal sensor changed'));
                }
            }
        
            # check if external sensor changed
            if ($old_sensorsecondtype != $sensorsecondnum) {    # external sensor changed
                // echo '<script language="javascript"> alert("' . _('External sensor type changed') . '"); </script>';
                $boot_msg = _('External sensor type changed');
                logger('DEBUG', 'External sensor type changed');
                if ($sensorsecondnum == 0 || $sensorsecondnum == 7) {    # changed back to disabled or MiThermometer, reboot is enough 
                    do_reboot($boot_msg);
                }
                else {
                    do_shutdown($boot_msg);
                }
            }
        
            # at last check if only ATC_xxxxxx device has changed
            if ($atc_device_name_changed == true) {
                logger('DEBUG', 'ATC device name changed');
            }
            else {
                echo '<script> alert("' . _('Nothing changed') . '"); </script>';
            }
        }
    }
?>
