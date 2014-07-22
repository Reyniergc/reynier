<?php
	session_start(); 
	include("conectar_mysql.php");
	
	if ($_REQUEST['opcion'] == 1) {
		$query = mysql_query("select AES_DECRYPT(password, cadena) from usuarios where id = '$_SESSION[usuariologueado]'", $link);
		$Fila = mysql_fetch_array($query);
		if ($Fila["AES_DECRYPT(password, cadena)"] != $_REQUEST['contrasena_actual']) echo '0';
		else echo '1';
	}
	elseif ($_REQUEST['opcion'] == 2) {
		$query =mysql_query ("select cadena from usuarios where id = '$_SESSION[usuariologueado]'", $link) or die (mysql_error()); 
		
		$Fila =mysql_fetch_array($query);
		mysql_query ("update usuarios set password=AES_ENCRYPT('$_REQUEST[contrasena]', '$Fila[cadena]'), email='$_REQUEST[email]',  
		telefono='$_REQUEST[telefono]' where id = '$_SESSION[usuariologueado]'", $link) or die (mysql_error());
	}
	mysql_close($link);
?>