<?php
    # new nextion firmware upload. Save *.zip file in folder /var/www/nextion.
    # filename must be pi-ager.zip, zipped from pi-ager.tft
    if (isset($_POST['upload_nextion_firmware'])){
        unset($_POST['upload_nextion_firmware']);
        $logstring = 'button upload tft firmware pressed';
        logger('DEBUG', $logstring );
        
        $tft_display_type = get_table_value($config_settings_table, $tft_display_type_key);
        if ($tft_display_type == 1) {
            $upload_folder = '/var/www/nextion/NX3224K028/'; //Das Upload-Verzeichnis NX3224K028
        }
        else if ($tft_display_type == 2) {
            $upload_folder = '/var/www/nextion/NX3224F028/'; //Das Upload-Verzeichnis NX3224F028
        }
        else {
            $upload_folder = '/var/www/nextion/NX3224T028/'; //Das Upload-Verzeichnis NX3224T028
        }
        
        $filename = pathinfo($_FILES['tft_file']['name'], PATHINFO_FILENAME);
        if ($filename != ''){
            $extension = strtolower(pathinfo($_FILES['tft_file']['name'], PATHINFO_EXTENSION));
             
            //Überprüfung der Dateiendung
            $allowed_extensions = array('zip');
            if (!in_array($extension, $allowed_extensions)) {
                $logstring = _('error on upload new nextion firmware: only .zip-files allowed');
                logger('WARNING', $logstring );
                die;
            }
             
            //Überprüfung der Dateigröße
            $max_size = 6000*1024; //5000 KB
            if ($_FILES['tft_file']['size'] > $max_size) {
                $logstring = _('error on upload new nextion firmware: only 6000kb allowed');
                logger('WARNING', $logstring );
                die;
            }
             
            //Pfad zum Upload
            $new_path = $upload_folder.$filename.'.'.$extension; 
            
            // echo "Upload: " . $_FILES["tft_file"]["name"] . "<br>";
            // echo "Type: " . $_FILES["tft_file"]["type"] . "<br>";
            // echo "Size: " . ($_FILES["tft_file"]["size"] / 1024) . " kB<br>";
            // echo "Temp file: " . $_FILES["tft_file"]["tmp_name"] . "<br>";
            
            //Alles okay, verschiebe Datei an neuen Pfad
            if (move_uploaded_file($_FILES['tft_file']['tmp_name'], $new_path)) {
                // echo 'uploaded to ' . $new_path;
                $zip = new ZipArchive;
                if ($zip->open($new_path) === TRUE) {
                    $zip->extractTo($upload_folder);
                    $unzipped_filename = $zip->getNameIndex(0);
                    $zip->close();
                    $unzipped_filename = strtolower($unzipped_filename);
                    if ($unzipped_filename != 'pi-ager.tft') {
                        echo '<script language="javascript"> alert("'. (_("upload firmware file")) . " : " . (_("wrong zip file content, must be pi-ager.tft ")) .'"); window.location.href = "admin.php";</script>';
                    }
                } else {
                    echo '<script language="javascript"> alert("'. (_("upload firmware file")) . " : " . (_("error during unzip uploaded file")) .'"); window.location.href = "admin.php";</script>';
                }
            }
            else {
                echo '<script language="javascript"> alert("'. (_("upload firmware file")) . " : " . (_("please select a zip file with content pi-ager.tft to upload")) .'"); window.location.href = "admin.php";</script>';
            }
            $logstring = 'new firmware file uploaded and extracted to ' . $upload_folder.$unzipped_filename;
            logger('DEBUG', $logstring );
        }
        else{
            print '<script language="javascript"> alert("'. (_("upload firmware file")) . " : " . (_("please select a zip file to upload")) .'"); window.location.href = "admin.php";</script>';
        }
    }

?>
