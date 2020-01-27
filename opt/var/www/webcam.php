<?php

include 'header.php';                                       // Template-Kopf und Navigation
include 'modules/names.php';
include 'modules/database.php';
include 'modules/logging.php';                            //liest die Datei fuer das logging ein
include 'modules/save_webcam_picture.php';
include 'modules/start_stop_program.php';
include 'modules/start_stop_light.php';
include 'modules/read_current_db.php';

?>
                                <?PHP
                                    if (isset($_SERVER['HTTPS']) &&
                                        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
                                        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                                        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
                                      $protocol = 'https://';
                                    }
                                    else {
                                      $protocol = 'http://';
                                    }
                                    echo '<img src="images/webcam/'.$latest_filename.'" alt="webcam snapshot file">';
                                    echo '<div><br/>';
                                    echo '<a href="images/webcam/'.$latest_filename.'" download>download image file</a>';
                                    echo '<br/></div><br/>';
                                ?>

                                <div class="hg_container">
                                    <form  method="post" name="webcam">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td><button class="art-button" name="save_webcam_picture" ><?php echo _('take webcam snapshot'); ?></button></td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                                <div class="hg_container">
                                    <form  method="post" name="light">
                                        <table style="width: 100%;">
                                            <tr>
                                                 <?php 
                                                    
                                                    if($status_light_manual == 0) {
                                                         echo '<td><button class="art-button" name="turn_on_light" >'; echo _('turn on light'); echo '</button></td>';
                                                    }
                                                    else{
                                                        echo '<td><button class="art-button" name="turn_off_light" >'; echo _('turn off light'); echo '</button></td>';
                                                    }
                                                ?>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            include 'footer.php';
        ?>
