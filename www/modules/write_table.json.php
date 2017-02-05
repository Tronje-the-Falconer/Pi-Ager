<?PHP
    #Namen der Reifetabelle in tables.json speichern
    if (isset ($_POST['Reifetab'])){
        $timestamp = time();
        $array = array( 'Reifetab' => $_POST['Reifetab'],'date' => $timestamp);
        $jsoninput = json_encode($array);
        file_put_contents('tabels.json', $jsoninput);

        #3 Sekunden anzeigen, dass gespeichert wurde
        print "<p id=\"info-message\" style=\"color: #ff0000; font-size: 20px;\"><b>Auswahl gespeichert</b></p>
        <script language=\"javascript\">
        setTimeout(function(){document.getElementById('info-message').style.display='none'}, 3000)
        </script>";
    }
?>