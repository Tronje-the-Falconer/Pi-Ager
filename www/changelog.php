                                <?php 
                                    include 'header.php';      // Template-Kopf und Navigation
                                ?>
                                <h2 class="art-postheader"><?php echo _('changelog'); ?></h2>
                                <div class="hg_container" style="text-align: left;">
                                    <form  method="post">
                                        <table style="width: 100%" class=" miniature_writing">
                                            <tr>
                                                <td>
                                                    <?php 
                                                        if (is_file($changelogfile)) {
                                                            echo _('file verification').': '.$changelogfile.'<br />';
                                                            echo _('file size').': '.filesize($changelogfile).' bytes<br />';
                                                            $mtime = filemtime($changelogfile);
                                                            echo _('last changed at').': ';
                                                            echo date('d M Y, H:i:s', $mtime);
                                                            echo ' ';
                                                            echo _('oclock').'<br />';
                                                            echo '<img src="images/icons/check_true_42x42.png"> '._('file exists').'<br />';
                                                        }
                                                        else {
                                                            echo '<img src="images/icons/check_false_42x42"> '._('file does not exist').'<br />';
                                                        }
                                                        if (is_readable($changelogfile)) {
                                                            echo '<img src="images/icons/check_true_42x42.png"> '._('file is readable').'<br />';
                                                        }
                                                        else {
                                                            echo '<img src="images/icons/check_false_42x42.png"> '._('file is not readable').'<br />';
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                    <hr>
                                    <!----------------------------------------------------------------------------------------Logeinträge-->
                                    <table class="miniature_writing">
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
                                <<h2 class="art-postheader" ><?php echo _('the developer team'); ?></h2>
                                <div class="hg_container" style="text-align: left;">
                                    <table style="width: 100%" class="miniature_writing">
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
                                            <td style="text-align: center"><a class="art-postcontent" href="https://www.grillsportverein.de/forum/members/tronje-the-falconer.73106/" target="_blank"><b>Tronje the Falconer</b></a><br><?php _('backend & linux'); ?></td>
                                            <td style="text-align: center"><a href="https://www.grillsportverein.de/forum/members/steinbacher.79220/" target="_blank"><b>Steinbacher</b></a><br><?php _('frontend & design'); ?></td>
                                            <td style="text-align: center"><a href="https://www.grillsportverein.de/forum/members/ha-ma.74075/" target="_blank"><b>Ha-Ma</b></a><br><?php _('hardware & testing'); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center" colspan="3"><br><br><?php echo _('based on an idea and submission of'); echo ' ';?><a href="https://www.grillsportverein.de/forum/members/tommy_j.54659/" target="_blank" title="Tommy_J">Tommy_J</a></td>
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
