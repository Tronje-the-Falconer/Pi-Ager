<?php 
    #neue Datenbank erstellen
    if (isset ($_POST['create_new_database'])){
        logger('DEBUG', 'button create_new_database pressed');
        $date = date('d.m.Y H:i:s');
        rename_database();
        create_new_database();
        logger('DEBUG', 'new database created');
        print '<script language="javascript"> alert("'. (_("new database created")) . '"); </script>';
    }
?>