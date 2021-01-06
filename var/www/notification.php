                                <?php 
                                    include 'header.php';      // Template-Kopf und Navigation
                                    include 'modules/names.php';
                                    include 'modules/database.php';
                                    include 'modules/logging.php';                            //liest die Datei fuer das logging ein
                                    include 'modules/test_notifications.php';
                                    include 'modules/write_notification_db.php';
                                    
                                ?>
                                <h2 class="art-postheader"><?php echo _('messenger'); ?></h2>
                                <!----------------------------------------------------------------------------------------Messenger-->
                                <div class="hg_container" >
                                    <form method="post" name="messenger">
                                        <table id="show_messenger" class="show_messenger">
                                            <tr style="background-color: #F0F5FB; border-bottom: 1px solid #000033">
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('id') ?><span class="tooltiptext"><?php echo _('id'); ?></span></div></td>
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('exception') ?><span class="tooltiptext"><?php echo _('exception'); ?></span></div></td>
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('e-mail') ?><span class="tooltiptext"><?php echo _('e-mail'); ?></span></div></td>
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('pushover') ?><span class="tooltiptext"><?php echo _('pushover'); ?></span></div></td>
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('telegram') ?><span class="tooltiptext"><?php echo _('telegram'); ?></span></div></td>
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('alarm') ?><span class="tooltiptext"><?php echo _('alarm'); ?></span></div></td>
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('raise_exception') ?><span class="tooltiptext"><?php echo _('raise_exception'); ?></span></div></td>
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('active') ?><span class="tooltiptext"><?php echo _('active'); ?></span></div></td>
                                            </tr>
                                            <?php 
                                                $index_row = 0;
                                                $messenger_rows = get_table_dataset($messenger_table);
                                                if ($messenger_rows != false){
                                                    try {
                                                        $count_messenger_number_rows = count($messenger_rows);
                                                        while ($index_row < $count_messenger_number_rows) {
                                                            $checked_messenger_true = "";
                                                            $dataset = $messenger_rows[$index_row];
                                                            // $num = count($dataset);
                                                            if (!empty($dataset[$messenger_id_field])){
                                                                $messenger_id = $dataset[$messenger_id_field];
                                                            } else {$messenger_id = '..';}
                                                            if (!empty($dataset[$messenger_exception_field])){
                                                                $messenger_exception = $dataset[$messenger_exception_field];
                                                            } else {$messenger_exception = '..';}
                                                            if (!empty($dataset[$messenger_e_mail_field])){
                                                                $messenger_e_mail = $dataset[$messenger_e_mail_field];
                                                            } else {$messenger_e_mail = '';}
                                                            if ($messenger_e_mail == 1){
                                                                $checked_messenger_e_mail_true = 'checked';
                                                            }
                                                            else{
                                                                $checked_messenger_e_mail_true = '';
                                                            }

                                                            if (!empty($dataset[$messenger_pushover_field])){
                                                                $messenger_pushover = str_replace(',', '.', $dataset[$messenger_pushover_field]);
                                                            } else {$messenger_pushover = '';}
                                                            if ($messenger_pushover == 1){
                                                                $checked_messenger_pushover_true = 'checked';
                                                            }
                                                            else{
                                                                $checked_messenger_pushover_true = '';
                                                            }
                                                            
                                                            if (!empty($dataset[$messenger_telegram_field])){
                                                                $messenger_telegram = str_replace(',', '.',$dataset[$messenger_telegram_field]);
                                                            } else {$messenger_telegram = '';}
                                                            if ($messenger_telegram == 1){
                                                                $checked_messenger_telegram_true = 'checked';
                                                            }
                                                            else{
                                                                $checked_messenger_telegram_true = '';
                                                            }
                                                            
                                                            if (!empty($dataset[$messenger_alarm_field])){
                                                                $messenger_alarm = $dataset[$messenger_alarm_field];
                                                            } else {$messenger_alarm = '';}
                                                            if (!empty($dataset[$messenger_raise_exception_field])){
                                                                $messenger_raise_exception = $dataset[$messenger_raise_exception_field];
                                                            } else {$messenger_raise_exception = '';}
                                                            if ($messenger_raise_exception == 1){
                                                                $checked_messenger_raise_exeption_true = 'checked';
                                                            }
                                                            else{
                                                                $checked_messenger_raise_exeption_true = '';
                                                            }
                                                            
                                                            if (!empty($dataset[$messenger_active_field])){
                                                                $messenger_active = $dataset[$messenger_active_field];
                                                            } else {$messenger_active = '';}
                                                            if ($messenger_active == 1){
                                                                $checked_messenger_active_true = 'checked';
                                                            }
                                                            else{
                                                                $checked_messenger_active_true = '';
                                                            }
                                                                echo '<td><input type="hidden" name="messenger_id_' . $index_row . '" value="' . $messenger_id . '">'. $messenger_id .'</td>';
                                                                echo '<td><input name="messenger_exception_' . $messenger_id . '" type="text" style="width: 90%; text-align: right;" required value=' . $messenger_exception .'></td>';
                                                                echo '<td> <input type="hidden" name="checked_messenger_e_mail_true_' . $messenger_id . '" value="0">
                                                                        <input type="checkbox" name="checked_messenger_e_mail_true_' . $messenger_id . '" value="1" ' . $checked_messenger_e_mail_true .'></td>';
                                                                echo '<td> <input type="hidden" name="checked_pushover_true_' . $messenger_id . '" value="0">
                                                                        <input type="checkbox" name="checked_pushover_true_' . $messenger_id . '" value="1" ' . $checked_messenger_pushover_true .'></td>';
                                                                echo '<td> <input type="hidden" name="checked_telegram_true_' . $messenger_id . '" value="0">
                                                                        <input type="checkbox" name="checked_telegram_true_' . $messenger_id . '" value="1" ' . $checked_messenger_telegram_true .'></td>';
                                                                echo '<td><input name="messenger_alarm_' . $messenger_id . '" type="text" style="width: 90%; text-align: right;" value='. $messenger_alarm .'></td>';
                                                                echo '<td> <input type="hidden" name="checked_messenger_raise_exeption_true_' . $messenger_id . '" value="0">
                                                                        <input type="checkbox" name="checked_messenger_raise_exeption_true_' . $messenger_id . '" value="1" ' . $checked_messenger_raise_exeption_true .'></td>';
                                                                 echo '<td> <input type="hidden" name="checked_active_true_' . $messenger_id . '" value="0">
                                                                        <input type="checkbox" name="checked_active_true_' . $messenger_id . '" value="1" ' . $checked_messenger_active_true .'></td>';
                                                            echo '</tr>';
                                                            $index_row++;
                                                        }
                                                        echo '<input type="hidden" name="count_messenger_number_rows" value="' . $count_messenger_number_rows . '">';
                                                     }
                                                     catch (Exception $e) {
                                                        }
                                                }
                                            ?>
                                        </table>
                                    <button class="art-button" name="save_messenger_values" value="save_messenger_values" onclick="return confirm('<?php echo _('ATTENTION: save messenger values?');?>');"><?php echo _('save'); ?></button>
                                    </form>
                                    <form method="post" name="add_messenger">
                                        <table id="show_messenger" class="show_messenger">
                                            <tr style="background-color: #F0F5FB; border-bottom: 1px solid #000033">
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('exception') ?><span class="tooltiptext"><?php echo _('exception'); ?></span></div></td>
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('e-mail') ?><span class="tooltiptext"><?php echo _('e-mail'); ?></span></div></td>
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('pushover') ?><span class="tooltiptext"><?php echo _('pushover'); ?></span></div></td>
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('telegram') ?><span class="tooltiptext"><?php echo _('telegram'); ?></span></div></td>
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('alarm') ?><span class="tooltiptext"><?php echo _('alarm'); ?></span></div></td>
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('raise_exception') ?><span class="tooltiptext"><?php echo _('raise_exception'); ?></span></div></td>
                                                <td class="show_messenger_cell"><div class="tooltip"><?php echo _('active') ?><span class="tooltiptext"><?php echo _('active'); ?></span></div></td>
                                            </tr>
                                            <tr>
                                                <td><input name="add_messenger_exception" type="text" style="width: 90%; text-align: right;" required value></td>
                                                <td><input type="hidden" name="add_checked_messenger_e_mail_true" value="0">
                                                    <input type="checkbox" name="add_checked_messenger_e_mail_true" value="1"></td>
                                                <td> <input type="hidden" name="add_checked_pushover_true" value="0">
                                                     <input type="checkbox" name="add_checked_pushover_true" value="1"></td>
                                                <td> <input type="hidden" name="add_checked_telegram_true" value="0">
                                                     <input type="checkbox" name="add_checked_telegram_true" value="1"></td>
                                                <td><input name="add_messenger_alarm" type="text" style="width: 90%; text-align: right;"></td>
                                                <td> <input type="hidden" name="add_checked_messenger_raise_exeption_true" value="0">
                                                    <input type="checkbox" name="add_checked_messenger_raise_exeption_true" value="1"></td>
                                                    <td> <input type="hidden" name="add_checked_active_true" value="0">
                                                         <input type="checkbox" name="add_checked_active_true" value="1"></td>
                                            </tr>
                                        </table>
                                        <button class="art-button" name="add_messenger" value="add_messenger" onclick="return confirm('<?php echo _('ATTENTION: add messenger?');?>');"><?php echo _('add'); ?></button>
                                    </form>
                                    <form method="post" name="delete_messenger">
                                        <table id="show_messenger" class="show_messenger">
                                            <tr>
                                                <td><?php echo _('id to delete: ') ?></td><td><input name="id" type="number" step="1" style="width: 90%; text-align: right;" ></td>
                                            </tr>
                                            <tr>
                                                <td><button class="art-button" name="delete_messenger" value="delete_messenger" onclick="return confirm('<?php echo _('ATTENTION: delete messenger?');?>');"><?php echo _('delete'); ?></button></td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                                <hr>
                                
                                
                                <h2 class="art-postheader"><?php echo _('alarm'); ?></h2>
                                <!----------------------------------------------------------------------------------------Alarm-->
                                <div class="hg_container" >
                                    <form method="post" name="alarm">
                                        <table id="show_alarm" class="show_alarm">
                                            <tr style="background-color: #F0F5FB; border-bottom: 1px solid #000033">
                                                <td class="show_alarm_cell"><div class="tooltip"><?php echo _('id') ?><span class="tooltiptext"><?php echo _('id'); ?></span></div></td>
                                                <td class="show_alarm_cell"><div class="tooltip"><?php echo _('alarm') ?><span class="tooltiptext"><?php echo _('alarm'); ?></span></div></td>
                                                <td class="show_alarm_cell"><div class="tooltip"><?php echo _('replication') ?><span class="tooltiptext"><?php echo _('replication'); ?></span></div></td>
                                                <td class="show_alarm_cell"><div class="tooltip"><?php echo _('sleep') ?><span class="tooltiptext"><?php echo _('sleep'); ?></span></div></td>
                                                <td class="show_alarm_cell"><div class="tooltip"><?php echo _('high_time') ?><span class="tooltiptext"><?php echo _('high_time'); ?></span></div></td>
                                                <td class="show_alarm_cell"><div class="tooltip"><?php echo _('low_time') ?><span class="tooltiptext"><?php echo _('low_time'); ?></span></div></td>
                                                <!--<td class="show_alarm_cell"><div class="tooltip"><?php echo _('waveform') ?><span class="tooltiptext"><?php echo _('waveform'); ?></span></div></td>
                                                <td class="show_alarm_cell"><div class="tooltip"><?php echo _('frequency') ?><span class="tooltiptext"><?php echo _('frequency'); ?></span></div></td>
                                                -->
                                                <td class="show_alarm_cell"></td>
                                                <td class="show_alarm_cell"></td>
                                            </tr>
                                            <?php 
                                                $index_row = 0;
                                                $alarm_rows = get_table_dataset($alarm_table);
                                                if ($alarm_rows != false){
                                                    try {
                                                        $count_alarm_number_rows = count($alarm_rows);
                                                        while ($index_row < $count_alarm_number_rows) {
                                                            $checked_alarm_true = "";
                                                            $dataset = $alarm_rows[$index_row];
                                                            // $num = count($dataset);
                                                            if (!empty($dataset[$alarm_id_field])){
                                                                $alarm_id = $dataset[$alarm_id_field];
                                                            } else {$alarm_id = '..';}
                                                            if (!empty($dataset[$alarm_alarm_field])){
                                                                $alarm_alarm = $dataset[$alarm_alarm_field];
                                                            } else {$alarm_alarm = '..';}
                                                            if (!empty($dataset[$alarm_replication_field])){
                                                                $alarm_replication = $dataset[$alarm_replication_field];
                                                            } else {$alarm_replication = '';}
                                                            if (!empty($dataset[$alarm_sleep_field])){
                                                                $alarm_sleep = str_replace(',', '.', $dataset[$alarm_sleep_field]);
                                                            } else {$alarm_sleep = '';}
                                                            if (!empty($dataset[$alarm_high_time_field])){
                                                                $alarm_high_time = str_replace(',', '.',$dataset[$alarm_high_time_field]);
                                                            } else {$alarm_high_time = '';}
                                                            if (!empty($dataset[$alarm_low_time_field])){
                                                                $alarm_low_time = str_replace(',', '.',$dataset[$alarm_low_time_field]);
                                                            } else {$alarm_low_time = '';}
                                                            if (!empty($dataset[$alarm_waveform_field])){
                                                                $alarm_waveform = $dataset[$alarm_waveform_field];
                                                            } else {$alarm_waveform = '';}
                                                            if (!empty($dataset[$alarm_frequency_field])){
                                                                $alarm_frequency = $dataset[$alarm_frequency_field];
                                                            } else {$alarm_frequency = '';}
                                                                echo '<td><input type="hidden" name="alarm_id_' . $index_row . '" value="' . $alarm_id . '">'. $alarm_id .'</td>';
                                                                echo '<td><input name="alarm_alarm_' . $alarm_id . '" type="text" style="width: 90%; text-align: right;" required value='. $alarm_alarm .'></td>';
                                                                echo '<td><input name="alarm_replication_' . $alarm_id . '" type="number" step="1" style="width: 90%; text-align: right;" required value='. $alarm_replication .'></td>';
                                                                echo '<td><input name="alarm_sleep_' . $alarm_id . '" type="number" style="width: 90%; text-align: right;" required value='. $alarm_sleep .'></td>';
                                                                echo '<td><input name="alarm_high_time_' . $alarm_id . '" type="number" step="0.1" style="width: 90%; text-align: right;" required value='. $alarm_high_time .'></td>';
                                                                echo '<td><input name="alarm_low_time_' . $alarm_id . '" type="number" step="0.1" style="width: 90%; text-align: right;" required value='. $alarm_low_time .'></td>';
                                                                /* echo '<td><input name="alarm_waveform_' . $alarm_id . '" type="text" style="width: 90%; text-align: right;" required value='. $alarm_waveform .'></td>';
                                                                echo '<td><input name="alarm_frequency_' . $alarm_id . '" type="number" style="width: 90%; text-align: right;" required value='. $alarm_frequency .'></td>';
                                                                */
                                                                echo '<td><input name="alarm_waveform_' . $alarm_id . '" type="hidden" style="width: 90%; text-align: right;"  value='. $alarm_waveform .'></td>';
                                                                echo '<td><input name="alarm_frequency_' . $alarm_id . '" type="hidden" style="width: 90%; text-align: right;"  value='. $alarm_frequency .'></td>';
                                                            echo '</tr>';
                                                            $index_row++;
                                                        }
                                                        echo '<input type="hidden" name="count_alarm_number_rows" value="' . $count_alarm_number_rows . '">';
                                                     }
                                                     catch (Exception $e) {
                                                        }
                                                }
                                            ?>
                                        </table>
                                    <button class="art-button" name="save_alarm_values" value="save_alarm_values" onclick="return confirm('<?php echo _('ATTENTION: save alarm values?');?>');"><?php echo _('save'); ?></button>
                                    </form>
                                    <form method="post" name="add_alarm">
                                        <table id="show_alarm" class="show_alarm">
                                            <tr style="background-color: #F0F5FB; border-bottom: 1px solid #000033">
                                                <td class="show_alarm_cell"><div class="tooltip"><?php echo _('alarm') ?><span class="tooltiptext"><?php echo _('alarm'); ?></span></div></td>
                                                <td class="show_alarm_cell"><div class="tooltip"><?php echo _('replication') ?><span class="tooltiptext"><?php echo _('replication'); ?></span></div></td>
                                                <td class="show_alarm_cell"><div class="tooltip"><?php echo _('sleep') ?><span class="tooltiptext"><?php echo _('sleep'); ?></span></div></td>
                                                <td class="show_alarm_cell"><div class="tooltip"><?php echo _('high_time') ?><span class="tooltiptext"><?php echo _('high_time'); ?></span></div></td>
                                                <td class="show_alarm_cell"><div class="tooltip"><?php echo _('low_time') ?><span class="tooltiptext"><?php echo _('low_time'); ?></span></div></td>
                                                <!-- <td class="show_alarm_cell"><div class="tooltip"><?php echo _('waveform') ?><span class="tooltiptext"><?php echo _('waveform'); ?></span></div></td>
                                                <td class="show_alarm_cell"><div class="tooltip"><?php echo _('frequency') ?><span class="tooltiptext"><?php echo _('frequency'); ?></span></div></td> -->
                                                <td class="show_alarm_cell"></td>
                                                <td class="show_alarm_cell"></td>
                                            </tr>
                                            <tr>
                                                <td><input name="add_alarm_alarm" type="text" style="width: 90%; text-align: right;" required></td>';
                                                <td><input name="add_alarm_replication" type="number" step="1" style="width: 90%; text-align: right;" required></td>';
                                                <td><input name="add_alarm_sleep" type="number" style="width: 90%; text-align: right;" required></td>';
                                                <td><input name="add_alarm_high_time" type="number" step="0.1" style="width: 90%; text-align: right;" required></td>';
                                                <td><input name="add_alarm_low_time" type="number" step="0.1" style="width: 90%; text-align: right;" required></td>';
                                                <!-- <td><input name="add_alarm_waveform" type="text" style="width: 90%; text-align: right;" required></td>';
                                                <td><input name="add_alarm_frequency" type="number" style="width: 90%; text-align: right;" required></td>'; -->
                                                <td><input name="add_alarm_waveform" type="hidden" style="width: 90%; text-align: right;"></td>';
                                                <td><input name="add_alarm_frequency" type="hidden" style="width: 90%; text-align: right;">0</td>';
                                                            
                                            </tr>
                                        </table>
                                        <button class="art-button" name="add_alarm" value="add_alarm" onclick="return confirm('<?php echo _('ATTENTION: add alarm?');?>');"><?php echo _('add'); ?></button>
                                    </form>
                                    <form method="post" name="delete_alarm">
                                        <table id="show_alarm" class="show_alarm">
                                            <tr>
                                                <td><?php echo _('id to delete: ') ?></td><td><input name="id" type="number" step="1" style="width: 90%; text-align: right;" ></td>
                                            </tr>
                                            <tr>
                                                <td><button class="art-button" name="delete_alarm" value="delete_alarm" onclick="return confirm('<?php echo _('ATTENTION: delete alarm?');?>');"><?php echo _('delete'); ?></button></td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                                <hr>
                                
                                <h2 class="art-postheader"><?php echo _('e-mail'); ?></h2>
                                <!----------------------------------------------------------------------------------------e-Mail-->
                                <div class="hg_container" >
                                    <form method="post" name="email_recipients">
                                        <table id="show_email_recipients" class="show_email_recipients">
                                            <tr style="background-color: #F0F5FB; border-bottom: 1px solid #000033">
                                                <td class="show_email_recipientscell"><div class="tooltip"><?php echo _('id') ?><span class="tooltiptext"><?php echo _('id'); ?></span></div></td>
                                                <td class="show_email_recipientscell"><div class="tooltip"><?php echo _('to mail') ?><span class="tooltiptext"><?php echo _('to mail'); ?></span></div></td>
                                                <td class="show_email_recipientscell"><div class="tooltip"><?php echo _('active') ?><span class="tooltiptext"><?php echo _('active'); ?></span></div></td>
                                            </tr>
                                            <?php 
                                                // Empfaenger aus DB auslesen und als Tabelle schreiben
                                                $index_row = 0;
                                                $e_mail_recipients_rows = get_table_dataset($email_recipients_table);
                                                if ($e_mail_recipients_rows != false){
                                                    try {
                                                        $count_e_mail_recipients_number_rows = count($e_mail_recipients_rows);
                                                        while ($index_row < $count_e_mail_recipients_number_rows) {
                                                            $checked_e_mail_recipient_true = "";
                                                            $dataset = $e_mail_recipients_rows[$index_row];
                                                            // $num = count($dataset);
                                                            if (!empty($dataset[$e_mail_recipients_id_field])){
                                                                $e_mail_recipients_id = $dataset[$e_mail_recipients_id_field];
                                                            } else {$e_mail_recipients_id = '..';}
                                                            if (!empty($dataset[$e_mail_recipients_to_mail_field])){
                                                                $e_mail_recipients_to_mail = $dataset[$e_mail_recipients_to_mail_field];
                                                            } else {$e_mail_recipients_to_mail = '..';}
                                                            if (!empty($dataset[$e_mail_recipients_active_field])){
                                                                $e_mail_recipients_active = $dataset[$e_mail_recipients_active_field];
                                                                if ($e_mail_recipients_active == 1){
                                                                    $checked_e_mail_recipient_true = "checked";
                                                                } else { 
                                                                    $checked_e_mail_recipient_true = "";
                                                                }
                                                            } else {$e_mail_recipients_active = '';}
                                                                echo '<td><input type="hidden" name="e_mail_recipient_id_' . $index_row . '" value="' . $e_mail_recipients_id . '">'. $e_mail_recipients_id .'</td>';
                                                                echo '<td><input name="e_mail_recipient_to_mail_' . $e_mail_recipients_id . '" type="text" style="width: 90%; text-align: right;" required value='. $e_mail_recipients_to_mail .'></td>';
                                                                echo '<td> <input type="hidden" name="checked_e_mail_recipient_true_' . $e_mail_recipients_id . '" value="0">
                                                                        <input type="checkbox" name="checked_e_mail_recipient_true_' . $e_mail_recipients_id . '" value="1" ' . $checked_e_mail_recipient_true .'></td>';
                                                            echo '</tr>';
                                                            $index_row++;
                                                        }
                                                        echo '<input type="hidden" name="count_e_mail_recipients_number_rows" value="' . $count_e_mail_recipients_number_rows . '">';
                                                     }
                                                     catch (Exception $e) {
                                                        }
                                                }
                                            ?>
                                        </table>
                                        <button class="art-button" name="save_e_mail_recipient_values" value="save_e_mail_recipient_values" onclick="return confirm('<?php echo _('ATTENTION: save e_mail recipient values?');?>');"><?php echo _('save'); ?></button>
                                    </form>
                                    <form method="post" name="add_e_mail_recipient">
                                        <table id="show_email_recipients" class="show_email_recipients">
                                            <tr>
                                                <td class="show_email_recipientscell"><div class="tooltip"><?php echo _('to mail') ?><span class="tooltiptext"><?php echo _('to mail'); ?></span></div></td>
                                                <td class="show_email_recipientscell"><div class="tooltip"><?php echo _('active') ?><span class="tooltiptext"><?php echo _('active'); ?></span></div></td>
                                            </tr>
                                            <tr>
                                                <td><input name="add_e_mail_recipient_to_mail" type="text" style="width: 90%; text-align: right;" required value></td>'
                                                <td> <input type="hidden" name="add_checked_e_mail_recipient_true" value="0">
                                                    <input type="checkbox" name="add_checked_e_mail_recipient_true" value="1"></td>
                                            </tr>
                                        </table>
                                        <button class="art-button" name="add_e_mail_recipient" value="add_e_mail_recipient" onclick="return confirm('<?php echo _('ATTENTION: add e_mail recipient?');?>');"><?php echo _('add'); ?></button>
                                    </form>
                                    <form method="post" name="delete_e_mail_recipient">
                                        <table id="show_e_mail_recipient" class="show_e_mail_recipient">
                                            <tr>
                                                <td><?php echo _('id to delete: ') ?></td><td><input name="id" type="number" step="1" style="width: 90%; text-align: right;" ></td>
                                            </tr>
                                            <tr>
                                              <td><button class="art-button" name="delete_e_mail_recipient" value="delete_e_mail_recipient" onclick="return confirm('<?php echo _('ATTENTION: delete e-mail recipient?');?>');"><?php echo _('delete'); ?></button></td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                                <hr>
                                
                                <h2 class="art-postheader"><?php echo _('Mailserver'); ?></h2>
                                <!----------------------------------------------------------------------------------------Mailserver-->
                                <div class="hg_container" >
                                    <form method="post" name="mailserver">
                                        <table style="width: 100%;">
                                            <?php
                                                $mailserver_server = get_table_value_from_field($mailserver_table, Null, $mailserver_server_field,);
                                                $mailserver_user = get_table_value_from_field($mailserver_table, Null, $mailserver_user_field);
                                                $mailserver_password = get_table_value_from_field($mailserver_table, Null, $mailserver_password_field);
                                                $mailserver_starttls = get_table_value_from_field($mailserver_table, Null, $mailserver_starttls_field,);
                                                $mailserver_from_mail = get_table_value_from_field($mailserver_table, Null, $mailserver_from_mail_field);
                                                $mailserver_port = get_table_value_from_field($mailserver_table, Null, $mailserver_port_field);
                                                
                                                if ($mailserver_password == ''){
                                                    $old_mailserver_password = '';
                                                }
                                                else{
                                                    $old_mailserver_password = '123456789abcdefghi';
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo _('server'); ?>:</td>
                                                <td><input name="mailserver_server" type="text" style="width: 90%; text-align: right;" required value=<?php echo $mailserver_server; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('user'); ?>:</td>
                                                <td><input name="mailserver_user" type="text" style="width: 90%; text-align: right;" required value=<?php echo $mailserver_user; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('password'); ?>:</td>
                                                <td><input name="mailserver_password" type="password" style="width: 90%; text-align: right;" required value=<?php echo $old_mailserver_password; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('starttls'); ?>:</td>
                                                <td><input name="mailserver_starttls" type="number" style="width: 90%; text-align: right;" required value=<?php echo $mailserver_starttls; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('from mail'); ?>:</td>
                                                <td><input name="mailserver_from_mail" type="text" style="width: 90%; text-align: right;" required value=<?php echo $mailserver_from_mail; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('port'); ?>:</td>
                                                <td><input name="mailserver_port" type="number" style="width: 90%; text-align: right;" required value=<?php echo $mailserver_port; ?>></td>
                                            </tr>
                                        </table>
                                        <button class="art-button" name="save_mailserver_values" value="save_mailserver_values" onclick="return confirm('<?php echo _('ATTENTION: save mailserver values?');?>');"><?php echo _('save'); ?></button>
                                    </form>
                                    <table style="width: 100%;">
                                        <tr>
                                            <form method="post" name="mailserver_test">
                                                <tr>
                                                    <td></td>
                                                    <td><button class="art-button" name="mailserver_test" value="mailserver_test" onclick="return confirm('<?php echo _('ATTENTION: test mailserver? be shure you have saved the values before!');?>');"><?php echo _('test mailserver'); ?></button></td>
                                                </tr>
                                            </form>
                                        </tr>
                                    </table>
                                </div>
                                <hr>
                                
                                <h2 class="art-postheader"><?php echo _('pushover'); ?></h2>
                                <!----------------------------------------------------------------------------------------Pushover-->
                                <div class="hg_container" >
                                    <form method="post" name="pushover">
                                        <table style="width: 100%;">
                                            <?php
                                                $pushover_user_key = get_table_value_from_field($pushover_table, Null, $pushover_user_key_field,);
                                                $pushover_api_token = get_table_value_from_field($pushover_table, Null, $pushover_api_token_field);
                                                $pushover_active = get_table_value_from_field($pushover_table, Null, $pushover_active_field);
                                                
                                                if($pushover_active == 1) {
                                                    $checked_pushover_true = "checked";
                                                }
                                                else{
                                                    $checked_pushover_true = "";
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo _('user key'); ?>:</td>
                                                <td><input name="pushover_user_key" type="text" style="width: 90%; text-align: right;" required value=<?php echo $pushover_user_key; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('api token'); ?>:</td>
                                                <td><input name="pushover_api_token" type="text" style="width: 90%; text-align: right;" required value=<?php echo $pushover_api_token; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('pushover_active'); ?>:</td>
                                                <td>
                                                    <input type="hidden" name="pushover_active" value="0">
                                                    <input type="checkbox" name="pushover_active" value="1" <?php echo $checked_pushover_true; ?>/>
                                                </td>
                                            </tr>
                                        </table>
                                        <button class="art-button" name="save_pushover_values" value="save_pushover_values" onclick="return confirm('<?php echo _('ATTENTION: save pushover values?');?>');"><?php echo _('save'); ?></button>
                                    </form>
                                    <table style="width: 100%;">
                                        <tr>
                                            <form method="post" name="pushover_test">
                                                <td><button class="art-button" name="pushover_test" value="pushover_test" onclick="return confirm('<?php echo _('ATTENTION: test pushover values? be shure you have saved the values before!');?>');"><?php echo _('test pushover values'); ?></button></td>
                                            </form>
                                        </tr>
                                    </table>
                                </div>
                                <hr>
                                
                                <h2 class="art-postheader"><?php echo _('telegram'); ?></h2>
                                <!----------------------------------------------------------------------------------------Telegram-->
                                <div class="hg_container" >
                                    <form method="post" name="telegram">
                                        <table style="width: 100%;">
                                            <?php
                                                $telegram_bot_token = get_table_value_from_field($telegram_table, Null, $telegram_bot_token_field,);
                                                $telegram_bot_chatid = get_table_value_from_field($telegram_table, Null, $telegram_bot_chat_id_field);
                                                $telegram_active = get_table_value_from_field($telegram_table, Null, $telegram_active_field);
                                                
                                                if($telegram_active == 1) {
                                                    $checked_telegram_true = "checked";
                                                }
                                                else{
                                                    $checked_telegram_true = "";
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo _('bot token'); ?>:</td>
                                                <td><input name="telegram_bot_token" type="text" style="width: 90%; text-align: right;" required value=<?php echo $telegram_bot_token; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('bot chatID'); ?>:</td>
                                                <td><input name="telegram_bot_chatid" type="text" style="width: 90%; text-align: right;" required value=<?php echo $telegram_bot_chatid; ?>></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _('telegram_active'); ?>:</td>
                                                <td>
                                                    <input type="hidden" name="telegram_active" value="0">
                                                    <input type="checkbox" name="telegram_active" value="1" <?php echo $checked_telegram_true; ?>/>
                                                </td>
                                            </tr>
                                        </table>
                                        <button class="art-button" name="save_telegram_values" value="save_telegram_values" onclick="return confirm('<?php echo _('ATTENTION: save telegram values?');?>');"><?php echo _('save'); ?></button>
                                    </form>
                                    <table style="width: 100%;">
                                        <tr>
                                            <form method="post" name="telegram_test">
                                                <td><button class="art-button" name="telegram_test" value="telegram_test" onclick="return confirm('<?php echo _('ATTENTION: test telegram values? be shure you have saved the values before!');?>');"><?php echo _('test telegram values'); ?></button></td>
                                            </form>
                                        </tr>
                                    </table>
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
