    <?php

#ini_set( 'display_errors', true );
#error_reporting( E_ALL );


if($_GET['temp']==0) 
{
// nothing to do
}
else
{
$timestamp = time();


$array = array( 'temp' => (float)$_GET['temp'],
				'mod' => (int)$_GET['mod'], 
				'hum' => (float)$_GET['hum'],
				'tempon' => (int)$_GET['tempon']*60,
				'tempoff' => (int)$_GET['tempoff']*3600-$_GET['tempon']*60,
				'tempon1' => (int)$_GET['tempon1']*60,
				'tempoff1' => (int)$_GET['tempoff1']*3600-$_GET['tempon1']*60,
				'temphyston' => (float)$_GET['temphyston'],
				'temphystoff' => (float)$_GET['temphystoff'],
				'humhyston' => (float)$_GET['humhyston'],
				'humhystoff' => (float)$_GET['humhystoff'],

				'humdelay' => (float)$_GET['humdelay'],
				'date' => $timestamp);
				
$jsoninput = json_encode($array);
file_put_contents('settings.json', $jsoninput);
echo "Werte wurden gespeichert";

}

if (isset($_POST['button']))
    {
         $valrs = shell_exec("ps ax | grep -v grep | grep Rss.py"); #Rss.py
         if($valrs == 0) {
		 	shell_exec('sudo python /home/pi/RSS1.0/Rss.py > /dev/null 2>/dev/null &'); }

    }

if (isset($_POST['button1']))
    {
         $valsa = shell_exec("ps ax | grep -v grep | grep salami.py"); #Rss.py
         if($valsa == 0) {
		 	shell_exec('sudo python /home/pi/RSS1.0/salami.py > /dev/null 2>/dev/null &'); }


    }
    
if (isset($_POST['button3']))
    {
         exec('sudo pkill -f Rss.py');
    }
    
if (isset($_POST['button4']))
    {
         exec('sudo pkill -f salami.py');
    }

# current.json auslesen um aktuelle temperatur anzuzeigen
$API = file_get_contents("current.json");
$array = json_decode($API, true);
$temp_float = $array['temperatur'];
$hum_float = $array['luftfeuchtigkeit'];

# settings.json auslesen um sollwerte wieder zu geben
$SET = file_get_contents("settings.json");
$array1 = json_decode($SET, true);
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
$tempoff = $tempoff/3600;
$tempon = $tempon/60;
$tempoff1 = $tempoff1/3600;
$tempon1 = $tempon1/60;
$tempoff = number_format($tempoff,2);
$tempoff1 = number_format($tempoff1,2);





#schaltzustände anzeigen wiringpi
$val22 = shell_exec("/usr/local/bin/gpio -g read 22"); #cool
$val27 = shell_exec("/usr/local/bin/gpio -g read 27"); #heat
$val18 = shell_exec("/usr/local/bin/gpio -g read 18"); #umluft
$val23 = shell_exec("/usr/local/bin/gpio -g read 23"); #luftaustausch
$val24 = shell_exec("/usr/local/bin/gpio -g read 24"); #luftbefeuchter

#Prüfen ob Programme laufen
$valsal = shell_exec("ps ax | grep -v grep | grep salami.py"); #salami
$valrss = shell_exec("ps ax | grep -v grep | grep Rss.py"); #Rss.py


if($val22 == 0) {
$cool = "LED_on.jpg"; }
if($val22 == 1) {
$cool = "LED_off.jpg"; }

if($val27 == 0) {
$heat = "LED_on.jpg"; }
if($val27 == 1) {
$heat = "LED_off.jpg"; }

if($val18 == 0) {
$uml = "LED_on.jpg"; }
if($val18 == 1) {
$uml = "LED_off.jpg"; }

if($val23 == 0) {
$lat = "LED_on.jpg"; }
if($val23 == 1) {
$lat = "LED_off.jpg"; }

if($val24 == 0) {
$lbf = "LED_on.jpg"; }
if($val24 == 1) {
$lbf = "LED_off.jpg"; }

if($valsal == 0) {
$salami = "LED_off.jpg"; }
else {
$salami = "LED_on.jpg"; }

if($valrss == 0) {
$rss = "LED_off.jpg"; }
else {
$rss = "LED_on.jpg"; }



