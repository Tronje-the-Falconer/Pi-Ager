<?php 

    include 'read_all_gpio.php';
    
    #Prüfen ob threads aktiv sind

    $exec_retval = exec('pgrep -a python3 | grep main.py');
    $grepmain = 0;
    if ($exec_retval != '') {
        $grepmain = 1;
    }

    // $grepagingtable = exec('pgrep -a python3 | grep agingtable.py');
    $grepagingtable = intval(get_table_value($current_values_table, $status_agingtable_key));
    // $grepscale = exec('pgrep -a python3 | grep scale.py');
    // $grepwebcam = shell_exec('ps ax | grep -v grep | grep mjpg-streamer');
    $grepbackup = shell_exec('ps ax | grep -v grep | grep pi-ager_backup.sh');
    # check if scale threads are alive
    $scale1_thread_alive = intval(get_table_value($current_values_table, $scale1_thread_alive_key));
    $scale2_thread_alive = intval(get_table_value($current_values_table, $scale2_thread_alive_key));
    
    #Schaltzustände setzen
    if($read_gpio_cooling_compressor == 0) {
        $cooler_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_cooling_compressor == 1) {
        $cooler_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($read_gpio_heater == 0) {
        $heater_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_heater == 1) {
        $heater_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($read_gpio_circulating_air == 0) {
        $circulating_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_circulating_air == 1) {
        $circulating_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($read_gpio_exhausting_air == 0) {
        $exhausting_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_exhausting_air == 1) {
        $exhausting_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($read_gpio_humidifier == 0) {
        $humidifier_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_humidifier == 1) {
        $humidifier_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($read_gpio_uv == 0) {
        $uv_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_uv == 1) {
        $uv_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($read_gpio_light == 0) {
        $light_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_light == 1) {
        $light_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($read_gpio_dehumidifier == 0) {
        $dehumidifier_on_off_png = 'images/icons/status_on_20x20.png';
        }
    if($read_gpio_dehumidifier == 1) {
        $dehumidifier_on_off_png = 'images/icons/status_off_20x20.png';
        }
    if($grepagingtable == 0) {
        $agingtable_on_off_png = 'images/icons/status_off_20x20.png';
    }
    else {
        $agingtable_on_off_png = 'images/icons/status_on_20x20.png';
    }
    if($grepmain == 0) {
        $pi_ager_on_off_png = 'images/icons/status_off_20x20.png';
    }
    else {
        $pi_ager_on_off_png = 'images/icons/status_on_20x20.png';
    }
    // if($grepscale1 == 0) {
    //     $scale1_on_off_png = 'images/icons/status_off_20x20.png';
    // }
    // else {
    //     $scale1_on_off_png = 'images/icons/status_on_20x20.png';
    // }
    // if($grepscale2== 0) {
    //     $scale2_on_off_png = 'images/icons/status_off_20x20.png';
    // }
    // else {
    //     $scale2_on_off_png = 'images/icons/status_on_20x20.png';
    // }
    logger('DEBUG', 'read_gpio performed');
?>
