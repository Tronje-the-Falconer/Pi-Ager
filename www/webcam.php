<?php
                                    include 'modules/save_webcam_picture.php';
                                    include 'header.php';                                       // Template-Kopf und Navigation
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
                                    
                                    $grepwebcam = shell_exec('sudo /var/sudowebscript.sh grepwebcam');
                                    if($grepwebcam != 0) {
                                        print '<iframe src="'. $protocol . $_SERVER['SERVER_NAME'].':8080/?action=stream" height="480" width="640" frameborder="0"></iframe>';
                                    }
                                    else {
                                        echo 'stream is not running';
                                    }
                                    
                                ?>
                                <div class="hg_container">
                                    <form  method="post" name="webcam">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td><button class="art-button" name="save_webcam_picture" ><?php echo _('save webcam picture'); ?></button></td>
                                                <?php 
                                                    if($grepwebcam == 0) {
                                                         echo '<td><button class="art-button" name="webcam_start" >'; echo _('start stream'); echo '</button></td>';
                                                    }
                                                    else{
                                                        echo '<td><button class="art-button" name="webcam_stop" >'; echo _('stop stream'); echo '</button></td>';
                                                    }
                                                ?>
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
