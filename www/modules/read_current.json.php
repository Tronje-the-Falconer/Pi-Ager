<?php
    # current.json auslesen um aktuelle werte von temperatur und luftfeuchtigkeit anzuzeigen
    $API = file_get_contents('current.json');
    $array = json_decode($API, true);
    $temp_float = round($array['temperatur'], 1);
    $hum_float = round($array['luftfeuchtigkeit'], 1);
    $data_timestamp = $array['date'];
?>
