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

?>