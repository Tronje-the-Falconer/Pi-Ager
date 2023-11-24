<?php 
                                    include 'header.php';      // Template-Kopf und Navigation
                                    include 'modules/logging.php';                            //liest die Datei fuer das logging ein
                                    
                                    include 'modules/database.php';
                                    include 'modules/clear_logfile.php';
                                    include 'modules/save_logfiles.php';
                                ?>
                                <h2 class="art-postheader"><?php echo _('log entries'); ?></h2>
                                <div class="hg_container" style="text-align: left;">
                                    <form  method="post">
                                        <table style="width: 100%;" class="miniature_writing">
                                            <tr>
                                                <td>
                                                    <?php 
                                                        if (is_file($logfile)) {
                                                            echo _('file verification').': '.$logfile.'<br />';
                                                            echo _('file size').': '.filesize($logfile).' bytes<br />';
                                                            $mtime = filemtime($logfile);
                                                            $serverTimezone = exec('date +%Z');
                                                            // echo 'Timezone: ' . $serverTimezone . '<br>';
                                                            $toffset = timezone_offset_get(new DateTimeZone($serverTimezone), new DateTime('now'));
                                                            // echo 'Timezone offset in hours : ' . $toffset/3600 . '<br>';
                                                            echo _('last changed at').': ';
                                                            echo date('d M Y, H:i:s', $mtime + $toffset );
                                                            echo ' '._("o'clock").'<br />';
//                                                            echo '<img src="images/icons/check_true_42x42.png"> '._('file exists').'<br />';
                                                        }
                                                        else {
                                                            echo '<img src="images/icons/check_false_42x42.png"> '._('file does not exist').'<br />';
                                                        }
