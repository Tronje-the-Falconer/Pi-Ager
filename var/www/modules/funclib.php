<?php
function convert_seconds_to_dhms($seconds) {
    $string = "";

    $days = intval(intval($seconds) / (3600*24));
    $hours = (intval($seconds) / 3600) % 24;
    $minutes = (intval($seconds) / 60) % 60;
    $seconds = (intval($seconds)) % 60;

    if ($days > 0) {
        if ($days == 1) {
            $string .= "$days" . ' ' . _('day') . ', ';
        }
        else {
            $string .= "$days" . ' ' . _('days') . ', ';
        }
    }
    if ($hours > 0) {
        if ($hours == 1) {
            $string .= "$hours" . ' ' . _('hour') . ', ';
        }
        else {
            $string .= "$hours" . ' ' . _('hours') . ', ';
        }
    }
    if ($minutes > 0) {
        if ($minutes == 1) {
            $string .= "$minutes" . ' ' . _('minute') . ', ';
        }
        else {
            $string .= "$minutes" . ' ' . _('minutes') . ', ';
        }
    }
//    if ($seconds > 0) {
        if ($seconds == 1) {
            $string .= "$seconds" . ' ' . _('second');
        }
        else {
            $string .= "$seconds" . ' ' . _('seconds');
        }
//    }

    return $string;
}

function convert_seconds_to_hours($seconds, $decimals) {
    
    return (number_format($seconds/3600.0, $decimals, '.', '') . ' ' . _('hours'));
}

function eval_switch_on_humidity( $setpoint_humidity, $humidifier_hysteresis, $hysteresis_offset ) {
    $hum_temp = $setpoint_humidity - $humidifier_hysteresis/2.0 + $hysteresis_offset;
    return ($hum_temp < 0 ? 0 : $hum_temp); 
}

function eval_switch_off_humidity( $setpoint_humidity, $humidifier_hysteresis, $hysteresis_offset, $saturation_point ) {
    $hum_temp = $setpoint_humidity + $humidifier_hysteresis/2.0 + $hysteresis_offset;
    $hum_max = $saturation_point - 1;        // do not allow values greater saturation point - 1% !
    return ($hum_temp > $hum_max ? $hum_max : $hum_temp);
}

function eval_switch_on_dehumidity( $setpoint_humidity, $dehumidifier_hysteresis, $hysteresis_offset, $saturation_point ) {
    $hum_temp = $setpoint_humidity + $dehumidifier_hysteresis/2.0 + $hysteresis_offset;
    $hum_max = $saturation_point - 1;  
    return ($hum_temp > $hum_max ? $hum_max : $hum_temp); 
}

function eval_switch_off_dehumidity( $setpoint_humidity, $dehumidifier_hysteresis, $hysteresis_offset ) {
    $hum_temp = $setpoint_humidity - $dehumidifier_hysteresis/2.0 + $hysteresis_offset;
    return ($hum_temp < 0 ? 0 : $hum_temp);
}

# find delay and offset for humidifier control by linear interpolation
function eval_humidifier_delay_offset( $humidity_parms, $setp_temp ) {
    $i = -1;
    $count = count($humidity_parms);
    foreach( $humidity_parms as $parms ) {
        if ($setp_temp < $parms['setpoint_temp']) {
            break;
        }
        $i++;
    }
    if ($i == -1) {
       $i = 0; 
    }
    if ($i > ($count - 2)) {
        $i = $count - 2;
    }
    $index0 = $i;
    $index1 = $i + 1; 
    
    $d = ($setp_temp - $humidity_parms[$index0]['setpoint_temp']) / ($humidity_parms[$index1]['setpoint_temp'] - $humidity_parms[$index0]['setpoint_temp']);  // setpoint temperature
    $delay = $humidity_parms[$index0]['delay_humidifier'] + $d * ($humidity_parms[$index1]['delay_humidifier'] - $humidity_parms[$index0]['delay_humidifier']);        // delay
    $offset = $humidity_parms[$index0]['offset_humidifier'] + $d * ($humidity_parms[$index1]['offset_humidifier'] - $humidity_parms[$index0]['offset_humidifier']);       // offset
    return [$delay, $offset];
}


# find cooler_hysteresis_offset and heater_hysteresis offset for temperature control by linear interpolation
function eval_cooler_heater_offsets( $offset_parms, $setp_temp ) {
    $i = -1;
    $count = count($offset_parms);
    foreach( $offset_parms as $parms ) {
        if ($setp_temp < $parms['setpoint_temp']) {
            break;
        }
        $i++;
    }
    if ($i == -1) {
       $i = 0; 
    }
    if ($i > ($count - 2)) {
        $i = $count - 2;
    }
    $index0 = $i;
    $index1 = $i + 1; 
    
    $d = ($setp_temp - $offset_parms[$index0]['setpoint_temp']) / ($offset_parms[$index1]['setpoint_temp'] - $offset_parms[$index0]['setpoint_temp']);  // setpoint temperature
    $cooler_offset = $offset_parms[$index0]['cooler_offset'] + $d * ($offset_parms[$index1]['cooler_offset'] - $offset_parms[$index0]['cooler_offset']);        // cooler_offset
    $heater_offset = $offset_parms[$index0]['heater_offset'] + $d * ($offset_parms[$index1]['heater_offset'] - $offset_parms[$index0]['heater_offset']);       // heater_offset
    return [$cooler_offset, $heater_offset];
}
?>