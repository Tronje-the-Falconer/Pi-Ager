<?php
$latest_ctime = 0;
$latest_filename = "logo.png";
$webcam_path = "/var/www/images/webcam/";
$jpgfiles = "/var/www/images/webcam/*.jpg";
$d = dir($webcam_path);      # find latest snapshot image, is only 1 or none
while (false !== ($entry = $d->read())) {
    #       echo "entry: $entry <br/>";
    $filepath = $webcam_path . $entry;
    if (is_file($filepath) && (pathinfo($filepath, PATHINFO_EXTENSION) == "jpg") && (filectime($filepath) > $latest_ctime)) {
        $latest_ctime = filectime($filepath);
        $latest_filename = $entry;
        #         echo "latest: $latest_filename <br/>";
    }
}
if (isset ($_POST['save_webcam_picture'])){
    logger('DEBUG', 'button save webcampicture pressed');
    unset($_POST['save_webcam_picture']);
    # remove all .jpg image files
    shell_exec('sudo /var/sudowebscript.sh delete_snapshot_files');
    $date = date('Y-m-d_Hms');
    $filename = "snap_" . $date . ".jpg";
    $downloadfile = $webcam_path . $filename;
    shell_exec('sudo /var/sudowebscript.sh savewebcampicture ' . $downloadfile);
    sleep (1);
    # setup latest file
    $latest_filename = $filename;
    logger('DEBUG', 'webcampicture saved');
    header("Location: webcam.php");   #reload
    exit;
}
?>
