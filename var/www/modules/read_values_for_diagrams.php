<?php 
    function get_defined_first_timestamp($last_timestamp, $delta){
        global $customtime;
        Switch ($delta){
            case 'hour':
                return $last_timestamp - 3600;
            case 'day':
                return $last_timestamp - 86400;
            case 'week':
                return $last_timestamp - 604800;
            case 'month':
                return $last_timestamp - 2629700;
            case 'custom':
                return $last_timestamp - $customtime;
        }
        logger('DEBUG', 'get_defined_first_timestamp_from_array performed');
    }
    
    function get_timestamps_for_time_axis($timestamps){
        global $first_timestamp_diagram;
        $timestamps_axis = array();
        foreach ($timestamps as $current_timestamp){
            if ($current_timestamp >= $first_timestamp_diagram){
                $timestamps_axis[] = $current_timestamp;
            }
        }
        logger('DEBUG', 'get_timestamp_for_time_axis performed');
        return $timestamps_axis;
    }
    
    function get_text_array_for_time_axis($timestamps_axis){
        $axis_text = '[';
        foreach ($timestamps_axis as $timestamp){
            $axis_text = $axis_text . 'new Date(' .$timestamp . '000),';
        }
        $axis_text = substr($axis_text, 0, -1);
        $axis_text = $axis_text . ']';
        logger('DEBUG', 'get_text_array_for_time_axis performed');
        return $axis_text;
    }

    function get_dataset_of_values($datavalues, $timestamps_axis){
        $dataset = array_values($datavalues);
        logger('DEBUG', 'get_dataset_of_values performed');
        return $dataset;
    }

    function duplicate_last_value_in_array($array, $added_timestamp){
        $last_value = end($array);
        $array[$added_timestamp] = $last_value;
        logger('DEBUG', 'duplicate_last_value_in_array performed');
        return $array;
    }
    
    function add_current_time_in_array($array){
        
        $new_time = get_current_time();
        $array[] = $new_time;
        logger('DEBUG', 'add_current_time_in_array performed');
        return $array;
    }
    function get_synchronized_data_to_diagram_mode($data_dict_array, $timestamp_array){
        global $last_timestamp_diagram;
        global $first_timestamp_diagram;
        if (end($timestamp_array) < $first_timestamp_diagram) {
            $new_data_dict_array[$first_timestamp_diagram] = $data_dict_array[end($timestamp_array)];
            $new_data_dict_array[$last_timestamp_diagram] = $data_dict_array[end($timestamp_array)];
        }
        
    }
    
    function get_intermediate_value($first_value, $second_value, $first_timestamp, $second_timestamp) {
        global $first_timestamp_diagram;
        # Zwischenwert für ersten Timestamp errechnen
        if ($first_value == Null || $second_value == Null) {
            return Null;
        }
        $teiler = ($first_timestamp_diagram - $first_timestamp) / ($second_timestamp - $first_timestamp);
        $intermediate_value = $first_value + (($second_value - $first_value) / $teiler);
        return $intermediate_value;
    }
    
    function get_timestamps_with_values_for_missing_data($data_values, $timestamp_count_in_diagram, $is_OnOff_value){
        global $first_timestamp_diagram;
        global $last_timestamp_diagram;
        // echo ("First Timestamp: " . $first_timestamp_diagram . "Last Timestamp: " . $last_timestamp_diagram . "<br>");
        $intermediate_timestamp_diagram = ($first_timestamp_diagram + $last_timestamp_diagram) / 2;
        $timestamps_in_db = array_keys($data_values);
        $last_timestamp_in_db = end($timestamps_in_db);
        $timestamp_value_dict = array();
        if ($timestamp_count_in_diagram == 0) {
            if ($is_OnOff_value) {
                $timestamp_value_dict[$first_timestamp_diagram] = $data_values[$last_timestamp_in_db];
                // $timestamp_value_dict[$intermediate_timestamp_diagram] = $data_values[$last_timestamp_in_db];
                $timestamp_value_dict[$last_timestamp_diagram] = $data_values[$last_timestamp_in_db];
            }
            else {
                $timestamp_value_dict[$first_timestamp_diagram] = Null;
                // $timestamp_value_dict[$intermediate_timestamp_diagram] = Null;
                $timestamp_value_dict[$last_timestamp_diagram] = Null;
            }
        }
        elseif ($timestamp_count_in_diagram == 1) {
            $count_timestamps = count(array_keys($data_values));
            if ($is_OnOff_value) {
                if ($count_timestamps <= 1){
                    $timestamp_value_dict[$first_timestamp_diagram] = Null;
                    $timestamp_value_dict[$last_timestamp_diagram] = array_values($data_values)[0];
                }
                else {
                    $timestamp_value_dict[$first_timestamp_diagram] = array_values($data_values)[$count_timestamps-2]; # vorletzter Wert in DB
                }
                $timestamp_value_dict[$last_timestamp_in_db] = $data_values[$last_timestamp_in_db];
                $timestamp_value_dict[$last_timestamp_diagram] = $data_values[$last_timestamp_in_db];
            }
            else {
                if ($count_timestamps <= 1){
                    $timestamp_value_dict[$first_timestamp_diagram] = Null;
                }
                else {
                    $timestamp_value_dict[$first_timestamp_diagram] = get_intermediate_value(array_values($data_values)[$count_timestamps-2], $data_values[$last_timestamp_in_db], array_keys($data_values)[$count_timestamps-2], $last_timestamp_in_db);
                }
                $timestamp_value_dict[$last_timestamp_in_db] = $data_values[$last_timestamp_in_db];
                $timestamp_value_dict[$last_timestamp_diagram] = Null;
            }
        }
        else {
            # Zwischenwert für First_Timestamp errechnen?
            $timestamps = array_keys($data_values);
            $timestamp_value_dict[$first_timestamp_diagram] = Null;
            foreach ($timestamps as $timestamp){
                if ($timestamp >= $first_timestamp_diagram){
                    $timestamp_value_dict[$timestamp] = $data_values[$timestamp];
                }
            }
            $count_all_values = count($data_values);
            $count_diagram_values = count($timestamp_value_dict);
            if ($count_all_values > $count_diagram_values){
                $wanted_index = $count_all_values - $count_diagram_values - 2;
                if ($wanted_index < 0){
                    $wanted_index = 0;
                }
                $wanted_timestamp = array_keys($data_values)[$wanted_index];
                // $timestamp_value_dict[$first_timestamp_diagram] = $data_values[$wanted_timestamp];
                if ($is_OnOff_value){
                    $timestamp_value_dict[$first_timestamp_diagram] = $data_values[$wanted_timestamp];
                }
                else {
                    $timestamp_value_dict[$first_timestamp_diagram] = get_intermediate_value($data_values[$wanted_timestamp], array_values($timestamp_value_dict)[1], $wanted_timestamp, array_keys($timestamp_value_dict)[1]);
                }
                // reset($timestamp_value_dict);
                // print ("current(timestamp_value_dict): " . strval(current($timestamp_value_dict)) . "<br>");
                // print ("data_values[wanted_timestamp]: " . $data_values[$wanted_timestamp] . "<br>");
                // print ("first_timestamp_diagram: " . $first_timestamp_diagram . "<br>");
            }
            else{
                $timestamp_value_dict[$first_timestamp_diagram] = Null;
            }
            // $timestamp_value_dict[$first_timestamp_diagram] = Null;
            // $timestamp_value_dict[$last_timestamp_diagram] = $data_values[end($timestamps)];
            if ($is_OnOff_value) {
                $timestamp_value_dict[$last_timestamp_diagram] = $data_values[end($timestamps)];
            }
            else {
                $timestamp_value_dict[$last_timestamp_diagram] = Null;
            }
        }
        return $timestamp_value_dict;
    }

    function get_data_for_diagram($data_values, $is_OnOff_value){
        $timestamps = array_keys($data_values);
        $timestamps_axis = get_timestamps_for_time_axis($timestamps);
        $values_diagram = get_timestamps_with_values_for_missing_data($data_values,count($timestamps_axis), $is_OnOff_value);
        $timestamps_axis_text = get_text_array_for_time_axis(array_keys($values_diagram));
        $dataset = get_dataset_of_values($values_diagram, $timestamps_axis);
        // print "timestamps_axis: " . count($timestamps_axis) . "<br>";
        // print "values_diagram: " . count($values_diagram) . "<br>";
        // print "dataset: " . count($dataset) . "<br>";
        
        $return_array = array($timestamps_axis_text, $dataset);
        return $return_array;
    }

    
    function get_data_for_sensor_diagram($data_values){
        global $last_timestamp_diagram;
        global $first_timestamp_diagram;
        
        $timestamp_value_dict = array();
        $timestamps = array_keys($data_values);
        $timestamp_value_dict[$first_timestamp_diagram] = Null;
        
        if (count($timestamps) > 0) {
            foreach ($timestamps as $timestamp){
                if ($timestamp >= $first_timestamp_diagram){
                    $timestamp_value_dict[$timestamp] = $data_values[$timestamp];
                }
            }
        }
        $timestamp_value_dict[$last_timestamp_diagram] = Null;
        $timestamps = array_keys($timestamp_value_dict);
        $timestamps_axis_text = get_text_array_for_time_axis($timestamps);
        $dataset = array_values($timestamp_value_dict);
        // print "dataset: " . count($dataset) . "<br>";
        // print "timestamps_axis_text: " . $timestamps_axis_text . "<br>";
        // echo '<pre>';
        // var_dump($dataset);
        // echo '</pre>';
        $return_array = array($timestamps_axis_text, $dataset, $timestamps);
        return $return_array;
    }   
    
    // reconstruct NTC diagram with timebase from ref_timestamps derived from temperature or humidity data
    function get_data_for_NTC_diagram($data_values, $ref_timestamps){
        
        $timestamp_value_dict = array();
        $timestamps = array_keys($data_values);
        // preload timestamp_value_dict with ref_timestamps and values set to Null
        foreach ($ref_timestamps as $ref_timestamp) {
            $timestamp_value_dict[$ref_timestamp] = Null;
        }
        // now fill timestamp_value_dict with values from data_values
        if (count($timestamps) > 0) {
            foreach ($timestamps as $timestamp){
                $timestamp_value_dict[$timestamp] = $data_values[$timestamp];
            }
        }
        
        $timestamps_axis_text = get_text_array_for_time_axis($ref_timestamps);
        $dataset = array_values($timestamp_value_dict);
        // print "dataset: " . count($dataset) . "<br>";
        // print "timestamps_axis_text: " . $timestamps_axis_text . "<br>";
        // echo '<pre>';
        // var_dump($dataset);
        // echo '</pre>';
        $return_array = array($timestamps_axis_text, $dataset);
        return $return_array;
    }
    
    global $last_timestamp_diagram;
    global $first_timestamp_diagram;
    $customtime = intval(get_table_value($config_settings_table, $customtime_for_diagrams_key));
    $last_timestamp_diagram = get_current_time();
    $first_timestamp_diagram = get_defined_first_timestamp($last_timestamp_diagram, $diagram_mode);
    
 
    $meat4_sensortype = get_table_value($config_settings_table, $meat4_sensortype_key);
    $config_sensor4 = get_meatsensor_table_row( $meat4_sensortype );
    
    $sensor4_is_current = false;
    $sensor4_current_mode = 'AC';
    if (strncmp($config_sensor4['name'], 'LEM', 3) === 0)
    {
        $sensor4_is_current = true;
        if ($config_sensor4['Mode'] == 'DC')
        {
            $sensor4_current_mode = 'DC';   
        }            
    }

    
    // echo "Temperatur<br>";
    $nth_value = 1;
    if ($diagram_mode == 'month') {
        $nth_value = 15;
    }
    elseif ($diagram_mode == 'week') {
        $nth_value = 4;
    }
    elseif ($diagram_mode == 'custom') {
        $min_time_window = 604800 / 4;  // if time windows <= 1/4 week then nth = 1
        if ($customtime > $min_time_window) {   
            $nth_value =  intval($customtime / $min_time_window);
            if ($nth_value < 1) {
                $nth_value = 1;
            }
        }
    }
    
    
    $temperature_values = get_diagram_values_range($data_sensor_temperature_table, $nth_value, $first_timestamp_diagram, $last_timestamp_diagram);
    //$is_OnOff_value = False;
    $temperature_data_diagram = get_data_for_sensor_diagram($temperature_values);
    $temperature_timestamps_axis_text = $temperature_data_diagram[0];
    $temperature_dataset = $temperature_data_diagram[1];
    
    $ref_timestamps = $temperature_data_diagram[2];
    
    // $temperature_timestamps = array_keys($temperature_values);
    // $temperature_timestamps_axis = get_timestamps_for_time_axis($temperature_timestamps);
    // $temperature_values_diagram = get_timestamps_with_values_for_missing_data($temperature_values,count($temperature_timestamps_axis))
    // $temperature_timestamps_axis_text = get_text_array_for_time_axis(array_keys($temperature_values_diagram));
    // $temperature_dataset = get_dataset_of_values($temperature_values_diagram, $temperature_timestamps_axis);
    
    // echo "humidity_values<br>";
    $humidity_values = get_diagram_values_range($data_sensor_humidity_table, $nth_value, $first_timestamp_diagram, $last_timestamp_diagram);
    //$is_OnOff_value = False;
    $humidity_data_diagram = get_data_for_sensor_diagram($humidity_values);
    $humidity_timestamps_axis_text = $humidity_data_diagram[0];
    $humidity_dataset = $humidity_data_diagram[1];
    
    // $humidity_values = get_diagram_values($data_sensor_humidity_table);
    // $humidity_timestamps = array_keys($humidity_values);
    // $last_timestamp_humidity = end($humidity_timestamps);
    // $first_timestamp_humidity = get_defined_first_timestamp_from_array($last_timestamp_humidity, $diagram_mode);
    // $humidity_timestamps_axis = get_timestamps_for_time_axis($humidity_timestamps, $first_timestamp_humidity);
    // $humidity_timestamps_axis_text = get_text_array_for_time_axis($humidity_timestamps_axis);
    // $humidity_dataset = get_dataset_of_values($humidity_values, $humidity_timestamps_axis);

