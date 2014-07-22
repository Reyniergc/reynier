<?php
	include ("conectar_mysql.php");
	
	if ($_REQUEST['opcion'] == 0) { // Comprueba si el nick introducido en el momento de darse de alta a un usuario se encuentra disponible.
		
		$query = mysql_query("select nick from usuarios where nick = '$_REQUEST[nick_usuario]'", $link) or die (mysql_error());
		if (mysql_num_rows($query) > 0) echo '1';
		else echo '0';
	}
	elseif ($_REQUEST['opcion'] == 1) { // Alta usuarios.
		$encriptar = "mdn" . rand(1, 2000); // Genera valores aleatorios para encriptar la contraseña de cada empresa.
		
		mysql_query("insert into usuarios (nombre_empresa, nick, password, email, cadena, telefono, estado) values ('$_REQUEST[nombre_empresa]', '$_REQUEST[nick_usuario]', 
		AES_ENCRYPT('$_REQUEST[contrasena1]', '$encriptar'), '$_REQUEST[email]', '$encriptar', '$_REQUEST[telefono]', 1)", $link) or die (mysql_error());

		mkdir($_REQUEST['nick_usuario']); // Crea una carpeta como el mismo nombre de la empresa aquí estaran todos los menus de dicha empresa.
		
		// Se crean 5 carpetas para guardar los menús y la publicidad dentro del directorio principal con el nombre de la empresa dada de alta.
		mkdir($_REQUEST['nick_usuario'] . "/" . "Primer plato");
		mkdir($_REQUEST['nick_usuario'] . "/" . "Segundo plato");
		mkdir($_REQUEST['nick_usuario'] . "/" . "Postre");
		mkdir($_REQUEST['nick_usuario'] . "/" . "Bebida");
		mkdir($_REQUEST['nick_usuario'] . "/" . "Menus enviados");
		
		$_REQUEST['opcion'] =3; // Para saber si el correo electronico que es enviado al usuario es del registro de usuarios y no de recuperar contraseña.
		require("envio_email.php");
		return;
	}
	elseif ($_REQUEST['opcion'] == 2) { // Elimina la empresa y toda su información.
		
		function eliminarDir($carpeta) { // Borra la carpeta de la empresa que ha sido dada de baja.
			foreach (glob($carpeta . "/*") as $archivos_carpeta) {
				if (is_dir($archivos_carpeta)) eliminarDir($archivos_carpeta);
				else unlink($archivos_carpeta);
			}
			rmdir($carpeta);
		}
		
		$query = mysql_query("select nick from usuarios where id = '$_REQUEST[id_usuario]'", $link) or die (mysql_error());
		$fila = mysql_fetch_array($query);
		// Se llama la función "eliminarDir" para eliminar las carpetas de la empresa dada de baja.
		eliminarDir($fila['nick']);
		
		mysql_query("delete from usuarios where id = '$_REQUEST[id_usuario]'", $link) or die (mysql_error());
		mysql_query("delete from info_platos where id_usuario = '$_REQUEST[id_usuario]'", $link) or die (mysql_error());
		mysql_query("delete from info_menu where id_usuario = '$_REQUEST[id_usuario]'", $link) or die (mysql_error());
	}
	elseif ($_REQUEST['opcion'] == 3) { // HABILITA UNA EMPRESA.
		mysql_query("UPDATE usuarios SET estado=1 where id = '$_REQUEST[id_usuario]'", $link) or die (mysql_error());
	}
	elseif ($_REQUEST['opcion'] == 4) { // DESHABILITA UNA EMPRESA.
		mysql_query("UPDATE usuarios SET estado=0 where id = '$_REQUEST[id_usuario]'", $link) or die (mysql_error());
	}
	elseif ($_REQUEST['opcion'] == 5) { // Inicializa el DIALOG para actualizar la información de los usuarios.
		$query = mysql_query("select nombre_empresa, email, telefono from usuarios where id = '$_REQUEST[id_usuario]'", $link) or die (mysql_error());
		$fila = mysql_fetch_array($query);
		
		echo '{"nombre_empresa" : "'. $fila['nombre_empresa'] .'", 
			"email" : "'. $fila['email'] .'",
			"telefono" : "'. $fila['telefono'] .'"
		}';
	}
	elseif ($_REQUEST['opcion'] == 6) { // Atualiza la información de los usuarios.
		mysql_query("UPDATE usuarios SET nombre_empresa='$_REQUEST[nombre_empresa]', email='$_REQUEST[email]', telefono='$_REQUEST[telefono]' where id = '$_REQUEST[id_usuario]'", $link) or die (mysql_error());
	}
	
	mysql_close($link); 
?>