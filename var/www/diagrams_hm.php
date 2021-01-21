<?php
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                    include 'modules/database.php';
                                    include 'modules/logging.php';                            //liest die Datei fuer das logging ein
                                    include 'modules/write_customtime_db.php';                        //speichert die individuelle Zeit für die Diagramme
                                ?>
                                <!----------------------------------------------------------------------------------------Was eben hier hin kommt ...-->
								
                                <?php 
                                    // wenn nichts anderes ausgewählt wurde, ist Stündlich ausgewählt
                                    if (isset ($_GET['diagram_mode'])) {
                                        $diagram_mode = $_GET['diagram_mode'];
                                    }else{
                                        $diagram_mode = 'hour';
                                    }
                                    function get_translated_diagram_mode($diagram_mode){
                                        switch ($diagram_mode){
                                            case 'hour':
                                                return $diagram_mode_translated = _('hour');
                                            case 'day':
                                                return $diagram_mode_translated = _('day');
                                            case 'week':
                                                return $diagram_mode_translated = _('week');
                                            case 'month':
                                                return $diagram_mode_translated = _('month');
                                            case 'custom':
                                                return $diagram_mode_translated = _('custom');
                                        }
                                    }
                                    $diagram_mode_translated = get_translated_diagram_mode($diagram_mode);
                                ?>
                                <h2 class="art-postheader"><?php echo _('diagrams') . ' - ' . $diagram_mode_translated; ?></h2>
                                <div class="hg_container" style="margin-bottom: 20px; margin-top: 20px;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td><img src="images/icons/hour_42x42.png" alt=""></td>
                                            <td><img src="images/icons/daily_42x42.png" alt=""></td>
                                            <td><img src="images/icons/week_42x42.png" alt=""></td>
                                            <td><img src="images/icons/month_42x42.png" alt=""></td>
                                            <td><img src="images/icons/custom_42x42.png" alt=""></td>
                                        </tr>
                                        <tr>
                                            <td><a href="diagrams.php?diagram_mode=hour" class="art-button"><?php echo _('hour'); ?></a></td>
                                            <td><a href="diagrams.php?diagram_mode=day" class="art-button"><?php echo _('day'); ?></a></td>
                                            <td><a href="diagrams.php?diagram_mode=week" class="art-button"><?php echo _('week'); ?></a></td>
                                            <td><a href="diagrams.php?diagram_mode=month" class="art-button"><?php echo _('month'); ?></a></td>
                                            <td><a href="diagrams.php?diagram_mode=custom" class="art-button"><?php echo _('custom'); ?></a></td>
                                        </tr>
<!--                                        <tr>
                                            <td><button class="art-button" type="button" id="hour">hour</button></td>
                                            <td><button class="art-button" type="button" id="day">day</button></td>
                                            <td><button class="art-button" type="button" id="week">week</button></td>
                                            <td><button class="art-button" type="button" id="month">month</button></td>
                                            <td><button class="art-button" type="button" id="month">custom</button></td>
                                        </tr>
-->                                 </table>
                                    <?php
                                        if ($diagram_mode_translated == 'custom'){
                                            
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
                                            
                                            
                                            echo '<hr>';
                                            echo '<form method="post" name="change_customtime">';
                                                echo '<table style="width: 100%;">';
                                                    echo '<tr>';
                                                        /* echo '<td>years</td>'; */
                                                        echo '<td>month</td>';
                                                        echo '<td>days</td>';
                                                        echo '<td>hours</td>';
                                                        echo '<td>minutes</td>';
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
                                                echo '<button class="art-button" name="change_customtime" value="change_customtime" onclick="return confirm("' . _("change customtime?") . '");">' . _("change") . '</button>';
                                            echo '</form>';
                                        }
                                    ?>
                                </div>

                                    <div style="">
                                        <h4><?php echo _('every ~ 5 minutes a new value is written!'); ?></h4>
                                        <?php
                                            include 'modules/read_values_for_diagrams.php';
                                        ?>
                                        <canvas id="temperature_humidity_chart"></canvas>
                                        <canvas id="scales_chart"></canvas>
                                        <canvas id="thermometer1_chart"></canvas> 
