<?php 
    #manuelles Backup
    if (isset ($_POST['manual_backup'])){
        logger('DEBUG', 'button manual backup pressed');
        unset($_POST['manual_backup']);
        shell_exec('sudo /var/sudowebscript.sh backup > /dev/null 2>&1 &');
    }
?>
