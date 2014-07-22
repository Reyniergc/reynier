<?php

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
	
	// Para que no de error si la variable elecciónn no esta definida y pueda mostrar el contenido inicial de la pagina.
	if (!isset($_REQUEST['eleccion'])) $eleccion = 0;
    else $eleccion = $_REQUEST['eleccion'];
	
    switch ($eleccion) { // Aquí generamos lo que va a salir del contenido php.
		case 0: { // Aquí se muestra el contenido inicial de la pagina.
			echo '<center>Información inicial de la pagina</center>';
			break;
		}
		case 1: { // VER PERFIL.
			echo '<div class="span4">';
				require ("conectar_mysql.php");
				$query = mysql_query("select nombre_empresa, nick, email, telefono from usuarios where id = '$_SESSION[usuariologueado]'", $link); 
				$Fila = mysql_fetch_array($query);
				mysql_close($link);
				echo '<legend>Ver perfil:</legend>';
				echo '<p><b>Información de la empresa:</b></p>';
				echo '<p><img src="img/casa-icono.png"><b> Nombre Empresa:</b> ' . $Fila['nombre_empresa'] . '</p>';
				echo '<p><i class="icon-user"></i> <b>Usuario:</b> ' . $Fila['nick'] . '</p>';
				echo '<p><img src="img/email.png"> <b>Email:</b> ' . $Fila['email'] . '</p>';
				echo '<p><img src="img/phone.png"> <b>Telefono:</b> ' . $Fila['telefono'] . '</p>';  
?>
				<div class="form-actions">
					<button style="margin-bottom: 10px;" class="btn offset2" onClick="location.href='aplicaciones.php?eleccion=2'">Editar</button>
				</div>
<?php
			echo '</div>';
			break;
		}
		case 2: { // EDITAR PERFIL.
			require ("conectar_mysql.php");
			$query = mysql_query("select AES_DECRYPT(password, cadena), telefono, email from usuarios where id = '$_SESSION[usuariologueado]'", $link);
			$Fila = mysql_fetch_array($query);
			mysql_close($link);
			echo '<div class="span6">';
				echo '<form class="form-horizontal">';
					echo '<fieldset>';
						echo '<legend>Editar perfil:</legend>';
						echo '<div class="control-group">';
							echo '<div class="controls">';
								echo '<div id="tooltip-right">';
									echo '<label for="input05">Contraseña actual:</label>';
									echo '<input type="password" id="input05" title="Introduzca la contraseña actual para aplicar los cambios." autofocus required/>';
								echo '</div>';
								echo '<p id="small-text10"></p>';
								
								echo '<label  for="input01">Cambiar la contraseña:</label>';
								echo '<input type="password" class="input-large" id="input01" required>';
								echo '<p id="small-text5"></p>';
								
								echo '<label for="input02">Repita la contraseña:</label>';
								echo '<input type="password" class="input-large" id="input02" required>';
								echo '<input type="hidden" id="input06" value="' . $Fila["AES_DECRYPT(password, cadena)"] . '"><br>';
								echo '<p id="small-text7"></p>';
								
								echo '<label for="input03">Telefono:</label>';
								echo '<input type="text" class="input-large" id="input03" value="' . $Fila["telefono"] . '" required><br>';
								echo '<p id="small-text8"></p>';
								
								echo '<label for="input04">Email:</label>';
								echo '<input type="email" class="input-large" id="input04" value="' . $Fila["email"] . '" required>';
								echo '<p id="small-text9"></p>';
								
								echo '<p id="small-text1"></p>';
							echo '</div>';
						echo '</div>';
					echo '</fieldset>';
					
					echo '<div class="form-actions">';
						echo '<button type="submit" class="btn btn-primary" id="Editar_Perfil">Aceptar</button>';
?>
						<input type="button" class="btn btn" value="Cancelar" onClick="location.href='aplicaciones.php?eleccion=1'">
<?php
					echo '</div>';
				echo '</form>';
			echo '</div>';
			break;
		}	
		case 3: { // SUBIR LA INFORMACIÓN DEL PLATO A LA BASE DE DATOS Y AL SERVIDOR.
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
			
			if (isset($_POST['guardar'])) {
				if (is_uploaded_file($_FILES["archivo"]["tmp_name"])) { // Comprobamos que se haya subido un fichero.
					if (($_FILES["archivo"]["type"] == "image/gif") || 
						($_FILES["archivo"]["type"] == "image/jpeg") || 
						($_FILES["archivo"]["type"] == "image/pjpeg") ||
						($_FILES["archivo"]["type"] == "image/png")) {
					
						//Si es que hubo un error en la subida, mostrarlo, de la variable $_FILES podemos extraer el valor de [error], que almacena un valor booleano (1 o 0).
						if ($_FILES["archivo"]["error"] > 0) {
							echo $_FILES["archivo"]["error"] . "<br />";
						} 
						else {
							// Si no hubo ningun error, hacemos otra condición para asegurarnos que el archivo no sea repetido.
							if (file_exists($_SESSION['nick_usuario'] . "/" . $_REQUEST['tipo'] . "/" . stripAccents($_FILES["archivo"]["name"]))) {
								echo $_FILES["archivo"]["name"] . " ya existe.";
							}
							else {						
								require("conectar_mysql.php"); 
								
								$fecha_creacion =date('d-m-Y');
								$ruta_img =$_SESSION['nick_usuario'] . "/" . $_REQUEST['tipo'] . "/" . stripAccents($_FILES["archivo"]["name"]);
								
								mysql_query("insert into info_platos (id_usuario, nombre, tipo, descripcion, ruta_img, fecha_creacion) values ('$_SESSION[usuariologueado]', 
								'$_REQUEST[nombre_plato]', '$_REQUEST[tipo]', '$_REQUEST[descripcion]', '$ruta_img', '$fecha_creacion')", $link) or die (mysql_error());
								mysql_close($link); 
							
								move_uploaded_file($_FILES["archivo"]["tmp_name"], $_SESSION['nick_usuario'] . "/" . $_REQUEST['tipo'] . "/" . stripAccents($_FILES["archivo"]["name"]));
								echo "<p id='small-text3'>Archivo subido correctamente<br/></p>"; 
							}
						}
					}
					else {
						// Si el usuario intenta subir algo que no es una imagen o una imagen que pesa mas de 20 KB mostramos este mensaje
						echo "<p id='small-text2'>Error archivo no permitido</p>";
					}
				}
			}
			echo '<div class="span7">';
?>				
				<legend>Crear plato:</legend>
				<form name="formPlatos" class="form-horizontal" method="post" action="aplicaciones.php?eleccion=3" onsubmit="return altaPlatos()" enctype="multipart/form-data">
					<fieldset>
						<div class="control-group">
							<label class="control-label" for="nombre_plato">Nombre:</label>
							<div class="controls">
								<input type="text" class="input-large" name="nombre_plato" id="nombre_plato" autofocus required>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="filtroSelect">Tipo:</label>
							<div class="controls">
								<select name="tipo" id="filtroSelect">
									<option value="Primer plato">Primer plato</option>
									<option value="Segundo plato">Segundo plato</option>
									<option value="Postre">Postre</option>
									<option value="Bebida">Bebida</option>
								</select>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="descripcion_plato">Descripción:</label>
							<div class="controls">
								<textarea name="descripcion" rows=8 cols=70 class="input-xxlarge" id="descripcion_plato" required></textarea>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="archivo"><i class="icon-picture"></i> Seleccione una imagen:</label> 
							<div class="controls">
								<input type="file" name="archivo" id="archivo" />
							</div>
						</div>
						
						<div class="form-actions">
							<input type="submit" class="btn btn-primary" name="guardar" value="Guardar">
							<input type="button" class="btn" value="Cancelar" onClick="location.href='aplicaciones.php?eleccion=3'">
						</div>
					</fieldset>
				</form>  
			</div>
<?php		
			break;
		}
		case 4: { // GESTIONAR PLATOS.
			echo '<div class="row-fluid">';	
				require("codigo_html.php");
				require("conectar_mysql.php");
				
				$query = mysql_query("select * from info_platos where id_usuario = '$_SESSION[usuariologueado]' AND tipo = 'Primer plato' 
				ORDER BY fecha_creacion LIMIT 0, 4", $link) or die (mysql_error());
				
				echo '<div class="span8">';		
					echo '<legend>Gestionar platos:</legend>';					
					echo '<table id="tabla" class="table table-striped">';
						echo '<thead>';
							echo '<tr>';
								echo '<th class="centrartextoTabla">';
									echo '<select name="tipo" style="width: 100%;" id="filtroSelect">';
										echo '<option value="Primer plato">Primer plato</option>';
										echo '<option value="Segundo plato">Segundo plato</option>';
										echo '<option value="Postre">Postre</option>';
										echo '<option value="Bebida">Bebida</option>';
									echo '</select>';
								echo '</th>';
								echo '<th >';
									echo '<form>';
										echo '<input class="search" type="text" name="inputbuscar" id="inputbuscar" style="float: right; border-radius: 8px;" title="Buscar" placeholder="Buscar Plato...">';
									echo '</form>';
								echo '</th>';
							echo '</tr>';
							
							echo '<tr>';
								echo '<th style="visibility: hidden;"></th>';
								echo '<th style="top; visibility: hidden;"></th>';
								echo '<th class="centrartextoTabla" style="vertical-align: middle;" id="ordenacion">Fecha de creación<img src="img/ordenar_desc.png" id="ordenacionDesc" title="Ordenar descendente"></th>';
							echo '</tr>'; 
						echo '</thead>';
						echo '<tbody id="cuerpoTabla">';
							while ($fila = mysql_fetch_array($query)) {
								echo '<tr>';
									echo '<td width ="20%">';
										echo '<ul class="thumbnails">';
											echo '<li class="span2">';
												echo '<div class="thumbnail">';
													echo '<img src="' . $fila['ruta_img'] . '">';
												echo '</div>'; 
											echo '</li>'; 
										echo '</ul>';
										// Si el plato no pertenece a un menú lo podemos EDITAR en caso contrario no se podrá EDITAR pero si COPIARLO.
										if (!perteneceMenu($fila['id'])) echo '<button value="' . $fila['id'] . '" class="btn btn-small mostrar_dialogo">Editar</button>';
										else echo '<button value="' . $fila['id'] . '" class="btn btn-small mostrar_dialogo_1">Copiar</button>';
										echo ' <button value="' . $fila['id'] . '" class="btn btn-small btn-danger eliminar">Eliminar</button>';
									echo '</td>';
									
									echo '<td>';
										echo '<p><b>Tipo:</b> ' . $fila['tipo']. '</p>';
										echo '<p><b>Nombre: </b>' . $fila['nombre'] . '</p>';
										echo '<p><b>Descripción: </b>' . $fila['descripcion'] . '</p>';
										
										$pertenece_menu =false;
										$mostrar =true;
										$query_1 = mysql_query("select * from info_menu where id_usuario = '$_SESSION[usuariologueado]'", $link) or die (mysql_error());
										while ($fila_1 = mysql_fetch_array($query_1)) {
											$i =1;
											while ($i <= 12) { 
												if ($fila_1['op' . $i] > 0) { // Seleccionamos unicamente las opciones "op" que son distintas de cero es decir que contienen el id de un plato.												
													if ($fila_1['op' . $i] == $fila['id']) {
														if ($mostrar) {
															echo '<p><b>Incluido en los siguientes menús: </b></p>';
															$mostrar =false;
															$pertenece_menu =true;
														}
														
														// Se comprueba si el menú tiene o no fecha de envío para saber si debemos mostrar un boton de copiar o editar en el DIALOG.
														$query_2 = mysql_query("SELECT fecha_envio FROM fecha_envio_menu WHERE id_menu = '$fila_1[id]'", $link) or die (mysql_error());
														if (mysql_num_rows($query_2) > 0) {
?>
															<a href="#" class="mostrar_dialogo_menu_pagina_copiar" onClick="recibeIdMenu('<?php echo $fila_1['id'] ?>')" ><?php echo $fila_1['titulo'] ?></a> 
<?php		
														}
														else {
?>
															<a href="#" class="mostrar_dialogo_menu_pagina_editar" onClick="recibeIdMenu('<?php echo $fila_1['id'] ?>')" ><?php echo $fila_1['titulo'] ?></a> 
<?php
														}
														break 1;
													}
												}
												++$i;
											}
										}
										if (!$pertenece_menu) echo '<p><b>No está incluido en ningún menú.</b></p>';
									echo '</td>';
									
									echo '<td width="28%" class="centrartextoColumna">';
										echo '<p>' . $fila['fecha_creacion'] . '</p>'; 
									echo '</td>';
								echo '</tr>';
							}
						echo '</tbody>';
					echo '</table>';
				
					// Para mostrar el paginador.
					echo '<div class="row-fluid">';	
						echo '<div id="example"></div>';
					echo '</div>';
					echo '<input type="hidden" value="4" name="numeropagina" id="numeropagina" />';
				echo '</div>'; //</div class="span8">
			echo '</div>';	//</div class="row-fluid">
			mysql_close($link);
			break;
		}
		case 5: { // CREAR MENÚ.
			echo '<div class="row-fluid">';
				require("codigo_html.php");
				require ("conectar_mysql.php");
				$query = mysql_query("select * from info_platos where id_usuario = '$_SESSION[usuariologueado]' AND tipo = 'Primer plato' 
				ORDER BY fecha_creacion LIMIT 0, 4", $link) or die (mysql_error());
				
				echo '<div class="span7">';
					
					if (isset($_REQUEST['mostrar_titulo'])) echo '<legend>Copiar menú:</legend>';
					elseif (isset($_REQUEST['id_menu'])) echo '<legend>Editar menú:</legend>';
					else echo '<legend>Crear nuevo menú:</legend>';
					
					echo '<table id="tabla" class="table table-striped">';
						echo '<thead>';
							echo '<tr>';
								echo '<th class="centrartextoTabla">';
									echo '<select name="tipo" style="width: 100%;" id="filtroSelect">';
										echo '<option value="Primer plato">Primer plato</option>';
										echo '<option value="Segundo plato">Segundo plato</option>';
										echo '<option value="Postre">Postre</option>';
										echo '<option value="Bebida">Bebida</option>';
									echo '</select>';
								echo '</th>';
								echo '<th >';
									echo '<form>';
										echo '<input class="search" type="text" name="inputbuscar" id="inputbuscar" style="float: right; border-radius: 8px;" title="Buscar" placeholder="Buscar Plato...">';
									echo '</form>';
								echo '</th>';
							echo '</tr>';
							
							echo '<tr>';
								echo '<th style="visibility: hidden;"></th>';
								echo '<th style="top; visibility: hidden;"></th>';
								echo '<th class="centrartextoTabla" style="vertical-align: middle;" id="ordenacion">Fecha de creación<img src="img/ordenar_desc.png" id="ordenacionDesc" title="Ordenar descendente"></th>';
							echo '</tr>';  
						echo '</thead>';
						echo '<tbody id="cuerpoTabla">';
							while ($fila = mysql_fetch_array($query)) {
								echo '<tr>';
									echo '<td width ="20%">';
										echo '<ul class="thumbnails">';
											echo '<li class="span2">';
												echo '<div class="thumbnail">';
													echo '<img src="' . $fila['ruta_img'] . '">';
												echo '</div>'; 
											echo '</li>'; 
										echo '</ul>';
										// Si el plato no pertenece a un menú lo podemos EDITAR en caso contrario no se podrá EDITAR pero si COPIARLO.
										if (!perteneceMenu($fila['id'])) echo '<button value="' . $fila['id'] . '" class="btn btn-small mostrar_dialogo">Editar</button>';
										else echo '<button value="' . $fila['id'] . '" class="btn btn-small mostrar_dialogo_1">Copiar</button>';
										echo ' <button value="' . $fila['id'] . '" class="btn btn-small btn-danger eliminar">Eliminar</button>';						
									echo '</td>';
									
									echo '<td>';
										echo '<p><b>Tipo:</b> ' . $fila['tipo']. '</p>';
										echo '<p><b>Nombre: </b>' . $fila['nombre'] . '</p>';
										echo '<p><b>Descripción: </b>' . $fila['descripcion'] . '</p>';
										
										$pertenece_menu =false;
										$mostrar =true;
										$query_1 = mysql_query("select * from info_menu where id_usuario = '$_SESSION[usuariologueado]'", $link) or die (mysql_error());
										while ($fila_1 = mysql_fetch_array($query_1)) {
											$i =1;
											while ($i <= 12) { 
												if ($fila_1['op' . $i] > 0) { // Seleccionamos unicamente las opciones "op" que son distintas de cero es decir que contienen el id de un plato.												
													if ($fila_1['op' . $i] == $fila['id']) {
														if ($mostrar) {
															echo '<p><b>Pertenece a los siguientes menús: </b></p>';
															$mostrar =false;
															$pertenece_menu =true;
														}
														
														// Se comprueba si el menú tiene o no fecha de envío para saber si debemos mostrar un boton de copiar o editar en el DIALOG.
														$query_2 = mysql_query("SELECT fecha_envio FROM fecha_envio_menu WHERE id_menu = '$fila_1[id]'", $link) or die (mysql_error());
														if (mysql_num_rows($query_2) > 0) {
?>
															<a href="#" class="mostrar_dialogo_menu_pagina_copiar" onClick="recibeIdMenu('<?php echo $fila_1['id'] ?>')" ><?php echo $fila_1['titulo'] ?></a> 
<?php		
														}
														else {
?>
															<a href="#" class="mostrar_dialogo_menu_pagina_editar" onClick="recibeIdMenu('<?php echo $fila_1['id'] ?>')" ><?php echo $fila_1['titulo'] ?></a> 
<?php
														}													
														break 1;
													}
												}
												++$i;
											}
										}
										if (!$pertenece_menu) echo '<p><b>No está incluido en ningún menú.</b></p>';
									echo '</td>';
									
									echo '<td width="28%" class="centrartextoColumna">';
										echo '<p>' . $fila['fecha_creacion'] . '</p>';
										//PARA SABER SI DEBEMOS INICIALIZAR EL MENÚ CON LOS DATOS ENVIADOS DESDE LA PAGINA "GESTIONAR MENÚS".
										if (isset($_REQUEST['id_menu'])) { 
											$query_1 = mysql_query("select * from info_menu where id = '$_REQUEST[id_menu]'", $link);
											$fila_platos_menu = mysql_fetch_array($query_1);
											
											$i =1;
											while ($i <= 12) { 
												if ($fila_platos_menu['op' . $i] > 0) { // Seleccionamos unicamente las opciones "op" que son distintas de cero es decir que contienen el id de un plato.
													if ($fila_platos_menu['op' . $i] == $fila['id']) {
														echo '<button id="idbutton' . $fila['id'] . '" value="' . $fila['id'] . '" class="btn btn-info btn-mini select_img" disabled>Añadir al <br>menú</button>';
														break;
													}
												}
												++$i;
											}
											if ($i > 12) { // PARA SABER SI UN PLATO NO PERTENECE A UN MENÚ Y PONER EL BOTON HABILITADO.
												echo '<button id="idbutton' . $fila['id'] . '" value="' . $fila['id'] . '" class="btn btn-info btn-mini select_img">Añadir al <br>menú</button>';
											}
										}
										else echo '<button id="idbutton' . $fila['id'] . '" value="' . $fila['id'] . '" class="btn btn-info btn-mini select_img">Añadir al <br>menú</button>';
									echo '</td>';
								echo '</tr>';
							}
						echo '</tbody>';
					echo '</table>';
					
					// Para mostrar el paginador.
					echo '<div class="row-fluid">';	
						echo '<div id="example"></div>';
					echo '</div>';
				echo '</div>';	// </div class="span7">
					
				// COLUMNA RESERVADA PARA COLOCAR EL MENÚ QUE SERÁ CREADO.
				echo '<form>'; 
					echo '<div class="span4">'; 
						echo '<p style="font-size: 1.2em; font-weight: bold;">Seleccione los platos de la izquierda
						y añadalos al menú</p>';
						echo '<hr>';
						
						echo '<fieldset>';
							echo '<div class="control-group">';
								echo '<div class="controls">';
									echo '<div id="tooltip-right">';
										echo '<label for="titulo">Título del menú:</label>';
										echo '<input type="text" class="input-large" id="titulo" />';
									echo '</div>';
								echo '</div>';
							echo '</div>';
						echo '</fieldset>';		
						
						echo '<div class="row-fluid">';
							echo '<table class="table table-striped span4">';
								echo '<thead>';
									echo '<tr>';
										echo '<p class="text-menu">Primeros platos</p>';
									echo '</tr>'; 
								echo '</thead>';
								echo '<tbody>'; // PONER AQUI UN IF QUE PREGUNTE SI EL MENU LLEGA INICIALIZADO O NO.
									echo '<tr id="primer_plato"></tr>';
								echo '</tbody>';
							echo '</table>';
						echo '</div>';
						
						echo '<div class="row-fluid">';
							echo '<table class="table table-striped span4">';
								echo '<thead>';
									echo '<tr>';
										echo '<p class="text-menu">Segundos platos</p>';
									echo '</tr>'; 
								echo '</thead>';
								echo '<tbody>';
									echo '<tr id="segundo_plato"></tr>';
								echo '</tbody>';
							echo '</table>';
						echo '</div>';
						
						echo '<div class="row-fluid">';	
							echo '<table class="table table-striped span4">';
								echo '<thead>';
									echo '<tr>';
										echo '<p class="text-menu">Postres</p>';
									echo '</tr>'; 
								echo '</thead>';
								echo '<tbody>';
									echo '<tr id="postre"></tr>';
								echo '</tbody>';
							echo '</table>';
						echo '</div>';
						
						echo '<div class="row-fluid">';	
							echo '<table class="table table-striped span4">';
								echo '<thead>';
									echo '<tr>';
										echo '<p class="text-menu">Bebidas</p>';
									echo '</tr>'; 
								echo '</thead>';
								echo '<tbody>';
									echo '<tr id="bebida"></tr>';
								echo '</tbody>';
							echo '</table>';	//class="input-xxlarge
						echo '</div>';
						
						echo '<fieldset>';
							echo '<div class="control-group">';
								echo '<div class="controls">';
									echo '<div id="tooltip-right">';
										//echo '<label for="titulo">Título del menú:</label>';
										//echo '<input type="text" class="input-large" id="titulo" />';
										
										if (!isset($_REQUEST['mostrar_titulo'])) { // Solo se muestra estos botones en el caso de que no sea copiar un menú.
											echo '<label for="datepicker1">Fecha de publicación:</label>';
											echo '<p><input type="text" class="input-large" id="datepicker1" /></p>';
										}
									echo '</div>';
								echo '</div>';
							echo '</div>';
							
							if (!isset($_REQUEST['mostrar_titulo'])) { // Solo se muestra estos botones en el caso de que no sea copiar un menú.
								echo '<input type="submit" id="exportarMenu" value="Publicar menú" class="btn btn-small">';
								echo ' <input type="submit" id="guardarMenu" value="Guardar menú" class="btn btn-small">';	
							}
							else echo '<input type="submit" id="copiarMenu" value="Copiar menú" class="btn btn-small">';
							
							if (isset($_REQUEST['id_menu'])) echo ' <button class="btn btn-small" id="paginaAnterior">Cancelar</button>';
						echo '</fieldset>';					
					echo '</div>'; // </div span4">
				echo '</form>'; 
			echo '</div>';	// </div row-fluid">
			
			if (isset($_REQUEST['mostrar_titulo'])) echo '<input type="hidden" value="false" id="mostrar_titulo" />';
			if (isset($_REQUEST['id_menu'])) { // Para poder acceder al id del menú desde JQUERY.
				echo '<script>';
					echo 'window.onload = init(' . $_REQUEST['id_menu'] . ')';
				echo '</script>';
				echo '<input type="hidden" value="' . $_REQUEST['id_menu'] . '" id="id_menu" />';
			}
			
			mysql_close($link);
			break;
		}
		case 6: { // GESTIONAR MENÚS.
			require ("conectar_mysql.php");
			require ("codigo_html.php");
			
			$query = mysql_query("SELECT * FROM info_menu WHERE id_usuario = '$_SESSION[usuariologueado]' LIMIT 0, 4", $link) or die (mysql_error());
			echo '<div class="row-fluid">';	
				//echo '<div class="span9">';
				echo '<div class="span10">';
					echo '<legend>Gestionar menús:</legend>';
					echo '<table id="tabla" class="table table-striped">';
						echo '<thead>';
							echo '<tr>';
								echo '<th>';
									echo '<form>';
										echo '<input class="search" type="text" name="inputbuscar" id="inputbuscarMenu" style="float: right; border-radius: 8px;" title="Buscar" placeholder="Buscar Menú...">';
									echo '</form>';
								echo '</th>';
							echo '</tr>';							
							
							echo '<tr>';
								echo '<th class="centrartextoTabla" style="vertical-align: top;">Título</th>';
								echo '<th class="centrartextoTabla" style="vertical-align: top;">Fecha de última publicación</th>';
								echo '<th class="centrartextoTabla" style="vertical-align: top;">Fecha de última modificación</th>';
								echo '<th class="centrartextoTabla" style="vertical-align: top;">Primer Plato</th>';
								echo '<th class="centrartextoTabla" style="vertical-align: top;">Segundo Plato</th>';
								echo '<th class="centrartextoTabla" style="vertical-align: top;">Postre</th>';
								echo '<th class="centrartextoTabla" style="vertical-align: top;">Bebida</th>';
							echo '</tr>'; 
						echo '</thead>';
						echo '<tbody id="cuerpotablaListaMenus">';
							while ($fila = mysql_fetch_array($query)) {
								$query_1 = mysql_query("SELECT fecha_envio FROM fecha_envio_menu WHERE id_menu = '$fila[id]'", $link) or die (mysql_error());
								echo '<tr>';
									echo '<td width ="20%">';
										echo '<p>' . $fila['titulo'] . '</p>';
										
										if (mysql_num_rows($query_1) == 0) { // MUESTRA EL BUTTON EDITAR SI EL MENÚ NO HA SIDO EXPORTADO.
?>
											<button class="btn btn-small" onClick="location.href='aplicaciones.php?eleccion=5&id_menu=<?php echo $fila['id'] ?>'">Editar</button>
<?php
										} // SI HA SIDO EXPORTADO MUESTRA EL BUTTON VISUALIZAR
										else echo '<button value="' . $fila['id'] . '" class="btn btn-small btn-info mostrar_dialogo_gestion_menu">Visualizar</button> ';
										echo '<button value="' . $fila['id'] . '" class="btn btn-small btn-danger eliminarMenu">Eliminar</button>'; 
									echo '</td>';
									
									echo '<td width ="20%" class="centrartextoColumna">';
										if (mysql_num_rows($query_1) > 0) {
											while ($fecha = mysql_fetch_array($query_1)) $ultima_fecha_publicacion =$fecha['fecha_envio'];
											echo '<p id="' . $fila['id'] . '">' . $ultima_fecha_publicacion . '</p>';
										}
										else echo "<p>No ha sido publicado</p>";
									echo '</td>';
									
									echo '<td width ="20%" class="centrartextoColumna">'; 
										echo '<p>' . $fila['fecha_ultima_modificacion'] . '</p>';
									echo '</td>';
									
									$i =1;
									$indice =1;
									$array_tipo_plato = array(1 => 'Primer plato', 2 => 'Segundo plato', 3 => 'Postre', 4 => 'Bebida');
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
										
										if ($j > 12) { // En caso de que no exista el tipo de plato.
											echo '<td class="centrartextoColumna">';  
												echo '<p>Sin elección<p>';
											echo '</td>';
										}
										else {
											echo '<td class="centrartextoColumna">';
												echo '<ul class="thumbnails">';
													echo '<li class="span1" style="width: 60px;">';
														echo '<div class="thumbnail">';
															echo '<img src="' . $fila1['ruta_img'] . '">'; 
														echo '</div>'; 
													echo '</li>'; 
												echo '</ul>';
											echo '</td>';
										}
										++$indice;
										++$i; 
									}
								echo '</tr>';
							}
						echo '</tbody>';
					echo '</table>';
				
					// Para mostrar el paginador.
					echo '<div class="row-fluid">';	
						echo '<div id="example"></div>';
					echo '</div>';
					echo '<input type="hidden" value="6" id="numeropagina" />';
				echo '</div>'; //</div class="span9">
				
			echo '</div>';	//</div class="row-fluid">
			mysql_close($link);
			break;
		}
	}	
?>