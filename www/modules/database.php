<?php 
    include 'names.php';
// include 'debug.php';

    function open_connection()
    {
        global $connection;
    
        $connection = new SQLite3("/var/www/config/pi-ager.sqlite3");
        $connection->busyTimeout(10000);
        // so müsste es sein... ;-)
        // $connection->exec('PRAGMA journal_mode = wal;');
        // $connection->exec('BEGIN TRANSACTION;');
    }

    function execute_query($command){
        global $connection;
        
        // open_connection();
        // $connection->exec('BEGIN;');
        $connection->exec($command);
        //echo('DB Input: ' . $command);
    }
    
    function close_database(){
        global $connection;

        // $connection->exec('COMMIT;');
        $connection->close();
    }
    
    function get_current_time(){
        $current_time = time();
        return $current_time;
    }
    
    function get_query_result($sql_statement){
        global $connection;
        
        // open_connection();
        // $connection->exec('BEGIN;');
        $result = $connection->query($sql_statement);
        //echo('DB Input: ' . $sql_statement);
        return $result;
    }
    
    function get_table_value($table, $key)
    {
        global $value_field,$id_field;
        
        $value = NULL;
        open_connection();
		if ($key == NULL){
            $sql = 'SELECT ' . $value_field . ' FROM ' . $table . ' WHERE ' . $id_field . ' = (SELECT MAX(' . $id_field . ') from ' . $table . ')';
        }
        else {
            $sql = 'SELECT ' . $value_field . ' FROM ' . $table . ' WHERE key = "' . $key . '" AND ' . $id_field . ' = (SELECT MAX(' . $id_field . ') from ' . $table . ' WHERE key = "' . $key . '")';
        }
        $result = get_query_result($sql);
        // if ($result == FALSE)
            // {
                // echo($sql . '<br>');
                // }
        // else
            // {
            while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
                {
                $value = $dataset[$value_field];
                }
            // }
        close_database();
        
        return $value;
    }

    function get_last_change($table, $key)
    {
        global $last_change_field,$id_field;
        
        open_connection();
        if ($key == NULL){
            #$sql = 'SELECT ' . $value_field . ' FROM ' . $table . ' o WHERE o.id = (SELECT MAX(i.id) from ' . $table . ')';
            $sql = 'SELECT ' . $last_change_field . ' FROM ' . $table . ' WHERE ' . $id_field . ' = (SELECT MAX(' . $id_field . ') from ' . $table . ')';
        }
        else {
            #$sql = 'SELECT ' . $value_field . ' FROM ' . $table . ' o WHERE o.key = "' . $key . '" AND o.id = (SELECT MAX(i.id) from ' . $table . ' i WHERE i.key = "' . $key . '")';
            $sql = 'SELECT ' . $last_change_field . ' FROM ' . $table . ' WHERE key = "' . $key . '" AND ' . $id_field . ' = (SELECT MAX(' . $id_field . ') from ' . $table . ' WHERE key = "' . $key . '")';
        }
        $result = get_query_result($sql);
        while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
            {
            $last_change = $dataset[$last_change_field];
            }
        close_database();
        
        return $last_change;
    }

    function get_scale_table_row($table){
        global $value_field, $last_change_field,$id_field;
        
        open_connection();
        $sql = 'SELECT ' . $value_field . ', ' . $last_change_field . ' FROM ' . $table . ' WHERE ' . $id_field . ' = (SELECT MAX(' . $id_field . ') from ' . $table . ')';
        $result = get_query_result($sql);
        while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
            {
            $value = $dataset[$value_field];
            $last_change = $dataset[$last_change_field];
            }
        // if (debugging == 'on') {
            // print ("DEBUG: " + strval(row[0]))
            // print ("DEBUG: " + strval(row.keys()))
        close_database();
        
        return $dataset;
    }

    function get_current_values_for_monitoring(){
        global $current_values_table, $key_field, $value_field, $sensor_temperature_key, $sensor_humidity_key, $scale1_key, $scale2_key, $last_change_field, $last_change_temperature_json_key, $last_change_humidity_json_key, $last_change_scale1_json_key, $last_change_scale2_json_key;
        
        open_connection();
        $sql = 'SELECT * FROM ' . $current_values_table;
        // echo $sql;
        $result = get_query_result($sql);
        $values = array();
        while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
        {
           $values[$dataset[$key_field]] =  $dataset[$value_field];
           if ($dataset[$key_field] == $sensor_temperature_key)
           {
               $values[$last_change_temperature_json_key] =  $dataset[$last_change_field];
           }
           elseif ($dataset[$key_field] == $sensor_humidity_key)
           {
               $values[$last_change_humidity_json_key] =  $dataset[$last_change_field];
           }
           elseif ($dataset[$key_field] == $scale1_key)
           {
               $values[$last_change_scale1_json_key] =  $dataset[$last_change_field];
           }
           elseif ($dataset[$key_field] == $scale2_key)
           {
               $values[$last_change_scale2_json_key] =  $dataset[$last_change_field];
           }
        }
        close_database();
        return $values;
    }

    function read_agingtable_name_from_config()
        {
        global $id_field,$agingtable_name_field,$config_settings_table,$agingtable_key,$agingtables_table;
        
        $id_agingtable = get_table_value($config_settings_table,$agingtable_key);
		open_connection();
		$sql = 'SELECT "' . $agingtable_name_field . '" FROM ' . $agingtables_table . ' WHERE ' . $id_field . ' = ' . intval($id_agingtable) . ';';
		// echo($sql);
        $table_result = get_query_result($sql);
		if (!$table_result) {
			echo('$table_result = ' . strval($table_result));
		}
        while ($dataset = $table_result->fetchArray(SQLITE3_ASSOC))
            {
            $agingtable_name = $dataset[$agingtable_name_field];
            }
        close_database();
        
        return $agingtable_name;
    }
    
    function get_agingtable_names()
    {
        global $agingtable_name_field, $agingtables_table;
        
        open_connection();
        $sql = 'SELECT ' . $agingtable_name_field . ' FROM ' . $agingtables_table;
        $result = get_query_result($sql);
        $index = 0;
        while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
            {
            $agingtable_names[$index] = $dataset[$agingtable_name_field];
            $index++;
            }
        close_database();
        return $agingtable_names;
    }

    function get_agingtable_id_by_name($agingtable_name)
    {
        global $id_field, $agingtables_table, $agingtable_name_field;
        
        open_connection();
        $sql = 'SELECT ' . $id_field . ' FROM ' . $agingtables_table . ' WHERE ' . $agingtable_name_field . ' = "' . $agingtable_name . '"';
        $result = get_query_result($sql);
        while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
            {
            $id_agingtable = $dataset[$id_field];
            }
        close_database();
        return $id_agingtable;
    }
    
    function get_agingtable_dataset($agingtable_name)
    {
        open_connection();
        $sql = 'SELECT * FROM agingtable_' . $agingtable_name;
        $result = get_query_result($sql);
        $index = 0;
        while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
            {
            $agingtable_rows[$index] = $dataset;
            // Beispiel für späteren Aufruf: $agingtable_rows[0]['setpoint_temperature']
            $index++;
            }
        close_database();
        return $agingtable_rows;
    }
    
    function write_agingtable($agingtable){
        global $value_field, $last_change_field, $key_field, $config_settings_table, $agingtable_key;
        
        $id_agingtable = get_agingtable_id_by_name($agingtable);
        
        open_connection();
        $sql = 'UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = "' . $id_agingtable . '" , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $agingtable_key . '"';
        execute_query($sql);
        
        close_database();
    }
    
    function write_loglevel($chosen_loglevel_file, $chosen_loglevel_console){
        global $value_field, $last_change_field, $key_field, $config_settings_table, $loglevel_console_key, $loglevel_file_key, $debug_table;
        
        open_connection();
        $sql = 'UPDATE ' . $debug_table . ' SET "' . $value_field . '" = ' . $chosen_loglevel_file . ' WHERE ' . $key_field . ' = "' . $loglevel_file_key . '";';
        $sql = $sql . ' UPDATE ' . $debug_table . ' SET "' . $value_field . '" = ' . $chosen_loglevel_console . ' WHERE ' . $key_field . ' = "' . $loglevel_console_key . '";';
        execute_query($sql);
        
        close_database();
    }
    

    function write_settings($modus, $setpoint_temperature, $setpoint_humidity, $circulation_air_period, $circulation_air_duration, $exhaust_air_period,
                            $exhaust_air_duration)
        {
        global $value_field, $last_change_field, $key_field, $config_settings_table, $settings_scale1_table, $settings_scale2_table, $modus_key, $setpoint_temperature_key, $setpoint_humidity_key, $circulation_air_period_key, $circulation_air_duration_key, $exhaust_air_period_key, $exhaust_air_duration_key;
        open_connection();

        $sql_statement = 'UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($modus) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $modus_key . '";';
        $sql_statement = $sql_statement . 'UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($setpoint_temperature) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $setpoint_temperature_key .'";';
        $sql_statement = $sql_statement . 'UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($setpoint_humidity) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $setpoint_humidity_key . '";';
        $sql_statement = $sql_statement . 'UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($circulation_air_period * 60) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $circulation_air_period_key . '";';
        $sql_statement = $sql_statement . 'UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($circulation_air_duration * 60) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $circulation_air_duration_key . '";';
        $sql_statement = $sql_statement . 'UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($exhaust_air_period * 60) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $exhaust_air_period_key . '";';
        $sql_statement = $sql_statement . 'UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($exhaust_air_duration * 60) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $exhaust_air_duration_key . '";';

        execute_query($sql_statement);
        close_database();
    }

    function write_config($sensortype, $language, $switch_on_cooling_compressor, $switch_off_cooling_compressor,
                            $switch_on_humidifier, $switch_off_humidifier, $delay_humidify, $uv_modus, $uv_duration, 
                            $uv_period, $switch_on_uv_hour, $switch_on_uv_minute, $light_modus, $light_duration, 
                            $light_period, $switch_on_light_hour, $switch_on_light_minute, $dehumidifier_modus, 
                            $referenceunit_scale1, $referenceunit_scale2)
        {
        global $value_field, $last_change_field, $key_field, $config_settings_table, $settings_scale1_table, $settings_scale2_table, $sensortype_key, $language_key, $switch_on_cooling_compressor_key,
                $switch_off_cooling_compressor_key, $switch_on_humidifier_key, $switch_off_humidifier_key, $delay_humidify_key, $uv_modus_key,
                $uv_duration_key, $uv_period_key, $switch_on_uv_hour_key, $switch_on_uv_minute_key, $light_modus_key, $light_duration_key, $light_period_key,
                $switch_on_light_hour_key, $switch_on_light_minute_key, $dehumidifier_modus_key, $referenceunit_key;
        open_connection();

        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($sensortype) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $sensortype_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($language) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $language_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($switch_on_cooling_compressor) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $switch_on_cooling_compressor_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($switch_off_cooling_compressor) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $switch_off_cooling_compressor_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($switch_on_humidifier) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $switch_on_humidifier_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($switch_off_humidifier) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $switch_off_humidifier_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($delay_humidify) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $delay_humidify_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($uv_modus) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $uv_modus_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval(($uv_duration * 60)) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $uv_duration_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval(($uv_period *60)) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $uv_period_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($switch_on_uv_hour) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $switch_on_uv_hour_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($switch_on_uv_minute) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $switch_on_uv_minute_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($light_modus) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $light_modus_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval(($light_duration * 60)) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $light_duration_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval(($light_period * 60)) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $light_period_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($switch_on_light_hour) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $switch_on_light_hour_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($switch_on_light_minute) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $switch_on_light_minute_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($dehumidifier_modus) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $dehumidifier_modus_key . '"');
        get_query_result('UPDATE ' . $settings_scale1_table . ' SET "' . $value_field . '" = ' . strval($referenceunit_scale1) . ' WHERE ' . $key_field . ' = "' . $referenceunit_key . '"');
        get_query_result('UPDATE ' . $settings_scale2_table . ' SET "' . $value_field . '" = ' . strval($referenceunit_scale2) . ' WHERE ' . $key_field . ' = "' . $referenceunit_key . '"');

        close_database();
        //echo('FINISHED WRITING IN DATABASE');
        }
    
    function write_start_in_database($module_key)
    {
        write_startstop_status_in_database($module_key, 1);
    }

    function write_stop_in_database($module_key)
    {
        write_startstop_status_in_database($module_key, 0);
    }
    
    function delete_data($table_name)
    {
        open_connection();
        $sql = 'DELETE FROM ' . $table_name;
        get_query_result($sql);
        $sql = 'VACUUM';
        get_query_result($sql);
        close_database();
        
    }
    
    function delete_statistic_tables()
    {
        global $data_sensor_temperature_table, $data_sensor_humidity_table, $status_heater_table, $status_exhaust_air_table, $status_cooling_compressor_table, $status_circulating_air_table, $status_uv_table, $status_light_table, $status_humidifier_table, $status_dehumidifier_table, $data_scale1_table, $data_scale2_table, $data_sensor_temperature_meat1_table, $data_sensor_temperature_meat2_table, $data_sensor_temperature_meat3_table, $data_sensor_temperature_meat4_table;

        delete_data($data_sensor_temperature_table);
        delete_data($data_sensor_humidity_table);
        delete_data($status_heater_table);
        delete_data($status_exhaust_air_table);
        delete_data($status_cooling_compressor_table);
        delete_data($status_circulating_air_table);
        delete_data($status_circulating_air_table);
        delete_data($status_uv_table);
        delete_data($status_light_table);
        delete_data($status_humidifier_table);
        delete_data($status_dehumidifier_table);
        delete_data($data_scale1_table);
        delete_data($data_scale2_table);
        delete_data($data_sensor_temperature_meat1_table);
        delete_data($data_sensor_temperature_meat2_table);
        delete_data($data_sensor_temperature_meat3_table);
        delete_data($data_sensor_temperature_meat4_table);
        //delete_data($);

    }

    function write_startstop_status_in_database($module_key, $status)
    {
        global $current_values_table, $value_field, $last_change_field, $key_field;
        
        open_connection();
        
        $sql = 'UPDATE ' . $current_values_table . ' SET "' . $value_field . '" = "' . strval($status) . '" , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $module_key . '"';
        //echo $sql;
        execute_query($sql);
        
        close_database();
    }
    // function write_current(sensor_temperature, status_heater, status_exhaust_air, status_cooling_compressor, status_circulating_air, sensor_humidity){
        // open_connection();
        // if pi_ager_debug.debugging=='on':
            // print('DEBUG: in write_current')
            // print('INSERT INTO ' . data_sensor_temperature_table + '(' + strval(value_field) + ',' + strval(last_change_field) +') VALUES ('+ strval(sensor_temperature) + ', ' . strval(get_current_time()) + ')')
        // get_query_result('INSERT INTO ' . data_sensor_temperature_table + '(' + strval(value_field) + ',' + strval(last_change_field) +') VALUES ('+ strval(sensor_temperature) + ', ' . strval(get_current_time()) + ')')
        // get_query_result('INSERT INTO ' . status_heater_table . ' (' + strval(value_field) + ',' + strval(last_change_field) +') VALUES ('+ strval(status_heater) + ',' + strval(get_current_time()) + ')')
        // get_query_result('INSERT INTO ' . status_exhaust_air_table . ' (' + strval(value_field) + ',' + strval(last_change_field) +') VALUES ('+ strval(status_exhaust_air) + ',' + strval(get_current_time()) + ')')
        // get_query_result('INSERT INTO ' . status_cooling_compressor_table . ' (' + strval(value_field) + ',' + strval(last_change_field) +') VALUES ('+ strval(status_cooling_compressor) + ',' + strval(get_current_time()) + ')')
        // get_query_result('INSERT INTO ' . status_circulating_air_table . ' (' + strval(value_field) + ',' + strval(last_change_field) +') VALUES ('+ strval(status_circulating_air) + ',' + strval(get_current_time()) + ')')
        // get_query_result('INSERT INTO ' . data_sensor_humidity_table . ' (' + strval(value_field) + ',' + strval(last_change_field) +') VALUES ('+ strval(sensor_humidity) + ',' + strval(get_current_time()) + ')')
        // close_database()
    // }

    // function write_scales(value_scale1, value_scale2){
        // open_connection();
        // get_query_result('INSERT INTO ' . data_scale1_table . ' (' + strval(value_field) + ',' + strval(last_change_field) +') VALUES (' + strval(value_scale1) + ',' + strval(get_current_time()) + ')')
        // get_query_result('INSERT INTO ' . data_scale2_table . ' (' + strval(value_field) + ',' + strval(last_change_field) +') VALUES (' + strval(value_scale2) + ',' + strval(get_current_time()) + ')')
        // close_database()
    // }

    // function read_config(){
        // open_connection();
        // get_query_result('SELECT * FROM ' . config_settings_table . ' WHERE ' . key_field . ' = "sensortype" OR ' . key_field . ' = "language" OR ' . key_field . ' = "switch_on_cooling_compressor" OR ' . key_field . ' = "switch_off_cooling_compressor" OR ' . key_field . ' = "switch_on_humidifier" OR ' . key_field . ' = "switch_off_humidifier" OR ' . key_field . ' = "delay_humidify" OR ' . key_field . ' = "uv_modus" OR ' . key_field . ' = "uv_duration" OR ' . key_field . ' = "uv_period" OR ' . key_field . ' = "switch_on_uv_hour" OR ' . key_field . ' = "switch_on_uv_minute" OR ' . key_field . ' = "light_modus" OR ' . key_field . ' = "light_duration" OR ' . key_field . ' = "light_period" OR ' . key_field . ' = "switch_on_light_hour" OR ' . key_field . ' = "switch_on_light_minute" OR ' . key_field . ' = "dehumidifier_modus" OR ' . key_field . ' = "referenceunit_scale1" OR ' . key_field . ' = "referenceunit_scale2"');
        // rows = cursor.fetchall()
        // close_database()
        // return rows
    // }

    // function read_settings(){
        // open_connection();
        // get_query_result('SELECT * FROM ' . config_settings_table . ' WHERE ' . key_field . ' ="modus" OR "key"="setpoint_temperature" OR "key"="setpoint_humidity" OR "key"="circulation_air_period" OR "key"="circulation_air_duration" OR "key"="exhaust_air_period" OR "key"="exhaust_air_duration"')
        // rows = cursor.fetchall()
        // close_database()
        // return rows
    // }

    
    
    
    
    // $items_current = array();
    // while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
        // {
        // $items_current[$dataset['key']] = $dataset['value'];
        // }
    
    // $sensor_temperature = round($items_current['sensor_temperature'], 1);
    // $sensor_humidity = round($items_current['sensor_humidity'], 1);
    // $status_exhaust_air = $items_current["status_exhaust_air"];
    // $sensor_temperature = $items_current["sensor_temperature"];
    // $status_circulating_air = $items_current["status_circulating_air"];
    // $sensor_humidity = $items_current["sensor_humidity"];
    // $status_heater = $items_current["status_heater"];
    // $status_cooling_compressor = $items_current["status_cooling_compressor"];
    // $current_json_timestamp_last_change = $dataset['last_change'];
?>
