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
                                        }
                                    }
                                    $diagram_mode_translated = get_translated_diagram_mode($diagram_mode);
                                ?>
                                <h2 class="art-postheader"><?php echo _('graphs') . ' ' . $diagram_mode_translated; ?></h2>
                                <div class="hg_container" style="margin-bottom: 20px; margin-top: 20px;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td><img src="images/icons/hour_42x42.png" alt=""></td>
                                            <td><img src="images/icons/daily_42x42.png" alt=""></td>
                                            <td><img src="images/icons/week_42x42.png" alt=""></td>
                                            <td><img src="images/icons/month_42x42.png" alt=""></td>
                                        </tr>
                                        <tr>
                                            <td><a href="diagrams.php?diagram_mode=hour" class="art-button"><?php echo _('hour'); ?></a></td>
                                            <td><a href="diagrams.php?diagram_mode=day" class="art-button"><?php echo _('day'); ?></a></td>
                                            <td><a href="diagrams.php?diagram_mode=week" class="art-button"><?php echo _('week'); ?></a></td>
                                            <td><a href="diagrams.php?diagram_mode=month" class="art-button"><?php echo _('month'); ?></a></td>
                                        </tr>
<!--                                        <tr>
                                            <td><button class="art-button" type="button" id="hour">hour</button></td>
                                            <td><button class="art-button" type="button" id="day">day</button></td>
                                            <td><button class="art-button" type="button" id="week">week</button></td>
                                            <td><button class="art-button" type="button" id="month">month</button></td>
                                        </tr>
