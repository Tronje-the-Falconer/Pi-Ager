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
                                                            echo 'Dateiprüfung: '.$changelogfile.'<br />';
                                                            echo 'Dateigröße: '.filesize($changelogfile).' bytes<br />';
                                                            $mtime = filemtime($changelogfile);
                                                            print 'Zuletzt geändert am: ';
                                                            print date('d M Y, H:i:s', $mtime);
                                                            print ' Uhr<br />';
                                                            print '<img src="images/yes_small.png"> Datei ist vorhanden<br />';
                                                        }
                                                        else {
                                                            print '<img src="images/no_small.png"> Datei ist nicht vorhanden<br />';
                                                        }
                                                        if (is_readable($changelogfile)) {
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

                                 <!----------------------------------------------------------------------------------------Hall-of-Fame-->
                                <h2 class="art-postheader">Das Entwickler-Team</h2>
                                <div class="hg_container" style="text-align: left;">
                                        <table style="width: 100%">
                                            <tr>
                                                <td>
                                                          <img src="images/tronje.gif" alt="">
                                                </td>
                                                <td>
                                                          <img src="images/hama.gif" alt="">
                                                </td>
                                                <td>
                                                            <img src="images/steini.gif" alt="">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center"><a class="art-postcontent" href="https://www.grillsportverein.de/forum/members/tronje-the-falconer.73106/" target="_blank"><b>Tronje the Falconer</b></a><br>Backend & Linux</td>
                                                <td style="text-align: center"><a href="https://www.grillsportverein.de/forum/members/ha-ma.74075/" target="_blank"><b>Ha-Ma</b></a><br>Hardware & Testing</td>
                                                <td style="text-align: center"><a href="https://www.grillsportverein.de/forum/members/steinbacher.79220/" target="_blank"><b>Steinbacher</b></a><br>Frontend & Design</td>
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
