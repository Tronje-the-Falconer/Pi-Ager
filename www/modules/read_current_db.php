<?php 
// include 'database.php';
// include 'names.php';

    # config/current.json auslesen um aktuelle werte von temperatur und luftfeuchtigkeit anzuzeigen
    // $database = new SQLite3("/var/www/config/pi-ager.sqlite3");
    // #$result = $database->query('SELECT key, value FROM current WHERE "key"="status_exhaust_air" OR "key"="sensor_temperature" OR "key"="status_circulating_air" OR "key"="sensor_humidity" OR "key"="status_heater" OR "key"="status_cooling_compressor"');
    // $result = $database->query('SELECT value FROM sensor_temperature o WHERE "o.id" = (SELECT MAX(i.id) from sensor_temperature i)');
    // $items_current = array();
    // while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
        // {
        // $items_current[$dataset['key']] = $dataset['value'];
        // }
    // $database->close();
    $sensor_temperature = round(get_table_value($current_values_table,$sensor_temperature_key), 1);
    $sensor_humidity = round(get_table_value($current_values_table,$sensor_humidity_key), 1);
    $status_exhaust_air = get_table_value($current_values_table,$status_exhaust_air_key);
    $status_circulating_air = get_table_value($current_values_table,$status_circulating_air_key);
    $status_heater = get_table_value($current_values_table,$status_heater_key);
    $status_cooling_compressor = get_table_value($current_values_table,$status_cooling_compressor_key);
    $current_temperature_timestamp_last_change = get_last_change($current_values_table,$sensor_temperature_key);
    $current_humidity_timestamp_last_change = get_last_change($current_values_table,$sensor_temperature_key);
    $status_piager = get_table_value($current_values_table,$status_piager_key);
    $status_agingtable = get_table_value($current_values_table,$status_agingtable_key);
    $status_scale1 = get_table_value($current_values_table,$status_scale1_key);
    $status_scale2 =get_table_value($current_values_table,$status_scale2_key);
    
    $current_scale1_timestamp_last_change_dataset = get_scale_table_row($data_scale1_table);
    $current_scale1_timestamp_last_change = $current_scale1_timestamp_last_change_dataset[$last_change_field];
    $current_scale2_timestamp_last_change_dataset = get_scale_table_row($data_scale2_table);
    $current_scale2_timestamp_last_change = $current_scale2_timestamp_last_change_dataset[$last_change_field];
?>
