                                <?php 
                                    include 'header.php';      // Template-Kopf und Navigation
                                    include 'modules/clear_logfile.php';
                                ?>
                                <h2 class="art-postheader"><?php echo _('log entries'); ?></h2>
                                <div class="hg_container" style="text-align: left;">
                                    <form  method="post">
                                        <table style="width: 100%" class="miniature_writing">
                                            <tr>
                                                <td>
                                                    <?php 
                                                        if (is_file($logfile)) {
                                                            echo _('file verification').': '.$logfile.'<br />';
                                                            echo _('file size').': '.filesize($logfile).' bytes<br />';
                                                            $mtime = filemtime($logfile);
                                                            echo _('last changed at').': ';
                                                            echo date('d M Y, H:i:s', $mtime);
                                                            echo ' '._('oclock').'<br />';
                                                            echo '<img src="images/icons/check_true_42x42.png"> '._('file exists').'<br />';
                                                        }
                                                        else {
                                                            echo '<img src="images/icons/check_false_42x42.png"> '._('file does not exist').'<br />';
                                                        }
                                                        if (is_readable($logfile)) {
                                                            echo '<img src="images/icons/check_true_42x42.png"> '._('file is readable').'<br />';
                                                        }
                                                        else {
                                                            echo '<img src="images/icons/check_false_42x42.png"> '._('file is not readable').'<br />';
                                                        }
                                                        if (is_writable($logfile)) {
                                                            echo '<img src="images/icons/check_true_42x42.png"> '._('file is writable').'<br />';
                                                        }
                                                        else {
                                                            echo '<img src="images/icons/check_false_42x42.png"> '._('file is not writable').'<br />';
                                                        }
                                                    ?>
                                                </td>
                                                <td>&nbsp;</td>
                                                <td><button class="art-button" name="clear_logfile" onclick="return confirm(<?php echo _('clear all logfile data?'); ?>);"><?php echo _('delete data'); ?></button></td>
                                            </tr>
                                        </table>
                                    </form>
                                    <hr>
                                    <!----------------------------------------------------------------------------------------Logeinträge-->
                                    <table class="miniature_writing">
                                        <tr>
                                            <td>
                                                <?php 
                                                    $f = file('/logs/logfile.txt');
                                                    foreach($f as $file) {
                                                        echo '<br />'. $file;
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <!----------------------------------------------------------------------------------------Ende! ...-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            include 'footer.php';
        ?>