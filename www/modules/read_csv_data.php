<?php 
    // Gewählte CSV-Datei ermitteln
   // $read_tables_json = file_get_contents('config/tables.json');
   // $array_tables_json = json_decode($read_tables_json, true);
   // $agingtablename = $array_tables_json['agingtable'];

    // Gewählte CSV-Datei auslesen und als Array anlegen
    //$chosen_agingtable='/csv/Dryaging2.csv';

    echo $chosen_agingtable;

    $row = 1;
    if (($handle = fopen($chosen_agingtable, 'r')) !== FALSE) {
        while (($tables_data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $num = count($tables_data);
            echo '<p> '
            echo sprintf(_('%d fields in row %d: '), $num, $row)
            echo '<br /></p>\n';
            $row++;
            for ($c=0; $c < $num; $c++) {
                echo $tables_data[$c] . '<br />\n';
            }
        }
        fclose($handle);
    }
?>
