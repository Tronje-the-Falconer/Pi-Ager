<?php
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                    include 'modules/database.php';
                                    include 'modules/logging.php';                            //liest die Datei fuer das logging ein
                                    // include 'modules/write_customtime_db.php';                        //speichert die individuelle Zeit für die Diagramme
                                ?>
                                <!----------------------------------------------------------------------------------------Was eben hier hin kommt ...-->
                                <?php
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
                                    $diagram_modus_index = get_table_value($config_settings_table, $diagram_modus_key);
                                    if ($diagram_modus_index === NULL) {
                                        $diagram_modus_index = 0;  // hour
                                    }
                                    $diagram_modus_names = array('hour', 'day', 'week', 'month', 'custom');
                                    $diagram_mode = $diagram_modus_names[$diagram_modus_index];
                                    // echo 'diagram_mode = ' . $diagram_mode . '<br>';

                                    $diagram_mode_translated = get_translated_diagram_mode($diagram_mode);
                                ?>
                                <h2 id="diagram_header_id" class="art-postheader"><?php echo _('diagrams') . ' - ' . $diagram_mode_translated; ?></h2>
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
                                            <td><button id="hour_button_id" class="art-button" onclick="set_mode_hour()")><?php echo _('hour'); ?></td>
                                            <td><button id="day_button_id" class="art-button" onclick="set_mode_day()"><?php echo _('day'); ?></td>
                                            <td><button id="week_button_id" class="art-button" onclick="set_mode_week()"><?php echo _('week'); ?></td>
                                            <td><button id="month_button_id" class="art-button" onclick="set_mode_month()"><?php echo _('month'); ?></td>
                                            <td><button id="custom_button_id" class="art-button" onclick="set_mode_custom()"><?php echo _('custom'); ?></td>                                        
                                        </tr>
                                    </table>
                                    
                                    <?php

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
                                    ?>

                                    <div id="customtime_setup_id" <?php if ($diagram_mode != 'custom') { echo ' style="display: none";';}?>>       
                                            <hr>
                                            <form id="queryformid" name="change_customtime">
                                                <table style="width: 100%;">
                                                    <tr>
                                                        <td><?php echo _('months'); ?></td>
                                                        <td><?php echo _('day'); ?></td>
                                                        <td><?php echo _('hour'); ?></td>
                                                        <td><?php echo _('minutes'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input name="months" type="number" min="0" max="11" step="1" style="width: 90%; text-align: right;" value = <?php echo $months; ?>></td>
                                                        <td><input name="days" type="number" min="0" max="30" step="1" style="width: 90%; text-align: right;" value = <?php echo $days; ?>></td>
                                                        <td><input name="hours" type="number" min="0" max="23" step="1" style="width: 90%; text-align: right;" value = <?php echo $hours; ?>></td>
                                                        <td><input name="minutes" type="number" min="0" max="59" step="1" style="width: 90%; text-align: right;" value = <?php echo $minutes; ?>></td>
                                                    </tr>
                                                </table>
                                                <br>
                                                <button id="change_customtime_id" class="art-button" name="change_customtime" value="change_customtime"><?php echo _('change');?> </button>
                                            </form>
                                    </div>

                                    <script>
                                    // this is to avoid form refresh after change customtime button is pushed
                                    $("#change_customtime_id").click(function() {
                                        var url = "modules/querywcdb.php"; // the script where you handle the form input.

                                        $.ajax({
                                            type: "POST",
                                            url: url,
                                            data: $("#queryformid").serialize(), // serializes the form's elements.
                                            success: function(data)
                                            {
                                                alert(data); // show response from the php script.
                                                loadContentAlldg();    // refresh charts
                                            }
                                        });

                                        return false; // avoid to execute the actual submit of the form.
                                    });                                    
                                    
                                    async function set_mode_hour() {
                                        $('#customtime_setup_id').hide(500);
                                        
                                        $.ajax({
                                            method: 'POST',
                                            url: 'modules/save_diagram_mode.php?q=hour',
                                        })
                                        .done(function( msg ) {
                                            console.log(msg);
                                            $('#diagram_header_id').html(msg);
                                            loadContentAlldg();    // refresh charts
                                        })
                                        .fail(function(xhr, textStatus) {
                                            console.log( "diagram mode set to hour failed: " + xhr.statusText);
                                            console.log(textStatus);
                                        });
                                        return;
                                    }
                                    
                                    async function set_mode_day() {
                                        $('#customtime_setup_id').hide(500);
                                        $.ajax({
                                            method: 'POST',
                                            url: 'modules/save_diagram_mode.php?q=day',
                                        })
                                        .done(function( msg ) {
                                            console.log(msg);
                                            $('#diagram_header_id').html(msg);
                                            loadContentAlldg();    // refresh charts
                                        })
                                        .fail(function(xhr, textStatus) {
                                            console.log( "diagram mode set to day failed: " + xhr.statusText);
                                            console.log(textStatus);
                                        });
                                        return;
                                    }
                                    
                                    async function set_mode_week() {
                                        $('#customtime_setup_id').hide(500);
                                        $.ajax({
                                            method: 'POST',
                                            url: 'modules/save_diagram_mode.php?q=week',
                                        })
                                        .done(function( msg ) {
                                            console.log(msg);
                                            $('#diagram_header_id').html(msg);
                                            loadContentAlldg();    // refresh charts
                                        })
                                        .fail(function(xhr, textStatus) {
                                            console.log( "diagram mode set to week failed: " + xhr.statusText);
                                            console.log(textStatus);
                                        });
                                        return;
                                    } 
                                    
                                    async function set_mode_month() {
                                        $('#customtime_setup_id').hide(500);
                                        $.ajax({
                                            method: 'POST',
                                            url: 'modules/save_diagram_mode.php?q=month',
                                        })
                                        .done(function( msg ) {
                                            console.log(msg);
                                            $('#diagram_header_id').html(msg);
                                            loadContentAlldg();    // refresh charts
                                        })
                                        .fail(function(xhr, textStatus) {
                                            console.log( "diagram mode set to month failed: " + xhr.statusText);
                                            console.log(textStatus);
                                        });
                                        return;
                                    }
                                    
                                    async function set_mode_custom() {
                                        $('#customtime_setup_id').show(500);
                                        $.ajax({
                                            method: 'POST',
                                            url: 'modules/save_diagram_mode.php?q=custom',
                                        })
                                        .done(function( msg ) {
                                            console.log(msg);
                                            $('#diagram_header_id').html(msg);
                                            loadContentAlldg();    // refresh charts
                                        })
                                        .fail(function(xhr, textStatus) {
                                            console.log( "diagram mode set to custom failed: " + xhr.statusText);
                                            console.log(textStatus);
                                        });
                                        return;
                                    }
                                    
                                    </script>
                                                                        
                                </div>

                                    <div style="">
                                        <h4><?php 
					                        include 'modules/chartsdata_diagrams.php';
											$temperatur_humidity_saving_period = ceil (2 *($save_temperature_humidity_loops * 10 /60)) / 2;
											echo _('storage interval of the data in the database');
							                echo ': ~ ' . $temperatur_humidity_saving_period . ' ';
											echo _('minutes'); 
										    ?></h4>
                                        <canvas id="temperature_humidity_chart_id"></canvas>
                                        <div class="on_off_chart"><canvas id="cooler_chart_id"></canvas></div>
                                        <div class="on_off_chart"><canvas id="heater_chart_id"></canvas></div>
                                        <div class="on_off_chart"><canvas id="humidifier_chart_id"></canvas></div>
                                        <div class="on_off_chart"><canvas id="dehumidifier_chart_id"></canvas></div>
                                        <div class="on_off_chart"><canvas id="circulation_air_chart_id"></canvas></div>
                                        <div class="on_off_chart"><canvas id="exhaust_air_chart_id"></canvas></div>
                                        <div class="on_off_chart"><canvas id="uv_chart_id"></canvas></div>
                                        <div class="on_off_chart"><canvas id="light_chart_id"></canvas></div>
                                        <canvas id="scales_chart_id"></canvas>
                                        <canvas id="thermometers_chart_id"></canvas> 
                                        <canvas id="dewpoint_humidity_chart_id"></canvas> 
                                        
                                        <script>
                                        var timeFormat = 'MM/DD/YYYY HH:mm';
                                        
                                        // Temperatur und Feuchte
                                        var temp_hum_chart_el = document.getElementById("temperature_humidity_chart_id");
                                        var config_temp_hum_chart = {
                                            type: 'line',
                                            data: {
                                                labels: [],
                                                datasets: [{
                                                    label: '<?php echo _("temperature") . ' int.' ?>',
                                                    yAxisID: 'temperature',
                                                    data: <?php echo json_encode($temperature_dataset);?>,
                                                    backgroundColor: '#FF4040',
                                                    borderColor: '#FF4040',
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
                                                    pointStyle:'rect',
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                },
                                                {
                                                    label: '<?php echo _("temperature") . ' ext.' ?>',
                                                    yAxisID: 'temperature',
                                                    data: <?php echo json_encode($extern_temperature_dataset); ?>,
                                                    backgroundColor: '#8A0808',
                                                    borderColor: '#8A0808',
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
                                                        if ($diagram_mode == 'hour' or ($diagram_mode == 'custom' and $customtime <= 3600)) {
                                                            print 'pointRadius: 1, pointHitRadius: 5,';
                                                        }
                                                        else
                                                        {
                                                            print 'pointRadius: 0, pointHitRadius: 5,';
                                                        }
                                                    ?>
                                                    pointStyle:'rect',
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                },
                                                {
                                                    label: '<?php echo _("humidity") . ' ext.' ?>',
                                                    yAxisID: 'humidity',
                                                    data: <?php echo json_encode($extern_humidity_dataset); ?>,
                                                    backgroundColor: '#08298A',
                                                    borderColor: '#08298A',
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
                                                                return Number(tooltipItem.yLabel).toFixed(1) + ' %';
                                                            } else if (tooltipItem.datasetIndex === 3) {
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
                                                                hour: 'MMM D, HH:mm'
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
                                                            labelString: '<?php echo _("temperature") ?> <?php echo _(" - ϑ") ?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'temperature',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                val = Math.round(value * 10)/10;
                                                                return '  ' + val + ' °C' + '  ';
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
                                                                val = Math.round(value * 10)/10;
                                                                return '  ' + val + ' %' + '  ';
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
 
                                        // Taupunkt und absolute Feuchte
                                        var dewpoint_humidity_chart_el = document.getElementById("dewpoint_humidity_chart_id");
                                        var config_dewpoint_humidity_chart = {
                                            type: 'line',
                                            data: {
                                                labels: [], 
                                                datasets: [{
                                                    label: '<?php echo _("dewpoint") . ' int.' ?>',
                                                    yAxisID: 'temperature',
                                                    data: <?php echo json_encode($dewpoint_dataset); ?>,
                                                    backgroundColor: '#04B431',
                                                    borderColor: '#04B431',
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
                                                    pointStyle:'rect',
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                },
                                                {
                                                    label: '<?php echo _("dewpoint") . ' ext.' ?>',
                                                    yAxisID: 'temperature',
                                                    data: <?php echo json_encode($extern_dewpoint_dataset); ?>,
                                                    backgroundColor: '#0B6121',
                                                    borderColor: '#0B6121',
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
                                                    pointStyle:'rect',
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                },
                                                {
                                                    label: '<?php echo _("humidity abs") . ' int.' ?>',
                                                    yAxisID: 'humidityabs',
                                                    data: <?php echo json_encode($humidity_abs_dataset); ?>,
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
                                                    pointStyle:'rect',
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                },
                                                {
                                                    label: '<?php echo _("humidity abs") . ' ext.' ?>',
                                                    yAxisID: 'humidityabs',
                                                    data: <?php echo json_encode($extern_humidity_abs_dataset); ?>,
                                                    backgroundColor: '#08298A',
                                                    borderColor: '#08298A',
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
                                                    pointStyle:'rect',
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false

                                                }]
                                            },
                                            options: {
                                                title: {
                                                    display: true,
                                                    text: '<?php echo _("dewpoint") ?> & <?php echo _("humidity abs") ?>',
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
                                                                return Number(tooltipItem.yLabel).toFixed(1) + ' g/m³';
                                                            } else if (tooltipItem.datasetIndex === 3) {
                                                                return Number(tooltipItem.yLabel).toFixed(1) + ' g/m³';
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
                                                                hour: 'MMM D, HH:mm'
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
                                                            labelString: '<?php echo _("temperature") ?> <?php echo _(" - ϑ") ?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'temperature',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                val = Math.round(value * 10)/10;
                                                                return '  ' + val + ' °C' + '  ';
                                                            },
                                                            fontColor: '#000000',
                                                            suggestedMax: 10,
                                                            suggestedMin: -2															
                                                            //max: 30,
                                                            //min: -2
                                                        }
                                                    }, {
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: '<?php echo _("humidity abs") ?> <?php echo _(" - φ") ?>',
                                                            //  fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'humidityabs',
                                                        type: 'linear',
                                                        display: true,
                                                        position: 'right',
                                                        labelString: '<?php echo _("humidity abs") ?>',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                val = Math.round(value * 10)/10;
                                                                return '  ' + val + ' g/m³' + '  ';
                                                               // return '  ' + '        ' + ' ' + '  ';
                                                            },
                                                            fontColor: '#000000',
                                                           
                                                            suggestedMax: 10,
                                                            suggestedMin: 0,															
/*                                                            //max: 30,
                                                            //min: -2
*/
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
                                                        if ($diagram_mode == 'hour' or ($diagram_mode == 'custom' and $customtime <= 3600)) {
                                                            print 'pointRadius: 1, pointHitRadius: 5,';
                                                        }
                                                        else
                                                        {
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
                                                            if (tooltipItem.datasetIndex === 0) {
                                                                return Number(tooltipItem.yLabel).toFixed(1) + ' g';
                                                            } else if (tooltipItem.datasetIndex === 1) {
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
                                                                hour: 'MMM D, HH:mm'
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
                                        
                                        // NTC thermometer 1,2,3 on left side and ntc4/Current Sensor on right side
                                        var thermometers_chart_el = document.getElementById("thermometers_chart_id");
                                        var config_thermometers_chart = {
                                            type: 'line',
                                            data: {
                                                labels: [],
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
                                                    pointStyle:'rect',
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
                                                    pointStyle:'rect',
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
                                                    pointStyle:'rect',
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                },
                                                {
                                                    label: '<?php if ($sensor4_is_current == true) { echo _("Current"); } else { echo _("temperature") . ' NTC 4';}?>',
                                                    yAxisID: 'temp_current',
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
                                                    pointStyle:'rect',
                                                    cubicInterpolationMode: 'monotone',
                                                    fill: false
                                                }]
                                            },
                                            options: {
                                                title: {
                                                    display: true,
                                                    text: '<?php if ($sensor4_is_current == true) { echo  _("Thermometer") . " NTC 1..3, " . _("Current"); } else { echo _("Thermometer") . " NTC 1..4";}?>',
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
                                                                <?php if ($sensor4_is_current == true){ echo 'return Number(tooltipItem.yLabel).toFixed(3) + " A";'; } else { echo 'return Number(tooltipItem.yLabel).toFixed(1) + " °C";';}?> 
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
                                                                hour: 'MMM D, HH:mm'
                                                            },
                                                            tooltipFormat: 'DD. MMM. YYYY HH:mm'
                                                        },
                                                        ticks: {
                                                            autoSkip: false,
                                                            maxRotation: 0,
                                                            minRotation: 0
                                                        },
                                                    }, ],
                                                    yAxes: [
                                                    {
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: '<?php echo "NTC 1..3 " . _("temperature")?> <?php echo _(" - ϑ") ?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'temperature',
                                                        type: 'linear',
                                                        position: 'left',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                val = Math.round(value * 10)/10;
                                                                return '  ' + val + ' °C' + '  ';
                                                            },
                                                            fontColor: '#000000',
                                                            //    fontSize: 20,
                                                            //max: 25000,
                                                            beginAtZero: false,
                                                            maxTicksLimit: 10,
                                                            suggestedMax: 15,
                                                            suggestedMin: 5
                                                        }
                                                    },
                                                    {
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: '<?php if ($sensor4_is_current == true) { echo _("Current") . " - I"; } else { echo "NTC4 " . _("temperature") . _(" - ϑ");}?>',
                                                        //    fontSize: 20,
                                                            fontColor: '#000000'
                                                        },
                                                        id: 'temp_current',
                                                        type: 'linear',
                                                        position: 'right',
                                                        ticks: {
                                                            callback: function(value, index, values) {
                                                                val = Math.round(value * <?php if ($sensor4_is_current == true) { echo '1000)/1000';} else { echo '10)/10';}?>;
                                                                return '  ' + val + '<?php if ($sensor4_is_current == true) { echo ' A'; } else { echo ' °C'; }?>' + '  ';
                                                            },
                                                            fontColor: '#000000',
                                                            beginAtZero: <?php if ($sensor4_is_current == true) { echo 'true';} else { echo 'false';}?>,
                                                            maxTicksLimit: 10,
	                                                        suggestedMin: <?php if ($sensor4_is_current == true) { echo '0';} else { echo '10';}?>,
                                                            suggestedMax: <?php if ($sensor4_is_current == true) { echo '2';} else { echo '20';}?>
                                                            //max: 30, 
                                                            //min: -4
                                                        }
                                                    }]
                                                }
                                            }
                                        };
 

                                        // licht
                                        var light_chart_el = document.getElementById("light_chart_id");
                                        var config_light_chart = {
                                            type: 'line',
                                            data: {
                                                labels: [],
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
                                                    pointStyle:'rect',
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
                                                legend: {
                                                    labels: {
                                                    usePointStyle: true,
                                                    },
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
                                                                hour: 'MMM D, HH:mm'
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
                                        var uv_light_chart_el = document.getElementById("uv_chart_id");
                                        var config_uv_light_chart = {
                                            type: 'line',
                                            data: {
                                                labels: [],
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
                                                    pointStyle:'rect',
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
                                                legend: {
                                                    labels: {
                                                    usePointStyle: true,
                                                    },
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
                                                                hour: 'MMM D, HH:mm'
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
                                        var heater_chart_el = document.getElementById("heater_chart_id");
                                        var config_heater_chart = {
                                            type: 'line',
                                            data: {
                                                labels: [],
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
                                                    pointStyle:'rect',
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
                                                legend: {
                                                    labels: {
                                                    usePointStyle: true,
                                                    },
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
                                                                hour: 'MMM D, HH:mm'
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
                                        var cooler_chart_el = document.getElementById("cooler_chart_id");
                                        var config_cooler_chart = {
                                            type: 'line',
                                            data: {
                                                labels: [],
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
                                                    pointStyle:'rect',
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
                                                legend: {
                                                    labels: {
                                                    usePointStyle: true,
                                                    },
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
                                                                hour: 'MMM D, HH:mm'
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
                                        var humidifier_chart_el = document.getElementById("humidifier_chart_id");
                                        var config_humidifier_chart = {
                                            type: 'line',
                                            data: {
                                                labels: [],
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
                                                    pointStyle:'rect',
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
                                                legend: {
                                                    labels: {
                                                    usePointStyle: true,
                                                    },
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
                                                                hour: 'MMM D, HH:mm'
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
                                        var dehumidifier_chart_el = document.getElementById("dehumidifier_chart_id");
                                        var config_dehumidifier_chart = {
                                            type: 'line',
                                            data: {
                                                labels: [],
                                                datasets: [{
                                                    label: '<?php echo _("dehumidifier"); ?>',
                                                    yAxisID: 'status',
                                                    data: <?php echo json_encode($dehumidifier_dataset);?>,
                                                    backgroundColor: '#BDB76B',
                                                    borderColor: '#BDB76B',
                                                    borderWidth: 0.1,
                                                    radius: 0,
                                                    pointRadius: 0,
                                                    pointHitRadius: 0,
                                                    pointStyle:'rect',
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
                                                legend: {
                                                    labels: {
                                                    usePointStyle: true,
                                                    },
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
                                                                hour: 'MMM D, HH:mm'
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
                                        var exhaust_air_chart_el = document.getElementById("exhaust_air_chart_id");
                                        var config_exhaust_air_chart = {
                                            type: 'line',
                                            data: {
                                                labels: [],
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
                                                    pointStyle:'rect',
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
                                                legend: {
                                                    labels: {
                                                    usePointStyle: true,
                                                    },
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
                                                                hour: 'MMM D, HH:mm'
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
                                        var circulation_air_chart_el = document.getElementById("circulation_air_chart_id");
                                        var config_circulation_air_chart = {
                                            type: 'line',
                                            data: {
                                                labels: [],
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
                                                    pointStyle:'rect',
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
                                                legend: {
                                                    labels: {
                                                    usePointStyle: true,
                                                    },
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
                                                                hour: 'MMM D, HH:mm'
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
                                        
                                        // analog charts time axis
                                        timestamps_temp_seconds = <?php echo json_encode($all_sensors_timestamps_array); ?>;
                                        timestamps_scales_seconds = <?php echo json_encode($all_scales_timestamps_array); ?>;
                                        // on_off charts time axis
                                        timestamps_light_seconds = <?php echo json_encode($light_timestamps_axis); ?>;
                                        timestamps_uv_light_seconds = <?php echo json_encode($uv_light_timestamps_axis); ?>;
                                        timestamps_heater_seconds = <?php echo json_encode($heater_timestamps_axis); ?>;                                        
                                        timestamps_cooler_seconds = <?php echo json_encode($cooler_timestamps_axis); ?>;
                                        timestamps_humidifier_seconds = <?php echo json_encode($humidifier_timestamps_axis); ?>;
                                        timestamps_dehumidifier_seconds = <?php echo json_encode($dehumidifier_timestamps_axis); ?>;
                                        timestamps_exhaust_air_seconds = <?php echo json_encode($exhaust_air_timestamps_axis); ?>;
                                        timestamps_circulate_air_seconds = <?php echo json_encode($circulate_air_timestamps_axis); ?>;
                                        
                                        timestamps_temp_js = convert_timestamps_index( timestamps_temp_seconds );
                                        timestamps_scales_js = convert_timestamps_index( timestamps_scales_seconds );

                                        timestamps_light_js = convert_timestamps_index( timestamps_light_seconds );
                                        timestamps_uv_light_js = convert_timestamps_index( timestamps_uv_light_seconds );
                                        timestamps_heater_js = convert_timestamps_index( timestamps_heater_seconds );
                                        timestamps_cooler_js = convert_timestamps_index( timestamps_cooler_seconds );
                                        timestamps_humidifier_js = convert_timestamps_index( timestamps_humidifier_seconds );
                                        timestamps_dehumidifier_js = convert_timestamps_index( timestamps_dehumidifier_seconds );
                                        timestamps_exhaust_air_js = convert_timestamps_index( timestamps_exhaust_air_seconds );
                                        timestamps_circulate_air_js = convert_timestamps_index( timestamps_circulate_air_seconds );

                                        config_temp_hum_chart.data.labels = timestamps_temp_js;
                                        config_scales_chart.data.labels = timestamps_scales_js;
                                        config_dewpoint_humidity_chart.data.labels = timestamps_temp_js;
                                        config_thermometers_chart.data.labels = timestamps_temp_js;

                                        config_light_chart.data.labels = timestamps_light_js;
                                        config_uv_light_chart.data.labels = timestamps_uv_light_js;
                                        config_heater_chart.data.labels = timestamps_heater_js;
                                        config_cooler_chart.data.labels = timestamps_cooler_js;
                                        config_humidifier_chart.data.labels = timestamps_humidifier_js;
                                        config_dehumidifier_chart.data.labels = timestamps_dehumidifier_js;
                                        config_exhaust_air_chart.data.labels = timestamps_exhaust_air_js;
                                        config_circulation_air_chart.data.labels = timestamps_circulate_air_js;
                                    
                                        // restore hidden flags for temp_hum_chart
                                        let dataset_count = config_temp_hum_chart.data.datasets.length;
                                        for (let i = 0; i < dataset_count; ++i) {
                                            let key = 'diagrams_temp_dataset' + i.toString();
                                            let chart_hidden = window.localStorage.getItem(key);
                                            if (chart_hidden == null) {
                                                chart_hidden = false;
                                            }
                                            console.log(key + ' = ' + chart_hidden);
                                            config_temp_hum_chart.data.datasets[i].hidden = (chart_hidden == 'true');
                                        }
                                        
                                        // restore hidden flags for thermometers_chart
                                        dataset_count = config_thermometers_chart.data.datasets.length;
                                        for (i = 0; i < dataset_count; ++i) {
                                            key = 'diagrams_ntc_dataset' + i.toString();
                                            chart_hidden = window.localStorage.getItem(key);
                                            if (chart_hidden == null) {
                                                chart_hidden = false;
                                            }
                                            console.log(key + ' = ' + chart_hidden);
                                            config_thermometers_chart.data.datasets[i].hidden = (chart_hidden == 'true');
                                        }                                        
                                        
                                        // restore hidden flags for dewpoint_humidity_chart
                                        dataset_count = config_dewpoint_humidity_chart.data.datasets.length;
                                        for (i = 0; i < dataset_count; ++i) {
                                            key = 'diagrams_dew_dataset' + i.toString();
                                            chart_hidden = window.localStorage.getItem(key);
                                            if (chart_hidden == null) {
                                                chart_hidden = false;
                                            }
                                            console.log(key + ' = ' + chart_hidden);
                                            config_dewpoint_humidity_chart.data.datasets[i].hidden = (chart_hidden == 'true');
                                        }                                        
                                        
                                        // generate charts
                                        temp_hum_chart = new Chart(temp_hum_chart_el, config_temp_hum_chart);
                                        scales_chart = new Chart(scales_chart_el, config_scales_chart);
                                        dewpoint_humidity_chart = new Chart(dewpoint_humidity_chart_el, config_dewpoint_humidity_chart);
                                        thermometers_chart = new Chart(thermometers_chart_el, config_thermometers_chart);

                                        light_chart = new Chart(light_chart_el, config_light_chart);
                                        uv_light_chart = new Chart(uv_light_chart_el, config_uv_light_chart);
                                        heater_chart = new Chart(heater_chart_el, config_heater_chart);
                                        cooler_chart = new Chart(cooler_chart_el, config_cooler_chart);
                                        humidifier_chart = new Chart(humidifier_chart_el, config_humidifier_chart);
                                        dehumidifier_chart = new Chart(dehumidifier_chart_el, config_dehumidifier_chart);
                                        exhaust_air_chart =  new Chart(exhaust_air_chart_el, config_exhaust_air_chart);
                                        circulation_air_chart =  new Chart(circulation_air_chart_el, config_circulation_air_chart);

                                        // save hidden flags for temp_hum_chart
                                        window.addEventListener('beforeunload', (event) => {
                                            // event.preventDefault();
                                            let count = temp_hum_chart.data.datasets.length;
                                            for (let i = 0; i < count; ++i) {
                                                var ds_visible = temp_hum_chart.isDatasetVisible(i);
                                                var loc_store_name = 'diagrams_temp_dataset' + i.toString();
                                                console.log('loc_store_name = ' + loc_store_name + '  ' + ds_visible);
                                                window.localStorage.setItem(loc_store_name, (!ds_visible).toString());
                                            }
                                            
                                            count = thermometers_chart.data.datasets.length;
                                            for (i = 0; i < count; ++i) {
                                                var ds_visible = thermometers_chart.isDatasetVisible(i);
                                                var loc_store_name = 'diagrams_ntc_dataset' + i.toString();
                                                console.log('loc_store_name = ' + loc_store_name + '  ' + ds_visible);
                                                window.localStorage.setItem(loc_store_name, (!ds_visible).toString());
                                            }                                            
                                            
                                            count = dewpoint_humidity_chart.data.datasets.length;
                                            for (i = 0; i < count; ++i) {
                                                var ds_visible = dewpoint_humidity_chart.isDatasetVisible(i);
                                                var loc_store_name = 'diagrams_dew_dataset' + i.toString();
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
                                        
                                        </script>
                                        </br>
                                        </br>
                                        
                                        <?php
                                            echo "<script src='js/ajax_all_dg.js'></script>";
                                        ?> 
                                    </div>
                                <!----------------------------------------------------------------------------------------Ende! ...-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--        </div> -->
        <?php 
            include 'footer.php';
        ?>
