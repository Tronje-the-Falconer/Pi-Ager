<?php
    $bus = round(get_table_value($config_settings_table,$sensorbus_key), 1);
    
    if ($bus  ==  0) {
        $bus_name = 'i2c';
        $changebus_name = '1wire';
    }
    if ($bus  ==  1) {
        $bus_name = '1wire';
        $changebus_name = 'i2c';
    }