?>
<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">-->
<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<head>
		<title>Reifeschranksteuerung</title>
		<link href="style.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
		.auto-style1 {
			text-align: left;
		}
		.auto-style2 {
	text-decoration: underline;
}
		.auto-style3 {
			text-align: center;
		}
		</style>
	</head>
	<body>
	<div id="header">
 	<h1>Reifeschranksteuerung</h1>
	</div>

	<div id="nav">
 		
 		
 		<fieldset name="Group1">
				<legend class="auto-style3">Schaltzustände</legend>
				
			
		Kühlung <img src="<?= $cool ?>" alt="Pin17" Style=width:15px;height:15px;><br>
		Heizung <img src="<?= $heat ?>" alt="Pin17" Style=width:15px;height:15px;><br>
		Luftbefeuchter <img src="<?= $lbf ?>" alt="Pin17" Style=width:15px;height:15px;><br>
		Umluft <img src="<?= $uml ?>" alt="Pin17" Style=width:15px;height:15px;><br>
		Luftaustausch <img src="<?= $lat ?>" alt="Pin17" Style=width:15px;height:15px;><br>
		Salami.py <img src="<?= $salami ?>" alt="Pin17" Style=width:15px;height:15px;><br>
		Rss.py <img src="<?= $rss ?>" alt="Pin17" Style=width:15px;height:15px;><br>
		
    <br>


         </fieldset> 
             <fieldset name="Group1">
				<legend class="auto-style3">Eingestellte Werte</legend>
				
			
		<u>Temperatur <?=$tempsoll_float?>C°</u><br>
		Ein <?=$temphyston?> Aus <?=$temphystoff?> <br>
				<span class="auto-style2">Luftf. <?=$humsoll_float?>%</span> <br>
		Ein <?=$humhyston?> Aus <?=$humhystoff?><br>
				<span class="auto-style2">Luftumwelzung </span> 
				<br class="auto-style2">
		Alle <?=$tempoff?> Std <br>für <?=$tempon?> Min<br>
				<span class="auto-style2">Luftaustausch</span><br class="auto-style2">
		Alle <?=$tempoff1?> Std <br>für <?=$tempon1?> Min<br>


         </fieldset>
            <fieldset name="Group1">
				<legend class="auto-style3">Programme starten</legend>
				
			<form method="post">
    <p>
        <button name="button" style="width: 110">Rss Start</button> <br>
        <button name="button3" style="width: 110">Rss Stop</button> <br>
        <br>
       
        <button name="button1" style="width: 110">Salami Start</button> <br>
		<button name="button4" style="width: 110">Salami Stop</button> <br>

    </p>
    </form>


         </fieldset>           		
		</div>
		
	<div id="section">
	
		<div class="content">
		<table style="width: 80%" align="center">
		<tr>
			<td class="auto-style1"><a href="index.php">Startseite</a>&nbsp;</td>
			<td class="auto-style1"><a href="set.php">Einstellungen</a>&nbsp;</td>
			<td class="auto-style1"><a href="diagram.php">Diagramme</a>&nbsp;</td>	
<!--HM_start-->
			<td class="auto-style1"><a href="restart.php">Neustart RasPi</a>&nbsp;</td>
			<td class="auto-style1"><a href="shutdown.php">Shutdown RasPi</a>&nbsp;</td>			
			
<!--HM_end-->
		</tr>
	</table>

	
<div id="formular">
		
 		<div id="Modus" style="height: 281px">
		<form action="set.php" method="get" >
		
 <fieldset name="Group1">
				<legend class="auto-style1">Modus</legend>
				<p><select name="mod">
				<option value="1">Kühlen</option>
				<option value="2">Heizen</option>
				<option selected="" value="3">Automatik</option>
				<option value="4">Automatik mit Luftaustausch</option>
			</select></p>	
			</fieldset>
				
<fieldset name="Group2">
				<legend class="auto-style1">Temperatur und Luftfeuchtigkeit</legend>
				<p>Temperatur:
				<input name="temp" size="3" type="text" min="-2" max="22" value=<?=$tempsoll_float?>>C° 
				Luftfeuchtigkeit:<input name="hum" size="3" type="text" value=<?=$humsoll_float?>> 
				%<br>
				Befeuchtungsverzögerung:<input name="humdelay" size="3" type="text" value="2"/> 
				Minuten

				</p>
			</fieldset>				