//-------------------------------------- neuHM


    // echo "dewpoint_values<br>";
    $dewpoint_values = get_diagram_values_range($data_sensor_dewpoint_table, $nth_value, $first_timestamp_diagram, $last_timestamp_diagram);
    //$is_OnOff_value = False;
    $dewpoint_data_diagram = get_data_for_sensor_diagram($dewpoint_values);
    $dewpoint_timestamps_axis_text = $dewpoint_data_diagram[0];
    $dewpoint_dataset = $dewpoint_data_diagram[1];


    // echo "temperature_extern_values<br>";
    $extern_temperature_values = get_diagram_values_range($data_sensor_extern_temperature_table, $nth_value, $first_timestamp_diagram, $last_timestamp_diagram);
    //$is_OnOff_value = False;
    $extern_temperature_data_diagram = get_data_for_sensor_diagram($extern_temperature_values);
    $extern_temperature_timestamps_axis_text = $extern_temperature_data_diagram[0];
    $extern_temperature_dataset = $extern_temperature_data_diagram[1];
    
    
    // echo "humidity_extern_values<br>";
    $extern_humidity_values = get_diagram_values_range($data_sensor_extern_humidity_table, $nth_value, $first_timestamp_diagram, $last_timestamp_diagram);
    //$is_OnOff_value = False;
    $extern_humidity_data_diagram = get_data_for_sensor_diagram($extern_humidity_values);
    $extern_humidity_timestamps_axis_text = $extern_humidity_data_diagram[0];
    $extern_humidity_dataset = $extern_humidity_data_diagram[1];


    // echo "dewpoint_extern_values<br>";
    $extern_dewpoint_values = get_diagram_values_range($data_sensor_extern_dewpoint_table, $nth_value, $first_timestamp_diagram, $last_timestamp_diagram);
    //$is_OnOff_value = False;
    $extern_dewpoint_data_diagram = get_data_for_sensor_diagram($extern_dewpoint_values);
    $extern_dewpoint_timestamps_axis_text = $extern_dewpoint_data_diagram[0];
    $extern_dewpoint_dataset = $extern_dewpoint_data_diagram[1];


