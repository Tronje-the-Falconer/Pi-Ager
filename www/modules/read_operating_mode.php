<?php 
    // zuletzt gespeicherte Reifeart in der tables.json
    $data_tables_json = file_get_contents('tables.json');
    $array_tables_json = json_decode($data_tables_json, true);
    $desired_maturity = $array_tables_json['hangingtable'];

    // Derzeitige Reife-Art, manuell oder nach Tabelle...
    $grephangingtable = shell_exec('sudo /var/sudowebscript.sh grephangingtable');
    if ($grephangingtable){
        $data_tables_json = file_get_contents('tables.json');
        $array_tables_json1 = json_decode($data_tables_json, true);
        $maturity_type = $array_tables_json1['hangingtable'];

    }
    else {
        $maturity_type = _('keine');
    }
?>
