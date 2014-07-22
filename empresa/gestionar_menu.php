<?php
	session_start(); 
	include ("conectar_mysql.php");
	
	// Comprueba si el título para crear un nuevo menú no está siendo usado por otro menú.
	function CompruebaNombreMenu($titulo) {
		include ("conectar_mysql.php");
		$query = mysql_query("SELECT titulo FROM info_menu WHERE id_usuario = '$_SESSION[usuariologueado]' AND titulo = '$titulo'", $link) or die (mysql_error());
		// Comprobamos si el nombre del menú que queremos crear ya existe.
		if (mysql_num_rows($query) > 0) return true;
		else return false;
	}
	
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
	
	if (isset($_REQUEST['pagina_actual'])) { // Indica apartir de que fila debemos seleccionar la información de la base de datos.
		$valor_inicio = (($_REQUEST['pagina_actual'] * $_REQUEST['limite']) - ($_REQUEST['limite']));
	}
	
	if ($_REQUEST['opcion'] == 0) { // INICIALIZA EL NÚMERO DE PAGINAS DEL PAGINATOR.
		$query = mysql_query("select * from info_menu where id_usuario = '$_SESSION[usuariologueado]'", $link) or die (mysql_error());
		$num_reg =mysql_num_rows($query);
		if (($num_reg % $_REQUEST['limite']) == 0) echo ($num_reg / $_REQUEST['limite']);
		else echo (intval(($num_reg / $_REQUEST['limite'])+1));  // Si el resto de la divisón no es cero.
		return;
	}
	elseif ($_REQUEST['opcion'] == 1) { //ELIMINAR MENÚ.
		
		function eliminarDir($carpeta) {
			foreach (glob($carpeta . "/*") as $archivos_carpeta) {
				if (is_dir($archivos_carpeta)) eliminarDir($archivos_carpeta);
				else unlink($archivos_carpeta);
			}
			rmdir($carpeta);
		}
		
		$query = mysql_query("SELECT fecha_envio FROM fecha_envio_menu WHERE id_menu = '$_REQUEST[id_menu]'", $link) or die (mysql_error());
		if (mysql_num_rows($query) > 0) {  // Solo eliminamos el directorio si el menú ha sido enviado.
			$query = mysql_query("SELECT distinct info_menu.titulo, fecha_envio_menu.fecha_envio FROM info_menu INNER JOIN fecha_envio_menu 
			ON info_menu.id = fecha_envio_menu.id_menu WHERE info_menu.id = '$_REQUEST[id_menu]'", $link) or die (mysql_error());
			
			// Bucle necesario en caso que el menú tenga varias fechas de envío.
			while ($fila = mysql_fetch_array($query)) eliminarDir($_SESSION['nick_usuario'] . "/Menus enviados/" . stripAccents($fila['titulo']) . ' ' . $fila['fecha_envio']);	
		}
		mysql_query("delete from info_menu where id = '$_REQUEST[id_menu]'") or die (mysql_error());
	}
	elseif (($_REQUEST['opcion'] == 2) || ($_REQUEST['opcion'] == 3)) { // DATOS QUE SE ENVIARAN A JQUERY PARA CONTRUIR EL MODAL DE VISUALIZACIÓN DE LOS MENÚS.	
		
		$i =0;
		$vec_fechas =array();
		$query_1 = mysql_query("select distinct fecha_envio from fecha_envio_menu where id_menu = '$_REQUEST[id_menu]'", $link);
		while ($fila_1 = mysql_fetch_array($query_1)) {
			$vec_fechas[$i] =$fila_1['fecha_envio'];
			++$i;
		}
		
		$query = mysql_query("select * from info_menu where id = '$_REQUEST[id_menu]'", $link);
		$fila = mysql_fetch_array($query);
		
		$vec_res =array();
		$i =1;
		while ($i <= 12) { 
			if ($fila['op' . $i] > 0) { // Seleccionamos unicamente las opciones "op" que son distintas de cero es decir que contienen el id de un plato.
				$query = mysql_query("select * from info_platos where id =" . $fila['op' . $i], $link) or die (mysql_error());
				$fila_platos = mysql_fetch_array($query);
				
				$vec_res[] =array(
					"tipo_plato"   => $fila_platos['tipo'],
					"nombre_plato" => $fila_platos['nombre'],
					"ruta_img"     => $fila_platos['ruta_img'],
					"id"    	   => $fila_platos['id'],
					"titulo_menu"  => $fila['titulo']
				);
			}
			++$i;
		}
		
		$vec_res['sucess'] =true;
		$vec_res['vec_fechas'] =$vec_fechas;
		echo json_encode($vec_res);		
	} 
	elseif ($_REQUEST['opcion'] == 4) { // ELIMINA EL PLATO QUE PERTENECE A UN MENÚ DEL MISMO MENÚ AL QUE PERTENECE.
		$query = mysql_query("select * from info_menu where id = '$_REQUEST[id_menu]'", $link);
		$fila = mysql_fetch_array($query);
		
		$i =1;
		while ($i <= 12) { 
			if ($fila['op' . $i] > 0) { // Seleccionamos unicamente las opciones "op" que son distintas de cero es decir que contienen el id de un plato.
				if ($fila['op' . $i] == $_REQUEST['id_plato']) mysql_query("UPDATE info_menu SET op" . $i . "=0 where id = '$_REQUEST[id_menu]'", $link) or die (mysql_error());
			}
			++$i;
		}
	}
	elseif ($_REQUEST['opcion'] == 5) { // COPIAR MENÚ.	
		$fecha =date('d-m-Y');
		if (!CompruebaNombreMenu($_REQUEST['titulo'])) mysql_query("insert into info_menu (id_usuario, titulo, fecha_ultima_modificacion) values ('$_SESSION[usuariologueado]', '$_REQUEST[titulo]', '$fecha')", $link) or die (mysql_error());
		else { // Si ya existe el nombre del menú.
			echo '1';
			return;
		}
		
		$id_insert =mysql_insert_id(); // Función que devuelve el id del ultimo insert.
		$cont =1;
		$vec_platos = array();
		$vec_platos =$_REQUEST['vec_platos'];
		
		for ($i =0; $i < $_REQUEST['indice_vec']; $i++) {
			mysql_query("UPDATE info_menu SET op" . $cont . "='$vec_platos[$i]' where id = '$id_insert'", $link) or die (mysql_error());
			++$cont; 
		}
	}
	elseif (($_REQUEST['opcion'] == 6) || ($_REQUEST['opcion'] == 7)) { // Para dibujar la interfaz de gestionar menú cuando pinchamos sobre el paginador y el buscador de menús. 		
		
		if ($_REQUEST['opcion'] == 6) $query = mysql_query("SELECT * FROM info_menu WHERE id_usuario = '$_SESSION[usuariologueado]' LIMIT $valor_inicio, $_REQUEST[limite]", $link) or die (mysql_error());
		elseif ($_REQUEST['opcion'] == 7) { // Si es del buscador de menús
			$query = mysql_query("SELECT * FROM info_menu WHERE id_usuario = '$_SESSION[usuariologueado]' AND INSTR (titulo, '$_REQUEST[inputbuscarMenu]')", $link) or die (mysql_error());
		}
		
		while ($fila = mysql_fetch_array($query)) {
			$editar =false;
			$fecha_envio_menu ="";
			
			$query_1 = mysql_query("SELECT fecha_envio FROM fecha_envio_menu WHERE id_menu = '$fila[id]'", $link) or die (mysql_error());
			
			// Si "editar" es verdadero significa que el menú no ha sido enviado.
			if (mysql_num_rows($query_1) == 0) $editar =true;
			else { // Si "editar" se mantiene a falso significa que el menú ha sido enviado.
				while ($fecha = mysql_fetch_array($query_1)) $fecha_envio_menu =$fecha['fecha_envio'];
			}
			
			$i =1;
			$indice =1;
			$array_tipo_plato = array(1 => 'Primer plato', 2 => 'Segundo plato', 3 => 'Postre', 4 => 'Bebida');
			$ruta_primer_plato ="Sin eleccion";
			$ruta_segundo_plato ="Sin eleccion";
			$ruta_postre ="Sin eleccion";
			$ruta_bebida ="Sin eleccion";
			
			while ($i <= 4) {
				$j =1;
				while ($j <= 12) {
					if ($fila['op' . $j] > 0) { // Seleccionamos unicamente las opciones "op" que son distintas de cero es decir que contienen el id de un plato.
						$query_2 = mysql_query("SELECT nombre, ruta_img FROM info_platos WHERE tipo ='$array_tipo_plato[$indice]' AND id =" . $fila['op' . $j], $link) or die (mysql_error());
				
						if (mysql_num_rows($query_2) > 0) {
							$fila1 = mysql_fetch_array($query_2);
							break 1;
						}
					}	
					++$j; 
				}
	
				if ($j < 12) { // En caso de que no exista el tipo de plato.
					if ($i == 1) $ruta_primer_plato =$fila1['ruta_img'];
					elseif ($i == 2) $ruta_segundo_plato =$fila1['ruta_img'];
					elseif ($i == 3) $ruta_postre =$fila1['ruta_img']; 
					else $ruta_bebida =$fila1['ruta_img']; 
				} 
				++$indice;
				++$i;
			}
		
			$vec_res[] =array(
				"id_menu"      				=> $fila['id'],
				"titulo_menu"  				=> $fila['titulo'],
				"ruta_primer_plato"     	=> $ruta_primer_plato,
				"ruta_segundo_plato"   		=> $ruta_segundo_plato,
				"ruta_postre"     			=> $ruta_postre,
				"ruta_bebida"     			=> $ruta_bebida,
				"editar"  	  				=> $editar,
				"fecha_envio_menu" 			=> $fecha_envio_menu,  
				"fecha_ultima_modificacion" => $fila['fecha_ultima_modificacion']
			);
		}
		$vec_res['sucess'] =true;
		echo json_encode($vec_res);
	}
	mysql_close($link);
?>