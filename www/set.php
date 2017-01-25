<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">-->
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<head>
        <link href="style.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
        .auto-style1 {text-align: left;}
        .auto-style2 {text-decoration: underline;}
        </style>
</head>
<body>
    <?php
        include 'links.php';
    ?>
    <div id="section">    
        <div class="content">
    
        <tr>
            <td class="auto-style1"><a href="index.php">Startseite</a>&nbsp;&nbsp;&nbsp;</td>
            <td class="auto-style1"><a href="set.php"><b>Einstellungen</b></a>&nbsp;&nbsp;&nbsp;</td>
            <td class="auto-style1"><a href="diagram.php">Diagramme</a>&nbsp;&nbsp;&nbsp;</td>
            <td class="auto-style1"><a href="log.php">Loginfos</a>&nbsp;&nbsp;&nbsp;</td>

        </tr>
        </table>


        <div id="formular">
        <div id="Modus" style="height: 281px">
        <form action="set.php" method="post" >
        
         <fieldset name="Group1">
        <legend class="auto-style1">Modus</legend>
        <p><select name="mod">
        <option value="1">Kühlen</option>
        <option value="2">Heizen</option>
        <option selected="" value="3">Automatik</option>
        <option value="4">Automatik mit Luftaustausch</option>
        </select></p>    
        </fieldset>

<br>                
        <fieldset name="Group1">
        <legend class="auto-style1">Sensortype</legend>
        <p><select name="sensortype">
        <option value="1">DHT11</option>
        <option value="2">DHT22</option>
        <option selected="" value="3">SHT75</option>
        </select></p>    
        </fieldset>

<br>                
        <fieldset name="Group2">
        <legend class="auto-style1">Temperatur und Luftfeuchtigkeit</legend>
        <p>Temperatur:  <input name="temp" size="1" type="text" min="-2" max="22" value=<?=$tempsoll_float?>>°C&nbsp;&nbsp;    
        Luftfeuchtigkeit:  <input name="hum" size="1" type="text" value=<?=$humsoll_float?>> %<br>
        Befeuchtungsverzögerung:<input name="humdelay" size="1" type="text" value="2"/> Minuten</p>
        </fieldset>
<br>                

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
                <p>Luftumwälzung alle<span lang="de-lu">
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
<br>
        <fieldset name="Group1">
                <legend>Hysterese</legend>
                <p>Temperatur</p>
                Einschaltwert<span lang="de-lu"><input name="temphyston" type="text" size="1" value=<?=$temphyston?> > </span>°C
                &nbsp;&nbsp;Auschaltwert<input name="temphystoff" type="text" size="1" value= <?=$temphystoff?>> °C
<br>

                <p>Luftfeuchtigkeit</p>
                 Einschaltwert<span lang="de-lu"> 
                <input name="humhyston" type="text" size="2" value=<?=$humhyston?>> </span>%
                &nbsp;&nbsp;Auschaltwert<input name="humhystoff" type="text" size="2" value=<?=$humhystoff?>>%
        </fieldset>
                <p><input type="submit" value="absenden" /></p>
        </form>

<br>            
        <fieldset name="Group1">
                <span class="auto-style2">Modus</span><br>
                hier wird die Hauptfunktionsweise des Reifeschranks festgelegt<br>
                
                Kühlen:  Es wird auf die eingestellte Temperatur gekühlt, die 
                Heizung wird nie angesteuert. Umluft, Abluft, Befeuchten sind 
                aktiv.<br>
                Heizen: Es wird auf die eingestellte Temperatur geheizt, die 
                Kühlung wird nie angesteuert. Umluft, Abluft, Befeuchten sind 
                aktiv.<br>
                Automatik: Der Reifeschrank kühlt oder heizt je nach 
                eingestelltem Wert, Umluft, Abluf, Befeuchtung sind aktiv.<br>
                Erweiterte Automatik: Wie Automatik, beim Überschreiten der Lft. 
                schaltet die Abluft ein bis Soll erreicht ist, die Befeuchtung 
                kann verzögert werden<br>
                <br>
                Befeuchtungsverzögerung: hier wird die Zeit eingestellt bis der 
                Befeuchter bei zu niedriger Lft. einschaltet, damit kann die 
                kurzeitig fallende Lft. beim kühlen ausgeblendet werden<br>
                <br>
                <span class="auto-style2">Hysterese</span><br>
                Hier werden die Einschaltpunkte für die Soll Temperatur sowie 
                Lft. eingestellt.<br>
                Einschaltwert ist der Wert der Differenz zum Sollwert ab dem der Befeuchter, Heizung oder 
                Kühlung einschaltet.<br>
                Auschaltwert ist der Wert der Differenz zum Sollwert (vor dem Erreichen dessen) ab dem 
                ausgeschaltet wird.<br>
                <br>
                <span class="auto-style2">Beispiel:</span><br class="auto-style2">
                Eingestellte Temperatur: 12Grad; Einschaltwert: 2Grad; 
                Auschaltwert 0.5 Grad<br>
                wenn 10 Grad unterschritten wird heizt der Schrank auf bis 11.5 
                Grad<br>
                Wenn der Auschaltwert negativ ist: -0.5 Grad würde erst bei 
                erreichen von 12.5 Grad ausgeschaltet.<br>
                <br>
                <u><strong>Aufgepasst!!!!!</strong><br></u>
                <span class="auto-style2"><strong>Die Ein und Auschaltwerte 
                müssen im Automatikmodus aussreichend Abstand haben um zu 
                vermeiden dass nach dem Heizen die Kühlung einschaltet und 
                umgekehrt.....</strong></span><strong><br class="auto-style2"></strong>
                <u><strong>Keine Kommas benutze !!!!!!!!!!! Immer mit PUNKT die 
                Dezimalstellen trenen</strong></u>                 
        </fieldset>
    </div>
    </div>

<div id="footer"> by Tommy_J  </div>

</body>
</html>
