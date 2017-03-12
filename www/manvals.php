<!-- Diese Einstellungen werden nur im manuellen Modus eingeblendet -->
<h2 class="art-postheader"><?php echo _('Manuelle Werte'); ?></h2>
                                <!----------------------------------------------------------------------------------------Betriebsart-->
                                <form method="post">
                                    <div class="hg_container" >
                                        <table style="width: 100%;" class="minischrift">
                                            <tr>
                                                <td class="td_png_icon"><h3><?php echo _('Betriebsart'); ?></h3><img src="images/operating_mode.png" alt=""><br><button class="art-button" type="button" onclick="help_betriebsart_blockFunction()"><?php echo _('Hilfe'); ?></button></td>
                                                <td style=" text-align: left; padding-left: 20px;">
                                                <input type="radio" name="modus" value="0" <?php echo $checked_0; ?>/><label> <?php echo _('Kuehlen'); ?></label><br>
                                                <input type="radio" name="modus" value="1" <?php echo $checked_1; ?>/><label> <?php echo _('Kuehlen mit Befeuchtung'); ?></label><br>
                                                <input type="radio" name="modus" value="2" <?php echo $checked_2; ?>/><label> <?php echo _('Heizen mit Befeuchtung'); ?></label><br>
                                                <input type="radio" name="modus" value="3" <?php echo $checked_3; ?>/><label> <?php echo _('Automatik mit Befeuchtung'); ?></label><br>
                                                <input type="radio" name="modus" value="4" <?php echo $checked_4; ?>/><label> <?php echo _('Automatik mit Be- und Entfeuchtung'); ?></label><br><br>
                                                <?php echo _('<b>Umluft- und Ablufttimer</b> koennen unabhaengig vom gewaehlten Modus genutzt werden.'); ?>
                                                </td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_betriebsart_blockFunction() {
                                                document.getElementById('help_betriebsart').style.display = 'block';
                                            }
                                            function help_betriebsart_noneFunction() {
                                                document.getElementById('help_betriebsart').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_betriebsart" class="help_p">
                                            <?php echo _('<b>Kuehlen:</b>  Es wird auf die eingestellte Temperatur mit Umluft gekuehlt.<br><br>
                                            <b>Kuehlen mit Befeuchtung:</b>  Es wird auf die eingestellte Temperatur mit Umluft gekuehlt und es wird befeuchtet,
                                            die Heizung wird nie angesteuert.<br><br>
                                            <b>Heizen mit Befeuchtung:</b> Es wird auf die eingestellte Temperatur mit Umluft geheizt und und es wird befeuchtet,
                                            die Kuehlung wird nie angesteuert.<br><br>
                                            <b>Automatik mit Befeuchtung:</b> Der Reifeschrank kuehlt oder heizt mit Umluft je nach eingestelltem Wert und es wird befeuchtet.<br><br>
                                            <b>Automatik mit Be- und Entfeuchtung:</b> Wie Automatik mit Befeuchtung, Zusatz: beim ueberschreiten der Luftfeuchte schaltet die Abluft ein, bis das Feuchte-Soll wieder erreicht ist.
                                            Da es sich um eine passive Entfeuchtung handelt, haengt die minimal erreichbare Luftfeuchte von der Trockenheit der Umgebungsluft ab.
                                            Um ein wildes Ein- und Ausschalten zu vermeiden, sollte die Befeuchtung unbedingt verzoegert werden (5-10min)!'); ?>
                                            <br><br>
                                            <button class="art-button" type="button" onclick="help_betriebsart_noneFunction()"><?php echo _('Schliessen'); ?></button>
                                        </p>

                                        <hr>
                                        <!----------------------------------------------------------------------------------------Temperatur-->
                                        <table style="width: 100%;" class="minischrift">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3><?php echo _('Temperatur'); ?></h3><img src="images/heating_cooling.png" alt=""><br><button class="art-button" type="button" onclick="help_temperatur_blockFunction()"><?php echo _('Hilfe'); ?></button></td>
                                                <td class="text_left_padding"><?php echo _('Soll-Temperatur'); ?>:</td>
                                                <td class="text_left_padding"><input name="temp" maxlength="4" size="2" type="text" value=<?php echo $setpoint_temperature; ?>>°C<span class="display_none" style="font-size: xx-small"> (0 <?php echo _('bis'); ?> 22)</span></td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding"><?php echo _('Einschaltwert'); ?>:</td>
                                                <td class="text_left_padding"><input name="temphyston" type="text" maxlength="4" size="2" value=<?php echo $switch_on_cooling_compressor; ?>>°C
                                                    <span class="display_none" style="font-size: xx-small">
                                                        <?php 
                                                            if($modus == 0 || $modus == 1){
                                                                print '('._('Ein bei')." ".($setpoint_temperature+$switch_on_cooling_compressor)."°C)";
                                                            }
                                                            elseif($modus == 2){
                                                                print '('._('Ein bei')." ".($setpoint_temperature-$switch_on_cooling_compressor)."°C)";
                                                            }
                                                            else {
                                                                print _('(siehe LOGS)');
                                                            }
                                                        ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding">Ausschaltwert:</td>
                                                <td class="text_left_padding"><input name="temphystoff" type="text" maxlength="4" size="2" value= <?php echo $switch_off_cooling_compressor; ?>>°C
                                                    <span class="display_none" style="font-size: xx-small">
                                                        <?php 
                                                            if($modus == 0 || $modus == 1){
                                                                echo "(Aus bei ".($setpoint_temperature+$switch_off_cooling_compressor)."°C)";
                                                            }
                                                            elseif($modus == 2){
                                                                echo "(Aus bei ".($setpoint_temperature-$switch_off_cooling_compressor)."°C)";
                                                            }
                                                            else {
                                                                echo '(siehe LOGS)';
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
                                            function help_temperatur_blockFunction() {
                                                document.getElementById('help_temperatur').style.display = 'block';
                                            }
                                            function help_temperatur_noneFunction() {
                                                document.getElementById('help_temperatur').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_temperatur" class="help_p">
                                            <b>Solltemperatur:</b> Hier wird die gewuenschte Temperatur eingestellt. Der minimale Wert betraegt 0°C, der maximale +22°C.
                                            Technisch bedingt koennen nicht alle Werte in jeder Betriebsart angefahren werden. Die Umluft ist waehrend der Kuehl-oder Heizphasen immer aktiv.<br><br>
                                            <b><u>Schalthysterese</u></b><br>
                                            <b>Einschaltwert:</b> ist der Wert, bei dem die Regelung aktiv wird (Wertgrenze:  0-10°C). Dieser Wert muss immer groesser sein, als der Ausschaltwert.<br>
                                            <b>Ausschaltwert:</b> ist der Wert, bei dem die Regelung inaktiv wird (Wertgrenze: 0-10°C)<br>
                                            Um ein wildes Ein- und Ausschalten zu vermeiden, duerfen die Werte nicht gleich sein.
                                            <br><br>
                                            <b>Beispiel fuer Kuehlung:</b> <i>Solltemperatur: 12°C; Einschaltwert: 3°C; Ausschaltwert 1°C</i><br>
                                            Einschalttemperatur = Solltemperatur + Einschaltwert --> 12°C + 3°C = 15°C<br>
                                            Ausschalttemperatur = Solltemperatur + Ausschaltwert --> 12°C + 1°C = 13°C<br>
                                            Wenn also 15 Grad ueberschritten werden, kuehlt der Schrank auf bis 13°C runter und schaltet dann ab, um ein zu starkes nachkuehlen zu vermeiden.
                                            Das gesamte Verhalten ist von Schrank zu Schrank unterschiedlich und daher individuell zu ermitteln.
                                            <br><br>
                                            <b>Beispiel fuer Heizung:</b> <i>Solltemperatur: 22°C; Einschaltwert: 3°C; Ausschaltwert 1°C</i><br>
                                            Einschalttemperatur = Solltemperatur - Einschaltwert --> 22°C - 3°C = 19°C<br>
                                            Ausschalttemperatur = Solltemperatur - Ausschaltwert --> 22°C - 1°C = 21°C<br>
                                            Wenn also 19 Grad unterschritten werden, heizt der Schrank auf bis 21°C auf und schaltet dann ab, um ein zu starkes nachheizen zu vermeiden.
                                            Das gesamte Verhalten ist von Schrank zu Schrank unterschiedlich und daher individuell zu ermitteln.
                                            <br><br>
                                            <b>Automatikmodus:</b> In jedem Automatikmodus wird die Temperatur vollstaendig automatisch geregelt.
                                            Zunaechst wird die aktuelle Temperatur ermittelt. Dann entscheidet sich, welches Verfahren (Kuehlen oder Heizen) geeignet ist,
                                            die eingestellte Solltemperatur zu erreichen. Das bedeutet aber auch, dass die Schaltwerte der Hysterese nicht zu eng beieinander
                                            liegen duerfen. Anderenfalls koennten Kuehlung und Heizung immer im Wechsel an und ausgeschaltet werden.
                                            <br><br>
                                            <b>Beispiel Automatik:</b> Jetzt wird es spannend!<br>
                                            <i>Solltemperatur: 15°C; Einschaltwert: 5°C; Ausschaltwert 3°C</i><br>
                                            1. Fall: SensorTemperatur >= (Solltemperatur + Einschaltwert [=20°C]) = Kuehlung an<br>
                                            2. Fall: SensorTemperatur <= (Solltemperatur + Ausschaltwert [=18°C]) = Kuehlung aus<br>
                                            3. Fall: SensorTemperatur >= (Solltemperatur - Einschaltwert [=10°C]) = Heizung an<br>
                                            4. Fall: SensorTemperatur <= (Solltemperatur - Ausschaltwert [=12°C]) = Heizung aus
                                            <br><br>
                                            <b>EMPFEHLUNG:</b> Kontrolle der gespeicherten Werte im Logfile!
                                            <br><br>
                                            <b>ACHTUNG:</b> Nur positive ganze Zahlen verwenden!<br><br>
                                            <button class="art-button" type="button" onclick="help_temperatur_noneFunction()">Schliessen</button>
                                        </p>
                                        <hr>
                                        <!----------------------------------------------------------------------------------------Luftfeuchte-->
                                        <div style="<?php if ($modus == 0){print "display: none;";}?>">
                                        <table style="width: 100%;" class="minischrift">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3>Luftfeuchte</h3><img src="images/umidification.png" alt=""><br><button class="art-button" type="button" onclick="help_befeuchtung_blockFunction()">Hilfe</button></td>
                                                <td class="text_left_padding">Soll-Feuchtigkeit</td>
                                                <td class="text_left_padding"><input name="hum" maxlength="4" size="2" type="text" value=<?php echo $setpoint_humidity; ?>>%<span class="display_none" style="font-size: xx-small"> (0 bis 99)</span></td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding">Einschaltwert:</td>
                                                <td class="text_left_padding"><input name="humhyston" maxlength="3" size="2" type="text" value=<?php echo $switch_on_humidifier; ?>>%<span class="display_none" style="font-size: xx-small">
                                                <?php 
                                                            if($modus == 0 || $modus == 1 || $modus == 2){
                                                                echo "(Ein bei ".($setpoint_humidity-$switch_on_humidifier)."%)";
                                                            }
                                                            else {
                                                                echo '(siehe LOGS)';
                                                            }
                                                ?>
                                                    </span></td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding">Ausschaltwert:</td>
                                                <td class="text_left_padding"><input name="humhystoff" maxlength="3" size="2" type="text" value=<?php echo $switch_off_humidifier; ?>>%<span class="display_none" style="font-size: xx-small">
                                                <?php 
                                                            if($modus == 0 || $modus == 1 || $modus == 2){
                                                                echo "(Aus bei ".($setpoint_humidity-$switch_off_humidifier)."%)";
                                                            }
                                                            else {
                                                                echo '(siehe LOGS)';
                                                            }
                                                ?></span></td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding">Verzoegerung:</td>
                                                <td class="text_left_padding"><input name="humdelay" maxlength="2" size="2" type="text" value=<?php echo $delay_humidify; ?>>Min<span class="display_none" style="font-size: xx-small"> (0 bis 60)</span></td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_befeuchtung_blockFunction() {
                                                document.getElementById('help_befeuchtung').style.display = 'block';
                                            }
                                            function help_befeuchtung_noneFunction() {
                                                document.getElementById('help_befeuchtung').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_befeuchtung" class="help_p">
                                            <b>Sollfeuchtigkeit:</b> Hier wird die gewuenschte Luftfeuchte eingestellt. Der minimale Wert betraegt theoretisch 0% und maximal 99%.
                                            Diese Werte werden aber in der Regel nie erreicht werden. Die Umluft ist waehrend der Befeuchtung immer aktiv. Die Wirksamkeit der Entfeuchtung (Automatikmodus mit mit Be- und Entfeuchtung) ist abhaengig von der Umgebungsluftfeuchte, da nur eine passive Entfeuchtung durch Abluft stattfindet.<br><br>
                                            <b><u>Schalthysterese</u></b><br>
                                            <b>Einschaltwert:</b> ist der Wert, bei dem die Regelung aktiv wird (Wertgrenze: 0-10%)<br>
                                            <b>Ausschaltwert:</b> ist der Wert, bei dem die Regelung inaktiv wird (Wertgrenze:0-10%)<br>
                                            Um ein wildes Ein- und Ausschalten zu vermeiden, duerfen die Werte nicht gleich sein.
                                            <br><br>
                                            <b>Verzoegerung:</b> hier wird die Verzoegerungszeit eingestellt, bis der Befeuchter bei zu niedriger Luftfeuchtigkeit einschaltet.
                                            Damit kann die kurzeitig fallende Luftfeuchtigkeit beim "Kuehlen", beim "Timer-Abluft" oder "Entfeuchten" ausgeblendet werden. Der minimale Wert betraegt 0 Minuten, der maximale 60 Minuten.
                                            <br><br>
                                            <b>Beispiel:</b> <i>Sollfeuchtigkeit: 75%; Einschaltwert: 5%; Ausschaltwert 1%</i><br>
                                            Einschaltfeuchtigkeit = Sollfeuchtigkeit - Einschaltwert --> 75% - 5% = 70%<br>
                                            Ausschaltfeuchtigkeit = Sollfeuchtigkeit - Ausschaltwert --> 75% - 1% = 74%<br>
                                            Verzoegerung = 5 Minuten<br>
                                            Wenn also 70% Luftfeuchtigkeit unterschritten werden, wartet die Regelung 5 Minuten ab. Erst dann befeuchtet der Schrank die Luft bis auf 74% und schaltet anschliessend Befeuchtung wieder ab.
                                            <br><br>
                                            <b>Beispiel Automatikmodus mit mit Be- und Entfeuchtung:</b> In diesem Automatikmodus wird die Luftfeuchtigkeit vollstaendig automatisch geregelt.
                                            Zunaechst wird die aktuelle Luftfeuchtigkeit ermittelt. Dann entscheidet sich, welches Verfahren (Be- und Entfeuchtung) geeignet ist,
                                            die eingestellte Soll-Feuchtigkeit zu erreichen. Das bedeutet aber auch, dass die Schaltwerte der Hysterese nicht zu eng beieinander
                                            liegen duerfen. Anderenfalls koennten Befeuchtung und Entfeuchtung immer im Wechsel an und ausgeschaltet werden.
                                            <br><br>
                                            <b>EMPFEHLUNG:</b> Kontrolle der gespeicherten Werte im Logfile!
                                            <br><br>
                                            <b>ACHTUNG:</b> Nur positive ganze Zahlen verwenden!<br><br>
                                            <button class="art-button" type="button" onclick="help_befeuchtung_noneFunction()">Schliessen</button>
                                        </p>
                                        <hr>
                                        </div>
                                        <!----------------------------------------------------------------------------------------Umluft-->
                                        <table style="width: 100%;" class="minischrift">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3>Timer Umluft</h3><img src="images/circulating.png" alt=""><br><button class="art-button" type="button" onclick="help_umluft_blockFunction()">Hilfe</button></td>
                                                <td class="text_left_padding">Periode alle </td>
                                                <td class="text_left_padding"><input type="text" size="3" maxlength="4" name="tempoff" value=<?php echo round($circulation_air_period); ?>>Min<span class="display_none" style="font-size: xx-small"> (0 bis 1440)</span></td>
                                            </tr>
                                            <tr>
                                                <td class="text_left_padding">fuer die Dauer von</td>
                                                <td class="text_left_padding"><input type="text" maxlength="4" size="3" name="tempon" value=<?php echo $circulation_air_duration; ?>>Min<span class="display_none" style="font-size: xx-small"> (0=aus)</span></td>
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
                                            function help_umluft_blockFunction() {
                                                document.getElementById('help_umluft').style.display = 'block';
                                            }
                                            function help_umluft_noneFunction() {
                                                document.getElementById('help_umluft').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_umluft" class="help_p">
                                            <b>Periode:</b> Hier wird die Pausenzeit eingestellt, die bis zum erneuten Einschalten der Umluft abgewartet wird. Bei Wert 0 (= keine Pause) ist die Umluft dauerhaft eingeschaltet. Der maximale Wert liegt bei 1440min.
                                            <br><br>
                                            <b>Dauer:</b> Hier wird die Umluftzeit eingestellt, waehrend der der Luefter laeuft. Bei Wert 0 ist die Umluft-Timerfunktion ausgeschaltet. Der maximale Wert betraegt 1440min.
                                            <br><br>
                                            <b>Hinweis:</b> Der Umluft-Luefter laeuft -unabhaengig von den Timereinstellungen- ausserdem auch waehrend der Kuehlung, Heizung und Befeuchtung.
                                            <br><br>
                                            <b>ACHTUNG:</b><br>
                                            Periode=0 und Dauer=0 ist nicht sinnvoll und nicht erlaubt.<br><br>
                                            <button class="art-button" type="button" onclick="help_umluft_noneFunction()">Schliessen</button>
                                        </p>
                                        <hr>
                                        <!----------------------------------------------------------------------------------------Abluft-->
                                        <table style="width: 100%;" class="minischrift">
                                            <tr>
                                                <td rowspan="4" class="td_png_icon"><h3>Timer Abluft</h3><img src="images/exhausting.png" alt=""><br><button class="art-button" type="button" onclick="help_abluft_blockFunction()">Hilfe</button></td>
                                                <td class="text_left_padding">Periode alle </td>
                                                <td class="text_left_padding"><input type="text" size="3" maxlength="4" name="tempoff1" value=<?php echo round($exhaust_air_period); ?>>Min<span class="display_none" style="font-size: xx-small"> (0 bis 1440)</span></td>
                                            </tr>
                                            <tr><td class="text_left_padding">fuer die Dauer von</td>
                                                <td class="text_left_padding"><input type="text" maxlength="4" size="3" name="tempon1" value=<?php echo $exhaust_air_duration; ?>>Min<span class="display_none" style="font-size: xx-small"> (0=aus)</span></td>
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
                                            function help_abluft_blockFunction() {
                                                document.getElementById('help_abluft').style.display = 'block';
                                            }
                                            function help_abluft_noneFunction() {
                                                document.getElementById('help_abluft').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_abluft" class="help_p">
                                            <b>Periode:</b> Hier wird die Pausenzeit eingestellt, die bis zum erneuten Einschalten der Abluft abgewartet wird. Bei Wert 0 (= keine Pause) ist die Abluft dauerhaft eingeschaltet. Der maximale Wert liegt bei 1440min.
                                            <br><br>
                                            <b>Dauer:</b> Hier wird die Abluftzeit eingestellt, waehrend der der Luefter laeuft. Bei Wert 0 ist die Abluft-Timerfunktion ausgeschaltet. Der maximale Wert betraegt 1440min.
                                            <br><br>
                                            <b>Hinweis:</b> Der Abluft-Luefter laeuft -unabhaengig von den Timereinstellungen- ausserdem auch waehrend der Entfeuchtung im Modus "Automatik mit Be- und Entfeuchtung".
                                            <br><br>
                                            <b>ACHTUNG:</b><br>
                                            Periode=0 und Dauer=0 ist nicht sinnvoll und nicht erlaubt.<br><br>
                                            <button class="art-button" type="button" onclick="help_abluft_noneFunction()">Schliessen</button>
                                        </p>
                                        <hr>
                                        <!----------------------------------------------------------------------------------------Sensortyp-->
                                        <table style="width: 100%;" class="minischrift">
                                            <tr>
                                                <td class="td_png_icon"><h3>Sensortyp</h3><img src="images/sensortype.png" alt=""><br><button class="art-button" type="button" onclick="help_sensortyp_blockFunction()">Hilfe</button>
                                                </td>
                                                <td style=" text-align: left; padding-left: 20px;">
                                                    <input type="radio" name="sensortype" value="1" <?php echo $checked_sens_1; ?>/><label> DHT11</label><br>
                                                    <input type="radio" name="sensortype" value="2" <?php echo $checked_sens_2; ?>/><label> DHT22</label><br>
                                                    <input type="radio" name="sensortype" value="3" <?php echo $checked_sens_3; ?>/><label> SHT75</label><br>
                                                    <br>
                                                </td>
                                                <td class="td_submitbutton">
                                                    <input class="art-button" type="submit" value="Speichern" />
                                                </td>
                                            </tr>
                                        </table>
                                        <script>
                                            function help_sensortyp_blockFunction() {
                                                document.getElementById('help_sensortyp').style.display = 'block';
                                            }
                                            function help_sensortyp_noneFunction() {
                                                document.getElementById('help_sensortyp').style.display = 'none';
                                            }
                                        </script>
                                        <p id="help_sensortyp" class="help_p">
                                            <b>Sensotyp:</b> Schliesse deinen Sensor nach Anleitung an und waehle hier den richtigen Typ aus.
                                            <br><br>
                                            <button class="art-button" type="button" onclick="help_sensortyp_noneFunction()">Schliessen</button>
                                        </p>
                                    </div>
                                </form>
