                                <?php 
                                    include 'header.php';      // Template-Kopf und Navigation
                                    include 'modules/clear_logfile.php';
                                ?>
                                <h2 class="art-postheader"><?php echo _('Logeintraege'); ?></h2>
                                <div class="hg_container" style="text-align: left;">
                                    <form  method="post">
                                        <table style="width: 100%" class="minischrift">
                                            <tr>
                                                <td>
                                                    <?php 
                                                        if (is_file($logfile)) {
                                                            echo _('Dateipruefung').': '.$logfile.'<br />';
                                                            echo _('Dateigroesse').': '.filesize($logfile).' bytes<br />';
                                                            $mtime = filemtime($logfile);
                                                            echo _('Zuletzt geaendert am').': ';
                                                            echo date('d M Y, H:i:s', $mtime);
                                                            echo ' '._('Uhr').'<br />';
                                                            echo '<img src="images/yes_small.png"> '._('Datei ist vorhanden').'<br />';
                                                        }
                                                        else {
                                                            echo '<img src="images/no_small.png"> '._('Datei ist nicht vorhanden').'<br />';
                                                        }
                                                        if (is_readable($logfile)) {
                                                            echo '<img src="images/yes_small.png"> '._('Datei ist lesbar').'<br />';
                                                        }
                                                        else {
                                                            echo '<img src="images/no_small.png"> '._('Datei ist nicht lesbar').'<br />';
                                                        }
                                                        if (is_writable($logfile)) {
                                                            echo '<img src="images/yes_small.png"> '._('Datei ist schreibbar').'<br />';
                                                        }
                                                        else {
                                                            echo '<img src="images/no_small.png"> '._('Datei ist nicht schreibbar').'<br />';
                                                        }
                                                    ?>
                                                </td>
                                                <td>&nbsp;</td>
                                                <td><button class="art-button" name="clear_logfile" onclick="return confirm(<?php echo _('Alle Logfiledaten loeschen?'); ?>);"><?php echo _('Daten loeschen'); ?></button></td>
                                            </tr>
                                        </table>
                                    </form>
                                    <hr>
                                    <!----------------------------------------------------------------------------------------Logeinträge-->
                                    <table class="minischrift">
                                        <tr>
                                            <td>
                                                <?php 
                                                    $f = file('logfile.txt');
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