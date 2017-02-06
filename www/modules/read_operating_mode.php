<?php
    // zuletzt gespeicherte Reifeart in der tabels.json
    $read_tables_json = file_get_contents('tabels.json');
    $array_new = json_decode($read_tables_json, true);
    $wunschreife = $array_new['Reifetab'];

    // Derzeitige Reife-Art, manuell oder nach Tabelle...
    $valtab = shell_exec('sudo /var/sudowebscript.sh grepreifetab');
    if ($valtab){
        $API = file_get_contents('tabels.json');
        $array1 = json_decode($API, true);
        $reifeart = $array1['Reifetab'];

    }
    else {
        $reifeart = 'keine';
    }
?>