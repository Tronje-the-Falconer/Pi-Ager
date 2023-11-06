<?php 
                                    include 'header.php';                                     // Template-Kopf und Navigation
                                    include 'modules/names.php';                              // Variablen mit Strings
                                    include 'modules/database.php';                           // Schnittstelle zur Datenbank
?>
                                <?php
                                # 10 Sekunden anzeigen, dass System heruntergefahren wird
                                    $reboot_datetime = exec('date +"%Y-%m-%d %T"');
                                    echo '<p id=\'info-message\' style=\'color: #ff0000; font-size: 20px;\'><b>' . (_("reboot")) . '</b><br>' . $reboot_datetime . '<br>' . (_("You will automatically redirected to the start page")) . '</p><br><br>';
                                    shell_exec('sudo /var/sudowebscript.sh reboot > /dev/null 2>&1 &');
                                ?>
                                <img src="images/spinner.gif" alt=""/>
                                <script>
                                    window.addEventListener('beforeunload', (event) => {
                                        // Cancel the event as stated by the standard.
                                        event.preventDefault();
                                        // Chrome requires returnValue to be set.
                                        event.returnValue = '';
                                    });
                                    
                                    setTimeout(function(){
                                        document.getElementById("info-message").style.color="#000000";
                                        while (serverReachable() == false) {
                                            continue;
                                        }
                                        window.location.href = "index.php?rand=" + Math.random();
                                    }, 10000); 
                                        
                                    function serverReachable() {
                                    // IE vs. standard XHR creation
                                        var x = new ( window.ActiveXObject || XMLHttpRequest )( "Microsoft.XMLHTTP" ), s;
                                        x.open(
                                                // requesting the headers is faster, and just enough
                                                "HEAD",
                                                // append a random string to the current hostname,
                                                // to make sure we're not hitting the cache
                                                "//" + window.location.hostname + "/?rand=" + Math.random(),
                                                // make a synchronous request
                                                false
                                        );
                                        try {
                                            x.send();
                                            s = x.status;
                                                // Make sure the server is reachable
                                            if ( s >= 200 && s < 300 || s === 304 ) {
                                           //     alert('success');
                                                return true;
                                            } else {
                                           //     alert('no success');
                                                return false;
                                            }
                                                // catch network & other problems
                                        } catch (e) {
                                          //  alert('other problems');
                                            return false;
                                        }
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
