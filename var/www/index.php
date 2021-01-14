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
                                      include 'modules/read_bus.php';                           //liest den bus aus, um externsensor ein oder auszublenden
                                ?>
                                <h2 class="art-postheader"><?php echo _('mainsensors'); ?></h2>
                        <!--        <div style="float: left; padding-left: 8px;" id="timestamp"></div>
                                <div style="float: left; padding-left: 8px;" id="json_timestamp"></div>
                                <div style="float: left; padding-left: 8px;" id="time_difference"></div>
                        -->
                                <!----------------------------------------------------------------------------------------Anzeige T/rLF-->
                                <div class="hg_container">
                                    <h2><?php echo _('internal'); ?></h2>
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td>
                                                <img src="images/icons/temperature.png" alt="" style="padding-top: 10px;">
                                            </td>
                                            <td>
                                                <img src="images/icons/humidity.png" alt="" style="padding-top: 10px;">
                                            </td>
                                            <td>
                                                <img src="images/icons/dew_point.png" alt="" style="padding-top: 10px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td id="json_temperature_main" style="text-align: center; font-size: 24px; text-shadow:0 0 5px #ff0000;"></td>
                                            <td id="json_humidity_main" style="text-align: center; font-size: 24px; text-shadow:0 0 5px #0066FF;"></td>
                                            <td id="json_dewpoint_main" style="text-align: center; font-size: 24px; text-shadow:0 0 5px #00cc66;"></td>
                                        </tr>
                                    </table>
                                    <?php
                                        if ($bus == 1 || $sensorsecondtype == 0){
                                            echo '<table class="switching_state miniature_writing" style="display: none !important;">';
                                        }
                                        else {
                                            echo '<hr><h2>';
                                            echo _('external');
                                            echo '</h2>';
                                            echo '<table class="switching_state miniature_writing">';
                                        }
                                        echo '    
                                                        <tr>
                                                            <td>
                                                                <img src="images/icons/temperature_extern_42x42.png" alt="" style="padding-top: 10px;">
                                                            </td>
                                                            <td>
                                                                <img src="images/icons/humidity_extern_42x42.png" alt="" style="padding-top: 10px;">
                                                            </td>
                                                            <td>
                                                                <img src="images/icons/dew_point_extern_42x42.png" alt="" style="padding-top: 10px;">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td id="json_temperature_extern" style="text-align: center; font-size: 20px;"></td>
                                                            <td id="json_humidity_extern" style="text-align: center; font-size: 20px;"></td>
                                                            <td id="json_dewpoint_extern" style="text-align: center; font-size: 20px;"></td>
                                                        </tr>
                                                    </table>';
                                    ?>
                                </div>
                                <hr>
                                    <!------------------------------ ----------------------------------------------------------Anzeige meat thermometers-->
                                <h2 class="art-postheader"><?php echo _('ntc sensors'); ?></h2>
                                <div class="hg_container">
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td>
                                                <img src="images/icons/temperature_42x42.png" alt="">&thetasym; 1
                                            </td>
                                            <td>
                                                <img src="images/icons/temperature_42x42.png" alt="">&thetasym; 2
                                            </td>
                                            <td>
                                                <img src="images/icons/temperature_42x42.png" alt="">&thetasym; 3
                                            </td>
                                        <tr>
                                            <td id="json_meat_temperature1" style="font-size: 20px;">
                                            </td>
                                            <td id="json_meat_temperature2" style="font-size: 20px;">
                                            </td>
                                            <td id="json_meat_temperature3" style="font-size: 20px;">
                                            </td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td>
                                                <img src="images/icons/voltage_42x42.png" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td id="json_meat_temperature4" style="font-size: 20px;"></td>
                                        </tr>
                                    </table>
                                </div>
                            <hr>
                            <!------------------------------ ----------------------------------------------------------Anzeige Scales-->
                            <h2 class="art-postheader"><?php echo _('scales'); ?></h2>
                                <div class="hg_container">
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td width="100px"></td>
                                            <td></td>
                                            <td></td>
                                            <td width="100px"></td>
                                            
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <img src="images/icons/scale_42x42.png" alt="">1
                                            </td>
                                            <td>
                                                <img src="images/icons/scale_42x42.png" alt="" style="padding-top: 10px;">2
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td id="json_scale1" style="font-size: 20px;"></td>
                                            <td id="json_scale2" style="font-size: 20px;"></td>
                                            <td></td>
                                        </tr>
                                    </table>
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
                                                <?php if ($diagram_mode == 'hour') {print 'pointRadius: 1,
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
                                                <?php if ($diagram_mode == 'hour') {print 'pointRadius: 1,
                                                pointHitRadius: 5,';} else {print 'pointRadius: 0,
                                                pointHitRadius: 5,';} ?>
                                                cubicInterpolationMode: 'monotone',
                                                fill: false
                                            },
                                            {
                                                label: '<?php echo _("temperature") . ' NTC 1' ?>',
												hidden: true,
                                                yAxisID: 'temperature',
                                                data: <?php echo json_encode($thermometer1_dataset); ?>,
                                                backgroundColor: '#F7AC08',
                                                borderColor: '#F7AC08',
                                                borderWidth: 2,
                                                <?php if ($diagram_mode == 'hour') {print 'pointRadius: 1,
                                                pointHitRadius: 5,';} else {print 'pointRadius: 0,
                                                pointHitRadius: 5,';} ?>
                                                cubicInterpolationMode: 'monotone',
                                                fill: false
                                            },
                                            {
                                                label: '<?php echo _("temperature") . ' NTC 2' ?>',
												hidden: true,
                                                yAxisID: 'temperature',
                                                data: <?php echo json_encode($thermometer2_dataset); ?>,
                                                backgroundColor: '#06AF8F',
                                                borderColor: '#06AF8F',
                                                borderWidth: 2,
                                                <?php if ($diagram_mode == 'hour') {print 'pointRadius: 1,
                                                pointHitRadius: 5,';} else {print 'pointRadius: 0,
                                                pointHitRadius: 5,';} ?>
                                                cubicInterpolationMode: 'monotone',
                                                fill: false
                                            },
                                            {
                                                label: '<?php echo _("temperature") . ' NTC 3' ?>',
												hidden: true,
                                                yAxisID: 'temperature',
                                                data: <?php echo json_encode($thermometer3_dataset); ?>,
                                                backgroundColor: '#AF06A1',
                                                borderColor: '#AF06A1',
                                                borderWidth: 2,
                                                <?php if ($diagram_mode == 'hour') {print 'pointRadius: 1,
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
                                                        if (tooltipItem.datasetIndex === 0) {
                                                            return Number(tooltipItem.yLabel).toFixed(1) + ' °C';
                                                        } else if (tooltipItem.datasetIndex === 1) {
                                                            return Number(tooltipItem.yLabel).toFixed(1) + ' %';
                                                        } else if (tooltipItem.datasetIndex === 2) {
                                                            return Number(tooltipItem.yLabel).toFixed(1) + ' °C';
                                                        } else if (tooltipItem.datasetIndex === 3) {
                                                            return Number(tooltipItem.yLabel).toFixed(1) + ' °C';
                                                        } else if (tooltipItem.datasetIndex === 4) {
                                                            return Number(tooltipItem.yLabel).toFixed(1) + ' °C';
                                                        }                                                        
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
                                                        labelString: '<?php echo _("temperature")?> <?php echo _(" - ϑ") ?>',
                                                    //    fontSize: 20,
                                                        fontColor: '#000000'
                                                    },
                                                    id: 'temperature',
                                                    type: 'linear',
                                                    position: 'left',
                                                    ticks: {
                                                        callback: function(value, index, values) {
                                                            return '  ' + value + ' °C' + '  ';;
                                                        },
                                                        fontColor: '#000000',
                                                        // fontSize: 20,
                                                        max: 30,
                                                        min: -4
                                                    }
                                                    
                                                }, {
                                                    scaleLabel: {
                                                        display: true,
                                                        labelString: '<?php echo _("humidity") ?> <?php echo _(" - φ") ?>',
                                                        // fontSize: 20,
                                                        fontColor: '#000000'
                                                    },
                                                    id: 'humidity',
                                                    type: 'linear',
                                                    display: true,
                                                    position: 'right',
                                                    ticks: {
                                                        callback: function(value, index, values) {
                                                            return ' ' + value + ' %' + '    ';
                                                        },
                                                        fontColor: '#000000',
                                                        // fontSize: 20,
                                                        max: <?php 
                                                            $max_value_humidiy = intval(max($humidity_dataset) + (max($humidity_dataset) / 100 * 1))+10;
                                                            print min (100,max (10,$max_value_humidiy));
                                                            ?>,
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
                                                fill: false,
                                                spanGaps: true
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
                                                fill: false,
                                                spanGaps: true
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
                                                            if (Math.round(value) === value)
                                                            return value + ' gr' + ' ';
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
                                                            $scale1_dataset_max = array_filter($scale1_dataset);
                                                            if (empty($scale1_dataset_max)) {
                                                                    $scale1_dataset_max[] = Null;
                                                            }
                                                            $max_scale1 = max($scale1_dataset_max);
                                                            $max_value_scale1 = intval($max_scale1 + abs($max_scale1) / 100 * 5) + 1;
                                                            print $max_value_scale1;
                                                            ?>,
                                                            min: <?php 
                                                            $scale1_dataset_min = array_filter($scale1_dataset);
                                                            if (empty($scale1_dataset_min)) {
                                                                    $scale1_dataset_min[] = Null;
                                                            }
                                                            $min_scale1 = min($scale1_dataset_min);
                                                            $min_value_scale1 = intval($min_scale1 - abs($min_scale1) / 100 * 5) - 1;
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
                                                            if (Math.round(value) === value)
                                                            return ' ' + value + ' gr';
                                                        },
                                                        fontColor: '#000000',
                                                        // fontSize: 20,
                                                        //max: 25000,
                                                        beginAtZero: true,
                                                        maxTicksLimit: 10,
                                                        max: <?php 
                                                            $scale2_dataset_max = array_filter($scale2_dataset);
                                                            if (empty($scale2_dataset_max)) {
                                                                    $scale2_dataset_max[] = Null;
                                                            }
                                                            $max_scale2 = max($scale2_dataset_max);
                                                            $max_value_scale2 = intval($max_scale2 + abs($max_scale2) / 100 * 5) + 1;
                                                            print $max_value_scale2;
                                                            ?>,
                                                            min: <?php 
                                                            $scale2_dataset_min = array_filter($scale2_dataset);
                                                            if (empty($scale2_dataset_min)) {
                                                                    $scale2_dataset_min[] = Null;
                                                            }
                                                            $min_scale2 = min($scale2_dataset_min);
                                                            $min_value_scale2 = intval($min_scale2 - abs($min_scale2) / 100 * 5) - 1;
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
                                
                                <?php
                                 echo "<script src='js/ajax.js'></script>";
                                ?> 
                                
                                <!----------------------------------------------------------------------------------------Betriebsart-->
                                <h2 class="art-postheader"><?php echo _('statusboard'); ?></h2>
                                <div class="hg_container">
                                    <h2><?php echo _('general'); ?></h2>
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td width="100px"></td>
                                            <td width="100px"></td>
                                            <td class="text_left"></td>
                                            <td width="100px"><td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <?php 
                                                    // Prüft, ob Prozess RSS läuft
                                                    $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
                                                    if ($grepmain == 0){
                                                        echo '<td><img src="images/icons/operatingmode_fail_42x42.png" alt="" style="padding: 10px;"></td>';
                                                        echo '<td><img src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;"></td>';
                                                    }
                                                    elseif ($grepmain != 0 and $status_piager == 0){
                                                        echo '<td><img src="images/icons/operatingmode_42x42.png" alt="" style="padding: 10px;"></td>';
                                                        echo '<td><img src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;"></td>';
                                                    }
                                                    elseif ($grepmain != 0 and $status_piager == 1) {
                                                        echo '<td><img src="images/icons/operating_42x42.gif" alt="" style="padding: 10px;"></td>';
                                                        echo '<td><img src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;"></td>';
                                                    }
                                                ?>
                                            <td class="text_left">
                                                <?php echo '<b>'.strtoupper(_('operating mode')); ?>
                                            </td>
                                            <?php 
                                                    // Prüft, ob Prozess spannung vorhanden ist
                                                    $read_gpio_voltage = shell_exec('sudo /var/sudowebscript.sh read_gpio_voltage');
                                                    if ($read_gpio_voltage == 1){
                                                        echo '<td>';
                                                        echo '<img src="images/icons/5v_42x42.png" alt="" style="padding-top: 10px;">';
                                                        echo '</td>';
                                                        echo '<td style="text-align: left; ">' . _('powersuply ok') . '</td>';
                                                    }
                                                    else {
                                                        echo '<td>';
                                                        echo '<img src="images/icons/5v_fail_42x42.png" alt="" style="padding-top: 10px;">';
                                                        echo '</td>';
                                                        echo '<td style="text-align: left; color: red;">' . _('no powersuply! batterymode') . '</td>';
                                                    }
                                                ?>
                                                <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>   
                                            <td></td>
                                            <td class="text_left_top">
                                                <?php
                                                if ($grepmain == 0){
                                                    echo strtoupper(("see settings"));
                                                }
                                                elseif ($grepmain != 0 and $status_piager == 0){
                                                    echo strtoupper(("off"));
                                                }
                                                elseif($grepmain != 0 and $status_piager == 1){
                                                    echo $modus_name;
                                                }
                                                ?>
                                            </td>
                                            <?php 
                                                // Prüft, ob Batteriespannung vorhanden ist
                                                $read_gpio_battery = shell_exec('sudo /var/sudowebscript.sh read_gpio_battery');
                                                if ($read_gpio_battery == 1){
                                                    echo '<td>';
                                                    echo '<img src="images/icons/battery_42x42.png" alt="" style="padding-top: 10px;">';
                                                    echo '</td>';
                                                    echo '<td style="text-align: left; ">' . _('battery voltage ok') . '</td>';
                                                }
                                                else {
                                                    echo '<td>';
                                                    echo '<img src="images/icons/battery_fail_42x42.png" alt="" style="padding-top: 10px;">';
                                                    echo '</td>';
                                                    echo '<td style="text-align: left; color: red;">' . _('battery voltage low !!') . '</td>';
                                                }
                                            ?>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td height="20px"></td>
                                            <td height="20px"></td>
                                            <td height="20px"></td>
                                            <td height="20px"></td>
                                            <td height="20px"></td>
                                            <td height="20px"></td>
                                        </tr>
                                        <tr>
                                            <?php 
                                                // Prüft, ob Prozess Reifetab läuft
                                                $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
                                                if ($grepagingtable == 0){
                                                    echo '<td><img src="images/icons/agingtable_42x42.png" alt="" style="padding: 10px;"></td>';
                                                    echo '<td><img src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;"></td>';
                                                }
                                                else {
                                                    echo '<td><img src="images/icons/agingtable_42x42.gif" alt="" style="padding: 10px;"></td>';
                                                    echo '<td><img src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;"></td>';
                                                }
                                            ?>
                                            <td class="text_left"><?php echo '<b>'.strtoupper(_('agingtable')).':' ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="text_left_top"><?php echo $maturity_type;?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td width="100px"></td>
                                            <td width="100px"></td>
                                            <td class="text_left"></td>
                                            <td width="100px"><td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <img src="images/icons/switch_42x42.png" alt="" style="padding-top: 10px;">
                                            </td>
                                                <?php 
                                                    // Prüft, ob Prozess spannung vorhanden ist
                                                    $read_gpio_digital_switch = shell_exec('sudo /var/sudowebscript.sh read_gpio_digital_switch');
                                                    if ($read_gpio_digital_switch == 0){
                                                        echo '<td>';
                                                        echo '<img src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;">';
                                                        echo '</td>';
                                                        echo '<td style="text-align: left; ">' . _('Switch is on') . '</td>';
                                                    }
                                                    else {
                                                        echo '<td>';
                                                        echo '<img src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;">';
                                                        echo '</td>';
                                                        echo '<td style="text-align: left;">' . _('Switch is off') . '</td>';
                                                    }
                                                ?>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <hr>
                                    <h2><?php echo _('temperatures'); ?></h2>
                                    <br>
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td  width="100px"><b><?php echo strtoupper(_('type')); ?></b></td>
                                            <td  width="100px"><b><?php echo strtoupper(_('status')); ?></b></td>
                                            <td class="text_left">&nbsp;</td>
                                            <td  width="100px"><b><?php echo strtoupper(_('actual')); ?></b></td>
                                            <td  width="100px"><b><?php echo strtoupper(_('target')); ?></b></td>
                                            <td  width="100px"><b><?php echo strtoupper(_('on')); ?></b></td>
                                            <td  width="100px"><b><?php echo strtoupper(_('off')); ?></b></td>
                                        </tr>
                                        <tr>
                                            <?php 
                                                if ($modus==0 || $modus==1){
                                                    echo '<td><img src="images/icons/cooling_42x42.png" alt=""></td>
                                                        <td><img src="'.$cooler_on_off_png.'" title="PIN_COOL 4[7] -> IN 1 (PIN2)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('cooler'));
                                                    echo '</td>
                                                        <td>'.$sensor_temperature.' °C</td>
                                                        <td>'.$setpoint_temperature.' °C</td>
                                                        <td>'.($setpoint_temperature + $switch_on_cooling_compressor).' °C</td>
                                                        <td>'.($setpoint_temperature + $switch_off_cooling_compressor).' °C</td>';
                                                }
                                                if ($modus==2){
                                                    echo '<td><img src="images/icons/heating_42x42.png" alt=""></td>
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
                                                    echo '<td><img src="images/icons/cooling_42x42.png" alt=""></td>
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
                                                    echo '<td ><img src="images/icons/humidification_42x42.png" alt=""></td>
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
                                                    echo '<td ><img src="images/icons/humidification_42x42.png" alt=""></td>
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
                                    <h2><?php echo _('timer'); ?></h2>
                                    <br>
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td width="100px"><b><?php echo strtoupper(_('type')); ?></b></td>
                                            <td width="100px"><b><?php echo strtoupper(_('status')); ?></b></td>
                                            <td class="text_left">&nbsp;</td>
                                            <td width="100px"></td>
                                            <td width="100px"><b><?php echo strtoupper(_('period')); ?></b></td>
                                            <td width="100px"><b><?php echo strtoupper(_('duration')); ?></b></td>
                                        </tr>
                                        <tr>
                                            <td><img <?php if ($circulation_air_duration == 0) {echo 'class="transpng"';} ?> src="images/icons/circulate_42x42.png" alt=""></td>
                                            <td><img src="<?php echo $circulating_on_off_png ;?>" title="PIN_FAN 24[18] -> IN 5 (PIN 6)"></td>
                                            <td class="text_left">
                                            <?php
                                                echo strtoupper(_('circulating air'));
                                                if ($circulation_air_duration > 0 && $circulation_air_period >0) {echo ', '.strtoupper(_('timer on'));}
                                                elseif ($circulation_air_period == 0) {echo  ' '. strtoupper(_('always on'));}
                                                elseif ($circulation_air_duration == 0) {echo ', '. strtoupper(_('timer off'));}
                                            ?></td>
                                            <td></td>
                                            <td><?php echo $circulation_air_period.' '._('minutes'); ?></td>
                                            <td><?php echo $circulation_air_duration.' '._('minutes'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><img <?php if ($exhaust_air_duration == 0) {echo 'class="transpng"';} ?> src="images/icons/exhausting_42x42.png" alt=""></td>
                                            <td><img src="<?php echo $exhausting_on_off_png ;?>" title="PIN_FAN 23[16] -> IN 4 (PIN 5)"></td>
                                            <td class="text_left">
                                            <?php
                                                echo strtoupper(_('exhausting air'));
                                                if ($exhaust_air_duration > 0 && $exhaust_air_period >0) {echo ', '.strtoupper(_('timer on'));}
                                                elseif ($exhaust_air_period == 0) {echo  ' '. strtoupper(_('always on'));}
                                                elseif ($exhaust_air_duration == 0) {echo ', '. strtoupper(_('timer off'));}
                                            ?></td>
                                            <td></td>
                                            <td><?php echo $exhaust_air_period.' '._('minutes'); ?></td>
                                            <td><?php echo $exhaust_air_duration.' '._('minutes'); ?></td>
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
                                                // UV-LIGHT, TIMER ACTIVE/INACTIVE, only MANUELL OFF
                                                if ($uv_modus == 0) {echo ' '.strtoupper(_('timer off'));}
                                                elseif ($uv_modus == 1) {echo ' '.strtoupper(_('timer on'));}
                                                if ($status_uv_manual == 0){ echo ', '.strtoupper(_('manual switch off'));}
                                                /* 
                                                if ($uv_duration > 0 && $uv_period >0) {echo ', '.strtoupper(_('timer active'));}
                                                elseif ($uv_period == 0) {echo ' '.strtoupper(_('always on'));}
                                                elseif ($uv_duration == 0) {echo ', '.strtoupper(_('timer inactive'));}
                                                */
                                            ?></td>
                                           	<td></td>
                                           	<td><?php echo $uv_period.' '._('minutes'); ?></td>
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
                                                    echo '<img src="'.$light_on_off_png.'">';
                                                }
                                                ?>
                                            </td>
                                            <td class="text_left">
                                            <?php 
                                                echo strtoupper(_('light'));
                                                //LIGHT, TIMER ACTIVE/INACTIVE, only MANUELL ON
                                                if ($light_modus == 0) {echo ' '.strtoupper(_('timer off'));}
                                                elseif ($light_modus == 1) {echo ' '.strtoupper(_('timer on'));}
                                                if ($status_light_manual == 1){ echo ', '.strtoupper(_('manual switch on'));}
                                                /*
                                                if ($light_duration > 0 && $light_period >0) {echo ', '.strtoupper(_('timer on'));}
                                                elseif ($light_period == 0) {echo ' '.strtoupper(_('always on'));}
                                                elseif ($light_duration == 0) {echo ', '.strtoupper(_('timer off'));}
                                                */
                                            
                                                ?></td>
                                                <td></td>
                                                <td><?php echo $light_period.' '._('minutes'); ?></td>
                                                <td><?php echo $light_duration.' '._('minutes'); ?></td>
                                            
                                        </tr>
                                    </table>
                                    <hr>
                                    <h2><?php echo _('scales'); ?></h2>
                                    <br>
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td width="100px"><b><?php echo strtoupper(_('type')); ?></b></td>
                                            <td width="100px"><b><?php echo strtoupper(_('status')); ?></b></td>
                                            <td></td>
                                            <td width="100px"></td>
                                            <td width="100px"></td>
                                            <td width="100px"></td>
                                        </tr>
                                        <tr>
                                            <?php
                                                $grepscale = shell_exec('sudo /var/sudowebscript.sh grepscale');
                                                if ($grepscale == 0){
                                                    echo '<td><img src="images/icons/scale_fail_42x42.png" alt=""></td>
                                                            <td><img src="images/icons/status_off_20x20.png" title=""></td>
                                                            <td class="text_left">';
                                                    echo strtoupper(_('see settings'));
                                                    echo '</td>';
                                                }
                                                elseif ($grepscale != 0 and $status_scale1 == 0){
                                                    echo '<td><img src="images/icons/scale_42x42.png" alt=""></td>
                                                            <td><img src="images/icons/status_off_20x20.png" title=""></td>
                                                            <td class="text_left">';
                                                    echo strtoupper(_('scale1'));
                                                    echo '</td>';
                                                }
                                                elseif ($grepscale != 0 and $status_scale1 == 1) {
                                                    echo '<td><img src="images/icons/scale_42x42.gif" alt=""></td>
                                                            <td><img src="images/icons/status_on_20x20.png" title=""></td>
                                                            <td  class="text_left">';
                                                    echo strtoupper(_('scale1'));
                                                    echo '</td>';
                                                }
                                            ?>
                                            <td></td>
                                            <td></td>
                                            <td></td>
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
                                                            <td><img src="images/icons/status_off_20x20.png" title=></td>
                                                            <td  class="text_left">';
                                                    echo strtoupper(_('scale2'));
                                                    echo '</td>';
                                                }
                                                elseif ($grepscale != 0 and $status_scale2 == 1) {
                                                  echo '<td><img src="images/icons/scale_42x42.gif" alt=""></td>
                                                            <td><img src="images/icons/status_on_20x20.png" title=></td>
                                                            <td  class="text_left">';
                                                    echo strtoupper(_('scale2'));
                                                    echo '</td>';
                                                }
                                            ?>
                                            <td></td>
                                            <td></td>
                                            <td></td>
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
