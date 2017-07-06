<?php
                                    include 'modules/save_webcam_picture.php';
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                    include 'modules/start_stop_program.php';
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
                                    <form  method="post" name="boot">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td><button class="art-button" name="save_webcam_picture" ><?php echo _('save webcam picture'); ?></button></td>
                                                <td><button class="art-button" name="webcam_start" ><?php echo _('start stream'); ?></button></td>
                                                <td><button class="art-button" name="webcam_stop" ><?php echo _('stop stream'); ?></button></td>
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
