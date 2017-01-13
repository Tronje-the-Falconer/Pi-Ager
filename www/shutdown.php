<!DOCTYPE html>
<html> 
<head>
	<meta charset="UTF-8" />
	<title>Restart Script</title> 
</head>
 
<body>
<h1>Das System wird heruntergefahren</h1>

<p> 
<?php
echo date("d.m.Y H:i:s");
system('sudo /sbin/shutdown -h now');
?></p>


<meta http-equiv="refresh" content="3; URL=/index.php">


</body>
</html>


