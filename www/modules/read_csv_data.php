<?php
    // Gewählte CSV-Datei ermitteln
    $read_tables_json = file_get_contents('tabels.json');
    $array_new = json_decode($read_tables_json, true);
    $tabname = $array_new['Reifetab'];

    // Gewählte CSV-Datei auslesen und als Array anlegen
    //$CSV_FILE='/csv/Dryaging2.csv';

    echo $CSV_FILE;

    $row = 1;
    if (($handle = fopen($CSV_FILE, 'r')) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $num = count($data);
            echo '<p> $num Felder in Zeile $row: <br /></p>\n';
            $row++;
            for ($c=0; $c < $num; $c++) {
                echo $data[$c] . '<br />\n';
            }
        }
        fclose($handle);
    }
?>
