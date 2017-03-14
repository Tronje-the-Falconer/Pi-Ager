<?php 
    // Gewählte CSV-Datei ermitteln
   // $read_tables_json = file_get_contents('tables.json');
   // $array_tables_json = json_decode($read_tables_json, true);
   // $hangingtablename = $array_tables_json['hangingtable'];

    // Gewählte CSV-Datei auslesen und als Array anlegen
    //$chosen_hangingtable='/csv/Dryaging2.csv';

    echo $chosen_hangingtable;

    $row = 1;
    if (($handle = fopen($chosen_hangingtable, 'r')) !== FALSE) {
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
