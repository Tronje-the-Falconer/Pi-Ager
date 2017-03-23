                                <button class="art-button" type="button" onclick="setconfig_blockFunction()"><?php echo _('set general configuration values'); ?></button>
                                <div id="set_config">
                                    <form method="post">
                                        <div class="hg_container" >
                                            <b><?php echo strtoupper(_('attention! be carful what you do!')); ?></b>
                                            <hr>
                                            <!----------------------------------------------------------------------------------------Temperatur-->
                                            <table style="width: 100%;" class="miniature_writing">
                                                <tr>
                                                    <td rowspan="4" class="td_png_icon"><h3><?php echo _('temperature'); ?></h3><img src="images/heating_cooling.png" alt=""><br><button class="art-button" type="button" onclick="help_temperature_config_blockFunction()"><?php echo _('help'); ?></button></td>
                                                    <td class="text_left_padding"><?php echo _('switch-on value'); ?>:</td>
                                                    <td class="text_left_padding"><input name="switch_on_cooling_compressor_config" type="text" maxlength="4" size="2" value=<?php echo $switch_on_cooling_compressor; ?>>°C
                                                        <span class="display_none" style="font-size: xx-small">
                                                            <?php 
                                                                if($modus == 0 || $modus == 1){
                                                                    echo '('._('on at')." ".($setpoint_temperature+$switch_on_cooling_compressor)."°C)";
                                                                }
                                                                elseif($modus == 2){
                                                                    echo '('._('on at')." ".($setpoint_temperature-$switch_on_cooling_compressor)."°C)";
                                                                }
                                                                else {
                                                                    echo _('see LOGS');
                                                                }
                                                            ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text_left_padding"><?php echo _('switch-off value').':'; ?></td>
                                                    <td class="text_left_padding"><input name="switch_off_cooling_compressor_config" type="text" maxlength="4" size="2" value= <?php echo $switch_off_cooling_compressor; ?>>°C
                                                        <span class="display_none" style="font-size: xx-small">
                                                            <?php 
                                                                if($modus == 0 || $modus == 1){
                                                                    echo '('._('off at').' '.($setpoint_temperature+$switch_off_cooling_compressor).'°C)';
                                                                }
                                                                elseif($modus == 2){
                                                                    echo '('._('off at').' '.($setpoint_temperature-$switch_off_cooling_compressor).'°C)';
                                                                }
                                                                else {
                                                                    echo _('see LOGS');
                                                                }
                                                            ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </table>
                                            <script>
                                                function help_temperature_config_blockFunction() {
                                                    document.getElementById('help_temperature_config').style.display = 'block';
                                                }
                                                function help_temperature_config_noneFunction() {
                                                    document.getElementById('help_temperature_config').style.display = 'none';
                                                }
                                            </script>
                                            <p id="help_temperature_config" class="help_p">
                                                <?php echo '<b>'._('switch-on value').':</b> '._('is the value at which the control becomes active (value limit: 0-10 ° C). This value must always be greater than the switch-off value.').'<br><b>'.
                                                _('switch-off value').':</b> '._('is the value at which the control becomes inactive (value: 0-10 ° C)').'<br>'.
                                                _('the values may not be the same in order to avoid a wild switching on and off.').
                                                '<br><br>
                                                <b>'._('recommendation').':</b> '._('check the stored values in the logfile!').
                                                '<br><br>
                                                <b>'._('attention').':</b> '._('use only positive integers!').'<br><br>'; ?>
                                                <button class="art-button" type="button" onclick="help_temperature_config_noneFunction()"><?php echo _('close'); ?></button>
                                            </p>
                                            <hr>
                                            <!----------------------------------------------------------------------------------------Luftfeuchte-->
                                            <table style="width: 100%;" class="miniature_writing">
                                                <tr>
                                                    <td rowspan="4" class="td_png_icon"><h3><?php echo _('humidity'); ?></h3><img src="images/humidification.png" alt=""><br><button class="art-button" type="button" onclick="help_humidify_config_blockFunction()"><?php echo _('help'); ?></button></td>
                                                    <td class="text_left_padding"><?php echo _('switch-on value').':'; ?></td>
                                                    <td class="text_left_padding"><input name="switch_on_humidifier_config" maxlength="3" size="2" type="text" value=<?php echo $switch_on_humidifier; ?>>%<span class="display_none" style="font-size: xx-small">
                                                    <?php 
                                                                if($modus == 0 || $modus == 1 || $modus == 2){
                                                                    echo '('._('on at'). ' '.($setpoint_humidity-$switch_on_humidifier)."%)";
                                                                }
                                                                else {
                                                                    echo _('see logs');
                                                                }
                                                    ?>
                                                        </span></td>
                                                </tr>
                                                <tr>
                                                    <td class="text_left_padding"><?php echo _('switch-off value').':'; ?></td>
                                                    <td class="text_left_padding"><input name="switch_off_humidifier_config" maxlength="3" size="2" type="text" value=<?php echo $switch_off_humidifier; ?>>%<span class="display_none" style="font-size: xx-small">
                                                    <?php 
                                                                if($modus == 0 || $modus == 1 || $modus == 2){
                                                                    echo '('._('off at'). ' '.($setpoint_humidity-$switch_off_humidifier)."%)";
                                                                }
                                                                else {
                                                                    echo _('see logs');
                                                                }
                                                    ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td class="text_left_padding"><?php echo _('delay'); ?>:</td>
                                                    <td class="text_left_padding"><input name="delay_humidify_config" maxlength="2" size="2" type="text" value=<?php echo $delay_humidify; ?>><?php echo _('minutes'); ?><span class="display_none" style="font-size: xx-small"> (0 <?php echo _('to'); ?> 60)</span></td>
                                                </tr>
                                            </table>
                                            <script>
                                                function help_humidify_config_blockFunction() {
                                                    document.getElementById('help_humidify_config').style.display = 'block';
                                                }
                                                function help_humidify_config_noneFunction() {
                                                    document.getElementById('help_humidify_config').style.display = 'none';
                                                }
                                            </script>
                                            <p id="help_humidify_config" class="help_p">
                                                <?php echo '<b><u>'._('switching hysteresis').'</u></b><br>
                                                <b>'._('switch-on value').':</b>'._('is the value at which the control becomes active (value: 0-10%)').'<br>
                                                <b>'._('switch-off value').':</b>'._('is the value at which the control becomes inactive (value: 0-10%)').'<br>'.
                                                _('the values may not be the same in order to avoid a wild switching on and off.').'<br><br>
                                                <b>'._('delay').':</b>'._('here the delay time is set until the humidifier turns on if the humidity is too low. this can be used to blast out the rapidly falling air humidity during "cooling", "timer exhaust" or "dehumidification". The minimum value is 0 minutes, the maximum 60 minutes.').'<br><br>
                                                <b>'._('example').'</b> <i>'._('target humidity').': 75%'._('switch-on value').': 5%'._('switch-off value').': 1%</i><br>'.
                                                _('switch-on humidity').' = '._('target humidity').' - '._('switch-on value').' --> 75% - 5% = 70%<br>'.
                                                _('switch-off humidity').' = '._('target humidity').' - '._('switch-off value').'--> 75% - 1% = 74%<br>'.
                                                _('delay').' = 5 '._('minutes').'<br>'.
                                                _('so if 70% relative humidity are reached, the control waits for 5 minutes. only then does the pi-ager humidify the air to 74% and then switch off humidification again.').'<br><br>
                                                <b>'._('example automatic mode with with humidification and dehumidification').':</b>'._('in this automatic mode, the humidity is completely automatically controlled.
                                                 the current humidity is determined first. it is then decided which method (humidification and dehumidification) is suitable for achieving the desired set-point humidity.
                                                 this also means that the switching values of the hysteresis must not be too close together. otherwise, humidification and dehumidification could always be switched on and off alternately.').'<br><br>
                                                 <b>'._('recommendation').':</b> '._('check the stored values in the logfile!').'<br><br>
                                                 <b>'._('attention').'</b> '._('use only positive integers!').'<br><br>';?>
                                                <button class="art-button" type="button" onclick="help_humidify_config_noneFunction()"><?php echo _('close'); ?></button>
                                            </p>
                                            <hr>
                                            <!----------------------------------------------------------------------------------------Sensortype-->
                                            <table style="width: 100%;" class="miniature_writing">
                                                <tr>
                                                    <td class="td_png_icon"><h3><?php echo _('sensortype'); ?></h3><img src="images/sensortype.png" alt=""><br><button class="art-button" type="button" onclick="help_sensortype_blockFunction()"><?php echo _('help'); ?></button>
                                                    </td>
                                                    <td style=" text-align: left; padding-left: 20px;">
                                                        <input type="radio" name="sensortype_config" value="1" <?php echo $checked_sens_1; ?>/><label> DHT11</label><br>
                                                        <input type="radio" name="sensortype_config" value="2" <?php echo $checked_sens_2; ?>/><label> DHT22</label><br>
                                                        <input type="radio" name="sensortype_config" value="3" <?php echo $checked_sens_3; ?>/><label> SHT</label><br>
                                                        <br>
                                                    </td>
                                                    
                                                </tr>
                                            </table>
                                            <script>
                                                function help_sensortype_blockFunction() {
                                                    document.getElementById('help_sensortype').style.display = 'block';
                                                }
                                                function help_sensortype_noneFunction() {
                                                    document.getElementById('help_sensortype').style.display = 'none';
                                                }
                                            </script>
                                            <p id="help_sensortype" class="help_p">
                                                <?php  echo '<b>'._('sensortype').':</b> '._('connect your sensor according to instructions and select the right type.');
                                                 echo '<br><br>'; ?>
                                                <button class="art-button" type="button" onclick="help_sensortype_noneFunction()"><?php echo _('close'); ?></button>
                                            </p>
                                            <hr>
                                            <!----------------------------------------------------------------------------------------Language-->
                                            <table style="width: 100%;" class="miniature_writing">
                                                <tr>
                                                    <td class="td_png_icon"><h3><?php echo _('language'); ?></h3><img src="images/language.png" alt=""><br><button class="art-button" type="button" onclick="help_language_blockFunction()"><?php echo _('help'); ?></button>
                                                    </td>
                                                    <td style=" text-align: left; padding-left: 20px;">
                                                        <input type="radio" name="language_config" value="de_DE" <?php echo $checked_language_1; ?>/><label> <?php echo _('german') ?></label><br>
                                                        <input type="radio" name="language_config" value="en_EN" <?php echo $checked_language_2; ?>/><label> <?php echo _('english') ?></label><br>
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
                                                <?php  echo '<b>'._('language').':</b> '._('set the language.');
                                                 echo '<br><br>'; ?>
                                                <button class="art-button" type="button" onclick="help_language_noneFunction()"><?php echo _('close'); ?></button>
                                            </p>
                                            <hr>
                                            <!----------------------------------------------------------------------------------------GPIO-->
                                            <table style="width: 100%;" class="miniature_writing">
                                                <tr>
                                                    <td class="td_png_icon"><h3><?php echo _("GPIO's"); ?></h3><img src="images/gpio.png" alt=""><br><button class="art-button" type="button" onclick="help_gpio_blockFunction()"><?php echo _('help'); ?></button>
                                                    </td>
                                                    <td class="text_left_padding"><?php echo _('cooling compressor'); ?>:</td>
                                                    <td class="text_left_padding"><input name="gpio_cooling_compressor_config" type="text" maxlength="4" size="2" value=<?php echo $gpio_cooling_compressor; ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td class="text_left_padding"><?php echo _('heater'); ?>:</td>
                                                    <td class="text_left_padding"><input name="gpio_heater_config" type="text" maxlength="4" size="2" value=<?php echo $gpio_heater; ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td class="text_left_padding"><?php echo _('humidifier'); ?>:</td>
                                                    <td class="text_left_padding"><input name="gpio_humidifier_config" type="text" maxlength="4" size="2" value=<?php echo $gpio_humidifier; ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td class="text_left_padding"><?php echo _('circulating air'); ?>:</td>
                                                    <td class="text_left_padding"><input name="gpio_circulating_air_config" type="text" maxlength="4" size="2" value=<?php echo $gpio_circulating_air; ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td class="text_left_padding"><?php echo _('exhausting air'); ?>:</td>
                                                    <td class="text_left_padding"><input name="gpio_exhausting_air_config" type="text" maxlength="4" size="2" value=<?php echo $gpio_exhausting_air; ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td class="text_left_padding"><?php echo _('uv light'); ?>:</td>
                                                    <td class="text_left_padding"><input name="gpio_uv_light_config" type="text" maxlength="4" size="2" value=<?php echo $gpio_uv_light; ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td class="text_left_padding"><?php echo _('light'); ?>:</td>
                                                    <td class="text_left_padding"><input name="gpio_light_config" type="text" maxlength="4" size="2" value=<?php echo $gpio_light; ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td class="text_left_padding"><?php echo _('reserved1'); ?>:</td>
                                                    <td class="text_left_padding"><input name="gpio_reserved1_config" type="text" maxlength="4" size="2" value=<?php echo $gpio_reserved1; ?>>
                                                    </td>
                                                </tr>
                                            </table>
                                            <script>
                                                function help_gpio_blockFunction() {
                                                    document.getElementById('help_gpio').style.display = 'block';
                                                }
                                                function help_gpio_noneFunction() {
                                                    document.getElementById('help_gpio').style.display = 'none';
                                                }
                                            </script>
                                            <div id="help_gpio" class="help_p">
                                                <?php  echo '<b>'._('gpio').':</b> '._('Connect all devices to the correct relays.').'<br>'.
                                                    _('If a relay fails, the device can be connected from the faulty relay to another and the control can be changed here.').'<br>';
                                                ?>
                                                <table style="width: 100%; border: 1px solid black; border-collapse: collapse; padding: 10px;" class="miniature_writing">
                                                    <tr>
                                                        <th><?php echo _('gpio') ?></th>
                                                        <th><?php echo _('relay nr') ?></th>
                                                        <th><?php echo _('standard device') ?></th>
                                                    </tr>
                                                    <tr>
                                                        <td>23</td>
                                                        <td>1</td>
                                                        <td><?php echo _('cooling compressor')?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>22</td>
                                                        <td>2</td>
                                                        <td><?php echo _('heater') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>27</td>
                                                        <td>3</td>
                                                        <td><?php echo _('humidifier') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>18</td>
                                                        <td>4</td>
                                                        <td><?php echo _('circulate air') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>17</td>
                                                        <td>5</td>
                                                        <td><?php echo _('exhaust air') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>6</td>
                                                        <td><?php echo _('uv-light') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>7</td>
                                                        <td><?php echo _('light') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>8</td>
                                                        <td><?php echo _('reserved') ?></td>
                                                    </tr>
                                                </table><br><br>
                                                <button class="art-button" type="button" onclick="help_gpio_noneFunction()"><?php echo _('close'); ?></button>
                                            </div>
                                            <input class="art-button" type="submit" value="<?php echo _('save'); ?>" />
                                            <button class="art-button" type="button" onclick="setconfig_noneFunction()"><?php echo _('hide'); ?></button>
                                        </div>
                                    </form>
                                </div>
                                <script>
                                    function setconfig_blockFunction() {
                                        document.getElementById('set_config').style.display = 'block';
                                    }
                                    function setconfig_noneFunction() {
                                        document.getElementById('set_config').style.display = 'none';
                                    }
                                </script>
