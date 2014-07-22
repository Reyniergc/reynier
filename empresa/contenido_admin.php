<?php
	// Para que no de error si la variable elección no esta definida y pueda mostrar el contenido inicial de la pagina.
	if (!isset($_REQUEST['eleccion'])) $eleccion = 0;
    else $eleccion = $_REQUEST['eleccion']; 
	
    switch ($eleccion) {
		case 0: { // ALTA DE EMPRESAS SOLO DISPONIBLE SI EL USUARIO LOGUEADO ES EL ADMINISTRADOR.
			echo '<div class="span6">';
				echo '<form class="form-horizontal">';
					echo '<fieldset>';
						echo '<legend>Registro de usuarios</legend>';
						echo '<div class="control-group">';
							echo '<div class="controls">';
								echo '<label for="nombre_empresa">Introduzca el nombre de la empresa.</label>';
								echo '<input type="text" class="input-xlarge" id="nombre_empresa">';
								
								echo '<label for="nick_usuario">Introduzca el usuario.</label>';
								echo '<input type="text" class="input-xlarge" id="nick_usuario">';
								echo '<p id="small-text10"></p>';
								
								echo '<label for="contraseña1">Introduzca la contraseña.</label>';
								echo '<input type="password" class="input-xlarge" id="contraseña1">';
								echo '<p id="small-text5"></p>';
								
								echo '<label for="contraseña2">Repita la contraseña.</label>';
								echo '<input type="password" class="input-xlarge" id="contraseña2">';
								echo '<p id="small-text7"></p>';
								
								echo '<label for="email">Email de la empresa.</label>';
								echo '<input type="email" class="input-xlarge" id="email">';
								echo '<p id="small-text8"></p>';
								
								echo '<label for="telefono">Telefono de la empresa.</label>';
								echo '<input type="text" class="input-xlarge" id="telefono">';
								echo '<p id="small-text9"></p>';
								
								echo '<p id="small-text1"></p>';
							echo '</div>';
						echo '</div>';
					echo '</fieldset>';
					echo '<div class="form-actions">';
						echo '<button class="btn btn-primary offset2" id="altaEmpresa">Guardar datos</button>';
					echo '</div>';
				echo '</form>';
			echo '</div>';
			break;
		}
		case 1: { // BAJA DE EMPRESAS SOLO DISPONIBLE SI EL USUARIO LOGUEADO ES ADMINISTRADOR.
			echo '<div id="dialog-form-administracion" title="Actualizar información de los usuarios">'; 
				echo '<form>'; 
					echo '<fieldset>'; 
						echo '<label for="nombre_empresa">Nombre de la empresa</label>';
						echo '<input type="text" id="nombre_empresa" class="text ui-widget-content ui-corner-all" />';
						
						echo '<label for="email">Email de la empresa</label>'; 
						echo '<input type="text" id="email" class="text ui-widget-content ui-corner-all" />';	
						echo '<p id="small-text8"></p>';
						
						echo '<label for="telefono">Telefono de la empresa</label>'; 
						echo '<input type="text" id="telefono" class="text ui-widget-content ui-corner-all" />';
						echo '<p id="small-text9"></p>';
					echo '</fieldset>';  
				echo '</form>'; 
			echo '</div>';
			
			include ("conectar_mysql.php");
			$query =mysql_query("select id, nombre_empresa, nick, email, telefono, estado from usuarios", $link);
			echo '<legend>Lista de usuarios</legend>';
			echo '<div class="span11">';
				echo '<table class="table table-striped table table-bordered">';
					echo '<thead>';
						echo '<tr>';
							echo '<th class="textocabeceraTabla">';
								echo "Nombre";
							echo '</th>';
							
							echo '<th class="textocabeceraTabla">';
								echo "Nombre de usuario";
							echo '</th>';
							
							echo '<th class="textocabeceraTabla">';
								echo "Email";
							echo '</th>';
							
							echo '<th class="textocabeceraTabla">';
								echo "Telefono";
							echo '</th>';
							
							echo '<th class="textocabeceraTabla">';
								echo "Estado";
							echo '</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody >';
						while ($fila =mysql_fetch_array($query)) {
							echo '<tr>';
								echo '<td class="centrartextoTabla">';
									echo $fila['nombre_empresa'];
								echo '</td>';
								
								echo '<td class="centrartextoTabla">';
									echo $fila['nick'];
								echo '</td>';
								
								echo '<td class="centrartextoTabla">';
									echo $fila['email'];
								echo '</td>';
								
								echo '<td class="centrartextoTabla">';
									echo $fila['telefono'];
								echo '</td>';
								
								echo '<td class="centrartextoTabla">'; 
									if ($fila['estado'] == 0) echo 'Deshabilitado';
									else echo 'Habilitado';
								echo '</td>';
								
								echo '<td class="centrartextoTabla">'; 
									echo '<i class="icon-pencil" title="Editar" style="margin-top: 6px;"></i> <button value="' .  $fila['id'] . '"class="btn btn-mini editar">Editar</button>';
								echo '</td>';
								
								echo '<td class="centrartextoTabla">'; 
									if ($fila['estado'] == 0) echo '<img src="img/add_user.png" title="Habilitar" style="margin-top: 5px;"> <button value="' .  $fila['id'] . '"class="btn btn-mini btn btn-success habilitar">Habilitar</button>';
									else echo '<img src="img/delete_user.png" title="Deshabilitar" style="margin-top: 5px;"> <button value="' .  $fila['id'] . '"class="btn btn-mini btn btn-warning deshabilitar">Deshabilitar</button>';
								echo '</td>';
							
								echo '<td class="centrartextoTabla">'; 
									echo '<img src="img/delete_user.png" title="Eliminar usuario" style="margin-top: 5px;"> <button value="' .  $fila['id'] . '"class="btn btn-mini btn btn-danger bajaEmpresa">Eliminar</button>';
								echo '</td>';
							echo '</tr>';
						}
					echo '</tbody>';
				echo '</table>';
			echo '</div>';
			mysql_close($link); 
			break;
		}
	}
?>