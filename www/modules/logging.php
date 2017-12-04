<?php
    function logger($loglevel, $message){
        $date_pi_ager_log = date("Y-M-D H:M:S");
        $date_website_log = date("D.M.Y H:M");
        
        switch $loglevel{
            case 'DEBUG':
                $loglevelstring = 10;
            case 'INFO':
                $loglevelstring = 20;
            case 'WARNING':
                $loglevelstring = 30;
            case 'ERROR':
                $loglevelstring = 40;
            case 'CRITICAL':
                $loglevelstring = 50;
        }
        
        $loglevel_file_value = get_loglevel($loglevel_file_key)
        
        $message_website_log = $date_website_log . ' ' . __FILE__ . ' ' . $message;
        $message_pi_ager_log = $date_pi_ager_log . ' ' . __FILE__ . ' ' . $loglevel . ' ' . $message;
        
        if ($loglevelstring > 10){
            return file_put_contents($logfile_txt_file, $message_website_log, FILE_APPEND);
        }
        if ($loglevelstring >= $loglevel_file_value){
            return file_put_contents($pi_ager_log_file, $message_pi_ager_log, FILE_APPEND);
        }
    }
    logger('DEBUG', 'Button pressed')
?>