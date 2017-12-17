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
                
                header( "Content-Disposition: attachment; filename=\"" . $zipname . '"' );
                header( "X-LIGHTTPD-send-file: " . $downloadfile);
                
                logger('DEBUG', 'logfiles downloaded');
                exit();
            }
            else{
                $file_exists = FALSE;
                sleep(2);
            }
        }
    }
?>