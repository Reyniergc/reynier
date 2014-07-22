<?php
	session_start(); 

	include ("conectar_mysql.php");
	
	// Elimina los caracteres especiales para evitar que se escriba mal el nombre del menú si este nombre tiene acentos.
	function stripAccents($String) {
	
		$String = str_replace(array('á','à','â','ã','ª','ä'), 'a', $String);
		$String = str_replace(array('Á','À','Â','Ã','Ä'), 'A', $String);
		$String = str_replace(array('Í','Ì','Î','Ï'), 'I', $String);
		$String = str_replace(array('í','ì','î','ï'), 'i', $String);
		$String = str_replace(array('é','è','ê','ë'), 'e', $String);
		$String = str_replace(array('É','È','Ê','Ë'), 'E', $String);
		$String = str_replace(array('ó','ò','ô','õ','ö','º'), 'o', $String);
		$String = str_replace(array('Ó','Ò','Ô','Õ','Ö'), 'O', $String);
		$String = str_replace(array('ú','ù','û','ü'), 'u', $String);
		$String = str_replace(array('Ú','Ù','Û','Ü'), 'U', $String);
		$String = str_replace(array('[','^','´','`','¨','~',']'), "", $String); 
		$String = str_replace('ç', 'c', $String);
		$String = str_replace('Ç', 'C', $String);
		$String = str_replace('ñ', 'n', $String);
		$String = str_replace('Ñ', 'N', $String);
		$String = str_replace('Ý', 'Y', $String);
		$String = str_replace('ý', 'y', $String);
		$String = str_replace('ÿ', 'y', $String);
		return $String;
	}
	
	$fecha ="";
	if (isset($_REQUEST['fecha'])) {
		$_REQUEST['fecha'] =$_REQUEST['fecha'] . '/';
		$fecha = $fecha . substr($_REQUEST['fecha'], 0, strpos($_REQUEST['fecha'], '/')) . '-';
		$_REQUEST['fecha'] =substr($_REQUEST['fecha'], strpos($_REQUEST['fecha'], '/')+1);
		$fecha = $fecha . substr($_REQUEST['fecha'], 0, strpos($_REQUEST['fecha'], '/')) . '-';
		$_REQUEST['fecha'] =substr($_REQUEST['fecha'], strpos($_REQUEST['fecha'], '/')+1);
		$fecha = $fecha . substr($_REQUEST['fecha'], 0, strpos($_REQUEST['fecha'], '/'));
	}
	
	function creaCarpetas() {
		
		include ("conectar_mysql.php");

		global $fecha;
		$primer_plato =true;
		$segundo_plato =true;
		$postre =true;
		$bebida =true;	
		$vec_platos = array();
		$vec_platos =$_REQUEST['vec_platos'];
		
		mkdir($_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($_REQUEST['titulo']) . ' ' . $fecha);
		
		for ($i =0; $i < $_REQUEST['indice_vec']; $i++) {
			$query = mysql_query("select * from info_platos where id =" . $vec_platos[$i], $link) or die (mysql_error());
			$fila = mysql_fetch_array($query);
			
			$nombre_archivo =$fila['ruta_img'];
			while (strpos($nombre_archivo, '/') > 0) $nombre_archivo =substr($nombre_archivo, strpos($nombre_archivo, '/')+1);
				
			if ($fila['tipo'] == "Primer plato") {
				if ($primer_plato) {
					mkdir($_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($_REQUEST['titulo']) . ' ' . $fecha . "/opciones-primer-plato");
					$primer_plato =false;
				}
				copy ($fila['ruta_img'], $_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($_REQUEST['titulo']) . ' ' . $fecha . "/opciones-primer-plato/" . $nombre_archivo);
			}
			elseif ($fila['tipo'] == "Segundo plato") {
				if ($segundo_plato) {
					mkdir($_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($_REQUEST['titulo']) . ' ' . $fecha . "/opciones-segundo-plato");
					$segundo_plato =false;
				}
				copy ($fila['ruta_img'], $_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($_REQUEST['titulo']) . ' ' . $fecha . "/opciones-segundo-plato/" . $nombre_archivo);
			}
			elseif ($fila['tipo'] == "Postre") {
				if ($postre) {
					mkdir($_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($_REQUEST['titulo']) . ' ' . $fecha . "/postre");
					$postre =false;
				}
				copy ($fila['ruta_img'], $_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($_REQUEST['titulo']) . ' ' . $fecha . "/postre/" . $nombre_archivo);
			}
			elseif ($fila['tipo'] == "Bebida") {
				if ($bebida) {
					mkdir($_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($_REQUEST['titulo']) . ' ' . $fecha . "/bebida");
					$bebida =false;
				}
				copy ($fila['ruta_img'], $_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($_REQUEST['titulo']) . ' ' . $fecha . "/bebida/" . $nombre_archivo);
			}
		}
	}
	
	// Comprueba si el título para crear un nuevo menú no está siendo usado por otro menú.
	function CompruebaNombreMenu($titulo) {
		include ("conectar_mysql.php");
		$query = mysql_query("SELECT titulo FROM info_menu WHERE id_usuario = '$_SESSION[usuariologueado]' AND titulo = '$titulo'", $link) or die (mysql_error());
		// Comprobamos si el nombre del menú que queremos crear ya existe.
		if (mysql_num_rows($query) > 0) return true;
		else return false;
	}

	if (($_REQUEST['opcion'] == 1) || ($_REQUEST['opcion'] == 2)) { // Se ejecuta al pulsar el boton "exportarMenu" y se recupera del vector "vec_platos" todos los platos que constituyen el menú.
	
		$fecha_modificacion =date('d-m-Y');
		if ($_REQUEST['opcion'] == 1) {
			//mysql_query("insert into info_menu (id_usuario, titulo, fecha_ultima_modificacion) values ('$_SESSION[usuariologueado]', '$_REQUEST[titulo]', '$fecha_modificacion')", $link) or die (mysql_error());
			if (!CompruebaNombreMenu($_REQUEST['titulo'])) mysql_query("insert into info_menu (id_usuario, titulo, fecha_ultima_modificacion) values ('$_SESSION[usuariologueado]', '$_REQUEST[titulo]', '$fecha_modificacion')", $link) or die (mysql_error());
			else { // Si ya existe el nombre del menú.
				echo '1';
				return;
			}
		}
		else { // Guardar.
			//mysql_query("insert into info_menu (id_usuario, titulo, fecha_ultima_modificacion) values ('$_SESSION[usuariologueado]', '$_REQUEST[titulo]', '$fecha_modificacion')", $link) or die (mysql_error());
			if (!CompruebaNombreMenu($_REQUEST['titulo'])) mysql_query("insert into info_menu (id_usuario, titulo, fecha_ultima_modificacion) values ('$_SESSION[usuariologueado]', '$_REQUEST[titulo]', '$fecha_modificacion')", $link) or die (mysql_error());
			else { // Si ya existe el nombre del menú.
				echo '1';
				return;
			}
		}
		
		$id_insert =mysql_insert_id(); // Función que devuelve el id del ultimo insert.
		$cont =1;
		$vec_platos = array();
		$vec_platos =$_REQUEST['vec_platos'];
		
		for ($i =0; $i < $_REQUEST['indice_vec']; $i++) {
			mysql_query("UPDATE info_menu SET op" . $cont . "='$vec_platos[$i]' where id = '$id_insert'", $link) or die (mysql_error());
			++$cont; 
		}
		
		if ($_REQUEST['opcion'] == 2) { // Si la opción es solo guardar el menú retornamos para que no se creen las carpetas ya que no es necesario.
			mysql_close($link);
			return; 
		}
		
		mysql_query("insert into fecha_envio_menu (id_menu, fecha_envio) values ('$id_insert', '$fecha')", $link) or die (mysql_error());
		creaCarpetas();
	}
	elseif (($_REQUEST['opcion'] == 3) || ($_REQUEST['opcion'] == 4)) {	// "OPCION 3" EXPORTA EL MENÚ SI LO ESTAMOS EDITANDO. "OPCION 4" GUARDA LOS CAMBIOS HECHOS EN UN MENÚ CUANDO ES EDITADO.
		
		$query = mysql_query("SELECT fecha_envio FROM fecha_envio_menu WHERE id_menu = '$_REQUEST[id_menu]'", $link) or die (mysql_error());
		// Comprobamos si el menú que intentamos editar ya ha sido enviado.
		if (mysql_num_rows($query) > 0) {
			echo $_REQUEST['opcion'];
			return;
		}
		
		///////////////////////////////////////////////////////
		$query1 = mysql_query("SELECT titulo FROM info_menu WHERE id = '$_REQUEST[id_menu]' AND titulo = '$_REQUEST[titulo]'", $link) or die (mysql_error());
		if (mysql_num_rows($query1) == 0) {
			if (CompruebaNombreMenu($_REQUEST['titulo'])) { // Ya existe el título del menú.
				echo '1';
				return;
			}
		}
		///////////////////////////////////////////////////////
		
		$fecha_modificacion =date('d-m-Y');
		mysql_query("UPDATE info_menu SET fecha_ultima_modificacion='$fecha_modificacion', titulo = '$_REQUEST[titulo]', op1=0, op2=0, op3=0, op4=0, op5=0, op6=0, op7=0, op8=0, op9=0, op10=0, op11=0, op12=0 where id = '$_REQUEST[id_menu]'", $link) or die (mysql_error());
		
		$cont =1;
		$vec_platos = array();
		$vec_platos =$_REQUEST['vec_platos'];
		
		for ($i =0; $i < $_REQUEST['indice_vec']; $i++) {
			mysql_query("UPDATE info_menu SET op" . $cont . "='$vec_platos[$i]' where id = '$_REQUEST[id_menu]'", $link) or die (mysql_error());
			++$cont; 
		}
	
		if ($_REQUEST['opcion'] == 4) { // Si la opción es solo guardar el menú retornamos para que no se creen las carpetas ya que no es necesario.
			mysql_close($link);
			return; 
		}
		
		mysql_query("insert into fecha_envio_menu (id_menu, fecha_envio) values ('$_REQUEST[id_menu]', '$fecha')", $link) or die (mysql_error());
		creaCarpetas();
	}	
	elseif ($_REQUEST['opcion'] == 5) { // Para volver a enviar un menú que ya existe y que haya sido enviado.
		mysql_query("insert into fecha_envio_menu (id_menu, fecha_envio) values ('$_REQUEST[id_menu]', '$fecha')", $link) or die (mysql_error());
		
		$query = mysql_query("select * from info_menu where id = '$_REQUEST[id_menu]'", $link) or die (mysql_error());
		$fila =mysql_fetch_array($query);
		
		$vec_platos = array();
		$indice_vec =0;
		$i =1;
		while ($i <= 12) { 
			if ($fila['op' . $i] > 0) { // Seleccionamos unicamente las opciones "op" que son distintas de cero es decir que contienen el id de un plato.
				$vec_platos[$indice_vec] =$fila['op' . $i];
				++$indice_vec;
			}
			++$i;
		}
		
		mkdir($_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($fila['titulo']) . ' ' . $fecha);
		
		$primer_plato =true;
		$segundo_plato =true;
		$postre =true;
		$bebida =true;	
		for ($i =0; $i < $indice_vec; $i++) {
			$query_1 = mysql_query("select * from info_platos where id =" . $vec_platos[$i], $link) or die (mysql_error());
			$fila_1 = mysql_fetch_array($query_1);
			
			$nombre_archivo =$fila_1['ruta_img'];
			while (strpos($nombre_archivo, '/') > 0) $nombre_archivo =substr($nombre_archivo, strpos($nombre_archivo, '/')+1);
				
			if ($fila_1['tipo'] == "Primer plato") {
				if ($primer_plato) {
					mkdir($_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($fila['titulo']) . ' ' . $fecha . "/opciones-primer-plato");
					$primer_plato =false;
				}
				copy ($fila_1['ruta_img'], $_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($fila['titulo']) . ' ' . $fecha . "/opciones-primer-plato/" . $nombre_archivo);
			}
			elseif ($fila_1['tipo'] == "Segundo plato") {
				if ($segundo_plato) {
					mkdir($_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($fila['titulo']) . ' ' . $fecha . "/opciones-segundo-plato");
					$segundo_plato =false;
				}
				copy ($fila_1['ruta_img'], $_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($fila['titulo']) . ' ' . $fecha . "/opciones-segundo-plato/" . $nombre_archivo);
			}
			elseif ($fila_1['tipo'] == "Postre") {
				if ($postre) {
					mkdir($_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($fila['titulo']) . ' ' . $fecha . "/postre");
					$postre =false;
				}
				copy ($fila_1['ruta_img'], $_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($fila['titulo']) . ' ' . $fecha . "/postre/" . $nombre_archivo);
			}
			elseif ($fila_1['tipo'] == "Bebida") {
				if ($bebida) {
					mkdir($_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($fila['titulo']) . ' ' . $fecha . "/bebida");
					$bebida =false;
				}
				copy ($fila_1['ruta_img'], $_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($fila['titulo']) . ' ' . $fecha . "/bebida/" . $nombre_archivo);
			}
		}
	}
	mysql_close($link); 
?>