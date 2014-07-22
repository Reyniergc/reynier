<?php
	// Se usa en la pagina 4 y 5.
	echo '<div id="dialog-form" title="Editar plato">'; 
		echo '<form>'; 
			echo '<fieldset>'; 
				echo '<label for="nombre_plato">Nombre del plato</label>';
				echo '<input type="text" id="nombre_plato" class="text ui-widget-content ui-corner-all" />';
				echo '<p id="small-text1"></p>';
				
				echo '<label for="descripcion_plato">Descripción del plato</label>'; 
				echo '<textarea rows=8 cols=80 class="input-xxlarge text ui-widget-content ui-corner-all" id="descripcion_plato"></textarea>';
				
				echo '<br><br><label for="archivo"><i class="icon-picture"></i> Seleccione un plato:</label>';
				echo '<input type="file" name="archivo" id="archivo" />';
				echo '<input type="hidden" value="5" name="numeropagina" id="numeropagina" />';
				echo '<p id="error_archivo"></p>';
		
				echo '<ul class="thumbnails">';
					echo '<li class="span2">';
						echo '<div class="thumbnail" id="modal_img"></div>';
					echo '</li>';
				echo '</ul>';
			echo '</fieldset>';  
		echo '</form>'; 
	echo '</div>';
	
	echo '<div id="dialog-form_2" title="Copiar plato">'; 
		echo '<form>'; 
			echo '<fieldset>'; 
				echo '<label for="nombre_plato_copia">Nombre:</label>';
				echo '<input type="text" id="nombre_plato_copia" class="text ui-widget-content ui-corner-all" />';
				echo '<p id="small-text2"></p>';
				
				echo '<label for="descripcion_plato_copia">Descripción:</label>'; 
				echo '<textarea rows=8 cols=80 class="input-xxlarge text ui-widget-content ui-corner-all" id="descripcion_plato_copia"></textarea>';
				
				echo '<br><br><label for="archivo_copia"><i class="icon-picture"></i> Seleccione un plato:</label>';
				echo '<input type="file" name="archivo" id="archivo_copia" />';
				echo '<input type="hidden" value="5" name="numeropagina" id="numeropagina" />';
				echo '<p id="error_archivo1"></p>';
				
				echo '<ul class="thumbnails">';
					echo '<li class="span2">';
						echo '<div class="thumbnail" id="modal_img_copia"></div>';
					echo '</li>';
				echo '</ul>';
			echo '</fieldset>';  
		echo '</form>'; 
	echo '</div>';
	
	// Se usa en la pagina 4 y 5 cuando COPIAMOS.
	echo '<div id="dialog-form_1" title="Información del menú">'; 
		echo '<div id="error">';
			echo '<label class="control-label" for="datepicker"><p class="text-menu">Introduzca la fecha de publicación si desea volver a publicar el menú:</p></label>';
			echo '<p><input type="text" class="input-large" id="datepicker" /></p>';
			echo '<span class="help-inline"><p id="texto_error_dialog"></p></span>';
		echo '</div>';
		
		echo '<div class="row-fluid">';
			echo '<table class="table span4">';
				echo '<thead>';
					echo '<tr>';
						echo '<p class="text-menu">Primeros platos</p>';
					echo '</tr>'; 
				echo '</thead>';
				echo '<tbody id="primeros_platos_menu">';
					echo '<tr id="fila_primeros_platos_menu"></tr>';
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
		
		echo '<div class="row-fluid">';
			echo '<table class="table span4">';
				echo '<thead>';
					echo '<tr>';
						echo '<p class="text-menu">Segundos platos</p>';
					echo '</tr>'; 
				echo '</thead>';
				echo '<tbody id="segundos_platos_menu">';
					echo '<tr id="fila_segundos_platos_menu"></tr>';
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
		
		echo '<div class="row-fluid">';
			echo '<table class="table span4">';
				echo '<thead>';
					echo '<tr>';
						echo '<p class="text-menu">Postres</p>';
					echo '</tr>'; 
				echo '</thead>';
				echo '<tbody id="postres_menu">';
					echo '<tr id="fila_postres_menu"></tr>';
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
		
		echo '<div class="row-fluid">';
			echo '<table class="table span4">';
				echo '<thead>';
					echo '<tr>';
						echo '<p class="text-menu">Bebidas</p>';
					echo '</tr>'; 
				echo '</thead>';
				echo '<tbody id="bebidas_menu">';
					echo '<tr id="fila_bebidas_menu"></tr>';
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
		
		echo '<p class="text-menu">Fechas de publicaciones del menú:</p>';
		echo '<p id="fechas_publicaciones" style="font-weight: bold;"></p>';
	echo '</div>';
	
	// Se usa en la pagina 4 y 5 cuando EDITAMOS.
	echo '<div id="dialog-form_3" title="Información del menú">'; 		
		echo '<div class="row-fluid">';
			echo '<table class="table span4">';
				echo '<thead>';
					echo '<tr>';
						echo '<p class="text-menu">Primeros platos</p>';
					echo '</tr>'; 
				echo '</thead>';
				echo '<tbody id="primeros_platos_menu_editar">';
					echo '<tr id="fila_primeros_platos_menu_editar"></tr>';
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
		
		echo '<div class="row-fluid">';
			echo '<table class="table span4">';
				echo '<thead>';
					echo '<tr>';
						echo '<p class="text-menu">Segundos platos</p>';
					echo '</tr>'; 
				echo '</thead>';
				echo '<tbody id="segundos_platos_menu_editar">';
					echo '<tr id="fila_segundos_platos_menu_editar"></tr>';
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
		
		echo '<div class="row-fluid">';
			echo '<table class="table span4">';
				echo '<thead>';
					echo '<tr>';
						echo '<p class="text-menu">Postres</p>';
					echo '</tr>'; 
				echo '</thead>';
				echo '<tbody id="postres_menu_editar">';
					echo '<tr id="fila_postres_menu_editar"></tr>';
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
		
		echo '<div class="row-fluid">';
			echo '<table class="table span4">';
				echo '<thead>';
					echo '<tr>';
						echo '<p class="text-menu">Bebidas</p>';
					echo '</tr>'; 
				echo '</thead>';
				echo '<tbody id="bebidas_menu_editar">';
					echo '<tr id="fila_bebidas_menu_editar"></tr>';
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
	echo '</div>';
?>