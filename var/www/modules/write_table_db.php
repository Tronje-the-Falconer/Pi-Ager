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
    # Reifetabelle nach editieren in DB schreiben
    if (isset ($_POST['edit_agingtable_form_submit'])){
        $logstring = 'button save edit agingtable pressed';
        logger('DEBUG', $logstring );
        
        $agingtable_to_edit = $_POST['agingtable_edit'];
        $maxrow = $_POST['max_row'];
        $comment = $_POST['comment_edit_agingtable'];
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
            
            if ($index_row == 0){
                $sql = 'UPDATE agingtable_' . $agingtable_to_edit . ' SET "' . $agingtable_modus_field . '" = ' . $data_modus_edit_agingtable . ', "' . $agingtable_setpoint_humidity_field . '" = ' . $data_setpoint_humidity_edit_agingtable . ', "' . $agingtable_setpoint_temperature_field . '" = ' . $data_setpoint_temperature_edit_agingtable . ', "' . $agingtable_circulation_air_duration_field . '" = ' . $data_circulation_air_duration_edit_agingtable . ', "' . $agingtable_circulation_air_period_field . '" = ' . $data_circulation_air_period_edit_agingtable . ', "' . $agingtable_exhaust_air_duration_field . '" = ' . $data_exhaust_air_duration_edit_agingtable . ', "' . $agingtable_exhaust_air_period_field . '" = ' . $data_exhaust_air_period_edit_agingtable . ', "' . $agingtable_days_field . '" = ' . $data_days_edit_agingtable . ', "' . $agingtable_comment_field . '" = "'  . $comment . '" WHERE "' . $id_field . '" =' . $row_id .  ';';
            }
            else{
                $sql = 'UPDATE agingtable_' . $agingtable_to_edit . ' SET "' . $agingtable_modus_field . '" = ' . $data_modus_edit_agingtable . ', "' . $agingtable_setpoint_humidity_field . '" = ' . $data_setpoint_humidity_edit_agingtable . ', "' . $agingtable_setpoint_temperature_field . '" = ' . $data_setpoint_temperature_edit_agingtable . ', "' . $agingtable_circulation_air_duration_field . '" = ' . $data_circulation_air_duration_edit_agingtable . ', "' . $agingtable_circulation_air_period_field . '" = ' . $data_circulation_air_period_edit_agingtable . ', "' . $agingtable_exhaust_air_duration_field . '" = ' . $data_exhaust_air_duration_edit_agingtable . ', "' . $agingtable_exhaust_air_period_field . '" = ' . $data_exhaust_air_period_edit_agingtable . ', "' . $agingtable_days_field . '" = ' . $data_days_edit_agingtable . ' WHERE "' . $id_field . '" =' . $row_id .  ';';
            }
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
    # Reifetabelle löschen
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
    # neue Reifetabelle per Upload in db schreiben

    if (isset ($_POST['upload_new_agingtable'])){
        $logstring = 'button upload agingtable pressed';
        logger('DEBUG', $logstring );
        
        $upload_folder = '/var/www/csv/'; //Das Upload-Verzeichnis
        $filename = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
        if ($filename != ''){
            $extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
             
             
            //Überprüfung der Dateiendung
            $allowed_extensions = array('csv');
            if(!in_array($extension, $allowed_extensions)) {
                $logstring = _('error on upload new agingtable: only csv-files allowed');
                logger('WARNING', $logstring );
                die;
            }
             
            //Überprüfung der Dateigröße
            $max_size = 500*1024; //500 KB
            if($_FILES['file']['size'] > $max_size) {
                $logstring = _('error on upload new agingtable: only 500kb allowed');
                logger('WARNING', $logstring );
                die;
            }
             
            //Pfad zum Upload
            $new_path = $upload_folder.$filename.'.'.$extension;
             
            //Neuer Dateiname falls die Datei bereits existiert
            if(file_exists($new_path)) { //Falls Datei existiert, hänge eine Zahl an den Dateinamen
                 $id = 1;
                 do {
                     $new_path = $upload_folder.$filename.'_'.$id.'.'.$extension;
                     $id++;
                 } while(file_exists($new_path));
            }
             
            //Alles okay, verschiebe Datei an neuen Pfad
            move_uploaded_file($_FILES['file']['tmp_name'], $new_path);
            $logstring = 'new csv-file uploaded to ' . $new_path;
            logger('DEBUG', $logstring );
            
            $csv_imported_in_database = import_csv_to_sqlite($new_path);
            
            if($csv_imported_in_database == true){
                $logstring = ' agingtable saved in db';
                logger('DEBUG', $logstring );
                print '<script language="javascript"> alert("'. (_("upload agingtable")) . " : csv-" . (_("file saved in database")) .'"); </script>';
            }
            else{
                unlink($new_path);
                $logstring = _('csv-file has not the right structure. please use a default-template');
                logger('WARNING', $logstring );
                print '<script language="javascript"> alert("'. (_("upload agingtable")) . " : csv-" . (_("csv-file has not the right structure. please use a default-template")) .'"); </script>';
            }
            
        }
        else{
            print '<script language="javascript"> alert("'. (_("upload agingtable")) . " : " . (_("please select an file to upload")) .'"); window.location.href = "settings.php";</script>';
        }
    }
    if (isset ($_POST['export_agingtable'])){
        $logstring = 'button export agingtable pressed';
        logger('DEBUG', $logstring );
        $edit_agingtable = $_POST['agingtable_edit'];
        $agingtable_rows = get_agingtable_dataset($edit_agingtable);
        
        
        $filename = $edit_agingtable . '.csv';
        $filepath = '/var/www/csv/' . $filename;
        $file = fopen($filepath,"w");
        $number_rows = count($agingtable_rows);
        $index_row = -1;
        while ($index_row < $number_rows) {
            $dataset = $agingtable_rows[$index_row];
            if ($index_row == -1){
                $data_modus = $agingtable_modus_field;
                $data_setpoint_humidity = $agingtable_setpoint_humidity_field;
                $data_setpoint_temperature = $agingtable_setpoint_temperature_field;
                $data_circulation_air_duration = $agingtable_circulation_air_duration_field;
                $data_circulation_air_period =$agingtable_circulation_air_period_field;
                $data_exhaust_air_duration = $agingtable_exhaust_air_duration_field;
                $data_exhaust_air_period = $agingtable_exhaust_air_period_field;
                $data_days = $agingtable_days_field;
                $data_comment = $agingtable_comment_field;
            }
            else{
                if (!empty($dataset[$agingtable_modus_field])){
                    $data_modus = intval($dataset[$agingtable_modus_field]);
                } else {$data_modus = NULL;}
                if (!empty($dataset[$agingtable_setpoint_humidity_field])){
                    $data_setpoint_humidity = intval($dataset[$agingtable_setpoint_humidity_field]);
                } else {$data_setpoint_humidity = NULL;}
                if (!empty($dataset[$agingtable_setpoint_temperature_field])){
                    $data_setpoint_temperature = intval($dataset[$agingtable_setpoint_temperature_field]);
                } else {$data_setpoint_temperature = NULL;}
                if (!empty($dataset[$agingtable_circulation_air_duration_field])){
                    $data_circulation_air_duration = intval($dataset[$agingtable_circulation_air_duration_field]);
                } else {$data_circulation_air_duration = NULL;}
                if (!empty($dataset[$agingtable_circulation_air_period_field])){
                    $data_circulation_air_period = intval($dataset[$agingtable_circulation_air_period_field]);
                } else {$data_circulation_air_period = NULL;}
                if (!empty($dataset[$agingtable_exhaust_air_duration_field])){
                    $data_exhaust_air_duration = intval($dataset[$agingtable_exhaust_air_duration_field]);
                } else {$data_exhaust_air_duration = NULL;}
                if (!empty($dataset[$agingtable_exhaust_air_period_field])){
                    $data_exhaust_air_period = intval($dataset[$agingtable_exhaust_air_period_field]);
                } else {$data_exhaust_air_period = NULL;}
                if (!empty($dataset[$agingtable_days_field])){
                    $data_days = intval($dataset[$agingtable_days_field]);
                } else {$data_days = NULL;}
                if (!empty($dataset[$agingtable_comment_field])){
                    $data_comment = "'" . $dataset[$agingtable_comment_field] . "'";
                } else {$data_comment = NULL;}
            }


            $line = $data_modus . ',' . $data_setpoint_humidity . ',' . $data_setpoint_temperature .',' . $data_circulation_air_duration .',' . $data_circulation_air_period .',' . $data_exhaust_air_duration .',' . $data_exhaust_air_period .',' . $data_days .',' . $data_comment;
            fputcsv($file, explode(',', $line));
            $index_row++;
        }
        
        fclose($file);
        header( "Content-Disposition: attachment; filename=\"" . $filename . '"' );
        header( "X-LIGHTTPD-send-file: " . $filepath);
        
        
        $logstring = 'button export  pressed';
        logger('DEBUG', $logstring );
        print '<script language="javascript"> alert("'. (_("export agintable")) . " : " . (_("done")) .'"); </script>';
    }
?>
