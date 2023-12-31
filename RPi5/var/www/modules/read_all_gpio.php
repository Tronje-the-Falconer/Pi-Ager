<?php
# gpio pins used by Pi-Ager, sorted by GPIO
    $gpio_heater = 3;
    $gpio_cooling_compressor = 4;
    $gpio_dehumidifier = 7;
    $gpio_light = 8;
    $gpio_battery = 11;
    $gpio_humidifier = 18;
    $gpio_digital_switch = 22;    
    $gpio_exhausting_air = 23;
    $gpio_circulating_air = 24;
    $gpio_uv_light = 25;
    $gpio_voltage = 26;
    
    $channel_list = [$gpio_heater,
                     $gpio_cooling_compressor,
                     $gpio_dehumidifier,
                     $gpio_light,
                     $gpio_battery,
                     $gpio_humidifier,
                     $gpio_digital_switch,
                     $gpio_exhausting_air,
                     $gpio_circulating_air,
                     $gpio_uv_light,
                     $gpio_voltage,
                     ];
                     
    $channels = join(',', $channel_list);
    # var_dump($channels);
    $command = 'pinctrl ' . $channels . ' | cut -c 16-17';
    
    # pin status auslesen
    $pin_status = [];
    $res = null;
    exec($command, $pin_status, $res);
    # var_dump($pin_status);

    $pin_status_int = array_map(fn ($a) => $a == 'hi' ? 1 : 0 , $pin_status);
    # var_dump($pin_status_int);
    
    $read_gpio_heater = $pin_status_int[0];
    $read_gpio_cooling_compressor = $pin_status_int[1];
    $read_gpio_dehumidifier = $pin_status_int[2];
    $read_gpio_light = $pin_status_int[3];
    $read_gpio_battery = $pin_status_int[4];    
    $read_gpio_humidifier = $pin_status_int[5];
    $read_gpio_digital_switch = $pin_status_int[6];     
    $read_gpio_exhausting_air = $pin_status_int[7];
    $read_gpio_circulating_air = $pin_status_int[8];
    $read_gpio_uv = $pin_status_int[9];
    $read_gpio_voltage = $pin_status_int[10];
  
?>    