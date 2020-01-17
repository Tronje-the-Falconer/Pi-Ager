<?php 
// include 'database.php';
#Bus Werte in Datenbank schreiben
if(!empty($_POST['change_sensorbus_submit']))
{                       // ist das $_POST-Array gesetzt
    logger('DEBUG', 'button save change_sensorbus pressed');
    $bus_value = $_POST['bustype_admin'];
    if ($bus_value == 1){
        write_busvalue(1);
        logger('DEBUG', 'sensorbus saved. changed to 1wire (1)');
        echo "<script>window.close();</script>";
        shell_exec('sudo /var/sudowebscript.sh sensorbus1wire');
    }
    if ($bus_value == 0){
        write_busvalue(0);
        logger('DEBUG', 'sensorbus saved. changed to i2c (0)');
        echo "<script>window.close();</script>";
        shell_exec('sudo /var/sudowebscript.sh sensorbusi2c');
    }
    echo "<meta http-equiv='refresh' content='0'>";
}
?>