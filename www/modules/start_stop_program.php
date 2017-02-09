<?php
    #programme Rss.py und/oder Reifetab.py starten/stoppen
    if (isset($_POST['rss_start'])){
        $valrs = shell_exec('sudo /var/sudowebscript.sh greprss');
        if($valrs == 0) {
            shell_exec('sudo /var/sudowebscript.sh startrss');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $valrs = shell_exec('sudo /var/sudowebscript.sh greprss');
            if($valrs != 0) {
                $f=fopen('logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." Reifeschrank gestartet");
                fclose($f);
            }
            else{
                $f=fopen('logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." Reifeschrank konnte nicht gestartet werden");
                fclose($f);
            }
        }
    }
    if (isset($_POST['rss_reifetab_start'])){
        $valrs = shell_exec('sudo /var/sudowebscript.sh greprss'); #Rss.py
        if($valrs == 0) {
            shell_exec('sudo /var/sudowebscript.sh startrss');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $valrs = shell_exec('sudo /var/sudowebscript.sh greprss') # RSS hat sich geändert daher neu setzen
            if($valrs != 0) {
                $f=fopen('logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." Reifeschrank gestartet");
                fclose($f);
                shell_exec('sudo /var/sudowebscript.sh startreifetab');
                sleep (1); # 1 Sec auf start der Py-Datei warten
                $f=fopen('logfile.txt','a');
                fwrite($f, "\n".date('d.m.Y H:i')." Reifeprogramm gestartet");
                fclose($f);
                $valtab = shell_exec('sudo /var/sudowebscript.sh grepreifetab'); # Reifetab hat sich geaändert also neu setzen
            }
            else{
                $f=fopen('logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." Reifeschrank konnte nicht gestartet werden");
                fclose($f);
            }
        }
        #ist aus settings schon gegeben
        #$valtab = shell_exec('sudo /var/sudowebscript.sh grepreifetab'); #Reifetab.py 
        // if($valtab == 0) {
            // shell_exec('sudo /var/sudowebscript.sh startreifetab');
            // $f=fopen('logfile.txt','a');
            // fwrite($f, "\n".date('d.m.Y H:i')." Reifeprogramm gestartet");
            // fclose($f);
        // }
        
    }
    if (isset($_POST['rss_reifetab_stop'])){
        shell_exec('sudo /var/sudowebscript.sh pkillrss');
        shell_exec('sudo /var/sudowebscript.sh pkillreifetab');
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write22'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write27'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write24'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write18'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write23'));
        $f=fopen('logfile.txt','a');
        fwrite($f, "\n".date('d.m.Y H:i')." Reifeschrank gestoppt");
        fclose($f);
    }
    if (isset($_POST['reifetab_stop'])){
        shell_exec('sudo /var/sudowebscript.sh pkillreifetab');
        $f=fopen('logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." Reifeprogramm gestoppt");
        fclose($f);
     }
?>