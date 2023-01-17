<?php
// ajax query to write custom time to DB, from index.php
// without page refresh

    include 'names.php';
    include 'database.php';
    include 'logging.php'; 
    
    #### BEGIN Language from DB

    $language = intval(get_table_value($config_settings_table, $language_key));
    if ($language == 1) {
        $language = 'de_DE.utf8';
    }
    elseif ($language == 2) {
        $language = 'en_GB.utf8';
    }
    setlocale(LC_ALL, $language);
    
    # Set the text domain as 'messages'
    $domain = 'pi-ager';
    bindtextdomain($domain, "/var/www/locale"); 
    textdomain($domain);    
    
    #### END Language from DB
    
    logger('DEBUG', 'button change customtime pressed');
    $years = 0; // $_POST['years'];
    $months = $_POST['months'];
    $days = $_POST['days'];
    $hours = $_POST['hours'];
    $minutes =$_POST['minutes'];
    
    $time_in_seconds = $years * 31557600;
    $time_in_seconds = $time_in_seconds + ($months * 2678400);  // seconds per month with 31 days per month
    $time_in_seconds = $time_in_seconds + ($days * 86400);  // seconds per day
    $time_in_seconds = $time_in_seconds + ($hours * 3600);  // seconds per hour
    $time_in_seconds = $time_in_seconds + ($minutes * 60);  // seconds per minute
    
    write_customtime($time_in_seconds);
    echo _('custom time for diagrams changed');
?>