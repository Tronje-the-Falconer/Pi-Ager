<?php 
    #neue Datenbank erstellen
    if (isset ($_POST['create_new_database'])){
        $date = date('d.m.Y H:i:s');
        rename_database();
        create_new_database();
        print '<script language="javascript"> alert("'. (_("new database created")) . '"); </script>';
    }
?>