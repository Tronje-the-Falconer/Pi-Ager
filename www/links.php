<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <head>
        <title>Reifeschranksteuerung</title>
        <link href="style.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
            .auto-style1 {text-align: left;}
            .auto-style2 {text-decoration: underline;}
            .auto-style3 {text-align: center;}
        </style>
    </head>
    <body>
        <div id="header">
            <h1>Reifeschranksteuerung</h1>
        </div>
        <?php
        #ini_set( 'display_errors', true );
        #error_reporting( E_ALL );
        #Namen der Reifetabelle in tables.json speichern
            if($_POST['Reifetab']){
                $timestamp = time();
                $array = array( 'Reifetab' => $_POST['Reifetab'],'date' => $timestamp);                
                $jsoninput = json_encode($array);
                file_put_contents('tabels.json', $jsoninput);
        #3 sekunden anzeige dass namen der reifetabelle gespeichert wurde
        ?>
        <p id="info-message"><font color="#FF0000">Reifetabelle gespeichert </font></p>
        <script language="javascript">
            setTimeout(function(){document.getElementById('info-message').style.display='none'}, 3000)
        </script>    
        <?php
            }
            #eingestellte Werte in settings.json speichern
            if($_POST['temp']<>0){
                $timestamp = time();
                $array = array( 'temp' => (float)$_POST['temp'],
                    'mod' => (int)$_POST['mod'], 
                    'hum' => (float)$_POST['hum'],
                    'tempon' => (int)$_POST['tempon']*60,
                    'tempoff' => (int)$_POST['tempoff']*3600-$_POST['tempon']*60,
                    'tempon1' => (int)$_POST['tempon1']*60,
                    'tempoff1' => (int)$_POST['tempoff1']*3600-$_POST['tempon1']*60,
                    'temphyston' => (float)$_POST['temphyston'],
                    'temphystoff' => (float)$_POST['temphystoff'],
                    'humhyston' => (float)$_POST['humhyston'],
                    'humhystoff' => (float)$_POST['humhystoff'],
                    'humdelay' => (float)$_POST['humdelay'],
                    'sensortype' => (int)$_POST['sensortype'], # Sensortyp auswählbar machen
                    'date' => $timestamp);                
                $jsoninput = json_encode($array);
                file_put_contents('settings.json', $jsoninput);
                $f=fopen("logfile.txt","a");
                fwrite($f, "\n".date("d.m.Y H:i")." Werte wurden manuell aus Webinterface geändert:");
                fwrite($f, "\n"."mod ".$array['mod']);
                fwrite($f, "\n"."temp ".$array['temp']);
                fwrite($f, "\n"."hum ".$array['hum']);
                fwrite($f, "\n"."tempon ".$array['tempon']);
                fwrite($f, "\n"."tempoff ".$array['tempoff']);
                fwrite($f, "\n"."tempon1 ".$array['tempon1']);
                fwrite($f, "\n"."tempoff1 ".$array['tempoff1']);
                fwrite($f, "\n"."temphyston ".$array['temphyston']);
                fwrite($f, "\n"."temphystoff ".$array['temphystoff']);
                fwrite($f, "\n"."humhyston ".$array['humhyston']);
                fwrite($f, "\n"."humhystoff ".$array['humhystoff']);
                fwrite($f, "\n"."humdelay ".$array['humdelay']);
                fwrite($f, "\n"."sensortype ".$array['sensortype']); # Sensortyp auswählbar machen
                fwrite($f, "\n"."***********************************************************************");
                fclose($f);
            #3 sekunden Anzeige dass die Werte gespeichert wurden
        ?>
        <p id="info-message"><font color="#FF0000">Werte gespeichert </font></p>
        <script language="javascript">
            setTimeout(function(){document.getElementById('info-message').style.display='none'}, 3000)
        </script>    
        <?php
            }
            #programme Rss.py und/oder Reifetab.py starten/stoppen
            if (isset($_POST['button'])){
                $valrs = shell_exec("sudo /var/sudowebscript.sh grrss"); #Rss.py         
                if($valrs == 0) {
                    shell_exec('sudo /var/sudowebscript.sh startrss'); 
                    $f=fopen("logfile.txt","w");
                    fwrite($f, "\n".date("d.m.Y H:i")." Programm RSS.py aus Webinterface gestartet");
                    fwrite($f,"\n"."***********************************************************************");
                    fclose($f);
                }
            }
            if (isset($_POST['button1'])){
                $valrs = shell_exec("sudo /var/sudowebscript.sh grrss"); #Rss.py
                if($valrs == 0) {
                    shell_exec('sudo /var/sudowebscript.sh startrss'); 
                    $f=fopen("logfile.txt","w");
                    fwrite($f, "\n".date("d.m.Y H:i")." Programm RSS.py aus Webinterface gestartet");
                    fwrite($f,"\n"."***********************************************************************");
                    fclose($f);
                }
                $valtab = shell_exec("sudo /var/sudowebscript.sh grreifetab"); #Reifetab.py
                if($valtab == 0) {
                    shell_exec('sudo /var/sudowebscript.sh startreifetab'); 
                    $f=fopen("logfile.txt","a");
                    fwrite($f, "\n".date("d.m.Y H:i")." Programm Reifetab.py aus Webinterface gestartet");
                    fwrite($f,"\n"."***********************************************************************");
                    fclose($f);            
                }
            }   
            if (isset($_POST['button3'])){
                shell_exec("sudo /var/sudowebscript.sh stoprss");
                shell_exec("sudo /var/sudowebscript.sh stopreifetab");
                $f=fopen("logfile.txt","a");
                fwrite($f, "\n".date("d.m.Y H:i")." Programm RSS.py (und, falls aktiv auch Reifetab.py) aus Webinterface gestoppt");
                fwrite($f,"\n"."***********************************************************************");
                fclose($f);
            }    
            if (isset($_POST['button4'])){
                shell_exec("sudo /var/sudowebscript.sh stopreifetab");
                $f=fopen("logfile.txt","a");
                fwrite($f,"\n". date("d.m.Y H:i")." Programm Reifetab.py aus Webinterface gestoppt");
                fwrite($f,"\n"."***********************************************************************");
                fclose($f);
            }
            # current.json auslesen um aktuelle werte von temperatur und luftfeuchtigkeit anzuzeigen
            $API = file_get_contents("current.json");
            $array = json_decode($API, true);
            $temp_float = $array['temperatur'];
            $hum_float = $array['luftfeuchtigkeit'];
            # settings.json auslesen um sollwerte wieder zu geben
            $SET = file_get_contents("settings.json");
            $array1 = json_decode($SET, true);
            $mod=$array1['mod'];
            if ($mod==1) {
                $modus='Kühlen';
            }elseif ($mod==2) {
                $modus='Heizen';
            }elseif ($mod==3) {
                $modus='Auto';
            }elseif ($mod==4) {
                $modus='Automatik mit Luftaustausch';
            }
            $sensorvalue=$array1['sensortype'];
            if ($sensorvalue==1) {
                $sensor='DHT11';
            }elseif ($sensorvalue==2) {
                $sensor='DHT22';
            }elseif ($sensorvalue==3) {
                $sensor='SHT75';
            }
            $tempsoll_float = $array1['temp'];
            $humsoll_float = $array1['hum'];
            $tempon = $array1['tempon'];
            $tempoff = $array1['tempoff'];
            $tempon1 = $array1['tempon1'];
            $tempoff1 = $array1['tempoff1'];
            $temphyston = $array1['temphyston'];
            $temphystoff = $array1['temphystoff'];
            $humhyston = $array1['humhyston'];
            $humhystoff = $array1['humhystoff'];
            $tempoff = $tempoff/3600+$tempon/3600;
            $tempon = $tempon/60;
            $tempoff1 = $tempoff1/3600+$tempon1/3600;
            $tempon1 = $tempon1/60;
            $tempoff = number_format($tempoff,2);
            $tempoff1 = number_format($tempoff1,2);
            #schaltzustände anzeigen wiringpi
            $val22 = shell_exec("sudo /var/sudowebscript.sh r22"); #cool
            $val27 = shell_exec("sudo /var/sudowebscript.sh r27"); #heat
            $val18 = shell_exec("sudo /var/sudowebscript.sh r18"); #umluft
            $val23 = shell_exec("sudo /var/sudowebscript.sh r23"); #luftaustausch
            $val24 = shell_exec("sudo /var/sudowebscript.sh r24"); #luftbefeuchter
            #Prüfen ob Programme laufen
            $valtab = shell_exec("sudo /var/sudowebscript.sh grreifetab"); #Reifetab.py
            $valrss = shell_exec("sudo /var/sudowebscript.sh grrss"); #Rss.py

            if($val22 == 0) {$cool = "LED_on.jpg"; }
            if($val22 == 1) {$cool = "LED_off.jpg"; }
            if($val27 == 0) {$heat = "LED_on.jpg"; }
            if($val27 == 1) {$heat = "LED_off.jpg"; }
            if($val18 == 0) {$uml = "LED_on.jpg"; }
            if($val18 == 1) {$uml = "LED_off.jpg"; }
            if($val23 == 0) {$lat = "LED_on.jpg"; }
            if($val23 == 1) {$lat = "LED_off.jpg"; }
            if($val24 == 0) {$lbf = "LED_on.jpg"; }
            if($val24 == 1) {$lbf = "LED_off.jpg"; }
            if($valtab == 0) {$tabelle = "LED_off.jpg"; 
            }else
                {$tabelle = "LED_on.jpg"; }
            if($valrss == 0) {$rss = "LED_off.jpg"; 
            }else
                {$rss = "LED_on.jpg"; }
            # Funktion zur Anzeige der Temp und Lft
            function tempParts($array, $index) {
                $parts=explode('.', number_format($array,1));
                return $parts[$index];
            }
            #Schaltzustände anzeigen
        ?>
        <div id="nav">
            <fieldset name="Group1"><legend class="auto-style3">Schaltzustände</legend>
                Kühlung <img src="<?= $cool ?>" alt="Pin17" Style=width:15px;height:15px;><br>
                Heizung <img src="<?= $heat ?>" alt="Pin17" Style=width:15px;height:15px;><br>
                Luftbefeuchter <img src="<?= $lbf ?>" alt="Pin17" Style=width:15px;height:15px;><br>
                Umluft <img src="<?= $uml ?>" alt="Pin17" Style=width:15px;height:15px;><br>
                Luftaustausch <img src="<?= $lat ?>" alt="Pin17" Style=width:15px;height:15px;><br>
                Rss.py <img src="<?= $rss ?>" alt="Pin17" Style=width:15px;height:15px;><br>
                Reifetab.py <img src="<?= $tabelle ?>" alt="Pin17" Style=width:15px;height:15px;><br>
            </fieldset> 
        <!--Eingestellte Werte anzeigen-->
        <br>
            <fieldset name="Group1"><legend class="auto-style3">Eingestellte Werte</legend>
                <u>Kontrolle:</u> 
                <?php
                    $valtab = shell_exec("sudo /var/sudowebscript.sh grreifetab"); #Reifetab.py
                    if ($valtab){
                        $API = file_get_contents("tabels.json");
                        $array1 = json_decode($API, true);
                        $temp = $array1['Reifetab'];
                        echo $temp;
                    }else {
                        echo "manuell";
                    }
                ?>
                <u>Modus:</u>  <?=$modus?><br>
                <u>Sensor:</u> <?=$sensor?><br>
                <u>Temperatur:</u> <?=$tempsoll_float?>°C<br>
                Ein <?=$temphyston?> Aus <?=$temphystoff?> <br>
                <u>Luftfeuchtigkeit:</u> <?=$humsoll_float?>%<br>
                Ein <?=$humhyston?> Aus <?=$humhystoff?><br>
                <span class="auto-style2">Luftumwälzung </span> <br class="auto-style2">
                Alle <?=$tempoff?> Std <br>für <?=$tempon?> Min<br>
                <span class="auto-style2">Luftaustausch</span><br class="auto-style2">
                Alle <?=$tempoff1?> Std <br>für <?=$tempon1?> Min<br>
            </fieldset>
            <!--Dateien mit Reifeprogrammtabellen  suchen-->
            <?php
                $valtab = shell_exec("sudo /var/sudowebscript.sh grreifetab"); #Reifetab.py
                if ($valtab==0){
                $x = 0;
                    foreach (glob("../../opt/RSS/*.csv") as $filename)
                    {
                        $pdf[$x] = end(explode('/',$filename));
                        $info = pathinfo($pdf[$x]);
                        $pdf[$x] = $info['filename'];    
                        $x++;
                    }
                $API = file_get_contents("tabels.json");
                $array1 = json_decode($API, true);
                $temp = $array1['Reifetab'];
            ?>
            <br>
            <!--Reifetabelle auswählen-->                
            <fieldset name="Group1"><legend class="auto-style1">Tabelle für Reifeprog.</legend>
                <form  method="POST" >        
                    <p>
                        <select name="Reifetab">
                            <option selected="selected"><?=$temp ?></option>
                            <?php
                                foreach($pdf as $name) { 
                                if ($name<>$temp){
                            ?>
                            <option value="<?= $name ?>"><?= $name ?></option>
                            <?php
                                    }
                                } 
                            ?>    
                        </select>
                    </p>
                    <p><input type="submit" value="absenden" /></p>
                </form>
            </fieldset>
            <?php
                } 
            ?>
            <br>
            <!--Programme starten/stoppen-->        
            <fieldset name="Group1"><legend class="auto-style3">Progr. Start/Stop</legend>
                <form  method="post">
                    <p>
                    <?php 
                        $valrs = shell_exec("sudo /var/sudowebscript.sh grrss"); #Rss.py        
                        $valtab = shell_exec("sudo /var/sudowebscript.sh grreifetab"); #Reifetab.py
                        if($valrs == 0 and $valtab) {
                            shell_exec('sudo /var/sudowebscript.sh stopreifetab');
                            $valtab = shell_exec("sudo /var/sudowebscript.sh grreifetab"); #Reifetab.py
                        }
                        if($valrs == 0 and $valtab==0) {
                    ?>            
                    <button name="button" onclick="return confirm('Sind sie sicher dass sie RSS.py starten moechten?')" style="width: 110" id=0>Rss Start</button> <br>
                    <br>
                        <button name="button1" onclick="return confirm('Sind sie sicher dass sie Reifetab.py (und falls noch nicht gestartet, auch RSS.py) starten moechten?');"style="width: 110" id=1>Reifeprog. Start</button> <br>
                    <?php
                        }
                        if($valrs and $valtab==0) {
                    ?>
                    <button name="button3" onclick="return confirm('Sind sie sicher dass sie RSS.py (und falls gestartet, auch Reifetab.py) stoppen moechten?');"style="width: 110" id=3>Rss Stop</button> <br>        
                    <br>
                    <button name="button1" onclick="return confirm('Sind sie sicher dass sie Reifetab.py (und falls noch nicht gestartet, auch RSS.py) starten moechten?');"style="width: 110" id=1>Reifeprog. Start</button> <br>
                    <?php 
                        }
                        if($valrs and $valtab) {
                    ?>
                    <button name="button3" onclick="return confirm('Sind sie sicher dass sie RSS.py (und falls gestartet, auch Reifetab.py) stoppen moechten?');"style="width: 110" id=3>Rss Stop</button> <br>
                    <br>
                    <button name="button4" onclick="return confirm('Sind sie sicher dass sie Reifetab.py stoppen moechten?');"style="width: 110" id=4>Reifeprog. Stop</button> <br>            
                    <?php } ?>
                    </p>
                </form>
            </fieldset>
        </div>
    </body>
</html>