<!--                                    <canvas id="thermometer2_chart"></canvas> 
                                        <canvas id="thermometer3_chart"></canvas> 
-->
                                        <canvas id="thermometer4_chart"></canvas> 
                                        <div class="on_off_chart"><canvas id="cooler_chart"></canvas></div>
                                        <div class="on_off_chart"><canvas id="heater_chart"></canvas></div>
                                        <div class="on_off_chart"><canvas id="humidifier_chart"></canvas></div>
                                        <div class="on_off_chart"><canvas id="dehumidifier_chart"></canvas></div>
                                        <div class="on_off_chart"><canvas id="circulation_air_chart"></canvas></div>
                                        <div class="on_off_chart"><canvas id="exhaust_air_chart"></canvas></div>
                                        <div class="on_off_chart"><canvas id="uv_chart"></canvas></div>
                                        <div class="on_off_chart"><canvas id="light_chart"></canvas></div>
                                        
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
                                                    <?php
                                                        if ($diagram_mode == 'hour' or ($diagram_mode == 'custom' and $customtime <= 3600)) {
                                                            print 'pointRadius: 1, pointHitRadius: 5,';
                                                        }
                                                        else
                                                        {
                                                            print 'pointRadius: 0, pointHitRadius: 5,';
                                                        }
                                                    ?>
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
                                                    <?php
                                                        if ($diagram_mode == 'hour' or ($diagram_mode == 'custom' and $customtime <= 3600)) {
                                                            print 'pointRadius: 1, pointHitRadius: 5,';
                                                        }
                                                        else
                                                        {
                                                            print 'pointRadius: 0, pointHitRadius: 5,';
                                                        }
                                                    ?>
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
                                                tooltips: {
                                                    mode: 'index',
                                                    intersect: false,
                                                    callbacks: {
                                                        label: function(tooltipItem, data) {
                                                            if (tooltipItem.datasetIndex === 0) {
                                                                return Number(tooltipItem.yLabel).toFixed(1) + ' °C';
                                                            } else if (tooltipItem.datasetIndex === 1) {
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
                                                    }, ],
                                                    yAxes: [{
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: '<?php echo _("temperature") ?> <?php echo _(" - ϑ") ?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'temperature',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                return '  ' + value + ' °C' + '  ';
                                                            },
                                                            fontColor: '#000000',
                                                        //    fontSize: 20,
                                                            //max: 30,
															max: <?php 
                                                            $max_value_temperature = intval(max($temperature_dataset)/10)*10 +15;
                                                            print $max_value_temperature;
                                                            ?>,
                                                            //min: -2
															min: <?php														
                                                            $min_value_temperature = intval(min($non_empties = array_filter($temperature_dataset))/1)*1 - 1;
															print $min_value_temperature;	
                                                            ?>,
                                                        }
                                                        
                                                    }, {
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: '<?php echo _("humidity") ?> <?php echo _(" - φ") ?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'humidity',
                                                        type: 'linear',
                                                        display: true,
                                                        position: 'right',
                                                        labelString: '<?php echo _("humidity") ?>',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                return '  ' + value + ' %' + '  ';
                                                            },
                                                            fontColor: '#000000',
                                                           //    fontSize: 20,
                                                            max: <?php 
                                                            $max_value_humidiy = intval(max($humidity_dataset)/10) * 10 + 10;
                                                            print $max_value_humidiy;
                                                            ?>,
                                                            //min: <?php 
                                                            //$min_value_humidiy = intval(min($humidity_dataset) - (min($humidity_dataset) / 100 * 1))-1;
                                                            //?>
                                                            //max: 100,
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
                                                    <?php 
                                                    echo $scale1_timestamps_axis_text;
                                                    ?>,
                                                datasets: [{
                                                    label: '<?php echo _("scale") ?> 1',
                                                    yAxisID: 'scale1',
                                                    data: <?php echo json_encode($scale1_dataset);?>,
                                                    backgroundColor: '#AEC645',
                                                    borderColor: '#AEC645',
                                                    borderWidth: 2,
                                                    <?php
                                                        if ($diagram_mode == 'hour' or ($diagram_mode == 'custom' and $customtime <= 3600)) {
                                                            print 'pointRadius: 1, pointHitRadius: 5,';
                                                        }
                                                        else
                                                        {
                                                            print 'pointRadius: 0, pointHitRadius: 5,';
                                                        }
                                                    ?>
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
                                                    <?php
                                                        if ($diagram_mode == 'hour' or ($diagram_mode == 'custom' and $customtime <= 3600)) {
                                                            print 'pointRadius: 1, pointHitRadius: 5,';
                                                        }
                                                        else
                                                        {
                                                            print 'pointRadius: 0, pointHitRadius: 5,';
                                                        }
                                                    ?>
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
                                                        //    fontSize: 20,
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
                                                        //    fontSize: 20,
                                                            //max: 25000,
                                                            beginAtZero: true,
                                                            maxTicksLimit: 10,
                                                            max: <?php 
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
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'scale2',
                                                        type: 'linear',
                                                        position: 'right',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                if (Math.round(value) === value) {
                                                                return ' ' + value + ' gr';
                                                            }
                                                            },
                                                            fontColor: '#000000',
                                                            //    fontSize: 20,
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
                                        
                                        // NTC thermometer 1,2,3
                                        var thermometer1_chart = document.getElementById("thermometer1_chart");
                                        var config_thermometer1_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php 
                                                    echo $temperature_timestamps_axis_text;
                                                    ?>,
                                                datasets: [
                                                {
                                                    label: '<?php echo _("temperature") . ' NTC 1' ?>',
                                                    yAxisID: 'temperature',
                                                    data: <?php echo json_encode($thermometer1_dataset); ?>,
                                                    backgroundColor: '#F7AC08',
                                                    borderColor: '#F7AC08',
                                                    borderWidth: 2,
                                                    <?php
                                                        if ($diagram_mode == 'hour' or ($diagram_mode == 'custom' and $customtime <= 3600)) {
                                                            print 'pointRadius: 1, pointHitRadius: 5,';
                                                        }
                                                        else
                                                        {
                                                            print 'pointRadius: 0, pointHitRadius: 5,';
                                                        }
                                                    ?>
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                },
                                                {
                                                    label: '<?php echo _("temperature") . ' NTC 2' ?>',
                                                    yAxisID: 'temperature',
                                                    data: <?php echo json_encode($thermometer2_dataset); ?>,
                                                    backgroundColor: '#06AF8F',
                                                    borderColor: '#06AF8F',
                                                    borderWidth: 2,
                                                    <?php
                                                        if ($diagram_mode == 'hour' or ($diagram_mode == 'custom' and $customtime <= 3600)) {
                                                            print 'pointRadius: 1, pointHitRadius: 5,';
                                                        }
                                                        else
                                                        {
                                                            print 'pointRadius: 0, pointHitRadius: 5,';
                                                        }
                                                    ?>
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                },                                                
                                                {
                                                    label: '<?php echo _("temperature") . ' NTC 3' ?>',
                                                    yAxisID: 'temperature',
                                                    data: <?php echo json_encode($thermometer3_dataset); ?>,
                                                    backgroundColor: '#AF06A1',
                                                    borderColor: '#AF06A1',
                                                    borderWidth: 2,
                                                    <?php
                                                        if ($diagram_mode == 'hour' or ($diagram_mode == 'custom' and $customtime <= 3600)) {
                                                            print 'pointRadius: 1, pointHitRadius: 5,';
                                                        }
                                                        else
                                                        {
                                                            print 'pointRadius: 0, pointHitRadius: 5,';
                                                        }
                                                    ?>
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                }]
                                            },
                                            options: {
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("Thermometer") ?> NTC 1..3',
                                                    fontSize: 24
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
                                                    yAxes: [
                                                    {
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
                                                                if (Math.round(value) === value) {
                                                                return '  ' + value + ' °C' + '  ';
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                            //    fontSize: 20,
                                                            //max: 25000,
                                                            beginAtZero: true,
                                                            maxTicksLimit: 10,
                                                            max: 30, 
                                                            min: -4
                                                        }
                                                    },
                                                    {
                                                        scaleLabel: {
                                                            display: true,
                                                            //labelString: '<?php echo _("temperature")?> <?php echo _(" - ϑ") ?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'temperature_right',
                                                        type: 'linear',
                                                        position: 'right',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                if (Math.round(value) === value) {
                                                                return '  ' + value + ' °C' + '  ';
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                            //    fontSize: 20,
                                                            //max: 25000,
                                                            beginAtZero: true,
                                                            maxTicksLimit: 10,
                                                            max: 30, 
                                                            min: -4
                                                        }
                                                    }]
                                                }
                                            }
                                        };
 
                                        
                                        // Meat thermometer NTC 4, can also be AC/DC Current sensor
                                        var thermometer4_chart = document.getElementById("thermometer4_chart");
                                        var config_thermometer4_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php 
                                                    echo $thermometer4_timestamps_axis_text;
                                                    ?>,
                                                datasets: [
                                                {
                                                    label: '<?php if ($sensor4_is_current == true) { echo _("Current") . " - I"; } else { echo _("temperature") . _(" - ϑ");}?>',
                                                    yAxisID: 'temperature',
                                                    data: <?php echo json_encode($thermometer4_dataset); ?>,
                                                    backgroundColor: '#ff0000',
                                                    borderColor: '#ff0000',
                                                    borderWidth: 2,
                                                    <?php
                                                        if ($diagram_mode == 'hour' or ($diagram_mode == 'custom' and $customtime <= 3600)) {
                                                            print 'pointRadius: 1, pointHitRadius: 5,';
                                                        }
                                                        else
                                                        {
                                                            print 'pointRadius: 0, pointHitRadius: 5,';
                                                        }
                                                    ?>
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                }]
                                            },
                                            options: {
                                                title: {
                                                    display: true,
                                                    text: '<?php if ($sensor4_is_current == true){ echo "AC/DC-Current"; } else { echo _("Thermometer") . ' NTC 4';} ?>',
                                                    fontSize: 24
                                                },
                                                tooltips: {
                                                    mode: 'index',
                                                    intersect: false,
                                                    callbacks: {
                                                        label: function(tooltipItem, data) {
                                                            return Number(tooltipItem.yLabel).toFixed(3) + '<?php if ($sensor4_is_current == true){ echo " A"; } else { echo " °C"; } ?>';
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
                                                    yAxes: [
                                                    {
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: '<?php if ($sensor4_is_current == true) { echo _("Current") . " - I"; } else { echo _("temperature") . _(" - ϑ");}?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'temperature',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                if (Math.round(value) === value) {
                                                                    return '  ' + value + '<?php if ($sensor4_is_current == true) { echo ' A'; } else { echo ' °C'; }?>' + '  ';
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                            //    fontSize: 20,
                                                            //max: 25000,
                                                            beginAtZero: true,
                                                            maxTicksLimit: 10,
                                                            //max: <?php if ($sensor4_is_current == true) { echo '2'; } else { echo '30'; }?>, 
                                                            //min: <?php if ($sensor4_is_current == true) { echo '0'; } else { echo '-1'; }?>
															//
															max: <?php
															$max_value_thermometer4 = intval(max($non_empties = array_filter($thermometer4_dataset))/1) * 1 + 1;
                                                            print $max_value_thermometer4;
                                                            ?>,
                                                            //min: -2
															min: <?php														
                                                            $min_value_thermometer4 = intval(min($non_empties = array_filter($thermometer4_dataset))/2) * 2 - 0;
															print $min_value_thermometer4;	
															?>	
                                                        }
                                                    },
                                                    {
                                                        scaleLabel: {
                                                            display: true,
                                                        //    labelString: '<?php if ($sensor4_is_current == true) { echo _("Current"); } else { echo _("temperature") . '4';}?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'temperature_right4',
                                                        type: 'linear',
                                                        position: 'right',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                if (Math.round(value) === value) {
                                                                    return '  ' + value + '<?php if ($sensor4_is_current == true) { echo ' A'; } else { echo ' °C'; }?>' + '  ';
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                            //    fontSize: 20,
                                                            //max: 25000,
                                                            beginAtZero: true,
                                                            maxTicksLimit: 10,
                                                            //max: <?php if ($sensor4_is_current == true) { echo '2'; } else { echo '30'; }?>, 
                                                            //min: <?php if ($sensor4_is_current == true) { echo '0'; } else { echo '-1'; }?>
															//
															max: <?php
															$max_value_thermometer4 = intval(max($non_empties = array_filter($thermometer4_dataset))/1) * 1 + 1;
                                                            print $max_value_thermometer4;
                                                            ?>,
                                                            //min: -2
															min: <?php														
                                                            $min_value_thermometer4 = intval(min($non_empties = array_filter($thermometer4_dataset))/2) * 2 - 0;
															print $min_value_thermometer4;	
															?>	
														}
                                                    }]
                                                }
                                            }
                                        };
                                                                                                                        
                                                                                                                        
                                        // licht
                                        var light_chart = document.getElementById("light_chart");
                                        var config_light_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php echo $light_timestamps_axis_text; ?>,
                                                datasets: [{
                                                    label: '<?php echo _("light"); ?>',
                                                    yAxisID: 'status',
                                                    data: <?php echo json_encode($light_dataset);?>,
                                                    backgroundColor: '#FFBF00',
                                                    borderColor: '#FFBF00',
                                                    borderWidth: 0.1,
                                                    radius: 0,
                                                    pointRadius: 0,
                                                    pointHitRadius: 0,
                                                    hoverRadius: 0,
                                                    hoverBorderWidth: 0,
                                                    steppedLine: true,
                                                    fill: true
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("light"); ?>',
                                                    fontSize: 24
                                                },
                                                tooltips: {
                                                    enabled: false
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
														}
                                                    }, ],
                                                    yAxes: [{
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: '<?php echo _("status"); ?>',
                                                        //   fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'status',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '        ' + '<?php echo _('on'); ?>' + '  ';
                                                                            break;
                                                                    case 0: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                    default: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                        //    fontSize: 20,
                                                            max: 1,
                                                            min: 0,
                                                            //stepSize: 1
                                                        }
                                                    }, {
                                                        scaleLabel: {
                                                           display: true,
                                                           labelString: '<?php echo _(" ") ?>',
                                                           fontColor: '#000000'
                                                        }, 
                                                        display: true,
                                                        position: 'right',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '  ' + '<?php echo _('on'); ?>' + '        ';
                                                                            break;
                                                                    case 0: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                    default: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                            max: 1,
                                                            min: 0
                                                        }
                                                    }]
                                                }
                                            }
                                        };
                                        
										// uv
                                        var uv_chart = document.getElementById("uv_chart");
                                        var config_uv_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php echo $uv_light_timestamps_axis_text; ?>,
                                                datasets: [{
                                                    label: '<?php echo _("uv-light"); ?>',
                                                    yAxisID: 'status',
                                                    data: <?php echo json_encode($uv_light_dataset);?>,
                                                    backgroundColor: '#A801FB',
                                                    borderColor: '#A801FB',
                                                    borderWidth: 0.1,
                                                    radius: 0,
                                                    pointRadius: 0,
                                                    pointHitRadius: 0,
                                                    hoverRadius: 0,
                                                    hoverBorderWidth: 0,
                                                    steppedLine: true,
                                                    fill: true
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("uv-light"); ?>',
                                                    fontSize: 24
                                                },
                                                tooltips: {
                                                    enabled: false
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
														}														
                                                    }, ],
                                                    yAxes: [{
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: '<?php echo _("status"); ?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'status',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '        ' + '<?php echo _('on'); ?>' + '  ';
                                                                            break;
                                                                    case 0: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                    default: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                        //    fontSize: 20,
                                                            max: 1,
                                                            min: 0,
                                                            stepSize: 1
                                                        }
                                                    }, {
                                                         scaleLabel: {
                                                           display: true,
                                                           labelString: '<?php echo _(" ") ?>',
                                                           fontColor: '#000000'
                                                        }, 
                                                        display: true,
                                                        position: 'right',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '  ' + '<?php echo _('on'); ?>' + '        ';
                                                                            break;
                                                                    case 0: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                    default: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                            max: 1,
                                                            min: 0
                                                        }
                                                    }]
                                                }
                                            }
                                        };
                                        
										// heater
                                        var heater_chart = document.getElementById("heater_chart");
                                        var config_heater_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php echo $heater_timestamps_axis_text; ?>,
                                                datasets: [{
                                                    label: '<?php echo _("heater"); ?>',
                                                    yAxisID: 'status',
                                                    data: <?php echo json_encode($heater_dataset);?>,
                                                    backgroundColor: '#C03738',
                                                    borderColor: '#C03738',
                                                    borderWidth: 0.1,
                                                    radius: 0,
                                                    pointRadius: 0,
                                                    pointHitRadius: 0,
                                                    hoverRadius: 0,
                                                    hoverBorderWidth: 0,
                                                    steppedLine: true,
                                                    fill: true
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("heater"); ?>',
                                                    fontSize: 24
                                                },
                                                tooltips: {
                                                    enabled: false
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
														}														
                                                    }, ],
                                                    yAxes: [{
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: '<?php echo _("status"); ?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'status',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '        ' + '<?php echo _('on'); ?>' + '  ';
                                                                            break;
                                                                    case 0: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                    default: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                        //    fontSize: 20,
                                                            max: 1,
                                                            min: 0,
                                                            stepSize: 1
                                                        }
                                                    }, {
                                                         scaleLabel: {
                                                           display: true,
                                                           labelString: '<?php echo _(" ") ?>',
                                                           fontColor: '#000000'
                                                        }, 
                                                        display: true,
                                                        position: 'right',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '  ' + '<?php echo _('on'); ?>' + '        ';
                                                                            break;
                                                                    case 0: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                    default: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                            max: 1,
                                                            min: 0
                                                        }
                                                    }]
                                                }
                                            }
                                        };
                                        
										// cooler
                                        var cooler_chart = document.getElementById("cooler_chart");
                                        var config_cooler_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php echo $cooler_timestamps_axis_text; ?>,
                                                datasets: [{
                                                    label: '<?php echo _("cooler"); ?>',
                                                    yAxisID: 'status',
                                                    data: <?php echo json_encode($cooler_dataset);?>,
                                                    backgroundColor: '#59A9C4',
                                                    borderColor: '#59A9C4',
                                                    borderWidth: 0.1,
                                                    radius: 0,
                                                    pointRadius: 0,
                                                    pointHitRadius: 0,
                                                    hoverRadius: 0,
                                                    hoverBorderWidth: 0,
                                                    steppedLine: true,
                                                    fill: true
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("cooler"); ?>',
                                                    fontSize: 24
                                                },
                                                tooltips: {
                                                    enabled: false
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
														}														
                                                    }, ],
                                                    yAxes: [{
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: '<?php echo _("status"); ?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'status',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '        ' + '<?php echo _('on'); ?>' + '  ';
                                                                            break;
                                                                    case 0: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                    default: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                        //    fontSize: 20,
                                                            max: 1,
                                                            min: 0,
                                                            stepSize: 1
                                                        }
                                                    }, {
                                                         scaleLabel: {
                                                           display: true,
                                                           labelString: '<?php echo _(" ") ?>',
                                                           fontColor: '#000000'
                                                        }, 
                                                        display: true,
                                                        position: 'right',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '  ' + '<?php echo _('on'); ?>' + '        ';
                                                                            break;
                                                                    case 0: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                    default: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                            max: 1,
                                                            min: 0
                                                        }
                                                    }]
                                                }
                                            }
                                        };
										
                                        // humidifier
                                        var humidifier_chart = document.getElementById("humidifier_chart");
                                        var config_humidifier_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php echo $humidifier_timestamps_axis_text; ?>,
                                                datasets: [{
                                                    label: '<?php echo _("humidifier"); ?>',
                                                    yAxisID: 'status',
                                                    data: <?php echo json_encode($humidifier_dataset);?>,
                                                    backgroundColor: '#CF9248',
                                                    borderColor: '#CF9248',
                                                    borderWidth: 0.1,
                                                    radius: 0,
                                                    pointRadius: 0,
                                                    pointHitRadius: 0,
                                                    hoverRadius: 0,
                                                    hoverBorderWidth: 0,
                                                    steppedLine: true,
                                                    fill: true
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("humidifier"); ?>',
                                                    fontSize: 24
                                                },
                                                tooltips: {
                                                    enabled: false
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
														}														
                                                    }, ],
                                                    yAxes: [{
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: '<?php echo _("status"); ?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'status',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '        ' + '<?php echo _('on'); ?>' + '  ';
                                                                            break;
                                                                    case 0: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                    default: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                        //    fontSize: 20,
                                                            max: 1,
                                                            min: 0,
                                                            stepSize: 1
                                                        }
                                                    }, {
                                                         scaleLabel: {
                                                           display: true,
                                                           labelString: '<?php echo _(" ") ?>',
                                                           fontColor: '#000000'
                                                        }, 
                                                        display: true,
                                                        position: 'right',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '  ' + '<?php echo _('on'); ?>' + '        ';
                                                                            break;
                                                                    case 0: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                    default: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                            max: 1,
                                                            min: 0
                                                        }
                                                    }]
                                                }
                                            }
                                        };
                                        
										// dehumidifier
                                        var dehumidifier_chart = document.getElementById("dehumidifier_chart");
                                        var config_dehumidifier_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php echo $dehumidifier_timestamps_axis_text; ?>,
                                                datasets: [{
                                                    label: '<?php echo _("dehumidifier"); ?>',
                                                    yAxisID: 'status',
                                                    data: <?php echo json_encode($dehumidifier_dataset);?>,
                                                    backgroundColor: '#CFED53',
                                                    borderColor: '#CFED53',
                                                    borderWidth: 0.1,
                                                    radius: 0,
                                                    pointRadius: 0,
                                                    pointHitRadius: 0,
                                                    hoverRadius: 0,
                                                    hoverBorderWidth: 0,
                                                    steppedLine: true,
                                                    fill: true
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("dehumidifier"); ?>',
                                                    fontSize: 24
                                                },
                                                tooltips: {
                                                    enabled: false
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
														}														
                                                    }, ],
                                                    yAxes: [{
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: '<?php echo _("status"); ?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'status',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '        ' + '<?php echo _('on'); ?>' + '  ';
                                                                            break;
                                                                    case 0: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                    default: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                        //    fontSize: 20,
                                                            max: 1,
                                                            min: 0,
                                                            stepSize: 1
                                                        }
                                                    }, {
                                                         scaleLabel: {
                                                           display: true,
                                                           labelString: '<?php echo _(" ") ?>',
                                                           fontColor: '#000000'
                                                        }, 
                                                        display: true,
                                                        position: 'right',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '  ' + '<?php echo _('on'); ?>' + '        ';
                                                                            break;
                                                                    case 0: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                    default: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                            max: 1,
                                                            min: 0
                                                        }
                                                    }]
                                                }
                                            }
                                        };
										
                                        // exhaust_air
                                        var exhaust_air_chart = document.getElementById("exhaust_air_chart");
                                        var config_exhaust_air_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php echo $exhaust_air_timestamps_axis_text; ?>,
                                                datasets: [{
                                                    label: '<?php echo _("exhaust air"); ?>',
                                                    yAxisID: 'status',
                                                    data: <?php echo json_encode($exhaust_air_dataset);?>,
                                                    backgroundColor: '#99D498',
                                                    borderColor: '#99D498',
                                                    borderWidth: 0.1,
                                                    radius: 0,
                                                    pointRadius: 0,
                                                    pointHitRadius: 0,
                                                    hoverRadius: 0,
                                                    hoverBorderWidth: 0,
                                                    steppedLine: true,
                                                    fill: true
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("exhaust air"); ?>',
                                                    fontSize: 24
                                                },
                                                tooltips: {
                                                    enabled: false
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
														}														
                                                    }, ],
                                                    yAxes: [{
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: '<?php echo _("status"); ?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'status',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '        ' + '<?php echo _('on'); ?>' + '  ';
                                                                            break;
                                                                    case 0: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                    default: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                        //    fontSize: 20,
                                                            max: 1,
                                                            min: 0,
                                                            stepSize: 1
                                                        }
                                                    }, {
                                                         scaleLabel: {
                                                           display: true,
                                                           labelString: '<?php echo _(" ") ?>',
                                                           fontColor: '#000000'
                                                        }, 
                                                        display: true,
                                                        position: 'right',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '  ' + '<?php echo _('on'); ?>' + '        ';
                                                                            break;
                                                                    case 0: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                    default: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                            max: 1,
                                                            min: 0
                                                        }
                                                    }]
                                                }
                                            }
                                        };
                                        
										// circulate air
                                        var circulation_air_chart = document.getElementById("circulation_air_chart");
                                        var config_circulation_air_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php echo $circulate_air_timestamps_axis_text; ?>,
                                                datasets: [{
                                                    label: '<?php echo _("circulate air"); ?>',
                                                    yAxisID: 'status',
                                                    data: <?php echo json_encode($circulate_air_dataset);?>,
                                                    backgroundColor: '#86CBB0',
                                                    borderColor: '#86CBB0',
                                                    borderWidth: 0.1,
                                                    radius: 0,
                                                    pointRadius: 0,
                                                    pointHitRadius: 0,
                                                    hoverRadius: 0,
                                                    hoverBorderWidth: 0,
                                                    steppedLine: true,
                                                    fill: true
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("circulate air"); ?>',
                                                    fontSize: 24
                                                },
                                                tooltips: {
                                                    enabled: false
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
														}														
                                                    }, ],
                                                    yAxes: [{
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: '<?php echo _("status"); ?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'status',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '        ' + '<?php echo _('on'); ?>' + '  ';
                                                                            break;
                                                                    case 0: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                    default: return '        ' + '<?php echo _('off'); ?>' + '  ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                        //    fontSize: 20,
                                                            max: 1,
                                                            min: 0,
                                                            stepSize: 1
                                                        }
                                                    }, {
                                                        scaleLabel: {
                                                           display: true,
                                                           labelString: '<?php echo _(" ") ?>',
                                                           fontColor: '#000000'
                                                        }, 
                                                        display: true,
                                                        position: 'right',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                switch (value) {
                                                                    case 1: return '  ' + '<?php echo _('on'); ?>' + '        ';
                                                                            break;
                                                                    case 0: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                    default: return '  ' + '<?php echo _('off'); ?>' + '        ';
                                                                            break;
                                                                }
                                                            },
                                                            fontColor: '#000000',
                                                            max: 1,
                                                            min: 0
                                                        }
                                                    }]
                                                }
                                            }
                                        };
                                        
                                        window.onload = function() {
                                            window.temperature_humidity_chart = new Chart(temperature_humidity_chart, config_temperature_humidity_chart);
                                            window.scales_chart = new Chart(scales_chart, config_scales_chart);
                                            window.thermometer1_chart = new Chart(thermometer1_chart, config_thermometer1_chart);
                                        //    window.thermometer2_chart = new Chart(thermometer2_chart, config_thermometer2_chart);
                                        //    window.thermometer3_chart = new Chart(thermometer3_chart, config_thermometer3_chart);
                                            window.thermometer4_chart = new Chart(thermometer4_chart, config_thermometer4_chart);
                                            window.light_chart = new Chart(light_chart, config_light_chart);
											window.uv_chart = new Chart(uv_chart, config_uv_chart);
                                            window.heater_chart = new Chart(heater_chart, config_heater_chart);
											window.cooler_chart = new Chart(cooler_chart, config_cooler_chart);
                                            window.humidifier_chart = new Chart(humidifier_chart, config_humidifier_chart);
											window.dehumidifier_chart = new Chart(dehumidifier_chart, config_dehumidifier_chart);
                                            window.exhaust_air_chart =  new Chart(exhaust_air_chart, config_exhaust_air_chart);
											window.circulation_air_chart =  new Chart(circulation_air_chart, config_circulation_air_chart);
                                        };
                                        // document.getElementById('hour').addEventListener('click', function() {
                                            // diagram_mode = 'hour';
                                            // window.temperature_humidity_chart.update();
                                        // });
                                        // document.getElementById('day').addEventListener('click', function() {
                                            // <?php $diagram_mode = 'day'; ?>
                                             // window.temperature_humidity_chart.update();
                                        // });                                     
                                        // document.getElementById('week').addEventListener('click', function() {
                                            // <?php $diagram_mode = 'week'; ?>
                                             // window.temperature_humidity_chart.update();
                                        // });
                                        // document.getElementById('month').addEventListener('click', function() {
                                            // <?php $diagram_mode = 'month'; ?>
                                             // window.temperature_humidity_chart.update();
                                        // });                                
                                        </script>
                                        </br>
                                        </br>
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
