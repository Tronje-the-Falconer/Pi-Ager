<?php
    if (isset ($_POST['save_webcam_picture'])){
        $date = date('d.m.Y H:i:s');
        shell_exec('sudo /var/sudowebscript.sh savewebcampicture');
        $webcam_path = "/var/www/images/webcam";
        $latest_ctime = 0;
        $latest_filename = '';
        sleep (1);
        $d = dir($webcam_path);
        while (false !== ($entry = $d->read())) {
          $filepath = "{$webcam_path}/{$entry}";
          if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
            $latest_ctime = filectime($filepath);
            $latest_filename = $entry;
          }
        }
        $filename = $latest_filename;
        $downloadfile = "images/webcam/".$filename;
        $filesize = filesize($downloadfile);
        header("Content-Type: image/jpg"); 
        header("Content-Disposition: attachment; filename='".$filename);
        header("Content-Length:". $filesize);
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        readfile($downloadfile);
        exit;
    }
?>