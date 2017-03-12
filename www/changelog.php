                                <?php 
                                    include 'header.php';      // Template-Kopf und Navigation
                                ?>
                                <h2 class="art-postheader"><?php echo _('Changelog'); ?></h2>
                                <div class="hg_container" style="text-align: left;">
                                    <form  method="post">
                                        <table style="width: 100%" class=" minischrift">
                                            <tr>
                                                <td>
                                                    <?php 
                                                        if (is_file($changelogfile)) {
                                                            echo _('Dateipruefung').': '.$changelogfile.'<br />';
                                                            echo _('Dateigroesse').': '.filesize($changelogfile).' bytes<br />';
                                                            $mtime = filemtime($changelogfile);
                                                            echo _('Zuletzt geaendert am').': ';
                                                            echo date('d M Y, H:i:s', $mtime);
                                                            echo _(' Uhr').'<br />';
                                                            echo '<img src="images/yes_small.png"> '._('Datei ist vorhanden').'<br />';
                                                        }
                                                        else {
                                                            echo '<img src="images/no_small.png"> '._('Datei ist nicht vorhanden').'<br />';
                                                        }
                                                        if (is_readable($changelogfile)) {
                                                            echo '<img src="images/yes_small.png"> '._('Datei ist lesbar').'<br />';
                                                        }
                                                        else {
                                                            echo '<img src="images/no_small.png"> '._('Datei ist nicht lesbar').'<br />';
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                    <hr>
                                    <!----------------------------------------------------------------------------------------Logeinträge-->
                                    <table class="minischrift">
                                        <tr>
                                            <td>
                                                <?php 
                                                    $f = file('changelog.txt');
                                                    foreach($f as $file) {
                                                        echo '<br />'. $file;
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                 <!----------------------------------------------------------------------------------------Hall-of-Fame-->
                                <<h2 class="art-postheader" ><?php echo _('Das Entwickler-Team'); ?></h2>
                                <div class="hg_container" style="text-align: left;">
                                    <table style="width: 100%" class="minischrift">
                                        <tr>
                                            <td>
                                                <img src="images/tronje.gif" alt="">
                                            </td>
                                            <td>
                                                <img src="images/steini.gif" alt="">
                                            </td>
                                            <td>
                                                <img src="images/hama.gif" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center"><a class="art-postcontent" href="https://www.grillsportverein.de/forum/members/tronje-the-falconer.73106/" target="_blank"><b>Tronje the Falconer</b></a><br><?php _('Backend & Linux'); ?></td>
                                            <td style="text-align: center"><a href="https://www.grillsportverein.de/forum/members/steinbacher.79220/" target="_blank"><b>Steinbacher</b></a><br><?php _('Frontend & Design'); ?></td>
                                            <td style="text-align: center"><a href="https://www.grillsportverein.de/forum/members/ha-ma.74075/" target="_blank"><b>Ha-Ma</b></a><br><?php _('Hardware & Testing'); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center" colspan="3"><br><br><?php echo _('Nach einer Idee und Vorlage von '); ?><a href="https://www.grillsportverein.de/forum/members/tommy_j.54659/" target="_blank" title="Tommy_J">Tommy_J</a></td>
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
