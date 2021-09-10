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
								      include 'modules/write_customtime_db.php';                 //speichert die individuelle Zeit für die Diagramme
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
                                            <td width="33%">
                                                <img src="images/icons/temperature.png" alt="" style="padding-top: 10px;">
                                            </td>
                                            <td width="33%">
                                                <img src="images/icons/humidity.png" alt="" style="padding-top: 10px;">
                                            </td>
                                            <td width="33%">
                                                <img src="images/icons/dew_point.png" alt="" style="padding-top: 10px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="33%" id="json_temperature_main" style="text-align: center; font-size: 24px; text-shadow:0 0 5px #ff0000;"></td>
                                            <td width="33%" id="json_humidity_main" style="text-align: center; font-size: 24px; text-shadow:0 0 5px #0066FF;"></td>
                                            <td  width="33%" id="json_dewpoint_main" style="text-align: center; font-size: 24px; text-shadow:0 0 5px #00cc66;"></td>
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
                                                            <td width="33%">
                                                                <img src="images/icons/temperature_extern_42x42.png" alt="" style="padding-top: 10px;">
                                                            </td>
                                                            <td width="33%">
                                                                <img src="images/icons/humidity_extern_42x42.png" alt="" style="padding-top: 10px;">
                                                            </td>
                                                            <td width="33%">
                                                                <img src="images/icons/dew_point_extern_42x42.png" alt="" style="padding-top: 10px;">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="33%" id="json_temperature_extern" align="center" style="text-align: center; font-size: 20px;"></td>
                                                            <td width="33%" id="json_humidity_extern" align="center" style="text-align: center; font-size: 20px;"></td>
                                                            <td width="33%" id="json_dewpoint_extern" align="center" style="text-align: center; font-size: 20px;"></td>
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
                                                <?php
                                                $meatsensortype = get_table_value($config_settings_table, $meat4_sensortype_key);
                                                $row = get_meatsensor_table_row( $meatsensortype );
                                                if (strncmp($row['name'], 'LEM', 3) === 0)
                                                {
                                                    echo '<img src="images/icons/voltage_42x42.png" alt="">';
                                                }
                                                else {
                                                    echo '<img src="images/icons/temperature_42x42.png" alt="" >'.'&thetasym; 4';
                                                }
                                                ?>
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
                                <hr>
                                <!------------------------------ ----------------------------------------------------------T/rLF Diagramm-->
                               
                                <h2 class="art-postheader"><?php echo _('diagrams'); ?></h2>
                                <div class="hg_container" style="margin-bottom: 20px; margin-top: 20px;">
                                    <table style="width: 100%;">

                                    <?php
                                      //  if ($diagram_mode_translated == 'custom'){
                                            
                                            $duration = get_table_value($config_settings_table, $customtime_for_diagrams_key); 
                                            $years = floor ($duration / 31557600);
                                            $duration = $duration - $years * 31557600;
                                            $months = floor($duration / 2628000);
                                            $duration = $duration - $months * 2628000;
                                            $days = floor($duration / 86400);
                                            $duration = $duration - $days * 86400;
                                            $hours = floor($duration / 3600);
                                            $duration = $duration - $hours * 3600;
                                            $minutes = floor($duration / 60);
                                            $duration = $duration - $minutes * 60;
                                            $seconds = floor($duration / 1);             
                                            
                                           // echo '<hr>';
                                            echo '<form method="post" name="change_customtime">';
                                                echo '<table style="width: 100%;">';
                                                    echo '<tr>';
                                                        /* echo '<td>' . _('years') . '</td>'; */
                                                        echo '<td>' . _('months') . '</td>';
                                                        echo '<td>' . _('days') . '</td>';
                                                        echo '<td>' . _('hours') . '</td>';
                                                        echo '<td>' . _('minutes') .'</td>';
                                                    echo '</tr>';
                                                    echo '<tr>';
                                                        echo '<input name="years" type="hidden" value = ' . $years . '>';
                                                        /* echo '<td><input name="years" type="number" step="1" style="width: 90%; text-align: right;" value = ' . $years . '></td>'; */
                                                        echo '<td><input name="months" type="number" min="0" max="12.0" step="1" style="width: 90%; text-align: right;" value = ' . $months . '></td>';
                                                        echo '<td><input name="days" type="number" min="0" max="31.0" step="1" style="width: 90%; text-align: right;" value = ' . $days . '></td>';
                                                        echo '<td><input name="hours" type="number"  min="0" max="24.0" step="1" style="width: 90%; text-align: right;" value = ' . $hours . '></td>';
                                                        echo '<td><input name="minutes" type="number" min="0" max="60.0" step="1" style="width: 90%; text-align: right;" value = ' . $minutes . '></td>';
                                                    echo '</tr>';
                                                echo '</table>';
                                                echo '<button class="art-button" name="change_customtime" value="change_customtime" onclick="return confirm(\'' . _('change customtime?') . '\');">' . _('change') . '</button>';
                                            echo '</form>';
                                       // }
                                    ?>
                                </div>

							   <?php
                                    $diagram_mode = 'custom';
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
                                                <?php echo $all_sensors_timestamps_axis; ?>,
                                            datasets: [{
                                                label: '<?php echo _("temperature") . ' int.' ?>',
                                                yAxisID: 'temperature',
                                                data: <?php echo json_encode($temperature_dataset);?>,
                                                backgroundColor: '#FF4040',
                                                borderColor: '#FF4040',
                                                borderWidth: 2,
                                                <?php
                                                    if ($customtime <= 3600) {
                                                        print 'pointRadius: 1, pointHitRadius: 5,';
                                                    }
                                                    else {
                                                        print 'pointRadius: 0, pointHitRadius: 5,';
                                                    }
                                                ?>
                                                pointStyle:'rect',
                                                cubicInterpolationMode: 'monotone',
                                                fill: false
                                            },
                                            {
                                                label: '<?php echo _("temperature") . ' avg.' ?>',
                                                yAxisID: 'temperature',
                                                //data: <?php echo json_encode($extern_temperature_dataset); ?>,
												data: <?php echo json_encode($temperature_avg_dataset); ?>,
                                                backgroundColor: '#8A0808',
                                                borderColor: '#8A0808',
                                                borderWidth: 2,
                                                <?php
                                                    if ($customtime <= 3600) {
                                                        print 'pointRadius: 1, pointHitRadius: 5,';
                                                    }
                                                    else {
                                                        print 'pointRadius: 0, pointHitRadius: 5,';
                                                    }
                                                ?>
                                                pointStyle:'rect',
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
                                                <?php
                                                    if ($customtime <= 3600) {
                                                        print 'pointRadius: 1, pointHitRadius: 5,';
                                                    }
                                                    else {
                                                        print 'pointRadius: 0, pointHitRadius: 5,';
                                                    }
                                                ?>
                                                pointStyle:'rect',
                                                cubicInterpolationMode: 'monotone',
                                                fill: false
                                            },                                            
                                            {
                                                label: '<?php echo _("humidity") . ' int.' ?>',
                                                yAxisID: 'humidity',
                                                data: <?php echo json_encode($humidity_dataset); ?>,
                                                backgroundColor: '#59A9C4',
                                                borderColor: '#59A9C4',
                                                borderWidth: 2,
                                                <?php
                                                    if ($customtime <= 3600) {
                                                        print 'pointRadius: 1, pointHitRadius: 5,';
                                                    }
                                                    else {
                                                        print 'pointRadius: 0, pointHitRadius: 5,';
                                                    }
                                                ?>
                                                pointStyle:'rect',
                                                cubicInterpolationMode: 'monotone',
                                                fill: false
                                            },
                                            {
                                                label: '<?php echo _("humidity") . ' avg.' ?>',
                                                yAxisID: 'humidity',
                                                //data: <?php echo json_encode($extern_humidity_dataset); ?>,
												data: <?php echo json_encode($humidity_avg_dataset); ?>,
                                                backgroundColor: '#08298A',
                                                borderColor: '#08298A',
                                                borderWidth: 2,
                                                <?php
                                                    if ($customtime <= 3600) {
                                                        print 'pointRadius: 1, pointHitRadius: 5,';
                                                    }
                                                    else {
                                                        print 'pointRadius: 0, pointHitRadius: 5,';
                                                    }
                                                ?>
                                                pointStyle:'rect',
                                                cubicInterpolationMode: 'monotone',
                                                fill: false
                                            }]
                                        },
                                        options: {
                                            title: {
                                                display: true,
                                                text: '<?php echo _("temperature") ?> & <?php echo _("humidity") ?>',
                                                fontSize: 24
                                            },
                                            legend: {
                                                labels: {
                                                    usePointStyle: true,
                                                },
                                            },
                                            tooltips: {
                                                mode: 'index',
                                                intersect: false,
                                                callbacks: {
                                                    label: function(tooltipItem, data) {
                                                        if (tooltipItem.datasetIndex === 0) {
                                                            return Number(tooltipItem.yLabel).toFixed(1) + ' °C';
                                                        } else if (tooltipItem.datasetIndex === 1) {
                                                            return Number(tooltipItem.yLabel).toFixed(1) + ' °C';
                                                        } else if (tooltipItem.datasetIndex === 2) {
                                                            return Number(tooltipItem.yLabel).toFixed(1) + ' °C';
                                                        } else if (tooltipItem.datasetIndex === 3) {
                                                            return Number(tooltipItem.yLabel).toFixed(1) + ' %';
                                                        } else if (tooltipItem.datasetIndex === 4) {
                                                            return Number(tooltipItem.yLabel).toFixed(1) + ' %';
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
                                                    ticks: {
                                                        autoSkip: false,
                                                        maxRotation: 0,
                                                        minRotation: 0
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
                                                            return '  ' +  Math.round(value * 10)/10 + ' °C' + '  ';
                                                        },
                                                        fontColor: '#000000',
                                                        beginAtZero: false,
                                                        suggestedMax: 15,
                                                        suggestedMin: 10															
                                                        //max: 30,
                                                        //min: -2
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
                                                    labelString: '<?php echo _("humidity") ?>',
                                                    ticks: {
                                                        callback: function(value, index, values) {
                                                            return '  ' + Math.round(value * 10)/10 + ' %' + '  ';
                                                        },
                                                        fontColor: '#000000',
                                                        beginAtZero: true,
                                                        //suggestedMax: 100,
                                                        //suggestedMin: 30
                                                        //max: 100,
                                                        //min: 0
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
                                            labels: <?php echo $all_scales_timestamps_axis; ?>,
                                            datasets: [{
                                                label: '<?php echo _("scale") ?> 1',
                                                yAxisID: 'scale1',
                                                data: <?php echo json_encode($scale1_dataset);?>,
                                                backgroundColor: '#AEC645',
                                                borderColor: '#AEC645',
                                                borderWidth: 2,
                                                <?php
                                                    if ($customtime <= 3600) {
                                                        print 'pointRadius: 2, pointHitRadius: 5,';
                                                    }
                                                    else {
                                                        print 'pointRadius: 0, pointHitRadius: 5,';
                                                    }
                                                ?>
                                                pointStyle:'rect',
                                                cubicInterpolationMode: 'monotone',
                                                fill: false,
                                                spanGaps: true
                                            },
                                            {
                                                label: '<?php echo _("scale") ?> 2',
                                                yAxisID: 'scale2',
                                                data: <?php echo json_encode($scale2_dataset);?>,
                                                backgroundColor: '#BF9543',
                                                borderColor: '#BF9543',
                                                borderWidth: 2,
                                                <?php
                                                    if ($customtime <= 3600) {
                                                        print 'pointRadius: 2, pointHitRadius: 5,';
                                                    }
                                                    else {
                                                        print 'pointRadius: 0, pointHitRadius: 5,';
                                                    }
                                                ?>
                                                pointStyle:'rect',
                                                cubicInterpolationMode: 'monotone',
                                                fill: false,
                                                spanGaps: true
                                            }]
                                        },
                                        options: {
                                            title: {
                                                display: true,
                                                text: '<?php echo _("scale") ?> 1 & 2',
                                                fontSize: 24
                                            },
                                            legend: {
                                                labels: {
                                                    usePointStyle: true,
                                                },
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
                                                    ticks: {
                                                        autoSkip: false,
                                                        maxRotation: 0,
                                                        minRotation: 0
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
                                                            val = Math.round(value * 10)/10;
                                                            return val + ' gr' + ' ';
                                                        },
                                                        fontColor: '#000000',
                                                        beginAtZero: true,
                                                        maxTicksLimit: 10,
                                                        suggestedMax: 100
                                                        //suggestedMin: 0
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
                                                            val = Math.round(value * 10)/10;
                                                            return ' ' + val + ' gr';
                                                        },
                                                        fontColor: '#000000',
                                                        beginAtZero: true,
                                                        maxTicksLimit: 10,
                                                        suggestedMax: 100
                                                        //suggestedMin: 0
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
                                <hr>
                                <?php
                                 echo "<script src='js/ajax.js'></script>";
                                ?> 
                                
                                 <!----------------------------------------------------------------------------------------Reifetabelle-->
                                <?php 
                                    if ($grepagingtable != NULL){
                                        $current_period = get_table_value($current_values_table, $agingtable_period_key);
                                        $current_period_day = get_table_value($current_values_table, $agingtable_period_day_key);
                                        echo '
                                        <hr>
                                        <h2 class="art-postheader">' . _('agingtable') .'</h2>
                                        <div class="hg_container">
                                        <table style="width: 100%" class="switching_state miniature_writing">
                                            <tr>
                                                <td width="75px">' . _('phase') . '</td><td align="left">' . (intval($current_period) + 1) . '</td>
                                            </tr>
                                            <tr>
                                                <td width="75px">' . _('day') . '</td><td align="left">' . (intval($current_period_day)) . '</td>
                                            </tr>
                                        </table>
                                        <table id="show_agingtable" class="show_agingtable">
                                            <tr style="background-color: #F0F5FB; border-bottom: 1px solid #000033">
                                                <td class="show_agingcell"><div class="tooltip">' . _('phase') . '<span class="tooltiptext">' .  _("phase") . '></span></div></td>
                                                <td class="show_agingcell"><div class="tooltip">' . _('modus') . '<span class="tooltiptext">' .  _("aging-modus") . '></span></div></td>
                                                <td class="show_agingcell"><div class="tooltip">&phi;<span class="tooltiptext">' . _('target humidity in %') . '</span></div></td>
                                                <td class="show_agingcell"><div class="tooltip">°C<span class="tooltiptext">' .  _('target temperature in °C') . '</span></div></td>
                                                <td class="show_agingcell"><div class="tooltip">' . _('timer circulate d') . '<span class="tooltiptext">' . _('timer of the circulation air duration in minutes') . '</span></div></td>
                                                <td class="show_agingcell"><div class="tooltip">' .  _('timer circulate p') . '<span class="tooltiptext">' . _('timer of the circulation air period in minutes') . '</span></div></td>
                                                <td class="show_agingcell"><div class="tooltip">' .  _('timer exhaust d') . '<span class="tooltiptext">' . _('timer of the exhausting air duration in minutes') . '</span></div></td>
                                                <td class="show_agingcell"><div class="tooltip">' . _('timer exhaust p') . '<span class="tooltiptext">' . _('timer of the exhausting air period in minutes') . '</span></div></td>
                                                <td class="show_agingcell"><div class="tooltip">' .  _('days')  . '<span class="tooltiptext">' . _('duration of hanging phase in days') . '</span></div></td>
                                            </tr>';
                                                // Gewählte Agingtable aus DB auslesen und als Tabelle beschreiben
                                                $index_row = 0;
                                                $agingtable_rows = get_agingtable_dataset($desired_maturity);
                                                if ($agingtable_rows != false){
                                                    $firstrow = $agingtable_rows[0];
                                                    $agingtable_comment = $firstrow[$agingtable_comment_field];
                                                    if (!isset($agingtable_comment)){
                                                        $agingtable_comment = _('no comment');
                                                    }
                                                    
                                                    
                                                    //$current_period_0 = $current_period - 1;
                                                    try {
                                                        $number_rows = count($agingtable_rows);
                                                        while ($index_row < $number_rows) {
                                                            $dataset = $agingtable_rows[$index_row];
                                                            // $num = count($dataset);
                                                            if (!empty($dataset[$agingtable_modus_field])){
                                                                $data_modus = $dataset[$agingtable_modus_field];
                                                            } else {$data_modus = '..';}
                                                            if (!empty($dataset[$agingtable_setpoint_humidity_field])){
                                                                $data_setpoint_humidity = $dataset[$agingtable_setpoint_humidity_field];
                                                            } else {$data_setpoint_humidity = '..';}
                                                            if (!empty($dataset[$agingtable_setpoint_temperature_field])){
                                                                $data_setpoint_temperature = $dataset[$agingtable_setpoint_temperature_field];
                                                            } else {$data_setpoint_temperature = '..';}
                                                            if (!empty($dataset[$agingtable_circulation_air_duration_field])){
                                                                $data_circulation_air_duration = $dataset[$agingtable_circulation_air_duration_field]/60;
                                                            } else {$data_circulation_air_duration = '..';}
                                                            if (!empty($dataset[$agingtable_circulation_air_period_field])){
                                                                $data_circulation_air_period = $dataset[$agingtable_circulation_air_period_field]/60;
                                                            } else {$data_circulation_air_period = '..';}
                                                            if (!empty($dataset[$agingtable_exhaust_air_duration_field])){
                                                                $data_exhaust_air_duration = $dataset[$agingtable_exhaust_air_duration_field]/60;
                                                            } else {$data_exhaust_air_duration = '..';}
                                                            if (!empty($dataset[$agingtable_exhaust_air_period_field])){
                                                                $data_exhaust_air_period = $dataset[$agingtable_exhaust_air_period_field]/60;
                                                            } else {$data_exhaust_air_period = '..';}
                                                            if (!empty($dataset[$agingtable_days_field])){
                                                                $data_days = $dataset[$agingtable_days_field];
                                                            } else {$data_days = '..';}

                                                            if ($current_period == $index_row AND $grepagingtable != NULL){
                                                                echo '<tr bgcolor=#D19600 >';
                                                                echo '<td>'. ($current_period +1) .'</td>';
                                                                echo '<td>'. $data_modus .'</td>';
                                                                echo '<td>'. $data_setpoint_humidity .'</td>';
                                                                echo '<td>'. $data_setpoint_temperature .'</td>';
                                                                echo '<td>'. $data_circulation_air_duration .'</td>';
                                                                echo '<td>'. $data_circulation_air_period .'</td>';
                                                                echo '<td>'. $data_exhaust_air_duration .'</td>';
                                                                echo '<td>'. $data_exhaust_air_period .'</td>';
                                                                echo '<td>'. $data_days .'</td>';
                                                                echo '</tr>';
                                                            }
                                                            $index_row++;
                                                        } 
                                                    }
                                                    catch (Exception $e) {
                                                    }
                                                }
                                        echo '</table>
                                        <table style="width: 100%" class="switching_state miniature_writing">
                                            <tr>';
                                                $agingtable_comment_with_carriage_return = nl2br($agingtable_comment);
                                                echo  '<td width="150px" align="right">' . _('comment:') . '</td><td align="left">' . $agingtable_comment_with_carriage_return . '</td>';
                                            echo '</tr>
                                        </table>
                                        </div>';
                                    }
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
                                                    //$grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
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
                                                    //$read_gpio_voltage = shell_exec('sudo /var/sudowebscript.sh read_gpio_voltage');
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
                                                    echo strtoupper(_('see settings'));
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
                                                //$read_gpio_battery = shell_exec('sudo /var/sudowebscript.sh read_gpio_battery');
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
                                                //$grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
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
                                                    //$read_gpio_digital_switch = shell_exec('sudo /var/sudowebscript.sh read_gpio_digital_switch');
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
                                                        <td>'.$sensor_humidity.' %</td>
                                                        <td>'.$setpoint_humidity.' %</td>
                                                        <td>'.($setpoint_humidity - $switch_on_humidifier).' %</td>
                                                        <td>'.($setpoint_humidity - $switch_off_humidifier).' %</td>';
                                                }

                                                if ($modus==4){
                                                    echo '<td ><img src="images/icons/humidification_42x42.png" alt=""></td>
                                                        <td><img src='.$humidifier_on_off_png.' title="PIN_HUM 18[12] -> IN 3 (PIN 4)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('humidification'));
                                                    echo '<td>'.$sensor_humidity.' %</td>
                                                        <td>'.$setpoint_humidity.' %</td>
                                                        <td>'.($setpoint_humidity - $switch_on_humidifier).' %</td>
                                                        <td>'.($setpoint_humidity - $switch_off_humidifier).' %</td></tr>';
                                                    echo '<tr> <td ><img src="images/icons/exhausting_42x42.png" alt=""></td>
                                                        <td><img src='.$exhausting_on_off_png.' title="PIN_EXH 23[16] -> IN 5 (PIN 5)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('exhausting'));
                                                    echo '</td>
                                                        <td>'.$sensor_humidity.' %</td>
                                                        <td>'.$setpoint_humidity.' %</td>
                                                        <td>'.((($setpoint_humidity + $switch_on_humidifier) <= 100) ? ($setpoint_humidity + $switch_on_humidifier) : 100).' %</td>
                                                        <td>'.((($setpoint_humidity + $switch_off_humidifier) <= 100) ? ($setpoint_humidity + $switch_off_humidifier) : 100).' %</td></tr>';
                                                    echo '<tr> <td ><img src="images/icons/dehumidification_42x42.png" alt=""></td>
                                                        <td><img src='.$dehumidifier_on_off_png.' title="PIN_DEH 7[26] -> IN 8 (PIN 9)"></td>
                                                        <td class="text_left">';
                                                    echo strtoupper(_('dehumidification'));
                                                    echo '</td>
                                                        <td>'.$sensor_humidity.' %</td>
                                                        <td>'.$setpoint_humidity.' %</td>
                                                        <td>'.((($setpoint_humidity + $switch_on_humidifier) <= 100) ? ($setpoint_humidity + $switch_on_humidifier) : 100).' %</td>
                                                        <td>'.((($setpoint_humidity + $switch_off_humidifier) <= 100) ? ($setpoint_humidity + $switch_off_humidifier) : 100).' %</td></tr>';
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
                                                //$grepscale = shell_exec('sudo /var/sudowebscript.sh grepscale');
                                                if ($scale1_thread_alive == 0){
                                                    echo '<td><img src="images/icons/scale_fail_42x42.gif" alt=""></td>
                                                            <td><img src="images/icons/status_off_20x20.png" title=""></td>
                                                            <td class="text_left">';
                                                    echo strtoupper(_('see settings'));
                                                    echo '</td>';
                                                }
                                                elseif ($status_scale1 == 0){
                                                    echo '<td><img src="images/icons/scale_42x42.png" alt=""></td>
                                                            <td><img src="images/icons/status_off_20x20.png" title=""></td>
                                                            <td class="text_left">';
                                                    echo strtoupper(_('scale1'));
                                                    echo '</td>';
                                                }
                                                elseif ($status_scale1 == 1) {
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
                                                //$grepscale = shell_exec('sudo /var/sudowebscript.sh grepscale');
                                                if ($scale2_thread_alive == 0){
                                                    echo '<td><img src="images/icons/scale_fail_42x42.gif" alt=""></td>
                                                            <td><img src="images/icons/status_off_20x20.png" title=""></td>
                                                            <td class="text_left">';
                                                    echo strtoupper(_('see settings'));
                                                    echo '</td>';
                                                }
                                                elseif ($status_scale2 == 0){
                                                    echo '<td><img src="images/icons/scale_42x42.png" alt=""></td>
                                                            <td><img src="images/icons/status_off_20x20.png" title=></td>
                                                            <td  class="text_left">';
                                                    echo strtoupper(_('scale2'));
                                                    echo '</td>';
                                                }
                                                elseif ($status_scale2 == 1) {
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
<!--        </div> -->
        <?php 
            include 'footer.php';
        ?>
