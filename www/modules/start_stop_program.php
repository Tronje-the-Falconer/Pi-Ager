<?php 
    #programme Rss.py und/oder Reifetab.py starten/stoppen
    if (isset($_POST['pi-ager_start'])){
        $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
        if($grepmain == 0) {
            shell_exec('sudo /var/sudowebscript.sh startmain');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
            if($grepmain != 0) {
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." "._('pi-ager started'));
                fclose($f);
            }
            else{
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." "._('pi-ager could not be started'));
                fclose($f);
            }
        }
    }
    if (isset($_POST['pi-ager_agingtable_start'])){
        $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain'); #Rss.py
        if($grepmain == 0) {
            shell_exec('sudo /var/sudowebscript.sh startmain');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain'); # RSS hat sich geändert daher neu setzen
            if($grepmain != 0) {
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." "._('pi-ager started'));
                fclose($f);
                shell_exec('sudo /var/sudowebscript.sh startagingtable');
                sleep (1); # 1 Sec auf start der Py-Datei warten
                $f=fopen('logs/logfile.txt','a');
                fwrite($f, "\n".date('d.m.Y H:i')." "._('agingtable started'));
                fclose($f);
                $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable'); # Reifetab hat sich geaändert also neu setzen
            }
            else{
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." "._('pi-ager could not be started'));
                fclose($f);
            }
        }
        elseif($grepmain != 0) {
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." "._('pi-ager started'));
                fclose($f);
                shell_exec('sudo /var/sudowebscript.sh startagingtable');
                sleep (1); # 1 Sec auf start der Py-Datei warten
                $f=fopen('logs/logfile.txt','a');
                fwrite($f, "\n".date('d.m.Y H:i')." "._('agingtable started'));
                fclose($f);
                $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
        }
    }
    if (isset($_POST['pi-ager_agingtable_stop'])){
        $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
        if ($grepagingtable !=0){
            shell_exec('sudo /var/sudowebscript.sh pkillagingtable');
            $f=fopen('logs/logfile.txt','a');
            fwrite($f, "\n".date('d.m.Y H:i')." "._('agingtable stopped'));
            fclose($f);
        }
        shell_exec('sudo /var/sudowebscript.sh pkillmain');
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_cooling_compressor'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_heater'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_humidifier'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_circulating_air'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_exhausting_air'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_uv'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_light'));
        $val = trim(@shell_exec('sudo /var/sudowebscript.sh write_gpio_dehumidifier'));
        $f=fopen('logs/logfile.txt','a');
        fwrite($f, "\n".date('d.m.Y H:i')." "._('pi-ager stopped'));
        fclose($f);
    }
    if (isset($_POST['agingtable_stop'])){
        shell_exec('sudo /var/sudowebscript.sh pkillagingtable');
        $f=fopen('logs/logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." "._('agingtable stopped'));
        fclose($f);
     }
     # Scales
     if (isset($_POST['scale1_start'])){
        shell_exec('sudo /var/sudowebscript.sh startscale1');
        $f=fopen('logs/logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." "._('measuring scale1 started'));
        fclose($f);
     }
     if (isset($_POST['scale2_start'])){
        shell_exec('sudo /var/sudowebscript.sh startscale2');
        $f=fopen('logs/logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." "._('measuring scale2 started'));
        fclose($f);
     }
     if (isset($_POST['scale1_stop'])){
        shell_exec('sudo /var/sudowebscript.sh pkillscale1');
        $f=fopen('logs/logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." "._('measuring scale1 stopped'));
        fclose($f);
     }
     if (isset($_POST['scale2_stop'])){
        shell_exec('sudo /var/sudowebscript.sh pkillscale2');
        $f=fopen('logs/logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." "._('measuring scale2 stopped'));
        fclose($f);
     }
?>