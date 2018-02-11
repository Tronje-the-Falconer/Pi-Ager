<?php
                                      include 'header.php';                                       // Template-Kopf und Navigation
                                      include 'modules/database.php';                             // Schnittstelle zur Datenbank
                                      include 'modules/logging.php';                            //liest die Datei fuer das logging ein
                                      include 'modules/names.php';                                // Variablen mit Strings
                                      include 'modules/read_settings_db.php';                   // Liest die Einstellungen (Temperaturregelung, Feuchte, Lueftung) und Betriebsart des RSS
                                      include 'modules/read_config_db.php';                     // Liest die Grundeinstellungen Sensortyp, Hysteresen, GPIO's)
                                      include 'modules/read_operating_mode_db.php';                  // Liest die Art der Reifesteuerung
                                      include 'modules/read_gpio.php';                            // Liest den aktuellen Zustand der GPIO-E/A
                                      include 'modules/read_current_db.php';                    // Liest die gemessenen Werte Temp, Humy, Timestamp
                                ?>
                                <h2 class="art-postheader"><?php echo _('current values'); ?></h2>
                        <!--        <div style="float: left; padding-left: 8px;" id="timestamp"></div>
                                <div style="float: left; padding-left: 8px;" id="json_timestamp"></div>
                                <div style="float: left; padding-left: 8px;" id="time_difference"></div>
                        -->
                                <!----------------------------------------------------------------------------------------Anzeige T/rLF-->
<?php
 echo "<script src='js/ajax.js'></script>";
