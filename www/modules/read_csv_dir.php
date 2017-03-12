<?php 
    $x = 0;
    foreach (glob('csv/*.csv') as $filename){
        $csvfilename[$x] = explode('/',$filename);
        $csvfilename[$x] = end($csvfilename[$x]);
        $info = pathinfo($csvfilename[$x]);
        $csvfilename[$x] = $info['filename'];
        $x++;
    }
    // }
?>
