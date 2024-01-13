<?php 
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                    include 'modules/names.php';
                                    include 'modules/database.php';
                                    include 'modules/logging.php';                            //liest die Datei fuer das logging ein
                                    
                                    include 'modules/system_reboot.php';                        // Startet das System neu
                                    include 'modules/system_shutdown.php';                      // Fährt das System herunter
                                    include 'modules/start_stop_program.php';                   // 
                                    
                                    include 'modules/database_empty_statistic_tables.php';      // leert die Statistik-Tabellen (Status, data)
                                    include 'modules/write_loglevel_db.php';                    // schreibt das Loglevel in Datenbank
                                    include 'modules/write_debug_values.php';                   // schreibt die Debug-Werte
                                    include 'modules/write_admin_db.php';                       // schreibt die admin-Werte
                                    include 'modules/write_bus.php';                            // schreibt den bus-value
                                    include 'modules/backup_manual.php';                        // steuert manuelles Backup im sudowebscript an
                                    include 'modules/nextion_upload_firmware.php';              // upload firmware file for nextion hmi display                                    
                                    include 'modules/write_backup_db.php';                      // schreibt die backup-Werte
                                    include 'modules/write_defrost_db.php';                     // schreibt die defrost Werte in die DB
                                    include 'modules/write_nextion_type_db.php';                // nextion display type save
                                    
                                    // include 'modules/read_config_db.php';                       // Liest die Grundeinstellungen Sensortyp, Hysteresen, GPIO's)
                                    // include 'modules/read_current_db.php';
                                    include 'modules/read_all_admin.php';
                                    include 'modules/start_stop_uv.php';                        // UV-light stop/auto
                                    include 'modules/read_bus.php';                             // liest den gesetzten bus-value
                                    

                                ?>
                                <h2 class="art-postheader"><?php echo _('uv-light switch'); ?></h2>
                                <div class="hg_container">
                                    <form  method="post" name="uv-light">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td width="100px"></td>
                                                <td width="180px"></td>
                                                <td></td>
                                            </tr>
                                            <tr>
												<?php 
                                                    if($status_uv_manual == 0) {
                                                        echo '<td><img src="/images/icons/status_off_manual_20x20.png" title="uv off"></td>';
                                                        echo '<td style="text-align: left; ">' . _('uv light manual off') . '</td>';
                                                        echo '<td style="text-align: left;"><button class="art-button" name="turn_on_uv" onclick="return confirm(\'' . _('end pause uv-light!') . '\');">' . _('auto'). '</button></td>';
                                                    }
                                                    else{
                                                        echo '<td><img src="/images/icons/status_on_20x20.png" title="uv on"></td>';
														echo '<td style="text-align: left;">' . _('auto mode on') . '</td>';
                                                        echo '<td style="text-align: left;"><button class="art-button" name="turn_off_uv" onclick="return confirm(\''. _('pause uv-light!') . '\');">' . _('uv light off'). '</button></td>';
                                                    }
                                                ?>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                                <hr>
                                <h2 class="art-postheader"><?php echo _('administration'); ?></h2>
                                <?php
                                    # Raspberry Pi check on Pi 5b
                                    $model = [];
                                    $res = null;
                                    exec('cat /proc/device-tree/model', $model, $res);
                                    # var_dump($model);
                                    if (str_contains($model[0], 'Raspberry Pi 5') == false) {
                                        $enable_sensor = ' ';
                                    }
                                    else {
                                        $enable_sensor = ' disabled';
                                    }
                                    # var_dump($enable_sensor);
                                ?>
                                <form method="post" name="admin">
                                    <div class="hg_container">
                                        <!----------------------------------------------------------------------------------------Sensortype-->
                                        <table style="width: 100%;" class="miniature_writing">
                                            <tr>
                                                <td style="width: 100px;"></td>
                                                <td style="width: 150px; text-align: left; padding-left: 20px;"><h3><?php echo _('internal'); ?></h3></td>
                                                <td style="width: 60px; "></td>
                                                <td style="width: 150px; text-align: left; padding-left: 20px;"><h3><?php echo _('external'); ?></h3></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td style="text-align: left; padding-left: 60px;"><h4><?php echo _('[I2C addr]'); ?></h4></td>
                                                <td></td>
                                                <td style="text-align: left; padding-left: 60px;"><h4><?php echo _('[I2C addr]'); ?></h4></td>
                                            </tr>
                                            <tr>
                                                <td class="td_png_icon"><h3><?php echo _('sensortype'); ?></h3><img src="images/icons/sensortype_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_sensortype_blockFunction()"><?php echo _('help'); ?></button>
                                                </td>
                                                <td style=" text-align: left; padding-left: 20px;"><br>
                                                    <input type="radio" name="sensortype_admin" value="1" <?php echo $checked_sens_1; ?>/><label><span style="color: #7A5800 !important"> DHT11</span></label><br>
                                                    <input type="radio" name="sensortype_admin" value="2" <?php echo $checked_sens_2; ?>/><label><span style="color: #7A5800 !important"> DHT22</span></label><br>
                                                    <input type="radio" name="sensortype_admin" value="3" <?php echo $checked_sens_3; ?>/><label><span style="color: #7A5800 !important"> SHT75</span></label><br>
                                                    <input type="radio" name="sensortype_admin" value="4" <?php echo $checked_sens_4; ?>/><label><strong> SHT85</strong>&nbsp;[0x44]</label><br>
                                                    <input type="radio" name="sensortype_admin" value="5" <?php echo $checked_sens_5; ?>/><label><strong> SHT3x</strong>&nbsp;[0x44]</label><br>
                                                    <input type="radio" name="sensortype_admin" value="6" <?php echo $checked_sens_6; ?>/><label><strong> SHT3x-mod</strong>&nbsp;[0x45]</label><br>
                                                    <input type="radio" name="sensortype_admin" value="7" <?php echo $checked_sens_7; ?>/><label><strong> AHT1x</strong>&nbsp;[0x38]</label><br>
                                                    <input type="radio" name="sensortype_admin" value="8" <?php echo $checked_sens_8; ?>/><label><strong> AHT1x-mod</strong>&nbsp;[0x39]</label><br>
                                                    <input type="radio" name="sensortype_admin" value="9" <?php echo $checked_sens_9; ?>/><label><strong> AHT2x</strong>&nbsp;[0x38]</label><br>
                                                    <input type="radio" name="sensortype_admin" value="10" <?php echo $checked_sens_10; ?>/><label><strong> AHT30</strong>&nbsp;[0x38]</label><br>
                                                    <input type="radio" name="sensortype_admin" value="11" <?php echo $checked_sens_11; ?>/><label><strong> SHT4x-A</strong>&nbsp;[0x44]</label><br>
                                                    <input type="radio" name="sensortype_admin" value="12" <?php echo $checked_sens_12; ?>/><label><strong> SHT4x-B</strong>&nbsp;[0x45]</label><br>
                                                    <input type="radio" name="sensortype_admin" value="13" <?php echo $checked_sens_13; ?>/><label><strong> SHT4x-C</strong>&nbsp;[0x46]</label><br>
                                                    <br>
                                                </td>
                                                <td></td>
                                                <td style=" text-align: left; padding-left: 20px;">
                                                    <input type="radio" name="sensorsecondtype_admin" value="0" <?php echo $checked_senssecond_0; ?>/><label> disabled</label><br>
                                                    <input type="radio" name="sensorsecondtype_admin" value="4" <?php echo $checked_senssecond_4; ?>/><label><strong> SHT85</strong>&nbsp;[0x44]</label><br>
                                                    <input type="radio" name="sensorsecondtype_admin" value="5" <?php echo $checked_senssecond_5; ?>/><label><strong> SHT3x</strong>&nbsp;[0x44]</label><br>
                                                    <input type="radio" name="sensorsecondtype_admin" value="6" <?php echo $checked_senssecond_6; ?>/><label><strong> SHT3x-mod</strong>&nbsp;[0x45]</label><br>
                                                    <input type="radio" name="sensorsecondtype_admin" value="7" <?php echo $checked_senssecond_7; ?>/><label><strong> AHT1x</strong>&nbsp;[0x38]</label><br>
                                                    <input type="radio" name="sensorsecondtype_admin" value="8" <?php echo $checked_senssecond_8; ?>/><label><strong> AHT1x-mod</strong>&nbsp;[0x39]</label><br>
                                                    <input type="radio" name="sensorsecondtype_admin" value="9" <?php echo $checked_senssecond_9; ?>/><label><strong> AHT2x</strong>&nbsp;[0x38]</label><br>
                                                    <input type="radio" name="sensorsecondtype_admin" value="10" <?php echo $checked_senssecond_10; ?>/><label><strong> AHT30x</strong>&nbsp;[0x38]</label><br>
                                                    <input type="radio" name="sensorsecondtype_admin" value="11" <?php echo $checked_senssecond_11; ?>/><label><strong> SHT4x-A</strong>&nbsp;[0x44]</label><br>
                                                    <input type="radio" name="sensorsecondtype_admin" value="12" <?php echo $checked_senssecond_12; ?>/><label><strong> SHT4x-B</strong>&nbsp;[0x45]</label><br>
                                                    <input type="radio" name="sensorsecondtype_admin" value="13" <?php echo $checked_senssecond_13; ?>/><label><strong> SHT4x-C</strong>&nbsp;[0x46]</label><br>
                                                    <input type="radio" name="sensorsecondtype_admin" value="14" <?php echo $checked_senssecond_14; ?>/><label><strong> MiThermometer</strong></label><br>
                                                    <br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td colspan="2"><label><?php echo _('MiThermometer device name');?>&nbsp;</label><div class="tooltip"><input type="text" size="13" maxlength="10" id="atc_device_name" name="atc_device_name" value=<?php echo $ATC_device_name;?>><span class="tooltiptext"><?php echo _('Enter MiThermometer device name, e.g. ATC_C4C134, can be found with blescan tool');?></span></div></td>
                                            </tr>
                                        </table>
                                        <input name="bus" type="hidden" required value="<?php echo $bus; ?>">
                                        <script>
                                            function help_sensortype_blockFunction() {
                                                document.getElementById('help_sensortype').style.display = 'block';
                                            }
                                            function help_sensortype_noneFunction() {
                                                document.getElementById('help_sensortype').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_sensortype" class="help_p">
                                            <?php echo _('helptext_sensortype_admin');
                                                  echo '<br><br>'; ?>
                                            <button class="art-button" type="button" onclick="help_sensortype_noneFunction()"><?php echo _('close'); ?></button>
                                        </p>

                                        <table style="width: 100%; align: center;">
                                            <tr>
                                                <td><br><button class="art-button" name="change_sensorbus_submit" value="change_sensorbus_submit" onclick="return confirm('<?php echo _('ATTENTION: a sensor change may require system reboot or shutdown.\n After shutdown you have to put your Raspberry Pi device into power-off state!');?>');"><?php echo _('change sensors'); ?></button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>
                                        
                                <form method="post" name="admin">
                                    <div class="hg_container">
                                        <!----------------------------------------------------------------------------------------Waagen-->
                                    <table style="width: 100%;" class="miniature_writing">
                                        <tr>
                                            <td rowspan="18" class="td_png_icon"><h3><?php echo _('scales'); ?></h3><img src="images/icons/scale_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_scales_blockFunction()"><?php echo _('help'); ?></button></td>
                                            <td colspan="3"><h3><?php echo _('scale'); ?> 1</h3></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo _('reference unit'); ?>:</td>
                                            <td class="text_left_padding"><input name="referenceunit_scale1_admin" type="number" style="width: 90%;" maxlength="4" size="2" step="0.1" min="0.1" max="9999.9" required value=<?php echo $referenceunit_scale1; ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo _('offset'); ?>:</td>
                                            <td class="text_left_padding"><input name="offset_scale1_admin" type="number" style="width: 90%;" maxlength="4" size="2" step="0.1" required value=<?php echo $offset_scale1; ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo _('saving period'); ?>:</td>
                                            <td class="text_left_padding"><input name="saving_period_scale1_admin" type="number" style="width: 90%;" maxlength="4" size="2" min="5" max="300" step="1" required value=<?php echo $saving_period_scale1; ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo _('starting weight') . ' [g]'; ?>:</td>
                                            <td class="text_left_padding"><input name="take_off_weight_scale1_admin" type="number" style="width: 90%;" maxlength="6" size="2" min="0" max="100000" step="1" required value=<?php echo $take_off_weight_scale1; ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php # echo _('measuring interval'); ?><!--:--></td>
                                            <td class="text_left_padding"><input name="measuring_interval_scale1_admin" type="hidden" style="width: 90%;" min="2" max="20" step="1" required value=<?php echo $measuring_interval_scale1; ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php # echo _('measuring duration'); ?><!--:--></td>
                                            <td class="text_left_padding"><input name="measuring_duration_scale1_admin" type="hidden" style="width: 90%;" maxlength="4" size="2" min="1" required value=<?php echo $measuring_duration_scale1; ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php # echo _('samples'); ?><!--:--></td>
                                            <td class="text_left_padding"><input name="samples_scale1_admin" type="hidden" style="width: 90%;" maxlength="4" size="2" min="2" max="25" step="1" required value=<?php echo $samples_scale1; ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php # echo _('spikes'); ?><!--:--></td>
                                            <td class="text_left_padding"><input type="hidden" name="spikes_scale1_admin" type="number" maxlength="4" size="2" required value=<?php echo $spikes_scale1; ?>></td>
                                        </tr>

                                        <tr>
                                            <td colspan="2"><h3><?php echo _('scale'); ?> 2</h3></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo _('reference unit'); ?>:</td>
                                            <td class="text_left_padding"><input name="referenceunit_scale2_admin" type="number" style="width: 90%;" step="0.1" min="0.1" max="9999.9" required value=<?php echo $referenceunit_scale2; ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo _('offset'); ?>:</td>
                                            <td class="text_left_padding"><input name="offset_scale2_admin" type="number" style="width: 90%;" maxlength="4" size="2" step="0.1" required value=<?php echo $offset_scale2; ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo _('saving period'); ?>:</td>
                                            <td class="text_left_padding"><input name="saving_period_scale2_admin" type="number" style="width: 90%;" maxlength="4" size="2" min="5" max="300" step="1" required value=<?php echo $saving_period_scale2; ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo _('starting weight') . ' [g]'; ?>:</td>
                                            <td class="text_left_padding"><input name="take_off_weight_scale2_admin" type="number" style="width: 90%;" maxlength="6" size="2" min="0" max="100000" step="1" required value=<?php echo $take_off_weight_scale2; ?>></td>
                                        </tr>                                        
                                        <tr>
                                            <td class="text_left_padding"><?php # echo _('measuring interval'); ?><!--:--></td>
                                            <td class="text_left_padding"><input name="measuring_interval_scale2_admin" type="hidden" style="width: 90%;" min="2" max="20" step="1" required value=<?php echo $measuring_interval_scale2; ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php # echo _('measuring duration'); ?><!--:--></td>
                                            <td class="text_left_padding"><input name="measuring_duration_scale2_admin" type="hidden" style="width: 90%;" maxlength="4" size="2" min="1" required value=<?php echo $measuring_duration_scale2; ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php # echo _('samples'); ?><!--:--></td>
                                            <td class="text_left_padding"><input name="samples_scale2_admin" type="hidden" style="width: 90%;" maxlength="4" size="2" min="2" max="25" step="1" required value=<?php echo $samples_scale2; ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php # echo _('spikes'); ?><!--:--></td>
                                            <td class="text_left_padding"><input type="hidden" name="spikes_scale2_admin" type="number" maxlength="4" size="2" required value=<?php echo $spikes_scale2; ?>></td>
                                        </tr>

                                    </table>
                                    <script>
                                        function help_scales_blockFunction() {
                                            document.getElementById('help_scales').style.display = 'block';
                                        }
                                        function help_scales_noneFunction() {
                                            document.getElementById('help_scales').style.display = 'none';
                                        }
                                    </script>
                                    <p id="help_scales" class="help_p">
                                        <?php echo _('helptext_scale_admin');
                                              echo '<br><br>'; ?>
                                        <button class="art-button" type="button" onclick="help_scales_noneFunction()"><?php echo _('close'); ?></button>
                                    </p>
                                    <hr>
                                    <!----------------------------------------------------------------------------------------Meat Thermometer-->
                                    <table style="width: 100%;" class="miniature_writing">
                                        <tr>
                                            <td rowspan="10" class="td_png_icon"><h3><?php echo 'Thermometer'; ?></h3><img src="images/icons/temperature_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_thermometer_blockFunction()"><?php echo _('help'); ?></button></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo 'Sensor 1'; ?>:</td>
                                            <td style="text-align: left;">
                                            <?php 
                                                $meat_sensors = get_meatsensors_dataset();
                                                if (isset($meat_sensors))
                                                {
                                                    $meatsensor_index = intval($meat1_sensortype);
                                                    echo '<select name="temp_sensor1_admin">';
                                                    foreach($meat_sensors as $meatsensor_row)
                                                    {
                                                        if (strncmp($meatsensor_row['name'], 'LEM', 3) !== 0)
                                                        {
                                                            if ($meatsensor_row['id'] == $meatsensor_index)
                                                            {
                                                                echo '<option value="'.$meatsensor_row['id'].'" selected>'.$meatsensor_row['name'].'</option>';
                                                            }
                                                            else
                                                            {
                                                                echo '<option value="'.$meatsensor_row['id'].'">'.$meatsensor_row['name'].'</option>';
                                                            }
                                                        }
                                                    }
                                                    echo '</select>';
                                                }
                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo 'Sensor 2'; ?>:</td>
                                            <td style="text-align: left;">
                                            <?php 
                                                if (isset($meat_sensors))
                                                {
                                                    $meatsensor_index = intval($meat2_sensortype);
                                                    echo '<select name="temp_sensor2_admin">';
                                                    foreach($meat_sensors as $meatsensor_row)
                                                    {
                                                        if (strncmp($meatsensor_row['name'], 'LEM', 3) !== 0)
                                                        {
                                                            if ($meatsensor_row['id'] == $meatsensor_index)
                                                            {
                                                                echo '<option value="'.$meatsensor_row['id'].'" selected>'.$meatsensor_row['name'].'</option>';
                                                            }
                                                            else
                                                            {
                                                                echo '<option value="'.$meatsensor_row['id'].'">'.$meatsensor_row['name'].'</option>';
                                                            }
                                                        }
                                                    }
                                                    echo '</select>';
                                                }
                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo 'Sensor 3'; ?>:</td>
                                            <td style="text-align: left;">
                                            <?php 
                                                if (isset($meat_sensors))
                                                {
                                                    $meatsensor_index = intval($meat3_sensortype);
                                                    echo '<select name="temp_sensor3_admin">';
                                                    foreach($meat_sensors as $meatsensor_row)
                                                    {
                                                        if (strncmp($meatsensor_row['name'], 'LEM', 3) !== 0)
                                                        {
                                                            if ($meatsensor_row['id'] == $meatsensor_index)
                                                            {
                                                                echo '<option value="'.$meatsensor_row['id'].'" selected>'.$meatsensor_row['name'].'</option>';
                                                            }
                                                            else
                                                            {
                                                                echo '<option value="'.$meatsensor_row['id'].'">'.$meatsensor_row['name'].'</option>';
                                                            }
                                                        }
                                                    }
                                                    echo '</select>';
                                                }
                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo 'Sensor 4'; ?>:</td>
                                            <td style="text-align: left;">
                                            <?php 
                                                if (isset($meat_sensors))
                                                {
                                                    $meatsensor_index = intval($meat4_sensortype);
                                                    echo '<select name="temp_sensor4_admin" onchange="getMeat4SensorTypeIndex(this)">';
                                                    foreach($meat_sensors as $meatsensor_row)
                                                    {
                                                        if ($meatsensor_row['id'] == $meatsensor_index)
                                                        {
                                                            echo '<option value="'.$meatsensor_row['id'].'" selected>'.$meatsensor_row['name'].'</option>';
                                                        }
                                                        else
                                                        {
                                                            echo '<option value="'.$meatsensor_row['id'].'">'.$meatsensor_row['name'].'</option>';
                                                        }
                                                    }
                                                    echo '</select>';
                                                }
                                                $current_check_row = get_table_row($config_current_check_table, 1);
                                                $status_current_check = intval($current_check_row[$current_check_active_field]);
                                                $current_threshold = number_format(floatval($current_check_row[$current_threshold_field]), 2, '.', '');
                                                $repeat_event_cycle = intval($current_check_row[$repeat_event_cycle_field]);
                                                if ($status_current_check == 1) {
                                                    $cooler_relay_check_active_true = "checked";
                                                }
                                                else{
                                                    $cooler_relay_check_active_true = "";
                                                }
                                            ?>
                                            </td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                        <tr id="cooler_check_line1"
                                        <?php
                                            if (intval($meat4_sensortype) != 18) {
                                                echo ' style="display:none;"';
                                            }
                                        ?>>
                                            <td class="text_left_padding"><?php echo _('cooler relay monitoring'); ?>:</td>
                                            <td style="text-align: left;">
                                                <input type="hidden" name="cooler_relay_check_active_admin" value="0">
                                                <input type="checkbox" name="cooler_relay_check_active_admin" value="1" <?php echo $cooler_relay_check_active_true; ?>/>
                                            </td>
                                        </tr>
                                        <tr id="cooler_check_line2"
                                        <?php
                                            if (intval($meat4_sensortype) != 18) {
                                                echo ' style="display:none;"';
                                            }
                                        ?>>                                        
                                            <td class="text_left_padding"><?php echo _('current threshold'); ?>:</td>
                                            <td style="text-align: left;"><input name="current_threshold_admin" type="number" min="0.1" max="25" step="0.01" style="width: 30%;" required value=<?php echo $current_threshold; ?>>&nbsp;A<span style="font-size: xx-small"> (0.1A <?php echo _('to'); ?> 25A)</span></td>
                                        </tr>
                                        <tr id="cooler_check_line3"
                                        <?php
                                            if (intval($meat4_sensortype) != 18) {
                                                echo ' style="display:none;"';
                                            }
                                        ?>>                                        
                                            <td class="text_left_padding"><?php echo _('event messages every'); ?>:</td>
                                            <td style="text-align: left;"><input name="repeat_event_cycle_admin" type="number" min="1" max="1440" step="1" style="width: 30%;" required value=<?php echo $repeat_event_cycle; ?>>&nbsp;<?php echo _('minutes'); ?><span style="font-size: xx-small"> (10 <?php echo _('to'); ?> 1440)</span></td>
                                        </tr>
                                    </table>

                                    <script>
                                        function getMeat4SensorTypeIndex(selectedObject) {
                                            var rowId  = selectedObject.value;
                                            console.log('rowId = ' + rowId);
                                            if (rowId == "18") {
                                                document.getElementById('cooler_check_line1').style.display = '';
                                                document.getElementById('cooler_check_line2').style.display = '';
                                                document.getElementById('cooler_check_line3').style.display = '';
                                            }
                                            else {
                                                document.getElementById('cooler_check_line1').style.display = 'none';
                                                document.getElementById('cooler_check_line2').style.display = 'none';
                                                document.getElementById('cooler_check_line3').style.display = 'none';
                                            }
                                        }                                    
                                    </script>
                                    
                                    <script>
                                        function help_thermometer_blockFunction() {
                                            document.getElementById('help_thermometer').style.display = 'block';
                                        }
                                        function help_thermometer_noneFunction() {
                                            document.getElementById('help_thermometer').style.display = 'none';
                                        }
                                    </script>
                                    <p id="help_thermometer" class="help_p">
                                        <?php echo _('helptext_thermometer_admin');
                                              echo '<br><br>'; ?>
                                        <button class="art-button" type="button" onclick="help_thermometer_noneFunction()"><?php echo _('close'); ?></button>
                                    </p>
                                    <hr>                                    
                                    <!----------------------------------------------------------------------------------------Language-->
                                    <table style="width: 100%;" class="miniature_writing">
                                        <tr>
                                            <td class="td_png_icon"><h3><?php echo _('language'); ?></h3><img src="images/icons/language_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_language_blockFunction()"><?php echo _('help'); ?></button>
                                            </td>
                                            <td style=" text-align: left; padding-left: 20px;">
                                                <input type="radio" name="language_admin" value="1" <?php echo $checked_language_1; ?>/><label> de_DE</label><br>
                                                <input type="radio" name="language_admin" value="2" <?php echo $checked_language_2; ?>/><label> en_GB</label><br>
                                                <br>
                                            </td>
                                        </tr>
                                    </table>
                                    <script>
                                        function help_language_blockFunction() {
                                            document.getElementById('help_language').style.display = 'block';
                                        }
                                        function help_language_noneFunction() {
                                            document.getElementById('help_language').style.display = 'none';
                                        }
                                    </script>
                                    <p id="help_language" class="help_p">
                                        <?php echo _('helptext_language_admin');
                                              echo '<br><br>'; ?>
                                        <button class="art-button" type="button" onclick="help_language_noneFunction()"><?php echo _('close'); ?></button>
                                    </p>
                                    <hr>
                                    <!-----------------------------------------------------------switch control-->
                                    <table style="width: 100%;" class="miniature_writing">
                                        <tr>
                                             <td rowspan="6" class="td_png_icon"><h3><?php echo _('switch control'); ?></h3><img src="images/icons/switch_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_switch_blockFunction()"><?php echo _('help'); ?></button></td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo _('UV Light'); ?>:</td>
                                            <td class="text_left_padding">
                                                <select name="switch_UV_light_admin" style="width: 280px;">
                                                    <?php
                                                        $mode_names_uv = array(_('not active'), _('turn off if switch is open'), _('turn off if switch is closed'));
                                                        for ($i = 0; $i < 3; $i++) {
                                                            if ($i == $switch_control_uv_light) {
                                                                echo '<option value="' . $i . '" selected>' . $mode_names_uv[$i] . '</option>';
                                                            }
                                                            else {
                                                                echo '<option value="' . $i . '">' . $mode_names_uv[$i] . '</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>                                       
                                         <tr>
                                            <td class="text_left_padding"><?php echo _('Light'); ?>:</td>
                                            <td class="text_left_padding">
                                                <select name="switch_light_admin" style="width: 280px;">
                                                    <?php
                                                        $mode_names = array(_('not active'), _('turn on if switch is open'), _('turn on if switch is closed'));
                                                        for ($i = 0; $i < 3; $i++) {
                                                            if ($i == $switch_control_light) {
                                                                echo '<option value="' . $i . '" selected>' . $mode_names[$i] . '</option>';
                                                            }
                                                            else {
                                                                echo '<option value="' . $i . '">' . $mode_names[$i] . '</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                    </table>
                                    <script>
                                        function help_switch_blockFunction() {
                                            document.getElementById('help_switch').style.display = 'block';
                                        }
                                        function help_switch_noneFunction() {
                                            document.getElementById('help_switch').style.display = 'none';
                                        }
                                    </script>
                                    <p id="help_switch" class="help_p">
                                        <?php echo _('helptext_switch_admin');
                                              echo '<br><br>'; ?>
                                        <button class="art-button" type="button" onclick="help_switch_noneFunction()"><?php echo _('close'); ?></button>
                                    </p>
                                    <!-------------------submit button -->    
                                    <table style="width: 100%; align: center;">
                                        <tr>
                                            <td rowspan=3><button class="art-button" name="admin_form_submit" type="submit" value="admin_form_submit" onclick="return confirm('<?php echo _('save'); echo ' '; echo _('administration'); ?>?')"><?php echo _('save'); ?></button></td>
                                        </tr>
                                    </table>
                                    </div>
                                </form>
                                <hr>

                                <h2 class="art-postheader"><?php echo _('defrost'); ?></h2>
                                <!-----------------------------------------------------------------------------Automatic Defrost-->
                                <form method="post" name="defrost">
                                    <div class="hg_container" >
                                        <table style="width: 100%;" class="miniature_writing">
                                            <?php
                                                $defrost_active = get_table_value_from_field($defrost_table, Null, $defrost_active_field);
                                                $defrost_circulate_air = get_table_value_from_field($defrost_table, Null, $defrost_circulate_air_field);
                                                $defrost_temperature = number_format(floatval(get_table_value_from_field($defrost_table, Null, $defrost_temperature_field)), 1, '.', '');
                                                $defrost_cycle_hours = get_table_value_from_field($defrost_table, Null, $defrost_cycle_hours_field);
                                                if ($defrost_active == 1) {
                                                    $checked_defrost_true = "checked";
                                                }
                                                else{
                                                    $checked_defrost_true = "";
                                                }
                                                if ($defrost_circulate_air == 1) {
                                                    $checked_circulate_air_true = "checked";
                                                }
                                                else{
                                                    $checked_circulate_air_true = "";
                                                }
                                            ?>
                                            <tr>
                                                <td rowspan="5" class="td_png_icon"><h3><?php echo _('defrost'); ?></h3><img src="images/icons/defrost_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_defrost_blockFunction()"><?php echo _('help'); ?></button></td>                                            
                                                <td class="text_left_padding"><?php echo _('defrost temperature offset'); ?>:</td>
                                                <td style="text-align: left;"><input name="defrost_temperature" type="number" min="1" max="22" step="0.1" style="width: 30%;" required value=<?php echo $defrost_temperature; ?>>&nbsp;°C<span style="font-size: xx-small"> (1 <?php echo _('to'); ?> 22)</span></td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding"><?php echo _('defrost cycle'); ?>:</td>
                                                <td style="text-align: left;"><input name="defrost_cycle_hours" type="number" min="1" max="24" step="1" style="width: 30%;" required value=<?php echo $defrost_cycle_hours; ?>>&nbsp;<?php echo _('hours'); ?><span style="font-size: xx-small"> (1 <?php echo _('to'); ?> 24)</span></td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding"><?php echo _('circulate air active'); ?>:</td>
                                                <td style="text-align: left;">
                                                    <input type="hidden" name="defrost_circulate_air" value="0">
                                                    <input type="checkbox" name="defrost_circulate_air" value="1" <?php echo $checked_circulate_air_true; ?>/>
                                                </td>
                                            </tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr>
                                                <td class="text_left_padding"><?php echo _('defrost active'); ?>:</td>
                                                <td style="text-align: left;">
                                                    <input type="hidden" name="defrost_active" value="0">
                                                    <input type="checkbox" name="defrost_active" value="1" <?php echo $checked_defrost_true; ?>/>
                                                </td>
                                            </tr>
                                            <tr><td>&nbsp;</td></tr>
                                        </table>

                                        <script>
                                            function help_defrost_blockFunction() {
                                                document.getElementById('help_defrost').style.display = 'block';
                                            }
                                            function help_defrost_noneFunction() {
                                                document.getElementById('help_defrost').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_defrost" class="help_p">
                                            <?php echo _('helptext_defrost');
                                                echo '<br><br>'; ?>
                                            <button class="art-button" type="button" onclick="help_defrost_noneFunction()"><?php echo _('close'); ?></button>
                                        </p>
                                        <table style="width: 100%; align: center;">
                                            <tr>
                                                <td><button class="art-button" name="save_defrost_values" value="save_defrost_values" onclick="return confirm('<?php echo _('ATTENTION: save defrost values?');?>');"><?php echo _('save'); ?></button></td>
                                            </tr>
                                        </table>                                    
                                    </div>
                                </form>
                                <hr>
                                
                                <h2 class="art-postheader"><?php echo _('reboot & shutdown'); ?></h2>
                                <!----------------------------------------------------------------------------------------Reboot/Shutdown-->
                                <div class="hg_container">
                                    <form  method="post" name="boot">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td><button class="art-button" name="reboot" onclick="return confirm('<?php echo _('ATTENTION: reboot system?');?>');"><?php echo _('reboot'); ?></button></td>
                                                <td><button class="art-button" name="shutdown" onclick="return confirm('<?php echo _('ATTENTION: shutdown system?');?>');"><?php echo _('shutdown'); ?></button></td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                                <hr>
                                <h2 class="art-postheader"><?php echo _('logging'); ?></h2>
                                <!----------------------------------------------------------------------------------------logging-->
                                <div class="hg_container" >
                                    <?php
                                        $loglevel_console = get_table_value($debug_table, $loglevel_console_key);
                                        $loglevel_file = get_table_value($debug_table, $loglevel_file_key);
                                        
                                        if ($loglevel_console == 10){
                                            $loglevel_console_selected_10 = 'selected="selected"';
                                        }
                                        else{
                                            $loglevel_console_selected_10 = '';
                                        }
                                        if ($loglevel_console == 20){
                                            $loglevel_console_selected_20 = 'selected="selected"';
                                        }
                                        else{
                                            $loglevel_console_selected_20 = '';
                                        }
                                        if ($loglevel_console == 30){
                                            $loglevel_console_selected_30 = 'selected="selected"';
                                        }
                                        else {
                                            $loglevel_console_selected_30 = '';
                                        }
                                        if ($loglevel_console == 40){
                                            $loglevel_console_selected_40 = 'selected="selected"';
                                        }
                                        else{
                                            $loglevel_console_selected_40 = '';
                                        }
                                        if ($loglevel_console == 50){
                                            $loglevel_console_selected_50 = 'selected="selected"';
                                        }
                                        else{
                                            $loglevel_console_selected_50 = '';
                                        }
                                        if ($loglevel_file == 10){
                                            $loglevel_pi_ager_logfile_selected_10 = 'selected="selected"';
                                        }
                                        else{
                                            $loglevel_pi_ager_logfile_selected_10 = '';
                                        }
                                        if ($loglevel_file == 20){
                                            $loglevel_pi_ager_logfile_selected_20 = 'selected="selected"';
                                        }
                                        else{
                                            $loglevel_pi_ager_logfile_selected_20 = '';
                                        }
                                        if ($loglevel_file == 30){
                                            $loglevel_pi_ager_logfile_selected_30 = 'selected="selected"';
                                        }
                                        else{
                                            $loglevel_pi_ager_logfile_selected_30 = '';
                                        }
                                        if ($loglevel_file == 40){
                                            $loglevel_pi_ager_logfile_selected_40 = 'selected="selected"';
                                        }
                                        else{
                                            $loglevel_pi_ager_logfile_selected_40 = '';
                                        }
                                        if ($loglevel_file == 50){
                                            $loglevel_pi_ager_logfile_selected_50 = 'selected="selected"';
                                        }
                                        else{
                                            $loglevel_pi_ager_logfile_selected_50 = '';
                                        }
                                    
                                    ?>
                                    <form method="post" name="logging">
                                        <label>Loglevel Console:
                                            <select name="loglevel_console">
                                                <option <?php echo $loglevel_console_selected_10 ; ?> value="10">DEBUG</option>
                                                <option <?php echo $loglevel_console_selected_20 ; ?> value="20">INFO</option>
                                                <option <?php echo $loglevel_console_selected_30 ; ?> value="30">WARNING</option>
                                                <option <?php echo $loglevel_console_selected_40 ; ?> value="40">ERROR</option>
                                                <option <?php echo $loglevel_console_selected_50 ; ?> value="50">CRITICAL</option>
                                            </select>
                                        </label>
                                        <br>
                                        <label>Loglevel pi_ager.log:
                                            <select name="loglevel_file" >
                                                <option <?php echo $loglevel_pi_ager_logfile_selected_10 ; ?> value="10">DEBUG</option>
                                                <option <?php echo $loglevel_pi_ager_logfile_selected_20 ; ?> value="20">INFO</option>
                                                <option <?php echo $loglevel_pi_ager_logfile_selected_30 ; ?> value="30">WARNING</option>
                                                <option <?php echo $loglevel_pi_ager_logfile_selected_40 ; ?> value="40">ERROR</option>
                                                <option <?php echo $loglevel_pi_ager_logfile_selected_50 ; ?> value="50">CRITICAL</option>
                                            </select>
                                        </label>
                                        <br><br>
                                        <button class="art-button" name="save_loglevel" value="save_loglevel" onclick="return confirm('<?php echo _('save loglevel?');?>');"><?php echo _('save'); ?></button>
                                    </form>
                                </div>
                                <?php
                                    if ($loglevel_console == 10 and $loglevel_file == 10) {
                                        echo '<hr>';
                                        echo '<h2 class="art-postheader">' . _('debug values') . '</h2>';
                                /* <!----------------------------------------------------------------------------------------Debugging--> */
                                        echo '<div class="hg_container" >';
                                        echo '<form method="post" name="debug">';
                                        echo '<table style="width: 100%;">';
                                        $agingtable_hours_in_seconds_debug = get_table_value($debug_table, $agingtable_hours_in_seconds_debug_key);
                                        echo '<tr>';
                                        echo '<td>' . _('agingtable hours in seconds') . ':</td>';
                                        echo '<td><input name="agingtable_hours_in_seconds_debug" type="number" style="width: 90%;" min="1" max="3600" required value=' . $agingtable_hours_in_seconds_debug . '></td>';
                                        echo '</tr>';
                                        echo '</table>';
                                        echo '<button class="art-button" name="save_debug_values" value="save_debug_values" onclick="return confirm(\'' . _('ATTENTION: save debug values?') . '\');">' . _('save') . '</button>';
                                        echo '</form>';
                                        echo '</div>';
                                    }
                                ?>
                                <hr>
                                <h2 class="art-postheader"><?php echo _('backup'); ?></h2>
                                <!----------------------------------------------------------------------------------------Backup-->
                                <div class="hg_container" >
                                    <form method="post" name="backup">
                                        <table style="width: 100%;">
                                            <?php
                                                $backup_nfsvol = get_table_value_from_field($backup_table, Null, $backup_nfsvol_field);
                                                // $backup_subdir = get_table_value_from_field($backup_table, Null, $backup_subdir_field);
                                                // $backup_nfsmount= get_table_value_from_field($backup_table, Null, $backup_nfsmount_field);
                                                // $backup_path = get_table_value_from_field($backup_table, Null, $backup_path_field);
                                                $backup_number_of_backups = get_table_value_from_field($backup_table, Null, $backup_number_of_backups_field);
                                                $backup_name = get_table_value_from_field($backup_table, Null, $backup_name_field);
                                                $backup_nfsopt = get_table_value_from_field($backup_table, Null, $backup_nfsopt_field);
                                                $backup_active = get_table_value_from_field($backup_table, Null, $backup_active_field);
                                                if($backup_active == 1) {
                                                    $checked_backup_true = "checked";
                                                }
                                                else{
                                                    $checked_backup_true = "";
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo _('nfs volume'); ?>:</td>
                                                <td><input name="backup_nfsvol" type="text" style="width: 100%; text-align: right;" required value=<?php echo $backup_nfsvol; ?>></td>
                                            </tr>
<!--
                                            <tr>
                                                <td><?php echo _('subdirectory'); ?>:</td>
                                                <td><input name="backup_subdir" type="text" style="width: 90%; text-align: right;" value=<?php echo $backup_subdir; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('nfs mount'); ?>:</td>
                                                <td><input name="backup_nfsmount" type="text" style="width: 90%; text-align: right; background-color: lightgrey;" readonly required value=<?php echo $backup_nfsmount; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('nfs path'); ?>:</td>
                                                <td><input name="backup_path" type="text" style="width: 90%; text-align: right; background-color: lightgrey;" readonly required value=<?php echo $backup_path; ?>></td>
                                            </tr>
-->                                            
                                            <tr>
                                                <td><?php echo _('number of backups'); ?>:</td>
                                                <td><input name="backup_number_of_backups" type="number" step="1" min="1" max="60" style="width: 100%; text-align: right;" required value=<?php echo $backup_number_of_backups; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('backup name'); ?>:</td>
                                                <td><input name="backup_name" type="text" style="width: 100%; text-align: right;" required value=<?php echo $backup_name; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('nfs opt'); ?>:</td>
                                                <td><input name="backup_nfsopt" type="text" style="width: 100%; text-align: right;" value="<?php echo $backup_nfsopt; ?>"></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('backup_active'); ?>:</td>
                                                <td style="text-align: left;">
                                                    <input type="hidden" name="backup_active" value="0">
                                                    <input type="checkbox" name="backup_active" value="1" <?php echo $checked_backup_true; ?>/>
                                                </td>
                                            </tr>
                                        </table>
                                        <button class="art-button" name="save_backup_values" value="save_backup_values" onclick="return confirm('<?php echo _('ATTENTION: save backup values?');?>');"><?php echo _('save'); ?></button>
                                    </form>
                                    <table style="width: 100%;">
                                        <tr>
                                            <form method="post" name="manual_backup">
                                                <?php 
                                                    //$grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
                                                    // $grepagingtable = shell_exec('ps ax | grep -v grep | grep agingtable.py');
                                                    $grepagingtable = intval(get_table_value($current_values_table, $status_agingtable_key));
                                                    if ($grepagingtable == 0){
                                                        echo '<td><button class="art-button" name="manual_backup" value="manual_backup" onclick="return confirm(\'' . _('ATTENTION: backup manually?') . '\');">' . _('backup manually') . '</button></td>';
                                                    }
                                                    else{
                                                        echo '<td>' . _('Agingtable is active. No backup possible!') . '</td>';
                                                    }
                                                ?>
                                            </form>
                                        </tr>
                                    </table>
                                    <button class="art-button" name="view_pi_ager_backup_logfile" onClick="window.open('/logs/pi-ager_backup.log');"><?php echo _('open pi-ager_backup.log in new tab'); ?></button>
                                    <table style="width: 100%; align: center;">
                                        <tr>
                                            <td style="text-align: left; padding-left: 10px;" >
                                                <button class="art-button" type="button" onclick="help_backup_blockFunction()"><?php echo _('help'); ?></button>
                                            </td>
                                        </tr>
                                    </table>

                                    <script>
                                        function help_backup_blockFunction() {
                                            document.getElementById('help_backup').style.display = 'block';
                                        }
                                        function help_backup_noneFunction() {
                                            document.getElementById('help_backup').style.display = 'none';
                                        }
                                    </script>
                                    <p id="help_backup" class="help_p">
                                        <?php echo _('helptext_backup');
                                              echo '<br><br>'; ?>
                                        <button class="art-button" type="button" onclick="help_backup_noneFunction()"><?php echo _('close'); ?></button>
                                    </p>
                                </div>
                                <hr>
                                
                                <h2 class="art-postheader"><?php echo _('database'); ?></h2>
                                <!----------------------------------------------------------------------------------------Database-->
                                <div class="hg_container" >                                    
                                    <table style="width: 100%;">
                                        <tr>
                                            <form method="post" name="database">
                                                <td><button class="art-button" name="empty_statistic_tables" value="empty_statistic_tables" onclick="return confirm('<?php echo _('ATTENTION: empty statistic tables?');?>');"><?php echo _('empty statistic tables'); ?></button></td>
                                            </form>
                                            <td><button class="art-button" name="database_administration" onclick="window.open('/phpliteadmin.php')"><?php echo _('database administration'); ?></button></td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <hr>
                                <h2 class="art-postheader"><?php echo _('Nextion Display Firmware'); ?></h2>
                                <!----------------------------------------------------------------------------------------Nextion display Firmware -->
                                <div class="hg_container" >
                                    <form method="post" name="nextion_display">
                                        <table style="width: 100%;" class="miniature_writing">
                                            <tr>
                                                <td class="td_png_icon"><h3><?php echo _('TFT Display'); ?></h3><img src="images/icons/touch_screen_44x44.png" alt=""></td>
                                                <td style=" text-align: left; padding-left: 20px;"><br>
                                                    <input type="radio" name="tft_display_type_admin" value="1" <?php echo $checked_tft_display_type_1; ?>/><label> NX3224K028 (Enhanced)</label><br>
                                                    <input type="radio" name="tft_display_type_admin" value="2" <?php echo $checked_tft_display_type_2; ?>/><label> NX3224F028 (Discovery)</label><br>
                                                    <input type="radio" name="tft_display_type_admin" value="3" <?php echo $checked_tft_display_type_3; ?>/><label> NX3224T028 (Basic)</label><br>
                                                    <br>
                                                </td>
                                            </tr>
                                        </table>
                                        <button class="art-button" name="save_nextion_display_type" value="save_nextion_display_type" onclick="return confirm('<?php echo _('ATTENTION: save Nextion display type?');?>');"><?php echo _('save'); ?></button>
                                    </form>
                                    <hr>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td>
                                                <button class="art-button" id="program_firmware" name="program_firmware" value="program_firmware" onclick="move_bar()"><?php echo _('start programming'); ?></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                             <!--   <progress style="width: 100%; height: 20px; position: relative;" id="programming" value="32" max="100"> 32% </progress> -->
                                                <div id="progress_label" class="progress" data-label="0% Complete">
                                                    <span id="upload_progress" class="value" style="width:0%;"></span>
                                                </div>
                                            <!--    <script> -->
                                            <!--    $("#progress_label").css("border", "3px solid red"); -->
                                            <!--    </script> -->
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%;">
                                        <hr>
                                        <tr>
                                            <form method="post" id="nextion" enctype="multipart/form-data">
                                                <td>
                                                    <label for="tft_file">
			                                        <?php echo _('alternative firmware file:');?> <input type="file" name="tft_file" id="tft_file" accept=".zip" onchange="enableButton()">
                                                    </label>
                                                </td>
                                            </form>
                                            <td style=" text-align: left; ">
                                                <script>
                                                    function enableButton() {
                                                        document.getElementById("upload_nextion_firmware").disabled = false;
                                                    }
                                                </script>
                                                <button class="art-button" disabled id="upload_nextion_firmware" form="nextion" name="upload_nextion_firmware" value="upload_nextion_firmware" onclick="return confirm('<?php echo _('upload new firmware file?');?>');"><?php echo _('upload'); ?></button>
<!--                                                <script>
                                                    function enableProgrammingButton(){
                                                        if (confirm('upload new firmware file?')) { 
                                                            //document.getElementById("program_firmware").disabled = false;
                                                            return true;
                                                        }
                                                        return false;
                                                    }
                                                </script> -->
                                            </td>
                                        </tr>
                                        
                                        <table style="width: 100%; align: center;">
                                            <tr>
                                                <td style="text-align: left; padding-left: 10px;" >
                                                    <button class="art-button" type="button" onclick="help_display_blockFunction()"><?php echo _('help'); ?></button>
                                                </td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_display_blockFunction() {
                                                document.getElementById('help_display').style.display = 'block';
                                            }
                                            function help_display_noneFunction() {
                                                document.getElementById('help_display').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_display" class="help_p">
                                            <?php echo _('helptext_display');
                                                echo '<br><br>'; ?>
                                            <button class="art-button" type="button" onclick="help_display_noneFunction()"><?php echo _('close'); ?></button>
                                        </p>
                                    </table>
                                </div>
                                
                                <script>
                                                                    
                                    var first_action = false;
                                    var second_action = false;
                                
                                    function show_error( msg ) {
                                        $('#progress_label').css('border', '3px solid red');
                                        $('#progress_label').attr('data-label', msg);
                                        $("#program_firmware").attr("disabled", false);
                                    }
                                    
                                    async function do_finalize( success ) {
                                        $.ajax({
                                            method: 'POST',
                                            url: 'nextion_control.php?q=finalize',
                                        })
                                        .done(function( msg ) {
                                            //console.log('programming :' + msg);
                                            if (msg != 'ready') {
                                                show_error( msg );
                                            }
                                            else {
                                                if (success == true) {
                                                    $('#progress_label').attr('data-label', 'ready');
                                                }
                                                $("#program_firmware").attr("disabled", false);
                                            }
                                        })
                                        .fail(function(xhr, textStatus) {
                                            show_error(textStatus);
                                            console.log( "Ajax failed: " + xhr.statusText);
                                            console.log(textStatus);
                                            //console.log(error);
                                        });
                                    }
                                    
                                    async function do_programming() {
                                        while (true) {
                                            $.ajax({
                                                method: 'POST',
                                                url: 'nextion_control.php?q=program',
                                            })
                                            .done(function( msg ) {
                                                //console.log('programming :' + msg);
                                                try {
                                                    programming_status = JSON.parse(msg);
                                                    // progress in 0.2 % units
                                                    progress_tmp = programming_status.progress/5.0;
                                                    progress = progress_tmp.toFixed(1);
                                                    status = programming_status.status;
                                                    //console.log('second ajax function done. status :' + status);
                                                    $('#upload_progress').css('width', progress + '%');
                                                    $('#progress_label').attr('data-label', progress + '%' + ' complete');
                                                    second_action = true;
                                                }
                                                catch(err) {
                                                    console.log('error: ' + err);
                                                    $("#program_firmware").attr("disabled", false);
                                                }
                                            })
                                            .fail(function(xhr, textStatus) {
                                                show_error(textStatus);
                                                console.log( "Ajax failed: " + xhr.statusText);
                                                console.log(textStatus);
                                                //console.log(error);
                                                second_action = false;
                                            });
                                            
                                            await new Promise(resolve => setTimeout(resolve, 1000));
                                            
                                            if (second_action == false || status == 'failed') {
                                                //console.log('break 1');
                                                show_error('HMI display flashing failed. Check cables.');
                                                do_finalize( false );
                                                break;
                                            }
                                            if (status == 'idle' || status == 'running') {
                                                //console.log('continue');
                                                // await new Promise(resolve => setTimeout(resolve, 3000));   
                                                continue;
                                            }
                                            if (status == 'success') {
                                                //console.log('break 2');
                                                $('#progress_label').attr('data-label', 'Starting Pi-Ager Service now');
                                                do_finalize( true );
                                                break;
                                            }
                                        }
                                    }
                                    
                                    async function move_bar() {
                                        $('#progress_label').css('border', '3px solid #8c8c8c');
                                        $('#progress_label').attr('data-label', 'preparing...');
                                        $('#upload_progress').css('width', '0%');
                                        $("#program_firmware").attr("disabled", true);
                                        if (confirm("<?php echo _('Pi-Ager service will be stopped to flash the HMI display firmware.\nAn active aging table will be stopped.\nDuring firmware programming:\nDo NOT leave the current web page!\nDo NOT close your web browser!\nIf you are using a smartphone or tablet, do NOT allow to let your device to be deactivated by inactivity timeout!\nContinue ?');?>")) {
                                            $.ajax({
                                                method: 'POST',
                                                url: 'nextion_control.php?q=check',
                                            })
                                            .done(function( msg ) {
                                                if (msg != 'ready') {
                                                    show_error( msg );
                                                }
                                                else {
                                                    $('#progress_label').attr('data-label', 'ready');
                                                    //console.log('First ajax done');
                                                    do_programming();
                                                }
                                            })
                                            .fail(function(xhr, textStatus) {
                                                show_error(textStatus);
                                                console.log( "First ajax failed: " + xhr.statusText);
                                                console.log(textStatus);
                                                //console.log(error);
                                            });
                                            
                                            return;
                                        }
                                    }

                                </script> 
                                
                                <?php
                                    function validateDate($date, $format = 'Y-m-d H:i:s') {
                                        $d = DateTime::createFromFormat($format, $date);
                                        return $d && $d->format($format) == $date;
                                    }

                                    if (isset ($_POST['setdatetime'])){
                                        $newdatetime = $_POST['newdatetime'];
                                        if (validateDate($newdatetime) == true) {
                                            shell_exec('sudo /var/sudowebscript.sh set_time_date ' . '"' . $newdatetime . '"');
                                            echo '<script language="javascript"> alert("'. _('Date/Time') . ' : ' . _('new system date and time set') . '"); </script>';                                
                                        }
                                        else {
                                            echo '<script language="javascript"> alert("'. _('Date/Time') . ' : ' . _('wrong input format') . '"); </script>';
                                        }
                                    }
                                    // $essid = exec("iwgetid | awk -F'\"' '{print $2}'");
                                    // echo 'ESSID : ' . $essid . "<br>";
                                    $ap_mode = false;
                                    $local_ip = $_SERVER['REMOTE_ADDR'];
                                    // echo 'local ip : ' . $local_ip . '<br>';
                                    if (strpos($local_ip, '10.0.0') !== false) {
                                        $ap_mode = true;
                                    //    echo 'ap_mode : ' . $ap_mode . '<br>';
                                    }
                                    // $hostname = '';
                                    // $addresses = exec("hostname -I");
                                    // echo 'hostname -I : ' . $addresses . '<br>';
                                    
                                    // if (strpos($addresses, '10.0.0.1') !== false) {
                                    //    $ap_mode = true;
                                    //    echo 'ap_mode : ' . $ap_mode . '<br>';
                                    // }
                                    // $address_list = explode(" ", $addresses);
                                    // $address_list_count = count($address_list);
                                    // if ($address_list_count == 0) {
                                    //    $hostname = "";
                                    // }
                                    // else {
                                    //    $hostname = $address_list[0];
                                    // }                                                   
                                    // $hostname = exec("hostname -I");
                                    // echo 'hostname -I : ' . $hostname . '<br>';
                                    // if ($local_ip !== '10.0.0.5') {
                                    // if ($essid === 'RPiHotspot') {
                                ?>
                                <div <?php if ($ap_mode == false) { echo 'style="display:none;"'; }?>>
                                    <hr>
                                    <h2 class="art-postheader"><?php echo _('set Pi-Ager date/time'); ?></h2>
                                    <!----------------------------------------------------------------------------------------Date/Time--> 
                                    <div class="hg_container" >
                                        <form method="post" name="datetime">
                                            <table style="width: 100%;">
                                                <tr>
                                                    <td><label><?php echo _('Pi-Ager date/time format YYYY-MM-DD HH:MM:SS') . ' :&nbsp;'; ?></label><div class="tooltip"><input type="text" size="19" maxlength="19" name="newdatetime"><span class="tooltiptext"><?php echo _('date/time format YYYY-MM-DD HH:MM:SS e.g. 2023-01-31 16:05:09'); ?></span></input></div></td>
                                                </tr>
                                            </table>
                                            <br>
                                            <button class="art-button" name="setdatetime" value="setdatetime" onclick="return confirm('<?php echo _('ATTENTION: set date and time?');?> ')"><?php echo _('save'); ?></button>
                                        </form>
                                    </div>
                                </div>

                                
                                <div id="wlan_setup_id" <?php if ($ap_mode == false) { echo 'style="display:none;"'; }?>>
                                    <hr>
                                    <h2 class="art-postheader"><?php echo _('WLAN setup'); ?></h2>
                                    <?php
                                        $ssids_with_signal = [];
                                        $ssid_count = 0;
                                        if ($ap_mode == true) {
                                            $res = null;
                                            # exec('sudo /var/show_wifi_connections.sh', $ssids, $res);
                                            exec('sudo nmcli -t -f SSID,SIGNAL device wifi list --rescan yes ifname wlan0', $ssids_with_signal, $res);
                                            // $out = shell_exec('sudo /var/show_wifi_connections.sh 2>&1');
                                            $ssid_count = count($ssids_with_signal);
                                            # echo 'return status from show_wifi_connections : ' . $res . '<br>';
                                            # var_dump($ssids);
                                            $ssids_signals = array();
                                            foreach ($ssids_with_signal as $ssid_with_signal) {
                                                $ssids_signals[] = explode(':', $ssid_with_signal);
                                            }
                                        }
                                    ?>
                                    <div class="hg_container">
                                        <table style="width: 100%; align: center;">
                                            <tr>
                                                <td style="text-align: left; padding-left: 10px;" >
                                                    <button id="refresh_wlan_ssids" class="art-button" onclick="refresh_wlan_ssids()"><?php echo _('refresh'); ?></button>
                                                </td>
                                            </tr>
                                        </table>

                                        <form method="post" name="wlansetup">
                                            <table style="width: 100%;">
                                                <tr>
                                                    <td width="50%" style="text-align: left; padding-left: 20px;">SSID</td>
                                                    <td width="10%" style="text-align: left;">SIGNAL</td>
                                                </tr><br>
                                                    <?php
                                                        if ($ssid_count == 0) {
                                                            echo _('No WLAN networks found') . '<br>';
                                                        }
                                                        else {
                                                            $si = 0;
                                                            foreach ($ssids_signals as $ssid_signal) {
                                                                if ($ssid_signal[0] != '') {
                                                                    echo '<tr>';
                                                                    if ($si == 0) {
                                                                        echo '<td style="text-align: left; padding-left: 20px;"><input type="radio" name="ssid_selected" value="' . $ssid_signal[0] . '" checked="checked" /><label>&nbsp;' . $ssid_signal[0] . '</label></td>';
                                                                    }
                                                                    else {
                                                                        echo '<td style="text-align: left; padding-left: 20px;"><input type="radio" name="ssid_selected" value="' . $ssid_signal[0] . '" /><label>&nbsp;' . $ssid_signal[0] . '</label></td>';
                                                                    }
                                                                    echo '<td style="text-align: left;">' . $ssid_signal[1] . '</td>';
                                                                    echo '</tr>';
                                                                }
                                                                $si++;
                                                            }
                                                        }
                                                    ?>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left; padding-left: 20px;" colspan="2"><label><?php echo _('WLAN password');?>&nbsp;</label><div class="tooltip"><input id="txtPasswd" type="text" name="wlanpassword" <?php if ($ssid_count == 0) { echo ' disabled';} ?> required><span class="tooltiptext"><?php echo _('enter your WLAN password'); ?></span></input></div></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left; padding-left: 20px;" colspan="2"><label><?php echo _('WLAN Country'); ?>&nbsp;</label><div class="tooltip"><input style="max-width: 22px;" id="txtCountry" type="text" maxlength="2" name="wlancountry" required><span class="tooltiptext"><?php echo _('e.g. DE,GB,FR,AT,CH,US'); ?></span></input></div></td>
                                                </tr>
                                            </table>
                                            <br>
                                            <button class="art-button" name="setWLANconfig" value="setWLANconfig" <?php if ($ssid_count == 0) { echo 'disabled';} ?> onclick="return confirm('<?php echo _('ATTENTION: setup your WLAN?');?> ')"><?php echo _('save'); ?></button>
                                        </form>
                                    </div>
                                </div>
                                
                                <script>
                                    function Sleep(milliseconds) {
                                        return new Promise(resolve => setTimeout(resolve, milliseconds));
                                    }
                                    
                                    async function refresh_wlan_ssids() {
                                        $('*').css('cursor', 'wait');
                                        $('#wlan_setup_id').load('admin.php #wlan_setup_id');
                                        await Sleep(2000);
                                        $('*').css('cursor', 'default');
                                    }
                                </script>
                                
                                <hr>

                                <h2 class="art-postheader"><?php echo _('Pi-Ager Accesspoint'); ?></h2>
                                <!--------------------------------------------------------------------------- accesspoint password --> 
                                <div class="hg_container" >
                                    <form method="post" name="accesspoint">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td><label><?php echo _('new password for accesspoint') . ' :&nbsp;'; ?></label><div class="tooltip"><input id="input_password" type="text" size="19" maxlength="19" name="new_password" oninput="handle_password_input(this.value)" ><span class="tooltiptext"><?php echo _('password minimum length is 8 character'); ?></span></input></div></td>
                                            </tr>
                                        </table>
                                        <br>
                                        <button id="set_new_password" class="art-button" name="set_new_password" value="set_new_password" disabled onclick="return confirm('<?php echo _('ATTENTION: reboot follows after saving new accesspoint password');?> ')"><?php echo _('save'); ?></button>
                                    </form>
                                </div>
                                
                                <script>
                                    function handle_password_input(val) {
                                        if (val.length >= 8) {
                                            $("#set_new_password").attr("disabled", false);
                                        }
                                        else {
                                            $("#set_new_password").attr("disabled", true);
                                        }
                                    }
                                </script>
                                
                                <?php
                                    if ($loglevel_console == 10 and $loglevel_file == 10){
                                        echo '<hr>';
                                        echo '<h2 class="art-postheader">' . _('python') . '</h2>';
                                        /*<!----------------------------------------------------------------------------------------stop start pythonfiles--> */
                                        echo '<div class="hg_container">';
                                            echo '<form  method="post" name="boot">';
                                                echo '<table style="width: 100%;">';
                                                    echo '<tr>';
                                                        echo '<td><button class="art-button" name="admin_stop_main" value="admin_stop_main" onclick="return confirm(\'' . _('ATTENTION: stop') . ' main.py service?' . '\');">' . _('stop') . ' main.py</button></td>';
                                                        echo '<td><button class="art-button" name="admin_start_main" value="admin_start_main" onclick="return confirm(\'' .  _('start') . ' main.py service?' . '\');">' . _('start') . ' main.py</button></td>';
                                                    echo '</tr></tr>';
                                                        // echo '<td><button class="art-button" name="admin_stop_agingtable"  value="admin_stop_agingtable" onclick="return confirm("' . _('ATTENTION:') . 'kill agingtable.py?");">' . _('stop') . ' agingtable.py</button></td>';
                                                        // echo '<td><button class="art-button" name="admin_start_agingtable"  value="admin_start_agingtable" onclick="return confirm("' . _('start') . 'agingtable.py?");">' . _('start') . '  agingtable.py</button></td>';
                                                    echo '</tr></tr>';
                                                        // echo '<td><button class="art-button" name="admin_stop_scale"  value="admin_stop_scale" onclick="return confirm("' . _('ATTENTION: kill') . 'scale.py?");">' . _('stop') . '  scale.py</button></td>';
                                                        // echo '<td><button class="art-button" name="admin_start_scale"  value="admin_start_scale" onclick="return confirm("' . _('start') . ' scale.py?");">' . _('start') . '  scale.py</button></td>';
                                                    echo '</tr>';
                                                echo '</table>';
                                            echo '</form>';
                                        echo '</div>';
                                    }
                                ?>
                                
                                <!----------------------------------------------------------------------------------------Content Ende-->
                                
                                <script>
                                    if ( window.history.replaceState ) {    // avoid page confirmation on refresh
                                        window.history.replaceState( null, null, window.location.href );
                                    }
                                </script>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--        </div> -->
        <?php 
            include 'footer.php';
        ?>
