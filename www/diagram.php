<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <head>
        <link href="style.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
            .auto-style1 {text-align: left;}
            .auto-style2 {text-decoration: underline;}
            .auto-style3 {text-align: center;}
        </style>
    </head>
    <body>
        <?php include 'links.php';?>
        <div id="section">
            <div class="content">
                <tr>
                    <td class="auto-style1"><a href="index.php">Startseite</a>&nbsp;&nbsp;&nbsp;</td>
                    <td class="auto-style1"><a href="set.php">Einstellungen</a>&nbsp;&nbsp;&nbsp;</td>
                    <td class="auto-style1"><a href="diagram.php"><b>Diagramme</b></a>&nbsp;&nbsp;&nbsp;</td>
                    <td class="auto-style1"><a href="log.php">Loginfos</a>&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <br/>
                <div id="Modus1" >
                    <form action="diagram.php" method="get" >
                        <?php
                            if ($_GET['mode']) {
                                $mode=$_GET['mode'];
                            }else{
                                $mode = "hourly";
                            }
                            if ($mode=="hourly"){
                                $optsel="Stündlich";
                                $mode1="daily";
                                $opt1="Täglich";
                                $mode2="monthly";
                                $opt2="Monatlich";
                            } elseif ($mode=="daily") {
                                $optsel="Täglich";
                                $mode1="monthly";
                                $opt1="Monatlich";
                                $mode2="hourly";
                                $opt2="Stündlich";
                            } elseif ($mode=="monthly") {
                                $optsel="Monatlich";
                                $mode1="daily";
                                $opt1="Täglich";
                                $mode2="hourly";
                                $opt2="Stündlich";
                            }
                        ?>
                        <fieldset name="Group1">
                            <legend class="auto-style1">Diagrammzeitraum</legend>
                            <select name="mode">
                                <option selected="<?= $mode ?>" value=><?= $optsel ?></option>
                                <option value=<?= $mode1 ?>><?= $opt1 ?></option>
                                <option value=<?= $mode2 ?>><?= $opt2 ?></option>
                            </select>
                            <input name="Submit1" type="submit" value="OK" />
                        </fieldset>
                    </form>    
                    <?php
                        if ($_GET['mode']) {
                            $mode=$_GET['mode'];
                        }else{
                            $mode = "hourly";
                        }
                    ?>
                    <div class="section">
                        <h2>Temperaturverlauf </h2>
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
                            <h2>Luftumwälzung</h2>
                        <img src="/pic/dht22_uml-<?= $mode ?>.png" alt="Wochenverlauf" />
                    </div>
                </div>
            </div>
        </div>
        <div id="footer"> by Tommy_J  </div>    
    </body>
</html>
