<?php
                                      include 'header.php';                                       // Template-Kopf und Navigation
                                      include 'modules/database.php';                             // Schnittstelle zur Datenbank
                                      include 'modules/logging.php';                            //liest die Datei fuer das logging ein
                                      include 'modules/names.php';                                // Variablen mit Strings
                                      
                                      include 'modules/read_all_index.php';                     // get all data to setup index.php
                                      // include 'modules/read_settings_db.php';                   // Liest die Einstellungen (Temperaturregelung, Feuchte, Lueftung) und Betriebsart des RSS
                                      // include 'modules/read_config_db.php';                     // Liest die Grundeinstellungen Sensortyp, Hysteresen, GPIO's)
                                      // include 'modules/read_operating_mode_db.php';                  // Liest die Art der Reifesteuerung
                                      // include 'modules/read_gpio.php';                            // Liest den aktuellen Zustand der GPIO-E/A
                                      // include 'modules/read_current_db.php';                    // Liest die gemessenen Werte Temp, Humy, Timestamp
                                      // include 'modules/read_bus.php';                           //liest den bus aus, um externsensor ein oder auszublenden
                                      include 'modules/funclib.php';                            // Funktionsbibliothek
								    //  include 'modules/write_customtime_db.php';                 //speichert die individuelle Zeit für die Diagramme
                                ?>
                                <h2 class="art-postheader"><?php echo _('mainsensors'); ?></h2>

                                <!----------------------------------------------------------------------------------------Anzeige T/rLF-->
                                <div class="hg_container">
                                    <h2><?php echo _('internal'); ?></h2>
                                    <h4><?php echo '(' . $sensorname . ')'; ?></h4>
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td width="33%">
                                                <img src="images/icons/temperature.png" alt="" style="padding-top: 10px;">
                                            </td>
                                            <td width="33%">
                                                <img src="images/icons/humidity.png" alt="" style="padding-top: 10px;">
                                            </td>
                                            <td width="33%">
                                                <img src="images/icons/humidity_abs.png" alt="" style="padding-top: 10px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <?php
                                                if ($status_main == 0 or $status_piager == 0) {
                                                    $temp_main = '-----';
                                                    $hum_main = '-----';
                                                    $hum_abs_main = '-----';
                                                    $temp_ext = '-----';
                                                    $hum_ext = '-----';
                                                    $hum_abs_ext = '-----';
                                                    $temp_ntc1 = '-----';
                                                    $temp_ntc2 = '-----';
                                                    $temp_ntc3 = '-----';
                                                    $temp_ntc4 = '-----';
                                                }
                                                else {
                                                    $data_from_db = get_table_value($current_values_table, $temperature_avg_key);
                                                    if ($data_from_db === null) {
                                                        $temp_main = '-----';
                                                    }
                                                    else {
                                                        $temp_main = number_format(floatval($data_from_db), 1, '.', '');
                                                    }
                                                    $data_from_db = get_table_value($current_values_table, $humidity_avg_key);
                                                    if ($data_from_db === null) {
                                                        $hum_main = '-----';
                                                    }
                                                    else {
                                                        $hum_main = number_format(floatval($data_from_db), 1, '.', '');
                                                    }
                                                    $data_from_db = get_table_value($current_values_table, $humidity_abs_avg_key);
                                                    if ($data_from_db === null) {
                                                        $hum_abs_main = '-----';
                                                    }
                                                    else {
                                                        $hum_abs_main = number_format(floatval($data_from_db), 1, '.', '');
                                                    }
                                                    
                                                    $data_from_db = get_table_value($current_values_table, $sensor_extern_temperature_key);
                                                    $external_temperature = $data_from_db;
                                                    if ($data_from_db === null) {
                                                        $temp_ext = '-----';
                                                    }
                                                    else {
                                                        $temp_ext = number_format(floatval($data_from_db), 1, '.', '');
                                                    }
                                                    $data_from_db = get_table_value($current_values_table, $sensor_extern_humidity_key);
                                                    if ($data_from_db === null) {
                                                        $hum_ext = '-----';
                                                    }
                                                    else {
                                                        $hum_ext = round($data_from_db, 0);
                                                    }
                                                    $data_from_db = get_table_value($current_values_table, $sensor_extern_humidity_abs_key);
                                                    if ($data_from_db === null) {
                                                        $hum_abs_ext = '-----';
                                                    }
                                                    else {
                                                        $hum_abs_ext = number_format(floatval($data_from_db), 1, '.', '');
                                                    }
                                                    $data_from_db = get_table_value($current_values_table, $temperature_meat1_key);
                                                    if ($data_from_db === null) {
                                                        $temp_ntc1 = '-----';
                                                    }
                                                    else {
                                                        $temp_ntc1 = number_format(floatval($data_from_db), 1, '.', '');
                                                    }
                                                    $data_from_db = get_table_value($current_values_table, $temperature_meat2_key);
                                                    if ($data_from_db === null) {
                                                        $temp_ntc2 = '-----';
                                                    }
                                                    else {
                                                        $temp_ntc2 = number_format(floatval($data_from_db), 1, '.', '');
                                                    }
                                                    $data_from_db = get_table_value($current_values_table, $temperature_meat3_key);
                                                    if ($data_from_db === null) {
                                                        $temp_ntc3 = '-----';
                                                    }
                                                    else {
                                                        $temp_ntc3 = number_format(floatval($data_from_db), 1, '.', '');
                                                    }
                                                    
                                                    $data_from_db = get_table_value($current_values_table, $temperature_meat4_key);
                                                    if ($data_from_db === null) {
                                                        $temp_ntc4 = '-----';
                                                    }
                                                    else {
                                                        $meatsensortype = get_table_value($config_settings_table, $meat4_sensortype_key);
                                                        $row = get_meatsensor_table_row( $meatsensortype );
                                                        if (strncmp($row['name'], 'LEM', 3) === 0)
                                                        {
                                                            $temp_ntc4 = number_format(floatval($data_from_db), 2, '.', '');
                                                        }
                                                        else {
                                                            $temp_ntc4 = number_format(floatval($data_from_db), 1, '.', '');
                                                        }
                                                    }
                                                }
                                                
                                                if ($status_main == 0 or $status_scale1 == 0) {
                                                    $weight_scale1 = '-----';
                                                    $weight_loss_scale1 = '-----';
                                                }
                                                else {
                                                    $data_from_db = get_table_value($current_values_table, $scale1_key);
                                                    if ($data_from_db === null) {
                                                        $weight_scale1 = '-----';
                                                        $weight_loss_scale1 = '-----';
                                                    }
                                                    else {
                                                        $weight_scale1 = round($data_from_db, 0);
                                                        $take_off_weight_scale1 = intval(get_table_value($config_settings_table, $take_off_weight_scale1_key));
                                                        if ($take_off_weight_scale1 == 0) {
                                                            $weight_loss_scale1 = '-----';
                                                        }
                                                        else {
                                                            $diff = $take_off_weight_scale1 - $weight_scale1;
                                                            if ($weight_scale1 <= 0 || $diff < 0) {
                                                                $weight_loss_scale1 = '0g (0.0%)';
                                                            }
                                                            else {
                                                                $number = round(($diff / $take_off_weight_scale1 * 100), 1);
                                                                $num_fmt = number_format($number, 1, '.', '');
                                                                $weight_loss_scale1 = $diff . 'g (' . $num_fmt . '%)';
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($status_main == 0 or $status_scale2 == 0) {
                                                    $weight_scale2 = '-----';
                                                    $weight_loss_scale2 = '-----';
                                                }
                                                else {
                                                    $data_from_db = get_table_value($current_values_table, $scale2_key);
                                                    if ($data_from_db === null) {
                                                        $weight_scale2 = '-----';
                                                    }
                                                    else {
                                                        $weight_scale2 = round($data_from_db, 0);
                                                        $take_off_weight_scale2 = intval(get_table_value($config_settings_table, $take_off_weight_scale2_key));
                                                        if ($take_off_weight_scale2 == 0) {
                                                            $weight_loss_scale2 = '-----';
                                                        }
                                                        else {
                                                            $diff = $take_off_weight_scale2 - $weight_scale2;
                                                            if ($weight_scale2 <= 0 || $diff < 0) {
                                                                $weight_loss_scale2 = '0g (0.0%)';
                                                            }
                                                            else {
                                                                $number = round(($diff / $take_off_weight_scale2 * 100), 1);
                                                                $num_fmt = number_format($number, 1, '.', '');
                                                                $weight_loss_scale2 = $diff . 'g (' . $num_fmt . '%)';
                                                            }
                                                        }
                                                    }
                                                }
                                            ?>
                                            <td width="33%" id="json_temperature_main" style="text-align: center; font-size: 24px; text-shadow:0 0 5px #ff0000;"><?php echo $temp_main . ' °C';?></td>
                                            <td width="33%" id="json_humidity_main" style="text-align: center; font-size: 24px; text-shadow:0 0 5px #0066FF;"><?php echo $hum_main . ' %';?></td>
                                            <td width="33%" id="json_hum_abs_main" style="text-align: center; font-size: 24px; text-shadow:0 0 5px #00cc66;"><?php echo $hum_abs_main . ' g/m³';?></td>
                                        </tr>
                                    </table>
                                    <?php
                                        if ($sensorsecondtype == 0){
                                            echo '<table class="switching_state miniature_writing" style="display: none !important;">';
                                        }
                                        else {
                                            echo '<hr><h2>';
                                            echo _('external');
                                            echo '</h2>';
                                            if ($sensorsecondname == 'MiThermometer') {
                                                if ($MiSensor_battery === null) {
                                                    echo '<h4 id="secondsensorname_id">(' . $sensorsecondname . ', battery: ' . '----V)</h4>';
                                                }
                                                else {
                                                    $MiSensor_battery_tmp = number_format(floatval($MiSensor_battery), 2, '.', '');
                                                    echo '<h4 id="secondsensorname_id">(' . $sensorsecondname . ', battery: ' . $MiSensor_battery_tmp . 'V)</h4>';
                                                }
                                            }
                                            else {
                                                echo '<h4 id="secondsensorname_id">(' . $sensorsecondname . ')</h4>';
                                            }
                                            echo '<table class="switching_state miniature_writing">';
                                        }
                                    ?>
                                            <tr>
                                                <td width="33%">
                                                    <img src="images/icons/temperature_extern_42x42.png" alt="" style="padding-top: 10px;">
                                                </td>
                                                <td width="33%">
                                                    <img src="images/icons/humidity_extern_42x42.png" alt="" style="padding-top: 10px;">
                                                </td>
                                                <td width="33%">
                                                    <img src="images/icons/humidity_extern_abs_42x42.png" alt="" style="padding-top: 10px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="33%" id="json_temperature_extern" align="center" style="text-align: center; font-size: 20px;"><?php echo $temp_ext . " °C";?></td>
                                                <td width="33%" id="json_humidity_extern" align="center" style="text-align: center; font-size: 20px;"><?php echo $hum_ext . " %";?></td>
                                                <td width="33%" id="json_hum_abs_extern" align="center" style="text-align: center; font-size: 20px;"><?php echo $hum_abs_ext . " g/m³";?></td>
                                            </tr>
                                        </table>
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
                                            <td id="json_meat_temperature1" style="font-size: 20px;"><?php echo $temp_ntc1 . ' °C';?></td>
                                            <td id="json_meat_temperature2" style="font-size: 20px;"><?php echo $temp_ntc2 . ' °C';?></td>
                                            <td id="json_meat_temperature3" style="font-size: 20px;"><?php echo $temp_ntc3 . ' °C';?></td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td>
                                                <?php
                                                $meatsensortype = get_table_value($config_settings_table, $meat4_sensortype_key);
                                                $row = get_meatsensor_table_row( $meatsensortype );
                                                $sensor4_units = ' °C';
                                                if (strncmp($row['name'], 'LEM', 3) === 0)
                                                {
                                                    echo '<img src="images/icons/voltage_42x42.png" alt="">';
                                                    $sensor4_units = ' A';
                                                }
                                                else {
                                                    echo '<img src="images/icons/temperature_42x42.png" alt="" >'.'&thetasym; 4';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td id="json_meat_temperature4" style="font-size: 20px;"><?php echo $temp_ntc4 . $sensor4_units;?></td>
                                        </tr>
                                    </table>
                                </div>
                            <hr>
                            <!------------------------------ ----------------------------------------------------------Anzeige Scales-->
                            <h2 class="art-postheader"><?php echo _('scales'); ?></h2>
                                <div class="hg_container">
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td width=50%></td>
                                            <td width=50%></td>
                                        </tr>
                                        <tr>
                                            <td><img src="images/icons/scale_42x42.png" alt="">1</td>
                                            <td><img src="images/icons/scale_42x42.png" alt="">2</td>
                                        </tr>
                                        <tr>
                                            <td id="json_scale1" style="font-size: 20px;"><?php echo $weight_scale1 . ' g';?></td>
                                            <td id="json_scale2" style="font-size: 20px;"><?php echo $weight_scale2 . ' g';?></td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <table class="switching_state miniature_writing">
                                        <tr>
                                            <td width=50%><img src="images/icons/scale_d_trend_42x42.png" alt="">1</td>
                                            <td width=50%><img src="images/icons/scale_d_trend_42x42.png" alt="">2</td>
                                        </tr>
                                        <tr>
                                            <td id="json_weight_loss_scale1" style="font-size: 20px;"><?php echo $weight_loss_scale1;?></td>
                                            <td id="json_weight_loss_scale2" style="font-size: 20px;"><?php echo $weight_loss_scale2;?></td>
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
                                            
                                        $duration = intval(get_table_value($config_settings_table, $customtime_for_diagrams_key));

                                        $seconds_per_month = 2678400;    // 31 days per month
                                        $seconds_per_day = 86400;
                                        $seconds_per_hour = 3600;
                                        $seconds_per_minute = 60;
                                        // max input range in customtime settings
                                        $max_customtime = $seconds_per_month * 11 + $seconds_per_day * 30 + $seconds_per_hour * 23 + $seconds_per_minute * 59;
                                        if ($duration > $max_customtime) {
                                            $duration = $max_customtime;
                                        }
                                        $months = intdiv($duration, $seconds_per_month);    //seconds per month
                                        $remainder = $duration % $seconds_per_month;
                                        $days = intdiv($remainder, $seconds_per_day);
                                        $remainder = $remainder % $seconds_per_day;
                                        $hours = intdiv($remainder, $seconds_per_hour);
                                        $remainder = $remainder % $seconds_per_hour;
                                        $minutes = intdiv($remainder, $seconds_per_minute);
                                        
                                           // echo '<hr>';
                                            echo '<form id="queryformid" name="change_customtime">';
                                                echo '<table style="width: 100%;">';
                                                    echo '<tr>';
                                                        // echo '<td>' . _('years') . '</td>';
                                                        echo '<td>' . _('months') . '</td>';
                                                        echo '<td>' . _('days') . '</td>';
                                                        echo '<td>' . _('hours') . '</td>';
                                                        echo '<td>' . _('minutes') .'</td>';
                                                    echo '</tr>';
                                                    echo '<tr>';
                                                        // echo '<input name="years" type="hidden" value = ' . $years . '>';
                                                        // echo '<td><input name="years" type="number" step="1" style="width: 90%; text-align: right;" value = ' . $years . '></td>'; 
                                                        echo '<td><input name="months" type="number" min="0" max="11" step="1" style="width: 90%; text-align: right;" value = ' . $months . '></td>';
                                                        echo '<td><input name="days" type="number" min="0" max="30" step="1" style="width: 90%; text-align: right;" value = ' . $days . '></td>';
                                                        echo '<td><input name="hours" type="number"  min="0" max="23" step="1" style="width: 90%; text-align: right;" value = ' . $hours . '></td>';
                                                        echo '<td><input name="minutes" type="number" min="0" max="59" step="1" style="width: 90%; text-align: right;" value = ' . $minutes . '></td>';
                                                    echo '</tr>';
                                                echo '</table>';
                                                echo '<br>';
                                                echo '<button class="art-button" id="change_customtime_id" name="change_customtime" value="change_customtime">' . _('change') . '</button>';
                                            echo '</form>';
                                       // }
                                    ?>
                                </div>
                                
                                <script>
                                // this is to avoid form refresh after change customtime button is pushed
                                $("#change_customtime_id").click(function() {
                                    var url = "modules/querywcdb.php?rand=" + Math.random(); // the script where you handle the form input.

                                    $.ajax({
                                        type: "POST",
                                        url: url,
                                        data: $("#queryformid").serialize(), // serializes the form's elements.
                                        success: function(data)
                                        {
                                            // alert(data); // show response from the php script.
                                            loadContentdg();    // refresh charts
                                        }
                                    });

                                    return false; // avoid to execute the actual submit of the form.
                                });
                                </script>
                                
							   <?php
                                    $diagram_mode = 'custom';
                                    include 'modules/chartsdata_index.php';
                                ?>
                                <canvas id="temperature_humidity_chart_id"></canvas>
                                <canvas id="scales_chart_id"></canvas>
                                
                                <script>

                                    // Temperatur und Feuchte
                                    var temp_hum_chart_el = document.getElementById("temperature_humidity_chart_id");
                                    var config_temp_hum_chart = {
                                        type: 'line',
                                        data: {
                                            labels: [],
                                            datasets: [{
                                                label: '<?php echo _("temperature") . ' int.' ?>',
                                                hidden: true,
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
                                                hidden: true,
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
                                    var scales_chart_el = document.getElementById("scales_chart_id");
                                    var config_scales_chart = {
                                        type: 'line',
                                        data: {
                                            labels: [],
                                            datasets: [{
                                                label: '<?php echo _("scale") ?> 1',
                                                yAxisID: 'scale1',
                                                data: <?php echo json_encode($scale1_dataset);?>,
                                                backgroundColor: '#AEC645',
                                                borderColor: '#AEC645',
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
                                                        print 'pointRadius: 1, pointHitRadius: 5,';
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
                                                label: '<?php echo _("take-off weight") . " " . _("scale"); ?> 1',
                                                yAxisID: 'scale1',
                                                data: <?php echo json_encode($scale1_take_off_weight_dataset);?>,
                                                backgroundColor: '#05F700',
                                                borderColor: '#05F700',
                                                borderWidth: 0,
                                                pointRadius: 1, pointHitRadius: 5,
                                                pointStyle: 'rect',
                                                // cubicInterpolationMode: 'monotone',
                                                borderDash: [10,10],
                                                fill: false,
                                                spanGaps: true
                                            },
                                            {
                                                label: '<?php echo _("take-off weight") . " " . _("scale"); ?> 2',
                                                yAxisID: 'scale2',
                                                data: <?php echo json_encode($scale2_take_off_weight_dataset);?>,
                                                backgroundColor: '#FFC400',
                                                borderColor: '#FFC400',
                                                borderWidth: 0,
                                                pointRadius: 1, pointHitRadius: 5,
                                                pointStyle: 'rect',
                                                // cubicInterpolationMode: 'monotone',
                                                borderDash: [10,10],
                                                fill: false,
                                                spanGaps: true
                                            }
                                            ]
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
                                                        if (tooltipItem.datasetIndex === 0) {
                                                            return Number(tooltipItem.yLabel).toFixed(1) + ' g';
                                                        } else if (tooltipItem.datasetIndex === 1) {
                                                            return Number(tooltipItem.yLabel).toFixed(1) + ' g';
                                                        } else if (tooltipItem.datasetIndex === 2) {
                                                            return Number(tooltipItem.yLabel).toFixed(1) + ' g';
                                                        } else if (tooltipItem.datasetIndex === 3) {
                                                            return Number(tooltipItem.yLabel).toFixed(1) + ' g';
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
                                                            return val + ' g' + ' ';
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
                                                            return ' ' + val + ' g';
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
                                    
                                    timestamps_temp_seconds = <?php echo json_encode($all_sensors_timestamps_array); ?>;
                                    timestamps_scales_seconds = <?php echo json_encode($all_scales_timestamps_array); ?>;
                                    timestamps_temp_js = convert_timestamps_index( timestamps_temp_seconds );
                                    timestamps_scales_js = convert_timestamps_index( timestamps_scales_seconds );

                                    config_temp_hum_chart.data.labels = timestamps_temp_js;
                                    config_scales_chart.data.labels = timestamps_scales_js;
                                    
                                    let dataset_count = config_temp_hum_chart.data.datasets.length;
                                    for (let i = 0; i < dataset_count; ++i) {
                                        let key = 'monitor_temp_dataset' + i.toString();
                                        let chart_hidden = window.localStorage.getItem(key);
                                        if (chart_hidden == null) {
                                            chart_hidden = false;
                                        }
                                        console.log(key + ' = ' + chart_hidden);
                                        if (i == 1 || i == 4) { // restore only avg curves
                                            config_temp_hum_chart.data.datasets[i].hidden = (chart_hidden == 'true');
                                        }
                                    }
                                    
                                    temp_hum_chart = new Chart(temp_hum_chart_el, config_temp_hum_chart);
                                    scales_chart = new Chart(scales_chart_el, config_scales_chart);
                                    
                                    window.addEventListener('beforeunload', (event) => {
                                        // event.preventDefault();
                                        let count = temp_hum_chart.data.datasets.length;
                                        
                                        for (let i = 0; i < count; ++i) {
                                            var ds_visible = temp_hum_chart.isDatasetVisible(i);
                                            var loc_store_name = 'monitor_temp_dataset' + i.toString();
                                            console.log('loc_store_name = ' + loc_store_name + '  ' + ds_visible);
                                            window.localStorage.setItem(loc_store_name, (!ds_visible).toString());
                                        }
                                        //event.returnValue = '';
                                    });
                                    
                                    function convert_timestamps_index( timestamps_seconds ) {
                                        // convert timestamps array from seconds to js Date strings array
                                        const timestamps_js = [];
                                        for (const el of timestamps_seconds) {
                                            var dt = new Date(el * 1000);
                                            timestamps_js.push(dt);
                                        }
                                        return timestamps_js;    
                                    }
                                                                        

/*                                    window.onload = function() {
                                        temp_hum_chart = new Chart(temp_hum_chart_el, config_temp_hum_chart);
                                        window.my_temp_hum_chart = temp_hum_chart;
                                        scales_chart = new Chart(scales_chart_el, config_scales_chart);
                                        window.my_scales_chart = scales_chart;
                                    };
*/                                    
                                    
                                </script>
                                <hr>
                                
                                <?php
                                 echo "<script src='js/ajaxcv.js'></script>";
                                 echo "<script src='js/ajaxdg.js'></script>";
                                 echo "<script src='js/ajaxstatus.js'></script>";
                                ?> 
                                
                                 <!----------------------------------------------------------------------------------------Reifetabelle-->
                                <?php 
                                    $current_period = intval(get_table_value($current_values_table, $agingtable_period_key));
                                    $current_period_hour = intval(get_table_value($current_values_table, $agingtable_period_hour_key));
                                 ?>        
                                <h2 id="aging_table_header_id" class="art-postheader"><?php echo _('agingtable') . ' - ' . $maturity_type; ?></h2>
                                <div class="hg_container">
                                    <table style="width: 100%" class="switching_state miniature_writing">
                                        <tr>
                                            <td width="75px"><?php echo _('phase'); ?></td>
                                            <td align="left" id="current_period_head_index"><?php if ($status_agingtable != 0) { echo ($current_period + 1);} else { echo '';} ?></td>
                                        </tr>
                                        <tr>
                                            <td width="75px"><?php echo _('hour'); ?></td>
                                            <td align="left" id="current_period_hour_head_index"><?php if ($status_agingtable != 0) { echo ($current_period_hour);} else { echo '';} ?></td>
                                        </tr>
                                    </table>
                                    
                                    <table id="show_agingtable" class="show_agingtable">
                                        <tr style="background-color: #F0F5FB; border-bottom: 1px solid #000033">
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('phase'); ?><span class="tooltiptext"><?php echo _('phase'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('modus'); ?><span class="tooltiptext"><?php echo _('aging-modus'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('humidity %');?><span class="tooltiptext"><?php echo _('target humidity in %'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('temperature °C');?><span class="tooltiptext"><?php echo _('target temperature in °C'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('timer circulate ON duration'); ?><span class="tooltiptext"><?php echo _('timer of the circulation air ON duration in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('timer circulate OFF duration'); ?><span class="tooltiptext"><?php echo _('timer of the circulation air OFF duration in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('timer exhaust ON duration'); ?><span class="tooltiptext"><?php echo _('timer of the exhausting air ON duration in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('timer exhaust OFF duration'); ?><span class="tooltiptext"><?php echo _('timer of the exhausting air OFF duration in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('hours'); ?><span class="tooltiptext"><?php echo _('duration of hanging phase in hours'); ?></span></div></td>
                                        </tr>
                                        
                                        <?php
                                            // Gewählte Agingtable aus DB auslesen und als Tabelle beschreiben
                                            $data_modus = ' ';
                                            $data_setpoint_humidity = ' ';
                                            $data_setpoint_temperature = ' ';
                                            $data_circulation_air_duration = ' ';
                                            $data_circulation_air_period = ' ';
                                            $data_exhaust_air_duration = ' ';
                                            $data_exhaust_air_period = ' ';
                                            $data_hours = ' ';                                            
                                            $agingtable_comment_with_carriage_return = '';
                                            
                                            if ($status_agingtable != 0) {
                                                $index_row = 0;
                                                $agingtable_rows = get_agingtable_dataset($desired_maturity);
                                                if ($agingtable_rows != false){
                                                    $firstrow = $agingtable_rows[0];
                                                    $agingtable_comment = $firstrow[$agingtable_comment_field];
                                                    if (!isset($agingtable_comment)){
                                                        $agingtable_comment = _('no comment');
                                                    }
                                                    $agingtable_comment_with_carriage_return = nl2br($agingtable_comment);
                                                    
                                                    try {
                                                        $number_rows = count($agingtable_rows);
                                                        while ($index_row < $number_rows) {
                                                            $dataset = $agingtable_rows[$index_row];
                                                            if (isset($dataset[$agingtable_modus_field])){
                                                                $data_modus = $dataset[$agingtable_modus_field];
                                                            }
                                                            if (isset($dataset[$agingtable_setpoint_humidity_field])){
                                                                $data_setpoint_humidity = $dataset[$agingtable_setpoint_humidity_field];
                                                            }
                                                            if (isset($dataset[$agingtable_setpoint_temperature_field])){
                                                                $data_setpoint_temperature = $dataset[$agingtable_setpoint_temperature_field];
                                                            }
                                                            if (isset($dataset[$agingtable_circulation_air_duration_field])){
                                                                $data_circulation_air_duration = $dataset[$agingtable_circulation_air_duration_field]/60;
                                                            }
                                                            if (isset($dataset[$agingtable_circulation_air_period_field])){
                                                                $data_circulation_air_period = $dataset[$agingtable_circulation_air_period_field]/60;
                                                            }
                                                            if (isset($dataset[$agingtable_exhaust_air_duration_field])){
                                                                $data_exhaust_air_duration = $dataset[$agingtable_exhaust_air_duration_field]/60;
                                                            }
                                                            if (isset($dataset[$agingtable_exhaust_air_period_field])){
                                                                $data_exhaust_air_period = $dataset[$agingtable_exhaust_air_period_field]/60;
                                                            }
                                                            if (isset($dataset[$agingtable_hours_field])){
                                                                $data_hours = $dataset[$agingtable_hours_field];
                                                            }

                                                            if ($current_period == $index_row AND $status_agingtable != 0){
                                                                echo '<tr bgcolor=#D19600 >';
                                                                echo '<td id="current_period_index">'. ($current_period +1) .'</td>';
                                                                echo '<td id="data_modus_index">'. $data_modus .'</td>';
                                                                echo '<td id="data_setpoint_humidity_index">'. $data_setpoint_humidity .'</td>';
                                                                echo '<td id="data_setpoint_temperature_index">'. $data_setpoint_temperature .'</td>';
                                                                echo '<td id="data_circulation_air_duration_index">'. $data_circulation_air_duration .'</td>';
                                                                echo '<td id="data_circulation_air_period_index">'. $data_circulation_air_period .'</td>';
                                                                echo '<td id="data_exhaust_air_duration_index">'. $data_exhaust_air_duration .'</td>';
                                                                echo '<td id="data_exhaust_air_period_index">'. $data_exhaust_air_period .'</td>';
                                                                echo '<td id="data_hours_index">'. $data_hours .'</td>';
                                                                echo '</tr>';
                                                                break;
                                                            }
                                                            $index_row++;
                                                        } 
                                                    }
                                                    catch (Exception $e) {
                                                    }
                                                }
                                            }
                                            else {
                                                echo '<tr bgcolor=#D19600 >';
                                                echo '<td id="current_period_index">&nbsp;</td>';
                                                echo '<td id="data_modus_index">&nbsp;</td>';
                                                echo '<td id="data_setpoint_humidity_index">&nbsp;</td>';
                                                echo '<td id="data_setpoint_temperature_index">&nbsp;</td>';
                                                echo '<td id="data_circulation_air_duration_index">&nbsp;</td>';
                                                echo '<td id="data_circulation_air_period_index">&nbsp;</td>';
                                                echo '<td id="data_exhaust_air_duration_index">&nbsp;</td>';
                                                echo '<td id="data_exhaust_air_period_index">&nbsp;</td>';
                                                echo '<td id="data_hours_index">&nbsp;</td>';
                                                echo '</tr>';
                                            }
                                        ?>
                                    </table>
                                        
                                    <table style="width: 100%" class="switching_state miniature_writing">
                                        <tr>
                                            <td width="150px" align="right"><?php echo _('comment:'); ?></td>
                                            <td id="agingtable_comment_with_carriage_return" align="left"><?php echo $agingtable_comment_with_carriage_return; ?></td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <hr>
                               
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
                                                    if ($status_main == 0){
                                                        echo '<td><img id="mainstatus_img_mode" src="images/icons/operatingmode_fail_42x42.png" alt="" style="padding: 10px;"></td>';
                                                        echo '<td><img id="mainstatus_img_onoff" src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;"></td>';
                                                    }
                                                    elseif ($status_main != 0 and $status_piager == 0){
                                                        echo '<td><img id="mainstatus_img_mode" src="images/icons/operatingmode_42x42.png" alt="" style="padding: 10px;"></td>';
                                                        echo '<td><img id="mainstatus_img_onoff" src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;"></td>';
                                                    }
                                                    elseif ($status_main != 0 and $status_piager == 1) {
                                                        echo '<td><img id="mainstatus_img_mode" src="images/icons/operating_42x42.gif" alt="" style="padding: 10px;"></td>';
                                                        echo '<td><img id="mainstatus_img_onoff" src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;"></td>';
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
                                                        echo '<img id="gpio_voltage_img" src="images/icons/5v_42x42.png" alt="" style="padding-top: 10px;">';
                                                        echo '</td>';
                                                        echo '<td id="gpio_voltage_text" style="text-align: left; ">' . _('power supply ok') . '</td>';
                                                    }
                                                    else {
                                                        echo '<td>';
                                                        echo '<img id="gpio_voltage_img" src="images/icons/5v_fail_42x42.png" alt="" style="padding-top: 10px;">';
                                                        echo '</td>';
                                                        echo '<td id="gpio_voltage_text" style="text-align: left; color: red;">' . _('no power supply! batterymode') . '</td>';
                                                    }
                                                ?>
                                                <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>   
                                            <td></td>
                                            <td id="main_status_text" class="text_left_top"><?php
                                                if ($status_main == 0){
                                                    echo strtoupper(_('see settings'));
                                                }
                                                elseif ($status_main != 0 and $status_piager == 0){
                                                    echo strtoupper(_('off'));
                                                }
                                                elseif ($status_main != 0 and $status_piager == 1){
                                                    echo $modus_name;
                                                }
                                                ?></td>
                                            <?php 
                                                // Prüft, ob Batteriespannung vorhanden ist
                                                //$read_gpio_battery = shell_exec('sudo /var/sudowebscript.sh read_gpio_battery');
                                                if ($read_gpio_battery == 1){
                                                    echo '<td>';
                                                    echo '<img id="gpio_battery_img" src="images/icons/battery_42x42.png" alt="" style="padding-top: 10px;">';
                                                    echo '</td>';
                                                    echo '<td id="gpio_battery_text" style="text-align: left; ">' . _('battery voltage ok') . '</td>';
                                                }
                                                else {
                                                    echo '<td>';
                                                    echo '<img id="gpio_battery_img" src="images/icons/battery_fail_42x42.png" alt="" style="padding-top: 10px;">';
                                                    echo '</td>';
                                                    echo '<td id="gpio_battery_text" style="text-align: left; color: red;">' . _('battery voltage low !!') . '</td>';
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
                                                // Prüft, ob thread Reifetabee läuft
                                                if ($status_main == 0){
                                                    echo '<td><img id="agingtable_img" src="images/icons/agingtable_fail_42x42.gif" alt="" style="padding: 10px;"></td>';
                                                    echo '<td><img id="agingtable_img_status" src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;"></td>';
                                                }
                                                else if ($status_agingtable == 0){
                                                    echo '<td><img id="agingtable_img" src="images/icons/agingtable_42x42.png" alt="" style="padding: 10px;"></td>';
                                                    echo '<td><img id="agingtable_img_status" src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;"></td>';
                                                }
                                                else {
                                                    echo '<td><img id="agingtable_img" src="images/icons/agingtable_42x42.gif" alt="" style="padding: 10px;"></td>';
                                                    echo '<td><img id="agingtable_img_status" src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;"></td>';
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
                                            <td id="maturity_type_id" class="text_left_top"><?php echo $maturity_type;?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <!--------------------------------------switch-->
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
                                                <img src="images/icons/switch_42x42.png" alt="" style="padding: 10px;">
                                            </td>
                                                <?php 
                                                    // checks if digital_io is on or off
                                                    //$read_gpio_digital_switch = shell_exec('sudo /var/sudowebscript.sh read_gpio_digital_switch');
                                                    if ($read_gpio_digital_switch == 0){
                                                        echo '<td>';
                                                        echo '<img id="switch_img" src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;">';
                                                        echo '</td>';
                                                        echo '<td id="switch_text" style="text-align: left; ">' . _('Switch is on') . '</td>';
                                                    }
                                                    else {
                                                        echo '<td>';
                                                        echo '<img id="switch_img" src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;">';
                                                        echo '</td>';
                                                        echo '<td id="switch_text" style="text-align: left;">' . _('Switch is off') . '</td>';
                                                    }
                                                ?>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <!--------------------------------------defrost-->
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
                                                <img src="images/icons/defrost_42x42.png" alt="" style="padding: 10px;">
                                            </td>
                                                <?php 
                                                    // checks if defrost is in progress
                                                    $defrost_in_progress = intval(get_table_value($current_values_table, $status_defrost_key)); 
                                                    if ($defrost_in_progress == 1){
                                                        echo '<td>';
                                                        echo '<img id="defrost_img" src="images/icons/status_on_20x20.png" alt="" style="padding-top: 10px;">';
                                                        echo '</td>';
                                                        echo '<td id="defrost_text" style="text-align: left; ">' . _('Defrost in progress') . '</td>';
                                                    }
                                                    else {
                                                        echo '<td>';
                                                        echo '<img id="defrost_img" src="images/icons/status_off_20x20.png" alt="" style="padding-top: 10px;">';
                                                        echo '</td>';
                                                        echo '<td id="defrost_text" style="text-align: left;">' . _('Defrost currently off') . '</td>';
                                                    }
                                                ?>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <!---------------------------------------temperatures-->
                                    <hr>
                                    <h2><?php echo _('temperature/humidity control'); ?></h2>
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
                                                if ($status_main == 0 || $status_piager == 0) {
                                                    $sensor_temperature = '--.-';
                                                    $sensor_humidity = '--.-';
                                                }
                                                $internal_temperature = get_table_value($current_values_table, $sensor_temperature_key);
                                                $external_temperature = get_table_value($current_values_table, $sensor_extern_temperature_key);
                                                if ($external_temperature !== null && $internal_temperature !== null && $external_temperature < $setpoint_temperature && ($modus == 3 || $modus == 4)) {
                                                    $cooler_on = number_format(floatval($setpoint_temperature + $heating_hysteresis/2 + $heating_hysteresis_offset), 2, '.', '');
                                                    $cooler_off = number_format(floatval($setpoint_temperature - $heating_hysteresis/2 + $heating_hysteresis_offset), 2, '.', '');
                                                    $heater_on = number_format(floatval($setpoint_temperature - $cooling_hysteresis/2 + $cooling_hysteresis_offset), 2, '.', '');
                                                    $heater_off = number_format(floatval($setpoint_temperature + $cooling_hysteresis/2 + $cooling_hysteresis_offset), 2, '.', '');
                                                }
                                                else if ($modus == 2) { // heater only
                                                    $heater_on = number_format(floatval($setpoint_temperature - $cooling_hysteresis/2 + $heating_hysteresis_offset), 2, '.', '');
                                                    $heater_off = number_format(floatval($setpoint_temperature + $cooling_hysteresis/2 + $heating_hysteresis_offset), 2, '.', '');
                                                }
                                                else {
                                                    $cooler_on = number_format(floatval($setpoint_temperature + $cooling_hysteresis/2 + $cooling_hysteresis_offset), 2, '.', '');
                                                    $cooler_off = number_format(floatval($setpoint_temperature - $cooling_hysteresis/2 + $cooling_hysteresis_offset), 2, '.', '');
                                                    $heater_on = number_format(floatval($setpoint_temperature - $heating_hysteresis/2 + $heating_hysteresis_offset), 2, '.', '');
                                                    $heater_off = number_format(floatval($setpoint_temperature + $heating_hysteresis/2 + $heating_hysteresis_offset), 2, '.', '');
                                                }
                                                if ($modus == 0 || $modus == 1){
                                                    echo '<td><img id="mod_type_line1_id" src="images/icons/cooling_42x42.png" alt=""></td>
                                                        <td><img id="mod_stat_line1_id" src="'.$cooler_on_off_png.'" title="PIN_COOL 4[7] -> IN 1 (PIN2)"></td>
                                                        <td id="mod_name_line1_id" class="text_left">';
                                                    echo strtoupper(_('cooler'));
                                                    echo '</td>
                                                        <td id="mod_current_line1_id">'.$sensor_temperature.' °C</td>
                                                        <td id="mod_setpoint_line1_id">'.$setpoint_temperature.' °C</td>
                                                        <td id="mod_on_line1_id">'.$cooler_on.' °C</td>
                                                        <td id="mod_off_line1_id">'.$cooler_off.' °C</td>';
                                                }
                                                else if ($modus == 2){
                                                    // in mode 2, when only heating is active, take primary hysteresis values (primary is cooling_hysteresis).
                                                    echo '<td><img id="mod_type_line1_id" src="images/icons/heating_42x42.png" alt=""></td>
                                                        <td><img id="mod_stat_line1_id" src="'.$heater_on_off_png.'" title="PIN_HEATER 3[5] -> IN 2 (PIN 3)"></td>
                                                        <td id="mod_name_line1_id" class="text_left">';
                                                    echo strtoupper(_('heater'));
                                                    echo '</td>
                                                        <td id="mod_current_line1_id">'.$sensor_temperature.' °C</td>
                                                        <td id="mod_setpoint_line1_id">'.$setpoint_temperature.' °C</td>
                                                        <td id="mod_on_line1_id">'.$heater_on.' °C</td>
                                                        <td id="mod_off_line1_id">'.$heater_off.' °C</td>';
                                                }
                                                else {
                                                    echo '<td><img id="mod_type_line1_id" src="images/icons/cooling_42x42.png" alt=""></td>
                                                        <td><img id="mod_stat_line1_id" src="'.$cooler_on_off_png.'" title="PIN_COOL 4[7] -> IN 1 (PIN2)"></td>
                                                        <td id="mod_name_line1_id" class="text_left">';
                                                    echo strtoupper(_('cooler'));
                                                    echo '<td id="mod_current_line1_id">'.$sensor_temperature.' °C</td>
                                                        <td id="mod_setpoint_line1_id">'.$setpoint_temperature.' °C</td>
                                                        <td id="mod_on_line1_id">'.$cooler_on.' °C</td>
                                                        <td id="mod_off_line1_id">'.$cooler_off.' °C</td></tr>';
                                                        
                                                    echo '<tr><td ><img id="mod_type_line2_id" src="images/icons/heating_42x42.png" alt=""></td>
                                                        <td><img id="mod_stat_line2_id" src="'.$heater_on_off_png.'" title="PIN_HEATER 3[5] -> IN 2 (PIN 3)"></td>
                                                        <td id="mod_name_line2_id" class="text_left">';
                                                    echo strtoupper(_('heater'));
                                                    echo '<td id="mod_current_line2_id">'.$sensor_temperature.' °C</td>
                                                        <td id="mod_setpoint_line2_id">'.$setpoint_temperature.' °C</td>
                                                        <td id="mod_on_line2_id">'.$heater_on.' °C</td>
                                                        <td id="mod_off_line2_id">'.$heater_off.' °C</td>';
                                                }
                                            ?>
                                        </tr>
                                        <tr>
                                           <?php 
                                                if ($modus == 1 || $modus == 2 || $modus == 3){
                                                    echo '<td><img id="mod_type_line3_id" src="images/icons/humidification_42x42.png" alt=""></td>
                                                        <td><img id="mod_stat_line3_id" src='.$humidifier_on_off_png.' title="PIN_HUM 18[12] -> IN 3 (PIN 4)"></td>
                                                        <td id="mod_name_line3_id" class="text_left">';
                                                    echo strtoupper(_('humidification'));
                                                    echo '</td>
                                                        <td id="mod_current_line3_id">'.$sensor_humidity.' %</td>
                                                        <td id="mod_setpoint_line3_id">'.$setpoint_humidity.' %</td>
                                                        <td id="mod_on_line3_id">' . eval_switch_on_humidity( $setpoint_humidity, $humidifier_hysteresis, $humidifier_hysteresis_offset ) . ' %</td>
                                                        <td id="mod_off_line3_id">' . eval_switch_off_humidity( $setpoint_humidity, $humidifier_hysteresis, $humidifier_hysteresis_offset, $saturation_point ) . ' %</td>';
                                                }

                                                if ($modus == 4){
                                                    echo '<td><img id="mod_type_line3_id" src="images/icons/humidification_42x42.png" alt=""></td>
                                                        <td><img id="mod_stat_line3_id" src='.$humidifier_on_off_png.' title="PIN_HUM 18[12] -> IN 3 (PIN 4)"></td>
                                                        <td id="mod_name_line3_id" class="text_left">';
                                                    echo strtoupper(_('humidification'));
                                                    echo '<td id="mod_current_line3_id">'.$sensor_humidity.' %</td>
                                                        <td id="mod_setpoint_line3_id">'.$setpoint_humidity.' %</td>
                                                        <td id="mod_on_line3_id">' . eval_switch_on_humidity( $setpoint_humidity, $humidifier_hysteresis, $humidifier_hysteresis_offset ) . ' %</td>
                                                        <td id="mod_off_line3_id">' . eval_switch_off_humidity( $setpoint_humidity, $humidifier_hysteresis, $humidifier_hysteresis_offset, $saturation_point ) . ' %</td></tr>';
                                               
                                                    echo '<tr><td ><img id="mod_type_line5_id" src="images/icons/dehumidification_42x42.png" alt=""></td>
                                                        <td><img id="mod_stat_line5_id" src='.$dehumidifier_on_off_png.' title="PIN_DEH 7[26] -> IN 8 (PIN 9)"></td>
                                                        <td id="mod_name_line5_id" class="text_left">';
                                                    echo strtoupper(_('dehumidification'));
                                                    echo '</td>
                                                        <td id="mod_current_line5_id">'.$sensor_humidity.' %</td>
                                                        <td id="mod_setpoint_line5_id">'.$setpoint_humidity.' %</td>
                                                        <td id="mod_on_line5_id">' . eval_switch_on_dehumidity( $setpoint_humidity, $dehumidifier_hysteresis, $dehumidifier_hysteresis_offset, $saturation_point ) . ' %</td>
                                                        <td id="mod_off_line5_id">' . eval_switch_off_dehumidity( $setpoint_humidity,  $dehumidifier_hysteresis, $dehumidifier_hysteresis_offset ) . ' %</td></tr>';
                                                        
                                                    echo '<tr><td ><img id="mod_type_line4_id" src="images/icons/exhausting_42x42.png" alt=""></td>
                                                        <td><img id="mod_stat_line4_id" src='.$exhausting_on_off_png.' title="PIN_EXH 23[16] -> IN 5 (PIN 5)"></td>
                                                        <td id="mod_name_line4_id" class="text_left">';
                                                    echo strtoupper(_('exhausting'));
                                                    echo '</td></tr>';
                                                                                        
                                                    if ($sensorsecondtype != 0){  # if (!($bus == 1 || $sensorsecondtype == 0)){  // show abs. humidity check aktive only with second sensor
                                                        echo '<tr><td></td>';  // skip type column
                                                        if ($dehumidifier_modus == 3) {     // only dehumidification
                                                            echo '<td><img id="mod_stat_line6_id" src="images/icons/status_off_20x20.png" alt=""></td>';
                                                        }
                                                        else {
                                                            // $sensor_humidity_abs = get_table_value($current_values_table, $sensor_humidity_abs_key);
                                                            // $sensor_extern_humidity_abs = get_table_value($current_values_table, $sensor_extern_humidity_abs_key);
                                                            $status_humidity_check = intval(get_table_value($current_values_table, $status_humidity_check_key));
                                                            //if ($sensor_humidity_abs < $sensor_extern_humidity_abs) {
                                                            if ($status_humidity_check == 1) {
                                                                echo '<td><img id="mod_stat_line6_id" src="images/icons/status_on_red_20x20.png" alt=""></td>';
                                                            }
                                                            else {
                                                                echo '<td><img id="mod_stat_line6_id" src="images/icons/status_on_20x20.png" alt=""></td>';
                                                            }
                                                        }
                                                        echo '<td id="mod_name_line6_id" class="text_left">';
                                                        echo mb_strtoupper(_('abs. humidity check') . ': ');
                                                        
                                                        if ($dewpoint_check == 1) {
                                                            echo strtoupper(_('on'));
                                                        }
                                                        else {
                                                            echo strtoupper(_('off'));
                                                        }
                                                        echo '</td></tr>';  
                                                    }
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
                                            <td width="100px"><b><?php echo strtoupper(_('OFF DURATION')); ?></b></td>
                                            <td width="100px"><b><?php echo strtoupper(_('ON DURATION')); ?></b></td>
                                        </tr>
                                        
                                        <tr>
                                            <td><img id="timer_type_line1_id" <?php if ($circulation_air_duration == 0) {echo 'class="transpng"';} ?> src="images/icons/circulate_42x42.png" alt=""></td>
                                            <td><img id="timer_stat_line1_id" src="<?php echo $circulating_on_off_png ;?>" title="PIN_FAN 24[18] -> IN 5 (PIN 6)"></td>
                                            <td id="timer_name_line1_id" class="text_left">
                                            <?php
                                                echo strtoupper(_('circulating air'));
                                                if ($circulation_air_duration > 0 && $circulation_air_period >0) {echo ', '.strtoupper(_('timer on'));}
                                                elseif ($circulation_air_period == 0) {echo  ' '. strtoupper(_('always on'));}
                                                elseif ($circulation_air_duration == 0) {echo ', '. strtoupper(_('timer off'));}
                                            ?></td>
                                            <td></td>
                                            <td id="timer_period_line1_id"><?php echo $circulation_air_period.' '._('minutes'); ?></td>
                                            <td id="timer_dur_line1_id"><?php echo $circulation_air_duration.' '._('minutes'); ?></td>
                                        </tr>
                                        
                                        <tr>
                                            <td><img id="timer_type_line2_id" <?php if ($exhaust_air_duration == 0) {echo 'class="transpng"';} ?> src="images/icons/exhausting_42x42.png" alt=""></td>
                                            <td><img id="timer_stat_line2_id" src="<?php echo $exhausting_on_off_png ;?>" title="PIN_FAN 23[16] -> IN 4 (PIN 5)"></td>
                                            <td id="timer_name_line2_id" class="text_left">
                                            <?php
                                                echo strtoupper(_('exhausting air'));
                                                if ($exhaust_air_duration > 0 && $exhaust_air_period >0) {echo ', '.strtoupper(_('timer on'));}
                                                elseif ($exhaust_air_period == 0) {echo  ' '. strtoupper(_('always on'));}
                                                elseif ($exhaust_air_duration == 0) {echo ', '. strtoupper(_('timer off'));}
                                            ?></td>
                                            <td></td>
                                            <td id="timer_period_line2_id"><?php echo $exhaust_air_period.' '._('minutes'); ?></td>
                                            <td id="timer_dur_line2_id"><?php echo $exhaust_air_duration.' '._('minutes'); ?></td>
                                        </tr>
                                        
                                        <tr>
                                            <td><img id="timer_type_line3_id" <?php if ($uv_duration == 0) {echo 'class="transpng"';} ?> src="images/icons/uv-light_42x42.png" alt=""></td>
                                            <td><?php
                                                if ($status_uv_manual == 0) {
                                                    echo '<img id="timer_stat_line3_id" src="images/icons/status_off_manual_20x20.png" title="uv off">';
                                                }
                                                else{
                                                    echo '<img id="timer_stat_line3_id" src="'.$uv_on_off_png.'">';
                                                }
                                            ?>
                                            </td>
                                            <td id="timer_name_line3_id" class="text_left">
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
                                            <td id="timer_period_line3_id"><?php echo $uv_period.' '._('minutes'); ?></td>
                                            <td id="timer_dur_line3_id"><?php echo $uv_duration.' '._('minutes'); ?></td>
                                           
                                        </tr>
                                        
                                        <tr>
                                            <td><img id="timer_type_line4_id" <?php if ($light_duration == 0) {echo 'class="transpng"';} ?> src="images/icons/light_42x42.png" alt=""></td>
                                            <td>
                                                <?php
                                                if ($status_light_manual == 1) {
                                                    echo '<img id="timer_stat_line4_id" src="/images/icons/status_on_manual_20x20.png" title="light on">';
                                                }
                                                else{
                                                    echo '<img id="timer_stat_line4_id" src="'.$light_on_off_png.'">';
                                                }
                                                ?>
                                            </td>
                                            <td id="timer_name_line4_id" class="text_left">
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
                                                <td id="timer_period_line4_id"><?php echo $light_period.' '._('minutes'); ?></td>
                                                <td id="timer_dur_line4_id"><?php echo $light_duration.' '._('minutes'); ?></td>
                                            
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
                                                // check thread status
                                                if ($scale1_thread_alive == 0){
                                                    echo '<td><img id="scale1_img_id" src="images/icons/scale_fail_42x42.gif" alt=""></td>
                                                            <td><img id="scale1_onoff_status_id" src="images/icons/status_off_20x20.png" title=""></td>
                                                            <td id="scale1_status_text_id" class="text_left">';
                                                    echo strtoupper(_('see settings'));
                                                    echo '</td>';
                                                }
                                                elseif ($status_scale1 == 0){
                                                    echo '<td><img id="scale1_img_id" src="images/icons/scale_42x42.png" alt=""></td>
                                                            <td><img id="scale1_onoff_status_id" src="images/icons/status_off_20x20.png" title=""></td>
                                                            <td id="scale1_status_text_id" class="text_left">';
                                                    echo strtoupper(_('scale1'));
                                                    echo '</td>';
                                                }
                                                elseif ($status_scale1 == 1) {
                                                    echo '<td><img id="scale1_img_id" src="images/icons/scale_42x42.gif" alt=""></td>
                                                            <td><img id="scale1_onoff_status_id" src="images/icons/status_on_20x20.png" title=""></td>
                                                            <td id="scale1_status_text_id" class="text_left">';
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
                                                // check thread status
                                                if ($scale2_thread_alive == 0){
                                                    echo '<td><img id="scale2_img_id" src="images/icons/scale_fail_42x42.gif" alt=""></td>
                                                            <td><img id="scale2_onoff_status_id" src="images/icons/status_off_20x20.png" title=""></td>
                                                            <td id="scale2_status_text_id" class="text_left">';
                                                    echo strtoupper(_('see settings'));
                                                    echo '</td>';
                                                }
                                                elseif ($status_scale2 == 0){
                                                    echo '<td><img id="scale2_img_id" src="images/icons/scale_42x42.png" alt=""></td>
                                                            <td><img id="scale2_onoff_status_id" src="images/icons/status_off_20x20.png" title=></td>
                                                            <td id="scale2_status_text_id" class="text_left">';
                                                    echo strtoupper(_('scale2'));
                                                    echo '</td>';
                                                }
                                                elseif ($status_scale2 == 1) {
                                                  echo '<td><img id="scale2_img_id" src="images/icons/scale_42x42.gif" alt=""></td>
                                                            <td><img id="scale2_onoff_status_id" src="images/icons/status_on_20x20.png" title=></td>
                                                            <td id="scale2_status_text_id" class="text_left">';
                                                    echo strtoupper(_('scale2'));
                                                    echo '</td>';
                                                }
                                            ?>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <h2><?php echo _('uptimes'); ?></h2>

                                    <?php
                                        $uptime_row = get_table_row($time_meter_table, 1);
                                        $uv_uptime_seconds = intval($uptime_row[$uv_light_seconds_field]);
                                        $uv_uptime_formatted = convert_seconds_to_hours($uv_uptime_seconds, 2);
                                        $pi_ager_uptime_seconds = intval($uptime_row[$pi_ager_seconds_field]);
                                        $pi_ager_uptime_formatted = convert_seconds_to_hours($pi_ager_uptime_seconds, 2);
                                    ?>
                                    <table class="show_uptime_table">
                                        <tr style="background-color: #F0F5FB; border-bottom: 1px solid #000033">
                                            <td class="show_uptime_cell"><div class="tooltip"><?php echo _('name'); ?><span class="tooltiptext"><?php echo _('uptime object name'); ?></span></div></td>
                                            <td class="show_uptime_cell"><div class="tooltip"><?php echo _('uptime'); ?><span class="tooltiptext"><?php echo _('uptime in hours'); ?></span></div></td>                                       
                                        </tr>
                                        <tr>
                                            <td><?php echo _('Pi-Ager');?></td>
                                            <td id="pi_ager_uptime_id"><?php echo $pi_ager_uptime_formatted;?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo _('uv light');?></td>
                                            <td id="uv_uptime_id"><?php echo $uv_uptime_formatted;?></td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <script src='js/ajaxat.js'></script>
                                
<!--                                <script>
                                    if ( window.history.replaceState ) {    // avoid page confirmation on refresh
                                        window.history.replaceState( null, null, window.location.href );
                                    }
                                </script>  -->
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--        </div> -->
        <?php 
            include 'footer.php';
        ?>
