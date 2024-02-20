<?php
    include 'names.php';
    include 'database.php';
    include 'logging.php';                            //liest die Datei fuer das logging ein

    // get the q parameter from URL
    // $request = $_REQUEST["q"];        //$request = $_GET['q'];
    // logger('DEBUG', 'Request is ' . $request);
    // if ($request == 'cv') {  
    //    $grepmain = intval(shell_exec('sudo /var/sudowebscript.sh grepmain'));
        $grepmain = exec('pgrep -a python3 | grep main.py');
        $current_values = get_current_values_for_ajax();
        if ($grepmain == '') {
            $current_values['grepmain'] = 0;
        }
        else {
           $current_values['grepmain'] = 1; 
        }
        echo json_encode($current_values);
    // }
    logger('DEBUG', 'querycv finished');
?>