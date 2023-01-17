<?php
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
        
    // get the q parameter from URL, which defines the diagram mode for diagrams.php
    $request = $_REQUEST["q"];        //$request = $_GET['q'];
    logger('DEBUG', 'button change diagram mode pressed : ' . $request);
    if ($request == 'hour') {
        write_diagram_modus('hour');
        echo _('diagrams') . ' - ' . _('hour');
    }
    else if ($request == 'day') {
        write_diagram_modus('day');
        echo _('diagrams') . ' - ' . _('day');
    }
    else if ($request == 'week') {
        write_diagram_modus('week');
        echo _('diagrams') . ' - ' . _('week');
    }
    else if ($request == 'month') {
        write_diagram_modus('month');
        echo _('diagrams') . ' - ' . _('month');
    }
    else if ($request == 'custom') {
        write_diagram_modus('custom');
        echo _('diagrams') . ' - ' . _('custom');
    }
    else {
        echo 'unknown diagram modus!';  
    }        
?>