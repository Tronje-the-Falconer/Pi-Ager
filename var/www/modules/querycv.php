<?php
    include 'names.php';
    include 'database.php';
    include 'logging.php';                            //liest die Datei fuer das logging ein

    // get the q parameter from URL
    // $request = $_REQUEST["q"];        //$request = $_GET['q'];
    // logger('DEBUG', 'Request is ' . $request);
    // if ($request == 'cv') {  
        $grepmain = intval(shell_exec('sudo /var/sudowebscript.sh grepmain'));
        $current_values = get_current_values_for_ajax();
        $current_values['grepmain'] = $grepmain;
        echo json_encode($current_values);
    // }
    logger('DEBUG', 'querycv finished');
?>