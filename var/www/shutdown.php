<?php 
                                    include 'header.php';                                     // Template-Kopf und Navigation
                                    include 'modules/names.php';                              // Variablen mit Strings
                                    include 'modules/database.php';                           // Schnittstelle zur Datenbank
?>
                                <?php
                                # 10 Sekunden anzeigen, dass System heruntergefahren wird
                                    print '<p id=\'info-message\' style=\'color: #ff0000; font-size: 20px;\'><b>'. (_("shutdown")) .'</b><br>' . date("m/d/y h:i:s a") . '<br>System shutdown after 10 seconds. You will automatically redirected to the start page' . ' </p>
                                    <br><br>';

                                ?>
                                <img src="images/spinner.gif" alt=""/>
                                <script>
                                    setTimeout(function(){
                                        document.getElementById("info-message").style.color="#000000";
                                        window.location.href = "index.php";
                                    }, 10000); 
                                       
                                </script>
                                  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            include 'footer.php';
        ?>
