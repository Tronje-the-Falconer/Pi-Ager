                                <?php 
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                    include 'modules/read_settings_json.php';                   // Liest die Einstellungen (Temperaturregelung, Feuchte, Lueftung und deren Hysteresen) und Betriebsart des RSS
                                    include 'modules/read_operating_mode.php';                  // Liest die Art der Reifesteuerung
                                    include 'modules/read_gpio.php';                            // Liest den aktuellen Zustand der GPIO-E/A
                                    include 'modules/read_current_json.php';                    // Liest die gemessenen Werte Temp, Humy, Timestamp
                                ?>
                                <h2 class="art-postheader"><?php echo _('Aktuelle Werte'); ?></h2>
                                <!----------------------------------------------------------------------------------------Anzeige T/rLF-->
                                <div class="thermometers">
                                    <div class="th-anzeige-div">
                                        <table><tr><td><div class="label"><?php sprintf(_('Temperatur %s'),'(&deg;C)'); ?></div></td></tr>
                                            <tr>
                                                <td>
                                                    <div class="de">
                                                        <div class="den">
                                                            <div class="dene">
                                                                <div class="denem">
                                                                    <div class="deneme">
                                                                        <?php 
                                                                            // Die Aktualität der Werte prUEfen, geichzeitige RSS-FunktionsprUEfung
                                                                            $timestamp_unix = time();
                                                                            $time_difference = $timestamp_unix - $current_json_timestamp_last_change;
                                                                            if ($time_difference >= 120) {
                                                                                $temperature_linestring = '<div style="float: left; padding-left: 8px;" id=""></div>--<span>.<div style="float: right; padding-top: 50px;" id="">-</div></span><strong>&deg;</strong>';
                                                                                $humidity_linestring = '<div style="float: left; padding-left: 8px;" id=""></div>--<span>.<div style="float: right; padding-top: 50px;" id="">-</div></span><strong>&#37</strong> ';
                                                                            }
                                                                            else {
                                                                                $temperature_linestring = '<div style="float: left; padding-left: 8px;" id="current_json_temperatur_0"></div><span>.<div style="float: right; padding-top: 37px;" id="current_json_temperatur_1"></div></span><strong>&deg;</strong>';
                                                                                $humidity_linestring = '<div style="float: left; padding-left: 8px;" id="current_json_luftfeuchtigkeit_0"></div><span>.<div style="float: right; padding-top: 37px;" id="current_json_luftfeuchtigkeit_1"></div></span><strong>&#37</strong> ';
                                                                            }
                                                                        ?>
                                                                        <?php echo $temperature_linestring;?>
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
                                        <table><tr><td><div class="label"><?php sprintf(_('Luftfeuchtigkeit'), '(%)'); ?></div></td></tr>
                                            <tr>
                                                <td>
                                                    <div class="de">
                                                        <div class="den">
                                                            <div class="dene">
                                                                <div class="denem">
                                                                    <div class="deneme">
                                                                        <?php echo $humidity_linestring;?>
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
                                <h2><?php echo _('Temperaturverlauf'); ?></h2>
                                <img src="/pic/pi-ager_sensortemp-hourly.png" alt="<?php echo _('Stundenverlauf Temperatur'); ?>" />
                                <br/><br/>
                                <h2><?php echo _('Luftfeuchtigkeitsverlauf'); ?></h2>
                                <img src="/pic/pi-ager_sensorhum-hourly.png" alt="<?php echo _('Stundenverlauf Luftfeuchtigkeit'); ?>" /><br><br>
                                <!----------------------------------------------------------------------------------------Betriebsart-->
                                <h2><?php _('Statusboard'); ?></h2>
                                <div class="hg_container">
                                    <table class="schaltzustaende minischrift">
                                        <tr>
                                            <td>
                                                <?php 
                                                    // PrUEft, ob Prozess RSS läuft
                                                    $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
                                                    if ($grepmain == 0){
                                                        print '<img src="images/led-off-green-20x20.png" alt="" style="padding-top: 10px;">';
                                                    }
                                                    else {
                                                        print '<img src="images/led-on-green-20x20.png" alt="" style="padding-top: 10px;">';
                                                    }
                                                ?>
                                                <br><img src="images/operating_mode.png" alt="" style="padding: 10px;">
                                            </td>
                                            <td class="text_left_top"><b><?php echo _('BETRIEBSART:'); ?> </b><br><?php if ($grepmain == 0){echo _("AUS");} else {print $modus_text;}?></td>
                                            <td>
                                                <?php 
                                                    // PrUEft, ob Prozess Reifetab läuft
                                                    $grephangingtable = shell_exec('sudo /var/sudowebscript.sh grephangingtable');
                                                    if ($grephangingtable == 0){
                                                        print '<img src="images/led-off-green-20x20.png" alt="" style="padding-top: 10px;">';
                                                    }
                                                    else {
                                                        print '<img src="images/led-on-green-20x20.png" alt="" style="padding-top: 10px;">';
                                                    }
                                                ?>
                                                <br><img src="images/hangingtable.png" alt="" style="padding: 10px;">
                                            </td>
                                            <td class="text_left_top"><b><?php echo _('REIFETABELLE:'); ?></b><br><?php echo $maturity_type;?></td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <table class="schaltzustaende minischrift">
                                        <tr>
                                            <td><b><?php echo _('TYP'); ?></b></td>
                                            <td ><b><?php echo _('STATUS'); ?></b></td>
                                            <td class="text_left">&nbsp;</td>
                                            <td ><b><?php echo _('IST'); ?></b></td>
                                            <td ><b><?php echo _('SOLL'); ?></b></td>
                                            <td ><b><?php echo _('EIN'); ?></b></td>
                                            <td ><b><?php echo _('AUS'); ?></b></td>
                                        </tr>
                                        <tr>
                                            <?php 
                                                if ($modus==0){
                                                    print '   <td ><img src="images/cooling.png" alt=""></td>
                                                        <td ><img src="'.$cool.'" title="PIN_COOL 22[15] -> IN 1 (GPIO 3)"></td>
                                                        <td class="text_left">';
                                                    echo _('KUEHLUNG');
                                                    print '</td>
                                                        <td >'.$sensor_temperature.' °C</td>
                                                        <td >'.$setpoint_temperature.' °C</td>
                                                        <td >'.($setpoint_temperature+$switch_on_cooling_compressor).' °C</td>
                                                        <td >'.($setpoint_temperature+$switch_off_cooling_compressor).' °C</td>';
                                                }
                                                if ($modus==1){
                                                    print '   <td ><img src="images/cooling.png" alt=""></td>
                                                        <td ><img src="'.$cool.'" title="PIN_COOL 22[15] -> IN 1 (GPIO 3)"></td>
                                                        <td class="text_left">';
                                                    echo _('KUEHLUNG');
                                                    print '<td >'.$sensor_temperature.' °C</td>
                                                        <td >'.$setpoint_temperature.' °C</td>
                                                        <td >'.($setpoint_temperature+$switch_on_cooling_compressor).' °C</td>
                                                        <td >'.($setpoint_temperature+$switch_off_cooling_compressor).' °C</td>';
                                                }
                                                if ($modus==2){
                                                    print '   <td ><img src="images/heating.png" alt=""></td>
                                                        <td ><img src="'.$heat.'" title="PIN_HEATER 27[13] -> IN 2 (GPIO 2)"></td>
                                                        <td class="text_left">';
                                                    echo _('HEIZUNG');
                                                    print '</td>
                                                        <td >'.$sensor_temperature.' °C</td>
                                                        <td >'.$setpoint_temperature.' °C</td>
                                                        <td >'.($setpoint_temperature-$switch_on_cooling_compressor).' °C</td>
                                                        <td >'.($setpoint_temperature-$switch_off_cooling_compressor).' °C</td>';
                                                }
                                                if ($modus==3 || $modus==4){
                                                    print '   <td ><img src="images/cooling.png" alt=""></td>
                                                        <td ><img src="'.$cool.'" title="PIN_COOL 22[15] -> IN 1 (GPIO 3)"></td>
                                                        <td class="text_left">';
                                                    echo _('KUEHLUNG');
                                                    print '<td >'.$sensor_temperature.' °C</td>
                                                        <td >'.$setpoint_temperature.' °C</td>
                                                        <td >'.($setpoint_temperature+$switch_on_cooling_compressor).' °C</td>
                                                        <td >'.($setpoint_temperature+$switch_off_cooling_compressor).' °C</td></tr>';
                                                    print '<tr> <td ><img src="images/heating.png" alt=""></td>
                                                        <td ><img src="'.$heat.'" title="PIN_HEATER 27[13] -> IN 2 (GPIO 2)"></td>
                                                        <td class="text_left">';
                                                    echo _('HEIZUNG');
                                                    print '<td >'.$sensor_temperature.' °C</td>
                                                        <td >'.$setpoint_temperature.' °C</td>
                                                        <td >'.($setpoint_temperature-$switch_on_cooling_compressor).' °C</td>
                                                        <td >'.($setpoint_temperature-$switch_off_cooling_compressor).' °C</td>';
                                                }
                                            ?>
                                        </tr>
                                        <tr>
                                           <?php 
                                                if ($modus==1 || $modus==2 || $modus==3){
                                                    print '   <td ><img src="images/umidification.png" alt=""></td>
                                                        <td ><img src='.$lbf.' title="PIN_HUM 24[18] -> IN 3 (GPIO 5)"></td>
                                                        <td class="text_left">';
                                                    echo _('BEFEUCHTUNG');
                                                    print '</td>
                                                        <td >'.$sensor_humidity.'%</td>
                                                        <td >'.$setpoint_humidity.'%</td>
                                                        <td >'.($setpoint_humidity-$switch_on_humidifier).'%</td>
                                                        <td >'.($setpoint_humidity-$switch_off_humidifier).'%</td>';
                                                }

                                                if ($modus==4){
                                                    print '   <td ><img src="images/umidification.png" alt=""></td>
                                                        <td ><img src='.$lbf.' title="PIN_HUM 24[18] -> IN 3 (GPIO 5)"></td>
                                                        <td class="text_left">';
                                                    echo _('BEFEUCHTUNG');
                                                    print '<td >'.$sensor_humidity.'%</td>
                                                        <td >'.$setpoint_humidity.'%</td>
                                                        <td >'.($setpoint_humidity-$switch_on_humidifier).'%</td>
                                                        <td >'.($setpoint_humidity-$switch_off_humidifier).'%</td></tr>';
                                                    print '<tr> <td ><img src="images/dehumidification.png" alt=""></td>
                                                        <td ><img src='.$lat.' title="PIN_FAN1 23[16] -> IN 5 (GPIO 4)"></td>
                                                        <td class="text_left">';
                                                    echo _('ENTFEUCHTUNG');
                                                    print '</td>
                                                        <td >'.$sensor_humidity.'%</td>
                                                        <td >'.$setpoint_humidity.'%</td>
                                                        <td >'.($setpoint_humidity+$switch_on_humidifier).'%</td>
                                                        <td >'.($setpoint_humidity+$switch_off_humidifier).'%</td></tr>';
                                                }
                                          ?>
                                       </tr>
                                    </table>
                                    <hr>
                                    <table class="schaltzustaende minischrift">
                                        <tr>
                                            <td><b><?php echo _('TYP'); ?></b></td>
                                            <td ><b><?php echo _('STATUS'); ?></b></td>
                                            <td class="text_left">&nbsp;</td>
                                            <td ><b><?php echo _('Periode'); ?></b></td>
                                            <td >&nbsp;</td>
                                            <td ><b><?php echo _('Dauer'); ?></b></td>
                                        </tr>
                                        <tr>
                                            <td ><img <?php if ($circulation_air_duration == 0) {echo 'class="transpng"';} ?> src="images/circulating.png" alt=""></td>
                                            <td ><img src="<?php echo $uml ;?>" title="PIN_FAN 18[12] -> IN 4 (GPIO 1)"></td>
                                            <td class="text_left">
                                            <?php 
                                                echo _('UMLUFT');
                                                if ($circulation_air_duration > 0 && $circulation_air_period >0) {echo ', '; echo _('TIMER AN');}
                                                elseif ($circulation_air_period == 0) {echo  ' '; echo _('IMMER AN');}
                                                elseif ($circulation_air_duration == 0) {echo ', ' ; echo _('TIMER AUS');}
                                            ?></td>
                                            <td ><?php echo $circulation_air_period; echo _(' Minuten'); ?></td>
                                            <td ></td>
                                            <td ><?php echo $circulation_air_duration; echo _(' Mininuten'); ?></td>
                                        </tr>
                                        <tr>
                                            <td ><img <?php if ($exhaust_air_duration == 0) {echo 'class="transpng"';} ?> src="images/exhausting.png" alt=""></td>
                                            <td ><img src="<?php echo $lat; ?>" title="PIN_FAN1 23[16] -> IN 5 (GPIO 4)"></td>
                                            <td class="text_left">
                                            <?php 
                                                echo _('ABLUFT');
                                                if ($exhaust_air_duration > 0 && $exhaust_air_period >0) {echo ', '; echo _('TIMER AN');}
                                                elseif ($exhaust_air_period == 0) {echo ' '; echo _('IMMER AN');}
                                                elseif ($exhaust_air_duration == 0) {echo ', '; echo _('TIMER AUS');}

                                            ?></td>
                                            <td ><?php echo $exhaust_air_period; echo _(' Minuten'); ?></td>
                                            <td ></td>
                                            <td ><?php echo $exhaust_air_duration; echo _(' Minuten'); ?></td>
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