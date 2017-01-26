<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="refresh" content="30" />
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
                        <td class="auto-style1"><a href="diagram.php">Diagramme</a>&nbsp;&nbsp;&nbsp;</td>
                        <td class="auto-style1"><a href="log.php"><b>Loginfos</b></a>&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <?php
                        $f = file("logfile.txt");
                        $f=array_reverse($f);
                        echo "<br />" ."<br />". 'Logeinträge' ."<br />";
                    ?>
                    <div class="log">
                        <?php
                            foreach($f as $file) {
                                echo "<br />". $file; 
                            }
                        ?>
                    </div>    
                </div>    
            </div>
        <div id="footer"> by Tommy_J  </div>
    </body>
</html>