<?php
    //Standard Datenbank
    
    function create_database_scheme()
    {
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
        drop_and_create_agingtable_list($agingtables_table);
        drop_and_create_agingtable('agingtable_salami');
        drop_and_create_agingtable('dryaging1');
        drop_and_create_agingtable('dryaging2');
    }
    
    // Drop And Create
    function drop_and_create_id_value_table($table)
    {
        open_connection();
        
        $sql = 'DROP TABLE IF EXISTS "'$table'";';
        execute_query($sql);
        $sql = 'CREATE TABLE "'$table'" ("'$id_field'" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "'$value_field'" REAL NOT NULL, "'$last_change_field'" INTEGER NOT NULL);';
        execute_query($sql);
        
        close_database();
    }
    
    function drop_and_create_key_value_table($table)
    {
        open_connection();
        
        $sql = 'DROP TABLE IF EXISTS "'$table'";';
        execute_query($sql);
        $sql = 'CREATE TABLE "'$table'" ("'$id_field'" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "'$key_field'" TEXT NOT NULL, "'$value_field'" REAL NOT NULL, "'$last_change_field'" INTEGER NOT NULL);';
        execute_query($sql);
        
        close_database();
        
        switch ($table) {
            case $current_values_table:
                insert_current_values();
                break;
            case $settings_scale1_table:
                insert_scale_settings_values($settings_scale1_table);
                break;
            case $settings_scale2_table:
                insert_scale_settings_values($settings_scale2_table);
                break;
             case $config_settings_table:
                insert_config_settings_values();
                break;
             case $debug_table:
                insert_debug_values();
                break;
        }
        
    }
    
    function drop_and_create_agingtable_list($table)
    {
        open_connection();
        
        $sql = 'DROP TABLE IF EXISTS "'$table'";';
        execute_query($sql);
        $sql = 'CREATE TABLE "'$table'" ("'$id_field'" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "'$agingtable_name_field'" TEXT NOT NULL);';
        execute_query($sql);
        
        close_database();
        insert_agingtable_list($table);

     }
     
    function drop_and_create_agingtable($table)
     {
         open_connection();
         
         $sql = 'DROP TABLE IF EXISTS "'$table'";';
         execute_query($sql);
         $sql = 'CREATE TABLE "'$table'" ("'$id_field'" INTEGER PRIMARY KEY NOT NULL , "'$agingtable_modus_field'" INTEGER, "'$agingtable_setpoint_humidity_field'" INTEGER, "'$agingtable_setpoint_temperature_field'" INTEGER, "'$agingtable_circulation_air_duration_field'" INTEGER,"'$agingtable_circulation_air_period_field'" INTEGER, "'$agingtable_exhaust_air_duration_field'" INTEGER, "'$agingtable_exhaust_air_period_field'" INTEGER, "'$agingtable_days_field'" INTEGER NOT NULL);';
         execute_query($sql);
         
         close_database();
         
         switch ($table) {
            case 'salami':
                insert_salami_values();
                break;
            case 'dryaging1':
                insert_dryaging1_values();
                break;
            case 'dryaging2':
                insert_dryaging2_values();
                break;
         }
     }
    

    ///Inserts
        
        
    function insert_current_values()
    {
        open_connection();
        
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("1","sensor_temperature","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("2","sensor_humidity","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("3","status_circulating_air","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("4","status_cooling_compressor","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("5","status_exhaust_air","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("6","status_heater","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("7","status_light","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("8","status_uv","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("9","status_humidifier","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("10","status_dehumidifier","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("11","scale1","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("12","scale2","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("13","status_piager","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("14","status_agingtable","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("15","status_scale1","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("16","status_scale2","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("17","status_tara_scale1","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("18","status_tara_scale2","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("19","sensor_temperature_meat1","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("20","sensor_temperature_meat2","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("21","sensor_temperature_meat3","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("22","sensor_temperature_meat4","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("23","agingtable_period","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("24","agingtable_period_starttime","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$current_values_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("25","status_light_manual","0.0","0");';
        execute_query($sql);
        
        close_database();
    }    
     
     function insert_scale_settings_values($settings_scale_table)
     {
        open_connection();
         
        $sql = 'INSERT INTO "'$settings_scale_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("1","samples","300.0", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$settings_scale_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("2","spikes","60.0", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$settings_scale_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("3","sleep","0.1", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$settings_scale_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("6","gain","128.0", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$settings_scale_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("7","bits_to_read","24.0", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$settings_scale_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("8","referenceunit","125.0", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$settings_scale_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("9","measuring_interval","120.0", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$settings_scale_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("11","measuring_duration","60.0", "0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$settings_scale_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("12","saving_period","1.0", "0");';
        execute_query($sql);
        
        close_database();
     }
        
     function insert_debug_values()
     {
        open_connection();
          
        $sql = 'INSERT INTO "'$debug_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("1","measuring_interval_debug","30","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$debug_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("2","agingtable_days_in_seconds_debug","1","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$debug_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("3","loglevel_file","10","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$debug_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("4","loglevel_console","10","0");';
        execute_query($sql);
        
        close_database();
     }     
     
     function insert_config_values()
     {
        open_connection();
        
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("1","switch_on_cooling_compressor","1.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("2","switch_off_cooling_compressor","0.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("3","switch_on_humidifier","5.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("4","switch_off_humidifier","4.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("5","delay_humidify","5.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("8","sensortype","3.0","1511267856");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("9","language","1.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("10","switch_on_light_hour","14.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("11","switch_on_light_minute","13.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("12","light_duration","60.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("13","light_period","180.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("14","light_modus","1.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("15","switch_on_uv_hour","15.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("16","switch_on_uv_minute","13.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("17","uv_duration","60.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("18","uv_period","120.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("19","uv_modus","1.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("20","dehumidifier_modus","2.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("21","circulation_air_period","5400.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("22","setpoint_temperature","2.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("23","exhaust_air_duration","900.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("24","modus","4.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("25","setpoint_humidity","100.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("26","exhaust_air_period","21600.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("27","circulation_air_duration","900.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("28","agingtable","1.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("31","failure_humidity_delta","10.0","0");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$config_settings_table'" ("'$id_field'","'$key_field'","'$value_field'","'$last_change_field'") VALUES ("32","failure_temperature_delta","3.0","0");';
        execute_query($sql);
        
        close_database();
     }

     function insert_agingtable_list($table)
     {
        open_connection();
        
        $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_name_field'") VALUES ("1","salami");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_name_field'") VALUES ("2","dryaging1");';
        execute_query($sql);
        $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_name_field'") VALUES ("3","dryaging2");';
        execute_query($sql);
        
        close_database();
     }
     function insert_salami_values($table)
     {
         open_connection();
        
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("1","4","93","21","900","3600","900","21600","1");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("2",NULL,NULL,"20",NULL,NULL,NULL,NULL,"1");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("3",NULL,"92","19",NULL,NULL,NULL,NULL,"2");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("4",NULL,"91","18",NULL,NULL,NULL,NULL,"2");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("5",NULL,"90","17",NULL,"5400",NULL,NULL,"2");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("6",NULL,"89","16",NULL,NULL,NULL,NULL,"2");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("7",NULL,"88","15",NULL,NULL,NULL,NULL,"1");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("8",NULL,"87",NULL,NULL,NULL,NULL,NULL,"1");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("9",NULL,"86","14",NULL,NULL,NULL,NULL,"1");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("10",NULL,"85","13",NULL,NULL,NULL,NULL,"1");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("11",NULL,"80","12",NULL,"7200",NULL,NULL,"7");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("12",NULL,"75",NULL,NULL,"10800",NULL,NULL,"7");';
         execute_query($sql);
         
         close_database();
     }
     
     function insert_dryaging1_values($table)
     {
         open_connection();
         
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("1","4","75","4","1080","2520","900","15120","12");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("2",NULL,"65","5","900","2700",NULL,"16200","8");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("3",NULL,"55","6","720","2880",NULL,"17280","16");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("4",NULL,"40","7","540","3060",NULL,"18360","8");';
         execute_query($sql);
         
         close_database();
     }
     
     function insert_dryaging2_values($table)
     {
         open_connection();
         
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("1","4","85","2","1440","2160","900","12960","12");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("2",NULL,"30","4","2520","1080",NULL,"6480","8");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("3",NULL,"70",NULL,"1080","2520",NULL,"15120","8");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("4",NULL,"60",NULL,"720","2880",NULL,"17280","8");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("5",NULL,"45","7","648","2952",NULL,"17712","12");';
         execute_query($sql);
         $sql = 'INSERT INTO "'$table'" ("'$id_field'","'$agingtable_modus_field'","'$agingtable_setpoint_humidity_field'","'$agingtable_setpoint_temperature_field'","'$agingtable_circulation_air_duration_field'","'$agingtable_circulation_air_period_field'","'$agingtable_exhaust_air_duration_field'","'$agingtable_exhaust_air_period_field'","'$agingtable_days_field'") VALUES ("6",NULL,"33",NULL,"540","3060",NULL,"18360","8");';
         execute_query($sql);
         
         close_database();
     }
 ?>