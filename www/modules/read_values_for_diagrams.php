<?php 
    function get_defined_last_timestamp_from_array($array, $delta){
        // sort($array);
        $last_timestamp_in_array = end($array);
        Switch ($delta){
            case 'hour':
                return $last_timestamp_in_array - 3600;
            case 'day':
                return $last_timestamp_in_array - 86400;
            case 'week':
                return $last_timestamp_in_array - 604800;
            case 'month':
                return $last_timestamp_in_array - 2629700;
        }
    }
    
    function get_timestamps_for_time_axis($timestamps, $first_timestamp){
        foreach ($timestamps as $current_timestamp){
            if ($current_timestamp >= $first_timestamp){
            $timestamps_axis[] = $current_timestamp;
            }
        }
        return $timestamps_axis;
    }

    function get_dataset_of_values($datavalues, $timestamps_axis){
        foreach ($timestamps_axis as $current_timestamp_axis){
            $dataset[] = $datavalues[$current_timestamp_axis];
        }
        return $dataset;
    }

    $temperature_values = get_diagram_values($data_sensor_temperature_table);
    $humidity_values = get_diagram_values($data_sensor_humidity_table);
    $scale1_values = get_diagram_values($data_scale1_table);
    $scale2_values = get_diagram_values($data_scale2_table);
    
    $temperature_timestamps = array_keys($temperature_values);
    $humidity_timestamps = array_keys($humidity_values);
    $scale1_timestamps = array_keys($scale1_values);
    $scale2_timestamps = array_keys($scale2_values);
    
    $last_timestamp_temperature = $temperature_timestamps[count($temperature_timestamps)-1];
    $first_timestamp_temperature = get_defined_last_timestamp_from_array($temperature_timestamps, $diagram_mode);
    $last_timestamp_scale1 = $scale1_timestamps[count($scale1_timestamps)-1];
    $first_timestamp_scale1 = get_defined_last_timestamp_from_array($scale1_timestamps, $diagram_mode);
    
    $temperature_timestamps_axis = get_timestamps_for_time_axis($temperature_timestamps, $first_timestamp_temperature);
    $temperature_dataset = get_dataset_of_values($temperature_values, $temperature_timestamps_axis);
    
    $humidity_timestamps_axis = get_timestamps_for_time_axis($humidity_timestamps, $first_timestamp_temperature);
    $humidity_dataset = get_dataset_of_values($humidity_values, $humidity_timestamps_axis);
    
?>