<?php   
session_start(); 

	header("Content-type: text/xml");
	$id_usuario ="";
	$valor_error =0;
	
	// Devuelve verdadero si el usuario esta registrado. 
	function usuarioLogueado($nick_usuario, $clave_usuario) { 
		include ("conectar_mysql.php");
		global $id_usuario;
		global $valor_error;
		
		if ($nick_usuario == "admin") { // Si es el administrador que intenta loguearse.
			// Se desencripta la contraseña.
			$query = mysql_query("select nick, AES_DECRYPT(password, cadena) from administrador where nick = '$nick_usuario'", $link); 
			$Fila = mysql_fetch_array($query);
			mysql_close($link); 
			
			if (isset($Fila['nick']) && ($Fila['nick'] = $nick_usuario) && ($Fila["AES_DECRYPT(password, cadena)"] == $clave_usuario)) return true;
			else return false;
		}
		else { // Si no es el administrador del sistema.
			$query = mysql_query("select id, nick, AES_DECRYPT(password, cadena), estado from usuarios where nick = '$nick_usuario'", $link);
			$Fila = mysql_fetch_array($query);
			$id_usuario =$Fila['id'];
			mysql_close($link); 
			
			if (isset($Fila['nick']) && ($Fila['nick'] = $nick_usuario) && ($Fila["AES_DECRYPT(password, cadena)"] == $clave_usuario)) {
				if ($Fila['estado'] > 0) return true;
				else {
					$valor_error =2;
					return false;
				}
			}
			else {
				$valor_error =0;
				return false;
			}
		}
	}
	
	if (usuarioLogueado($_POST['nick'], $_POST['contrasena'])) {	
		global $id_usuario;
		$_SESSION['nick_usuario'] = $_POST['nick']; // Para el mensaje de bienvenida.
		$_SESSION['usuariologueado'] = $id_usuario; // Registramos la sección con el id del usuario. 
		echo '1';
	}
	else echo $valor_error;
?>