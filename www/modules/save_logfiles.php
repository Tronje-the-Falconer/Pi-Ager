<?php
    if (isset ($_POST['save_logfiles'])){
        logger('DEBUG', 'button save logfiles pressed');
        
        shell_exec('sudo /var/sudowebscript.sh ziplogfiles');
        
        $zipname="pi-ager_logfiles.zip";
        $filepath = "/var/www/logs/";
        $downloadfile = $filepath . $zipname;
        $file_exists = FALSE;
        while ($file_exists == FALSE){
            if(file_exists($_SERVER['DOCUMENT_ROOT']."/logs/".$zipname)) {
                $file_exists = TRUE;

                $filesize = filesize($downloadfile);
                // http headers for zip downloads
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename='".$zipname."'");
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: ". $filesize);
                //ob_end_flush();
                //@
                readfile($downloadfile);
                
                
                // header('Content-Type: application/zip');
                // header('Content-disposition: attachment; filename='.$zipname);
                // header('Content-Length: ' . filesize($zipname));
                // header('Content-Transfer-Encoding: binary');
                // header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                // readfile($zipname);
                exit();
            }
            else{
                $file_exists = FALSE;
                sleep(2);
            }
        }
    }
?>