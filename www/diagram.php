
    <?php

#ini_set( 'display_errors', true );
#error_reporting( E_ALL );

$API = file_get_contents("current.json");
$array = json_decode($API, true);
$temp_float = $array['temperatur'];
$hum_float = $array['luftfeuchtigkeit'];

if($_GET['mod']==0) 
{
$mode = hourly;
}

if($_GET['mod']== hourly)
{
$mode = hourly;
}

if($_GET['mod']== daily)
{
$mode = daily;
}

if($_GET['mod']== monthly)
{
$mode = monthly;
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
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"-->
<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<head>
		<title>Reifeschranksteuerung</title>
		<link href="style.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
	<div id="header">
 	<h1> Reifeschranksteuerung</h1>
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
			<!--<html><a href="shutdown.php">Shutdown RasPi</a></html>
		        <html><a href="restart.php">RasPi neu starten</a></html> 			
<!--HM_end-->
		</tr>
	</table>
	
	
	
		<br/>
		<form action="diagram.php" method="get" >

		<fieldset name="Group1">
				<legend>Diagrammzeitraum</legend>
				<select name="mod">
				<option selected="" value="hourly">Stündlich</option>
				<option value="daily">Täglich</option>
				<option value="monthly">Monatlich</option>
		</select>
	<input name="Submit1" type="submit" value="OK" />
			</fieldset>
		
		</form>	
		
			
			
           			
	    <div class="section">
                            <h2>Temperaturverlauf</h2>
			    <img src="/pic/dht22_sensortemp-<?= $mode ?>.png" alt="Tagesverlauf" />
                            <h2>Luftfeuchtigkeitsverlauf</h2>
			    <img src="/pic/dht22_sensorhum-<?= $mode ?>.png" alt="Wochenverlauf" />
                            <h2>Kühlung</h2>
			    <img src="/pic/dht22_cool-<?= $mode ?>.png" alt="Wochenverlauf" />
			    			<h2>Heizung</h2>
			    <img src="/pic/dht22_heat-<?= $mode ?>.png" alt="Wochenverlauf" />
			    			<h2>Befeuchtung</h2>
			    <img src="/pic/dht22_lbf-<?= $mode ?>.png" alt="Wochenverlauf" />
			    			<h2>Luftaustausch</h2>
			    <img src="/pic/dht22_lat-<?= $mode ?>.png" alt="Wochenverlauf" />
			    			<h2>Luftumwelzung</h2>
			    <img src="/pic/dht22_uml-<?= $mode ?>.png" alt="Wochenverlauf" />
			    			
			</div>
		</div>
	</div>
	
	<div id="footer">
 	&nbsp;</div>

	</body>
</html>
