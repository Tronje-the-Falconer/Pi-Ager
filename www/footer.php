        <?php
        
        ?>
        <footer class="art-footer">
            <div class="art-footer-inner">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="minischrift" style="width: 50%; float: left;">
                            <?PHP
                              include   'modules/read_pirevision.php';  //liest die Raspberry Revision aus und generiert den Model Namen
                              print '<p>Running Engine <br>';
                              print $piversion;
                              print '</p>';
                            ?>
                        </div>
                        <div class="minischrift" style="width: 50%; float: right;">
                            <p style="text-align: right;">
                                <a href="changelog.php">
                                    <?php
                                        print $rssversion;
                                    ?>
                                </a>
                                <br>
                                <a href="https://www.grillsportverein.de/forum/threads/reifeschranksteuerung-per-raspberry-pi-tutorial.231649/" target="_blank">www.grillsportverein.de</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
<?php

?>
