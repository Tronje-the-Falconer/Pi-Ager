<?php

    session_start();
    # Entwicklermodus
    ini_set( 'display_errors', true );
    error_reporting( E_ALL );

    $monitor_active = '$monitor_active ist leer';
    $diagrams_active = '';
    $settings_active = '';
    $logs_active = '';
    $changelog_active = '';
    $webcam_active = '';
    $scale_wizzard_active = '';
    $admin_active = '';
    $notification_active = '';

    if ($_SERVER['PHP_SELF'] == '/index.php') {
        $monitor_active = 'active';
    }
    elseif ($_SERVER['PHP_SELF'] == '/diagrams.php') {
        $diagrams_active = 'active';
    }
    elseif ($_SERVER['PHP_SELF'] == '/settings.php') {
        $settings_active = 'active';
    }
    elseif ($_SERVER['PHP_SELF'] == '/logs.php') {
        $logs_active = 'active';
    }
    elseif ($_SERVER['PHP_SELF'] == '/changelog.php') {
        $changelog_active = 'active';
    }
    elseif ($_SERVER['PHP_SELF'] == '/webcam.php') {
        $webcam_active = 'active';
    }
    elseif ($_SERVER['PHP_SELF'] == '/scale_wizzard.php' OR $_SERVER['PHP_SELF'] == '/calibrate_scale.php' OR $_SERVER['PHP_SELF'] == '/modules/tara_scale.php') {
        $scale_wizzard_active = 'active';
    }
    elseif ($_SERVER['PHP_SELF'] == '/admin.php') {
        $admin_active = 'active';
    }
    elseif ($_SERVER['PHP_SELF'] == '/edit_agingtable.php') {
        $edit_agingtable_active = 'active';
    }
    elseif ($_SERVER['PHP_SELF'] == '/notification.php') {
        $notification_active = 'active';
    }
    
    # Language festlegen
    
    #### BEGIN Language from DB
    include 'modules/read_language.php';
    $language = get_language();
    if ($language == 1) {
        $language = 'de_DE.utf8';
    }
    elseif ($language == 2) {
        $language = 'en_GB.utf8';
    }
    #### END Language from DB
    
    #$language = 'de_DE.utf8';
    putenv("LANG=$language");
    setlocale(LC_ALL, $language);
   

    # Set the text domain as 'messages'
    $domain = 'pi-ager';
    bindtextdomain($domain, "/var/www/locale"); 
    textdomain($domain);
?>
<!DOCTYPE html>
<html>
    <meta http-equiv="content-type" content="text/html;  charset=utf-8">
    <meta http-equiv=“cache-control“ content=“no-cache, no-store, must-revalidate“ />
    <meta http-equiv=“pragma“ content=“no-cache“ />
    <meta http-equiv=“expires“ content=“0″ />
    <?php
#        if ($_SERVER['PHP_SELF'] == '/webcam.php') {
#            echo "<meta http-equiv=\"refresh\" content=\"5\" />";
#        }
#        elseif ($_SERVER['PHP_SELF'] == '/index.php') {
#            echo "<meta http-equiv=\"refresh\" content=\"60\" />";
#        }
#        elseif ($_SERVER['PHP_SELF'] == '/diagrams.php') {
#            echo "<meta http-equiv=\"refresh\" content=\"300\" />";
#        }
    ?>
    <head>
        <title>Pi-Ager</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
        <link href="css/style_pi_ager.css" rel="stylesheet" type="text/css" />
        <!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <link rel="stylesheet" href="css/style.css" media="screen">
        <!--[if lte IE 7]><link rel="stylesheet" href="css/style.ie7.css" media="screen" /><![endif]-->
        <link rel="stylesheet" href="css/style.responsive.css" media="all">
        <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

<!-- <script src="./node_modules/chart.js/dist/Chart.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script> -->
        <script src="js/jquery.js"></script>
        <script src="js/script.js"></script>
        <script src="js/script.responsive.js"></script>


       <?php

            if ($monitor_active == 'active'){
                echo "<script src='js/moment.min.js'></script>";
                echo "<script src='js/Chart.js'></script>";
            }
            if ($diagrams_active == 'active'){
                echo "<script src='js/moment.min.js'></script>";
                echo "<script src='js/Chart.js'></script>";
            }
