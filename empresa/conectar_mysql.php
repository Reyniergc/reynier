<?php
	$error_message = "Error. No se pudo conectar con la base de datos.";
	$hostname = "localhost";
	$user = "mdn";
	$pass = "empresamdn";
	$database = "empresa";
	$link = @mysql_connect($hostname, $user, $pass) or die ($error_message);
	@mysql_select_db($database, $link) or die ($error_message);
?>