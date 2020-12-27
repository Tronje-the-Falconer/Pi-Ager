<?php 

    if (isset ($_POST['admin_form_submit'])){
        logger('DEBUG', 'button save adminvalues pressed');
#        $sensortype_admin = $_POST['sensortype_admin'];
        $language_admin = $_POST['language_admin'];
        $referenceunit_scale1_admin = $_POST['referenceunit_scale1_admin'];
        $measuring_interval_scale1_admin = $_POST['measuring_interval_scale1_admin'];
        $measuring_duration_scale1_admin = $_POST['measuring_duration_scale1_admin'];
        $saving_period_scale1_admin = $_POST['saving_period_scale1_admin'];
        $samples_scale1_admin = $_POST['samples_scale1_admin'];
        $spikes_scale1_admin = $_POST['spikes_scale1_admin'];
        $referenceunit_scale2_admin = $_POST['referenceunit_scale2_admin'];
        $measuring_interval_scale2_admin = $_POST['measuring_interval_scale2_admin'];
        $measuring_duration_scale2_admin = $_POST['measuring_duration_scale2_admin'];
        $saving_period_scale2_admin = $_POST['saving_period_scale2_admin'];
        $samples_scale2_admin = $_POST['samples_scale2_admin'];
        $spikes_scale2_admin = $_POST['spikes_scale2_admin'];
        
        $temp_sensor1_admin = $_POST['temp_sensor1_admin'];
        $temp_sensor2_admin = $_POST['temp_sensor2_admin'];
        $temp_sensor3_admin = $_POST['temp_sensor3_admin'];
        $temp_sensor4_admin = $_POST['temp_sensor4_admin'];
        
        write_admin($language_admin, $referenceunit_scale1_admin, $measuring_interval_scale1_admin, $measuring_duration_scale1_admin, $saving_period_scale1_admin, $samples_scale1_admin, $spikes_scale1_admin,
                                    $referenceunit_scale2_admin, $measuring_interval_scale2_admin, $measuring_duration_scale2_admin, $saving_period_scale2_admin, $samples_scale2_admin, $spikes_scale2_admin,
                                    $temp_sensor1_admin, $temp_sensor2_admin, $temp_sensor3_admin, $temp_sensor4_admin);
        logger('DEBUG', 'adminvalues saved');
        print '<script language="javascript"> alert("'. (_("administration values")) . " : " . (_("values saved")) .'"); </script>';                            

    }
?>