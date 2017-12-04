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
    
    function get_text_array_for_time_axis($timestamps_axis){
        $axis_text = '[';
        foreach ($timestamps_axis as $timestamp){
            $axis_text = $axis_text . 'new Date(' .$timestamp . '000),';
        }
        $axis_text = substr($axis_text, 0, -1);
        $axis_text = $axis_text . ']';
        return $axis_text;
    }

    function get_dataset_of_values($datavalues, $timestamps_axis){
        foreach ($timestamps_axis as $current_timestamp_axis){
            $dataset[] = $datavalues[$current_timestamp_axis];
        }
        return $dataset;
    }

    function duplicate_last_value_in_array($array, $added_timestamp){
        $last_value = end($array);
        $array[$added_timestamp] = $last_value;
        
        return $array;
    }
    
    function add_current_time_in_array($array){
        
        $new_time = get_current_time();
        $array[] = $new_time;
        
        return $array;
    }
    
    $temperature_values = get_diagram_values($data_sensor_temperature_table);
    $temperature_timestamps = array_keys($temperature_values);
    $last_timestamp_temperature = $temperature_timestamps[count($temperature_timestamps)-1];
    $first_timestamp_temperature = get_defined_last_timestamp_from_array($temperature_timestamps, $diagram_mode);
    $temperature_timestamps_axis = get_timestamps_for_time_axis($temperature_timestamps, $first_timestamp_temperature);
    $temperature_timestamps_axis_text = get_text_array_for_time_axis($temperature_timestamps_axis);
    $temperature_dataset = get_dataset_of_values($temperature_values, $temperature_timestamps_axis);
    
    $humidity_values = get_diagram_values($data_sensor_humidity_table);
    $humidity_timestamps = array_keys($humidity_values);
    $last_timestamp_humidity = $humidity_timestamps[count($humidity_timestamps)-1];
    $first_timestamp_humidity = get_defined_last_timestamp_from_array($humidity_timestamps, $diagram_mode);
    $humidity_timestamps_axis = get_timestamps_for_time_axis($humidity_timestamps, $first_timestamp_humidity);
    $humidity_timestamps_axis_text = get_text_array_for_time_axis($humidity_timestamps_axis);
    $humidity_dataset = get_dataset_of_values($humidity_values, $humidity_timestamps_axis);
    
    $scale1_values = get_diagram_values($data_scale1_table);
    $scale1_timestamps = array_keys($scale1_values);
    $last_timestamp_scale1 = $scale1_timestamps[count($scale1_timestamps)-1];
    $first_timestamp_scale1 = get_defined_last_timestamp_from_array($scale1_timestamps, $diagram_mode);
    $scale1_timestamps_axis = get_timestamps_for_time_axis($scale1_timestamps, $first_timestamp_scale1);
    $scale1_timestamps_axis_text = get_text_array_for_time_axis($scale1_timestamps_axis);
    $scale1_dataset = get_dataset_of_values($scale1_values, $scale1_timestamps_axis);
    
    $scale2_values = get_diagram_values($data_scale2_table);
    $scale2_timestamps = array_keys($scale2_values);
    $last_timestamp_scale2 = $scale2_timestamps[count($scale2_timestamps)-1];
    $first_timestamp_scale2 = get_defined_last_timestamp_from_array($scale2_timestamps, $diagram_mode);
    $scale2_timestamps_axis = get_timestamps_for_time_axis($scale2_timestamps, $first_timestamp_scale2);
    $scale2_timestamps_axis_text = get_text_array_for_time_axis($scale2_timestamps_axis);
    $scale2_dataset = get_dataset_of_values($scale2_values, $scale2_timestamps_axis);
    
    $uv_light_values = get_diagram_values($status_uv_table);
    $uv_light_timestamps = array_keys($uv_light_values);
    $last_timestamp_uv_light = $uv_light_timestamps[count($uv_light_timestamps)-1];
    $first_timestamp_uv_light = get_defined_last_timestamp_from_array($uv_light_timestamps, $diagram_mode);
    
    $uv_light_timestamps_with_duplicated_last = add_current_time_in_array($uv_light_timestamps);
    $uv_light_values_with_duplicated_last = duplicate_last_value_in_array($uv_light_values, end($uv_light_timestamps_with_duplicated_last));
    
    $uv_light_timestamps_axis = get_timestamps_for_time_axis($uv_light_timestamps_with_duplicated_last, $first_timestamp_uv_light);
    $uv_light_timestamps_axis_text = get_text_array_for_time_axis($uv_light_timestamps_axis);
    $uv_light_dataset = get_dataset_of_values($uv_light_values_with_duplicated_last, $uv_light_timestamps_axis);
  
    $light_values = get_diagram_values($status_light_table);
    $light_timestamps = array_keys($light_values);
    $last_timestamp_light = $light_timestamps[count($light_timestamps)-1];
    $first_timestamp_light = get_defined_last_timestamp_from_array($light_timestamps, $diagram_mode);
    $light_timestamps_with_duplicated_last = add_current_time_in_array($light_timestamps);
    $light_values_with_duplicated_last = duplicate_last_value_in_array($light_values, end($light_timestamps_with_duplicated_last));
    $light_timestamps_axis = get_timestamps_for_time_axis($light_timestamps_with_duplicated_last, $first_timestamp_light);
    $light_timestamps_axis_text = get_text_array_for_time_axis($light_timestamps_axis);
    $light_dataset = get_dataset_of_values($light_values_with_duplicated_last, $light_timestamps_axis);
    
    $heater_values = get_diagram_values($status_heater_table);
    $heater_timestamps = array_keys($heater_values);
    $last_timestamp_heater = $heater_timestamps[count($heater_timestamps)-1];
    $first_timestamp_heater = get_defined_last_timestamp_from_array($heater_timestamps, $diagram_mode);
    $heater_timestamps_axis = get_timestamps_for_time_axis($heater_timestamps, $first_timestamp_heater);
    $heater_timestamps_axis_text = get_text_array_for_time_axis($heater_timestamps_axis);
    $heater_dataset = get_dataset_of_values($heater_values, $heater_timestamps_axis);
    
    $cooler_values = get_diagram_values($status_cooling_compressor_table);
    $cooler_timestamps = array_keys($cooler_values);
    $last_timestamp_cooler = $cooler_timestamps[count($cooler_timestamps)-1];
    $first_timestamp_cooler = get_defined_last_timestamp_from_array($cooler_timestamps, $diagram_mode);
    $cooler_timestamps_axis = get_timestamps_for_time_axis($cooler_timestamps, $first_timestamp_cooler);
    $cooler_timestamps_axis_text = get_text_array_for_time_axis($cooler_timestamps_axis);
    $cooler_dataset = get_dataset_of_values($cooler_values, $cooler_timestamps_axis);
    
    $humidifier_values = get_diagram_values($status_humidifier_table);
    $humidifier_timestamps = array_keys($humidifier_values);
    $last_timestamp_humidifier = $humidifier_timestamps[count($humidifier_timestamps)-1];
    $first_timestamp_humidifier = get_defined_last_timestamp_from_array($humidifier_timestamps, $diagram_mode);
    $humidifier_timestamps_axis = get_timestamps_for_time_axis($humidifier_timestamps, $first_timestamp_humidifier);
    $humidifier_timestamps_axis_text = get_text_array_for_time_axis($humidifier_timestamps_axis);
    $humidifier_dataset = get_dataset_of_values($humidifier_values, $humidifier_timestamps_axis);
    
    $dehumidifier_values = get_diagram_values($status_dehumidifier_table);
    $dehumidifier_timestamps = array_keys($dehumidifier_values);
    $last_timestamp_dehumidifier = $dehumidifier_timestamps[count($dehumidifier_timestamps)-1];
    $first_timestamp_dehumidifier = get_defined_last_timestamp_from_array($dehumidifier_timestamps, $diagram_mode);
    $dehumidifier_timestamps_axis = get_timestamps_for_time_axis($dehumidifier_timestamps, $first_timestamp_dehumidifier);
    $dehumidifier_timestamps_axis_text = get_text_array_for_time_axis($dehumidifier_timestamps_axis);
    $dehumidifier_dataset = get_dataset_of_values($dehumidifier_values, $dehumidifier_timestamps_axis);
    
    $exhaust_air_values = get_diagram_values($status_exhaust_air_table);
    $exhaust_air_timestamps = array_keys($exhaust_air_values);
    $last_timestamp_exhaust_air = $exhaust_air_timestamps[count($exhaust_air_timestamps)-1];
    $first_timestamp_exhaust_air = get_defined_last_timestamp_from_array($exhaust_air_timestamps, $diagram_mode);
    $exhaust_air_timestamps_axis = get_timestamps_for_time_axis($exhaust_air_timestamps, $first_timestamp_exhaust_air);
    $exhaust_air_timestamps_axis_text = get_text_array_for_time_axis($exhaust_air_timestamps_axis);
    $exhaust_air_dataset = get_dataset_of_values($exhaust_air_values, $exhaust_air_timestamps_axis);
    
    $circulate_air_values = get_diagram_values($status_circulating_air_table);
    $circulate_air_timestamps = array_keys($circulate_air_values);
    $last_timestamp_circulate_air = $circulate_air_timestamps[count($circulate_air_timestamps)-1];
    $first_timestamp_circulate_air = get_defined_last_timestamp_from_array($circulate_air_timestamps, $diagram_mode);
    $circulate_air_timestamps_axis = get_timestamps_for_time_axis($circulate_air_timestamps, $first_timestamp_circulate_air);
    $circulate_air_timestamps_axis_text = get_text_array_for_time_axis($circulate_air_timestamps_axis);
    $circulate_air_dataset = get_dataset_of_values($circulate_air_values, $circulate_air_timestamps_axis);
?>