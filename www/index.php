<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <head>
        <link href="style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <?php include 'links.php';?>
        <div id="section">    
            <div class="content">
                <table>
                    <tr>
                        <td ><a href="index.php"><b>Startseite</b></a>&nbsp;&nbsp;&nbsp;</td>
                        <td ><a href="set.php">Einstellungen</a>&nbsp;&nbsp;&nbsp;</td>
                        <td ><a href="diagram.php">Diagramme</a>&nbsp;&nbsp;&nbsp;</td>
                        <td ><a href="log.php">Loginfos</a>&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                </table>
                <div class="thermometers">
                    <div class="label">Temperatur (°C)</div><div class="label">Luftfeuchtigkeit (%)</div>
                    <div class="de">
                        <div class="den">
                            <div class="dene">
                                <div class="denem">
                                    <div class="deneme">
                                        <?php print tempParts($array['temperatur'],0); ?> <span>.<?php print tempParts($array['temperatur'],1); ?></span><strong>&deg;</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="de">
                        <div class="den">
                            <div class="dene">
                                <div class="denem">
                                    <div class="deneme">
                                        <?php print tempParts($array['luftfeuchtigkeit'],0); ?> <span>.<?php print tempParts($array['luftfeuchtigkeit'],1); ?></span><strong>&#37</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
                <h2>Temperaturverlauf</h2>
                <img src="/pic/rss_sensortemp-hourly.png" alt="Tagesverlauf Temperatur" />
                <br/><br/>
                <h2>Luftfeuchtigkeitsverlauf</h2>
                <img src="/pic/rss_sensorhum-hourly.png" alt="Tagesverlauf Luftfeuchtigkeit" />
            </div>        
        </div>
        <div id="footer"> by Tommy_J  </div>
    </body>
</html>
