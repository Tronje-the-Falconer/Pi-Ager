<?php 
    // zuletzt gespeicherte Reifeart in der tables.json
    $data_tables_json = file_get_contents('tables.json');
    $array_tables_json = json_decode($data_tables_json, true);
    $desired_maturity = $array_tables_json['agingtable'];

    // Derzeitige Reife-Art, manuell oder nach Tabelle...
    $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
    if ($grepagingtable){
        $data_tables_json = file_get_contents('tables.json');
        $array_tables_json1 = json_decode($data_tables_json, true);
        $maturity_type = $array_tables_json1['agingtable'];

    }
    else {
        $maturity_type = _('none');
    }
?>
