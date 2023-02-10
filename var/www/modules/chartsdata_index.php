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

    function get_dataset_of_values($datavalues, $timestamps_axis){
        $dataset = array_values($datavalues);
        logger('DEBUG', 'get_dataset_of_values performed');
        return $dataset;
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
    // calculated temperature-humidity saving period, saving loop needs about 12s
    $temperatur_humidity_saving_period = $save_temperature_humidity_loops * 12;
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
    
    // print "moving average window: " . $moving_average_window_x . " minutes <br>";
    // print "time window diagram: " . $x . " minutes <br>";
    // print "all_scales_rows count: " . count($all_scales_rows) . "<br>";
    // print "all_sensors_rows count: " . count($all_sensors_rows) . "<br>";    
	// print "row-0 tempint, last_change :" . $all_sensors_rows[0]['tempint'] . '  ' . $all_sensors_rows[0]['last_change'] . "<br>";
    
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
    $temperature_avg_dataset = moving_average_filter($all_sensors_timestamps_array, $temperature_dataset, intval($moving_average_window_x));
    
    // value array for humint
    $humidity_dataset = array_column($all_sensors_rows, 'humint');
    $humidity_dataset[] = Null;
    array_unshift($humidity_dataset, Null);
    $humidity_avg_dataset = moving_average_filter($all_sensors_timestamps_array, $humidity_dataset, intval($moving_average_window_x));

    // value array for ntc1
    $thermometer1_dataset = array_column($all_sensors_rows, 'ntc1');
    $thermometer1_dataset[] = Null;
    array_unshift($thermometer1_dataset, Null);

    // value array for scale1
    $scale1_dataset = array_column($all_scales_rows, 'scale1');
    $scale1_dataset[] = Null;
    array_unshift($scale1_dataset, Null);

    // value array for scale2
    $scale2_dataset = array_column($all_scales_rows, 'scale2');
    $scale2_dataset[] = Null;
    array_unshift($scale2_dataset, Null);

    logger('DEBUG', 'chartsdata_index performed');

?>