//----------------------------------------neuHM
    
    // echo "scale1_values<br>";
    $scale1_values = get_diagram_values_range($data_scale1_table, $nth_value, $first_timestamp_diagram, $last_timestamp_diagram);
    //$is_OnOff_value = False;
    $scale1_data_diagram = get_data_for_sensor_diagram($scale1_values);
    $scale1_timestamps_axis_text = $scale1_data_diagram[0];
    $scale1_dataset = $scale1_data_diagram[1];
    
    // $scale1_values = get_diagram_values($data_scale1_table);
    // $scale1_timestamps = array_keys($scale1_values);
    // $last_timestamp_scale1 = end($scale1_timestamps);
    // $first_timestamp_scale1 = get_defined_first_timestamp_from_array($last_timestamp_scale1, $diagram_mode);
    // $scale1_timestamps_axis = get_timestamps_for_time_axis($scale1_timestamps, $first_timestamp_scale1);
    // $scale1_timestamps_axis_text = get_text_array_for_time_axis($scale1_timestamps_axis);
    // $scale1_dataset = get_dataset_of_values($scale1_values, $scale1_timestamps_axis);
    
    // echo "scale2_values<br>";
    $scale2_values = get_diagram_values_range($data_scale2_table, $nth_value, $first_timestamp_diagram, $last_timestamp_diagram);
    //$is_OnOff_value = False;
    $scale2_data_diagram = get_data_for_sensor_diagram($scale2_values);
    $scale2_timestamps_axis_text = $scale2_data_diagram[0];
    $scale2_dataset = $scale2_data_diagram[1];
    
    // $scale2_values = get_diagram_values($data_scale2_table);
    // $scale2_timestamps = array_keys($scale2_values);
    // $last_timestamp_scale2 = end($scale2_timestamps);
    // $first_timestamp_scale2 = get_defined_first_timestamp_from_array($last_timestamp_scale2, $diagram_mode);
    // $scale2_timestamps_axis = get_timestamps_for_time_axis($scale2_timestamps, $first_timestamp_scale2);
    // $scale2_timestamps_axis_text = get_text_array_for_time_axis($scale2_timestamps_axis);
    // $scale2_dataset = get_dataset_of_values($scale2_values, $scale2_timestamps_axis);
    
    // echo "thermometer1_values<br>";
    $thermometer1_values = get_diagram_values_range($data_sensor_temperature_meat1_table, $nth_value, $first_timestamp_diagram, $last_timestamp_diagram);
    //$is_OnOff_value = False;
    $thermometer1_data_diagram = get_data_for_NTC_diagram($thermometer1_values, $ref_timestamps);
    $thermometer1_timestamps_axis_text = $thermometer1_data_diagram[0];
    $thermometer1_dataset = $thermometer1_data_diagram[1];
    
    // echo "thermometer2_values<br>";
    $thermometer2_values = get_diagram_values_range($data_sensor_temperature_meat2_table, $nth_value, $first_timestamp_diagram, $last_timestamp_diagram);
    //$is_OnOff_value = False;
    $thermometer2_data_diagram = get_data_for_NTC_diagram($thermometer2_values, $ref_timestamps);
    $thermometer2_timestamps_axis_text = $thermometer2_data_diagram[0];
    $thermometer2_dataset = $thermometer2_data_diagram[1];
    
    // echo "thermometer3_values<br>";
    $thermometer3_values = get_diagram_values_range($data_sensor_temperature_meat3_table, $nth_value, $first_timestamp_diagram, $last_timestamp_diagram);
    //$is_OnOff_value = False;
    $thermometer3_data_diagram = get_data_for_NTC_diagram($thermometer3_values, $ref_timestamps);
    $thermometer3_timestamps_axis_text = $thermometer3_data_diagram[0];
    $thermometer3_dataset = $thermometer3_data_diagram[1];
    
    // echo "thermometer4_values<br>";
    $thermometer4_values = get_diagram_values_range($data_sensor_temperature_meat4_table, $nth_value, $first_timestamp_diagram, $last_timestamp_diagram);
    //$is_OnOff_value = False;
    $thermometer4_data_diagram = get_data_for_NTC_diagram($thermometer4_values, $ref_timestamps);
    $thermometer4_timestamps_axis_text = $thermometer4_data_diagram[0];
    $thermometer4_dataset = $thermometer4_data_diagram[1];
    
    // echo "uv_light_values<br>";
    $uv_light_values = get_diagram_values($status_uv_table, 1);
    $is_OnOff_value = True;
    $uv_light_data_diagram = get_data_for_diagram($uv_light_values, $is_OnOff_value);
    $uv_light_timestamps_axis_text = $uv_light_data_diagram[0];
    $uv_light_dataset = $uv_light_data_diagram[1];
    
    // $uv_light_values = get_diagram_values($status_uv_table);
    // $uv_light_timestamps = array_keys($uv_light_values);
    // $last_timestamp_uv_light = end($uv_light_timestamps);
    // $first_timestamp_uv_light = get_defined_first_timestamp_from_array($last_timestamp_uv_light, $diagram_mode);
    
    // $uv_light_timestamps_with_duplicated_last = add_current_time_in_array($uv_light_timestamps);
    // $uv_light_values_with_duplicated_last = duplicate_last_value_in_array($uv_light_values, end($uv_light_timestamps_with_duplicated_last));
    
    // $uv_light_timestamps_axis = get_timestamps_for_time_axis($uv_light_timestamps_with_duplicated_last, $first_timestamp_uv_light);
    // $uv_light_timestamps_axis_text = get_text_array_for_time_axis($uv_light_timestamps_axis);
    // $uv_light_dataset = get_dataset_of_values($uv_light_values_with_duplicated_last, $uv_light_timestamps_axis);
  
    // echo "light_values<br>";
    $light_values = get_diagram_values($status_light_table, 1);
    $is_OnOff_value = True;
    $light_data_diagram = get_data_for_diagram($light_values, $is_OnOff_value);
    $light_timestamps_axis_text = $light_data_diagram[0];
    $light_dataset = $light_data_diagram[1];
    
    // $light_values = get_diagram_values($status_light_table);
    // $light_timestamps = array_keys($light_values);
    // $last_timestamp_light = end($light_timestamps);
    // $first_timestamp_light = get_defined_first_timestamp_from_array($last_timestamp_light, $diagram_mode);
    // $light_timestamps_with_duplicated_last = add_current_time_in_array($light_timestamps);
    // $light_values_with_duplicated_last = duplicate_last_value_in_array($light_values, end($light_timestamps_with_duplicated_last));
    // $light_timestamps_axis = get_timestamps_for_time_axis($light_timestamps_with_duplicated_last, $first_timestamp_light);
    // $light_timestamps_axis_text = get_text_array_for_time_axis($light_timestamps_axis);
    // $light_dataset = get_dataset_of_values($light_values_with_duplicated_last, $light_timestamps_axis);
    
    // echo "heater_values<br>";
    $heater_values = get_diagram_values($status_heater_table, 1);
    $is_OnOff_value = True;
    $heater_data_diagram = get_data_for_diagram($heater_values, $is_OnOff_value);
    $heater_timestamps_axis_text = $heater_data_diagram[0];
    $heater_dataset = $heater_data_diagram[1];
    
    // $heater_values = get_diagram_values($status_heater_table);
    // $heater_timestamps = array_keys($heater_values);
    // $last_timestamp_heater = end($heater_timestamps);
    // $first_timestamp_heater = get_defined_first_timestamp_from_array($last_timestamp_heater, $diagram_mode);
    // $heater_timestamps_axis = get_timestamps_for_time_axis($heater_timestamps, $first_timestamp_heater);
    // $heater_timestamps_axis_text = get_text_array_for_time_axis($heater_timestamps_axis);
    // $heater_dataset = get_dataset_of_values($heater_values, $heater_timestamps_axis);
    
    // echo "cooler_values<br>";
    $cooler_values = get_diagram_values($status_cooling_compressor_table, 1);
    $is_OnOff_value = True;
    $cooler_data_diagram = get_data_for_diagram($cooler_values, $is_OnOff_value);
    $cooler_timestamps_axis_text = $cooler_data_diagram[0];
    $cooler_dataset = $cooler_data_diagram[1];
    
    // $cooler_values = get_diagram_values($status_cooling_compressor_table);
    // $cooler_timestamps = array_keys($cooler_values);
    // $last_timestamp_cooler = end($cooler_timestamps);
    // $first_timestamp_cooler = get_defined_first_timestamp_from_array($last_timestamp_cooler, $diagram_mode);
    // $cooler_timestamps_axis = get_timestamps_for_time_axis($cooler_timestamps, $first_timestamp_cooler);
    // $cooler_timestamps_axis_text = get_text_array_for_time_axis($cooler_timestamps_axis);
    // $cooler_dataset = get_dataset_of_values($cooler_values, $cooler_timestamps_axis);
    
    // echo "humidifier_values<br>";
    $humidifier_values = get_diagram_values($status_humidifier_table, 1);
    $is_OnOff_value = True;
    $humidifier_data_diagram = get_data_for_diagram($humidifier_values, $is_OnOff_value);
    $humidifier_timestamps_axis_text = $humidifier_data_diagram[0];
    $humidifier_dataset = $humidifier_data_diagram[1];
    
    // $humidifier_values = get_diagram_values($status_humidifier_table);
    // $humidifier_timestamps = array_keys($humidifier_values);
    // $last_timestamp_humidifier = end($humidifier_timestamps);
    // $first_timestamp_humidifier = get_defined_first_timestamp_from_array($last_timestamp_humidifier, $diagram_mode);
    // $humidifier_timestamps_axis = get_timestamps_for_time_axis($humidifier_timestamps, $first_timestamp_humidifier);
    // $humidifier_timestamps_axis_text = get_text_array_for_time_axis($humidifier_timestamps_axis);
    // $humidifier_dataset = get_dataset_of_values($humidifier_values, $humidifier_timestamps_axis);
    
    // echo "dehumidifier_values<br>";
    $dehumidifier_values = get_diagram_values($status_dehumidifier_table, 1);
    $is_OnOff_value = True;
    $dehumidifier_data_diagram = get_data_for_diagram($dehumidifier_values, $is_OnOff_value);
    $dehumidifier_timestamps_axis_text = $dehumidifier_data_diagram[0];
    $dehumidifier_dataset = $dehumidifier_data_diagram[1];
    
    // $dehumidifier_values = get_diagram_values($status_dehumidifier_table);
    // $dehumidifier_timestamps = array_keys($dehumidifier_values);
    // $last_timestamp_dehumidifier = end($dehumidifier_timestamps);
    // $first_timestamp_dehumidifier = get_defined_first_timestamp_from_array($last_timestamp_dehumidifier, $diagram_mode);
    // $dehumidifier_timestamps_axis = get_timestamps_for_time_axis($dehumidifier_timestamps, $first_timestamp_dehumidifier);
    // $dehumidifier_timestamps_axis_text = get_text_array_for_time_axis($dehumidifier_timestamps_axis);
    // $dehumidifier_dataset = get_dataset_of_values($dehumidifier_values, $dehumidifier_timestamps_axis);
    
    // echo "exhaust_air_values<br>";
    $exhaust_air_values = get_diagram_values($status_exhaust_air_table, 1);
    $is_OnOff_value = True;
    $exhaust_data_diagram = get_data_for_diagram($exhaust_air_values, $is_OnOff_value);
    $exhaust_air_timestamps_axis_text = $exhaust_data_diagram[0];
    $exhaust_air_dataset = $exhaust_data_diagram[1];
    
    // $exhaust_air_values = get_diagram_values($status_exhaust_air_table);
    // $exhaust_air_timestamps = array_keys($exhaust_air_values);
    // $last_timestamp_exhaust_air = end($exhaust_air_timestamps);
    // $first_timestamp_exhaust_air = get_defined_first_timestamp_from_array($last_timestamp_exhaust_air, $diagram_mode);
    // $exhaust_air_timestamps_axis = get_timestamps_for_time_axis($exhaust_air_timestamps, $first_timestamp_exhaust_air);
    // $exhaust_air_timestamps_axis_text = get_text_array_for_time_axis($exhaust_air_timestamps_axis);
    // $exhaust_air_dataset = get_dataset_of_values($exhaust_air_values, $exhaust_air_timestamps_axis);
    
    // echo "circulate_air_values<br>";
    $circulate_air_values = get_diagram_values($status_circulating_air_table, 1);
    $is_OnOff_value = True;
    $circulate_air_data_diagram = get_data_for_diagram($circulate_air_values, $is_OnOff_value);
    $circulate_air_timestamps_axis_text = $circulate_air_data_diagram[0];
    $circulate_air_dataset = $circulate_air_data_diagram[1];
    
    // $circulate_air_values = get_diagram_values($status_circulating_air_table);
    // $circulate_air_timestamps = array_keys($circulate_air_values);
    // $last_timestamp_circulate_air = end($circulate_air_timestamps);
    // $first_timestamp_circulate_air = get_defined_first_timestamp_from_array($last_timestamp_circulate_air, $diagram_mode);
    // $circulate_air_timestamps_axis = get_timestamps_for_time_axis($circulate_air_timestamps, $first_timestamp_circulate_air);
    // $circulate_air_timestamps_axis_text = get_text_array_for_time_axis($circulate_air_timestamps_axis);
    // $circulate_air_dataset = get_dataset_of_values($circulate_air_values, $circulate_air_timestamps_axis);
    logger('DEBUG', 'read_values_for_diagrams performed');
?>
