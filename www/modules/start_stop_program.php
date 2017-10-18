<?php 

    include 'names.php';
// include 'database.php';

    #programme Rss.py und/oder Reifetab.py starten/stoppen
    if (isset($_POST['main_start'])){
        $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
        if($grepmain == 0) {
            shell_exec('sudo /var/sudowebscript.sh startmain');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain');
            
            if($grepmain != 0) {
                write_start_in_database($status_piager_key);
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." main.py "._('manualy started'));
                fwrite($f, "\n".date('d.m.Y H:i')." Pi-Ager "._('started'));
                fclose($f);
            }
            else{
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." main.py "._('could not be started'));
                fclose($f);
            }
        }
        elseif($grepmain != 0){
            write_start_in_database($status_piager_key);
            $f=fopen('logs/logfile.txt','w');
            fwrite($f, "\n".date('d.m.Y H:i')." main.py "._('is running'));
            fwrite($f, "\n".date('d.m.Y H:i')." Pi-Ager "._('started'));
            fclose($f);
        }
        else{
            $f=fopen('logs/logfile.txt','w');
            fwrite($f, "\n".date('d.m.Y H:i')." main.py "._('could not be found in process list'));
            fclose($f);
        }
    }
    if (isset($_POST['pi-ager_agingtable_start'])){
        $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain'); #Rss.py
        if($grepmain == 0) {
            shell_exec('sudo /var/sudowebscript.sh startmain');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $grepmain = shell_exec('sudo /var/sudowebscript.sh grepmain'); # RSS hat sich geändert daher neu setzen
            if($grepmain != 0) {                
                write_start_in_database($status_piager_key);
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." main.py "._('manualy started'));
                fwrite($f, "\n".date('d.m.Y H:i')." Pi-Ager"._('started due to agingtable'));
                fclose($f);
                
                shell_exec('sudo /var/sudowebscript.sh startagingtable');
                sleep (1); # 1 Sec auf start der Py-Datei warten
                $f=fopen('logs/logfile.txt','a');
                fwrite($f, "\n".date('d.m.Y H:i')." "._('agingtable started'));
                fclose($f);
                write_start_in_database($status_agingtable_key);
                $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable'); # Reifetab hat sich geaändert also neu setzen
            }
            else{
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." main.py "._('could not be started'));
                fclose($f);
            }
        }
        elseif($grepmain != 0 and $status_piager == 1) {
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." main.py "._('is running'));
                fwrite($f, "\n".date('d.m.Y H:i')." Pi-Ager "._('is running'));
                fclose($f);
                shell_exec('sudo /var/sudowebscript.sh startagingtable');
                sleep (1); # 1 Sec auf start der Py-Datei warten
                $f=fopen('logs/logfile.txt','a');
                fwrite($f, "\n".date('d.m.Y H:i')." "._('agingtable started'));
                fclose($f);
                write_start_in_database($status_agingtable_key);
                $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
        }
    }
    if (isset($_POST['pi-ager_agingtable_stop'])){ //Pi Ager wird gestoppt während agingtable noch läuft
        $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
        if ($grepagingtable !=0){
            shell_exec('sudo /var/sudowebscript.sh pkillagingtable');
            $f=fopen('logs/logfile.txt','a');
            fwrite($f, "\n".date('d.m.Y H:i')." "._('agingtable stopped due to stopping') . " Pi-Ager");
            fclose($f);
            write_stop_in_database($status_agingtable_key);
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
        fwrite($f, "\n".date('d.m.Y H:i')." Pi-Ager "._('stopped'));
        fclose($f);
        write_stop_in_database($status_piager_key);
    }
    if (isset($_POST['agingtable_stop'])){
        shell_exec('sudo /var/sudowebscript.sh pkillagingtable');
        $f=fopen('logs/logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." "._('agingtable stopped'));
        fclose($f);
        write_stop_in_database($status_agingtable_key);
    }
    # Scales
    if (isset($_POST['scale1_start']) OR isset($_POST['scale2_start']) OR isset($_POST['scale1_tara']) OR isset($_POST['scale2_tara'])){
        $grepscale = shell_exec('sudo /var/sudowebscript.sh startscale');
        if (grepscale == 0){
            shell_exec('sudo /var/sudowebscript.sh startscale');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $grepscale = shell_exec('sudo /var/sudowebscript.sh startscale');
            
            if ($grepscale != 0){
                $f=fopen('logs/logfile.txt','a');
                fwrite($f,"\n". date('d.m.Y H:i')." scale.py "._('manualy started'));
                fclose($f);
                if (isset($_POST['scale1_start'])){
                    #shell_exec('sudo /var/sudowebscript.sh startscale1');
                    write_start_in_database($status_scale1_key);
                    $f=fopen('logs/logfile.txt','a');
                    fwrite($f,"\n". date('d.m.Y H:i')." "._('measuring scale'). " 1 "._('started'));
                    fclose($f);
                }
                if (isset($_POST['scale2_start'])){
                    #shell_exec('sudo /var/sudowebscript.sh startscale2');
                    write_start_in_database($status_scale2_key);
                    $f=fopen('logs/logfile.txt','a');
                    fwrite($f,"\n". date('d.m.Y H:i')." ". _('measuring scale'). " 2 "._('started'));
                    fclose($f);
                }
                if (isset($_POST['scale1_tara'])){
                    $f=fopen('logs/logfile.txt','a');
                    fwrite($f,"\n". date('d.m.Y H:i')." "._('no tara possible because no measuring on scale') . " 1");
                    fclose($f);
                if (isset($_POST['scale2_tara'])){
                    $f=fopen('logs/logfile.txt','a');
                    fwrite($f,"\n". date('d.m.Y H:i')." "._('no tara possible because no measuring on scale') . " 2");
                    fclose($f);
                }
            }
            else{
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." scale.py "._('could not be started'));
                fclose($f);
            }
        }
        elseif (grepscale != 0){
            $f=fopen('logs/logfile.txt','a');
            fwrite($f,"\n". date('d.m.Y H:i')." scale.py "._('is running'));
            fclose($f);
            if (isset($_POST['scale1_start'])){
                #shell_exec('sudo /var/sudowebscript.sh startscale1');
                write_start_in_database($status_scale1_key);
                $f=fopen('logs/logfile.txt','a');
                fwrite($f,"\n". date('d.m.Y H:i')." "._('measuring on scale started.'). " ". _('scale') ." 1");
                fclose($f);
            }
            if (isset($_POST['scale2_start'])){
                #shell_exec('sudo /var/sudowebscript.sh startscale2');
                write_start_in_database($status_scale2_key);
                $f=fopen('logs/logfile.txt','a');
                fwrite($f,"\n". date('d.m.Y H:i')." "._('measuring on scale started.'). " ". _('scale') ." 2");
                fclose($f);
            }
            if (isset($_POST['scale1_tara']) AND $status_scale1 == 1){
                write_start_in_database($status_scale1_tara_key);
                $f=fopen('logs/logfile.txt','a');
                fwrite($f,"\n". date('d.m.Y H:i')." "._('tara scale') ." 1");
                fclose($f);
            }
            else{
                $f=fopen('logs/logfile.txt','a');
                fwrite($f,"\n". date('d.m.Y H:i')." "._('no tara on scale possible because no measuring'). " ". _('scale') ." 1";
                fclose($f);
            }
            if (isset($_POST['scale2_tara']) AND $status_scale2 == 1){
                write_start_in_database($status_scale2_tara_key);
                $f=fopen('logs/logfile.txt','a');
                fwrite($f,"\n". date('d.m.Y H:i')." "._('tara scale') ." 2");
                fclose($f);
            }
            else{
                $f=fopen('logs/logfile.txt','a');
                fwrite($f,"\n". date('d.m.Y H:i')." "._('no tara on scale possible because no measuring'). " ". _('scale') ." 2";
                fclose($f);
            }
        }
        else{
            $f=fopen('logs/logfile.txt','w');
            fwrite($f, "\n".date('d.m.Y H:i')." scale.py "._('could not be found in process list'));
            fclose($f);
        }
    }
    
    if (isset($_POST['scale1_stop'])){
        #shell_exec('sudo /var/sudowebscript.sh pkillscale1');
        write_stop_in_database($status_scale1_key);
        $f=fopen('logs/logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." "._('measuring scalen stopped'). " " . _('scale'). " 1");
        fclose($f);
    }
    if (isset($_POST['scale2_stop'])){
        #shell_exec('sudo /var/sudowebscript.sh pkillscale2');
        write_stop_in_database($status_scale2_key);
        $f=fopen('logs/logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." "._('measuring scalen stopped'). " " . _('scale'). " 2");
        fclose($f);
    }
 
    if (isset($_POST['webcam_start'])){
        $grepwebcam = shell_exec('sudo /var/sudowebscript.sh grepwebcam');
        if($grepwebcam == 0) {
            shell_exec('sudo /var/sudowebscript.sh startwebcam');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $grepwebcam = shell_exec('sudo /var/sudowebscript.sh grepwebcam');
            if($grepwebcam != 0) {
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." "._('webcam started'));
                fclose($f);
            }
            else{
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." "._('webcam could not be started'));
                fclose($f);
            }
        }
    }
    if (isset($_POST['webcam_stop'])){
        shell_exec('sudo /var/sudowebscript.sh pkillwebcam');
        $f=fopen('logs/logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." "._('webcam stopped'));
        fclose($f);
     }
?>