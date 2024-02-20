<?php

include 'header.php';                                       // Template-Kopf und Navigation
include 'modules/names.php';
include 'modules/database.php';
include 'modules/logging.php';                            //liest die Datei fuer das logging ein
include 'modules/save_webcam_picture.php';
include 'modules/start_stop_program.php';
include 'modules/start_stop_light.php';
//include 'modules/read_current_db.php';

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
                                    echo '<a href="images/webcam/'.$latest_filename.'" download>'; echo _('download image file'); echo '</a>';
                                    echo '<br/></div><br/>';
                                ?>
				                <h2 class="art-postheader"><?php echo _('webcam'); ?></h2>
                                <div class="hg_container">
                                    <form  method="post" name="webcam">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td><button class="art-button" name="save_webcam_picture" ><?php echo _('take webcam snapshot'); ?></button></td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
				                <h2 class="art-postheader"><?php echo _('light switch'); ?></h2>
                                <div class="hg_container">
                                    <form  method="post" name="light">
                                        <table style="width: 100%;">
		                                    <tr>
                                                <td width="100px"></td>
                                                <td width="180px"></td>
                                                <td></td>
                                            </tr>
											<tr>
                                                 <?php 
                                                    $status_light_manual = intval(get_table_value($current_values_table,$status_light_manual_key));
                                                    if($status_light_manual == 0) {
                                                        echo '<td><img src="/images/icons/status_on_20x20.png" title="uv off"></td>';
														echo '<td style="text-align: left; ">' . _('auto mode on') . '</td>';
													    echo '<td align="left"><button class="art-button" name="turn_on_light" >'; echo _('light on'); echo '</button></td>';
                                                    }
                                                    else{
													    echo '<td><img src="/images/icons/status_on_manual_20x20.png" title="uv on"></td>';
														echo '<td style="text-align: left;">' . _('light manual on') . '</td>';
                                                        echo '<td align="left"><button class="art-button" name="turn_off_light" >'; echo _('auto'); echo '</button></td>';
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
<!--        </div> -->
        <?php 
            include 'footer.php';
        ?>
