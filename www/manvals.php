<!-- Diese Einstellungen werden nur im manuellen Modus eingeblendet -->
                                <h2 class="art-postheader"><?php echo _('manual values'); ?></h2>
                                <!----------------------------------------------------------------------------------------Betriebsart-->
                                <form method="post" name="manvals">
                                    <div class="hg_container" >
                                        <table style="width: 100%;" class="miniature_writing">
                                            <tr>
                                                <td class="td_png_icon"><h3><?php echo _('operating mode'); ?></h3><img src="images/icons/operatingmode_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_operation_mode_blockFunction()"><?php echo _('help'); ?></button></td>
                                                <td style=" text-align: left; padding-left: 20px;">
                                                <input type="radio" name="modus_settings" value="0" <?php echo $checked_0; ?>/><label> <?php echo _('cooling'); ?></label><br>
                                                <input type="radio" name="modus_settings" value="1" <?php echo $checked_1; ?>/><label> <?php echo _('cooling with humidification'); ?></label><br>
                                                <input type="radio" name="modus_settings" value="2" <?php echo $checked_2; ?>/><label> <?php echo _('heating with humidification'); ?></label><br>
                                                <input type="radio" name="modus_settings" value="3" <?php echo $checked_3; ?>/><label> <?php echo _('automatik with humidification'); ?></label><br>
                                                <input type="radio" name="modus_settings" value="4" <?php echo $checked_4; ?>/><label> <?php echo _('automatik with dehumidification and humidification'); ?></label><br><br>
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
                                            <?php echo '<b>'._('cooling').':</b>  '._('it is cooled to the set temperature with circulating air.').'<br><br><b>'.
                                            _('cooling with humidification').':</b>  '._('it is cooled to the set temperature with circulating air and humidification is on,
                                             the heating is never controlled.').'<br><br><b>'.
                                            _('heating with humidification').':</b> '._('it is heated to the set temperature with circulating air and humidification is on,
                                             The cooling is never controlled.').'<br><br><b>'.
                                            _('automatik with humidification').':</b>'._('the pi-ager cools or heats with circulating air, depending on the set value and humidification is on.').'<br><br><b>'.
                                            _('automatik with dehumidification and humidification').':</b> '._('like automatic with humidification, additional: when the humidity is exceeded, the exhaust air switches on until the humidity target is reached again.
                                             since it is a passive dehumidification, the minimum achievable humidity depends on the dryness of the ambient air.
                                             to avoid a wild switching on and off, the humidification should be delayed (5-10min)!').
                                            '<br><br>'; ?>
                                            <button class="art-button" type="button" onclick="help_operation_mode_noneFunction()"><?php echo _('close'); ?></button>
                                        </p>

                                        <hr>
                                        <!----------------------------------------------------------------------------------------Temperatur-->
                                        <table style="width: 100%;" class="miniature_writing">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3><?php echo _('temperature'); ?></h3><img src="images/icons/heating_cooling_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_temperature_blockFunction()"><?php echo _('help'); ?></button></td>
                                                <td class="text_left_padding"><?php echo _('setpoint temperature'); ?>:</td>
                                                <td class="text_left_padding"><input name="setpoint_temperature_settings" maxlength="4" size="2" type="text" value=<?php echo $setpoint_temperature; ?>>°C<span class="display_none" style="font-size: xx-small"> (0 <?php echo _('to'); ?> 22)</span></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
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
                                            <?php echo '<b>'._('setpoint temperature').':</b> '._('the desired temperature is set here. the minimum value is 0 ° C, the maximum + 22 ° C.
                                             for technical reasons, not all values can be approached in any operating mode. the circulating air is always active during the cooling or heating phases.').'<br><br>'.
                                            '<br><br>
                                            <b>'._('example of cooling').':</b> <i>'._('setpoint temperature').': 12°C; '._('switch-on value').': 3°C; '._('switch-off value').': 1°C</i><br>'.
                                            _('switch-on temperature').' = '._('setpoint temperature').' + '._('switch-on value').' --> 12°C + 3°C = 15°C<br>'.
                                            _('switch-off temperature').' = '._('setpoint temperature').' + '._('switch-off value').' --> 12°C + 1°C = 13°C<br>'.
                                            _('so, if 15 degrees are exceeded, the pi-ager cools down to 13 ° C and then switches off to avoid excessive cooling.
                                             the entire behavior is different from pi-ager to pi-ager and therefore to be determined individually.').
                                            '<br><br>
                                            <b>'._('example of heating').':</b> <i>'._('setpoint temperature').': 22°C; '._('switch-on value').': 3°C; '._('switch-off value').' 1°C</i><br>'.
                                            _('switch-on temperature').' = '._('setpoint temperature').' - '._('switch-on value').'  --> 22°C - 3°C = 19°C<br>'.
                                            _('switch-off temperature').' = '._('setpoint temperature').' - '._('switch-off value').' --> 22°C - 1°C = 21°C<br>'.
                                            _('so, if the temperature drops below 19 degrees, the pi-ager heats up to 21 ° C and then switches off to avoid excessive heating.
                                             the entire behavior is different from pi-ager to pi-ager and therefore to be determined individually.').
                                            '<br><br>
                                            <b>'._('automatic mode').':</b> '._('in every automatic mode, the temperature is fully automatically controlled.
                                             first, the current temperature is determined. Then decide which method (cooling or heating) is suitable to reach the setpoint temperature set.
                                             this also means that the switching values of the hysteresis must not be too close together. Otherwise, cooling and heating could be switched on and off alternately.').
                                            '<br><br>
                                            <b>'._('example of automatic').':</b> '._("now it's getting exciting!").'<br>
                                            <i>'._('setpoint temperature').': 15°C; '._('switch-on value').': 5°C; '._('switch-off value').' 3°C</i><br>'.
                                            _('1st case').': '._('sensor temperature').' >= ('._('setpoint temperature').' + '._('switch-on value').' [=20°C]) = '._('cooling on').'<br>'.
                                            _('2st case').': '._('sensor temperature').' <= ('._('setpoint temperature').' + '._('switch-off value').' [=18°C]) = '._('cooling off').'<br>'.
                                            _('3st case').': '._('sensor temperature').' >= ('._('setpoint temperature').' - '._('switch-on value').' [=10°C]) = '._('heating on').'<br>'.
                                            _('4st case').': '._('sensor temperature').' <= ('._('setpoint temperature').' - '._('switch-off value').' [=12°C]) = '._('heating off').
                                            '<br><br>
                                            <b>'._('recommendation').':</b> '._('check the stored values in the logfile!').
                                            '<br><br>
                                            <b>'._('attention').':</b> '._('use only positive integers!').'<br><br>'; ?>
                                            <button class="art-button" type="button" onclick="help_temperature_noneFunction()"><?php echo _('close'); ?></button>
                                        </p>
                                        <hr>
                                        <!----------------------------------------------------------------------------------------Luftfeuchte-->
                                        <div style="<?php if ($modus == 0){print "display: none;";}?>">
                                        <table style="width: 100%;" class="miniature_writing">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3><?php echo _('humidity'); ?></h3><img src="images/icons/humidification_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_humidify_blockFunction()"><?php echo _('help'); ?></button></td>
                                                <td class="text_left_padding"><?php echo _('setpoint humidity'); ?></td>
                                                <td class="text_left_padding"><input name="setpoint_humidity_settings" maxlength="4" size="2" type="text" value=<?php echo $setpoint_humidity; ?>>%<span class="display_none" style="font-size: xx-small"> (0 <?php echo _('to'); ?> 99)</span></td>
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
                                            <?php echo '<b>'._('target humidity').
                                            ':</b> '._('the desired humidity is set here. The minimum value is theoretically 0% and a maximum of 99%.
                                            these values will be never reached normally. The circulating air is always active during humidification. The effectiveness of the dehumidification (automatic mode with with humidification and dehumidification) is dependent on the ambient air humidity, since only a passive dehumidification by exhaust air takes place.').
                                            '<br><br><b>'._('recommendation').':</b> '._('check the stored values in the logfile!').'<br><br>
                                             <b>'._('attention').'</b> '._('use only positive integers!').'<br><br>';?>
                                            <button class="art-button" type="button" onclick="help_humidify_noneFunction()"><?php echo _('close'); ?></button>
                                        </p>
                                        <hr>
                                        </div>
                                        <!----------------------------------------------------------------------------------------Umluft-->
                                        <table style="width: 100%;" class="miniature_writing">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3><?php echo _('timer circulatng'); ?></h3><img src="images/icons/circulate_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_exhausting_air_blockFunction()"><?php echo _('help'); ?></button></td>
                                                <td class="text_left_padding"><?php echo _('period every'); ?> </td>
                                                <td class="text_left_padding"><input type="text" size="3" maxlength="4" name="circulation_air_period_settings" value=<?php echo round($circulation_air_period); ?>><?php echo _('minutes'); ?><span class="display_none" style="font-size: xx-small"> (0 <?php echo _('to'); ?> 1440)</span></td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding"><?php echo _('for the duration of'); ?></td>
                                                <td class="text_left_padding"><input type="text" maxlength="4" size="3" name="circulation_air_duration_settings" value=<?php echo $circulation_air_duration; ?>><?php echo _('minutes'); ?><span class="display_none" style="font-size: xx-small"> (0=<?php echo _('off'); ?>)</span></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
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
                                            <?php echo '<b>'._('period').':</b> '._('this is used to set the pause time, which waits until the recirculation is switched on again. if the value is 0 (= no pause), the circulating air is permanently switched on. the maximum value is 1440min.').
                                            '<br><br>
                                            <b>'._('duration').':</b>'._('this sets the circulation time during which the fan is running. at 0, the circulating air timer function is switched off. the maximum value is 1440min.').
                                             '<br><br>
                                            <b>'._('note').':</b> '._('the circulating air fan runs independently of the timer settings - also during cooling, heating and humidification.').
                                             '<br><br>
                                            <b>'._('attention').':</b><br>'.
                                            _('period=0 und duration=0 is not useful and not allowed.').'<br><br>'; ?>
                                            <button class="art-button" type="button" onclick="help_exhausting_air_noneFunction()"><?php echo _('close'); ?></button>
                                        </p>
                                        <hr>
                                        <!----------------------------------------------------------------------------------------Abluft-->
                                        <table style="width: 100%;" class="miniature_writing">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3><?php echo _('timer exhausting air'); ?></h3><img src="images/icons/exhausting_42x42.png" alt=""><br><button class="art-button" type="button" onclick="help_circulation_air_blockFunction()"><?php echo _('help'); ?></button></td>
                                                <td class="text_left_padding"><?php echo _('period every'); ?> </td>
                                                <td class="text_left_padding"><input type="text" size="3" maxlength="4" name="exhaust_air_period_settings" value=<?php echo round($exhaust_air_period); ?>><?php echo _('minutes'); ?><span class="display_none" style="font-size: xx-small"> (0 <?php echo _('to'); ?> 1440)</span></td>
                                            </tr>
                                            <tr><td class="text_left_padding"><?php echo _('for the duration of'); ?></td>
                                                <td class="text_left_padding"><input type="text" maxlength="4" size="3" name="exhaust_air_duration_settings" value=<?php echo $exhaust_air_duration; ?>><?php echo _('minutes'); ?><span class="display_none" style="font-size: xx-small"> (0=<?php echo _('off'); ?>)</span></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td class="td_submitbutton">
                                                    <button class="art-button" name="manvals_form_submit" type="submit" value=""><?php echo _('save'); ?> </button>
                                                </td>
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
                                            <?php  echo '<b>'._('period').':</b> '._('this is used to set the pause time, which waits until the exhausting is switched on again. if the .lue is 0 (= no pause), the exhausting air is permanently switched on. the maximum value is 1440min.').
                                             '<br><br>
                                            <b>'._('duration').':</b> '._('this sets the exhausting time during which the fan is running. at 0, the exhausting air timer function is switched off. the maximum value is 1440min.').
                                             '<br><br>
                                            <b>'._('note').':</b> '._('The exhaust air fan runs independently of the timer settings - also during dehumidification in the "automatic mode with humidification and dehumidification.').
                                             '<br><br>
                                            <b>'._('attention').':</b><br>'.
                                            _('period=0 und duration=0 is not useful and not allowed.').'<br><br>'; ?>
                                            <button class="art-button" type="button" onclick="help_circulation_air_noneFunction()"><?php echo _('close'); ?></button>
                                        </p>
                                    </div>
                                </form>
