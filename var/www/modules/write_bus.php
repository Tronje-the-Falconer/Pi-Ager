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
        $bus_string = 'sensorbus1wire';
    }
    if ($bus_value == 0){
        write_busvalue(0);
        logger('DEBUG', 'sensorbus saved. changed to i2c (0)');
        $bus_string = 'sensorbusi2c';
    }
    #        print "<script>window.close();</script>";
    #file_get_contents('./index.php');
    header('Location: index.php');
    shell_exec('sudo /var/sudowebscript.sh', $bus_string);
    echo "<meta http-equiv='refresh' content='0'>";
}
?>