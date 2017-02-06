                                <?php
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                ?>
                                <!----------------------------------------------------------------------------------------Was eben hier hin kommt ...-->
                                <?php
                                    // wenn nichts anderes ausgewählt wurde, ist Stündlich ausgewählt
                                    if (isset ($_GET['mode'])) {
                                        $mode = $_GET['mode'];
                                    }else{
                                        $mode = 'hourly';
                                    }

                                ?>
                                <h2 class="art-postheader">Diagramme</h2>
                                <div class="hg_container" style="margin-bottom: 20px; margin-top: 20px;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td><img src="images/hourly_30x30.png" alt=""></td>
                                            <td><img src="images/daily_30x30.png" alt=""></td>
                                            <td><img src="images/weekly_30x30.png" alt=""></td>
                                            <td><img src="images/monthly_30x30.png" alt=""></td>
                                        </tr>
                                        <tr>
                                            <td><a href="diagrams.php?mode=hourly" class="art-button">Stunde</a></td>
                                            <td><a href="diagrams.php?mode=daily" class="art-button">Tag</a></td>
                                            <td><a href="diagrams.php?mode=weekly" class="art-button">Woche</a></td>
                                            <td><a href="diagrams.php?mode=monthly" class="art-button">Monat</a></td>
                                        </tr>
                                    </table>
                                </div>
                                <div>
                                    <h2>Temperaturverlauf </h2>
                                    <img src="/pic/rss_sensortemp-<?= $mode ?>.png" alt="<?= $mode ?>" />
                                    <h2>Luftfeuchtigkeitsverlauf</h2>
                                    <img src="/pic/rss_sensorhum-<?= $mode ?>.png" alt="<?= $mode ?>" />
                                    <h2>Kühlung</h2>
                                    <img src="/pic/rss_cool-<?= $mode ?>.png" alt="<?= $mode ?>" />
                                    <h2>Heizung</h2>
                                    <img src="/pic/rss_heat-<?= $mode ?>.png" alt="<?= $mode ?>" />
                                    <h2>Befeuchtung</h2>
                                    <img src="/pic/rss_lbf-<?= $mode ?>.png" alt="<?= $mode ?>" />
                                    <h2>Luftaustausch</h2>
                                    <img src="/pic/rss_lat-<?= $mode ?>.png" alt="<?= $mode ?>" />
                                    <h2>Luftumwälzung</h2>
                                    <img src="/pic/rss_uml-<?= $mode ?>.png" alt="<?= $mode ?>" />
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