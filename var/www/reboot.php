<?php 
                                    include 'header.php';                                     // Template-Kopf und Navigation
                                    include 'modules/names.php';                              // Variablen mit Strings
                                    include 'modules/database.php';                           // Schnittstelle zur Datenbank
?>
                                <?php
                                # 10 Sekunden anzeigen, dass System heruntergefahren wird
                                    print '<p id=\'info-message\' style=\'color: #ff0000; font-size: 20px;\'><b>'. (_("reboot")) .'</b><br>' . date("m/d/y h:i:s a") . '<br>You will automatically redirected to the start page' . ' </p>
                                    <br><br>';

                                ?>
                                <img src="images/spinner.gif" alt=""/>
                                <script>
                                    setTimeout(function(){
                                        document.getElementById("info-message").style.color="#000000";
                                        while (serverReachable() == false) {
                                            continue;
                                        }
                                        window.location.href = "index.php";
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
