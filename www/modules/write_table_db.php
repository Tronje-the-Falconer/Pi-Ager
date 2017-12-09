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
        $logstring = 'button save edit agingtable pressed';
        logger('DEBUG', $logstring );
        
        $agingtable_to_edit = $_POST['agingtable_edit'];
        $maxrow = $_POST['max_row'];
        $index_row = 0;
        $row_id = 1;
        while ($index_row < $maxrow) {
            $sql = '';
            $data_modus_edit_agingtable = set_null_if_empty($_POST['data_modus_edit_agingtable_'.$index_row]);
            $data_setpoint_humidity_edit_agingtable = set_null_if_empty($_POST['data_setpoint_humidity_edit_agingtable_'.$index_row]);
            $data_setpoint_temperature_edit_agingtable = set_null_if_empty($_POST['data_setpoint_temperature_edit_agingtable_'.$index_row]);
            $data_circulation_air_duration_edit_agingtable = set_null_if_empty($_POST['data_circulation_air_duration_edit_agingtable_'.$index_row]);
            if ($data_circulation_air_duration_edit_agingtable != 'NULL'){
                $data_circulation_air_duration_edit_agingtable = $data_circulation_air_duration_edit_agingtable * 60;
            }
            $data_circulation_air_period_edit_agingtable = set_null_if_empty($_POST['data_circulation_air_period_edit_agingtable_'.$index_row]);
            if ($data_circulation_air_period_edit_agingtable != 'NULL'){
                $data_circulation_air_period_edit_agingtable = $data_circulation_air_period_edit_agingtable * 60;
            }
            $data_exhaust_air_duration_edit_agingtable = set_null_if_empty($_POST['data_exhaust_air_duration_edit_agingtable_'.$index_row]);
            if ($data_exhaust_air_duration_edit_agingtable != 'NULL'){
                $data_exhaust_air_duration_edit_agingtable = $data_exhaust_air_duration_edit_agingtable * 60;
            }
            $data_exhaust_air_period_edit_agingtable = set_null_if_empty($_POST['data_exhaust_air_period_edit_agingtable_'.$index_row]);
            if ($data_exhaust_air_period_edit_agingtable != 'NULL'){
                $data_exhaust_air_period_edit_agingtable = $data_exhaust_air_period_edit_agingtable * 60;
            }
            $data_days_edit_agingtable = set_null_if_empty($_POST['data_days_edit_agingtable_'.$index_row]);

            $sql = 'UPDATE agingtable_' . $agingtable_to_edit . ' SET "' . $agingtable_modus_field . '" = ' . $data_modus_edit_agingtable . ', "' . $agingtable_setpoint_humidity_field . '" = ' . $data_setpoint_humidity_edit_agingtable . ', "' . $agingtable_setpoint_temperature_field . '" = ' . $data_setpoint_temperature_edit_agingtable . ', "' . $agingtable_circulation_air_duration_field . '" = ' . $data_circulation_air_duration_edit_agingtable . ', "' . $agingtable_circulation_air_period_field . '" = ' . $data_circulation_air_period_edit_agingtable . ', "' . $agingtable_exhaust_air_duration_field . '" = ' . $data_exhaust_air_duration_edit_agingtable . ', "' . $agingtable_exhaust_air_period_field . '" = ' . $data_exhaust_air_period_edit_agingtable . ', "' . $agingtable_days_field . '" = ' . $data_days_edit_agingtable . ' WHERE "' . $id_field . '" =' . $row_id .  ';';
            print $sql;
            open_connection();
            execute_query($sql);
            close_database();
            
            $row_id++;
            $index_row++;
        }
       
        $logstring = 'edited agingtable saved';
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
