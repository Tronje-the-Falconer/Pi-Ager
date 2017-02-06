<?php
    # current.json auslesen um aktuelle werte von temperatur und luftfeuchtigkeit anzuzeigen
    $API = file_get_contents('current.json');
    $array = json_decode($API, true);
    $temp_float = $array['temperatur'];
    $hum_float = $array['luftfeuchtigkeit'];
    $data_timestamp = $array['date'];
?>