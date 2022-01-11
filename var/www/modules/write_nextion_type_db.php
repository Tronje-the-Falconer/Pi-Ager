<?php 
    if (isset ($_POST['save_nextion_display_type'])){
        logger('DEBUG', 'button save Nextion display type pressed');
        $nextion_display = $_POST['tft_display_type_admin'];
        write_table_value($config_settings_table, $key_field, $tft_display_type_key, $value_field, $nextion_display);

        logger('DEBUG', 'Nextion display type saved');
        print '<script language="javascript"> alert("'. (_("Nextion display type saved")) .'"); </script>';                            
    }
?>