<?php 
    # Status auslesen
    $read_gpio_cooling_compressor = shell_exec('sudo /var/sudowebscript.sh read_gpio_cooling_compressor'); #Kühlung
    $read_gpio_heater = shell_exec('sudo /var/sudowebscript.sh read_gpio_heater'); #Heizung
    $read_gpio_humidifier = shell_exec('sudo /var/sudowebscript.sh read_gpio_humidifier'); #luftbefeuchter
    $read_gpio_circulating_air = shell_exec('sudo /var/sudowebscript.sh read_gpio_circulating_air'); #Umluft
    $read_gpio_exhausting_air = shell_exec('sudo /var/sudowebscript.sh read_gpio_exhausting_air'); #Abluft
    $read_gpio_uv = shell_exec('sudo /var/sudowebscript.sh read_gpio_uv'); #UV-Licht
    $read_gpio_light = shell_exec('sudo /var/sudowebscript.sh read_gpio_light'); # Licht
    $read_gpio_dehumidifier = shell_exec('sudo /var/sudowebscript.sh read_gpio_dehumidifier'); # Entfeuchter
    

    #Prüfen ob Programme laufen
    $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable'); #Reifetab.py
    $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain'); #Rss.py
    $grepscale1 = shell_exec('sudo /var/sudowebscript.sh grepscale1');
    $grepscale2 = shell_exec('sudo /var/sudowebscript.sh grepscale2');

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
    if($grepscale1 == 0) {
        $scale1_on_off_png = 'images/icons/status_off_20x20.png';
    }
    else {
        $scale1_on_off_png = 'images/icons/status_on_20x20.png';
    }
        if($grepscale2== 0) {
        $scale2_on_off_png = 'images/icons/status_off_20x20.png';
    }
    else {
        $scale2_on_off_png = 'images/icons/status_on_20x20.png';
    }
?>
