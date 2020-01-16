<?php
$bus = round(get_table_value($config_settings_table,$sensorbus_key), 1);

if ($bus  ==  0) {
    $bus_name = 'i2c';
    $checked_bus_0 = 'checked="checked"';
    $checked_bus_1 = '';
}
if ($bus  ==  1) {
    $bus_name = '1wire';
    $checked_bus_1 = 'checked="checked"';
    $checked_bus_0 = '';
}