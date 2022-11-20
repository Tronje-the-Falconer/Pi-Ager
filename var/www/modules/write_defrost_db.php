<?php 

    if (isset ($_POST['save_defrost_values'])){
        logger('DEBUG', 'button save defrost values pressed');
        unset($_POST['save_defrost_values']);
        $defrost_active = $_POST['defrost_active'];
        $defrost_temperature = $_POST['defrost_temperature'];
        $defrost_cycle_hours = $_POST['defrost_cycle_hours'];

        write_defrost_values($defrost_temperature, $defrost_cycle_hours, $defrost_active);
        logger('DEBUG', 'defrost values saved');
        print '<script language="javascript"> alert("'. (_("defrost values")) . " : " . (_("values saved")) .'"); </script>';
    }
?>
