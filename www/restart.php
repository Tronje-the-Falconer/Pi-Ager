<!DOCTYPE html>
<html> 
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
?></p>


<meta http-equiv="refresh" content="3; URL=/index.php">

</body>
</html>
