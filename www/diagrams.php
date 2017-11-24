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
                                                data: [12, 19, 3, 5, 2, 3],
                                                backgroundColor: [
                                                    'rgba(255, 99, 132, 0.2)',
                                                    'rgba(54, 162, 235, 0.2)',
                                                    'rgba(255, 206, 86, 0.2)',
                                                    'rgba(75, 192, 192, 0.2)',
                                                    'rgba(153, 102, 255, 0.2)',
                                                    'rgba(255, 159, 64, 0.2)'
                                                ],
                                                borderColor: [
                                                    'rgba(255,99,132,1)',
                                                    'rgba(54, 162, 235, 1)',
                                                    'rgba(255, 206, 86, 1)',
                                                    'rgba(75, 192, 192, 1)',
                                                    'rgba(153, 102, 255, 1)',
                                                    'rgba(255, 159, 64, 1)'
                                                ],
                                                borderWidth: 1,
                                                cubicInterpolationMode: 'monotone'
                                            }]
                                        },
                                        options: {

                                            scales: {
                                                yAxes: [{
                                                    ticks: {
                                                        beginAtZero:true
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
