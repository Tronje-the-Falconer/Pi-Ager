<?php
    
    function backup_database()
    {
        global $sqlite_path;
        
        $now = date("_Y-m-d_H-i-s");
        $backupfilename = $sqlite_path . $now;
        
        copy( $sqlite_path, $backupfilename);
        logger('DEBUG', 'backup_database performed');
    }
    
    function rename_database()
    {
        global $sqlite_path;
        
        $now = date("_Y-m-d_H-i-s");
        $renamedfilename = $sqlite_path . $now;
        
        rename( $sqlite_path, $renamedfilename);
        logger('DEBUG', 'rename_database performed');
    }
    
    function create_new_database()
    {
         global $sqlite_path;
         
        if ($db = sqlite_open($sqlite_path, 0666, $sqliteerror))
        {
            create_database_scheme();
            logger('DEBUG', 'create_new_database performed');
        }
        else
        {
            logger('DEBUG', 'backup_database not performed ' . $sqliteerror);
            die ($sqliteerror);
        }
    }
    
    function delete_database()
    {
        global $sqlite_path;
        
        unlink($sqlite_path);
        logger('DEBUG', 'delete_database performed');
    }
    
    //Standard Datenbank
    function create_database_scheme()
    {
        global $data_scale1_table, $data_scale2_table, $status_cooling_compressor_table, $status_heater_table, $data_sensor_humidity_table, $status_circulating_air_table,
        $data_sensor_temperature_table, $status_exhaust_air_table, $data_sensor_temperature_meat1_table, $data_sensor_temperature_meat2_table, $data_sensor_temperature_meat3_table, $data_sensor_temperature_meat4_table,
        $status_light_table, $status_uv_table, $status_humidifier_table, $status_dehumidifier_table, $current_values_table, $settings_scale1_table, $settings_scale2_table,
        $config_settings_table, $debug_table, $agingtables_table, $agingtable_salami_table, $agingtable_dryaging1_table, $agingtable_dryaging2_table, $system_table;
        

        drop_and_create_id_value_table($data_scale1_table);
        drop_and_create_id_value_table($data_scale2_table);
        drop_and_create_id_value_table($status_cooling_compressor_table);
        drop_and_create_id_value_table($status_heater_table);
        drop_and_create_id_value_table($data_sensor_humidity_table);
        drop_and_create_id_value_table($status_circulating_air_table);
        drop_and_create_id_value_table($data_sensor_temperature_table);
        drop_and_create_id_value_table($status_exhaust_air_table);
        drop_and_create_id_value_table($data_sensor_temperature_meat1_table);
        drop_and_create_id_value_table($data_sensor_temperature_meat2_table);
        drop_and_create_id_value_table($data_sensor_temperature_meat3_table);
        drop_and_create_id_value_table($data_sensor_temperature_meat4_table);
        drop_and_create_id_value_table($status_light_table);
        drop_and_create_id_value_table($status_uv_table);
        drop_and_create_id_value_table($status_humidifier_table);
        drop_and_create_id_value_table($status_dehumidifier_table);
        drop_and_create_key_value_table($current_values_table);
        drop_and_create_key_value_table($settings_scale1_table);
        drop_and_create_key_value_table($settings_scale2_table);
        drop_and_create_key_value_table($config_settings_table);
        drop_and_create_key_value_table($debug_table);
        drop_and_create_key_value_table($system_table);
        drop_and_create_agingtable_list($agingtables_table);
        drop_and_create_agingtable($agingtable_salami_table);
        drop_and_create_agingtable($agingtable_dryaging1_table);
        drop_and_create_agingtable($agingtable_dryaging2_table);
        logger('DEBUG', 'create_database_scheme performed');
    }
    
    // Drop And Create
    function drop_and_create_id_value_table($table)
    {
        global $id_field, $value_field, $last_change_field;
        open_connection();
        
        $sql = 'DROP TABLE IF EXISTS "' . $table . '";';
        execute_query($sql);
        $sql = 'CREATE TABLE "' . $table . '" ("' . $id_field . '" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "' . $value_field . '" REAL NOT NULL, "' . $last_change_field . '" INTEGER NOT NULL);';
        execute_query($sql);
        
        close_database();
        logger('DEBUG', 'drop_and_create_id_value_table performed');
    }
    
    function drop_and_create_key_value_table($table)
    {
        global $id_field, $key_field, $value_field, $last_change_field, $current_values_table, $settings_scale1_table, $settings_scale2_table, $config_settings_table, $debug_table, $system_table;
        
        open_connection();
        
        $sql = 'DROP TABLE IF EXISTS "' . $table . '";';
        execute_query($sql);
        $sql = 'CREATE TABLE "' . $table . '" ("' . $id_field . '" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "' . $key_field . '" TEXT NOT NULL, "' . $value_field . '" REAL NOT NULL, "' . $last_change_field . '" INTEGER NOT NULL);';
        execute_query($sql);
        
        close_database();
        
        switch ($table) {
            case $current_values_table:
                insert_current_values($current_values_table);
                break;
            case $settings_scale1_table:
                insert_scale_settings_values($settings_scale1_table);
                break;
            case $settings_scale2_table:
                insert_scale_settings_values($settings_scale2_table);
                break;
             case $config_settings_table:
                insert_config_settings_values($config_settings_table);
                break;
             case $debug_table:
                insert_debug_values($debug_table);
                break;
            case $system_table:
                insert_system_values($system_table);
        }
        logger('DEBUG', 'drop_and_create_key_value_table performed');
        
    }
    
    function drop_and_create_agingtable_list($table)
    {
        global $id_field, $agingtable_name_field;
        
        open_connection();
        
        $sql = 'DROP TABLE IF EXISTS "' . $table . '";';
        execute_query($sql);
        $sql = 'CREATE TABLE "' . $table . '" ("' . $id_field . '" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "' . $agingtable_name_field . '" TEXT NOT NULL);';
        execute_query($sql);
        
        close_database();
        insert_agingtable_list($table);
        logger('DEBUG', 'drop_and_create_agingtable_list performed');

     }
     
    function drop_and_create_agingtable($table)
     {
         global $id_field, $agingtable_modus_field, $agingtable_setpoint_humidity_field, $agingtable_setpoint_temperature_field, $agingtable_circulation_air_duration_field, $agingtable_circulation_air_period_field, $agingtable_exhaust_air_duration_field, $agingtable_exhaust_air_period_field, $agingtable_days_field,
         $agingtable_salami_table, $agingtable_dryaging1_table, $agingtable_dryaging2_table;
         
         open_connection();
         
         $sql = 'DROP TABLE IF EXISTS "' . $table . '";';
         execute_query($sql);
         $sql = 'CREATE TABLE "' . $table . '" ("' . $id_field . '" INTEGER PRIMARY KEY NOT NULL , "' . $agingtable_modus_field . '" INTEGER, "' . $agingtable_setpoint_humidity_field . '" INTEGER, "' . $agingtable_setpoint_temperature_field . '" INTEGER, "' . $agingtable_circulation_air_duration_field . '" INTEGER,"' . $agingtable_circulation_air_period_field . '" INTEGER, "' . $agingtable_exhaust_air_duration_field . '" INTEGER, "' . $agingtable_exhaust_air_period_field . '" INTEGER, "' . $agingtable_days_field . '" INTEGER NOT NULL);';
         execute_query($sql);
         
         close_database();
         
         switch ($table) {
            case 'salami':
                insert_salami_values($agingtable_salami_table);
                break;
            case 'dryaging1':
                insert_dryaging1_values($agingtable_dryaging1_table);
                break;
            case 'dryaging2':
                insert_dryaging2_values($agingtable_dryaging2_table);
                break;
         }
         logger('DEBUG', 'drop_and_create_agingtable performed');
     }
    
    //Statistik Tabellen leeren
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
    
    
    ///Inserts
    function insert_system_values($table)
    {
        global $id_field, $key_field, $value_field, $last_change_field, $pi_revision_key;
        
        open_connection();
        
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("1","' . $pi_revision_key . '","0000","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("2","' . $$pi_ager_version . '","2.2.1","0");';
        execute_query($sql);
        
        close_database();
        logger('DEBUG', 'insert_system_values performed');
    }
        
    function insert_current_values($table)
    {
        global $id_field, $key_field, $value_field, $last_change_field, $sensor_temperature_key, $sensor_humidity_key, $status_circulating_air_key, $status_cooling_compressor_key,
        $status_exhaust_air_key, $status_heater_key, $status_light_key, $status_uv_key, $status_humidifier_key, $status_dehumidifier_key, $scale1_key, $scale2_key,
        $status_piager_key, $status_agingtable_key, $status_scale1_key, $status_scale2_key, $status_tara_scale1_key, $status_tara_scale2_key, 
        $sensor_temperature_meat1_key, $sensor_temperature_meat2_key, $sensor_temperature_meat3_key, $sensor_temperature_meat4_key, $agingtable_period_key, $agingtable_period_starttime_key, $status_light_manual_key;
        
        open_connection();
        
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("1","' . $sensor_temperature_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("2","' . $sensor_humidity_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("3","' . $status_circulating_air_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("4","' . $status_cooling_compressor_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("5","' . $status_exhaust_air_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("6","' . $status_heater_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("7","' . $status_light_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("8","' . $status_uv_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("9","' . $status_humidifier_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("10","' . $status_dehumidifier_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("11","' . $scale1_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("12","' . $scale2_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("13","' . $status_piager_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("14","' . $status_agingtable_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("15","' . $status_scale1_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("16","' . $status_scale2_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("17","' . $status_tara_scale1_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("18","' . $status_tara_scale2_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("19","' . $sensor_temperature_meat1_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("20","' . $sensor_temperature_meat2_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("21","' . $sensor_temperature_meat3_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("22","' . $sensor_temperature_meat4_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("23","' . $agingtable_period_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("24","' . $agingtable_period_starttime_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("25","' . $status_light_manual_key . '","0.0","0");';
        execute_query($sql);
        
        close_database();
        logger('DEBUG', 'insert_current_values performed');
    }    
     
     function insert_scale_settings_values($table)
     {
         global $id_field, $key_field, $value_field, $last_change_field, $samples_key, $spikes_key, $sleep_key, $gain_key, $bits_to_read_key, $referenceunit_key, $measuring_interval_key,
         $measuring_duration_key, $saving_period_key;
        open_connection();
         
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("1","' . $samples_key . '","300.0", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("2","' . $spikes_key . '","60.0", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("3","' . $sleep_key . '","0.1", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("6","' . $gain_key . '","128.0", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("7","' . $bits_to_read_key . '","24.0", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("8","' . $referenceunit_key . '","125.0", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("9","' . $measuring_interval_key . '","120.0", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("11","' . $measuring_duration_key . '","60.0", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("12","' . $saving_period_key . '","1.0", "0");';
        execute_query($sql);
        
        close_database();
        logger('DEBUG', 'insert_scale_settings_values performed');
     }
        
     function insert_debug_values($table)
     {
         global $id_field, $key_field, $value_field, $last_change_field, $measuring_interval_debug_key, $agingtable_days_in_seconds_debug_key, $loglevel_file_key, $loglevel_console_key;
        open_connection();
          
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("1","' . $measuring_interval_debug_key . '","30","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("2","' . $agingtable_days_in_seconds_debug_key . '","1","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("3","' . $loglevel_file_key . '","10","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("4","' . $loglevel_console_key . '","10","0");';
        execute_query($sql);
        
        close_database();
        logger('DEBUG', 'insert_debug_values performed');
     }     
     
     function insert_config_values($table)
     {
         global $id_field, $key_field, $value_field, $last_change_field, $switch_on_cooling_compressor_key, $switch_off_cooling_compressor_key, $switch_on_humidifier_key, $switch_off_humidifier_key, $delay_humidify_key,
         $sensortype_key, $language_key, $switch_on_light_hour_key, $switch_on_light_minute_key, $light_duration_key, $light_period_key, $light_modus_key, $switch_on_uv_hour_key,
         $switch_on_uv_minute_key, $uv_duration_key, $uv_period_key, $uv_modus_key, $dehumidifier_modus_key, $circulation_air_period_key, $setpoint_temperature_key, $exhaust_air_duration_key,
         $modus_key, $setpoint_humidity_key, $exhaust_air_period_key, $circulation_air_duration_key, $agingtable_key, $failure_humidity_delta_key, $failure_temperature_delta_key;
        open_connection();
        
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("1","' . $switch_on_cooling_compressor_key . '","1.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("2","' . $switch_off_cooling_compressor_key . '","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("3","' . $switch_on_humidifier_key . '","5.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("4","' . $switch_off_humidifier_key . '","4.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("5","' . $delay_humidify_key . '","5.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("8","' . $sensortype_key . '","3.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("9","' . $language_key . '","1.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("10","' . $switch_on_light_hour_key . '","14.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("11","' . $switch_on_light_minute_key . '","13.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("12","' . $light_duration_key . '","60.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("13","' . $light_period_key . '","180.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("14","' . $light_modus_key . '","1.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("15","' . $switch_on_uv_hour_key . '","15.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("16","' . $switch_on_uv_minute_key . '","13.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("17","' . $uv_duration_key . '","60.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("18","' . $uv_period_key . '","120.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("19","' . $uv_modus_key . '","1.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("20","' . $dehumidifier_modus_key . '","2.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("21","' . $circulation_air_period_key . '","5400.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("22","' . $setpoint_temperature_key . '","2.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("23","' . $exhaust_air_duration_key . '","900.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("24","' . $modus_key . '","4.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("25","' . $setpoint_humidity_key . '","100.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("26","' . $exhaust_air_period_key . '","21600.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("27","' . $circulation_air_duration_key . '","900.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("28","' . $agingtable_key . '","1.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("31","' . $failure_humidity_delta_key . '","10.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $key_field . '","' . $value_field . '","' . $last_change_field . '") VALUES ("32","' . $failure_temperature_delta_key . '","3.0","0");';
        execute_query($sql);
        
        close_database();
        logger('DEBUG', 'insert_config_values performed');
     }

     function insert_agingtable_list($table)
     {
         global $id_field, $agingtable_name_field;
        open_connection();
        
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_name_field . '") VALUES ("1","salami");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_name_field . '") VALUES ("2","dryaging1");';
        execute_query($sql);
        $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_name_field . '") VALUES ("3","dryaging2");';
        execute_query($sql);
        
        close_database();
        logger('DEBUG', 'insert_agingtable_list performed');
     }
     function insert_salami_values($table)
     {
         global $id_field, $agingtable_modus_field, $agingtable_setpoint_humidity_field, $agingtable_setpoint_temperature_field, $agingtable_circulation_air_duration_field, $agingtable_circulation_air_period_field, $agingtable_exhaust_air_duration_field, $agingtable_exhaust_air_period_field, $agingtable_days_field;
         open_connection();
        
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("1","4","93","21","900","3600","900","21600","1");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("2",NULL,NULL,"20",NULL,NULL,NULL,NULL,"1");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("3",NULL,"92","19",NULL,NULL,NULL,NULL,"2");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("4",NULL,"91","18",NULL,NULL,NULL,NULL,"2");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("5",NULL,"90","17",NULL,"5400",NULL,NULL,"2");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("6",NULL,"89","16",NULL,NULL,NULL,NULL,"2");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("7",NULL,"88","15",NULL,NULL,NULL,NULL,"1");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("8",NULL,"87",NULL,NULL,NULL,NULL,NULL,"1");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("9",NULL,"86","14",NULL,NULL,NULL,NULL,"1");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("10",NULL,"85","13",NULL,NULL,NULL,NULL,"1");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("11",NULL,"80","12",NULL,"7200",NULL,NULL,"7");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("12",NULL,"75",NULL,NULL,"10800",NULL,NULL,"7");';
         execute_query($sql);
         
         close_database();
         logger('DEBUG', 'insert_salami_values performed');
     }
     
     function insert_dryaging1_values($table)
     {
         global $id_field, $agingtable_modus_field, $agingtable_setpoint_humidity_field, $agingtable_setpoint_temperature_field, $agingtable_circulation_air_duration_field, $agingtable_circulation_air_period_field, $agingtable_exhaust_air_duration_field, $agingtable_exhaust_air_period_field, $agingtable_days_field;
         
         open_connection();
         
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("1","4","75","4","1080","2520","900","15120","12");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("2",NULL,"65","5","900","2700",NULL,"16200","8");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("3",NULL,"55","6","720","2880",NULL,"17280","16");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("4",NULL,"40","7","540","3060",NULL,"18360","8");';
         execute_query($sql);
         
         close_database();
         logger('DEBUG', 'insert_dryaging1_values performed');
     }
     
     function insert_dryaging2_values($table)
     {
         global $id_field, $agingtable_modus_field, $agingtable_setpoint_humidity_field, $agingtable_setpoint_temperature_field, $agingtable_circulation_air_duration_field, $agingtable_circulation_air_period_field, $agingtable_exhaust_air_duration_field, $agingtable_exhaust_air_period_field, $agingtable_days_field;
         
         open_connection();
         
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("1","4","85","2","1440","2160","900","12960","12");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("2",NULL,"30","4","2520","1080",NULL,"6480","8");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("3",NULL,"70",NULL,"1080","2520",NULL,"15120","8");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("4",NULL,"60",NULL,"720","2880",NULL,"17280","8");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("5",NULL,"45","7","648","2952",NULL,"17712","12");';
         execute_query($sql);
         $sql = 'INSERT INTO "' . $table . '" ("' . $id_field . '","' . $agingtable_modus_field . '","' . $agingtable_setpoint_humidity_field . '","' . $agingtable_setpoint_temperature_field . '","' . $agingtable_circulation_air_duration_field . '","' . $agingtable_circulation_air_period_field . '","' . $agingtable_exhaust_air_duration_field . '","' . $agingtable_exhaust_air_period_field . '","' . $agingtable_days_field . '") VALUES ("6",NULL,"33",NULL,"540","3060",NULL,"18360","8");';
         execute_query($sql);
         
         close_database();
         logger('DEBUG', 'insert_dryaging2_values performed');
     }
 ?>