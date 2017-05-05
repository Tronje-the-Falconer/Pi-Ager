                                <?php 
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                    include 'modules/read_settings_json.php';                   // Liest die Einstellungen (Temperaturregelung, Feuchte, Lueftung) und Betriebsart des RSS
                                    include 'modules/read_config_json.php';                     // Liest die Grundeinstellungen Sensortyp, Hysteresen, GPIO's)
                                    include 'modules/read_operating_mode.php';                  // Liest die Art der Reifesteuerung
                                    include 'modules/read_gpio.php';                            // Liest den aktuellen Zustand der GPIO-E/A
                                    include 'modules/read_current_json.php';                    // Liest die gemessenen Werte Temp, Humy, Timestamp
                                ?>
                                <h2 class="art-postheader"><?php echo _('current values'); ?></h2>
                                <!----------------------------------------------------------------------------------------Anzeige T/rLF-->
                                <div class="thermometers">
                                    <div class="th-display-div">
                                        <table><tr><td><div class="label"><?php echo _('temperature').'( &deg;C)'; ?></div></td></tr>
                                            <tr>
                                                <td>
                                                    <div class="de">
                                                        <div class="den">
                                                            <div class="dene">
                                                                <div class="denem">
                                                                    <div class="deneme">
                                                                        <?php 
                                                                            // Die Aktualität der Werte pruefen, geichzeitige RSS-Funktionspruefung
                                                                            $timestamp_unix = time();
                                                                            $time_difference = $timestamp_unix - $current_json_timestamp_last_change ;
                                                                            if ($time_difference >= 120) {
                                                                                $temperature_linestring = '<div style="float: left; padding-left: 8px;" id=""></div>--<span>.<div style="float: right; padding-top: 50px;" id="">-</div></span><strong>&deg;</strong>';
                                                                                $humidity_linestring = '<div style="float: left; padding-left: 8px;" id=""></div>--<span>.<div style="float: right; padding-top: 50px;" id="">-</div></span><strong>&#37</strong> ';
                                                                            }
                                                                            else {
                                                                                $temperature_linestring = '<div style="float: left; padding-left: 8px;" id="current_json_temperature_0"></div><span>.<div style="float: right; padding-top: 37px;" id="current_json_temperature_1"></div></span><strong>&deg;</strong>';
                                                                                $humidity_linestring = '<div style="float: left; padding-left: 8px;" id="current_json_humidity_0"></div><span>.<div style="float: right; padding-top: 37px;" id="current_json_humidity_1"></div></span><strong>&#37</strong> ';
                                                                            }
                                                                        ?>
                                                                        <?php echo $temperature_linestring; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="th-display-div">
                                        <table><tr><td><div class="label"><?php echo _('humidity').'( %)'; ?></div></td></tr>
                                            <tr>
                                                <td>
                                                    <div class="de">
                                                        <div class="den">
                                                            <div class="dene">
                                                                <div class="denem">
                                                                    <div class="deneme">
                                                                        <?php echo $humidity_linestring; ?>
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
                                <h2><?php echo _('temperature profile'); ?></h2>
                                <img src="/pic/pi-ager_sensor_temperature-hourly.png" alt="<?php echo _('hours history temperature'); ?>" />
                                <br/><br/>
                                <h2><?php echo _('humidity profile'); ?></h2>
                                <img src="/pic/pi-ager_sensor_humidity-hourly.png" alt="<?php echo _('hours history humidity'); ?>" /><br><br>
                                <!----------------------------------------------------------------------------------------Betriebsart-->
                                <h2><?php echo _('statusboard'); ?></h2>
                                <div class="hg_container">
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td>
                                                <?php 
                                                    // PrUEft, ob Prozess RSS läuft
                                                    $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
                                                    if ($grepmain == 0){
                                                        echo '<img src="images/led-off-green-20x20.png" alt="" style="padding-top: 10px;">';
                                                    }
                                                    else {
                                                        echo '<img src="images/led-on-green-20x20.png" alt="" style="padding-top: 10px;">';
                                                    }
                                                ?>
                                                <br><img src="images/operating_mode.png" alt="" style="padding: 10px;">
                                            </td>
                                            <td class="text_left_top"><b><?php echo strtoupper(_('operating mode')).':</b><br>'; if ($grepmain == 0){echo strtoupper(("off"));} else {echo $modus_name;} ?></td>
                                            <td>
                                                <?php 
                                                    // PrUEft, ob Prozess Reifetab läuft
                                                    $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
                                                    if ($grepagingtable == 0){
                                                        echo '<img src="images/led-off-green-20x20.png" alt="" style="padding-top: 10px;">';
                                                    }
                                                    else {
                                                        echo '<img src="images/led-on-green-20x20.png" alt="" style="padding-top: 10px;">';
                                                    }
                                                ?>
                                                <br><img src="images/agingtable.png" alt="" style="padding: 10px;">
                                            </td>
                                            <td class="text_left_top"><b><?php echo strtoupper(_('agingtable')).':</b><br>'.$maturity_type;?></td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td><b><?php echo strtoupper(_('type')); ?></b></td>
                                            <td><b><?php echo strtoupper(_('status')); ?></b></td>
                                            <td class="text_left">&nbsp;</td>
                                            <td><b><?php echo strtoupper(_('actual')); ?></b></td>
                                            <td><b><?php echo strtoupper(_('target')); ?></b></td>
                                            <td><b><?php echo strtoupper(_('on')); ?></b></td>
                                            <td><b><?php echo strtoupper(_('off')); ?></b></td>
                                        </tr>
                                        <tr>
                                            <?php 
                                                if ($modus==0){
                                                    echo '   <td ><img src="images/cooling.png" alt=""></td>
                                                        <td><img src="'.$cooler_on_off_png.'" title="PIN_COOL 22[15] -> IN 1 (GPIO 3)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('cooler'));
                                                    echo '</td>
                                                        <td>'.$sensor_temperature.' °C</td>
                                                        <td>'.$setpoint_temperature.' °C</td>
                                                        <td>'.($setpoint_temperature + $switch_on_cooling_compressor).' °C</td>
                                                        <td>'.($setpoint_temperature + $switch_off_cooling_compressor).' °C</td>';
                                                }
                                                if ($modus==1){
                                                    echo '   <td ><img src="images/cooling.png" alt=""></td>
                                                        <td><img src="'.$cooler_on_off_png.'" title="PIN_COOL 22[15] -> IN 1 (GPIO 3)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('cooler'));
                                                    echo '<td>'.$sensor_temperature.' °C</td>
                                                        <td>'.$setpoint_temperature.' °C</td>
                                                        <td>'.($setpoint_temperature + $switch_on_cooling_compressor).' °C</td>
                                                        <td>'.($setpoint_temperature + $switch_off_cooling_compressor).' °C</td>';
                                                }
                                                if ($modus==2){
                                                    echo '   <td ><img src="images/heating.png" alt=""></td>
                                                        <td><img src="'.$heater_on_off_png.'" title="PIN_HEATER 27[13] -> IN 2 (GPIO 2)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('heater'));
                                                    echo '</td>
                                                        <td>'.$sensor_temperature.' °C</td>
                                                        <td>'.$setpoint_temperature.' °C</td>
                                                        <td>'.($setpoint_temperature - $switch_on_cooling_compressor).' °C</td>
                                                        <td>'.($setpoint_temperature - $switch_off_cooling_compressor).' °C</td>';
                                                }
                                                if ($modus==3 || $modus==4){
                                                    echo '   <td ><img src="images/cooling.png" alt=""></td>
                                                        <td><img src="'.$cooler_on_off_png.'" title="PIN_COOL 22[15] -> IN 1 (GPIO 3)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('cooler'));
                                                    echo '<td>'.$sensor_temperature.' °C</td>
                                                        <td>'.$setpoint_temperature.' °C</td>
                                                        <td>'.($setpoint_temperature + $switch_on_cooling_compressor).' °C</td>
                                                        <td>'.($setpoint_temperature + $switch_off_cooling_compressor).' °C</td></tr>';
                                                    echo '<tr> <td ><img src="images/heating.png" alt=""></td>
                                                        <td><img src="'.$heater_on_off_png.'" title="PIN_HEATER 27[13] -> IN 2 (GPIO 2)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('heater'));
                                                    echo '<td>'.$sensor_temperature.' °C</td>
                                                        <td>'.$setpoint_temperature.' °C</td>
                                                        <td>'.($setpoint_temperature - $switch_on_cooling_compressor).' °C</td>
                                                        <td>'.($setpoint_temperature - $switch_off_cooling_compressor).' °C</td>';
                                                }
                                            ?>
                                        </tr>
                                        <tr>
                                           <?php 
                                                if ($modus==1 || $modus==2 || $modus==3){
                                                    echo '   <td ><img src="images/humidification.png" alt=""></td>
                                                        <td><img src='.$humidifier_on_off_png.' title="PIN_HUM 24[18] -> IN 3 (GPIO 5)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('humidification'));
                                                    echo '</td>
                                                        <td>'.$sensor_humidity.'%</td>
                                                        <td>'.$setpoint_humidity.'%</td>
                                                        <td>'.($setpoint_humidity - $switch_on_humidifier).'%</td>
                                                        <td>'.($setpoint_humidity - $switch_off_humidifier).'%</td>';
                                                }

                                                if ($modus==4){
                                                    echo '   <td ><img src="images/humidification.png" alt=""></td>
                                                        <td><img src='.$humidifier_on_off_png.' title="PIN_HUM 24[18] -> IN 3 (GPIO 5)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('humidification'));
                                                    echo '<td>'.$sensor_humidity.'%</td>
                                                        <td>'.$setpoint_humidity.'%</td>
                                                        <td>'.($setpoint_humidity - $switch_on_humidifier).'%</td>
                                                        <td>'.($setpoint_humidity - $switch_off_humidifier).'%</td></tr>';
                                                    echo '<tr> <td ><img src="images/dehumidification.png" alt=""></td>
                                                        <td><img src='.$exhausting_on_off_png.' title="PIN_FAN1 23[16] -> IN 5 (GPIO 4)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('dehumidification'));
                                                    echo '</td>
                                                        <td>'.$sensor_humidity.'%</td>
                                                        <td>'.$setpoint_humidity.'%</td>
                                                        <td>'.($setpoint_humidity + $switch_on_humidifier).'%</td>
                                                        <td>'.($setpoint_humidity + $switch_off_humidifier).'%</td></tr>';
                                                }
                                          ?>
                                       </tr>
                                    </table>
                                    <hr>
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td><b><?php echo strtoupper(_('type')); ?></b></td>
                                            <td><b><?php echo strtoupper(_('status')); ?></b></td>
                                            <td class="text_left">&nbsp;</td>
                                            <td><b><?php echo strtoupper(_('period')); ?></b></td>
                                            <td>&nbsp;</td>
                                            <td><b><?php echo strtoupper(_('duration')); ?></b></td>
                                        </tr>
                                        <tr>
                                            <td><img <?php if ($circulation_air_duration == 0) {echo 'class="transpng"';} ?> src="images/circulating.png" alt=""></td>
                                            <td><img src="<?php echo $circulating_on_off_png ;?>" title="PIN_FAN 18[12] -> IN 4 (GPIO 1)"></td>
                                            <td class="text_left">
                                            <?php 
                                                echo _('circulating air');
                                                if ($circulation_air_duration > 0 && $circulation_air_period >0) {echo ', '.strtoupper(_('timer on'));}
                                                elseif ($circulation_air_period == 0) {echo  ' '. strtoupper(_('always on'));}
                                                elseif ($circulation_air_duration == 0) {echo ', '. strtoupper(_('timer off'));}
                                            ?></td>
                                            <td><?php echo $circulation_air_period.' '._('minutes'); ?></td>
                                            <td></td>
                                            <td><?php echo $circulation_air_duration.' '._('minutes'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><img <?php if ($exhaust_air_duration == 0) {echo 'class="transpng"';} ?> src="images/exhausting.png" alt=""></td>
                                            <td><img src="<?php echo $exhausting_on_off_png; ?>" title="PIN_FAN1 23[16] -> IN 5 (GPIO 4)"></td>
                                            <td class="text_left">
                                            <?php 
                                                echo _('exhausting air');
                                                if ($exhaust_air_duration > 0 && $exhaust_air_period >0) {echo ', '.strtoupper(_('timer on'));}
                                                elseif ($exhaust_air_period == 0) {echo ' '.strtoupper(_('always on'));}
                                                elseif ($exhaust_air_duration == 0) {echo ', '.strtoupper(_('timer off'));}

                                            ?></td>
                                            <td><?php echo $exhaust_air_period.' '._('minutes'); ?></td>
                                            <td></td>
                                            <td><?php echo $exhaust_air_duration.' '._('minutes'); ?></td>
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
