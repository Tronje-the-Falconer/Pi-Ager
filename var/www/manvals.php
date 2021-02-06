<!-- Diese Einstellungen werden nur im manuellen Modus eingeblendet -->
                                <?php
                                include 'modules/write_settings_db_logfile_txt.php';        // Speichert die eingestelleten Werte (Temperaturregelung, Feuchte, Lüftung)
                                include 'modules/read_settings_db.php';                     // Liest die Einstellungen (Temperaturregelung, Feuchte, Lüftung und deren Hysteresen) und Betriebsart des RSS
    // echo ('<br>');
    // echo ('MANVALS_read DB circulate: ' . $circulation_air_period);
    // echo ('<br>');
    // echo ('MANVALS_read DB exhaust: ' . $exhaust_air_period);
    // echo ('<br>');
    // echo date('d M Y, H:i:s');
                                ?>
                                <h2 class="art-postheader"><?php echo _('manual values'); ?></h2>
                                <!----------------------------------------------------------------------------------------Betriebsart-->
                                <form method="post" name="manvals">
                                    <div class="hg_container" >
                                        <table style="width: 100%;table-layout: fixed";>
                                            <tr>
                                                <td class="td_png_icon"></td>
                                                <td style=" text-align: left; padding-left: 20px; height:10px;"></td>
                                            </tr>
                                            <tr>
                                                <td class="td_png_icon"><h3><?php echo _('operating mode'); ?></h3><img src="images/icons/operatingmode_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_operation_mode_blockFunction()"><?php echo _('help'); ?></button></td>
                                                <td style=" text-align: left; padding-left: 20px;">
                                                <input type="radio" name="modus_settings" value="0" <?php echo $checked_0; ?>/><label> <?php echo '0 - '._('cooling'); ?></label><br>
                                                <input type="radio" name="modus_settings" value="1" <?php echo $checked_1; ?>/><label> <?php echo '1 - '._('cooling with humidification'); ?></label><br>
                                                <input type="radio" name="modus_settings" value="2" <?php echo $checked_2; ?>/><label> <?php echo '2 - '._('heating with humidification'); ?></label><br>
                                                <input type="radio" name="modus_settings" value="3" <?php echo $checked_3; ?>/><label> <?php echo '3 - '._('automatik with humidification'); ?></label><br>
                                                <input type="radio" name="modus_settings" value="4" <?php echo $checked_4; ?>/><label> <?php echo '4 - '._('automatik with dehumidification and humidification'); ?></label><br><br>
                                                <?php echo '<b>'._('circulating air and exhaust air timer').'</b> '._('can be used independently of the selected mode.'); ?>
                                                </td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_operation_mode_blockFunction() {
                                                document.getElementById('help_operation_mode').style.display = 'block';
                                            }
                                            function help_operation_mode_noneFunction() {
                                                document.getElementById('help_operation_mode').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_operation_mode" class="help_p">
                                            <?php echo _('helptext_operation_mode');
                                                  echo '<br><br>'; ?>
                                            <button class="art-button" type="button" onclick="help_operation_mode_noneFunction()"><?php echo _('close'); ?></button>
                                        </p>

                                        <hr>
                                        <!----------------------------------------------------------------------------------------Temperatur-->
                                        <table style="width: 100%;table-layout: fixed;">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3><?php echo _('temperature'); ?></h3><img src="images/icons/heating_cooling_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_temperature_blockFunction()"><?php echo _('help'); ?></button></td>
                                                <td class="text_left_padding"><?php echo _('setpoint temperature'); ?>:</td>
                                                <td style="text-align: left;"><input name="setpoint_temperature_settings" type="number" style="width: 30%;" min="0" max="25" value=<?php echo $setpoint_temperature; ?>>°C<span style="font-size: xx-small"> (0 <?php echo _('to'); ?> 25)</span></td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_temperature_blockFunction() {
                                                document.getElementById('help_temperature').style.display = 'block';
                                            }
                                            function help_temperature_noneFunction() {
                                                document.getElementById('help_temperature').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_temperature" class="help_p">
                                            <?php echo _('helptext_temperature_setpoint');
                                                  echo '<br><br>'; ?>
                                            <button class="art-button" type="button" onclick="help_temperature_noneFunction()"><?php echo _('close'); ?></button>
                                        </p>
                                        <hr>
                                        <!----------------------------------------------------------------------------------------Luftfeuchte-->
                                        <div style="<?php if ($modus == 0){print "display: none;";}?>">
                                        <table style="width: 100%;table-layout: fixed;">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3><?php echo _('humidity'); ?></h3><img src="images/icons/humidification_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_humidify_blockFunction()"><?php echo _('help'); ?></button></td>
                                                <td class="text_left_padding"><?php echo _('setpoint humidity'); ?></td>
                                                <td style="text-align: left;"><input name="setpoint_humidity_settings" type="number" style="width: 30%;" min="0" max="99" value=<?php echo $setpoint_humidity; ?>>%<span style="font-size: xx-small"> (0 <?php echo _('to'); ?> 99)</span></td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_humidify_blockFunction() {
                                                document.getElementById('help_humidify').style.display = 'block';
                                            }
                                            function help_humidify_noneFunction() {
                                                document.getElementById('help_humidify').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_humidify" class="help_p">
                                            <?php echo _('helptext_humidity_setpoint');
                                                  echo '<br><br>'; ?>
                                            <button class="art-button" type="button" onclick="help_humidify_noneFunction()"><?php echo _('close'); ?></button>
                                        </p>
                                        <hr>
                                        </div>
                                        <!----------------------------------------------------------------------------------------Umluft-->
                                        <table style="width: 100%;table-layout: fixed;">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3><?php echo _('timer circulating'); ?></h3><img src="images/icons/circulate_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_exhausting_air_blockFunction()"><?php echo _('help'); ?></button></td>
                                                <td class="text_left_padding"><?php echo _('period every'); ?> </td>
                                                <td style="text-align: left;"><input type="number" style="width: 35%;" min="0" max="1440" name="circulation_air_period_settings" value=<?php echo round($circulation_air_period); ?>><?php echo _('minutes'); ?><span style="font-size: xx-small"> (0 <?php echo _('to'); ?> 1440)</span></td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding"><?php echo _('for the duration of'); ?></td>
                                                <td style="text-align: left;"><input type="number" style="width: 35%;" min="0" max="1440" name="circulation_air_duration_settings" value=<?php echo $circulation_air_duration; ?>><?php echo _('minutes'); ?><span style="font-size: xx-small"> (0=<?php echo _('off'); ?>)</span></td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_exhausting_air_blockFunction() {
                                                document.getElementById('help_exhausting_air').style.display = 'block';
                                            }
                                            function help_exhausting_air_noneFunction() {
                                                document.getElementById('help_exhausting_air').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_exhausting_air" class="help_p">
                                            <?php echo _('helptext_exhausting_air');
                                                  echo '<br><br>'; ?>
                                            <button class="art-button" type="button" onclick="help_exhausting_air_noneFunction()"><?php echo _('close'); ?></button>
                                        </p>
                                        <hr>
                                        <!----------------------------------------------------------------------------------------Abluft-->
                                        <table style="width: 100%;table-layout: fixed;">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3><?php echo _('timer exhausting air'); ?></h3><img src="images/icons/exhausting_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_circulation_air_blockFunction()"><?php echo _('help'); ?></button></td>
                                                <td class="text_left_padding"><?php echo _('period every'); ?> </td>
                                                <td style="text-align: left;"><input type="number" style="width: 35%;" min="0" max="1440" name="exhaust_air_period_settings" value=<?php echo round($exhaust_air_period); ?>><?php echo _('minutes'); ?><span style="font-size: xx-small"> (0 <?php echo _('to'); ?> 1440)</span></td>
                                            </tr>
                                            <tr><td class="text_left_padding"><?php echo _('for the duration of'); ?></td>
                                                <td style="text-align: left;"><input type="number" style="width: 35%;" min="0" max="1440" name="exhaust_air_duration_settings" value=<?php echo $exhaust_air_duration; ?>><?php echo _('minutes'); ?><span style="font-size: xx-small"> (0=<?php echo _('off'); ?>)</span></td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_circulation_air_blockFunction() {
                                                document.getElementById('help_circulation_air').style.display = 'block';
                                            }
                                            function help_circulation_air_noneFunction() {
                                                document.getElementById('help_circulation_air').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_circulation_air" class="help_p">
                                            <?php  echo _('helptext_circulation_air');
                                                  echo '<br><br>'; ?>
                                            <button class="art-button" type="button" onclick="help_circulation_air_noneFunction()"><?php echo _('close'); ?></button>
                                        </p>
                                        <br>
                                        <br>
                                        <table style="width: 100%; align: center;">
                                             <tr>
                                                <td class="td_submitbutton">
                                                    <button class="art-button" name="manvals_form_submit" type="submit" value="manvals_form_submit" onclick="return confirm('<?php echo _('save'); echo ' '; echo _('manual values'); ?>?')"><?php echo _('save'); ?> </button>
                                                </td>
                                            </tr>
                                        </table>                                            
                                    </div>
                                </form>
