<?php
    #programme Rss.py und/oder Reifetab.py starten/stoppen
    if (isset($_POST['rss_start'])){
        $valrs = shell_exec("ps ax | grep -v grep | grep Rss.py");
        if($valrs == 0) {
            shell_exec('sudo python RSS/Rss.py > /dev/null 2>/dev/null &');
            $f=fopen("logfile.txt","w");
            fwrite($f, "\n".date("d.m.Y H:i")." Reifeschrank gestartet");
            fclose($f);
        }
    }
    if (isset($_POST['rss_reifetab_start'])){
        $valrs = shell_exec("ps ax | grep -v grep | grep Rss.py"); #Rss.py
        if($valrs == 0) {
            shell_exec('sudo python RSS/Rss.py > /dev/null 2>/dev/null &');
            $f=fopen("logfile.txt","w");
            fwrite($f, "\n".date("d.m.Y H:i")." Reifeschrank gestartet");
            fclose($f);
        }
        $valtab = shell_exec("ps ax | grep -v grep | grep Reifetab.py"); #Reifetab.py
        if($valtab == 0) {
            shell_exec('sudo python RSS/Reifetab.py > /dev/null 2>/dev/null &');
            $f=fopen("logfile.txt","a");
            fwrite($f, "\n".date("d.m.Y H:i")." Reifeprogramm gestartet");
            fclose($f);
        }
    }
    if (isset($_POST['rss_reifetab_stop'])){
        exec('sudo pkill -f Rss.py');
        exec('sudo pkill -f Reifetab.py');
        $val = trim(@shell_exec("/usr/local/bin/gpio -g write 22 1"));
        $val = trim(@shell_exec("/usr/local/bin/gpio -g write 27 1"));
        $val = trim(@shell_exec("/usr/local/bin/gpio -g write 24 1"));
        $val = trim(@shell_exec("/usr/local/bin/gpio -g write 18 1"));
        $val = trim(@shell_exec("/usr/local/bin/gpio -g write 23 1"));
        $f=fopen("logfile.txt","a");
        fwrite($f, "\n".date("d.m.Y H:i")." Reifeschrank gestoppt");
        fclose($f);
    }
    if (isset($_POST['reifetab_stop'])){
        exec('sudo pkill -f Reifetab.py');
        $f=fopen("logfile.txt","a");
        fwrite($f,"\n". date("d.m.Y H:i")." Reifeprogramm gestoppt");
        fclose($f);
     }
?>