#            if ($logs_active == 'active'){
#                echo '<meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->';
#                echo '<meta http-equiv="expires" content="0"> <!-- says that the cache expires now -->';
#                echo '<meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->';
#            }
            
        ?>

    </head>
    <body onclick >  <!--  this hack is for tooltip not hiding when tipped outside the input element on iPhone and iPad and other smartphones -->
        <div id="art-main">
            <header class="art-header">
                <div class="art-shapes">
                    <div class="art-logo"></div>
                </div>
                <h1 class="art-headline">Pi-Ager</h1>
                <h2 class="art-slogan"><?php echo _('by') . ' ' . ('Grillsportverein'); ?></h2>
                <h2 id="server_date_time_id" class="date-header"><?php echo exec('date +"%Y-%m-%d %H:%M"');?></h2>
                <h2 id="server_ip_id" class="ip-header"><?php
                    $local_ip = $_SERVER['REMOTE_ADDR'];
                    if (strpos($local_ip, '10.0.0') !== false) {
                        echo 'AP Mode';
                    }
                    else {
                        echo 'Client Mode';
                    }?></h2>
                <nav class="art-nav">
                    <div class="art-nav-inner">
                        <ul class="art-hmenu">
                            <li><a <?php echo 'href="index.php?rand=' . rand() . '"';?> class="<?php echo $monitor_active; ?>"><?php echo _('monitor'); ?></a></li>
                            <li><a <?php echo 'href="diagrams.php?rand=' . rand() . '"';?> class="<?php echo $diagrams_active; ?>"><?php echo _('diagrams'); ?></a></li>
                            <li><a <?php echo 'href="settings.php?rand=' . rand() . '"';?> class="<?php echo $settings_active; ?>"><?php echo _('settings'); ?></a></li>
                            <li><a <?php echo 'href="admin.php?rand=' . rand() . '"';?> class="<?php echo $admin_active; ?>"><?php echo _('admin'); ?></a></li>
                            <?php
                            //    if ($_SERVER['PHP_SELF'] == '/settings.php' OR $_SERVER['PHP_SELF'] == '/admin.php') {
                            //        echo '<li><a href="admin.php" class="';
                            //        echo $admin_active;
                            //        echo '">';
                            //        echo _('administration');
                            //        echo '</a></li>';
                            //    }
                            ?>
                            <li><a <?php echo 'href="notification.php?rand=' .rand() . '"';?> class="<?php echo $notification_active; ?>"><?php echo _('notification'); ?></a></li>
                            <li><a <?php echo 'href="logs.php?rand=' . rand() . '"';?> class="<?php echo $logs_active; ?>"><?php echo _('logs'); ?></a></li>
                            <li><a <?php echo 'href="webcam.php?rand=' . rand() . '"';?> class="<?php echo $webcam_active; ?>"><?php echo _('webcam'); ?></a></li>
                            <?php 
                                if ($_SERVER['PHP_SELF'] == '/changelog.php') {
                                    echo '<li><a href="changelog.php?rand=' . rand() . '" class="';
                                    echo $changelog_active;
                                    echo '">';
                                    echo _('changelog');
                                    echo '</a></li>';
                                }
                                if ($_SERVER['PHP_SELF'] == '/scale_wizzard.php' OR $_SERVER['PHP_SELF'] == '/calibrate_scale.php' OR $_SERVER['PHP_SELF'] == '/modules/tara_scale.php') {
                                    echo '<li><a href="scale_wizzard.php?rand=' . rand() . '" class="';
                                    echo $scale_wizzard_active;
                                    echo '">';
                                    echo _('scale wizzard');
                                    echo '</a></li>';
                                }
                                if ($_SERVER['PHP_SELF'] == '/edit_agingtable.php') {
                                    echo '<li><a href="edit_agingtable.php?rand=' . rand() . '" class="';
                                    echo $edit_agingtable_active;
                                    echo '">';
                                    echo _('edit agingtable');
                                    echo '</a></li>';
                                }
                            ?>
                        </ul>
                    </div>
                </nav>
                <script src='js/ajaxdatetime.js'></script>
            </header>
            <div class="art-sheet clearfix">
                <div class="art-layout-wrapper">
                    <div class="art-content-layout">
                        <div class="art-content-layout-row">
                            <div class="art-layout-cell art-content">
