<?php 
                                    include 'header.php';                                       // Template-Kopf und Navigation
                                    include 'modules/database.php';                             // Schnittstelle zur Datenbank
                                    include 'modules/names.php';                                // Variablen mit Strings
                                    include 'modules/read_settings_db.php';                   // Liest die Einstellungen (Temperaturregelung, Feuchte, Lueftung) und Betriebsart des RSS
                                    include 'modules/read_config_db.php';                     // Liest die Grundeinstellungen Sensortyp, Hysteresen, GPIO's)
                                    include 'modules/read_operating_mode_db.php';                  // Liest die Art der Reifesteuerung
                                    include 'modules/read_gpio.php';                            // Liest den aktuellen Zustand der GPIO-E/A
                                    include 'modules/read_current_db.php';                    // Liest die gemessenen Werte Temp, Humy, Timestamp
                                ?>
                                <h2 class="art-postheader"><?php echo _('scale wizzard'); ?></h2>
                                <!-----------------------------------Wizzard für Scale Reference Unit-->
                                <!-----------------------------------Warnhinweis das dies nicht bei aktuellen Messungen gemacht werden darf-->
                                <!-----------------------------------Messen ohne Gewicht-->
                                <!-----------------------------------Messen mit definiertem Gewicht-->
                                <!-----------------------------------angeben des Gewichtes-->
                                <!-----------------------------------Errechnen der reference Unit-->
                                <!-----------------------------------schreiben der Reference Unit in DB-->
                                <!-----------------------------------Messen Button um werte mit gewichten zu messen mit Kontrolle-->
                                <!-----------------------------------Anzeige des Gemessenen Wertes-->
                                <!-----------------------------------scale.py neu starten-->
                                
