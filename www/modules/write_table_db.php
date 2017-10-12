<?php 
// include 'database.php';
    #Namen der Reifetabelle in config/tables.json speichern
    if (isset ($_POST['save_agingtable'])){
        $chosen_agingtable = $_POST['agingtable'];
        write_agingtable($chosen_agingtable);

        #3 Sekunden anzeigen, dass gespeichert wurde
        print '<p id="info-message" style="color: #ff0000; font-size: 20px;"><b>'. (_("the selection is saved")) .'</b></p>
            <script language="javascript">
                setTimeout(function(){document.getElementById("info-message").style.display="none"}, 3000)
            </script>';
    }
?>