/*                                                        if (is_readable($logfile)) {
                                                            echo '<img src="images/icons/check_true_42x42.png"> '._('file is readable').'<br />';
                                                        }
                                                        else {
                                                            echo '<img src="images/icons/check_false_42x42.png"> '._('file is not readable').'<br />';
                                                        }
                                                        if (is_writable($logfile)) {
                                                            echo '<img src="images/icons/check_true_42x42.png"> '._('file is writable').'<br />';
                                                        }
                                                        else {
                                                            echo '<img src="images/icons/check_false_42x42.png"> '._('file is not writable').'<br />';
                                                        } */
                                                        echo '<br />';
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                        <table style="width: 100%;" class="miniature_writing">
                                            <tr>
                                                <td style="text-align: left;">
                                                    <div style="width: 100%;">
                                                        <button class="art-button" name="save_logfiles" onclick="return confirm(<?php echo _('save all logfiles?'); ?>);"><?php echo _('save logfiles'); ?></button>
                                                        <button class="art-button" name="clear_logfile" onclick="return confirm(<?php echo _('clear all logfile data?'); ?>);"><?php echo _('delete data'); ?></button>
                                                        <button class="art-button" name="view_pi_ager_logfile" onClick="window.open('/logs/pi-ager.log');"><?php echo _('open pi-ager.log in new tab'); ?></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                    <hr>
                                    <?php 
                                        $files = glob('logs/*.txt*');
                                        $filecounter = 0;
                                        foreach ($files as $file) {
                                           // ${'filename'.$filecounter} = basename($file);
                                           // ${'filecontent'.$filecounter} = file($file);
                                            $filecounter++;
                                        }
                                    ?>
                                    <script>
                                        var mainlogfile = "logfile.txt";
                                        var logfilepath = "/logs/";
                                        var current_logfile = 'logfile.txt';
                                        var logfileindex = 0;  // points to logfile.txt
                                        
                                        window.onload = function () {
                                            write_filecontent(logfilepath + current_logfile);
                                            document.getElementById("currentfile").innerHTML = (current_logfile);
                                        }
                                        
                                        function write_filecontent(file){
                                            var xmlHttp = null;
                                            // Mozilla, Opera, Safari sowie Internet Explorer 7
                                            if (typeof XMLHttpRequest != 'undefined') {
                                              xmlHttp = new XMLHttpRequest();
                                            }
                                            if (!xmlHttp) {
                                              // Internet Explorer 6 und älter
                                              try {
                                                  xmlHttp  = new ActiveXObject("Msxml2.XMLHTTP");
                                              } catch(e) {
                                                  try {
                                                      xmlHttp  = new ActiveXObject("Microsoft.XMLHTTP");
                                                  } catch(e) {
                                                      xmlHttp  = null;
                                                  }
                                              }
                                            }
                                            if (xmlHttp) {
                                              xmlHttp.open('GET', file, true);
                                              xmlHttp.setRequestHeader("Cache-Control", "max-age=0");
                                              xmlHttp.onreadystatechange = function () {
                                                  if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                                                      text = xmlHttp.responseText;
                                                      //newtext = text.replace(/(?:\r\n|\r|\n)/g, '<br />');
                                                      newtext = text.replace(/(?:\\[rn]|[\r\n])/g, '<br>');
                                                      newtext = newtext.replace(/exception/gi, function(x) {
                                                      return('<span style="color: red;">' + x + '</span>');   
                                                      });
                                                      document.getElementById("filecontent").innerHTML = (newtext);
                                                      // document.getElementById("filecontent").textContent = text;
                                                      document.getElementById("end_href").click();
                                                  }
                                              };
                                              xmlHttp.send(null);
                                            }
                                        }
                                        
                                        
                                        
                                        // function getfilecontent(file) {
                                            // file =$.text(file);
                                            // file = file.html(file.html().replace(/\n/g,'<br/>'));
                                           //// file = file.replace(/(?:\r\n|\r|\n)/g, '<br />');
                                            // return file;
                                        // }
                                        function prev_logfile(phplogfilecount){
                                            console.log('prev clicked. phplogfilecount = ' + phplogfilecount + ' logfileindex = ' + logfileindex);
                                            if (phplogfilecount == 0 || phplogfilecount == 1) {  // file is missing or only mainlogfile
                                                return;
                                            }
                                            // here we have more than 1 log file
                                            if (logfileindex < (phplogfilecount - 1)){
                                                logfileindex++;
                                                write_filecontent(logfilepath + "logfile.txt." + logfileindex.toString());
                                                current_logfile = 'logfile.txt.' + logfileindex.toString();
                                                // alert(current_logfile);
                                                document.getElementById("currentfile").innerHTML = (current_logfile);
                                            }
                                        }
                                        
                                        function next_logfile(phplogfilecount){
                                            console.log('next clicked. phplogfilecount = ' + phplogfilecount + ' logfileindex = ' + logfileindex);
                                            if (phplogfilecount == 0 || phplogfilecount == 1) {  // file is missing or only mainlogfile
                                                return;
                                            }
                                            // here we have more than 1 log file                                            
                                            if (logfileindex > 0){
                                                logfileindex--;
                                                if (logfileindex == 0) {
                                                    write_filecontent(logfilepath + "logfile.txt");
                                                    current_logfile = 'logfile.txt'
                                                    // alert('current logfile ' + current_logfile);
                                                    document.getElementById("currentfile").innerHTML = (current_logfile);      
                                                }
                                                else {
                                                    write_filecontent(logfilepath + "logfile.txt." + logfileindex.toString());
                                                    current_logfile = 'logfile.txt.' + logfileindex.toString();
                                                    // alert(current_logfile);
                                                    document.getElementById("currentfile").innerHTML = (current_logfile);                                               
                                                }
                                            }
                                        }
                                    </script>
                                    <table>
                                        <tr>
                                            <td>
                                                <button  class="art-button" onClick="prev_logfile(<?php print $filecounter; ?>);"> <?php print _('prev'); ?> </button>
                                            </td>
                                            <td><button  class="art-button" onClick="next_logfile(<?php print $filecounter; ?>);"> <?php print _('next'); ?> </button></td>
                                            <td style="width: 100%; background-color: silver; text-align: center;" id="currentfile"></td>
                                        </tr>
                                    </table>
                                    <!----------------------------------------------------------------------------------------Logeinträge-->
                                    <div style="margin: 5px; height: 600px; border: 1px solid #ccc; overflow: auto;">
                                    <table style="width: 100%" class="miniature_writing">
                                        <tr>
                                            <td>
                                                <p id="begin" align="right">
                                                    <a id="end_href" href="#end"><?php echo _('to bottom') ?></a>
                                                </p>
                                                <div id="filecontent" ></div>
                                                </div>
                                                </br></br>
                                                <p align="right" id="end">
                                                    <a href="#begin"><?php echo _('to top') ?></a>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    </div>
                                </div>
                                <!----------------------------------------------------------------------------------------Ende! ...-->
                            </div>  <!-- art-layout-cell art-content -->
                        </div>      <!-- art-content-layout-row -->
                    </div>          <!-- art-content-layout -->
                </div>             <!--  art-layout-wrapper -->
            </div>                  <!-- art-sheet clearfix -->
            <?php 
                include 'footer.php';
            ?>                   
<!--    </div>                       art-main -->
