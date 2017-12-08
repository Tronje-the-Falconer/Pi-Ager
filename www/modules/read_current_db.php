<?php 
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
    $status_light_manual =get_table_value($current_values_table,$status_light_manual_key);
    
    $current_scale1_timestamp_last_change_dataset = get_scale_table_row($data_scale1_table);
    $current_scale1_timestamp_last_change = $current_scale1_timestamp_last_change_dataset[$last_change_field];
    $current_scale2_timestamp_last_change_dataset = get_scale_table_row($data_scale2_table);
    $current_scale2_timestamp_last_change = $current_scale2_timestamp_last_change_dataset[$last_change_field];
    logger('DEBUG', 'read_current_db performed');
?>
