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
        print "<script language='javascript'>window.location.href='index.php';</script>";
        shell_exec('sudo /var/sudowebscript.sh sensorbus1wire');
        print '<script language="javascript"> alert("'. (_("sensorbus changed to i2c. please unplug and plug in the power supply for the restart after shutdown.")) . " : " . (_("shutdown")) .'"); </script>';
    }
    if ($bus_value == 0){
        write_busvalue(0);
        logger('DEBUG', 'sensorbus saved. changed to i2c (0)');
        print "<script language='javascript'>window.location.href='index.php';</script>";
        shell_exec('sudo /var/sudowebscript.sh sensorbusi2c');
        print '<script language="javascript"> alert("'. (_("sensorbus changed to 1wire. please unplug and plug in the power supply for the restart after shutdown.")) . " : " . (_("shutdown")) .'"); </script>';
    }
    echo "<meta http-equiv='refresh' content='0'>";
}
?>