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
//        $timestamps_axis_text = get_text_array_for_time_axis(array_keys($values_diagram));
        $dataset = get_dataset_of_values($values_diagram, $timestamps_axis);
        // print "timestamps_axis: " . count($timestamps_axis) . "<br>";
        // print "values_diagram: " . count($values_diagram) . "<br>";
        // print "dataset: " . count($dataset) . "<br>";
        
//        $return_array = array($timestamps_axis_text, $dataset);
        $return_array = array(array_keys($values_diagram), $dataset);
        return $return_array;
    }

    // filter dataset with moving average, window in minutes, if window == 0 -> filter is off
    function moving_average_filter($timestamps, $dataset, $window_minutes) {
        if ($window_minutes == 0) {
            return $dataset;
        }
        
        $window_seconds = $window_minutes * 60;
        $element_count = count($timestamps);
        // $dataset_elements = count($dataset);
        // echo 'Element count timestamps : ' . $element_count . '<br>';
        // echo 'Element count dataset    : ' . $dataset_elements . '<br>';
        $total_time = $timestamps[$element_count-1] - $timestamps[0];
        if ($window_seconds > $total_time) {
            return $dataset;
        }
        // convert time window into index range
        $start_time = $timestamps[0];
        $end_time = $start_time + $window_seconds;
        $cnt_index = 0; 
        for ($i = 0; $i < $element_count; ++$i) {
            if ($timestamps[$i] <= $end_time) {
                $cnt_index++;
            }
            else {
                break;
            }
        }
        
        $filtered = array();
        
        for ($i = 0; $i < $element_count; ++$i) {
            $start_index = intval($i - $cnt_index/2);
            $end_index = $start_index + $cnt_index;
            $sum = 0;
            $cnt_sum = 0;
            for ($j = $start_index; $j < $end_index; ++$j) {
                if ($j < 0 or $j > $element_count - 1)  // must be within array limits
                    continue;
                if ($dataset[$j] == Null) {
                    continue;
                }
                $sum += $dataset[$j];
                $cnt_sum += 1;
            }
            if ($cnt_sum == 0) {
                $filtered[$i] = Null;
            }
            else {
                if ($dataset[$i] == Null) {
                    $filtered[$i] = Null;
                }
                else {
                    $filtered[$i] = $sum / $cnt_sum;
                }
            }
            if ($filtered[$i] == Null and $dataset[$i] != Null) {
                $filtered[$i] = $dataset[$i];
            }
        }
        return $filtered;
    }
    
/*----------------------------------------------------------------------------*/

    global $last_timestamp_diagram;
    global $first_timestamp_diagram;
    
    $customtime = intval(get_table_value($config_settings_table, $customtime_for_diagrams_key));
    if ($customtime < 60) { // limit to 1 minute
        $customtime = 60;
    }
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
            $nth_value_scale =  intval($customtime / $min_time_window * $corr_factor_scale);
        }
    }
    if ($nth_value < 1) {
        $nth_value = 1;
    }
    if ($nth_value_scale < 1) {
        $nth_value_scale = 1;
    }
    
    // make nth_value_scale odd-numbered
    if ($nth_value_scale > 1 and (($nth_value_scale % 2) == 0)) {
        $nth_value_scale -= 1;
    }
    	
        
    // reading all_sensors_table
    $all_sensors_rows = get_allsensors_dataset($nth_value, $first_timestamp_diagram, $last_timestamp_diagram);
    // reading all_scales_table
    $all_scales_rows = get_allscales_dataset($nth_value_scale, $first_timestamp_diagram, $last_timestamp_diagram);
    
    // print "moving average window: " . $moving_average_window_x . " minutes <br>";
    // print "time window diagram: " . $x . " minutes <br>";
    // print "all_scales_rows count: " . count($all_scales_rows) . "<br>";
    // print "all_sensors_rows count: " . count($all_sensors_rows) . "<br>";    
	// print "row-0 tempint, last_change :" . $all_sensors_rows[0]['tempint'] . '  ' . $all_sensors_rows[0]['last_change'] . "<br>";
    
    // common x-axis timestamps for all_sensors
    $all_sensors_timestamps_array = array_column($all_sensors_rows, 'last_change');
    $all_sensors_timestamps_array[] = $last_timestamp_diagram;
    array_unshift($all_sensors_timestamps_array, $first_timestamp_diagram);
//    $all_sensors_timestamps_axis = get_text_array_for_time_axis($all_sensors_timestamps_array);
    
    // common x-axis timestamps for all_scales
    $all_scales_timestamps_array = array_column($all_scales_rows, 'last_change');
    $all_scales_timestamps_array[] = $last_timestamp_diagram;
    array_unshift($all_scales_timestamps_array, $first_timestamp_diagram);
//    $all_scales_timestamps_axis = get_text_array_for_time_axis($all_scales_timestamps_array);
        
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
    
    logger('DEBUG', 'chartsdata_diagrams performed');
 
?>
