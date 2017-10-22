<?php 
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                    include 'modules/names.php';
                                    include 'modules/database.php';
                                    include 'modules/read_config_db.php';                       // Liest die Grundeinstellungen Sensortyp, Hysteresen, GPIO's)
                                    
                                    include 'modules/system_reboot.php';                        // Startet das System neu
                                    include 'modules/system_shutdown.php';                      // Fährt das System herunter
                                    
                                    include 'modules/database_empty_statistic_tables.php';      // leert die Statistik-Tabellen (Status, data)
                                    include 'modules/write_loglevel_db.php';                    // schreibt das Loglevel in Datenbank
                                    

                                ?>
                                <h2 class="art-postheader"><?php echo _('administration'); ?></h2>
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
                                <!----------------------------------------------------------------------------------------Database-->
                                <div class="hg_container" >
                                    <?php
                                        $loglevel_console = get_table_value($config_settings_table, $loglevel_console_key);
                                        $loglevel_file = get_table_value($config_settings_table, $loglevel_file_key);
                                        
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
                                <!----------------------------------------------------------------------------------------Content Ende-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            include 'footer.php';
        ?>