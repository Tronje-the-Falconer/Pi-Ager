        <footer class="art-footer">
            <div class="art-footer-inner">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="miniature_writing" style="width: 50%; float: left;">
                            <?php 
                              include   'modules/read_systemdetails.php';  //liest die Raspberry Revision aus und generiert den Model Namen
                              echo '<p>';
                              echo _('running engine: ');
                              echo '<br>';
                              echo $piversion;
                              echo '</p>';
                            ?>
                        </div>
                        <div class="miniature_writing" style="width: 50%; float: right;">
                            <p style="text-align: right;">
                                <a href="changelog.php">
                                    <?php 
                                        echo $piager_version;
                                    ?>
                                </a>
                                <br>
                                <a href="<?php echo $thread_url;?>" target="_blank">www.grillsportverein.de</a>
                                <br>
                                <a href="<?php echo $faq_url;?>" target="_blank">FAQ</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
