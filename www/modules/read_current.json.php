<?php
    # current.json auslesen um aktuelle werte von temperatur und luftfeuchtigkeit anzuzeigen
    $API = file_get_contents('current.json');
    $array = json_decode($API, true);
    $sensor_temperature = round($array['sensor_temperature'], 1);
    $sensor_humidity = round($array['sensor_humidity'], 1);
    $current_data_timestamp = $array['last_change'];
?>
