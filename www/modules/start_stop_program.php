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
                fwrite($f, "\n".date('d.m.Y H:i')." main.py "._('manually started'));
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
            fwrite($f, "\n".date('d.m.Y H:i')." main.py "._('no idea what is happening'));
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
                write_start_in_database($status_agingtable_key);
                sleep(2); //warten auf annahme der Startsequenz
                // prüfen ob main immer noch läuft und ob main im messloop
                // prüfen ob agingtable läuft
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." main.py "._('manually started'));
                fwrite($f, "\n".date('d.m.Y H:i')." Pi-Ager"._('started due to agingtable start'));
                fwrite($f, "\n".date('d.m.Y H:i')." "._('agingtable started'));
                fclose($f);
                $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable'); # Reifetab hat sich geaändert also neu setzen
            }
            else{
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." main.py "._('could not be started'));
                fclose($f);
            }
        }
        elseif($grepmain != 0) {
                // write_start_in_database($status_agingtable_key);
                sleep(5);
                $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
                //wenn agingtable läuft dann Log schreiben
                $f=fopen('logs/logfile.txt','w');
                fwrite($f, "\n".date('d.m.Y H:i')." main.py "._('is already running'));
                fwrite($f, "\n".date('d.m.Y H:i')." Pi-Ager "._('is already running or started due to agingtable start'));
                if ($grepagingtable != 0) {
                    fwrite($f, "\n".date('d.m.Y H:i')." "._('agingtable started'));
                }
                else {
                    fwrite($f, "\n".date('d.m.Y H:i')." "._('agingtable could not be started'));
                }
                fclose($f);
                // wenn agingtable nicht läuft dann Log das Fehler
        }
        else{
            $f=fopen('logs/logfile.txt','w');
            fwrite($f, "\n".date('d.m.Y H:i')." agingtable.py "._('no idea what is happening'));
            fclose($f);
        }
    }
    if (isset($_POST['pi-ager_agingtable_stop'])){ //Pi Ager wird gestoppt während agingtable noch läuft
        $grepagingtable = shell_exec('sudo /var/sudowebscript.sh grepagingtable');
        if ($grepagingtable !=0){
            write_stop_in_database($status_agingtable_key);
            $f=fopen('logs/logfile.txt','a');
            fwrite($f, "\n".date('d.m.Y H:i')." "._('agingtable stopped due to stopping') . " Pi-Ager");
            fclose($f);
        }
        write_stop_in_database($status_piager_key);
        sleep(1);
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

    }
    if (isset($_POST['agingtable_stop'])){
        write_stop_in_database($status_agingtable_key);
        $f=fopen('logs/logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." "._('agingtable stopped'));
        fclose($f);

    }
    # Scales
    if (isset($_POST['scale1_start']) OR isset($_POST['scale2_start']) OR isset($_POST['scale1_tara']) OR isset($_POST['scale2_tara'])){
        $grepscale = shell_exec('sudo /var/sudowebscript.sh grepscale');
        if (grepscale == 0){
            shell_exec('sudo /var/sudowebscript.sh startscale');
            sleep (1); # 1 Sec auf start der Py-Datei warten
            $grepscale = shell_exec('sudo /var/sudowebscript.sh grepscale');
            if ($grepscale != 0){
                $f=fopen('logs/logfile.txt','a');
                fwrite($f,"\n". date('d.m.Y H:i')." scale.py "._('manually started'));
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
                    write_start_in_database($status_scale1_tara_key);
                    $f=fopen('logs/logfile.txt','a');
                    fwrite($f,"\n". date('d.m.Y H:i')." "._('performing tara on scale') . " 1");
                    fclose($f);
                }
                if (isset($_POST['scale2_tara'])){
                    write_start_in_database($status_scale2_tara_key);
                    $f=fopen('logs/logfile.txt','a');
                    fwrite($f,"\n". date('d.m.Y H:i')." "._('performing tara on scale') . " 2");
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
            fwrite($f,"\n". date('d.m.Y H:i')." scale.py "._('is already running'));
            fclose($f);
            if (isset($_POST['scale1_start'])){
                write_start_in_database($status_scale1_key);
                $f=fopen('logs/logfile.txt','a');
                fwrite($f,"\n". date('d.m.Y H:i')." "._('measuring on scale started.'). " ". _('scale') ." 1");
                fclose($f);
            }
            if (isset($_POST['scale2_start'])){
                write_start_in_database($status_scale2_key);
                $f=fopen('logs/logfile.txt','a');
                fwrite($f,"\n". date('d.m.Y H:i')." "._('measuring on scale started.'). " ". _('scale') ." 2");
                fclose($f);
            }
            if (isset($_POST['scale1_tara'])){
                write_start_in_database($status_scale1_tara_key);
                $f=fopen('logs/logfile.txt','a');
                fwrite($f,"\n". date('d.m.Y H:i')." "._('performing tara on scale') ." 1");
                fclose($f);
            }
            if (isset($_POST['scale2_tara'])){
                write_start_in_database($status_scale2_tara_key);
                $f=fopen('logs/logfile.txt','a');
                fwrite($f,"\n". date('d.m.Y H:i')." "._('performing tara on scale') ." 2");
                fclose($f);
            }
        }
        else{
            $f=fopen('logs/logfile.txt','w');
            fwrite($f, "\n".date('d.m.Y H:i')." scale.py "._('no idea what is happening'));
            fclose($f);
        }
    }
    
    if (isset($_POST['scale1_stop'])){
        write_stop_in_database($status_scale1_key);
        $f=fopen('logs/logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." "._('measuring scale stopped'). " " . _('scale'). " 1");
        fclose($f);
    }
    if (isset($_POST['scale2_stop'])){
        write_stop_in_database($status_scale2_key);
        $f=fopen('logs/logfile.txt','a');
        fwrite($f,"\n". date('d.m.Y H:i')." "._('measuring scale stopped'). " " . _('scale'). " 2");
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