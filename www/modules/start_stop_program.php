<?php 
    #programme Rss.py und/oder Reifetab.py starten/stoppen
    if (isset($_POST['pi-ager_start'])){
        $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
        if($grepmain == 0) {
            shell_exec('sudo /var/sudowebscript.sh startmain');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
            if($grepmain != 0) {
                $f=fopen('logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." ".echo _('pi-ager started'));
                fclose($f);
            }
            else{
                $f=fopen('logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." ".echo _('pi-ager could not be started'));
                fclose($f);
            }
        }
    }
    if (isset($_POST['pi-ager_hangingtable_start'])){
        $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain'); #Rss.py
        if($grepmain == 0) {
            shell_exec('sudo /var/sudowebscript.sh startmain');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain'); # RSS hat sich geändert daher neu setzen
            if($grepmain != 0) {
                $f=fopen('logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." ".echo _('pi-ager started'));
                fclose($f);
                shell_exec('sudo /var/sudowebscript.sh startautomatic');
                sleep (1); # 1 Sec auf start der Py-Datei warten
                $f=fopen('logfile.txt','a');
                fwrite($f, "\n".date('d.m.Y H:i')." ".echo _('hangingtable started'));
                fclose($f);
                $grephangingtable = shell_exec('sudo /var/sudowebscript.sh grephangingtable'); # Reifetab hat sich geaändert also neu setzen
            }
            else{
                $f=fopen('logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." ".echo _('pi-ager could not be started'));
                fclose($f);
            }
        }
        elseif($grepmain != 0) {
                $f=fopen('logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." ".echo _('pi-ager started'));
                fclose($f);
                shell_exec('sudo /var/sudowebscript.sh startautomatic');
                sleep (1); # 1 Sec auf start der Py-Datei warten
                $f=fopen('logfile.txt','a');
                fwrite($f, "\n".date('d.m.Y H:i')." ".echo _('hangingtable started'));
                fclose($f);
                $grephangingtable = shell_exec('sudo /var/sudowebscript.sh grepautomatic');
        }
    }
    if (isset($_POST['pi-ager_hangingtable_stop'])){
        shell_exec('sudo /var/sudowebscript.sh pkillmain');
        shell_exec('sudo /var/sudowebscript.sh pkillautomatic');
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write22'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write27'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write24'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write18'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write23'));
        $f=fopen('logfile.txt','a');
        fwrite($f, "\n".date('d.m.Y H:i')." ".echo _('hangingtable stopped'));
        fclose($f);
    }
    if (isset($_POST['hangingtable_stop'])){
        shell_exec('sudo /var/sudowebscript.sh pkillautomatic');
        $f=fopen('logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." ".echo _('hangingtable stopped'));
        fclose($f);
     }
?>