<fieldset name="Group1">
				<legend class="auto-style1">Lüfter</legend>
				<p>Luftaustausch alle<span lang="de-lu"> </span>
				<select name="tempoff1">
				<option value="0">aus</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option selected="" value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
			</select> Stunden für<span lang="de-lu">
				<select name="tempon1">
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="15">15</option>
				<option value="20">20</option>
				<option value="25">25</option>
				<option selected="" value="30">30</option>
				<option value="35">35</option>
				<option value="40">40</option>
				<option value="45">45</option>
				<option value="50">50</option>
				<option value="55">55</option>
				<option value="60">60</option>
			</select></span>&nbsp;Minuten </p>
				<p>Luftumwelzung alle<span lang="de-lu">
				<select name="tempoff">
				<option value="0">aus</option>
				<option value="1" selected="">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
			</select> </span>
				Stunden für<span lang="de-lu">
				<select name="tempon">
				<option value="5">5</option>
				<option value="10" selected="">10</option>
				<option value="15">15</option>
				<option value="20">20</option>
				<option value="25">25</option>
				<option value="30">30</option>
				<option value="35">35</option>
				<option value="40">40</option>
				<option value="45">45</option>
				<option value="50">50</option>
				<option value="55">55</option>
				<option value="60">60</option>
			</select></span>&nbsp;Minuten 
				</p>	
			</fieldset>
		<fieldset name="Group1">
				<legend>Hysterese</legend>
				<p>Temperatur</p>
				 Einschaltwert<span lang="de-lu"><input name="temphyston" type="text" size="2" value=<?=$temphyston?> > </span>
				Auschaltwert<input name="temphystoff" type="text" size="2" value= <?=$temphystoff?>>
				<br />
				<br />
				<p>Luftfeuchtigkeit</p>
				 Einschaltwert<span lang="de-lu"> 
				<input name="humhyston" type="text" size="2" value=<?=$humhyston?>> </span>

				Auschaltwert<input name="humhystoff" type="text" size="2" value=<?=$humhystoff?>>
				</fieldset>




 
		<p>             
		<input type="submit" value="---ABSENDEN---" />
		</p>
 			
				<fieldset name="Group1">
				<span class="auto-style2">Modus</span><br>
				hier wird die Hauptfunktionsweise des Reifeschranks festgelegt<br>
				
				Kühlen: Es wird auf die eingestellte Temperatur gekühlt, die 
				Heizung wird nie angesteuert. Umluft, Abluft, Befeuchten sind 
				aktiv.<br>
				Heizen: Es wird auf die eingestellte Temperatur geheizt, die 
				Kühlung wird nie angesteuert. Umluft, Abluft, Befeuchten sind 
				aktiv.<br>
				Automatik: Der Reifeschrank kühlt oder heizt je nach 
				eingestelltem Wert, Umluft , Abluf, Befeuchtung sind aktiv.<br>
				Erweiterte Automatik: Wie Automatik, beim Ã¼berschreiten der Lft 
				schaltet die Abluft ein bis Soll erreischt ist, die Befeuchtung 
				kann verzögert werden<br>
				<br>
				Befeuchtungsverzögerung: hier wird die Zeit eingestellt bis der 
				Befeuchter bei zu niedriger Lft einschaltet, damit kann die 
				kurzeitig fallende Lft beim kühlen ausgeblendet werden<br>
				<br>
				<span class="auto-style2">Hysterese</span><br>
				Hier werden die Einschaltpunkte für die Soll Temperatur sowie 
				Lft eingestellt.<br>
				Einschaltwert ist der Wert ab dem der Befeuchter, Heizung oder 
				Kühlung einschaltet.<br>
				Auschaltwert ist der Wert wo wieder vor oder nach dem Sollwert 
				ausgeschaltet wird.<br>
				<br>
				<span class="auto-style2">Beispiel:</span><br class="auto-style2">
				Eingestellte Temperatur: 12Graad; Einschaltwert: 2Graad; 
				Auschaltwert 0.5 Graad<br>
				wenn 10 Graad unterschritten wird heizt der Schrank auf bis 11.5 
				Graad<br>
				Wenn der Auschaltwer negativ ist: -0.5 Graad würde erst bei 
				erreichen von 12.5 Graad ausgeschaltet.<br>
				<br>
				<u><strong>Aufgepasst!!!!!</strong><br>
</u>				<span class="auto-style2"><strong>Die Ein und Auschaltwerte 
				mÃ¼ssen im Automatikmodus aussreichent Abstand haben um zu 
				vermeiden dass nach dem Heizen; die Kühlung einschaltet und 
				umgekehrt.....</strong></span><strong><br class="auto-style2">
				</strong>
				<u><strong>Keine Kommas benutze !!!!!!!!!!! Immer mit PUNKT die 
				Dezimalstellen trenen
</strong></u>				 
				</fieldset>
&nbsp;</form>

	</div>
</div>

	<div id="footer">
 		by Tommy_J </div>

	</body>
</html>
