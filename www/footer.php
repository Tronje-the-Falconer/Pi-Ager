        <?php
        
        ?>
        <footer class="art-footer">
            <div class="art-footer-inner">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell layout-item-0" style="width: 50%">
                            <?PHP
                              include   'modules/read_pirevision.php';  //liest die Raspberry Revision aus und generiert den Model Namen
                              print '<p>Running Engine<br>' + str ($piversion)+ '</p>'
                            ?>
                        </div>
                        <div class="art-layout-cell layout-item-0" style="width: 50%">
                            <p style="text-align: right;">Version 2.01<br><a href="https://www.grillsportverein.de/forum/threads/reifeschranksteuerung-per-raspberry-pi-tutorial.231649/" target="_blank">www.grillsportverein.de</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
<?php

?>
