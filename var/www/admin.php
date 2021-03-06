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
                                    include 'modules/write_backup_db.php';                      // schreibt die backup-Werte
                                    
                                    include 'modules/read_config_db.php';                       // Liest die Grundeinstellungen Sensortyp, Hysteresen, GPIO's)
                                    include 'modules/read_current_db.php';
                                    include 'modules/start_stop_uv.php';
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
                                <form method="post" name="admin">
                                    <div class="hg_container">
                                        <!----------------------------------------------------------------------------------------Sensortype-->
                                        <table style="width: 100%;" class="miniature_writing">
                                            <tr>
                                                <td style="width: 100px;"></td>
                                                <td style="width: 150px; text-align: left; padding-left: 20px;"><h3><?php echo _('internal'); ?></h3></td>
                                                <td style="width: 100px; "></td>
                                                <td style="width: 150px; text-align: left; padding-left: 20px;"><h3><?php echo _('external'); ?></h3></td>
                                            </tr>
                                            <tr>
                                                <td class="td_png_icon"><h3><?php echo _('sensortype'); ?></h3><img src="images/icons/sensortype_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_sensortype_blockFunction()"><?php echo _('help'); ?></button>
                                                </td>
                                                <td style=" text-align: left; padding-left: 20px;">
                                                    <input type="radio" name="sensortype_admin" value="1" <?php echo $checked_sens_1; ?>/><label><span style="color: #7A5800 !important"> DHT11</span></label><br>
                                                    <input type="radio" name="sensortype_admin" value="2" <?php echo $checked_sens_2; ?>/><label><span style="color: #7A5800 !important"> DHT22</span></label><br>
                                                    <input type="radio" name="sensortype_admin" value="3" <?php echo $checked_sens_3; ?>/><label><span style="color: #7A5800 !important"> SHT75</span></label><br>
                                                    <input type="radio" name="sensortype_admin" value="4" <?php echo $checked_sens_4; ?>/><label> SHT85</label><br>
                                                    <input type="radio" name="sensortype_admin" value="5" <?php echo $checked_sens_5; ?>/><label> SHT3x</label><br>
                                                    <br>
                                                </td>
                                                <td></td>
                                                <td style=" text-align: left; padding-left: 20px;">
                                                    <?php 
                                                        if ($sens_second_active != ''){
                                                            echo '<ul>' . _('only configurable if internal sensor is set to sht3x or sht85') . '</ul><br><br>';
                                                            echo '<input type="hidden" name="sensorsecondtype_admin_inaktive" value="' . $sensorsecondtype . '" />';
                                                        }
                                                    ?>
                                                    <input type="radio" name="sensorsecondtype_admin" value="0" <?php echo $checked_senssecond_0 . ' ' . $sens_second_active; ?>/><label> disabled</label><br>
                                                    <!--
                                                    <input type="radio" name="sensorsecondtype_admin" value="1" <?php echo $checked_senssecond_1 . ' ' . $sens_second_active; ?>/><label> DHT11</label><br>
                                                    <input type="radio" name="sensorsecondtype_admin" value="2" <?php echo $checked_senssecond_2 . ' ' . $sens_second_active; ?>/><label> DHT22</label><br>
                                                    <input type="radio" name="sensorsecondtype_admin" value="3" <?php echo $checked_senssecond_3 . ' ' . $sens_second_active; ?>/><label> SHT75</label><br> -->
                                                    <input type="radio" name="sensorsecondtype_admin" value="4" <?php echo $checked_senssecond_4 . ' ' . $sens_second_active; ?>/><label> SHT85</label><br>
                                                    <input type="radio" name="sensorsecondtype_admin" value="5" <?php echo $checked_senssecond_5 . ' ' . $sens_second_active; ?>/><label> SHT3x</label><br>
                                                    <br>
                                                </td>
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
                                                <td style="width: 100px;"></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td><br><button class="art-button" name="change_sensorbus_submit" value="change_sensorbus_submit" onclick="return confirm('<?php echo _('ATTENTION: a shutdown is required, please turn the power off/on to restart the system!');?>');"><?php echo _('change sensors'); ?></button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>
                                        
                                <form method="post" name="admin">
                                    <div class="hg_container">
                                        <!----------------------------------------------------------------------------------------Waagen-->
                                    <table style="width: 100%;" class="miniature_writing">
                                        <tr>
                                            <td rowspan="16" class="td_png_icon"><h3><?php echo _('scales'); ?></h3><img src="images/icons/scale_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_scales_blockFunction()"><?php echo _('help'); ?></button></td>
                                            <td colspan="3"><h3><?php echo _('scale'); ?> 1</h3></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo _('reference unit'); ?>:</td>
                                            <td class="text_left_padding"><input name="referenceunit_scale1_admin" type="number" style="width: 90%;" maxlength="4" size="2" step="0.1" min="0.1" max="999.9" required value=<?php echo $referenceunit_scale1; ?>></td>
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
                                            <td class="text_left_padding"><input name="referenceunit_scale2_admin" type="number" style="width: 90%;" step="0.1" min="0.1" max="999.9" required value=<?php echo $referenceunit_scale2; ?>></td>
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
                                            <td rowspan="6" class="td_png_icon"><h3><?php echo 'Thermometer'; ?></h3><img src="images/icons/temperature_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_thermometer_blockFunction()"><?php echo _('help'); ?></button></td>
                                        </tr>
                                        <tr>
                                            <td class="text_left_padding"><?php echo 'Sensor 1'; ?>:</td>
                                            <td class="text_left_padding">
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
                                                            if ($meatsensor_row[id] == $meatsensor_index)
                                                            {
                                                                echo '<option value="'.$meatsensor_row[id].'" selected>'.$meatsensor_row[name].'</option>';
                                                            }
                                                            else
                                                            {
                                                                echo '<option value="'.$meatsensor_row[id].'">'.$meatsensor_row[name].'</option>';
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
                                            <td class="text_left_padding">
                                            <?php 
                                                if (isset($meat_sensors))
                                                {
                                                    $meatsensor_index = intval($meat2_sensortype);
                                                    echo '<select name="temp_sensor2_admin">';
                                                    foreach($meat_sensors as $meatsensor_row)
                                                    {
                                                        if (strncmp($meatsensor_row['name'], 'LEM', 3) !== 0)
                                                        {
                                                            if ($meatsensor_row[id] == $meatsensor_index)
                                                            {
                                                                echo '<option value="'.$meatsensor_row[id].'" selected>'.$meatsensor_row[name].'</option>';
                                                            }
                                                            else
                                                            {
                                                                echo '<option value="'.$meatsensor_row[id].'">'.$meatsensor_row[name].'</option>';
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
                                            <td class="text_left_padding">
                                            <?php 
                                                if (isset($meat_sensors))
                                                {
                                                    $meatsensor_index = intval($meat3_sensortype);
                                                    echo '<select name="temp_sensor3_admin">';
                                                    foreach($meat_sensors as $meatsensor_row)
                                                    {
                                                        if (strncmp($meatsensor_row['name'], 'LEM', 3) !== 0)
                                                        {
                                                            if ($meatsensor_row[id] == $meatsensor_index)
                                                            {
                                                                echo '<option value="'.$meatsensor_row[id].'" selected>'.$meatsensor_row[name].'</option>';
                                                            }
                                                            else
                                                            {
                                                                echo '<option value="'.$meatsensor_row[id].'">'.$meatsensor_row[name].'</option>';
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
                                            <td class="text_left_padding">
                                            <?php 
                                                if (isset($meat_sensors))
                                                {
                                                    $meatsensor_index = intval($meat4_sensortype);
                                                    echo '<select name="temp_sensor4_admin">';
                                                    foreach($meat_sensors as $meatsensor_row)
                                                    {
                                                        if ($meatsensor_row[id] == $meatsensor_index)
                                                        {
                                                            echo '<option value="'.$meatsensor_row[id].'" selected>'.$meatsensor_row[name].'</option>';
                                                        }
                                                        else
                                                        {
                                                            echo '<option value="'.$meatsensor_row[id].'">'.$meatsensor_row[name].'</option>';
                                                        }
                                                    }
                                                    echo '</select>';
                                                }
                                            ?>
                                            </td>
                                        </tr>                                        
                                    </table>
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
                                        <table style="width: 100%; align: center;">
                                            <tr>
                                                <td rowspan=3><button class="art-button" name="admin_form_submit" type="submit" value="admin_form_submit" onclick="return confirm('<?php echo _('save'); echo ' '; echo _('administration 1'); ?>?')"><?php echo _('save'); ?></button></td>
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
                                <hr>
                                <h2 class="art-postheader"><?php echo _('debug values'); ?></h2>
                                <!----------------------------------------------------------------------------------------Debugging-->
                                <div class="hg_container" >
                                    <form method="post" name="debug">
                                        <table style="width: 100%;">
                                            <?php
                                                // $measuring_interval_debug = get_table_value($debug_table, $measuring_interval_debug_key);
                                                $agingtable_days_in_seconds_debug = get_table_value($debug_table, $agingtable_days_in_seconds_debug_key);
                                            ?>
                                             <!-- <tr>
                                                <td><?p hp echo _('measuring interval'); ?>:</td>
                                                <td><input name="measuring_interval_debug" type="number" style="width: 90%;" min="1" max="60" required value=<?p hp echo $measuring_interval_debug; ?>></td>
                                            </tr> -->
                                            <tr>
                                                <td><?php echo _('agingtable days in seconds'); ?>:</td>
                                                <td><input name="agingtable_days_in_seconds_debug" type="number" style="width: 90%;" min="1" max="86400" required value=<?php echo $agingtable_days_in_seconds_debug; ?>></td>
                                            </tr>

                                        </table>
                                        <button class="art-button" name="save_debug_values" value="save_debug_values" onclick="return confirm('<?php echo _('ATTENTION: save debug values?');?>');"><?php echo _('save'); ?></button>
                                    </form>
                                </div>
                                <hr>
                                <h2 class="art-postheader"><?php echo _('backup'); ?></h2>
                                <!----------------------------------------------------------------------------------------Backup-->
                                <div class="hg_container" >
                                    <form method="post" name="backup">
                                        <table style="width: 100%;">
                                            <?php
                                                $backup_nfsvol = get_table_value_from_field($backup_table, Null, $backup_nfsvol_field);
                                                $backup_subdir = get_table_value_from_field($backup_table, Null, $backup_subdir_field);
                                                $backup_nfsmount= get_table_value_from_field($backup_table, Null, $backup_nfsmount_field);
                                                $backup_path = get_table_value_from_field($backup_table, Null, $backup_path_field);
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
                                                <td><input name="backup_nfsvol" type="text" style="width: 90%; text-align: right;" required value=<?php echo $backup_nfsvol; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('subdirectory'); ?>:</td>
                                                <td><input name="backup_subdir" type="text" style="width: 90%; text-align: right;" required value=<?php echo $backup_subdir; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('nfs mount'); ?>:</td>
                                                <td><input name="backup_nfsmount" type="text" style="width: 90%; text-align: right;" required value=<?php echo $backup_nfsmount; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('nfs path'); ?>:</td>
                                                <td><input name="backup_path" type="text" style="width: 90%; text-align: right;" required value=<?php echo $backup_path; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('number of backups'); ?>:</td>
                                                <td><input name="backup_number_of_backups" type="number" step="1" min="1" max="60" style="width: 90%;" required value=<?php echo $backup_number_of_backups; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('backup name'); ?>:</td>
                                                <td><input name="backup_name" type="text" style="width: 90%; text-align: right;" required value=<?php echo $backup_name; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('nfs opt'); ?>:</td>
                                                <td><input name="backup_nfsopt" type="text" style="width: 90%; text-align: right;" value="<?php echo $backup_nfsopt; ?>"></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('backup_active'); ?>:</td>
                                                <td>
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
                                            <td><button class="art-button" name="database_administration" onclick="window.location.href='/phpliteadmin.php'"><?php echo _('database administration'); ?></button></td>
                                        </tr>
                                    </table>
                                </div>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--        </div> -->
        <?php 
            include 'footer.php';
        ?>
