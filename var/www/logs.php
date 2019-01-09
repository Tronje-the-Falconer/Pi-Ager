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
                                        <table style="width: 100%" class="miniature_writing">
                                            <tr>
                                                <td>
                                                    <?php 
                                                        if (is_file($logfile)) {
                                                            echo _('file verification').': '.$logfile.'<br />';
                                                            echo _('file size').': '.filesize($logfile).' bytes<br />';
                                                            $mtime = filemtime($logfile);
                                                            echo _('last changed at').': ';
                                                            echo date('d M Y, H:i:s', $mtime);
                                                            echo ' '._("o'clock").'<br />';
                                                            echo '<img src="images/icons/check_true_42x42.png"> '._('file exists').'<br />';
                                                        }
                                                        else {
                                                            echo '<img src="images/icons/check_false_42x42.png"> '._('file does not exist').'<br />';
                                                        }
                                                        if (is_readable($logfile)) {
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
                                                        }
                                                    ?>
                                                </td>
                                                <td><button class="art-button" name="save_logfiles" onclick="return confirm(<?php echo _('save all logfiles?'); ?>);"><?php echo _('save logfiles'); ?></button></td>
                                                <td><button class="art-button" name="clear_logfile" onclick="return confirm(<?php echo _('clear all logfile data?'); ?>);"><?php echo _('delete data'); ?></button></td>
                                            </tr>
                                        </table>
                                    </form>
                                    <hr>
                                    <?php 
                                        $files = glob('logs/*.txt*');
                                        $filecounter = 0;
                                        foreach ($files as $file) {
                                            ${'filename'.$filecounter} = basename($file);
                                            ${'filecontent'.$filecounter} = file($file);
                                            $filecounter++;
                                        }
                                    ?>
                                    <script>
                                        var mainlogfile = "logfile.txt";
                                        var logfilepath = "/logs/";
                                        var current_logfile = 'logfile.txt';
                                        var logfilecount = -1;
                                        
                                        window.onload = function () {
                                            write_filecontent(logfilepath + current_logfile);
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
                                              xmlHttp.onreadystatechange = function () {
                                                  if (xmlHttp.readyState == 4) {
                                                      text = xmlHttp.responseText;
                                                      newtext = text.replace(/(?:\r\n|\r|\n)/g, '<br />');
                                                      document.getElementById("filecontent").innerHTML = (newtext);
                                                      //document.getElementById("filecontent").innerHTML = (xmlHttp.responseText);
                                                      
                                                      
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
                                            if (logfilecount == -1 || logfilecount == 0){
                                                write_filecontent(logfilepath + "logfile.txt.1");
                                                current_logfile = 'logfile.txt.1';
                                                logfilecount = 1;
                                                alert(current_logfile);
                                            }
                                            else if (logfilecount < phplogfilecount && logfilecount != (phplogfilecount-1)){
                                                logfilecount++;
                                                write_filecontent(logfilepath + "logfile.txt." + logfilecount.toString());
                                                current_logfile = 'logfile.txt.' + logfilecount.toString();// + nächste Zahl
                                                alert(current_logfile);
                                            }
                                            else {
                                                write_filecontent(logfilepath + "logfile.txt");
                                                current_logfile = 'logfile.txt'
                                                logfilecount = -1;
                                                alert('first logfile ' + current_logfile);
                                            }
                                        }
                                        
                                        function next_logfile(phplogfilecount){
                                            if (logfilecount <= 0){
                                                firstlogfile = phplogfilecount-1
                                                write_filecontent(logfilepath + "logfile.txt." + firstlogfile.toString());
                                                current_logfile = 'logfile.txt.' + firstlogfile.toString();
                                                logfilecount = firstlogfile;
                                                alert(current_logfile);
                                            }
                                            else if (logfilecount <= phplogfilecount && logfilecount != 1){
                                                logfilecount--;
                                                write_filecontent(logfilepath + "logfile.txt." + logfilecount.toString());// + nächste Zahl
                                                current_logfile = 'logfile.txt.' + logfilecount.toString();// + nächste Zahl
                                                alert(current_logfile);
                                            }
                                            else {
                                                write_filecontent(logfilepath + "logfile.txt");
                                                current_logfile = 'logfile.txt'
                                                logfilecount = -1;
                                                alert('current logfile ' + current_logfile);
                                            }
                                        }
                                    </script>
                                    <table>
                                        <tr>
                                            <td>
                                                <button  class="art-button" onClick="prev_logfile(<?php print $filecounter; ?>);">< <?php print _('prev'); ?> </button>
                                            </td>
                                            <td><button  class="art-button" onClick="next_logfile(<?php print $filecounter; ?>);"> <?php print _('next'); ?> ></button></td>
                                        </tr>
                                    </table>
                                    <!----------------------------------------------------------------------------------------Logeinträge-->
                                    <table style="width: 100%" class="miniature_writing">
                                        <tr>
                                            <td>
                                                <p id="begin" align="right">
                                                    <a  href="#end"><?php echo _('to bottom') ?></a>
                                                </p>
                                                <div id="filecontent"></div>
                                                </div>
                                                </br></br>
                                                <p align="right" id="end">
                                                    <a align="right" href="#begin"><?php echo _('to top') ?></a>
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <!----------------------------------------------------------------------------------------Ende! ...-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            include 'footer.php';
        ?>