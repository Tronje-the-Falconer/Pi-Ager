<?php
    $x = 0;
    foreach (glob('csv/*.csv') as $filename){
        $pdf[$x] = explode('/',$filename);
        $pdf[$x] = end($pdf[$x]);
        $info = pathinfo($pdf[$x]);
        $pdf[$x] = $info['filename'];
        $x++;
    }
    // }
?>
