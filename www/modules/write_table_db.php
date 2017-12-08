<?php 
// include 'database.php';
    #Namen der Reifetabelle in db speichern
    if (isset ($_POST['select_agingtable'])){
        logger('DEBUG', 'button select agingtable pressed');
        $chosen_agingtable = $_POST['agingtable'];
        write_agingtable($chosen_agingtable);
        $logstring = 'selected agingtable "' . $chosen_agingtable . '"';
        logger('INFO', $logstring );
        print '<script language="javascript"> alert("'. (_("agingtable")) . " : " . $chosen_agingtable . ' ' . (_("is selected")) .'"); </script>';
    }
    if (isset ($_POST['edit_agingtable_form_submit'])){
        $agingtable_to_edit = $_POST['agingtable_edit'];
        $maxrow = $_POST['max_row'];
        $index_row = 0;
        while ($index_row < $maxrow) {
            ${data_modus_edit_agingtable_.$index_row} = $_POST['data_modus_edit_agingtable_'.$index_row];
            ${data_setpoint_humidity_edit_agingtable_.$index_row} = $_POST['data_setpoint_humidity_edit_agingtable_'.$index_row];
            ${data_setpoint_temperature_edit_agingtable_.$index_row} = $_POST['data_setpoint_temperature_edit_agingtable_'.$index_row];
            ${data_circulation_air_duration_edit_agingtable_.$index_row} = $_POST['data_circulation_air_duration_edit_agingtable_'.$index_row];
            ${data_circulation_air_period_edit_agingtable_.$index_row} = $_POST['data_circulation_air_period_edit_agingtable_'.$index_row];
            ${data_exhaust_air_duration_edit_agingtable_.$index_row} = $_POST['data_exhaust_air_duration_edit_agingtable_'.$index_row];
            ${data_exhaust_air_period_edit_agingtable_.$index_row} = $_POST['data_exhaust_air_period_edit_agingtable_'.$index_row];
            ${data_days_edit_agingtable_.$index_row} = $_POST['data_days_edit_agingtable_'.$index_row];
        }
        //write_agingtable($chosen_agingtable);
        $logstring = 'button save edit agingtable pressed';;
        logger('DEBUG', $logstring );
        print '<script language="javascript"> alert("'. (_("agingtable")) . " : " . (_("edited agingtable saved")) .'"); window.location.href = "settings.php"; </script>';
    }
    if (isset ($_POST['delete_agingtable'])){
        $edit_agingtable = $_POST['agingtable_edit'];
        $returncode = delete_agingtable($edit_agingtable);
        $logstring = 'button delete agingtable pressed';
        logger('DEBUG', $logstring );
        if ($returncode == TRUE){
            print '<script language="javascript"> alert("'. (_("agingtable")) . " : " . (_("agingtable")) . ' ' . $edit_agingtable . ' ' . (_("deleted")) . '"); </script>';
        }
        elseif ($returncode == FALSE){
            print '<script language="javascript"> alert("'. (_("agingtable")) . " : " . $edit_agingtable . ' ' . (_("can not edit the controlling agingtable. Please choose an other first")) .'"); </script>';
        }
        else{
            print '<script language="javascript"> alert("'. (_("agingtable")) . " : " . (_("unexpected Error")) .'"); </script>';
        }
    }
    if (isset ($_POST['upload_new_agingtable'])){
        $edit_agingtable = $_POST['agingtable_edit'];
        // write_agingtable($edit_agingtable);
        $logstring = 'button upload new agingtable pressed';
        logger('DEBUG', $logstring );
        print '<script language="javascript"> alert("'. (_("button")) . " : " . (_("no buttonfunction")) .'"); </script>';
    }
    if (isset ($_POST['export_agingtable'])){
        $edit_agingtable = $_POST['agingtable_edit'];
        // write_agingtable($edit_agingtable);
        $logstring = 'button export  pressed';
        logger('DEBUG', $logstring );
        print '<script language="javascript"> alert("'. (_("button")) . " : " . (_("no buttonfunction")) .'"); </script>';
    }
?>
