<?php 
    #programme Rss.py und/oder Reifetab.py starten/stoppen
    if (isset($_POST['rss_start'])){
        $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
        if($grepmain == 0) {
            shell_exec('sudo /var/sudowebscript.sh startrss');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
            if($grepmain != 0) {
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
        $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain'); #Rss.py
        if($grepmain == 0) {
            shell_exec('sudo /var/sudowebscript.sh startrss');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain'); # RSS hat sich geändert daher neu setzen
            if($grepmain != 0) {
                $f=fopen('logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." Reifeschrank gestartet");
                fclose($f);
                shell_exec('sudo /var/sudowebscript.sh startreifetab');
                sleep (1); # 1 Sec auf start der Py-Datei warten
                $f=fopen('logfile.txt','a');
                fwrite($f, "\n".date('d.m.Y H:i')." Reifeprogramm gestartet");
                fclose($f);
                $grephangingtable = shell_exec('sudo /var/sudowebscript.sh grephangingtable'); # Reifetab hat sich geaändert also neu setzen
            }
            else{
                $f=fopen('logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." Reifeschrank konnte nicht gestartet werden");
                fclose($f);
            }
        }
        elseif($grepmain != 0) {
                $f=fopen('logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." Reifeschrank gestartet");
                fclose($f);
                shell_exec('sudo /var/sudowebscript.sh startreifetab');
                sleep (1); # 1 Sec auf start der Py-Datei warten
                $f=fopen('logfile.txt','a');
                fwrite($f, "\n".date('d.m.Y H:i')." Reifeprogramm gestartet");
                fclose($f);
                $grephangingtable = shell_exec('sudo /var/sudowebscript.sh grephangingtable');
        }
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