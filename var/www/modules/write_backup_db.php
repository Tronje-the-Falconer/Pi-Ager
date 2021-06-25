<?php 

    if (isset ($_POST['save_backup_values'])){
        logger('DEBUG', 'button save backupvalues pressed');
        unset($_POST['save_backup_values']);
        $backup_nfsvol = $_POST['backup_nfsvol'];
        $backup_subdir = $_POST['backup_subdir'];
        $backup_nfsmount = $_POST['backup_nfsmount'];
        $backup_path = $_POST['backup_path'];
        $backup_number_of_backups = $_POST['backup_number_of_backups'];
        $backup_name = $_POST['backup_name'];
        $backup_nfsopt = $_POST['backup_nfsopt'];
        $backup_active = $_POST['backup_active'];

        write_backupvalues($backup_nfsvol, $backup_subdir, $backup_nfsmount, $backup_path, $backup_number_of_backups, $backup_name, $backup_nfsopt, $backup_active);
        logger('DEBUG', 'backupvalues saved');
        print '<script language="javascript"> alert("'. (_("backup values")) . " : " . (_("values saved")) .'"); </script>';
    }
?>
