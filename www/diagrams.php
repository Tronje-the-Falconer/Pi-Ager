<?php 
                                    include 'header.php';                                       // Template-Kopf und Navigation
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
                                            <td><a href="diagrams.php?diagram_mode=hourly" class="art-button"><?php echo _('hour'); ?></a></td>
                                            <td><a href="diagrams.php?diagram_mode=daily" class="art-button"><?php echo _('day'); ?></a></td>
                                            <td><a href="diagrams.php?diagram_mode=weekly" class="art-button"><?php echo _('week'); ?></a></td>
                                            <td><a href="diagrams.php?diagram_mode=monthly" class="art-button"><?php echo _('month'); ?></a></td>
                                        </tr>
                                    </table>
                                </div>

                                    <div style="">
                                    
                                    <h2><?php echo _('test'); ?> </h2>
                                    <canvas class="chart"; id="temperature_humidity_chart"></canvas>
                                    <script>
                                    var ctx = document.getElementById("temperature_humidity_chart");
                                    var temperature_humidity_chart = new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                                            datasets: [{
                                                label: 'temperature',
                                                yAxisID: 'temperature',
                                                data: [12, 19, 3, 5, 2, 3],
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
                                                data: [50, 41, 60, 80, 100, 96],
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
                                                    time: {
                                                        unit: 'month'
                                                    },
                                                    ticks: {
                                                        fontSize: 20
                                                    }
                                                }],
                                                yAxes: [{
                                                    scaleLabel: {
                                                        display: true,
                                                        labelString: '<?php echo _("temperature") ?>',
                                                        fontSize: 20
                                                    },
                                                    id: 'temperature',
                                                    type: 'linear',
                                                    position: 'left',
                                                    ticks: {
                                                        fontColor: '#FF0000',
                                                        fontSize: 20,
                                                        max: 30,
                                                        min: -4
                                                    }
                                                }, {
                                                    scaleLabel: {
                                                        display: true,
                                                        labelString: '<?php echo _("humidity") ?>',
                                                        fontSize: 20
                                                    },
                                                    id: 'humidity',
                                                    type: 'linear',
                                                    display: true,
                                                    position: 'right',
                                                    labelString: 'humidity',
                                                    ticks: {
                                                        fontColor: '#2E2EFE',
                                                        fontSize: 20,
                                                        max: 110,
                                                        min: 40
                                                    }
                                                }]
                                            }
                                        }
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
