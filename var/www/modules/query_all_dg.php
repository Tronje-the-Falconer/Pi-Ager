<?php
    include 'names.php';
    include 'database.php';
    include 'logging.php';                            //liest die Datei fuer das logging ein
   
    function get_defined_first_timestamp($last_timestamp, $delta){
        global $customtime;
        switch ($delta){
            case 'hour':
                return $last_timestamp - 3600;
            case 'day':
                return $last_timestamp - 86400;
            case 'week':
                return $last_timestamp - 604800;
            case 'month':
                return $last_timestamp - 2678400;
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
    
    
    function get_dataset_of_values($datavalues, $timestamps_axis){
        $dataset = array_values($datavalues);
        logger('DEBUG', 'get_dataset_of_values performed');
        return $dataset;
    }
    
    function get_timestamps_with_values_for_missing_data($data_values, $timestamp_count_in_diagram){
        global $first_timestamp_diagram;
        global $last_timestamp_diagram;
        // echo ("First Timestamp: " . $first_timestamp_diagram . "Last Timestamp: " . $last_timestamp_diagram . "<br>");
        $timestamps_in_db = array_keys($data_values);
        $last_timestamp_in_db = end($timestamps_in_db);
        $timestamp_value_dict = array();
        if ($timestamp_count_in_diagram == 0) {
            $timestamp_value_dict[$first_timestamp_diagram] = $data_values[$last_timestamp_in_db];
            $timestamp_value_dict[$last_timestamp_diagram] = $data_values[$last_timestamp_in_db];
        }
        elseif ($timestamp_count_in_diagram == 1) {
            $count_timestamps = count(array_keys($data_values));
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
            # Wert für First_Timestamp errechnen?
            $timestamps = array_keys($data_values);
            $timestamp_value_dict[$first_timestamp_diagram] = Null;
            $current_ts = Null;
            foreach ($timestamps as $timestamp){
                if ($timestamp >= $first_timestamp_diagram){
                    $timestamp_value_dict[$timestamp] = $data_values[$timestamp];
                }
                else {
                    $current_ts = $timestamp;
                }
            }
            if ($current_ts != Null) {
                $timestamp_value_dict[$first_timestamp_diagram] = $data_values[$current_ts];
//              print("current_ts = " . $current_ts . "<br>");
            }
            
            $timestamp_value_dict[$last_timestamp_diagram] = $data_values[end($timestamps)];
        }
        return $timestamp_value_dict;
    }


    // prepare on_off data for diagrams
    function get_data_for_diagram($data_values){
        $timestamps = array_keys($data_values);
        $timestamps_axis = get_timestamps_for_time_axis($timestamps);
        $values_diagram = get_timestamps_with_values_for_missing_data($data_values, count($timestamps_axis));
        $dataset = get_dataset_of_values($values_diagram, $timestamps_axis);
        // print "timestamps_axis: " . count($timestamps_axis) . "<br>";
        // print "values_diagram: " . count($values_diagram) . "<br>";
        // print "dataset: " . count($dataset) . "<br>";
        
        $return_array = array(array_keys($values_diagram), $dataset);
        return $return_array;
    }


    
/*----------------------------------------------------------------------------*/

   
    global $last_timestamp_diagram;
    global $first_timestamp_diagram;
    global $customtime;
    
    $customtime = intval(get_table_value($config_settings_table, $customtime_for_diagrams_key));
    if ($customtime < 60) { // limit to 1 minute
        $customtime = 60;
    }
    
    $diagram_modus_index = intval(get_table_value($config_settings_table, $diagram_modus_key));
    if ($diagram_modus_index === NULL) {
        $diagram_modus_index = 0;
    }
    
    $diagram_modus_values = array('hour' => 3600, 'day' => 86400 , 'week' => 604800, 'month' => 2678400);
    $diagram_modus_values['custom'] = $customtime;

    $diagram_mode = array_keys($diagram_modus_values)[$diagram_modus_index];
    $diagram_time = array_values($diagram_modus_values)[$diagram_modus_index];

    $last_timestamp_diagram = get_current_time();
    $first_timestamp_diagram = get_defined_first_timestamp($last_timestamp_diagram, $diagram_mode);
/*
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
*/    
    //echo 'index = ' . $diagram_modus_index . ' customtime = ' . $customtime . ' diagram_mode = ' . $diagram_mode . ' diagram_time = ' . $diagram_time; 
  


    
    $save_temperature_humidity_loops = get_table_value($config_settings_table, $save_temperature_humidity_loops_key);
    $saving_period_scale1 = get_table_value($settings_scale1_table, $saving_period_key);
    $saving_period_scale2 = get_table_value($settings_scale2_table, $saving_period_key);
    // calculated temperature-humidity saving period, saving loop needs about 6s
    $temperatur_humidity_saving_period = $save_temperature_humidity_loops * 6;
    $corr_factor = 27.0/$save_temperature_humidity_loops;
    $corr_factor_scale = max($temperatur_humidity_saving_period/$saving_period_scale1, $temperatur_humidity_saving_period/$saving_period_scale2);  
    
 
    //$moving_average_window_x 
	
	$x = ($last_timestamp_diagram - $first_timestamp_diagram)/60;
  
    if ($x < 1440) {
        $moving_average_window_x = (0.14*($x)+ 21); //  x<1T
    }
    elseif ($x < 44640) {
        $moving_average_window_x = (0.042*($x)+167); // x<1Mon  
    }
    else {
        $moving_average_window_x = (1.063*($x)-44500); //  x>1Mon
    }
     

    $nth_value = 1;
    $nth_value_scale = 1;

    if ($diagram_mode == 'month') {
        $nth_value = intval(15.0 * $corr_factor);
        $nth_value_scale = intval(15.0 * $corr_factor_scale);
    }
    elseif ($diagram_mode == 'week') {
        $nth_value = intval(4.0 * $corr_factor);
        $nth_value_scale = intval(4.0 * $corr_factor_scale);
    }
    elseif ($diagram_mode == 'custom') {
        $min_time_window = 604800 / 4;  // if time windows <= 1/4 week then nth = 1
        if ($customtime > $min_time_window) {   
            $nth_value =  intval($customtime / $min_time_window * $corr_factor);
            if ($nth_value < 1) {
                $nth_value = 1;
            }
            $nth_value_scale =  intval($customtime / $min_time_window * $corr_factor_scale);
            if ($nth_value_scale < 1) {
                $nth_value_scale = 1;
            }            
        }
     }
     
    // make nth_value_scale odd-numbered
    if ($nth_value_scale > 1 and (($nth_value_scale % 2) == 0)) {
        $nth_value_scale -= 1;
    }
    	
        
    // reading all_sensors_table
    $all_sensors_rows = get_allsensors_dataset($nth_value, $first_timestamp_diagram, $last_timestamp_diagram);
    // reading all_scales_table
    $all_scales_rows = get_allscales_dataset($nth_value_scale, $first_timestamp_diagram, $last_timestamp_diagram);

    // common x-axis timestamps for all_sensors
    $all_sensors_timestamps_array = array_column($all_sensors_rows, 'last_change');
    $all_sensors_timestamps_array[] = $last_timestamp_diagram;
    array_unshift($all_sensors_timestamps_array, $first_timestamp_diagram);
    
    // common x-axis timestamps for all_scales
    $all_scales_timestamps_array = array_column($all_scales_rows, 'last_change');
    $all_scales_timestamps_array[] = $last_timestamp_diagram;
    array_unshift($all_scales_timestamps_array, $first_timestamp_diagram);
       
    // value array for tempint
    $temperature_dataset = array_column($all_sensors_rows, 'tempint');
    $temperature_dataset[] = Null;
    array_unshift($temperature_dataset, Null);
//    $temperature_avg_dataset = moving_average_filter($all_sensors_timestamps_array, $temperature_dataset, $moving_average_window_x);
    
    // value array for humint
    $humidity_dataset = array_column($all_sensors_rows, 'humint');
    $humidity_dataset[] = Null;
    array_unshift($humidity_dataset, Null);
//    $humidity_avg_dataset = moving_average_filter($all_sensors_timestamps_array, $humidity_dataset, $moving_average_window_x);

    // value array for dewint
    $dewpoint_dataset = array_column($all_sensors_rows, 'dewint');
    $dewpoint_dataset[] = Null;
    array_unshift($dewpoint_dataset, Null);

    // value array for humintabs
    $humidity_abs_dataset = array_column($all_sensors_rows, 'humintabs');
    $humidity_abs_dataset[] = Null;
    array_unshift($humidity_abs_dataset, Null);
         
    // value array for tempext
    $extern_temperature_dataset = array_column($all_sensors_rows, 'tempext');
    $extern_temperature_dataset[] = Null;
    array_unshift($extern_temperature_dataset, Null);
    //$extern_temperature_dataset = moving_average_filter($all_sensors_timestamps_array, $extern_temperature_dataset, $moving_average_window_x);

    // value array for humext
    $extern_humidity_dataset = array_column($all_sensors_rows, 'humext');
    $extern_humidity_dataset[] = Null;
    array_unshift($extern_humidity_dataset, Null);
//    $extern_humidity_dataset = moving_average_filter($all_sensors_timestamps_array, $extern_humidity_dataset, $moving_average_window_x);
    
    // value array for dewext
    $extern_dewpoint_dataset = array_column($all_sensors_rows, 'dewext');
    $extern_dewpoint_dataset[] = Null;
    array_unshift($extern_dewpoint_dataset, Null);           

    // value array for humextabs
    $extern_humidity_abs_dataset = array_column($all_sensors_rows, 'humextabs');
    $extern_humidity_abs_dataset[] = Null;
    array_unshift($extern_humidity_abs_dataset, Null);
    
    // value array for ntc1
    $thermometer1_dataset = array_column($all_sensors_rows, 'ntc1');
    $thermometer1_dataset[] = Null;
    array_unshift($thermometer1_dataset, Null);
    
    // value array for ntc2
    $thermometer2_dataset = array_column($all_sensors_rows, 'ntc2');
    $thermometer2_dataset[] = Null;
    array_unshift($thermometer2_dataset, Null);
    
    // value array for ntc3
    $thermometer3_dataset = array_column($all_sensors_rows, 'ntc3');
    $thermometer3_dataset[] = Null;
    array_unshift($thermometer3_dataset, Null);
    
    // value array for ntc4
    $thermometer4_dataset = array_column($all_sensors_rows, 'ntc4');
    $thermometer4_dataset[] = Null;
    array_unshift($thermometer4_dataset, Null);

    // value array for scale1
    $scale1_dataset = array_column($all_scales_rows, 'scale1');
    $scale1_dataset[] = Null;
    array_unshift($scale1_dataset, Null);

    // value array for scale2
    $scale2_dataset = array_column($all_scales_rows, 'scale2');
    $scale2_dataset[] = Null;
    array_unshift($scale2_dataset, Null);

    // echo "uv_light_values<br>";
    $uv_light_values = get_diagram_values($status_uv_table, 1);
    $uv_light_data_diagram = get_data_for_diagram($uv_light_values);
    $uv_light_timestamps_axis = $uv_light_data_diagram[0];
    $uv_light_dataset = $uv_light_data_diagram[1];
 
    // echo "light_values<br>";
    $light_values = get_diagram_values($status_light_table, 1);
    $light_data_diagram = get_data_for_diagram($light_values);
    $light_timestamps_axis = $light_data_diagram[0];
    $light_dataset = $light_data_diagram[1];

    // echo "heater_values<br>";
    $heater_values = get_diagram_values($status_heater_table, 1);
    $heater_data_diagram = get_data_for_diagram($heater_values);
    $heater_timestamps_axis = $heater_data_diagram[0];
    $heater_dataset = $heater_data_diagram[1];
    
    // echo "cooler_values<br>";
    $cooler_values = get_diagram_values($status_cooling_compressor_table, 1);
    $cooler_data_diagram = get_data_for_diagram($cooler_values);
    $cooler_timestamps_axis = $cooler_data_diagram[0];
    $cooler_dataset = $cooler_data_diagram[1];

    // echo "humidifier_values<br>";
    $humidifier_values = get_diagram_values($status_humidifier_table, 1);
    $humidifier_data_diagram = get_data_for_diagram($humidifier_values);
    $humidifier_timestamps_axis = $humidifier_data_diagram[0];
    $humidifier_dataset = $humidifier_data_diagram[1];

    // echo "dehumidifier_values<br>";
    $dehumidifier_values = get_diagram_values($status_dehumidifier_table, 1);
    $dehumidifier_data_diagram = get_data_for_diagram($dehumidifier_values);
    $dehumidifier_timestamps_axis = $dehumidifier_data_diagram[0];
    $dehumidifier_dataset = $dehumidifier_data_diagram[1];

    // echo "exhaust_air_values<br>";
    $exhaust_air_values = get_diagram_values($status_exhaust_air_table, 1);
    $exhaust_data_diagram = get_data_for_diagram($exhaust_air_values);
    $exhaust_air_timestamps_axis = $exhaust_data_diagram[0];
    $exhaust_air_dataset = $exhaust_data_diagram[1];

    // echo "circulate_air_values<br>";
    $circulate_air_values = get_diagram_values($status_circulating_air_table, 1);
    $circulate_air_data_diagram = get_data_for_diagram($circulate_air_values);
    $circulate_air_timestamps_axis = $circulate_air_data_diagram[0];
    $circulate_air_dataset = $circulate_air_data_diagram[1];
    
  

    // collect data for diagrams
    $current_values = array();
    $current_values['diagram_time'] = $diagram_time;

    $current_values['all_sensors_timestamps_axis'] = $all_sensors_timestamps_array;
    $current_values['all_scales_timestamps_axis'] = $all_scales_timestamps_array;
    $current_values['temperature_dataset'] = $temperature_dataset;
    $current_values['humidity_dataset'] = $humidity_dataset;
    $current_values['extern_temperature_dataset'] = $extern_temperature_dataset;
    $current_values['extern_humidity_dataset'] = $extern_humidity_dataset;
    $current_values['dewpoint_dataset'] = $dewpoint_dataset;
    $current_values['extern_dewpoint_dataset'] = $extern_dewpoint_dataset; 
    $current_values['humidity_abs_dataset'] = $humidity_abs_dataset;
    $current_values['extern_humidity_abs_dataset'] = $extern_humidity_abs_dataset; 
    $current_values['thermometer1_dataset'] = $thermometer1_dataset;
    $current_values['thermometer2_dataset'] = $thermometer2_dataset;    
    $current_values['thermometer3_dataset'] = $thermometer3_dataset;    
    $current_values['thermometer4_dataset'] = $thermometer4_dataset;

    
    $current_values['uv_light_timestamps_axis'] = $uv_light_timestamps_axis;  
    $current_values['uv_light_dataset'] = $uv_light_dataset;    
    $current_values['light_timestamps_axis'] = $light_timestamps_axis;  
    $current_values['light_dataset'] = $light_dataset;    
    $current_values['heater_timestamps_axis'] = $heater_timestamps_axis;  
    $current_values['heater_dataset'] = $heater_dataset;    
    $current_values['cooler_timestamps_axis'] = $cooler_timestamps_axis;  
    $current_values['cooler_dataset'] = $cooler_dataset;    
    $current_values['humidifier_timestamps_axis'] = $humidifier_timestamps_axis;  
    $current_values['humidifier_dataset'] = $humidifier_dataset;    
    $current_values['dehumidifier_timestamps_axis'] = $dehumidifier_timestamps_axis;  
    $current_values['dehumidifier_dataset'] = $dehumidifier_dataset;    
    $current_values['exhaust_air_timestamps_axis'] = $exhaust_air_timestamps_axis;  
    $current_values['exhaust_air_dataset'] = $exhaust_air_dataset;    
    $current_values['circulate_air_timestamps_axis'] = $circulate_air_timestamps_axis;  
    $current_values['circulate_air_dataset'] = $circulate_air_dataset;    

    $current_values['scale1_dataset'] = $scale1_dataset;
    $current_values['scale2_dataset'] = $scale2_dataset;
    
    echo json_encode($current_values);    
    // encode data and return it to client
    //echo json_encode($current_values);

    logger('DEBUG', 'query_all_dg performed');

?>
