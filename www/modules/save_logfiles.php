<?php
    if (isset ($_POST['save_logfiles'])){
        logger('DEBUG', 'button save logfiles pressed');
        
        $zipname="pi-ager_logfiles.zip";
        $zipfilepath = "/var/www/logs/";
        $downloadfile = $zipfilepath . $zipname;
        $file_exists = FALSE;
        
        $sqlitename = "pi-ager.sqlite3";
        $sqlitepath = "/var/www/config/";
        $sqlitefile = $sqlitepath . $sqlitename;
        $copysqlitefile = $zipfilepath . $sqlitename;

        copy($sqlitefile, $copysqlitefile);
        
        shell_exec('sudo /var/sudowebscript.sh ziplogfiles');
        
        unlink($copysqlitefile);
        
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