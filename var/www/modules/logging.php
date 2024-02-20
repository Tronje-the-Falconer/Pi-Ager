<?php
    function logger($loglevel, $message){
        global $loglevel_file_value, $logfile_txt_file, $pi_ager_log_file, $loglevel_file_key;
        try {
            $str = fgets(fopen('/etc/timezone', 'r'));
            $str = str_replace("\n", "", $str);
            date_default_timezone_set($str);
        }
        catch(Exception $ex)
        {
        }
        // $timezone = date_default_timezone_get();
        
        // $localtime_assoc = localtime(time(), true);

        $date_pi_ager_log = date("m-d H:i:s");
        $date_website_log = date("y-m-d H:i:s");
        $backfiles = debug_backtrace();
        $file_called_from = basename($backfiles[0]['file']);
        
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
        
        $loglevel_file_value = intval(get_loglevel($loglevel_file_key));
        
        // $logfile_txt_file = intval($loglevel_file_value);
        $message_website_log = $date_website_log . ' ' . $message  . " \n";
        // $message_pi_ager_log = sprintf("%02d-%02d %02d:%02d:%02d %-10s %-35s %s %s\n", $localtime_assoc["tm_mon"] + 1, $localtime_assoc["tm_mday"], $localtime_assoc["tm_hour"], $localtime_assoc["tm_min"], $localtime_assoc["tm_sec"], $loglevel , $file_called_from , $message, $timezone);
        $message_pi_ager_log = sprintf("%s %-10s %-35s %s\n", $date_pi_ager_log, $loglevel , $file_called_from , $message);
        
        if ($loglevelstring > 10){
            return file_put_contents($logfile_txt_file, $message_website_log, FILE_APPEND);
        }
        if ($loglevelstring >= $loglevel_file_value){
            return file_put_contents($pi_ager_log_file, $message_pi_ager_log, FILE_APPEND);
        }
    }
    // aufruf: logger('DEBUG', 'Button pressed');
?>