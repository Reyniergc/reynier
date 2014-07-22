<?php
	session_start(); 
	include ("conectar_mysql.php");
	
	if (isset($_REQUEST['pagina_actual'])) { // Indíca apartir de que fila debemos seleccionar la información de la base de datos.
		$valor_inicio = (($_REQUEST['pagina_actual'] * $_REQUEST['limite']) - ($_REQUEST['limite']));
	}
	
	// DEVUELVE UN VECTOR CON EL ID Y EL TIÍTULO DEL MENÚ SI EL PLATO PASADO COMO PARÁMETRO PERTENECE ALGÚN MENÚ.
	$vec_info_menu =array();
	function infoMenu($id_plato) {
		
		include ("conectar_mysql.php");
		$vec_info_menu =array();
		$indice =0;
		
		$query = mysql_query("select * from info_menu where id_usuario = '$_SESSION[usuariologueado]'", $link) or die (mysql_error());
		while ($fila = mysql_fetch_array($query)) {
			$i =1;
			while ($i <= 12) { // Seleccionamos unicamente las opciones "op" que son distintas de cero es decir que contienen el id de un plato.					
				if (($fila['op' . $i] > 0) && ($fila['op' . $i] == $id_plato)) {
					$vec_info_menu[$indice] =$fila['id'];
					$vec_info_menu[$indice+1] =$fila['titulo'];
					
					$query_1 = mysql_query("SELECT fecha_envio FROM fecha_envio_menu WHERE id_menu = '$fila[id]'", $link) or die (mysql_error());
					if (mysql_num_rows($query_1) > 0) $vec_info_menu[$indice+2] =true;
					else $vec_info_menu[$indice+2] =false;
					
					$indice +=3;
					break 1;
				}
				++$i;
			}
		}
		return $vec_info_menu;
	}
	
	// DEVUELVE VERDADERO SI UN PLATO PERTENECE A UN MENÚ Y FALSO EN CASO CONTRÁRIO.
	function perteneceMenu($id_plato) {
		
		include ("conectar_mysql.php");
		
		$query = mysql_query("select * from info_menu where id_usuario = '$_SESSION[usuariologueado]'", $link) or die (mysql_error());
		while ($fila = mysql_fetch_array($query)) {
			$i =1;
			while ($i <= 12) { // Seleccionamos unicamente las opciones "op" que son distintas de cero es decir que contienen el id de un plato.					
				if (($fila['op' . $i] > 0) && ($fila['op' . $i] == $id_plato)) return true;
				++$i;
			}
		}
		return false;
	}

	if ($_REQUEST['opcion'] == 0) { // INICIALIZA EL NÚMERO DE PAGINAS DEL PAGINATOR.
		$query = mysql_query("select * from info_platos where id_usuario = '$_SESSION[usuariologueado]' AND tipo = '$_REQUEST[tipo_plato]'", $link) or die (mysql_error());
		$num_reg =mysql_num_rows($query);
		mysql_close($link);
		if (($num_reg % $_REQUEST['limite']) == 0) echo ($num_reg / $_REQUEST['limite']);
		else echo (intval(($num_reg / $_REQUEST['limite'])+1));  // Si el resto de la división no es cero.
		return;
	}
	elseif (($_REQUEST['opcion'] == 1) || ($_REQUEST['opcion'] == 3) || ($_REQUEST['opcion'] == 8)) { // CONSULTA POR PLATOS.
		$query = mysql_query("select * from info_platos where id_usuario = '$_SESSION[usuariologueado]' AND tipo = '$_REQUEST[tipo_plato]' 
		ORDER BY fecha_creacion LIMIT $valor_inicio, $_REQUEST[limite]", $link) or die (mysql_error());
	}
	elseif ($_REQUEST['opcion'] == 2) {
		$query = mysql_query("select * from info_platos where id_usuario = '$_SESSION[usuariologueado]' AND tipo = '$_REQUEST[tipo_plato]' 
		ORDER BY fecha_creacion DESC LIMIT $valor_inicio, $_REQUEST[limite]", $link) or die (mysql_error());
	}
	elseif (($_REQUEST['opcion'] == 4) || ($_REQUEST['opcion'] == 10)) { // ELIMINAR PLATO.
		if ($_REQUEST['opcion'] == 4) {
			$query = mysql_query("select ruta_img from info_platos where id = '$_REQUEST[id]'", $link) or die (mysql_error());
			$ruta_img =mysql_fetch_array($query);
			unlink($ruta_img['ruta_img']);	// Elimina el plato de la carpeta.
			
			mysql_query("delete from info_platos where id = '$_REQUEST[id]'") or die (mysql_error());
			$query = mysql_query("select * from info_platos where id_usuario = '$_SESSION[usuariologueado]' AND tipo = '$_REQUEST[tipo_plato]' 
			ORDER BY fecha_creacion LIMIT $valor_inicio, $_REQUEST[limite]", $link) or die (mysql_error());
		}
		elseif (($_REQUEST['opcion'] == 10) && (perteneceMenu($_REQUEST['id_plato']))) { // COMPRUEBA QUE EL PLATO NO PERTENEZCA A NINGÚN MENÚ ANTES DE ELIMINARLO.
			echo "true";
			return;
		}
		else { // EL PLATO NO PERTENECE A UN MENÚ.
			echo "false";
			return;
		}
	}
	elseif (($_REQUEST['opcion'] == 5) || ($_REQUEST['opcion'] == 11)) { // ACTUALIZAR Y COPIAR PLATO. 
		
		if ($_REQUEST['opcion'] == 5) mysql_query("UPDATE info_platos SET nombre='$_REQUEST[nombre_plato]', descripcion='$_REQUEST[descripcion_plato]' where id = '$_REQUEST[id]'", $link) or die (mysql_error());
		elseif (($_REQUEST['opcion'] == 11) && (!(isset($_FILES['archivo']['name'])))) {  // Si es copiar un plato.			
			$fecha_creacion =date('d-m-Y');
			mysql_query("insert into info_platos (id_usuario, nombre, tipo, descripcion, fecha_creacion) values ('$_SESSION[usuariologueado]', 
			'$_REQUEST[nombre_plato_copia]', '$_REQUEST[tipo_plato]', '$_REQUEST[descripcion_plato_copia]', '$fecha_creacion')", $link) or die (mysql_error());
			$id_insert =mysql_insert_id(); // Función que devuelve el id del ultimo insert.
			
			$query = mysql_query("select ruta_img from info_platos where id = '$_REQUEST[id]'", $link) or die (mysql_error());
			$fila = mysql_fetch_array($query);
		
			$ruta_img_copia ="";			
			$ruta_img_copia .=$_SESSION['nick_usuario'] . "/" . $_REQUEST['tipo_plato'] .  "/" . $id_insert . '.jpg'; // Se añade la extension.
			mysql_query("UPDATE info_platos SET ruta_img='$ruta_img_copia' where id = '$id_insert'", $link) or die (mysql_error());

			copy ($fila['ruta_img'], $_SESSION['nick_usuario'] . '/' . $_REQUEST['tipo_plato'] . '/' . $id_insert . '.jpg');
		}
		
		if (isset($_FILES['archivo']['name']) && ($_FILES['archivo']['size']) > 0) {
			if (($_FILES["archivo"]["type"] == "image/gif") || 
			($_FILES["archivo"]["type"] == "image/jpeg") || 
			($_FILES["archivo"]["type"] == "image/pjpeg") ||
			($_FILES["archivo"]["type"] == "image/png")) { 
				if (file_exists($_SESSION['nick_usuario'] . "/" . $_REQUEST['tipo_plato'] . "/" . $_REQUEST['nombre_archivo'])) {
					$vec_res =array();
					$vec_res['sucess'] =false;
					$vec_res['error'] ='0';
					echo json_encode($vec_res);
					return;
				}
				
				if ($_REQUEST['opcion'] == 5) {
					$query = mysql_query("select ruta_img from info_platos where id = '$_REQUEST[id]'", $link) or die (mysql_error());
					$ruta_img =mysql_fetch_array($query);
					unlink($ruta_img['ruta_img']);	// Elimina el plato de la carpeta.
					
					$ruta_img =$_SESSION['nick_usuario'] . "/" . $_REQUEST['tipo_plato'] . "/" . $_REQUEST['nombre_archivo'];
					mysql_query("UPDATE info_platos SET ruta_img='$ruta_img' where id = '$_REQUEST[id]'", $link) or die (mysql_error());
				}
				elseif ($_REQUEST['opcion'] == 11) {
					$fecha_creacion =date('d-m-Y');
					$ruta_img =$_SESSION['nick_usuario'] . "/" . $_REQUEST['tipo_plato'] . "/" . $_REQUEST['nombre_archivo'];
					mysql_query("insert into info_platos (id_usuario, nombre, tipo, descripcion, ruta_img, fecha_creacion) values ('$_SESSION[usuariologueado]', 
					'$_REQUEST[nombre_plato_copia]', '$_REQUEST[tipo_plato]', '$_REQUEST[descripcion_plato_copia]', '$ruta_img', '$fecha_creacion')", $link) or die (mysql_error());
				}
				
				move_uploaded_file($_FILES["archivo"]["tmp_name"], $_SESSION['nick_usuario'] . "/" . $_REQUEST['tipo_plato'] . "/" . $_REQUEST['nombre_archivo']);
			}
			else { // Archivo no permitido.
				$vec_res =array();
				$vec_res['sucess'] =false; 
				$vec_res['error'] ='1';
				echo json_encode($vec_res);
				return;
			}
		}
		
		$query = mysql_query("select * from info_platos where id_usuario = '$_SESSION[usuariologueado]' AND tipo = '$_REQUEST[tipo_plato]' 
		ORDER BY fecha_creacion LIMIT $valor_inicio, $_REQUEST[limite]", $link) or die (mysql_error());
	}
	elseif ($_REQUEST['opcion'] == 6) { // MUESTRA DENTRO DEL FORMULARIO ACTUALIZAR DATOS "BOTON EDITAR" EL VALOR ACTUAL ALMACENADO EN LA BASE DE DATOS.		
		$query = mysql_query("select nombre, descripcion, ruta_img from info_platos where id = '$_REQUEST[id]'", $link) or die (mysql_error());
		$fila = mysql_fetch_array($query);
		
		echo '{"nombre_plato" : "'. $fila['nombre'] .'", 
			"descripcion" : "'. $fila['descripcion'] .'",
			"ruta_img" : "'. $fila['ruta_img'] .'"
		}'; 
		mysql_close($link);
		return;
	}
	elseif ($_REQUEST['opcion'] == 7) { // BUSCA LOS PLATOS EN LA BASE DE DATOS SEGÚN UN NOMBRE.
		$query = mysql_query("SELECT * FROM info_platos WHERE id_usuario = '$_SESSION[usuariologueado]' AND tipo = '$_REQUEST[tipo_plato]' 
		AND INSTR (nombre, '$_REQUEST[inputbuscar]')", $link) or die (mysql_error());
	}
	elseif ($_REQUEST['opcion'] == 9) { // PARA OBTENER LA RUTA Y EL NOMBRE DEL PLATO PARA CONSTRUIR EL MENÚ.
		$query = mysql_query("SELECT nombre, ruta_img FROM info_platos WHERE id = '$_REQUEST[id]'") or die (mysql_error());
		$fila = mysql_fetch_array($query);
		mysql_close($link);
		
		echo '{"nombre_plato" : "'. $fila['nombre'] .'", 
			"ruta_img" : "'. $fila['ruta_img'] .'"
		}'; 
		return;
	}
	
	$vec_res =array();
	while ($fila = mysql_fetch_array($query)) {
		$vec_info_menu =infoMenu($fila['id']);
		
		$vec_res[] =array(
			"id"             => $fila['id'],
			"nombre_plato"   => $fila['nombre'],
			"tipo_plato"     => $fila['tipo'],
			"descripcion"    => $fila['descripcion'],
			"ruta_img"       => $fila['ruta_img'],
			"fecha_creacion" => $fila['fecha_creacion'],
			"vec_info_menu" => $vec_info_menu
		);
	}
	
	// CONSULTA ORDENACIÓN DESCENDENTE. La consulta ordenada devuelta se invierte con la función array_reverse para mostrar los elementos en sentido inverso.
	//if ($_REQUEST['opcion'] == 2) $vec_res =array_reverse($vec_res); // Devuelve un array invertido.
	
	$vec_res['sucess'] =true;
	echo json_encode($vec_res);
	mysql_close($link);
?>