?>
                                <div class="thermometers">
                                    <div class="th-display-div">
                                        <table><tr><td><div class="label"><?php echo '<img src="images/icons/temperature_42x42.png" alt="" style="padding-top: 10px;">'; ?></div><div style="float: center; padding-left: 8px;" id="temperature_values_old"></div></td></tr>
                                            <tr>
                                                <td>
                                                    <div class="de">
                                                        <div class="den">
                                                            <div class="dene">
                                                                <div class="denem">
                                                                    <div class="deneme">
                                                                        <div style="float: left; padding-left: 8px;" id="current_json_temperature_0"></div><span>.<div style="float: right; padding-top: 37px;" id="current_json_temperature_1"></div></span><strong>&deg;C</strong>
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
                                        <table><tr><td><div class="label"><?php echo '<img src="images/icons/humidity_42x42.png" alt="" style="padding-top: 10px;">'; ?></div><div style="float: center; padding-left: 8px;" id="humidity_values_old"></div></td></tr>
                                            <tr>
                                                <td>
                                                    <div class="de">
                                                        <div class="den">
                                                            <div class="dene">
                                                                <div class="denem">
                                                                    <div class="deneme">
                                                                        <div style="float: left; padding-left: 8px;" id="current_json_humidity_0"></div><span>.<div style="float: right; padding-top: 37px;" id="current_json_humidity_1"></div></span><strong>&#37 </strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!------------------------------ ----------------------------------------------------------Anzeige Scales-->
                                    <div class="th-display-div">
                                        <table><tr><td><div class="label">
                                            <?php echo '<img src="images/icons/scale_42x42.png" alt="" style="padding-top: 10px;">'.'1'; ?></div>
                                            <div style="float: center; padding-left: 8px;" id="scale1_values_old"></div></td></tr>
                                            <tr>
                                                <td>
                                                    <div class="denemescale">
                                                        <div style="padding-left: 8px;" id="scale_json_scale1"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="th-display-div">
                                        <table><tr><td><div class="label">
                                            <?php echo '<img src="images/icons/scale_42x42.png" alt="" style="padding-top: 10px;">'.'2'; ?></div>
                                            <div style="float: center; padding-left: 8px;" id="scale2_values_old"></div></td></tr>
                                            <tr>
                                                <td>
                                                    <div class="denemescale">
                                                        <div style="padding-left: 8px;" id="scale_json_scale2"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <!------------------------------ ----------------------------------------------------------T/rLF Diagramm-->
                                <?php
                                    $diagram_mode = 'hour';
                                    include 'modules/read_values_for_diagrams.php';
                                ?>
                                <canvas class="chart"; id="temperature_humidity_chart"></canvas>
                                <canvas class="chart"; id="scales_chart"></canvas>
                                
                                <script>
                                    var timeFormat = 'MM/DD/YYYY HH:mm';

                                    // Temperatur und Feuchte
                                    var temperature_humidity_chart = document.getElementById("temperature_humidity_chart");
                                    var config_temperature_humidity_chart = {
                                        type: 'line',
                                        data: {
                                            labels:
                                                <?php echo $temperature_timestamps_axis_text; ?>,
                                            datasets: [{
                                                label: '<?php echo _("temperature") ?>',
                                                yAxisID: 'temperature',
                                                data: <?php echo json_encode($temperature_dataset);?>,
                                                backgroundColor: '#C03738',
                                                borderColor: '#C03738',
                                                borderWidth: 2,
                                                <?php if ($diagram_mode == 'hour') {print 'pointRadius: 2,
                                                pointHitRadius: 5,';} else {print 'pointRadius: 0,
                                                pointHitRadius: 5,';} ?>
                                                cubicInterpolationMode: 'monotone',
                                                fill: false
                                            },
                                            {
                                                label: '<?php echo _("humidity") ?>',
                                                yAxisID: 'humidity',
                                                data: <?php echo json_encode($humidity_dataset); ?>,
                                                backgroundColor: '#59A9C4',
                                                borderColor: '#59A9C4',
                                                borderWidth: 2,
                                                <?php if ($diagram_mode == 'hour') {print 'pointRadius: 2,
                                                pointHitRadius: 5,';} else {print 'pointRadius: 0,
                                                pointHitRadius: 5,';} ?>
                                                cubicInterpolationMode: 'monotone',
                                                fill: false
                                            }]
                                        },
                                        options: {
                                            title: {
                                                display: false,
                                                text: '',
                                                // fontSize: 24
                                            },
                                            tooltips: {
                                                mode: 'index',
                                                intersect: false,
                                                callbacks: {
                                                    label: function(tooltipItem, data) {
                                                        return Number(tooltipItem.yLabel).toFixed(1);
                                                    }
                                                }
                                            },
                                            scales: {
                                                xAxes: [{
                                                    type: "time",
                                                    time: {
                                                        displayFormats: {
                                                            second: 'HH:mm:ss',
                                                            minute: 'HH:mm',
                                                            hour: 'MMM D, H[h]'
                                                        },
                                                        tooltipFormat: 'DD. MMM. YYYY HH:mm'
                                                    },
                                                }, ],
                                                yAxes: [{
                                                    scaleLabel: {
                                                        display: true,
                                                        labelString: '<?php echo _("temperature") ?>',
                                                    //    fontSize: 20,
                                                        fontColor: '#000000'
                                                    },
                                                    id: 'temperature',
                                                    type: 'linear',
                                                    position: 'left',
                                                    ticks: {
                                                        callback: function(value, index, values) {
                                                            return value + ' °C';
                                                        },
                                                        fontColor: '#000000',
                                                        // fontSize: 20,
                                                        max: 30,
                                                        min: -4
                                                    }
                                                    
                                                }, {
                                                    scaleLabel: {
                                                        display: true,
                                                        labelString: '<?php echo _("humidity") ?>',
                                                        // fontSize: 20,
                                                        fontColor: '#000000'
                                                    },
                                                    id: 'humidity',
                                                    type: 'linear',
                                                    display: true,
                                                    position: 'right',
                                                    labelString: '<?php echo _("humidity") ?>',
                                                    ticks: {
                                                        callback: function(value, index, values) {
                                                            return 'φ ' + value + ' %';
                                                        },
                                                        fontColor: '#000000',
                                                        // fontSize: 20,
                                                        // max: <?php 
                                                        // $max_value_humidiy = intval(max($humidity_dataset) + (max($humidity_dataset) / 100 * 1))+1;

                                                        // print $max_value_humidiy;
                                                        // ?>,
                                                        // min: <?php 
                                                        // $min_value_humidiy = intval(min($humidity_dataset) - (max($humidity_dataset) / 100 * 1))-1;
                                                        
                                                        // print $min_value_humidiy;
                                                        // ?>
                                                        max: 100,
                                                        min: 0
                                                    }
                                                }]
                                            }
                                        }
                                    };

                                    // Waagen
                                    var scales_chart = document.getElementById("scales_chart");
                                    var config_scales_chart = {
                                        type: 'line',
                                        data: {
                                            labels: 
                                                <?php echo $scale1_timestamps_axis_text; ?>,
                                            datasets: [{
                                                label: '<?php echo _("scale") ?> 1',
                                                yAxisID: 'scale1',
                                                data: <?php echo json_encode($scale1_dataset);?>,
                                                backgroundColor: '#AEC645',
                                                borderColor: '#AEC645',
                                                borderWidth: 2,
                                                <?php if ($diagram_mode == 'hour') {print 'pointRadius: 2,
                                                pointHitRadius: 5,';} else {print 'pointRadius: 0,
                                                pointHitRadius: 5,';} ?>
                                                cubicInterpolationMode: 'monotone',
                                                fill: false
                                            },
                                            {
                                                label: '<?php echo _("scale") ?> 2',
                                                yAxisID: 'scale2',
                                                data: <?php echo json_encode($scale2_dataset); ?>,
                                                backgroundColor: '#BF9543',
                                                borderColor: '#BF9543',
                                                borderWidth: 2,
                                                <?php if ($diagram_mode == 'hour') {print 'pointRadius: 2,
                                                pointHitRadius: 5,';} else {print 'pointRadius: 0,
                                                pointHitRadius: 5,';} ?>
                                                cubicInterpolationMode: 'monotone',
                                                fill: false
                                            }]
                                        },
                                        options: {
                                            title: {
                                                display: false,
                                                text: '<?php echo _("scale") ?> 1 & 2',
                                                // fontSize: 24
                                            },
                                            tooltips: {
                                                mode: 'index',
                                                intersect: false,
                                                callbacks: {
                                                    label: function(tooltipItem, data) {
                                                        return Number(tooltipItem.yLabel).toFixed(1);
                                                    }
                                                }
                                            },
                                            scales: {
                                                xAxes: [{
                                                    type: "time",
                                                    time: {
                                                        displayFormats: {
                                                            second: 'HH:mm:ss',
                                                            minute: 'HH:mm',
                                                            hour: 'MMM D, H[h]'
                                                        },
                                                        tooltipFormat: 'DD. MMM. YYYY HH:mm'
                                                    },
                                                }, ],
                                                yAxes: [{
                                                    scaleLabel: {
                                                        display: true,
                                                        labelString: '<?php echo _("scale") . ' 1'; ?>',
                                                        // fontSize: 20,
                                                        fontColor: '#000000'
                                                    },
                                                    id: 'scale1',
                                                    type: 'linear',
                                                    position: 'left',
                                                    ticks: {
                                                        callback: function(value, index, values) {
                                                            return value + ' gr';
                                                        },
                                                        fontColor: '#000000',
                                                        // fontSize: 20,
                                                        //max: 25000,
                                                        beginAtZero: true,
                                                        maxTicksLimit: 10,
                                                        max: <?php 
                                                        $max_value_scale1 = intval(max($scale1_dataset) + (max($scale1_dataset) / 100 * 5))+1;
                                                        
                                                        print $max_value_scale1;
                                                        ?>,
                                                        min: <?php 
                                                        $min_value_scale1 = intval(min($scale1_dataset) - (max($scale1_dataset) / 100 * 5))-1;
                                                        
                                                        print $min_value_scale1;
                                                        ?>,
                                                        //stepSize: 1
                                                    }
                                                    
                                                },
                                                {
                                                    scaleLabel: {
                                                        display: true,
                                                        labelString: '<?php echo _("scale") . ' 2'; ?>',
                                                        // fontSize: 20,
                                                        fontColor: '#000000'
                                                    },
                                                    id: 'scale2',
                                                    type: 'linear',
                                                    position: 'right',
                                                    ticks: {
                                                        callback: function(value, index, values) {
                                                            return value + ' gr';
                                                        },
                                                        fontColor: '#000000',
                                                        // fontSize: 20,
                                                        //max: 25000,
                                                        beginAtZero: true,
                                                        maxTicksLimit: 10,
                                                        max: <?php 
                                                        $max_value_scale2 = intval(max($scale2_dataset) + (max($scale2_dataset) / 100 * 5))+1;
                                                        
                                                        print $max_value_scale2;
                                                        ?>,
                                                        min: <?php 
                                                        $min_value_scale2 = intval(min($scale2_dataset) - (max($scale2_dataset) / 100 * 5))-1;
                                                        
                                                        print $min_value_scale2;
                                                        ?>,
                                                        //stepSize: 1
                                                    }
                                                    
                                                }]
                                            }
                                        }
                                    };
                                    
                                    window.onload = function() {
                                        window.temperature_humidity_chart = new Chart(temperature_humidity_chart, config_temperature_humidity_chart);
                                        window.scales_chart = new Chart(scales_chart, config_scales_chart);
                                    };
                                </script>
                                <!----------------------------------------------------------------------------------------Betriebsart-->
                                <h2 class="art-postheader"><?php echo _('statusboard'); ?></h2>
                                <div class="hg_container">
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td>
                                                <?php 
                                                    // Prüft, ob Prozess RSS läuft
                                                    $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
                                                    if ($grepmain == 0){
                                                        echo '<img src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;">';
                                                        echo '<br><img src="images/icons/operatingmode_fail_42x42.png" alt="" style="padding: 10px;">';
                                                    }
                                                    elseif ($grepmain != 0 and $status_piager == 0){
                                                        echo '<img src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;">';
                                                        echo '<br><img src="images/icons/operatingmode_42x42.png" alt="" style="padding: 10px;">';
                                                    }
                                                    elseif ($grepmain != 0 and $status_piager == 1) {
                                                        echo '<img src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;">';
                                                        echo '<br><img src="images/icons/operating_42x42.gif" alt="" style="padding: 10px;">';
                                                    }
                                                ?>
                                            </td>
                                            <td class="text_left_top"><?php echo '<b>'.strtoupper(_('operating mode')).':</b><br>';
                                                if ($grepmain == 0){
                                                    echo strtoupper(("see settings"));
                                                }
                                                elseif ($grepmain != 0 and $status_piager == 0){
                                                    echo strtoupper(("off"));
                                                }
                                                elseif($grepmain != 0 and $status_piager == 1){
                                                    echo $modus_name;
                                                }
                                                ?></td>
                                            <td>
                                                <?php 
                                                    // Prüft, ob Prozess Reifetab läuft
                                                    $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
                                                    if ($grepagingtable == 0){
                                                        echo '<img src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;">';
                                                        echo '<br><img src="images/icons/agingtable_42x42.png" alt="" style="padding: 10px;">';
                                                    }
                                                    else {
                                                        echo '<img src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;">';
                                                        echo '<br><img src="images/icons/agingtable_42x42.gif" alt="" style="padding: 10px;">';
                                                    }
                                                ?>
                                            </td>
                                            <td class="text_left_top"><?php echo '<b>'.strtoupper(_('agingtable')).':</b><br>'.$maturity_type;?></td>
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
                                                    echo '<td ><img src="images/icons/cooling_42x42.png" alt=""></td>
                                                        <td><img src="'.$cooler_on_off_png.'" title="PIN_COOL 4[7] -> IN 1 (PIN2)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('cooler'));
                                                    echo '</td>
                                                        <td>'.$sensor_temperature.' °C</td>
                                                        <td>'.$setpoint_temperature.' °C</td>
                                                        <td>'.($setpoint_temperature + $switch_on_cooling_compressor).' °C</td>
                                                        <td>'.($setpoint_temperature + $switch_off_cooling_compressor).' °C</td>';
                                                }
                                                if ($modus==1){
                                                    echo '<td ><img src="images/icons/cooling_42x42.png" alt=""></td>
                                                        <td><img src="'.$cooler_on_off_png.'" title="PIN_COOL 4[7] -> IN 1 (PIN2)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('cooler'));
                                                    echo '<td>'.$sensor_temperature.' °C</td>
                                                        <td>'.$setpoint_temperature.' °C</td>
                                                        <td>'.($setpoint_temperature + $switch_on_cooling_compressor).' °C</td>
                                                        <td>'.($setpoint_temperature + $switch_off_cooling_compressor).' °C</td>';
                                                }
                                                if ($modus==2){
                                                    echo '<td ><img src="images/icons/heating_42x42.png" alt=""></td>
                                                        <td><img src="'.$heater_on_off_png.'" title="PIN_HEATER 3[5] -> IN 2 (PIN 3)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('heater'));
                                                    echo '</td>
                                                        <td>'.$sensor_temperature.' °C</td>
                                                        <td>'.$setpoint_temperature.' °C</td>
                                                        <td>'.($setpoint_temperature - $switch_on_cooling_compressor).' °C</td>
                                                        <td>'.($setpoint_temperature - $switch_off_cooling_compressor).' °C</td>';
                                                }
                                                if ($modus==3 || $modus==4){
                                                    echo '<td ><img src="images/icons/cooling_42x42.png" alt=""></td>
                                                        <td><img src="'.$cooler_on_off_png.'" title="PIN_COOL 4[7] -> IN 1 (PIN2)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('cooler'));
                                                    echo '<td>'.$sensor_temperature.' °C</td>
                                                        <td>'.$setpoint_temperature.' °C</td>
                                                        <td>'.($setpoint_temperature + $switch_on_cooling_compressor).' °C</td>
                                                        <td>'.($setpoint_temperature + $switch_off_cooling_compressor).' °C</td></tr>';
                                                    echo '<tr> <td ><img src="images/icons/heating_42x42.png" alt=""></td>
                                                        <td><img src="'.$heater_on_off_png.'" title="PIN_HEATER 3[5] -> IN 2 (PIN 3)"></td>
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
                                                    echo '   <td ><img src="images/icons/humidification_42x42.png" alt=""></td>
                                                        <td><img src='.$humidifier_on_off_png.' title="PIN_HUM 18[12] -> IN 3 (PIN 4)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('humidification'));
                                                    echo '</td>
                                                        <td>'.$sensor_humidity.'%</td>
                                                        <td>'.$setpoint_humidity.'%</td>
                                                        <td>'.($setpoint_humidity - $switch_on_humidifier).'%</td>
                                                        <td>'.($setpoint_humidity - $switch_off_humidifier).'%</td>';
                                                }

                                                if ($modus==4){
                                                    echo '   <td ><img src="images/icons/humidification_42x42.png" alt=""></td>
                                                        <td><img src='.$humidifier_on_off_png.' title="PIN_HUM 18[12] -> IN 3 (PIN 4)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('humidification'));
                                                    echo '<td>'.$sensor_humidity.'%</td>
                                                        <td>'.$setpoint_humidity.'%</td>
                                                        <td>'.($setpoint_humidity - $switch_on_humidifier).'%</td>
                                                        <td>'.($setpoint_humidity - $switch_off_humidifier).'%</td></tr>';
                                                    echo '<tr> <td ><img src="images/icons/exhausting_42x42.png" alt=""></td>
                                                        <td><img src='.$exhausting_on_off_png.' title="PIN_EXH 23[16] -> IN 5 (PIN 5)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('exhausting'));
                                                    echo '</td>
                                                        <td>'.$sensor_humidity.'%</td>
                                                        <td>'.$setpoint_humidity.'%</td>
                                                        <td>'.($setpoint_humidity + $switch_on_humidifier).'%</td>
                                                        <td>'.($setpoint_humidity + $switch_off_humidifier).'%</td></tr>';
                                                    echo '<tr> <td ><img src="images/icons/dehumidification_42x42.png" alt=""></td>
                                                        <td><img src='.$dehumidifier_on_off_png.' title="PIN_DEH 7[26] -> IN 8 (PIN 9)"></td>
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
                                            <td><img <?php if ($circulation_air_duration == 0) {echo 'class="transpng"';} ?> src="images/icons/circulate_42x42.png" alt=""></td>
                                            <td><img src="<?php echo $circulating_on_off_png ;?>" title="PIN_FAN 18[12] -> IN 4 (GPIO 1)"></td>
                                            <td class="text_left">
                                            <?php 
                                                echo strtoupper(_('circulating air'));
                                                if ($circulation_air_duration > 0 && $circulation_air_period >0) {echo ', '.strtoupper(_('timer on'));}
                                                elseif ($circulation_air_period == 0) {echo  ' '. strtoupper(_('always on'));}
                                                elseif ($circulation_air_duration == 0) {echo ', '. strtoupper(_('timer off'));}
                                            ?></td>
                                            <td><?php echo $circulation_air_period.' '._('minutes'); ?></td>
                                            <td></td>
                                            <td><?php echo $circulation_air_duration.' '._('minutes'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><img <?php if ($uv_duration == 0) {echo 'class="transpng"';} ?> src="images/icons/uv-light_42x42.png" alt=""></td>
                                            <td><?php
                                                if($status_uv_manual == 0) {
                                                    echo '<img src="/images/icons/status_off_manual_20x20.png" title="uv off">';
                                                }
                                                else{
                                                    echo '<img src="'.$uv_on_off_png.'">';
                                                }
                                            ?>
                                            </td>
                                            <td class="text_left">
                                            <?php 
                                                echo strtoupper(_('uv-light'));
                                                if ($uv_duration > 0 && $uv_period >0) {echo ', '.strtoupper(_('timer on'));}
                                                elseif ($uv_period == 0) {echo ' '.strtoupper(_('always on'));}
                                                elseif ($uv_duration == 0) {echo ', '.strtoupper(_('timer off'));}
                                            ?></td>
                                            <td><?php echo $uv_period.' '._('minutes'); ?></td>
                                            <td></td>
                                            <td><?php echo $uv_duration.' '._('minutes'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><img <?php if ($light_duration == 0) {echo 'class="transpng"';} ?> src="images/icons/light_42x42.png" alt=""></td>
                                            <td>
                                                <?php
                                                if($status_light_manual == 1) {
                                                    echo '<img src="/images/icons/status_on_manual_20x20.png" title="light on">';
                                                }
                                                else{
                                                    echo '<img src="'.$uv_on_off_png.'">';
                                                }
                                                ?>
                                            </td>
                                            <td class="text_left">
                                            <?php 
                                                echo strtoupper(_('light'));
                                                if ($light_duration > 0 && $light_period >0) {echo ', '.strtoupper(_('timer on'));}
                                                elseif ($light_period == 0) {echo ' '.strtoupper(_('always on'));}
                                                elseif ($light_duration == 0) {echo ', '.strtoupper(_('timer off'));}
                                            ?></td>
                                            <td><?php echo $light_period.' '._('minutes'); ?></td>
                                            <td></td>
                                            <td><?php echo $light_duration.' '._('minutes'); ?></td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td><b><?php echo strtoupper(_('type')); ?></b></td>
                                            <td><b><?php echo strtoupper(_('status')); ?></b></td>
                                            <td class="text_left">&nbsp;</td>
                                            <td class="text_left">&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td class="text_left">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <?php
                                                $grepscale = shell_exec('sudo /var/sudowebscript.sh grepscale');
                                                if ($grepscale == 0){
                                                    echo '<td><img src="images/icons/scale_fail_42x42.png" alt=""></td>
                                                            <td><img src="';
                                                    echo $scale1_on_off_png ;
                                                    echo '" title=></td>
                                                            <td>';
                                                    echo strtoupper(_('see settings'));
                                                    echo '</td>';
                                                }
                                                elseif ($grepscale != 0 and $status_scale1 == 0){
                                                    echo '<td><img src="images/icons/scale_42x42.png" alt=""></td>
                                                            <td><img src="';
                                                    echo $scale1_on_off_png ;
                                                    echo '" title=></td>
                                                            <td>';
                                                    echo strtoupper(_('scale1'));
                                                    echo '</td>';
                                                }
                                                elseif ($grepscale != 0 and $status_scale1 == 1) {
                                                    echo '<td><img src="images/icons/scale_42x42.gif" alt=""></td>
                                                            <td><img src="';
                                                    echo $scale1_on_off_png ;
                                                    echo '" title=></td>
                                                            <td>';
                                                    echo strtoupper(_('scale1'));
                                                    echo '</td>';
                                                }
                                            ?>
                                            <td class="text_left">&nbsp;</td>
                                            <td class="text_left">&nbsp;</td>
                                            <td class="text_left">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <?php
                                                $grepscale = shell_exec('sudo /var/sudowebscript.sh grepscale');
                                                if ($grepscale == 0){
                                                    echo '<td></td>
                                                            <td></td>
                                                            <td></td>';
                                                }
                                                elseif ($grepscale != 0 and $status_scale2 == 0){
                                                    echo '<td><img src="images/icons/scale_42x42.png" alt=""></td>
                                                            <td><img src="';
                                                    echo $scale2_on_off_png ;
                                                    echo '" title=></td>
                                                            <td>';
                                                    echo strtoupper(_('scale2'));
                                                    echo '</td>';
                                                }
                                                elseif ($grepscale != 0 and $status_scale2 == 1) {
                                                    echo '<td><img src="images/icons/scale_42x42.gif" alt=""></td>
                                                            <td><img src="';
                                                    echo $scale2_on_off_png ;
                                                    echo '" title=></td>
                                                            <td>';
                                                    echo strtoupper(_('scale2'));
                                                    echo '</td>';
                                                }
                                            ?>
                                            <td class="text_left">&nbsp;</td>
                                            <td class="text_left">&nbsp;</td>
                                            <td class="text_left">&nbsp;</td>
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
