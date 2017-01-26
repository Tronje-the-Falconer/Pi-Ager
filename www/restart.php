<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Restart Script</title> 
    </head>
    <body>
        <h1>Das System wird neu gestartet</h1>
        <p> 
            <?php
                echo date("d.m.Y H:i:s");
                shell_exec('sudo /var/sudowebscript.sh reb');
            ?>
        </p>
        <meta http-equiv="refresh" content="3; URL=/index.php">
    </body>
</html>
