<?php
    function logger($loglevel, $message){
        global $loglevel_file_value, $logfile_txt_file, $pi_ager_log_file, $loglevel_file_key;
        
        $date_pi_ager_log = date("m-d H:i:s");
        $date_website_log = date("y-m-d H:i:s");
        
        switch ($loglevel){
            case 'DEBUG':
                $loglevelstring = 10;
                break;
            case 'INFO':
                $loglevelstring = 20;
                break;
            case 'WARNING':
                $loglevelstring = 30;
                break;
            case 'ERROR':
                $loglevelstring = 40;
                break;
            case 'CRITICAL':
                $loglevelstring = 50;
                break;
        }
        
        intval($loglevel_file_value) = get_loglevel($loglevel_file_key);
        
        $message_website_log = $date_website_log . ' ' . $message  . " \n ";
        $message_pi_ager_log = $date_pi_ager_log . ' ' . __FILE__ . ' ' . $loglevel . ' ' . $message . " \n ";
        
        if ($loglevelstring > 10){
            return file_put_contents($logfile_txt_file, $message_website_log, FILE_APPEND);
        }
        if ($loglevelstring >= $loglevel_file_value){
            return file_put_contents($pi_ager_log_file, $message_pi_ager_log, FILE_APPEND);
        }
    }
    // aufruf: logger('DEBUG', 'Button pressed');
?>