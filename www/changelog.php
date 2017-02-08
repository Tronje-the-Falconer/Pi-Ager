                                <?php
                                    include 'header.php';      // Template-Kopf und Navigation
                                    include 'modules/clear_logfile.php';
                                ?>
                                <h2 class="art-postheader">Logeinträge</h2>
                                <div class="hg_container" style="text-align: left;">
                                    <form  method="post">
                                        <table style="width: 100%">
                                            <tr>
                                                <td>
                                                    <?php
                                                        if (is_file($logfile)) {
                                                            echo 'Dateiprüfung: '.$logfile.'<br />';
                                                            echo 'Dateigröße: '.filesize($logfile).' bytes<br />';
                                                            $mtime = filemtime($logfile);
                                                            print 'Zuletzt geändert am: ';
                                                            print date('d M Y, H:i:s', $mtime);
                                                            print ' Uhr<br />';
                                                            print '<img src="images/yes_small.png"> Datei ist vorhanden<br />';
                                                        }
                                                        else {
                                                            print '<img src="images/no_small.png"> Datei ist nicht vorhanden<br />';
                                                        }
                                                        if (is_readable($logfile)) {
                                                            print '<img src="images/yes_small.png"> Datei ist lesbar<br />';
                                                        }
                                                        else {
                                                            print '<img src="images/no_small.png"> Datei ist nicht lesbar<br />';
                                                        }
                                                        if (is_writable($logfile)) {
                                                            print '<img src="images/yes_small.png"> Datei ist schreibbar<br />';
                                                        }
                                                        else {
                                                            print '<img src="images/no_small.png"> Datei ist nicht schreibbar<br />';
                                                        }
                                                    ?>
                                                </td>
                                                <td>&nbsp;</td>
                                                <td><button class="art-button" name="clear_logfile" onclick="return confirm('Alle Logfiledaten löschen?');">Daten löschen</button></td>
                                            </tr>
                                        </table>
                                    </form>
                                    <hr>
                                    <!----------------------------------------------------------------------------------------Logeinträge-->
                                    <?php
                                        $f = file('logfile.txt');
                                        foreach($f as $file) {
                                            echo '<br />'. $file;
                                        }
                                    ?>
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
