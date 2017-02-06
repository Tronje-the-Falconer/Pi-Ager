<?PHP
    #System herunterfahren
    if (isset ($_POST['Shutdown'])){
        $date = date('d.m.Y H:i:s');
        shell_exec('sudo /var/sudowebscript.sh shutdown');
#3 Sekunden anzeigen, dass System heruntergefahren wird
        print '<p id="info-message" style="color: #ff0000; font-size: 20px;"><b>Das System wird heruntergefahren</b><br>' . $date . ' </p>
            <script language="javascript">
                setTimeout(function(){document.getElementById("info-message").style.display="none"}, 3000)
            </script>
            <meta http-equiv="refresh" content="3; URL=/index.php">';
    }
?>