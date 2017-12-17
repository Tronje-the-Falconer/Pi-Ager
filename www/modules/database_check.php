<?php
    function check_structure(){
        //agingtables
        $table_exists = check_if_table_exists($agingtables_table);
        if ($table_exists == true){ // Tabelle vorhanden
            $column_array = check_columns($agingtables_table);
            switch (false){
                case in_array($id_field, $column_array):{
                    echo 'id_field drin';
                }
                case !in_array('lala', $column_array):{
                    echo 'lala ist nicht drin';
                }
            }
            
            
            //check tabellenspalten
            //check tabellenzeilen
        }
        else{
            // Tabelle anlegen
            drop_and_create_agingtable_list($agingtables_table);
        }
        
        //circulating_air_status
        //$table_exists = get_query_result('SELECT * FROM' . $);
        //if ($table_exists == true){
            //check tabellenspalten
            //check tabellenzeilen
        //}
        //else{
            
        //}
        // config
        // cooling_compressor_status
        // current_values
        // debug
        // dehumidifier_status
        // exhaust_air_status
        // heater_status
        // humidifier_status
        // light_status
        // scale1_data
        // scale1_settings
        // scale2_data
        // scale2_settings
        // sensor_humidity_data
        // sensor_temperature_data
        // sensor_temperature_meat1_data
        // sensor_temperature_meat2_data
        // sensor_temperature_meat3_data
        // sensor_temperature_meat4_data
        // system
        // uv_status
    }
    if (isset ($_POST['database_maintenance'])){
        logger('DEBUG', 'button check database pressed');
        
        //checkbox Neue Datenbank
        //checkbox mit Sicherung der alten Datenbank unter anderem Namen
        //checkbox Standardwerte / oder verwenden der bereits gesetzten Werte
        
        if (isset ($_POST['new_database'])){
            if (isset ($_POST['default_values'])){
                if (isset ($_POST['with backup'])){
                    // neue Datenbank mit Defaultwerten und Sicherung der alten
                }
                else{
                    // neue Datenbank mit Defaultwerten ohne Sicherung
                    
                }
             }
             else{
                 // neue Datenbank mit bereits gesetzten Werten, verwerfen der alten
                 
             }
        }
        else{
            //keine Neue Datenbank sondern nur überprüfen
            check_structure();
        }
    }
?>