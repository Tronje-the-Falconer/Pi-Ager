                                <?php
                                    include 'header.php';      // Template-Kopf und Navigation
                                ?>
                                <h2 class="art-postheader">Changelog</h2>
                                <div class="hg_container" style="text-align: left;">
                                    <form  method="post">
                                        <table style="width: 100%">
                                            <tr>
                                                <td>
                                                    <?php
                                                        if (is_file($changelogfile)) {
                                                            echo 'Dateiprüfung: '.$changelogfilefile.'<br />';
                                                            echo 'Dateigröße: '.filesize($changelogfilefile).' bytes<br />';
                                                            $mtime = filemtime($changelogfilefile);
                                                            print 'Zuletzt geändert am: ';
                                                            print date('d M Y, H:i:s', $mtime);
                                                            print ' Uhr<br />';
                                                            print '<img src="images/yes_small.png"> Datei ist vorhanden<br />';
                                                        }
                                                        else {
                                                            print '<img src="images/no_small.png"> Datei ist nicht vorhanden<br />';
                                                        }
                                                        if (is_readable($changelogfilefile)) {
                                                            print '<img src="images/yes_small.png"> Datei ist lesbar<br />';
                                                        }
                                                        else {
                                                            print '<img src="images/no_small.png"> Datei ist nicht lesbar<br />';
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                    <hr>
                                    <!----------------------------------------------------------------------------------------Logeinträge-->
                                    <?php
                                        $f = file('changelog.txt');
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