-->                                 </table>
                                </div>

                                    <div style="">
                                        <h4><?php echo _('every ~ x minutes a new value is written!'); ?></h4>
                                        <?php
                                            include 'modules/read_values_for_diagrams.php';
                                        ?>
                                        <canvas class="chart"; id="temperature_humidity_chart"></canvas>
                                        <canvas class="chart"; id="scales_chart"></canvas>
                                        <canvas class="chart"; id="light_uv_chart"></canvas>
                                        <canvas class="chart"; id="heater_cooler_chart"></canvas>
                                        <canvas class="chart"; id="humidifier_dehumidifier_chart"></canvas>
                                        <canvas class="chart"; id="air_exchange_air_circulation_chart"></canvas>
                                        <script>
                                        var timeFormat = 'MM/DD/YYYY HH:mm';
                                        
                                        // Temperatur und Feuchte
                                        var temperature_humidity_chart = document.getElementById("temperature_humidity_chart");
                                        var config_temperature_humidity_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php
                                                    echo $temperature_timestamps_axis_text;
                                                    // print '[';
                                                        // foreach ($temperature_timestamps_axis as $timestamp){
                                                                // print 'new Date(' . $timestamp . '000),';
                                                            // }
                                                    // print ']';
                                                    ?>,
                                                datasets: [{
                                                    label: '<?php echo _("temperature") ?>',
                                                    yAxisID: 'temperature',
                                                    data: <?php echo json_encode($temperature_dataset);?>,
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
                                                    label: '<?php echo _("humidity") ?>',
                                                    yAxisID: 'humidity',
                                                    data: <?php echo json_encode($humidity_dataset); ?>,
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
                                                        labelString: '<?php echo _("humidity") ?>',
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
                                        };
                                        
                                        // Waagen
                                        var scales_chart = document.getElementById("scales_chart");
                                        var config_scales_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php
                                                    print '[';
                                                        foreach ($temperature_timestamps_axis as $timestamp){
                                                                print 'new Date(' . $timestamp . '000),';
                                                            }
                                                    print ']';
                                                    ?>,
                                                datasets: [{
                                                    label: '<?php echo _("scale") ?> 1',
                                                    yAxisID: 'gram',
                                                    data: <?php echo json_encode($temperature_dataset);?>,
                                                    backgroundColor: [
                                                        '#DDB929'
                                                    ],
                                                    borderColor: [
                                                        '#DDB929'
                                                    ],
                                                    borderWidth: 2,
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                },
                                                {
                                                    label: '<?php echo _("scale") ?> 2',
                                                    yAxisID: 'gram',
                                                    data: <?php echo json_encode($humidity_dataset); ?>,
                                                    backgroundColor: [
                                                        '#1EB623'
                                                    ],
                                                    borderColor: [
                                                        '#1EB623'
                                                    ],
                                                    borderWidth: 2,
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                }]
                                            },
                                            options: {
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("scale") ?> 1 & 2',
                                                    fontSize: 24
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
                                                            labelString: '<?php echo _("gram"); ?>',
                                                            fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'gram',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                return value + ' gr';
                                                            },
                                                            fontColor: '#000000',
                                                            fontSize: 20,
                                                            max: 25000,
                                                            min: -0.5
                                                        }
                                                        
                                                    }]
                                                }
                                            }
                                        };
                                        
                                        
                                        // uv und licht
                                        var light_uv_chart = document.getElementById("light_uv_chart");
                                        var config_light_uv_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php
                                                    print '[';
                                                        foreach ($temperature_timestamps_axis as $timestamp){
                                                                print 'new Date(' . $timestamp . '000),';
                                                            }
                                                    print ']';
                                                    ?>,
                                                datasets: [{
                                                    label: '<?php echo _("uv-light"); ?>',
                                                    yAxisID: 'status',
                                                    data: [0,0,1,1,0,1,0],
                                                    //data: <?php echo json_encode($temperature_dataset);?>,
                                                    backgroundColor: '#DDB929',
                                                    borderColor: '#DDB929',
                                                    borderWidth: 5,
                                                    steppedLine: true,
                                                    fill: true
                                                },
                                                {
                                                    label: '<?php echo _("light"); ?>',
                                                    yAxisID: 'status',
                                                    data: [0,1,0,1,0,1,0],
                                                    //data: <?php echo json_encode($humidity_dataset); ?>,
                                                    backgroundColor: '#1EB623',
                                                    borderColor: '#1EB623',
                                                    borderWidth: 5,
                                                    steppedLine: true,
                                                    fill: true
                                                }]
                                            },
                                            options: {
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("uv light") . ' & ' . _("light"); ?>',
                                                    fontSize: 24
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
                                                            labelString: '<?php echo _("status"); ?>',
                                                            fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'status',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                return value;
                                                            },
                                                            fontColor: '#000000',
                                                            fontSize: 20,
                                                            max: 1,
                                                            min: 0,
                                                            stepSize: 1
                                                        }
                                                        
                                                    }]
                                                }
                                            }
                                        };
                                        
                                        // heater und cooler
                                        var heater_cooler_chart = document.getElementById("heater_cooler_chart");
                                        var config_heater_cooler_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php
                                                    print '[';
                                                        foreach ($temperature_timestamps_axis as $timestamp){
                                                                print 'new Date(' . $timestamp . '000),';
                                                            }
                                                    print ']';
                                                    ?>,
                                                datasets: [{
                                                    label: '<?php echo _("heater"); ?>',
                                                    yAxisID: 'status',
                                                    //data: <?php echo json_encode($temperature_dataset);?>,
                                                    data: [0,0,1,1,0,1,0],
                                                    backgroundColor: [
                                                        '#DDB929'
                                                    ],
                                                    borderColor: [
                                                        '#DDB929'
                                                    ],
                                                    borderWidth: 5,
                                                    steppedLine: true,
                                                    fill: false
                                                },
                                                {
                                                    label: '<?php echo _("cooler"); ?>',
                                                    yAxisID: 'status',
                                                    data: [0,1,0,1,0,1,0],
                                                    //data: <?php echo json_encode($humidity_dataset); ?>,
                                                    backgroundColor:  '#1EB623',
                                                    borderColor: '#1EB623',
                                                    borderWidth: 5,
                                                    steppedLine: true,
                                                    fill: false
                                                }]
                                            },
                                            options: {
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("heater") . ' & ' . _("cooler"); ?>',
                                                    fontSize: 24
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
                                                            labelString: '<?php echo _("status"); ?>',
                                                            fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'status',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                return value;
                                                            },
                                                            fontColor: '#000000',
                                                            fontSize: 20,
                                                            max: 1,
                                                            min: 0,
                                                            stepSize: 1
                                                        }
                                                        
                                                    }]
                                                }
                                            }
                                        };
                                        
                                        // humidifier und dehumidifier
                                        var humidifier_dehumidifier_chart = document.getElementById("humidifier_dehumidifier_chart");
                                        var config_humidifier_dehumidifier_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php
                                                    print '[';
                                                        foreach ($temperature_timestamps_axis as $timestamp){
                                                                print 'new Date(' . $timestamp . '000),';
                                                            }
                                                    print ']';
                                                    ?>,
                                                datasets: [{
                                                    label: '<?php echo _("humidifier"); ?>',
                                                    yAxisID: 'status',
                                                    data: <?php echo json_encode($temperature_dataset);?>,
                                                    backgroundColor: [
                                                        '#DDB929'
                                                    ],
                                                    borderColor: [
                                                        '#DDB929'
                                                    ],
                                                    borderWidth: 5,
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                },
                                                {
                                                    label: '<?php echo _("dehumidifier"); ?>',
                                                    yAxisID: 'status',
                                                    data: <?php echo json_encode($humidity_dataset); ?>,
                                                    backgroundColor: [
                                                        '#1EB623'
                                                    ],
                                                    borderColor: [
                                                        '#1EB623'
                                                    ],
                                                    borderWidth: 5,
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                }]
                                            },
                                            options: {
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("humidifier") . ' & ' . _("dehumidifier"); ?>',
                                                    fontSize: 24
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
                                                            labelString: '<?php echo _("status"); ?>',
                                                            fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'status',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                return value;
                                                            },
                                                            fontColor: '#000000',
                                                            fontSize: 20,
                                                            max: 1,
                                                            min: 0,
                                                            stepSize: 1
                                                        }
                                                        
                                                    }]
                                                }
                                            }
                                        };
                                        
                                        // air exchange und air circulation
                                        var air_exchange_air_circulation_chart = document.getElementById("air_exchange_air_circulation_chart");
                                        var config_air_exchange_air_circulation_chart = {
                                            type: 'line',
                                            data: {
                                                labels: 
                                                    <?php
                                                    print '[';
                                                        foreach ($temperature_timestamps_axis as $timestamp){
                                                                print 'new Date(' . $timestamp . '000),';
                                                            }
                                                    print ']';
                                                    ?>,
                                                datasets: [{
                                                    label: '<?php echo _("air exchange"); ?>',
                                                    yAxisID: 'status',
                                                    data: <?php echo json_encode($temperature_dataset);?>,
                                                    backgroundColor: [
                                                        '#DDB929'
                                                    ],
                                                    borderColor: [
                                                        '#DDB929'
                                                    ],
                                                    borderWidth: 5,
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                },
                                                {
                                                    label: '<?php echo _("air circulation"); ?>',
                                                    yAxisID: 'status',
                                                    data: <?php echo json_encode($humidity_dataset); ?>,
                                                    backgroundColor: [
                                                        '#1EB623'
                                                    ],
                                                    borderColor: [
                                                        '#1EB623'
                                                    ],
                                                    borderWidth: 5,
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                }]
                                            },
                                            options: {
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("air exchange") . ' & ' . _("air circulation"); ?>',
                                                    fontSize: 24
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
                                                            labelString: '<?php echo _("status"); ?>',
                                                            fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'status',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                return value;
                                                            },
                                                            fontColor: '#000000',
                                                            fontSize: 20,
                                                            max: 1,
                                                            min: 0,
                                                            stepSize: 1
                                                        }
                                                        
                                                    }]
                                                }
                                            }
                                        };
                                        
                                        
                                        window.onload = function() {
                                            window.temperature_humidity_chart = new Chart(temperature_humidity_chart, config_temperature_humidity_chart);
                                            window.scales_chart = new Chart(scales_chart, config_scales_chart);
                                            window.light_uv_chart = new Chart(light_uv_chart, config_light_uv_chart);
                                            window.heater_cooler_chart = new Chart(heater_cooler_chart, config_heater_cooler_chart);
                                            window.humidifier_dehumidifier_chart = new Chart(humidifier_dehumidifier_chart, config_humidifier_dehumidifier_chart);
                                            window.air_exchange_air_circulation_chart =  new Chart(air_exchange_air_circulation_chart, config_air_exchange_air_circulation_chart);
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
