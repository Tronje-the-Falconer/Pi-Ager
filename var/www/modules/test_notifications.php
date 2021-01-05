<?php 

    if (isset ($_POST['mailserver_test'])){
        logger('DEBUG', 'button mailserver test pressed');
        unset($_POST['mailserver_test']);
        shell_exec('sudo /var/sudowebscript.sh test_mailserver ' . $_POST['mailserver_testadress'] .' > /dev/null 2>&1 &');
    }
    if (isset ($_POST['pushover_test'])){
        logger('DEBUG', 'button pushover test pressed');
        unset($_POST['pushover_test']);
        shell_exec('sudo /var/sudowebscript.sh test_pushover > /dev/null 2>&1 &');
    }
    if (isset ($_POST['telegram_test'])){
        logger('DEBUG', 'button telegram test pressed');
        unset($_POST['telegram_test']);
        shell_exec('sudo /var/sudowebscript.sh test_telegram > /dev/null 2>&1 &');
    }
?>