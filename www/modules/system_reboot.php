<?PHP
    #System neustarten
    if (isset ($_POST['Reboot'])){
        $date = date("d.m.Y H:i:s");
        shell_exec('sudo /var/sudowebscript.sh reboot');

#3 Sekunden anzeigen, dass System neugestartet wird

        print "<p id=\"info-message\" style=\"color: #ff0000; font-size: 20px;\"><b>Das System wird neu gestratet</b><br>" . $date . " </p>
        <script language=\"javascript\">
        setTimeout(function(){document.getElementById('info-message').style.display='none'}, 3000)
        </script>
        <meta http-equiv='refresh' content='3; URL=/index.php'>";
    }
?>