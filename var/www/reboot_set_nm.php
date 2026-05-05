<?php 
                                    include 'header.php';                                     // Template-Kopf und Navigation
                                    include 'modules/names.php';                              // Variablen mit Strings
                                    include 'modules/database.php';                           // Schnittstelle zur Datenbank
?>
                                <?php
                                # wait until connection is established, NetworkManager needs reboot when WLAN0 settings were setup
                                    $reboot_datetime = exec('date +"%Y-%m-%d %T"');
                                    echo '<p id=\'info-message\' style=\'color: #ff0000; font-size: 20px;\'><b>' . _("NetworkManager WiFi Setup") . '</b><br>' . $reboot_datetime . '<br>' . '</p><br>';
                                    $htmlpwd = $_GET["pwd"];
                                    $pwd = base64_decode($htmlpwd);
                                    $htmlssid = $_GET["ssid"];
                                    $ssid = base64_decode($htmlssid);
                                    $htmlcountry = $_GET["country"];
                                    $country = base64_decode($htmlcountry);
                                    # echo 'pwd = ' . $pwd . '<br>';
                                    # echo 'ssid = ' . $ssid . '<br>';
                                    # echo 'country = ' . $country . '<br>';
                                    $exec_data = [];
                                    $exec_status = 0;
                                    $cmd = "sudo /var/sudowebscript.sh nm_set_pw_ssid " . "'" . $pwd . "'" . " '" . $ssid . "'" . " '" . $country . "'" ;
                                    # echo 'cmd = ' . $cmd . '<br>';
                                    exec($cmd, $exec_data, $exec_status);
                                    # echo 'return status from sudowebscript : ' . $exec_status . '<br>';
                                    if ($exec_status != 0) {
                                        echo '<script> alert("'. _('Network Manager returned an error. Possible cause: incorrect WiFi parameters') . '");' . 'window.location.href = "admin.php";' . '</script>';
                                    }
                                    else {
                                        $exec_data = [];
                                        $exec_status = 0;
                                        exec('sudo nmcli -g ip4.address dev show wlan0', $exec_data, $exec_status);
                                        # var_dump($exec_data);
                                        # echo 'pi-ager ip address = ' . $exec_data[0] . '<br>';
                                        echo '<div style="text-align: center;"><p style="margin: 10px 0; padding: 5px; border: 1px solid #999; width: 70%; word-wrap: break-all; margin: auto; ">' . _('Network setup successfull. Please disconnect your tablet, smartphone or notebook now from the Pi-Ager accesspoint and connect to your WiFi router. Then you can access your Pi-Ager by entering the IP Address ') . '<b>' . str_replace("/24", "", $exec_data[0]) . '</b>' . _(' into your browser address field.') . '</p></div><br><br>';
                                        # echo '<script> alert("'. _('Network setup successfull. Please disconnect from Pi-Ager accesspoint and connect to your WiFi router with IP address') . ' : ' . $exec_data[0] . '");' . '</script>';
                                        # shell_exec('sudo /var/sudowebscript.sh reboot > /dev/null 2>&1 &');
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--        </div> -->
        <?php 
            include 'footer.php';
        ?>
