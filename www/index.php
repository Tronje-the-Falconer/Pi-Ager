                                <?php
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                    include 'modules/read_settings.json.php';                   // Liest die Einstellungen (Temperaturregelung, Feuchte, Lüftung und deren Hysteresen) und Betriebsart des RSS
                                    include 'modules/read_operating_mode.php';                  // Liest die Art der Reifesteuerung
                                    include 'modules/read_gpio.php';                            // Liest den aktuellen Zustand der GPIO-E/A
                                    include 'modules/read_current.json.php';                    // Liest die gemessenen Werte Temp, Humy, Timestamp
                                ?>
                                <h2 class="art-postheader">Aktuelle Werte</h2>
                                <!----------------------------------------------------------------------------------------Anzeige T/rLF-->
                                <div class="thermometers">
                                    <div class="th-anzeige-div">
                                        <table><tr><td><div class="label">Temperatur (C)</div></td></tr>
                                            <tr>
                                                <td>
                                                    <div class="de">
                                                        <div class="den">
                                                            <div class="dene">
                                                                <div class="denem">
                                                                    <div class="deneme">
                                                                        <?php
                                                                            // Die Aktualität der Werte prüfen, geichzeitige RSS-Funktionsprüfung
                                                                            $timestamp_unix = time();
                                                                            $time_diff = $timestamp_unix - $data_timestamp;
                                                                            if ($time_diff >= 120) {
                                                                                $temp_ausgabe = "<div style=\"float: left; padding-left: 8px;\" id=\"\"></div>--<span>.<div style=\"float: right; padding-top: 50px;\" id=\"\">-</div></span><strong>&deg;</strong>";
                                                                                $humy_ausgabe = "<div style=\"float: left; padding-left: 8px;\" id=\"\"></div>--<span>.<div style=\"float: right; padding-top: 50px;\" id=\"\">-</div></span><strong>&#37</strong> ";
                                                                            }
                                                                            else {
                                                                                $temp_ausgabe = "<div style=\"float: left; padding-left: 8px;\" id=\"current_json_temperatur_0\"></div><span>.<div style=\"float: right; padding-top: 37px;\" id=\"current_json_temperatur_1\"></div></span><strong>&deg;</strong>";
                                                                                $humy_ausgabe = "<div style=\"float: left; padding-left: 8px;\" id=\"current_json_luftfeuchtigkeit_0\"></div><span>.<div style=\"float: right; padding-top: 37px;\" id=\"current_json_luftfeuchtigkeit_1\"></div></span><strong>&#37</strong> ";
                                                                            }
                                                                        ?>
                                                                        <?=$temp_ausgabe?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="th-anzeige-div">
                                        <table><tr><td><div class="label">Luftfeuchtigkeit (%)</div></td></tr>
                                            <tr>
                                                <td>
                                                    <div class="de">
                                                        <div class="den">
                                                            <div class="dene">
                                                                <div class="denem">
                                                                    <div class="deneme">
                                                                        <?=$humy_ausgabe?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                     </div>
                                </div>
                                <!------------------------------ ----------------------------------------------------------T/rLF Diagramm-->
                                <h2>Temperaturverlauf</h2>
                                <img src="/pic/dht22_sensortemp-hourly.png" alt="Stundenverlauf Temperatur" />
                                <br/><br/>
                                <h2>Luftfeuchtigkeitsverlauf</h2>
                                <img src="/pic/dht22_sensorhum-hourly.png" alt="Stundenverlauf Luftfeuchtigkeit" />  <br><br>
                                <!----------------------------------------------------------------------------------------Betriebsart-->
                                <h2>Statusboard</h2>
                                <div class="hg_container">
                                    <table class="schaltzustaende">
                                        <tr>
                                            <td>
                                                <?php
                                                    // Prüft, ob Prozess RSS läuft
                                                    $valrs = shell_exec("ps ax | grep -v grep | grep Rss.py");
                                                    if ($valrs == 0){
                                                        print "<img src=\"images/led-off-green-20x20.png\" alt=\"\" style=\"padding-top: 10px;\">";
                                                    }
                                                    else {
                                                        print "<img src=\"images/led-on-green-20x20.png\" alt=\"\" style=\"padding-top: 10px;\">";
                                                    }
                                                ?>
                                                <br><img src="images/betriebsart.png" alt="" style="padding: 10px;">
                                            </td>
                                            <td class="text_left_top"><b>BETRIEBSART:</b><br><? if ($valrs == 0){print "AUS";} else {print $modus;}?></td>
                                            <td>
                                                <?php
                                                    // Prüft, ob Prozess Reifetab läuft
                                                    $valtab = shell_exec("ps ax | grep -v grep | grep Reifetab.py");
                                                    if ($valtab == 0){
                                                        print "<img src=\"images/led-off-green-20x20.png\" alt=\"\" style=\"padding-top: 10px;\">";
                                                    }
                                                    else {
                                                        print "<img src=\"images/led-on-green-20x20.png\" alt=\"\" style=\"padding-top: 10px;\">";
                                                    }
                                                ?>
                                                <br><img src="images/reifung.png" alt="" style="padding: 10px;">
                                            </td>
                                            <td class="text_left_top"><b>REIFEMODUS/TABELLE:</b><br><?=$reifeart;?></td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <table class="schaltzustaende">
                                        <tr>
                                            <td><b>TYP</b></td>
                                            <td ><b>STATUS</b></td>
                                            <td class="text_left">&nbsp;</td>
                                            <td ><b>IST</b></td>
                                            <td ><b>SOLL</b></td>
                                            <td ><b>EIN</b></td>
                                            <td ><b>AUS</b></td>
                                        </tr>
                                        <tr>
                                            <?php
                                                if ($mod==1){
                                                    echo "   <td ><img src=\"images/kuehlung.png\" alt=\"\"></td>
                                                        <td ><img src=\"".$cool."\" title=\"PIN_COOL 22[15] -> IN 1 (GPIO 3)\"></td>
                                                        <td class=\"text_left\">KÜHLUNG</td>
                                                        <td >".$temp_float." °C</td>
                                                        <td >".$tempsoll_float." °C</td>
                                                        <td >".($tempsoll_float+$temphyston)." °C</td>
                                                        <td >".($tempsoll_float+$temphystoff)." °C</td>";
                                                }
                                                if ($mod==2){
                                                    print "   <td ><img src=\"images/heizung.png\" alt=\"\"></td>
                                                        <td ><img src=\"".$cool."\" title=\"PIN_HEATER 27[13] -> IN 2 (GPIO 2)\"></td>
                                                        <td class=\"text_left\">HEIZUNG</td>
                                                        <td >".$temp_float." °C</td>
                                                        <td >".$tempsoll_float." °C</td>
                                                        <td >".($tempsoll_float-$temphyston)." °C</td>
                                                        <td >".($tempsoll_float-$temphystoff)." °C</td>";
                                                }
                                                if ($mod==3 || $mod==4){
                                                    print "   <td ><img src=\"images/heiz_kuehl.png\" alt=\"\"></td>
                                                        <td ><img src=\"".$cool."\" title=\"PIN_HEATER 27[13] -> IN 2 (GPIO 2)\"></td>
                                                        <td class=\"text_left\">HEIZ-/KÜHLUNG</td>
                                                        <td >".$temp_float." °C</td>
                                                        <td >".$tempsoll_float." °C</td>
                                                        <td >".($tempsoll_float-$temphyston)." °C</td>
                                                        <td >".($tempsoll_float-$temphystoff)." °C</td>";
                                                }
                                            ?>
                                        </tr>
                                        <tr>
                                            <td ><img src="images/befeuchtung.png" alt=""></td>
                                            <td ><img src="<?= $lbf ?>" title="PIN_HUM 24[18] -> IN 3 (GPIO 5)"></td>
                                            <td class="text_left"><? if($mod == 4) {echo "BE-/ENTFEUCHTUNG";} else {echo "BEFEUCHTUNG";}?></td>
                                            <td ><?=$hum_float?>%</td>
                                            <td ><?=$humsoll_float?>%</td>
                                            <td ><?=($humsoll_float-$humhyston)?>%</td>
                                            <td ><?=($humsoll_float-$humhystoff)?>%</td>
                                          </tr>
                                    </table>
                                    <hr>
                                    <table class="schaltzustaende">
                                        <tr>
                                            <td><b>TYP</b></td>
                                            <td ><b>STATUS</b></td>
                                            <td class="text_left">&nbsp;</td>
                                            <td ><b>Intervall</b></td>
                                            <td >&nbsp;</td>
                                            <td ><b>Dauer</b></td>
                                        </tr>
                                        <tr>
                                            <td ><img <? if ($tempoff == 0.00 || $tempon == 0) {echo "class=\"transpng\"";} ?> src="images/luftumwaelzung.png" alt=""></td>
                                            <td ><img src="<?= $uml ?>" title="PIN_FAN 18[12] -> IN 4 (GPIO 1)"></td>
                                            <td class="text_left">UMLUFT / TIMER</td>
                                            <td ><?=$tempoff?> Min</td>
                                            <td ></td>
                                            <td ><?=$tempon?> Min</td>
                                        </tr>
                                        <tr>
                                            <td ><img <? if ($tempoff1 == 0.00 || $tempon1 == 0) {echo "class=\"transpng\"";} ?> src="images/luftaustausch.png" alt=""></td>
                                            <td ><img src="<?= $lat ?>" title="PIN_FAN1 23[16] -> IN 5 (GPIO 4)"></td>
                                            <td class="text_left">ABLUFT / TIMER</td>
                                            <td ><?=$tempoff1?> Min</td>
                                            <td ></td>
                                            <td ><?=$tempon1?> Min</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            include 'footer.php';
        ?>