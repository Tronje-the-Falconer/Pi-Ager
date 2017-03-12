        <footer class="art-footer">
            <div class="art-footer-inner">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="minischrift" style="width: 50%; float: left;">
                            <?php 
                              include   'modules/read_pirevision.php';  //liest die Raspberry Revision aus und generiert den Model Namen
                              echo '<p>';
                              echo _('Running Engine: ');
                              echo '<br>';
                              echo $piversion;
                              echo '</p>';
                            ?>
                        </div>
                        <div class="minischrift" style="width: 50%; float: right;">
                            <p style="text-align: right;">
                                <a href="changelog.php">
                                    <?php 
                                        echo $piager_version;
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