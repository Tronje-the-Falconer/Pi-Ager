<?php 
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                    include 'modules/database.php';
                                ?>
                                <!----------------------------------------------------------------------------------------Was eben hier hin kommt ...-->
                                <?php 
                                    // wenn nichts anderes ausgewählt wurde, ist Stündlich ausgewählt
                                    if (isset ($_GET['diagram_mode'])) {
                                        $diagram_mode = $_GET['diagram_mode'];
                                    }else{
                                        $diagram_mode = 'hourly';
                                    }

                                ?>
                                <h2 class="art-postheader"><?php echo _('graphs'); ?></h2>
                                <div class="hg_container" style="margin-bottom: 20px; margin-top: 20px;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td><img src="images/icons/hour_42x42.png" alt=""></td>
                                            <td><img src="images/icons/daily_42x42.png" alt=""></td>
                                            <td><img src="images/icons/week_42x42.png" alt=""></td>
                                            <td><img src="images/icons/month_42x42.png" alt=""></td>
                                        </tr>
                                        <tr>
                                            <td><button class="art-button" type="button" id="hour">hour</button></td>
                                            <td><button class="art-button" type="button" id="day">day</button></td>
                                            <td><button class="art-button" type="button" id="week">week</button></td>
                                            <td><button class="art-button" type="button" id="month">month</button></td>
                                        </tr>
                                    </table>
                                </div>

                                    <div style="">
                                    
                                    <h2><?php echo _('test'); ?> </h2>
                                    <h4><?php echo _('every ~ x minutes a new value is written!'); ?></h4>
                                    <canvas class="chart"; id="temperature_humidity_chart"></canvas>
                                    
                                    <script>
                                    // Time is in miliseconds time.time (python ist in secunden)!!!!
                                    // bei Labels letzten timestamp auslesen und Stunde/Tag/Woche/monat abziehen und als ersten Wert nutzen
                                    var timeFormat = 'MM/DD/YYYY HH:mm';
                                    
                                    function newDate(days) {
                                        return moment().add(days, 'd').toDate();
                                    }

                                    function newDateString(days) {
                                        return moment().add(days, 'd').format(timeFormat);
                                    }

                                    function newTimestamp(days) {
                                        return moment().add(days, 'd').unix();
                                    }

                                    <?php 
                                        function get_defined_last_timestamp_from_array($array, $delta){
                                            sort($array);
                                            $last_timestamp_in_array = end($array);
                                            Switch ($delta){
                                                case 'hour':
                                                    $timestamp_delta = $last_timestamp_in_array - 3600;
                                                case 'day':
                                                    $timestamp_delta = $last_timestamp_in_array - 86400;
                                                case 'week':
                                                    $timestamp_delta = $last_timestamp_in_array - 604800;
                                                case 'month':
                                                    $timestamp_delta = $last_timestamp_in_array - 2629700;
                                            }
                                        }
                                        
                                        list ($temperature_dataset, $temperature_timestamps) = get_diagram_values($data_sensor_temperature_table);
                                        list ($humidity_dataset, $humidity_timestamps) = get_diagram_values($data_sensor_humidity_table);
                                        
                                        $last_timestamp_temperature = end($temperature_timestamps);
                                        
                                                    
                                        $timestamp_temperature_last_hour = get_defined_last_timestamp_from_array($temperature_timestamps, 'hour');
                                        $timestamp_temperature_last_day = get_defined_last_timestamp_from_array($temperature_timestamps, 'day');
                                        $timestamp_temperature_last_week = get_defined_last_timestamp_from_array($temperature_timestamps, 'week');
                                        $timestamp_temperature_last_month = get_defined_last_timestamp_from_array($temperature_timestamps, 'month');
                                    ?>
                                    
                                    var ctx = document.getElementById("temperature_humidity_chart");
                                    var temperature_humidity_chart = new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            ////Siehe Anmerkung oben!
                                            labels: [
                                                <?php
                                                    foreach ($temperature_timestamps as $timestamp){
                                                        if ($timestamp >= $timestamp_temperature_last_hour){
                                                            print 'new Date(' . $timestamp . '000),';
                                                        }
                                                        
                                                    }
                                                ?>
                                                // new Date(<?php print $timestamp_temperature_last_hour . '000'?>),
                                                // new Date(1511868589000),
                                                // new Date(<?php print $last_timestamp_temperature . '000' ?>)
                                            ],
                                            datasets: [{
                                                label: 'temperature',
                                                yAxisID: 'temperature',
                                                data: <?php echo json_encode($temperature_dataset);?>,
                                                // data: [12, 19, 3, 5, 2, 3],
                                                // data: [{
                                                        // x: new Date(1508245175000),
                                                        // y: 5
                                                    // }, {
                                                        // x: new Date(1508264866000),
                                                        // y: 3
                                                    // }, {
                                                        // x: new Date(1508412821000),
                                                        // y: 2
                                                    // }, {
                                                        // x: new Date(1508416968000),
                                                        // y: 1
                                                    // }, {
                                                        // x: new Date(1508494250000),
                                                        // y: -1
                                                    // }
                                                // ],
                                                backgroundColor: [
                                                    '#FF0000'
                                                ],
                                                borderColor: [
                                                    '#FF0000'
                                                ],
                                                borderWidth: 2,
                                                cubicInterpolationMode: 'monotone',
                                                fill: false
                                            },
                                            {
                                                label: 'humidity',
                                                yAxisID: 'humidity',
                                                data: <?php echo json_encode($humidity_dataset); ?>,
                                                // data: [50, 41, 60, 80, 100, 96],
                                                // data: [{
                                                        // x: new Date(1508245175000),
                                                        // y: 70
                                                    // }, {
                                                        // x: new Date(1508264866000),
                                                        // y: 75
                                                    // }, {
                                                        // x: new Date(1508412821000),
                                                        // y: 80
                                                    // }, {
                                                        // x: new Date(1508416968000),
                                                        // y: 90
                                                    // }, {
                                                        // x: new Date(1508494250000),
                                                        // y: 100
                                                    // }
                                                // ],
                                                backgroundColor: [
                                                    '#2E2EFE'
                                                ],
                                                borderColor: [
                                                    '#2E2EFE'
                                                ],
                                                borderWidth: 2,
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
                                            scales: {
                                                // xAxes: [{
                                                    // time: {
                                                        // unit: 'month'
                                                    // },
                                                    // ticks: {
                                                        // fontSize: 20
                                                    // }
                                                // }],
                                                xAxes: [{
                                                    type: "time",
                                                    time: {
                                                        format: timeFormat,
                                                        // round: 'day'
                                                        tooltipFormat: 'll HH:mm'
                                                    },
                                                }, ],
                                                yAxes: [{
                                                    scaleLabel: {
                                                        display: true,
                                                        labelString: '<?php echo _("temperature") ?>',
                                                        fontSize: 20,
                                                        fontColor: '#FF0000'
                                                    },
                                                    id: 'temperature',
                                                    type: 'linear',
                                                    position: 'left',
                                                    ticks: {
                                                        callback: function(value, index, values) {
                                                            return value + ' °C';
                                                        },
                                                        fontColor: '#FF0000',
                                                        fontSize: 20,
                                                        max: 30,
                                                        min: -4
                                                    }
                                                    
                                                }, {
                                                    scaleLabel: {
                                                        display: true,
                                                        labelString: '<?php echo _("humidity") ?>',
                                                        fontSize: 20,
                                                        fontColor: '#2E2EFE'
                                                    },
                                                    id: 'humidity',
                                                    type: 'linear',
                                                    display: true,
                                                    position: 'right',
                                                    labelString: 'humidity',
                                                    ticks: {
                                                        callback: function(value, index, values) {
                                                            return 'φ ' + value + ' %';
                                                        },
                                                        fontColor: '#2E2EFE',
                                                        fontSize: 20,
                                                        max: 110,
                                                        min: 40
                                                    }
                                                }]
                                            }
                                        }
                                    });
                                    
                                    document.getElementById('hour').addEventListener('click', function() {
                                        // if (config.data.datasets.length > 0) {
                                            // config.data.labels.push(newDate(config.data.labels.length));

                                            // for (var index = 0; index < config.data.datasets.length; ++index) {
                                                // if (typeof config.data.datasets[index].data[0] === "object") {
                                                    // config.data.datasets[index].data.push({
                                                        // x: newDate(config.data.datasets[index].data.length),
                                                        // y: randomScalingFactor(),
                                                    // });
                                                // } else {
                                                    // config.data.datasets[index].data.push(randomScalingFactor());
                                                // }
                                            // }

                                            // window.myLine.update();
                                        // }
                                    });
                                    document.getElementById('day').addEventListener('click', function() {
                                        // if (config.data.datasets.length > 0) {
                                            // config.data.labels.push(newDate(config.data.labels.length));

                                            // for (var index = 0; index < config.data.datasets.length; ++index) {
                                                // if (typeof config.data.datasets[index].data[0] === "object") {
                                                    // config.data.datasets[index].data.push({
                                                        // x: newDate(config.data.datasets[index].data.length),
                                                        // y: randomScalingFactor(),
                                                    // });
                                                // } else {
                                                    // config.data.datasets[index].data.push(randomScalingFactor());
                                                // }
                                            // }

                                            // window.myLine.update();
                                        // }
                                    });
                                    document.getElementById('week').addEventListener('click', function() {
                                        // if (config.data.datasets.length > 0) {
                                            // config.data.labels.push(newDate(config.data.labels.length));

                                            // for (var index = 0; index < config.data.datasets.length; ++index) {
                                                // if (typeof config.data.datasets[index].data[0] === "object") {
                                                    // config.data.datasets[index].data.push({
                                                        // x: newDate(config.data.datasets[index].data.length),
                                                        // y: randomScalingFactor(),
                                                    // });
                                                // } else {
                                                    // config.data.datasets[index].data.push(randomScalingFactor());
                                                // }
                                            // }

                                            // window.myLine.update();
                                        // }
                                    });
                                    document.getElementById('month').addEventListener('click', function() {
                                        // if (config.data.datasets.length > 0) {
                                            // config.data.labels.push(newDate(config.data.labels.length));

                                            // for (var index = 0; index < config.data.datasets.length; ++index) {
                                                // if (typeof config.data.datasets[index].data[0] === "object") {
                                                    // config.data.datasets[index].data.push({
                                                        // x: newDate(config.data.datasets[index].data.length),
                                                        // y: randomScalingFactor(),
                                                    // });
                                                // } else {
                                                    // config.data.datasets[index].data.push(randomScalingFactor());
                                                // }
                                            // }

                                            // window.myLine.update();
                                        // }
                                    });
                                    </script>
                                    
                                    <h2><?php echo _('temperature profile'); ?> </h2>
                                    <img src="/images/graphs/pi-ager_sensor_temperature-<?php echo $diagram_mode; ?>.png" alt="<?php echo $diagram_mode; ?>" />
                                    <h2><?php echo _('humidity profile'); ?></h2>
                                    <img src="/images/graphs/pi-ager_sensor_humidity-<?php echo $diagram_mode; ?>.png" alt="<?php echo $diagram_mode; ?>" />
                                    <h2><?php echo _('scale1 profile'); ?></h2>
                                    <img src="/images/graphs/pi-ager_scale1_data-<?php echo $diagram_mode; ?>.png" alt="<?php echo $diagram_mode; ?>" />
                                    <h2><?php echo _('scale2 profile'); ?></h2>
                                    <img src="/images/graphs/pi-ager_scale2_data-<?php echo $diagram_mode; ?>.png" alt="<?php echo $diagram_mode; ?>" />
                                    <h2><?php echo _('cooler'); ?></h2>
                                    <img src="/images/graphs/pi-ager_stat_coolcompressor-<?php echo $diagram_mode; ?>.png" alt="<?php echo $diagram_mode; ?>" />
                                    <h2><?php echo _('heater'); ?></h2>
                                    <img src="/images/graphs/pi-ager_stat_heater-<?php echo $diagram_mode; ?>.png" alt="<?php echo $diagram_mode; ?>" />
                                    <h2><?php echo _('humidifier'); ?></h2>
                                    <img src="/images/graphs/pi-ager_status_humidifier-<?php echo $diagram_mode; ?>.png" alt="<?php echo $diagram_mode; ?>" />
                                    <h2><?php echo _('air circulatory system'); ?></h2>
                                    <img src="/images/graphs/pi-ager_stat_circulate_air-<?php echo $diagram_mode; ?>.png" alt="<?php echo $diagram_mode; ?>" />
                                    <h2><?php echo _('air exchanger'); ?></h2>
                                    <img src="/images/graphs/pi-ager_stat_exhaust_air-<?php echo $diagram_mode; ?>.png" alt="<?php echo $diagram_mode; ?>" />
                                    <h2><?php echo _('uv'); ?></h2>
                                    <img src="/images/graphs/pi-ager_status_uv-<?php echo $diagram_mode; ?>.png" alt="<?php echo $diagram_mode; ?>" />
                                    <h2><?php echo _('light'); ?></h2>
                                    <img src="/images/graphs/pi-ager_status_light-<?php echo $diagram_mode; ?>.png" alt="<?php echo $diagram_mode; ?>" />
                                    <h2><?php echo _('dehumidifier'); ?></h2>
                                    <img src="/images/graphs/pi-ager_status_dehumidifier-<?php echo $diagram_mode; ?>.png" alt="<?php echo $diagram_mode; ?>" />
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
