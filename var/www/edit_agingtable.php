<?php 
                                    include 'header.php';                                     // Template-Kopf und Navigation
                                    include 'modules/names.php';                              // Variablen mit Strings
                                    include 'modules/database.php';                           // Schnittstelle zur Datenbank
                                    include 'modules/logging.php';                            //liest die Datei fuer das logging ein
                                    include 'modules/write_table_db.php'
?>
                                <h2 class="art-postheader"><?php
                                    if ((isset ($_POST['edit_agingtable'])) OR (isset($POST['edit_agingtable_form_submit']))){
                                        unset($_POST['edit_agingtable']);
                                        unset($_POST['edit_agingtable_form_submit']);
                                        $agingtable_to_edit = $_POST['agingtable_edit'];
                                        
                                        // write_agingtable($chosen_agingtable);
                                        $logstring = 'button edit agingtable pressed';
                                        logger('DEBUG', $logstring );
                                    }
                                    elseif (isset ($_SESSION['agingtable_to_edit'])){
                                        $agingtable_to_edit_session = $_SESSION['agingtable_to_edit'];
                                        //var_dump ($agingtable_to_edit_session);
                                        $agingtable_to_edit = $agingtable_to_edit_session;
                                        $logstring = 'back to edit agingtable';
                                        logger('DEBUG', $logstring );
                                        
                                    }else {
                                        print '<script language="javascript"> alert("'. (_("edit agingtable")) . " : " . (_("no agingtable selected")) .'"); window.location.href = "settings.php";</script>';
                                    }
                                    $id_agingtable_to_edit = get_agingtable_id_by_name($agingtable_to_edit);
                                    $id_chosen_agingtable = get_table_value($config_settings_table, $agingtable_key);
                                    $grepagingtable = intval(get_table_value($current_values_table, $status_agingtable_key));
                                    if ($id_agingtable_to_edit == $id_chosen_agingtable && $grepagingtable == 1){
                                        print '<script language="javascript"> alert("'. (_("edit agingtable")) . " : " . (_("can not edit the controlling agingtable. please select another")) .'"); window.location.href = "settings.php";</script>';
                                        //header ( 'Location: settings.php' );
                                    }
                                    echo strtoupper(_('edit agingtable:'));
                                    echo ' ' . strtoupper($agingtable_to_edit);
                                    
                                    ?></h2>

                                <form method="post">
                                    <table id="show_agingtable" class="show_agingtable" style="table-layout: fixed;">
                                        <tr style="background-color: #F0F5FB; border-bottom: 1px solid #000033">
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('phase') ?><span class="tooltiptext"><?php echo _('phase'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('modus') ?><span class="tooltiptext"><?php echo _('aging-modus'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">&phi;<span class="tooltiptext"><?php echo _('target humidity in %'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip">°C<span class="tooltiptext"><?php echo _('target temperature in °C'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('timer circulate d') ?><span class="tooltiptext"><?php echo _('timer of the circulation air duration in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('timer circulate p') ?><span class="tooltiptext"><?php echo _('timer of the circulation air period in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('timer exhaust d') ?><span class="tooltiptext"><?php echo _('timer of the exhausting air duration in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('timer exhaust p') ?><span class="tooltiptext"><?php echo _('timer of the exhausting air period in minutes'); ?></span></div></td>
                                            <td class="show_agingcell"><div class="tooltip"><?php echo _('days') ?><span class="tooltiptext"><?php echo _('duration of hanging phase in days'); ?></span></div></td>
                                        </tr>

                                        <?php 
                                            // Gewählte Agingtable aus DB auslesen und als Tabelle beschreiben
                                            $index_row = 0;
                                            $agingtable_rows = get_agingtable_dataset($agingtable_to_edit);
                                            $firstrow = $agingtable_rows[0];
                                            $agingtable_comment = $firstrow[$agingtable_comment_field];
                                            $row_id_array = array();
                                            try {
                                                $number_rows = count($agingtable_rows);
                                                while ($index_row < $number_rows) {
                                                    $dataset = $agingtable_rows[$index_row];
                                                    $row_id_array[($index_row + 1)] = $dataset['id'];
                                                    if (!empty($dataset[$agingtable_modus_field])){
                                                        $data_modus = $dataset[$agingtable_modus_field];
                                                    } else {$data_modus = '';}
                                                    if (!empty($dataset[$agingtable_setpoint_humidity_field])){
                                                        $data_setpoint_humidity = $dataset[$agingtable_setpoint_humidity_field];
                                                    } else {$data_setpoint_humidity = '';}
                                                    if (!empty($dataset[$agingtable_setpoint_temperature_field])){
                                                        $data_setpoint_temperature = $dataset[$agingtable_setpoint_temperature_field];
                                                    } else {$data_setpoint_temperature = '';}
                                                    if (!empty($dataset[$agingtable_circulation_air_duration_field])){
                                                        $data_circulation_air_duration = $dataset[$agingtable_circulation_air_duration_field]/60;
                                                    } else {$data_circulation_air_duration = '';}
                                                    if (!empty($dataset[$agingtable_circulation_air_period_field])){
                                                        $data_circulation_air_period = $dataset[$agingtable_circulation_air_period_field]/60;
                                                    } else {$data_circulation_air_period = '';}
                                                    if (!empty($dataset[$agingtable_exhaust_air_duration_field])){
                                                        $data_exhaust_air_duration = $dataset[$agingtable_exhaust_air_duration_field]/60;
                                                    } else {$data_exhaust_air_duration = '';}
                                                    if (!empty($dataset[$agingtable_exhaust_air_period_field])){
                                                        $data_exhaust_air_period = $dataset[$agingtable_exhaust_air_period_field]/60;
                                                    } else {$data_exhaust_air_period = '';}
                                                    if (!empty($dataset[$agingtable_days_field])){
                                                        $data_days = $dataset[$agingtable_days_field];
                                                    } else {$data_days = '';}
                                                    echo '<tr>';
                                                    echo '<td> ' . ($index_row +1) . '</td>';
                                                    echo '<td><input name="data_modus_edit_agingtable_' . $index_row .'" maxlength="4" style="width: 90%;" size="1" min="1" max="4" type = "number" value=' . $data_modus . '></td>';
                                                    echo '<td><input name="data_setpoint_humidity_edit_agingtable_' . $index_row .'" maxlength="4" style="width: 90%;" size="1" min="-1" max="100" type = "number" value=' . $data_setpoint_humidity . '></td>';
                                                    echo '<td><input name="data_setpoint_temperature_edit_agingtable_' . $index_row .'" maxlength="4" style="width: 90%;" size="1" min="-1" max="30" type = "number" value=' . $data_setpoint_temperature . '></td>';
                                                    echo '<td><input name="data_circulation_air_duration_edit_agingtable_' . $index_row .'" maxlength="4" style="width: 90%;" size="1" min="-1" max="1441" type = "number" value=' . $data_circulation_air_duration . '></td>';
                                                    echo '<td><input name="data_circulation_air_period_edit_agingtable_' . $index_row .'" maxlength="4" style="width: 90%;" size="1" min="-1" max="1441" type = "number" value=' . $data_circulation_air_period . '></td>';
                                                    echo '<td><input name="data_exhaust_air_duration_edit_agingtable_' . $index_row .'" maxlength="4" style="width: 90%;" size="1" min="-1" max="1441" type = "number" value=' . $data_exhaust_air_duration . '></td>';
                                                    echo '<td><input name="data_exhaust_air_period_edit_agingtable_' . $index_row .'" maxlength="4" style="width: 90%;" size="1" min="-1" max="1441" type = "number" value=' . $data_exhaust_air_period . '></td>';
                                                    echo '<td><input name="data_days_edit_agingtable_' . $index_row .'" maxlength="4" style="width: 90%;" size="1" type = "number" value=' . $data_days . '></td>';
                                                    echo '</tr>';
                                                    $index_row++;
                                                } 
                                             }
                                             catch (Exception $e) {
                                                }
                                        ?>
                                    </table>
                                    <h4>
                                        <?php 
                                            
                                            echo  _('comment:'). ' <textarea name="comment_edit_agingtable" cols="100" rows="3" maxlength="10000" wrap="soft" >'. $agingtable_comment . '</textarea>';
                                        ?>
                                    </h4>
                                    <input type="hidden" name="max_row" type="text" value="<?php echo $index_row ?>">
                                    <input type="hidden" name="agingtable_edit" type="text" value="<?php echo $agingtable_to_edit ?>">
                                    <button class="art-button" name="edit_agingtable_form_submit" type="submit" value="edit_agingtable_form_submit" onclick="return confirm('<?php echo _('save'); echo ' '; echo _('edit agingtable'); ?>?')"><?php echo _('save'); ?></button>
                                    <button class="art-button" name="cancel_agingtable_form_submit" type="submit" formaction="settings.php" value="cancel_agingtable_form_submit" onclick="return confirm('<?php echo _('attention'); echo ' '; echo _('cancel editing agingtable'); ?>?')"><?php echo _('cancel'); ?></button>
                                </form>
                                <form method="post" name="delete_row">
                                    <table>
                                        <tr>
                                            <td><?php echo _('phase to delete'); ?>
                                                <input type="hidden" name="agingtable_edit" type="text" value="<?php echo $agingtable_to_edit ?>">
                                                <?php $_SESSION['agingtable_row_array'] = $row_id_array; ?>
                                            </td>
                                            <td><input name="delete_agingtable_row" maxlength="2" style="width: 50px;" size="1" min="1" max="99" type = "number" value="1"></td>
                                            <td><button class="art-button" name="delete_agingtable_row_submit" type="submit" value="delete_agingtable_row_submit" onclick="return confirm('<?php echo _('delete row'); ?>?')"><?php echo _('delete'); ?></button></td>
                                        </tr>
                                    </table>
                                </form>
                                <form method="post" name="add_row">
                                    <table>
                                        <tr>
                                            <td><?php echo _('number of rows'); ?>
                                                <input type="hidden" name="agingtable_edit" type="text" value="<?php echo $agingtable_to_edit ?>">
                                                <input type="hidden" name="agingtable_number_rows" type="text" value="<?php echo $number_rows ?>">
                                            </td>
                                            <td><input name="adding_agingtable_rows" maxlength="2" style="width: 50px;" size="1" min="1" max="99" type = "number" value="1"></td>
                                            <td><button class="art-button" name="adding_agingtable_row_submit" type="submit" value="adding_agingtable_row_submit" onclick="return confirm('<?php echo _('add rows'); ?>?')"><?php echo _('add'); ?></button></td>
                                        </tr>
                                    </table>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--        </div> -->
        <?php 
            include 'footer.php';
        ?>
