<?php 
    # Funktion zur Anzeige der Temp und Lft
    function tempParts($array, $index) {
        $parts=explode('.', number_format($array,1));
        return $parts[$index];
    }
?>