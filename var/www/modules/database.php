<?php 
    include 'names.php';

    function open_connection()
    {
        global $connection;
    
        $connection = new SQLite3("/var/www/config/pi-ager.sqlite3");
        $connection->busyTimeout(10000);
        //$connection->enableExceptions(true);
        $connection->exec('PRAGMA journal_mode = wal;');
    }

    function execute_query($command){
        global $connection;
        
        $connection->exec($command);
    }
    
    function close_database(){
        global $connection;

        $connection->close();
    }
    
    function check_if_table_exists($table){
        
        open_connection();
        
        $result = get_query_result('SELECT count(*) FROM sqlite_master WHERE type="table" AND name="' . $table . '";');
        while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
            {
            $count = $dataset['count(*)'];
            }
            
        if ($count == 0 ){
            return false;
        }
        else{
            return true;
        }
        
        
        close_database();
        
        return $result;
    }
    
    function check_columns($table){
        
        open_connection();
        
        $result = get_query_result('PRAGMA table_info('. $table . ')');
        while ($dataset = $result->fetchArray(SQLITE3_ASSOC)){
            $column_array = $dataset['table_info'];
        }
        close_database();
        return $column_array;
    }
    
    function get_current_time(){
        $current_time = time();
        return $current_time;
    }
    
    function get_query_result($sql_statement){
        global $connection;

        $result = $connection->query($sql_statement);
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
            while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
                {
                $value = $dataset[$value_field];
                }
        close_database();
        
        return $value;
    }

    function get_diagram_values($table, $nth_value)
    {
        global $value_field, $last_change_field, $id_field;
        
        open_connection();
        $sql = 'SELECT ' . $value_field . ', ' .$last_change_field . ' FROM ' . $table . ' WHERE (' . $id_field . ' % ' . $nth_value . ') = 0';
        $result = get_query_result($sql);
        while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
        {
            $values[$dataset[$last_change_field]] = $dataset[$value_field];
        }
        close_database();
        
        if (!isset ($values)){
            $values = array();
            $values['1'] = 0;
        }
        
        return $values;
    }
    
    // get only part of values, range is defined by last_timestamp_diagram and first_timestamp_diagram
    function get_diagram_values_range($table, $nth_value, $first_timestamp_diagram, $last_timestamp_diagram)
    {
        global $value_field, $last_change_field, $id_field;
        
        open_connection();
        $sql = 'SELECT ' . $value_field . ', ' .$last_change_field . ' FROM ' . $table . ' WHERE (' . $id_field . ' % ' . $nth_value . ') = 0' . ' AND ' . $last_change_field . ' BETWEEN ' . $first_timestamp_diagram . ' AND ' . $last_timestamp_diagram;
        $result = get_query_result($sql);
        $values = array();
        while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
        {
            $values[$dataset[$last_change_field]] = $dataset[$value_field];
        }
        close_database();
        
        //if (!isset ($values)){
        //    $values = array();
        //    $values['1'] = 0;
        //}
        
        return $values;
    }
    
    function get_last_change($table, $key)
    {
        global $last_change_field,$id_field, $last_change;
        
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
        close_database();
        
        return $dataset;
    }
    
    function get_meatsensors_dataset()
    {
        global $meat_sensortypes;
        
        open_connection();
        $sql = 'SELECT * FROM ' . $meat_sensortypes;
        
        $result = get_query_result($sql);
    
        $index = 0;
        while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
            {
            $meatsensors_rows[$index] = $dataset;
            // Beispiel für späteren Aufruf: $meatsensors_rows[0]['name']
            $index++;
            }
        close_database();
        return $meatsensors_rows;
    }
    
    function get_meatsensor_table_row( $sensortype_id )
    {
        global $id_field, $meat_sensortypes;
        
        open_connection();
//        $sql = 'SELECT * FROM ' . $meat_sensortypes . ' WHERE ' . $id_field . ' = ' . intval($sensortype_id);
        $sql = 'SELECT * FROM ' . $meat_sensortypes . ' WHERE id = ' . intval($sensortype_id);
//        echo $sql;
        $result = get_query_result($sql);
        while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
            {
            $meatsensor_row = $dataset;
            }
        close_database();
//        echo json_encode($meatsensor_row);
        return $meatsensor_row;       
    }
    
    function get_current_values_for_monitoring(){
        global $current_values_table, $key_field, $value_field, $sensor_temperature_key, $sensor_humidity_key, $scale1_key, $scale2_key;
        global $last_change_field, $last_change_temperature_json_key, $last_change_humidity_json_key, $last_change_scale1_json_key, $last_change_scale2_json_key, $server_time_json_key;
        global $temperature_meat1_key, $temperature_meat2_key, $temperature_meat3_key, $temperature_meat4_key;
        global $last_change_temperature_meat1_json_key, $last_change_temperature_meat2_json_key, $last_change_temperature_meat3_json_key, $last_change_temperature_meat4_json_key;
        global $config_settings_table;
        global $meat1_sensortype_key, $meat2_sensortype_key, $meat3_sensortype_key, $meat4_sensortype_key;
        global $meat1_sensor_name_json_key, $meat2_sensor_name_json_key, $meat3_sensor_name_json_key, $meat4_sensor_name_json_key;
        global $sensor_dewpoint_key, $sensor_extern_temperature_key, $sensor_extern_humidity_key, $sensor_extern_dewpoint_key;
        global $last_change_dewpoint_json_key, $last_change_extern_temperature_json_key, $last_change_extern_humidity_json_key, $last_change_extern_dewpoint_json_key;
        
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
           elseif ($dataset[$key_field] == $temperature_meat1_key)
           {
               $values[$last_change_temperature_meat1_json_key] =  $dataset[$last_change_field];
           }
           elseif ($dataset[$key_field] == $temperature_meat2_key)
           {
               $values[$last_change_temperature_meat2_json_key] =  $dataset[$last_change_field];
           }
           elseif ($dataset[$key_field] == $temperature_meat3_key)
           {
               $values[$last_change_temperature_meat3_json_key] =  $dataset[$last_change_field];
           }
           elseif ($dataset[$key_field] == $temperature_meat4_key)
           {
               $values[$last_change_temperature_meat4_json_key] =  $dataset[$last_change_field];
           }
            elseif ($dataset[$key_field] == $sensor_dewpoint_key)
           {
               $values[$last_change_dewpoint_json_key] =  $dataset[$last_change_field];
           }
           elseif ($dataset[$key_field] == $sensor_extern_temperature_key)
           {
               $values[$last_change_extern_temperature_json_key] =  $dataset[$last_change_field];
           }
           elseif ($dataset[$key_field] == $sensor_extern_humidity_key)
           {
               $values[$last_change_extern_humidity_json_key] =  $dataset[$last_change_field];
           }
           elseif ($dataset[$key_field] == $sensor_extern_dewpoint_key)
           {
               $values[$last_change_extern_dewpoint_json_key] =  $dataset[$last_change_field];
           }
        }
        close_database();
        
        $values[$server_time_json_key] = time();

        $meat1_sensortype_id = get_table_value($config_settings_table, $meat1_sensortype_key);
        $meat2_sensortype_id = get_table_value($config_settings_table, $meat2_sensortype_key); 
        $meat3_sensortype_id = get_table_value($config_settings_table, $meat3_sensortype_key);
        $meat4_sensortype_id = get_table_value($config_settings_table, $meat4_sensortype_key);
        
        $row = get_meatsensor_table_row( $meat1_sensortype_id );
        $values[$meat1_sensor_name_json_key] = $row['name'];
        
        $row = get_meatsensor_table_row( $meat2_sensortype_id );
        $values[$meat2_sensor_name_json_key] = $row['name'];  
        $row = get_meatsensor_table_row( $meat3_sensortype_id );
        $values[$meat3_sensor_name_json_key] = $row['name'];
        $row = get_meatsensor_table_row( $meat4_sensortype_id );
        $values[$meat4_sensor_name_json_key] = $row['name'];  
        
        return $values;
    }
    
    function read_agingtable_name_from_config()
        {
        global $id_field,$agingtable_name_field,$config_settings_table,$agingtable_key,$agingtables_table;
        
        $id_agingtable = get_table_value($config_settings_table, $agingtable_key);
		open_connection();
		$sql = 'SELECT "' . $agingtable_name_field . '" FROM ' . $agingtables_table . ' WHERE ' . $id_field . ' = ' . intval($id_agingtable) . ';';
		// echo($sql);
        $table_result = get_query_result($sql);
		if (!$table_result) {
			// echo('$table_result = ' . strval($table_result));
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

    function get_table_dataset($table)
    {
        open_connection();
        $sql = 'SELECT * FROM ' . $table;
        
        $result = get_query_result($sql);
    
        $index = 0;
        while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
            {
            $dataset_rows[$index] = $dataset;
            // Beispiel für späteren Aufruf: $dataset_rows[0]['setpoint_temperature']
            $index++;
            }
        close_database();
        return $dataset_rows;
    }
    
    function write_telegram_values($telegram_bot_token, $telegram_bot_chatid, $telegram_active){
        global $telegram_table, $telegram_bot_token_field, $telegram_bot_chat_id_field, $telegram_active_field, $telegram_id_field;
        open_connection();
        $sql = 'UPDATE ' . $telegram_table . ' SET "' . $telegram_bot_token_field . '" = "' . $telegram_bot_token . '" , "' . $telegram_bot_chat_id_field . '" = "' . $telegram_bot_chatid . '", "'. $telegram_active_field . '" = ' . $telegram_active . ' WHERE "' . $telegram_id_field . '" = 1';
        execute_query($sql);
        
        close_database();
    }
    
    function write_pushover_values($pushover_user_key, $pushover_api_token, $pushover_active){
        global $pushover_table, $pushover_user_key_field, $pushover_api_token_field, $pushover_active_field, $pushover_id_field;
        open_connection();
        $sql = 'UPDATE ' . $pushover_table . ' SET "' . $pushover_user_key_field . '" = "' . $pushover_user_key . '" , "' . $pushover_api_token_field . '" = "' . $pushover_api_token . '", "'. $pushover_active_field . '" = ' . $pushover_active . ' WHERE "' . $pushover_id_field . '" = 1';
        execute_query($sql);
        
        close_database();
    }
    
    function write_mailserver_values($mailserver_server, $mailserver_user, $mailserver_port){
        global $mailserver_table, $mailserver_id_field, $mailserver_server_field, $mailserver_user_field, $mailserver_port_field;
        open_connection();
        $sql = 'UPDATE ' . $mailserver_table . ' SET "' . $mailserver_server_field . '" = "' . $mailserver_server . '" , "' . $mailserver_user_field . '" = "' . $mailserver_user . '" , "' . $mailserver_port_field . '" = "' . $mailserver_port. '" WHERE "' . $mailserver_id_field . '" = 1';

        execute_query($sql);
        
        close_database();
    }
    
    function write_mail_recipient_values($e_mail_recipients_id, $e_mail_recipients_to_mail, $e_mail_recipients_active){
        global $email_recipients_table, $e_mail_recipients_to_mail_field, $e_mail_recipients_active_field, $e_mail_recipients_id_field;
        open_connection();
        $sql = 'UPDATE ' . $email_recipients_table . ' SET "' . $e_mail_recipients_to_mail_field . '" = "' . $e_mail_recipients_to_mail . '" , "' . $e_mail_recipients_active_field . '" = "' . $e_mail_recipients_active . '" WHERE "' . $e_mail_recipients_id_field . '" = ' . $e_mail_recipients_id;
        
        execute_query($sql);
        
        close_database();
    }
    
    function write_messenger_values($messenger_id, $messenger_exception, $messenger_e_mail, $messenger_pushover, $messenger_telegram, $messenger_alarm, $messenger_raise_exception, $messenger_active ){
        global $messenger_table, $messenger_id_field, $messenger_exception_field, $messenger_e_mail_field, $messenger_pushover_field, $messenger_telegram_field, $messenger_alarm_field, $messenger_raise_exception_field, $messenger_active_field;
        open_connection();
        $sql = 'UPDATE ' . $messenger_table . ' SET "' . $messenger_exception_field . '" = "' . $messenger_exception . '" , "' . $messenger_e_mail_field . '" = "' . $messenger_e_mail . '" , "' . $messenger_pushover_field . '" = "' . $messenger_pushover . '" , "' . $messenger_telegram_field . '" = "' . $messenger_telegram . '" , "' . $messenger_alarm_field . '" = "' . $messenger_alarm . '" , "' . $messenger_raise_exception_field . '" = "' . $messenger_raise_exception . '" , "' . $messenger_active_field . '" = "' . $messenger_active . '" WHERE "' . $messenger_id_field . '" = ' . $messenger_id ;
        
        execute_query($sql);
        
        close_database();
    }
    
    function write_event_values($event_id, $event_event, $event_e_mail, $event_pushover, $event_telegram, $event_alarm, $event_eventtext, $event_active ){
        global $messenger_event_table, $event_id_field, $event_event_field, $event_e_mail_field, $event_pushover_field, $event_telegram_field, $event_alarm_field, $event_eventtext_field, $event_active_field;
        open_connection();
        $sql = 'UPDATE ' . $messenger_event_table . ' SET "' . $event_event_field . '" = "' . $event_event . '" , "' . $event_e_mail_field . '" = "' . $event_e_mail . '" , "' . $event_pushover_field . '" = "' . $event_pushover . '" , "' . $event_telegram_field . '" = "' . $event_telegram . '" , "' . $event_alarm_field . '" = "' . $event_alarm . '" , "' . $event_eventtext_field . '" = "' . $event_eventtext . '" , "' . $event_active_field . '" = "' . $event_active . '" WHERE "' . $event_id_field . '" = ' . $event_id ;
        
        execute_query($sql);
        
        close_database();
    }
    
    function write_alarm_values($alarm_id, $alarm_alarm, $alarm_replication, $alarm_sleep, $alarm_high_time, $alarm_low_time, $alarm_waveform, $alarm_frequency ){
        global $alarm_table, $alarm_id_field, $alarm_alarm_field, $alarm_replication_field, $alarm_sleep_field, $alarm_high_time_field, $alarm_low_time_field, $alarm_waveform_field, $alarm_frequency_field;
        open_connection();
        $sql = 'UPDATE ' . $alarm_table . ' SET "' . $alarm_alarm_field . '" = "' . $alarm_alarm . '" , "' . $alarm_replication_field . '" = "' . $alarm_replication . '" , "' . $alarm_sleep_field . '" = "' . $alarm_sleep . '" , "' . $alarm_high_time_field . '" = "' . $alarm_high_time . '" , "' . $alarm_low_time_field . '" = "' . $alarm_low_time . '" , "' . $alarm_waveform_field . '" = "' . $alarm_waveform . '" , "' . $alarm_frequency_field . '" = "' . $alarm_frequency . '" WHERE "' . $alarm_id_field . '" = ' . $alarm_id ;
        
        execute_query($sql);
        
        close_database();
        
    }

    function write_customtime($new_customtime){
        global $value_field, $key_field, $config_settings_table, $customtime_for_diagrams_key;
        
        open_connection();
        $sql = 'UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . $new_customtime . ' WHERE ' . $key_field . ' = "' . $customtime_for_diagrams_key . '";';

        execute_query($sql);
        
        close_database();
    }
    
    function add_alarm($alarm_alarm, $alarm_replication, $alarm_sleep, $alarm_high_time, $alarm_low_time, $alarm_waveform, $alarm_frequency ){
        global $alarm_table, $alarm_id_field, $alarm_alarm_field, $alarm_replication_field, $alarm_sleep_field, $alarm_high_time_field, $alarm_low_time_field, $alarm_waveform_field, $alarm_frequency_field;
        open_connection();
        $sql = 'INSERT INTO ' . $alarm_table . ' ("' . $alarm_alarm_field . '","' . $alarm_replication_field . '","' . $alarm_sleep_field . '","' . $alarm_high_time_field . '","' . $alarm_low_time_field . '","' . $alarm_waveform_field . '","' . $alarm_frequency_field . '") VALUES ("' . $alarm_alarm .'",' . $alarm_replication .',' . $alarm_sleep .',' . $alarm_high_time .',' . $alarm_low_time .',"' . $alarm_waveform .'",' . $alarm_frequency . ')';
            
        execute_query($sql);
        
        close_database();        
    }
    
    function add_messenger($add_messenger_exception, $add_checked_messenger_e_mail, $add_checked_messenger_pushover, $add_checked_messenger_telegram, $add_messenger_alarm, $add_checked_messenger_raise_exeption, $add_checked_messenger_active ){
        global $messenger_table, $messenger_id_field, $messenger_exception_field, $messenger_e_mail_field, $messenger_pushover_field, $messenger_telegram_field, $messenger_alarm_field, $messenger_raise_exception_field, $messenger_active_field;;
        open_connection();
        $sql = 'INSERT INTO ' . $messenger_table . ' ("' . $messenger_exception_field . '","' . $messenger_e_mail_field . '","' . $messenger_pushover_field . '","' . $messenger_telegram_field . '","' . $messenger_alarm_field . '","' . $messenger_raise_exception_field . '","' . $messenger_active_field . '") VALUES ("' . $add_messenger_exception .'",' . $add_checked_messenger_e_mail .',' . $add_checked_messenger_pushover .',' . $add_checked_messenger_telegram .',"' . $add_messenger_alarm .'",' . $add_checked_messenger_raise_exeption .',' . $add_checked_messenger_active . ')';
        execute_query($sql);
        
        close_database();
    }
    
    function add_event($add_event_event, $add_checked_event_e_mail, $add_checked_event_pushover, $add_checked_event_telegram, $add_event_alarm, $add_eventtext, $add_checked_alarm_active ){
        global $messenger_event_table, $event_id_field, $event_event_field, $event_e_mail_field, $event_pushover_field, $event_telegram_field, $event_alarm_field, $event_eventtext_field, $event_active_field;;
        open_connection();
        $sql = 'INSERT INTO ' . $messenger_event_table . ' ("' . $event_event_field . '","' . $event_e_mail_field . '","' . $event_pushover_field . '","' . $event_telegram_field . '","' . $event_alarm_field . '","' . $event_eventtext_field . '","' . $event_active_field . '") VALUES ("' . $add_event_event .'",' . $add_checked_event_e_mail .',' . $add_checked_event_pushover .',' . $add_checked_event_telegram .',"' . $add_event_alarm .'","' . $add_eventtext .'",' . $add_checked_alarm_active . ')';
        execute_query($sql);
        
        close_database();
    }
    
    function add_mail_recipient($add_e_mail_recipients_to_mail, $add_e_mail_recipients_active){
        global $email_recipients_table, $e_mail_recipients_to_mail_field, $e_mail_recipients_active_field;
        open_connection();
        $sql = 'INSERT INTO ' . $email_recipients_table . ' ("'. $e_mail_recipients_to_mail_field . '","' . $e_mail_recipients_active_field . '") VALUES ( "' . $add_e_mail_recipients_to_mail . '" , ' . $add_e_mail_recipients_active . ')';

        execute_query($sql);
        
        close_database();
    }
    
    function write_agingtable($agingtable){
        global $value_field, $last_change_field, $key_field, $config_settings_table, $agingtable_key;
        
        $id_agingtable = get_agingtable_id_by_name($agingtable);
        
        open_connection();
        $sql = 'UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = "' . $id_agingtable . '" , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $agingtable_key . '"';
        execute_query($sql);
        
        close_database();
    }
    
    function delete_row_from_table($tablename, $id_field, $row_id){
        open_connection();
        $sql = 'DELETE FROM ' . $tablename . ' WHERE  "' . $id_field . '" = ' . $row_id;
        execute_query($sql);
        
        close_database();
    }
    
  
    function delete_agingtable($agingtable){
        global  $config_settings_table, $id_field, $agingtables_table, $agingtable_key;
        
        $id_agingtable_to_delete = get_agingtable_id_by_name($agingtable);
        $id_chosen_agingtable = get_table_value($config_settings_table, $agingtable_key);;

        if ($id_chosen_agingtable == $id_agingtable_to_delete){
            return FALSE;
        }
        else {
            open_connection();
            
            $sql = 'DROP TABLE agingtable_' . $agingtable;
            execute_query($sql);
            
            $sql = 'DELETE FROM ' . $agingtables_table . ' WHERE "' . $id_field . '" = "' . $id_agingtable_to_delete . '"';
            execute_query($sql);
            
            close_database();
            
            return TRUE;
        }    
    }
    
    function import_csv_to_sqlite($csv_path)
    {
        global $connection, $agingtables_table, $agingtable_name_field;
        if (($csv_handle = fopen($csv_path, "r")) === FALSE)
            throw new Exception('Cannot open CSV file');
            
        $delimiter = ',';
        $tablename = preg_replace("/[^A-Z0-9]/i", '', basename($csv_path, '.csv'));
        $table = 'agingtable_' . $tablename;
        
        $fields = array_map(
            function ($field){
                return strtolower(preg_replace("/[^A-Z0-9_]/i", '', $field));
            }, fgetcsv($csv_handle, 0, $delimiter));
        
        $fieldcount = count($fields);
        if ($fieldcount == 9){
            if ($fields[0] == 'modus' AND 
                $fields[1] == 'setpoint_humidity' AND 
                $fields[2] == 'setpoint_temperature' AND 
                $fields[3] == 'circulation_air_duration' AND 
                $fields[4] == 'circulation_air_period' AND 
                $fields[5] == 'exhaust_air_duration' AND 
                $fields[6] == 'exhaust_air_period' AND 
                $fields[7] == 'days' AND 
                $fields[8] == 'comment'){

                $create_fields_str = join(', ', array_map(function ($field){
                    if ($field == 'comment'){
                        return "$field TEXT NULL";
                    }
                    else{
                        return "$field INTEGER NULL";
                    }
                }, $fields));

                open_connection();
                
                $create_table_sql = 'CREATE TABLE IF NOT EXISTS ' .$table . ' (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ' . $create_fields_str . ');';
                execute_query($create_table_sql);
                
                
                $insert_fields_str = join(', ', $fields);
                // $insert_values_str = join(', ', array_fill(0, count($fields),  '?'));
                // $insert_sql = "INSERT INTO $table ($insert_fields_str) VALUES ($insert_values_str)";
                // $insert_sth = $connection->prepare($insert_sql);
                
                // $inserted_rows = 0;
                // while (($data = fgetcsv($csv_handle, 0, $delimiter)) !== FALSE) {
                    // $insert_sth->execute($data);
                    // $inserted_rows++;
                // }
                
                while (($data = fgetcsv($csv_handle, 0, $delimiter)) !== FALSE) {
                    $num = count($data);
                    
                    $valuestring = '';
                    for ($c=0; $c < $num; $c++) {
                        $datafield = $data[$c];
                        if ($datafield == NULL){
                            $datafield = 'NULL';
                        }
                        if ($c == 0){
                            $valuestring = $datafield;
                        }
                        else{
                            $valuestring = $valuestring . ', ' . $datafield;
                        }
                    }
                    $sql = 'INSERT INTO ' . $table . ' (' . $insert_fields_str . ' ) VALUES (' . $valuestring . ')';
                    execute_query($sql);
                }
                $sql = 'INSERT INTO "' . $agingtables_table . '" ("' . $agingtable_name_field . '") VALUES ("'. $tablename . '")';
                execute_query($sql);
                
                close_database();
                fclose($csv_handle);
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    
    
    function write_loglevel($chosen_loglevel_file, $chosen_loglevel_console){
        global $value_field, $last_change_field, $key_field, $loglevel_console_key, $loglevel_file_key, $debug_table;
        
        open_connection();
        $sql = 'UPDATE ' . $debug_table . ' SET "' . $value_field . '" = ' . $chosen_loglevel_file . ' WHERE ' . $key_field . ' = "' . $loglevel_file_key . '";';
        $sql = $sql . ' UPDATE ' . $debug_table . ' SET "' . $value_field . '" = ' . $chosen_loglevel_console . ' WHERE ' . $key_field . ' = "' . $loglevel_console_key . '";';
        execute_query($sql);
        
        close_database();
    }
    
    function get_loglevel($destination){
        global $value_field, $debug_table, $key_field;
        
        $log_level = get_table_value($debug_table, $destination);
        return $log_level;
        // open_connection();
        
        // $sql = 'SELECT ' . $value_field . ' FROM ' . $debug_table . ' WHERE ' . $key_field . ' = "' . $destination . '"';
        // $result = get_query_result($sql);
        
        // $row = $result->fetchArray();
        
        // close_database();
        
        //return $row;
    }
    
    function get_calibrate_status($calibrate_scale){
        global $current_values_table;
        
        $calibrate_status = get_table_value($current_values_table, $calibrate_scale);

        return $calibrate_status;
    }
    
    function get_tara_status($tara_scale_key){
        global $current_values_table;
        
        $tara_status = get_table_value($current_values_table, $tara_scale_key);

        return $tara_status;
    }
    
    function write_debug_values($chosen_measuring_interval_debug, $chosen_agingtable_days_in_seconds_debug){
        global $value_field, $last_change_field, $key_field, $agingtable_days_in_seconds_debug_key, $measuring_interval_debug_key, $debug_table;
        
        open_connection();
        $sql = 'UPDATE ' . $debug_table . ' SET "' . $value_field . '" = ' . $chosen_measuring_interval_debug . ' WHERE ' . $key_field . ' = "' . $measuring_interval_debug_key . '";';
        $sql = $sql . ' UPDATE ' . $debug_table . ' SET "' . $value_field . '" = ' . $chosen_agingtable_days_in_seconds_debug . ' WHERE ' . $key_field . ' = "' . $agingtable_days_in_seconds_debug_key . '";';
        execute_query($sql);
        
        close_database();
    }
    
    function write_busvalue($chosen_busvalue){
        global $value_field, $key_field, $config_settings_table, $sensorbus_key;
        
        open_connection();
        $sql = 'UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($chosen_busvalue) . ' WHERE ' . $key_field . ' = "' . $sensorbus_key . '";';
        execute_query($sql);
        
        close_database();
    }
    
    function write_sensorvalue($chosen_sensorvalue){
        global $value_field, $key_field, $config_settings_table, $sensortype_key;
        
        open_connection();
        $sql = 'UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($chosen_sensorvalue) . ' WHERE ' . $key_field . ' = "' . $sensortype_key . '";';
        execute_query($sql);
        
        close_database();
    }
    
    function write_sensorsecondvalue($chosen_sensorvalue){
        global $value_field, $key_field, $config_settings_table, $sensorsecondtype_key;
        
        open_connection();
        $sql = 'UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($chosen_sensorvalue) . ' WHERE ' . $key_field . ' = "' . $sensorsecondtype_key . '";';
        execute_query($sql);
        
        close_database();
    }
    
    function write_settings($modus, $setpoint_temperature, $setpoint_humidity, $circulation_air_period, $circulation_air_duration, $exhaust_air_period,
                            $exhaust_air_duration)
        {
        global $value_field, $last_change_field, $key_field, $config_settings_table, $modus_key, $setpoint_temperature_key, $setpoint_humidity_key, $circulation_air_period_key, $circulation_air_duration_key, $exhaust_air_period_key, $exhaust_air_duration_key;
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

    function write_config($switch_on_cooling_compressor, $switch_off_cooling_compressor,
                            $switch_on_humidifier, $switch_off_humidifier, $delay_humidify, $uv_modus, $uv_duration, 
                            $uv_period, $switch_on_uv_hour, $switch_on_uv_minute, $light_modus, $light_duration, 
                            $light_period, $switch_on_light_hour, $switch_on_light_minute, $dehumidifier_modus, 
                            $failure_temperature_delta, $failure_humidity_delta)
        {
        global $value_field, $last_change_field, $key_field, $config_settings_table, $switch_on_cooling_compressor_key,
                $switch_off_cooling_compressor_key, $switch_on_humidifier_key, $switch_off_humidifier_key, $delay_humidify_key, $uv_modus_key,
                $uv_duration_key, $uv_period_key, $switch_on_uv_hour_key, $switch_on_uv_minute_key, $light_modus_key, $light_duration_key, $light_period_key,
                $switch_on_light_hour_key, $switch_on_light_minute_key, $dehumidifier_modus_key, $failure_temperature_delta_key, $failure_humidity_delta_key; 
        open_connection();

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
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($failure_temperature_delta) . ' WHERE ' . $key_field . ' = "' . $failure_temperature_delta_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($failure_humidity_delta) . ' WHERE ' . $key_field . ' = "' . $failure_humidity_delta_key . '"');
        
        close_database();
        }
    
    function write_admin($language, $referenceunit_scale1, $measuring_interval_scale1, $measuring_duration_scale1, $saving_period_scale1, $samples_scale1, $spikes_scale1,
                            $referenceunit_scale2, $measuring_interval_scale2, $measuring_duration_scale2, $saving_period_scale2, $samples_scale2, $spikes_scale2,
                            $temp_sensor1, $temp_sensor2, $temp_sensor3, $temp_sensor4)
    {
        global $value_field, $last_change_field, $key_field, $config_settings_table, $settings_scale1_table, $settings_scale2_table, $sensortype_key, $language_key;
        global $referenceunit_key, $scale_measuring_interval_key, $measuring_duration_key, $saving_period_key, $samples_key, $spikes_key;
        global $meat1_sensortype_key, $meat2_sensortype_key, $meat3_sensortype_key, $meat4_sensortype_key;
        
        open_connection();
        
      #  get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($sensortype) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $sensortype_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($language) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $language_key . '"');
 
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($temp_sensor1) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $meat1_sensortype_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($temp_sensor2) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $meat2_sensortype_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($temp_sensor3) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $meat3_sensortype_key . '"');
        get_query_result('UPDATE ' . $config_settings_table . ' SET "' . $value_field . '" = ' . strval($temp_sensor4) . ' , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' ="' . $meat4_sensortype_key . '"');
   
        get_query_result('UPDATE ' . $settings_scale1_table . ' SET "' . $value_field . '" = ' . strval($referenceunit_scale1) . ' WHERE ' . $key_field . ' = "' . $referenceunit_key . '"');
        get_query_result('UPDATE ' . $settings_scale1_table . ' SET "' . $value_field . '" = ' . strval($measuring_interval_scale1) . ' WHERE ' . $key_field . ' = "' . $scale_measuring_interval_key . '"');
        get_query_result('UPDATE ' . $settings_scale1_table . ' SET "' . $value_field . '" = ' . strval($measuring_duration_scale1) . ' WHERE ' . $key_field . ' = "' . $measuring_duration_key . '"');
        get_query_result('UPDATE ' . $settings_scale1_table . ' SET "' . $value_field . '" = ' . strval($saving_period_scale1) . ' WHERE ' . $key_field . ' = "' . $saving_period_key . '"');
        get_query_result('UPDATE ' . $settings_scale1_table . ' SET "' . $value_field . '" = ' . strval($samples_scale1) . ' WHERE ' . $key_field . ' = "' . $samples_key . '"');
        get_query_result('UPDATE ' . $settings_scale1_table . ' SET "' . $value_field . '" = ' . strval($spikes_scale1) . ' WHERE ' . $key_field . ' = "' . $spikes_key . '"');
        
        
        get_query_result('UPDATE ' . $settings_scale2_table . ' SET "' . $value_field . '" = ' . strval($referenceunit_scale2) . ' WHERE ' . $key_field . ' = "' . $referenceunit_key . '"');
        get_query_result('UPDATE ' . $settings_scale2_table . ' SET "' . $value_field . '" = ' . strval($measuring_interval_scale2) . ' WHERE ' . $key_field . ' = "' . $scale_measuring_interval_key . '"');
        get_query_result('UPDATE ' . $settings_scale2_table . ' SET "' . $value_field . '" = ' . strval($measuring_duration_scale2) . ' WHERE ' . $key_field . ' = "' . $measuring_duration_key . '"');
        get_query_result('UPDATE ' . $settings_scale2_table . ' SET "' . $value_field . '" = ' . strval($saving_period_scale2) . ' WHERE ' . $key_field . ' = "' . $saving_period_key . '"');
        get_query_result('UPDATE ' . $settings_scale2_table . ' SET "' . $value_field . '" = ' . strval($samples_scale2) . ' WHERE ' . $key_field . ' = "' . $samples_key . '"');
        get_query_result('UPDATE ' . $settings_scale2_table . ' SET "' . $value_field . '" = ' . strval($spikes_scale2) . ' WHERE ' . $key_field . ' = "' . $spikes_key . '"');
        
        close_database();
    }
    
    function write_start_in_database($module_key)
    {
        write_startstop_status_in_database($module_key, 1);
    }

    function write_stop_in_database($module_key)
    {
        write_startstop_status_in_database($module_key, 0);
    }

    function write_startstop_status_in_database($module_key, $status)
    {
        global $current_values_table, $value_field, $last_change_field, $key_field;
        
        open_connection();
        
        $sql = 'UPDATE ' . $current_values_table . ' SET "' . $value_field . '" = "' . strval($status) . '" , "' . $last_change_field . '" = ' . strval(get_current_time()) . ' WHERE ' . $key_field . ' = "' . $module_key . '"';
        execute_query($sql);
        
        close_database();
    }
    
    function set_null_if_empty($value){
        if ($value == '' OR $value == NULL){
            $value = 'NULL';
        }
        return $value;
    }
    //Statistik Tabellen leeren
    function delete_statistic_tables()
    {
        global $data_sensor_temperature_table, $data_sensor_humidity_table, $data_sensor_dewpoint_table, $data_sensor_extern_temperature_table, $data_sensor_extern_humidity_table, $data_sensor_extern_dewpoint_table, $status_heater_table, $status_exhaust_air_table, $status_cooling_compressor_table, $status_circulating_air_table, $status_uv_table, $status_light_table, $status_humidifier_table, $status_dehumidifier_table, $data_scale1_table, $data_scale2_table, $data_sensor_temperature_meat1_table, $data_sensor_temperature_meat2_table, $data_sensor_temperature_meat3_table, $data_sensor_temperature_meat4_table;

        delete_data($data_sensor_temperature_table);
        delete_data($data_sensor_humidity_table);
        delete_data($data_sensor_dewpoint_table);
        delete_data($data_sensor_extern_temperature_table);
        delete_data($data_sensor_extern_humidity_table);
        delete_data($data_sensor_extern_dewpoint_table);
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
        logger('DEBUG', 'delete_statistic_tables performed');
    }
     
     function delete_data($table_name)
    {
        open_connection();
        $sql = 'DELETE FROM ' . $table_name;
        get_query_result($sql);
        $sql = 'VACUUM';
        get_query_result($sql);
        close_database();
        logger('DEBUG', 'delete_data performed');
    }

    function get_table_value_from_field($table, $key, $field)
    {
        global $id_field;
        
        $value = NULL;
        open_connection();
        if ($key == NULL){
            $sql = 'SELECT ' . $field . ' FROM ' . $table . ' WHERE ' . $id_field . ' = (SELECT MAX(' . $id_field . ') from ' . $table . ')';
        }
        else {
            $sql = 'SELECT ' . $field . ' FROM ' . $table . ' WHERE key = "' . $key . '" AND ' . $id_field . ' = (SELECT MAX(' . $id_field . ') from ' . $table . ' WHERE key = "' . $key . '")';
        }
        $result = get_query_result($sql);
            while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
                {
                $value = $dataset[$field];
                }
        close_database();
        
        return $value;
    }
    
    function write_backupvalues($backup_nfsvol, $backup_subdir, $backup_nfsmount, $backup_path, $backup_number_of_backups, $backup_name, $backup_nfsopt, $backup_active){
        global $id_field, $backup_table, $backup_nfsvol_field, $backup_subdir_field, $backup_nfsmount_field, $backup_path_field, $backup_number_of_backups_field, $backup_name_field, $backup_nfsopt_field, $backup_active_field;
        
        open_connection();
        $sql = 'UPDATE ' . $backup_table . ' SET "' . $backup_nfsvol_field . '" = "' . $backup_nfsvol . '" , "' . $backup_subdir_field . '" = "' . $backup_subdir . '" , "' . $backup_nfsmount_field . '" = "' . $backup_nfsmount . '" , "' . $backup_path_field . '" = "' . $backup_path . '" , "' . $backup_number_of_backups_field . '" = ' . $backup_number_of_backups . ' , "' . $backup_name_field . '" = "' . $backup_name . '" , "' . $backup_nfsopt_field . '" = "' . $backup_nfsopt . '" , "' . $backup_active_field . '" = "' . $backup_active . '" ' . ' WHERE ' . $id_field . ' = 1';
        execute_query($sql);
        
        close_database();
    }
    
    function write_table_value($table, $field_key, $key, $field_value, $value){
        open_connection();
        $sql = 'UPDATE ' . $table . ' SET "' . $field_value . '" = "' . $value . '" WHERE "' . $field_key . '" = "' . $key .'"';
        execute_query($sql);
        
        close_database();
    }

    function get_alarm_names(){
        global $alarm_alarm_field, $alarm_table;
        
        open_connection();
        $sql = 'SELECT ' . $alarm_alarm_field . ' FROM ' . $alarm_table;
        $result = get_query_result($sql);
        $index = 0;
        while ($dataset = $result->fetchArray(SQLITE3_ASSOC))
            {
            $alarm_names[$index] = $dataset[$alarm_alarm_field];
            $index++;
            }
        close_database();
        
        return $alarm_names;
    }
?>
