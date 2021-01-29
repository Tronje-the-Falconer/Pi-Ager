<?php 
    if (isset ($_POST['save_messenger_values'])){
        logger('DEBUG', 'button save messenger pressed');
        unset($_POST['save_messenger_values']);
        
        $count_messenger_number_rows = $_POST['count_messenger_number_rows'];
        $index_row = 0;
        $index_row_id = 1;
        while ($index_row < $count_messenger_number_rows) {
            $messenger_id = $_POST['messenger_id_' . $index_row];
            $messenger_exception = $_POST['messenger_exception_' . $index_row_id ];
            $checked_messenger_e_mail_true = $_POST['checked_messenger_e_mail_true_' . $index_row_id];
            $checked_messenger_pushover_true = $_POST['checked_messenger_pushover_true_' . $index_row_id];
            $checked_messenger_telegram_true = $_POST['checked_messenger_telegram_true_' . $index_row_id];
            $messenger_alarm = $_POST['messenger_alarm_' . $index_row_id];
            $checked_messenger_raise_exeption_true = $_POST['checked_messenger_raise_exeption_true_' . $index_row_id];
            $checked_messenger_active_true = $_POST['checked_messenger_active_true_' . $index_row_id];
            
            write_messenger_values($messenger_id, $messenger_exception, $checked_messenger_e_mail_true, $checked_messenger_pushover_true, $checked_messenger_telegram_true, $messenger_alarm, $checked_messenger_raise_exeption_true, $checked_messenger_active_true );
            $index_row_id++;
            $index_row++;
        }

        logger('DEBUG', 'messenger values saved');
        print '<script language="javascript"> alert("'. (_("messenger values")) . " : " . (_("values saved")) .'"); </script>';
    }
    
    if (isset ($_POST['add_messenger'])){
        logger('DEBUG', 'button add messenger');
        unset($_POST['add_messenger']);
        
        $add_messenger_exception = $_POST['add_messenger_exception'];
        $add_checked_messenger_e_mail_true = $_POST['add_checked_messenger_e_mail_true'];
        $add_checked_messenger_pushover_true = $_POST['add_checked_messenger_pushover_true'];
        $add_checked_messenger_telegram_true = $_POST['add_checked_messenger_telegram_true'];
        $add_messenger_alarm = $_POST['add_messenger_alarm'];
        $add_checked_messenger_raise_exeption_true = $_POST['add_checked_messenger_raise_exeption_true'];
        $add_checked_messenger_active_true = $_POST['add_checked_messenger_active_true'];
        
        add_messenger($add_messenger_exception, $add_checked_messenger_e_mail_true, $add_checked_messenger_pushover_true, $add_checked_messenger_telegram_true, $add_messenger_alarm, $add_checked_messenger_raise_exeption_true, $add_checked_messenger_active_true );
    }
    
    if (isset ($_POST['save_event_values'])){
        logger('DEBUG', 'button save event pressed');
        unset($_POST['save_event_values']);
        
        $count_event_number_rows = $_POST['count_event_number_rows'];
        $index_row = 0;
        $index_row_id = 1;
        while ($index_row < $count_event_number_rows) {
            $event_id = $_POST['event_id_' . $index_row];
            $event_event = $_POST['event_event_' . $index_row_id ];
            $checked_event_e_mail_true = $_POST['checked_event_e_mail_true_' . $index_row_id];
            $checked_pushover_true = $_POST['checked_event_pushover_true_' . $index_row_id];
            $checked_telegram_true = $_POST['checked_event_telegram_true_' . $index_row_id];
            $event_alarm = $_POST['event_alarm_' . $index_row_id];
            $event_eventtext = $_POST['event_eventtext_' . $index_row_id];
            $checked_active_true = $_POST['checked_event_active_true_' . $index_row_id];
            
            write_event_values($event_id, $event_event, $checked_event_e_mail_true, $checked_pushover_true, $checked_telegram_true, $event_alarm, $event_eventtext, $checked_active_true );
            $index_row_id++;
            $index_row++;
        }

        logger('DEBUG', 'event values saved');
        print '<script language="javascript"> alert("'. (_("event values")) . " : " . (_("values saved")) .'"); </script>';
    }
    
    if (isset ($_POST['add_event'])){
        logger('DEBUG', 'button add event');
        unset($_POST['add_event']);
        
        $add_event_event = $_POST['add_event_event'];
        $add_checked_event_e_mail_true = $_POST['add_checked_event_e_mail_true'];
        $add_checked_event_pushover_true = $_POST['add_checked_event_pushover_true'];
        $add_checked_event_telegram_true = $_POST['add_checked_event_telegram_true'];
        $add_event_alarm = $_POST['add_event_alarm'];
        $add_event_eventtext = $_POST['add_event_eventtext'];
        $add_checked_event_active_true = $_POST['add_checked_event_active_true'];
        
        add_event($add_event_event, $add_checked_event_e_mail_true, $add_checked_event_pushover_true, $add_checked_event_telegram_true, $add_event_alarm, $add_event_eventtext, $add_checked_event_active_true );
    }
    
    if (isset ($_POST['save_alarm_values'])){
        logger('DEBUG', 'button save alarm pressed');
        unset($_POST['save_alarm_values']);
        
        $count_alarm_number_rows = $_POST['count_alarm_number_rows'];
        $index_row = 0;
        $index_row_id = 1;
        while ($index_row < $count_alarm_number_rows) {
            $alarm_id = $_POST['alarm_id_' . $index_row];
            $alarm_alarm = $_POST['alarm_alarm_' . $index_row_id ];
            $alarm_replication = $_POST['alarm_replication_' . $index_row_id];
            $alarm_sleep = $_POST['alarm_sleep_' . $index_row_id];
            $alarm_high_time = $_POST['alarm_high_time_' . $index_row_id];
            $alarm_low_time = $_POST['alarm_low_time_' . $index_row_id];
            $alarm_waveform = $_POST['alarm_waveform_' . $index_row_id];
            $alarm_frequency = $_POST['alarm_frequency_' . $index_row_id];
            write_alarm_values($alarm_id, $alarm_alarm, $alarm_replication, $alarm_sleep, $alarm_high_time, $alarm_low_time, $alarm_waveform, $alarm_frequency );
            $index_row_id++;
            $index_row++;
        }

        logger('DEBUG', 'alarm values saved');
        print '<script language="javascript"> alert("'. (_("alarm values")) . " : " . (_("values saved")) .'"); </script>';
    }
    
    if (isset ($_POST['delete_alarm'])){
        logger('DEBUG', 'button delete alarm pressed');
        unset($_POST['delete_alarm']);
        $id =  $_POST['id'];
        delete_row_from_table($alarm_table,$alarm_id_field,$id);
    }
    
    if (isset ($_POST['delete_messenger'])){
        logger('DEBUG', 'button delete messenger pressed');
        unset($_POST['delete_messenger']);
        $id =  $_POST['id'];
        delete_row_from_table($messenger_table,$messenger_id_field,$id);
    }
    
    if (isset ($_POST['delete_event'])){
        logger('DEBUG', 'button delete event pressed');
        unset($_POST['delete_event']);
        $id =  $_POST['event_id'];
        delete_row_from_table($messenger_event_table,$event_id_field,$id);
    }
    
    if (isset ($_POST['delete_e_mail_recipient'])){
        logger('DEBUG', 'button delete e-mail recipient pressed');
        unset($_POST['delete_e_mail_recipient']);
        $id =  $_POST['id'];
        delete_row_from_table($email_recipients_table,$e_mail_recipients_id_field,$id);
    }
    
    if (isset ($_POST['add_alarm'])){
        logger('DEBUG', 'button add alarm pressed');
        unset($_POST['add_alarm']);
        $alarm_alarm = $_POST['add_alarm_alarm'];
        $alarm_replication = $_POST['add_alarm_replication'];
        $alarm_sleep = $_POST['add_alarm_sleep'];
        $alarm_high_time = $_POST['add_alarm_high_time'];
        $alarm_low_time = $_POST['add_alarm_low_time'];
        $alarm_waveform = $_POST['add_alarm_waveform'];
        $alarm_frequency = $_POST['add_alarm_frequency'];
        if ($alarm_frequency == Null){
            $alarm_frequency = 0;
        }
        add_alarm($alarm_alarm, $alarm_replication, $alarm_sleep, $alarm_high_time, $alarm_low_time, $alarm_waveform, $alarm_frequency );
    }
    
    if (isset ($_POST['save_e_mail_recipient_values'])){
        logger('DEBUG', 'button save e-mail recipient pressed');
        unset($_POST['save_e_mail_recipient_values']);
        $count_e_mail_recipients_number_rows = $_POST['count_e_mail_recipients_number_rows'];
        $index_row = 0;
        $index_row_id = 1;
        while ($index_row < $count_e_mail_recipients_number_rows) {
            $e_mail_recipients_id = $_POST['e_mail_recipient_id_' . $index_row];
            $e_mail_recipients_to_mail = $_POST['e_mail_recipient_to_mail_' . $index_row_id ];
            $e_mail_recipients_active = $_POST['checked_e_mail_recipient_true_' . $index_row_id];
            write_mail_recipient_values($e_mail_recipients_id, $e_mail_recipients_to_mail, $e_mail_recipients_active);
            $index_row_id++;
            $index_row++;
        }

        logger('DEBUG', 'mail recipient values saved');
        print '<script language="javascript"> alert("'. (_("mail recipient values")) . " : " . (_("values saved")) .'"); </script>';
    }
    
    if (isset ($_POST['add_e_mail_recipient'])){
        logger('DEBUG', 'button add e-mail recipient pressed');
        unset($_POST['add_e_mail_recipient']);
        $add_e_mail_recipients_to_mail = $_POST['add_e_mail_recipient_to_mail'];
        $add_e_mail_recipients_active = $_POST['add_checked_e_mail_recipient_true'];
        add_mail_recipient($add_e_mail_recipients_to_mail, $add_e_mail_recipients_active);

        logger('DEBUG', 'mail recipient added');
        print '<script language="javascript"> alert("'. (_("ad mail recipient")) . " : " . (_("mail recipient added")) .'"); </script>';
    }
    
    if (isset ($_POST['save_mailserver_values'])){
        logger('DEBUG', 'button save mailserver pressed');
        unset($_POST['save_mailserver_values']);
        $mailserver_server = $_POST['mailserver_server'];
        $mailserver_user = $_POST['mailserver_user'];
        $mailserver_password = $_POST['mailserver_password'];
        $mailserver_starttls = $_POST['mailserver_starttls'];
        $mailserver_from_mail = $_POST['mailserver_from_mail'];
        $mailserver_port = $_POST['mailserver_port'];
        
        
        if ($mailserver_password != '123456789abcdefghi'){
            $mailserver_password_base64 = base64_decode($mailserver_password);
            shell_exec('sudo /var/sudowebscript.sh encrypt_password ' . $mailserver_password_base64 . ' "base64" > /dev/null 2>&1 &');
        }
        write_mailserver_values($mailserver_server, $mailserver_user, $mailserver_starttls, $mailserver_from_mail, $mailserver_port);
        logger('DEBUG', 'mailserver values saved');
        print '<script language="javascript"> alert("'. (_("mailserver values")) . " : " . (_("values saved")) .'"); </script>';
    }
    
    if (isset ($_POST['save_pushover_values'])){
        logger('DEBUG', 'button save pushover pressed');
        unset($_POST['save_pushover_values']);
        $pushover_user_key = $_POST['pushover_user_key'];
        $pushover_api_token = $_POST['pushover_api_token'];
        $pushover_active = $_POST['pushover_active'];
        write_pushover_values($pushover_user_key, $pushover_api_token, $pushover_active);
        logger('DEBUG', 'pushover values saved');
        print '<script language="javascript"> alert("'. (_("pushover values")) . " : " . (_("values saved")) .'"); </script>';
    }
    
    if (isset ($_POST['save_telegram_values'])){
        logger('DEBUG', 'button save telegram pressed');
        unset($_POST['save_telegram_values']);
        $telegram_bot_token = $_POST['telegram_bot_token'];
        $telegram_bot_chatid = $_POST['telegram_bot_chatid'];
        $telegram_active = $_POST['telegram_active'];
        write_telegram_values($telegram_bot_token, $telegram_bot_chatid, $telegram_active);
        logger('DEBUG', ' telegram values saved');
        print '<script language="javascript"> alert("'. (_("telegram values")) . " : " . (_("values saved")) .'"); </script>';
    }
?>
