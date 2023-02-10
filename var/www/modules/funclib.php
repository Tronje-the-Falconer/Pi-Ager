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
?>