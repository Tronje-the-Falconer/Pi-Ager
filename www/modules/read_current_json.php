<?php 
    # current.json auslesen um aktuelle werte von temperatur und luftfeuchtigkeit anzuzeigen
    $current_json_file = file_get_contents('current.json');
    $array_current_json = json_decode($current_json_file, true);
    $sensor_temperature = round($array_current_json['sensor_temperature'], 1);
    $sensor_humidity = round($array_current_json['sensor_humidity'], 1);
    $current_json_timestamp_last_change = $array_current_json['last_change'];
?>
