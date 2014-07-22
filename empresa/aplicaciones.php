<?php   
session_start(); 
?>
<!DOCTYPE html> 
<html lang="es"> 
<head> 
	<title>Marketing Digital Networks</title> 
	<meta charset="utf-8"/> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Marketing Digital Network" />

	<link rel="stylesheet" href="css/bootstrap.css" type="text/css"/>	
	<link rel="stylesheet" href="css/bootstrap-responsive.css" type="text/css"/>
	
	<!--<script src="js/bootstrap.js"></script>-->
	<script src="js/jquery.js"></script>
	
    <script src="js/jquery-1.9.1.min.js"></script> 
	
	<!---------------------------- LIBRERIA PARA USAR JQUERY-UI. ------------------------------>
	<!--<link type="text/css" href="css/ui-lightness/jquery-ui-1.9.2.custom.css" rel="Stylesheet" />  -->
	<link type="text/css" rel="stylesheet" href="css/custom-theme/jquery-ui-1.10.0.custom.css">
	<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="js/funcionesJquery.js"></script>
	
	
	<!-- LIBRERIA PARA USAR GALERIA DE IMAGENES BOOTSTRAP. -->
	<link rel="stylesheet" href="css/blueimp-gallery.min.css">
	<!-- ------------------------------------------------- -->
	
	<link rel="stylesheet" type="text/css" href="css/mi_estilo.css"/>
	<link href="css/enhanced.css" rel="Stylesheet" type="text/css">
	
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	<script type="text/javascript">

		// Cuando pinchamos en el button "Editar" nos envia a la pagina eleccion 5 con los datos inicializados.
		function editarMenu(id_menu) {
			location.href='aplicaciones.php?eleccion=5&id_menu=' + id_menu;
		}
		
		var id_menu_1 =0;
		function recibeIdMenu(id_menu) {
			id_menu_1 =id_menu;
		}

		var pagina_actual =1, limite =4;
		function mostrar_paginador(datos) { // RECIBE LOS DATOS PARA INICIALIZAR EL PAGINADOR.
			
			var options = {
				currentPage: 1,
				totalPages: datos,
				alignment: "center",
				onPageClicked: function(e, originalEvent, type, page) {
					e.stopImmediatePropagation();
					var currentTarget = $(e.currentTarget);
					var pages = currentTarget.bootstrapPaginator("getPages");
					$('#alert-content').text("Page item clicked, current page: " + pages.current);
			
					setTimeout(function() {
						currentTarget.bootstrapPaginator("show", page);
						var pages = currentTarget.bootstrapPaginator("getPages");
						var numeropagina =$("#numeropagina").val();
						pagina_actual =pages.current; // Devuelve el valor de la pagina actual.

						if ((numeropagina == 4) || (numeropagina == 5)) {
							$("#ordenacionDesc").remove(); 
							$("#ordenacionAsce").remove();
							var img = document.createElement("img"); // Se vuelve a poner la imagen inicial de ordenación descendente.
							img.setAttribute("id", "ordenacionDesc");
							img.setAttribute("src", "img/ordenar_desc.png");
							img.setAttribute("title", "Ordenar descendente");
							$('#ordenacion').append(img);
		
							var tipo_plato = $("#filtroSelect option:selected").val(), mostrar_titulo =$("#mostrar_titulo").val();
							$.ajax({
								async: false,
								type: "POST",
								dataType: "json",
								contentType: "application/x-www-form-urlencoded",
								url: "filtro_consulta.php",
								data: {tipo_plato: tipo_plato, pagina_actual: pagina_actual, limite: limite, opcion: 8},
								success: function(datos) {
									if (datos.sucess) {
										var tabla = "";
										$.each(datos, function (index, record) { 
											if ($.isNumeric(index)) {							
												tabla +='<tr>';
													tabla +='<td width ="20%">';
														tabla +='<ul class="thumbnails">';
															tabla +='<li class="span2">';
																tabla +='<div class="thumbnail">';
																	tabla +='<img src="' + record.ruta_img + '">';
																tabla +='</div>';
															tabla +='</li>';
														tabla +='</ul>';
														// Si el plato no pertenece a un menú lo podemos EDITAR en caso contrario no se podrá EDITAR pero si COPIARLO.
														if (record.vec_info_menu.length == 0) tabla +='<button value="' + record.id + '" class="btn btn-small mostrar_dialogo">Editar</button>';
														else tabla +='<button value="' + record.id + '" class="btn btn-small mostrar_dialogo_1">Copiar</button>';
														tabla +=' <button value="' + record.id + '" class="btn btn-danger btn-small eliminar">Eliminar</button>';
													tabla +='</td>';
												
													tabla +='<td>';
														tabla +='<p><b>Tipo:</b> ' + record.tipo_plato + '</p>';
														tabla +='<p><b>Nombre: </b>' + record.nombre_plato + '</p>';
														tabla +='<p><b>Descripción:</b> ' + record.descripcion + '</p>';
														
														if (record.vec_info_menu.length == 0) tabla += '<p><b>No está incluido en ningún menú.</b></p>';
														else {
															tabla += '<p><b>Incluido en los siguientes menús: </b></p>';
															for (var i=0; i < record.vec_info_menu.length; i +=3) {
																if (record.vec_info_menu[i+2] == true) tabla += ' <a href="#" class="mostrar_dialogo_menu_pagina_copiar" onClick="recibeIdMenu(' + record.vec_info_menu[i] + ')">' + record.vec_info_menu[i+1] + '</a>';
																else tabla += ' <a href="#" class="mostrar_dialogo_menu_pagina_editar" onClick="recibeIdMenu(' + record.vec_info_menu[i] + ')">' + record.vec_info_menu[i+1] + '</a>';
															}
														}
													tabla +='</td>';
												
													tabla +='<td width="28%" class="centrartextoColumna">';
														tabla +='<p>' + record.fecha_creacion + '</p>';
														if (numeropagina == 5) {
															if (platoSeleccionado(record.id)) {
																tabla +='<button id="idbutton' + record.id + '" value="' + record.id + '" class="btn btn-info btn-mini select_img" disabled>Añadir al <br>menú</button>';
															}
															else tabla +='<button id="idbutton' + record.id + '" value="' + record.id + '" class="btn btn-info btn-mini select_img">Añadir al <br>menú</button>';
														}
													tabla +='</td>'; 
												tabla +='</tr>';
											}
										});
										document.getElementById("cuerpoTabla").innerHTML =tabla;
									}
								},
								timeout: 1000, // Tiempo de espera por la respuesta del servidor.
							});
						}
						if (numeropagina == 6) {
							$.ajax({
								async: false,
								type: "POST",
								dataType: "json",
								contentType: "application/x-www-form-urlencoded",
								url: "gestionar_menu.php",
								data: {pagina_actual: pagina_actual, limite: limite, opcion: 6},
								success: function(datos) {
									if (datos.sucess) {
										var tabla = "";
										$.each(datos, function (index, record) { 
											if ($.isNumeric(index)) {							
												tabla +='<tr>';
													tabla +='<td width ="20%">';
														tabla +='<p>' + record.titulo_menu + '</p>';
														if (record.editar) tabla +='<button class="btn btn-small" onClick="editarMenu(' + record.id_menu + ')">Editar</button> ';
														else tabla +='<button value="' + record.id_menu + '" class="btn btn-small btn-info mostrar_dialogo_gestion_menu">Visualizar</button> ';
														tabla +='<button value="' + record.id_menu + '" class="btn btn-small btn-danger eliminarMenu">Eliminar</button>';
													tabla +='</td>';
													
													tabla +='<td width ="20%" class="centrartextoColumna">';
														if (record.editar) tabla +="<p>No ha sido publicado</p>"; 
														else tabla += '<p id="' + record.id_menu + '">' + record.fecha_envio_menu + '</p>';
													tabla +='</td>';
													
													tabla +='<td width ="20%" class="centrartextoColumna">';
														tabla += '<p>' + record.fecha_ultima_modificacion + '</p>'; 
													tabla +='</td>';
													
													if (record.ruta_primer_plato == "Sin eleccion") {
														tabla += '<td class="centrartextoColumna">';  
															tabla += '<p>Sin elección<p>';
														tabla += '</td>';
													}
													else {
														tabla +='<td class="centrartextoColumna">';
															tabla +='<ul class="thumbnails">';
																tabla +='<li class="span1" style="width: 60px;">';
																	tabla +='<div class="thumbnail">';
																		tabla +='<img src="' + record.ruta_primer_plato + '">';
																	tabla +='</div>';
																tabla +='</li>';
															tabla +='</ul>';
														tabla +='</td>';
													}
													
													if (record.ruta_segundo_plato == "Sin eleccion") {
														tabla += '<td class="centrartextoColumna">';  
															tabla += '<p>Sin elección<p>';
														tabla += '</td>';
													}
													else {
														tabla +='<td class="centrartextoColumna">';
															tabla +='<ul class="thumbnails">';
																tabla +='<li class="span1" style="width: 60px;">';
																	tabla +='<div class="thumbnail">';
																		tabla +='<img src="' + record.ruta_segundo_plato + '">';
																	tabla +='</div>';
																tabla +='</li>';
															tabla +='</ul>';
														tabla +='</td>';
													}
													
													if (record.ruta_postre == "Sin eleccion") {
														tabla += '<td class="centrartextoColumna">';  
															tabla += '<p>Sin elección<p>';
														tabla += '</td>';
													}
													else {
														tabla +='<td class="centrartextoColumna">';
															tabla +='<ul class="thumbnails">';
																tabla +='<li class="span1" style="width: 60px;">';
																	tabla +='<div class="thumbnail">';
																		tabla +='<img src="' + record.ruta_postre + '">';
																	tabla +='</div>';
																tabla +='</li>';
															tabla +='</ul>';
														tabla +='</td>';
													}
													
													if (record.ruta_bebida == "Sin eleccion") {
														tabla += '<td class="centrartextoColumna">';  
															tabla += '<p>Sin elección<p>';
														tabla += '</td>';
													}
													else {
														tabla +='<td class="centrartextoColumna">';
															tabla +='<ul class="thumbnails">';
																tabla +='<li class="span1" style="width: 60px;">';
																	tabla +='<div class="thumbnail">';
																		tabla +='<img src="' + record.ruta_bebida + '">';
																	tabla +='</div>';
																tabla +='</li>';
															tabla +='</ul>';
														tabla +='</td>';
													}
												tabla +='</tr>';
											}
										});
										document.getElementById("cuerpotablaListaMenus").innerHTML =tabla;
									}
								},
								timeout: 1000, // Tiempo de espera por la respuesta del servidor.
							});
						}
					}, 1000); 
				}
			}
		
			$('#example').bootstrapPaginator(options);
		}
	
		window.onload=iniciar_paginador;
		function iniciar_paginador() {
		
			var tipo_plato = $("#filtroSelect option:selected").val(), numeropagina =$("#numeropagina").val();
			if ((numeropagina == 4) || (numeropagina == 5)) {
				$.ajax({
					async: false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					url: "filtro_consulta.php",
					data: {tipo_plato: tipo_plato, limite: limite, opcion: 0},
					success: mostrar_paginador,
					timeout: 1000, // Tiempo de espera por la respuesta del servidor.
					error: problemasServidor
				});
			}
			if (numeropagina == 6) { // PARA EL PAGINADOR DE LA PAGINA GESTIONAR MENÚS.
				$.ajax({
					async: false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					url: "gestionar_menu.php",
					data: {limite: limite, opcion: 0},
					success: mostrar_paginador,
					timeout: 1000, // Tiempo de espera por la respuesta del servidor.
					error: problemasServidor
				});
			}
		}
		
		$(function() {
			//Array para dar formato en español.
			$.datepicker.regional['es'] = 
			{
				closeText: 'Cerrar', 
				prevText: 'Previo', 
				nextText: 'Próximo',
				monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
				'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
				monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
				'Jul','Ago','Sep','Oct','Nov','Dic'],
				monthStatus: 'Ver otro mes', yearStatus: 'Ver otro año',
				dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
				dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sáb'],
				dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
				dateFormat: 'dd/mm/yy', firstDay: 0, 
				initStatus: 'Selecciona la fecha', isRTL: false
			};
		 
			$.datepicker.setDefaults($.datepicker.regional['es']);
			$( "#datepicker" ).datepicker({
				showOn: 'focus',
				changeMonth: true,
				minDate: '0d'
			});
			
			$( "#datepicker1" ).datepicker({
				showOn: 'focus',
				changeMonth: true,
				minDate: '0d'
			});
	
			$('#archivo').customFileInput ({
				button_position : 'right'
			});
			
			$('#archivo_copia').customFileInput ({
				button_position : 'right'
			});
			
			$( "#tooltip-right" ).tooltip({
				position: {
					my: "left+15 left",
					at: "right center",
					using: function( position, feedback ) {
						$( this ).css( position );
						$( "<div>" )
						.addClass( "arrow left" )
						.addClass( feedback.vertical )
						.addClass( feedback.horizontal )
						.appendTo( this );
					}
				}
			});
		});
		
		
		function problemasServidor() {
			alert("Error en el servidor.");
		}
		
		/* DIALOGO PARA ACTUALIZAR LA INFORMACIÓN DE LOS PLATOS. */
		var id =0;
		$(function() {			
			function updateTips(t) {
				tips
					.text(t)
					.addClass("ui-state-highlight");
					setTimeout(function() {
					tips.removeClass("ui-state-highlight", 1500);
				}, 500);
			}
	 
			function checkLength(o, n, min, max) {
				if (o.val().length > max || o.val().length < min) {
					o.addClass("ui-state-error");
					updateTips("Length of " + n + " must be between " +
					min + " and " + max + ".");
					return false;
				} else {
					return true;
				}
			}
	 
			function checkRegexp(o, regexp, n) {
				if (!(regexp.test(o.val()))) {
					o.addClass("ui-state-error");
					updateTips(n);
					return false;
				} else {
					return true;
				}
			}
			
			var mensaje_error =2;
			function dibujarTabla1 (datos) {
				if (datos.sucess) {
					var tabla = "";
					$.each(datos, function (index, record) { 
						if ($.isNumeric(index)) {
							tabla +='<tr>';
								tabla +='<td width ="20%">';
									tabla +='<ul class="thumbnails">';
										tabla +='<li class="span2">';
											tabla +='<div class="thumbnail">';
												tabla +='<img src="' + record.ruta_img + '">';
											tabla +='</div>';
										tabla +='</li>';
									tabla +='</ul>';
									// Si el plato no pertenece a un menú lo podemos EDITAR en caso contrario no se podrá EDITAR pero si COPIARLO.
									if (record.vec_info_menu.length == 0) tabla +='<button value="' + record.id + '" class="btn btn-small mostrar_dialogo">Editar</button>';
									else tabla +='<button value="' + record.id + '" class="btn btn-small mostrar_dialogo_1">Copiar</button>';
									tabla +=' <button value="' + record.id + '" class="btn btn-danger btn-small eliminar">Eliminar</button>';
								tabla +='</td>';
							
								tabla +='<td>';
									tabla +='<p><b>Tipo:</b> ' + record.tipo_plato + '</p>';
									tabla +='<p><b>Nombre: </b>' + record.nombre_plato + '</p>';
									tabla +='<p><b>Descripción:</b> ' + record.descripcion + '</p>';
									
									if (record.vec_info_menu.length == 0) tabla += '<p><b>No está incluido en ningún menú.</b></p>';
									else {
										tabla += '<p><b>Incluido en los siguientes menús: </b></p>';
										for (var i=0; i < record.vec_info_menu.length; i +=3) {
											if (record.vec_info_menu[i+2] == true) tabla += ' <a href="#" class="mostrar_dialogo_menu_pagina_copiar" onClick="recibeIdMenu(' + record.vec_info_menu[i] + ')">' + record.vec_info_menu[i+1] + '</a>';
											else tabla += ' <a href="#" class="mostrar_dialogo_menu_pagina_editar" onClick="recibeIdMenu(' + record.vec_info_menu[i] + ')">' + record.vec_info_menu[i+1] + '</a>';
										}
									}
								tabla +='</td>';
							
								tabla +='<td width="28%" class="centrartextoColumna">';
									tabla +='<p>' + record.fecha_creacion + '</p>';
									if (numeropagina == 5) {
										if (platoSeleccionado(record.id)) {
											tabla +='<button id="idbutton' + record.id + '" value="' + record.id + '" class="btn btn-info btn-mini select_img" disabled>Añadir al <br>menú</button>';
										}
										else tabla +='<button id="idbutton' + record.id + '" value="' + record.id + '" class="btn btn-info btn-mini select_img">Añadir al <br>menú</button>';
									}
								tabla +='</td>'; 
							tabla +='</tr>';
						}
					});
					document.getElementById("cuerpoTabla").innerHTML =tabla;
				}
				else mensaje_error =datos.error;
			}
			
			$("#dialog-form").dialog({
				autoOpen: false,
				height: 600,
				width: 600,
				modal: true,
				buttons: {
					"Actualizar": function() {  
						var tipo_plato = $("#filtroSelect option:selected").val();
						var nombre_plato =$("#nombre_plato").val(), numeropagina =$("#numeropagina").val();
						var descripcion_plato =$("#descripcion_plato").val(), mostrar_titulo =$("#mostrar_titulo").val();
						
						if (nombre_plato == "") {
							$("#small-text1").html('* Por favor introduzca el nombre del plato.');
							return false;
						}
						
						if (confirm("¿Esta seguro que desea aplicar los cambios?")) {
							var file = new FormData();
							
							// Elimina los caracteres especiales de los nombres de los archivos. Primero comprobamos que se ha seleccionado un archivo.
							if (($('#archivo').val() != "") && ($('#archivo')[0].files[0].size > 0)) {
								var nombre_archivo =$('#archivo')[0].files[0].name;
								nombre_archivo =nombre_archivo.replace(/[áàâãªä]/gi, "a");
								nombre_archivo =nombre_archivo.replace(/[ÁÀÂÃÄ]/gi, "A");
								nombre_archivo =nombre_archivo.replace(/[ÍÌÎÏ]/gi, "I");
								nombre_archivo =nombre_archivo.replace(/[íìîï]/gi, "i");
								nombre_archivo =nombre_archivo.replace(/[éèêë]/gi, "e");
								nombre_archivo =nombre_archivo.replace(/[ÉÈÊË]/gi, "E");
								nombre_archivo =nombre_archivo.replace(/[óòôõöº]/gi, "o");
								nombre_archivo =nombre_archivo.replace(/[ÓÒÔÕÖ]/gi, "O");
								nombre_archivo =nombre_archivo.replace(/[úùûü]/gi, "u");
								nombre_archivo =nombre_archivo.replace(/[ÚÙÛÜ]/gi, "U");
								nombre_archivo =nombre_archivo.replace(/ç/gi, "c");
								nombre_archivo =nombre_archivo.replace(/Ç/gi, "C");
								nombre_archivo =nombre_archivo.replace(/Ñ/gi, "N");
								nombre_archivo =nombre_archivo.replace(/ñ/gi, "n");
								nombre_archivo =nombre_archivo.replace(/Ý/gi, "Y");
								nombre_archivo =nombre_archivo.replace(/ý/gi, "y");
								nombre_archivo =nombre_archivo.replace(/ÿ/gi, "y");
								
								file.append('nombre_archivo', nombre_archivo);
								file.append('archivo', $('#archivo')[0].files[0]);
							}
							
							file.append('nombre_plato', nombre_plato);
							file.append('descripcion_plato', descripcion_plato);
							file.append('id', id);
							file.append('tipo_plato', tipo_plato);
							file.append('pagina_actual', pagina_actual);
							file.append('limite', limite);
							file.append('opcion', 5);
							
							$.ajax({
								async: false,
								type: "POST",
								dataType: "json",
								contentType: false, // "contentType" SE DEBE PONER A FALSO CUANDO SE ENVIAN ARCHIVOS.
								url: "filtro_consulta.php",
								data: file,
								processData: false,
								success: dibujarTabla1,
								timeout: 1000, // Tiempo de espera por la respuesta del servidor.
								error: problemasServidor
							});
							
							if (mensaje_error == 1) $("#error_archivo").html('* Solo se aceptan archivos con los siguientes formatos "gif, jpeg, pjpeg, png".');
							else {
								if (mensaje_error == 0) $("#error_archivo").html('* La imagen descriptiva del plato ya existe, Por favor introduza una distinta.');
								else $(this).dialog("close");
							}
							mensaje_error =2;
						}
					},
					Cancelar: function() {
						$(this).dialog("close");
					}
				},
			});
			
			// Detecta si hemos pusado en editar.
			$('#cuerpoTabla').on('click', '.mostrar_dialogo', function() {
			
				id =$(this).val();
				$.ajax({
					async: false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					url: "filtro_consulta.php",
					data: {id: id, opcion: 6},
					success: function(datos) {
						var dato =JSON.parse(datos);
						$("#nombre_plato").val(dato.nombre_plato);
						$("#descripcion_plato").val(dato.descripcion);
						document.getElementById("modal_img").innerHTML ='<img src="' + dato.ruta_img + '">';  
					},
					timeout: 1000, // Tiempo de espera por la respuesta del servidor.
					error: problemasServidor
				});
				$("#small-text1").html('');
				$("#error_archivo").html('');
				$( "#dialog-form" ).dialog( "open" );
			});
			
			$("#dialog-form_2").dialog({
				autoOpen: false,
				height: 600,
				width: 600,
				modal: true,
				buttons: {
					"Copiar": function() {
						var tipo_plato = $("#filtroSelect option:selected").val();
						var nombre_plato_copia =$("#nombre_plato_copia").val();
						var descripcion_plato_copia =$("#descripcion_plato_copia").val();
						
						if (nombre_plato_copia == "") {
							$("#small-text2").html('* Por favor introduzca el nombre del plato.');
							return false;
						}
						
						if (confirm("¿Está seguro que desea copiar este plato?")) {
							var file = new FormData();
							
							// Elimina los caracteres especiales de los nombres de los archivos. Primero comprobamos que se ha seleccionado un archivo.
							if (($('#archivo_copia').val() != "") && ($('#archivo_copia')[0].files[0].size > 0)) {
								var nombre_archivo =$('#archivo_copia')[0].files[0].name;
								nombre_archivo =nombre_archivo.replace(/[áàâãªä]/gi, "a");
								nombre_archivo =nombre_archivo.replace(/[ÁÀÂÃÄ]/gi, "A");
								nombre_archivo =nombre_archivo.replace(/[ÍÌÎÏ]/gi, "I");
								nombre_archivo =nombre_archivo.replace(/[íìîï]/gi, "i");
								nombre_archivo =nombre_archivo.replace(/[éèêë]/gi, "e");
								nombre_archivo =nombre_archivo.replace(/[ÉÈÊË]/gi, "E");
								nombre_archivo =nombre_archivo.replace(/[óòôõöº]/gi, "o");
								nombre_archivo =nombre_archivo.replace(/[ÓÒÔÕÖ]/gi, "O");
								nombre_archivo =nombre_archivo.replace(/[úùûü]/gi, "u");
								nombre_archivo =nombre_archivo.replace(/[ÚÙÛÜ]/gi, "U");
								nombre_archivo =nombre_archivo.replace(/ç/gi, "c");
								nombre_archivo =nombre_archivo.replace(/Ç/gi, "C");
								nombre_archivo =nombre_archivo.replace(/Ñ/gi, "N");
								nombre_archivo =nombre_archivo.replace(/ñ/gi, "n");
								nombre_archivo =nombre_archivo.replace(/Ý/gi, "Y");
								nombre_archivo =nombre_archivo.replace(/ý/gi, "y");
								nombre_archivo =nombre_archivo.replace(/ÿ/gi, "y");
								
								file.append('nombre_archivo', nombre_archivo);
								file.append('archivo', $('#archivo_copia')[0].files[0]);
							}
							
							file.append('nombre_plato_copia', nombre_plato_copia);
							file.append('descripcion_plato_copia', descripcion_plato_copia);
							file.append('id', id);
							file.append('tipo_plato', tipo_plato);
							file.append('pagina_actual', pagina_actual);
							file.append('limite', limite);
							file.append('opcion', 11);
							
							$.ajax({
								async: false,
								type: "POST",
								dataType: "json",
								contentType: false, // "contentType" SE DEBE PONER A FALSO CUANDO SE ENVIAN ARCHIVOS.
								url: "filtro_consulta.php",
								data: file,
								processData: false,
								success: dibujarTabla1,								
								timeout: 1000, // Tiempo de espera por la respuesta del servidor.
								error: problemasServidor
							});
							
							if (mensaje_error == 1) $("#error_archivo1").html('* Solo se aceptan archivos con los siguientes formatos "gif, jpeg, pjpeg, png".');
							else {
								if (mensaje_error == 0) $("#error_archivo1").html('* La imagen descriptiva del plato ya existe, Por favor introduza una distinta.');
								else $(this).dialog("close");
							}
							
							mensaje_error =2;
							location.reload();
						}
					},
					Cancelar: function() {
						$(this).dialog("close");
					}
				},
			});
			
			// Detecta el evento que nos permite copiar un plato.
			$('#cuerpoTabla').on('click', '.mostrar_dialogo_1', function() {
			
				id =$(this).val();
				$.ajax({
					async: false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					url: "filtro_consulta.php",
					data: {id: id, opcion: 6},
					success: function(datos) {
						var dato =JSON.parse(datos);
						$( "#descripcion_plato_copia" ).val(dato.descripcion);
						document.getElementById("modal_img_copia").innerHTML ='<img src="' + dato.ruta_img + '">';  
					},
					timeout: 1000, // Tiempo de espera por la respuesta del servidor.
					error: problemasServidor
				});
				$("#small-text2").html('');
				$( "#dialog-form_2" ).dialog( "open" );
			});
			
			// DIALOG PARA VISUALIZAR UN MENÚ CON BOTON COPIAR.
			var id_menu =0;
			$("#dialog-form_1").dialog({
				
				autoOpen: false,
				height: 640,
				width: 800,
				modal: true,
				buttons: {
					"Publicar": function() { 
						if (!$("#datepicker").val()) {
							$("#error").addClass("control-group error");
							document.getElementById("texto_error_dialog").innerHTML ="Error! debe introducir la fecha de publicación.";
							return false;
						}
						
						var fecha =$("#datepicker").val();
						if (confirm("¿Está seguro que desea volver a publicar el menú?")) {
							$("#error").removeClass("control-group error");
							
							$.ajax({
								async: false,
								type: "POST",
								dataType: "html",
								contentType: "application/x-www-form-urlencoded",
								url: "crear_menu.php",
								data: {id_menu: id_menu, fecha: fecha, opcion: 5},
								timeout: 1000,
								error: problemasServidor
							});
							$(this).dialog("close");
							$("p#" + id_menu).html($("#datepicker").val());
						}
					},
					"Copiar": function() {
						location.href='aplicaciones.php?eleccion=5&id_menu=' + id_menu + '&mostrar_titulo=false';
					},
					Cancelar: function() {
						$("#error").removeClass("control-group error");
						$(this).dialog("close");
					},
				},
			});
			
			// DIALOG PARA VISUALIZAR UN MENÚ CON BOTON EDITAR.
			$("#dialog-form_3").dialog({
				
				autoOpen: false,
				height: 640,
				width: 800,
				modal: true,
				buttons: {
					"Editar": function() {
						location.href='aplicaciones.php?eleccion=5&id_menu=' + id_menu;
					},
					Cancelar: function() {
						$("#error").removeClass("control-group error");
						$(this).dialog("close");
					},
				},
			});
			
			// ESTÁ FUNCIÓN INICIALIZA EL DIALOG CON LOS PLATOS DE UN MENÚ.
			function muestraMenuDialog(id_menu, opcion) {
			
				$.ajax({
					async: false,
					type: "POST",
					dataType: "json",
					contentType: "application/x-www-form-urlencoded",
					url: "gestionar_menu.php",
					data: {id_menu: id_menu, opcion: 2},
					success: function(datos) {
						if (datos.sucess) {
							if (opcion == 0) { // COPIAR.
								$('#fila_primeros_platos_menu').remove(); 
								var tr1 = document.createElement("tr");
								tr1.setAttribute("id", "fila_primeros_platos_menu");
								
								$('#fila_segundos_platos_menu').remove(); 
								var tr2 = document.createElement("tr");
								tr2.setAttribute("id", "fila_segundos_platos_menu");
								
								$('#fila_postres_menu').remove(); 
								var tr3 = document.createElement("tr");
								tr3.setAttribute("id", "fila_postres_menu");
								
								$('#fila_bebidas_menu').remove(); 
								var tr4 = document.createElement("tr");
								tr4.setAttribute("id", "fila_bebidas_menu");
							}
							else { // EDITAR.
								$('#fila_primeros_platos_menu_editar').remove(); 
								var tr1 = document.createElement("tr");
								tr1.setAttribute("id", "fila_primeros_platos_menu_editar");
								
								$('#fila_segundos_platos_menu_editar').remove(); 
								var tr2 = document.createElement("tr");
								tr2.setAttribute("id", "fila_segundos_platos_menu_editar");
								
								$('#fila_postres_menu_editar').remove(); 
								var tr3 = document.createElement("tr");
								tr3.setAttribute("id", "fila_postres_menu_editar");
								
								$('#fila_bebidas_menu_editar').remove(); 
								var tr4 = document.createElement("tr");
								tr4.setAttribute("id", "fila_bebidas_menu_editar");
							}
							
							var tabla1 = "", tabla2 = "", tabla3 = "", tabla4 = "";
							$.each(datos, function (index, record) { 
								if ($.isNumeric(index)) {
									if (record.tipo_plato == "Primer plato") { // PRIMER PLATO
										var td = document.createElement("td"); // Se crea el elemento columna.
										
										var ul = document.createElement("ul");
										ul.setAttribute("class", "thumbnails");
										
										var li = document.createElement("li");
										li.setAttribute("class", "span2");
										
										var div = document.createElement("div");
										div.setAttribute("class", "thumbnail");
										div.innerHTML ='<img src="' + record.ruta_img + '">' + '<h5><center>' + record.nombre_plato + '</center></h5>';
										
										li.appendChild(div);
										ul.appendChild(li);
										td.appendChild(ul);
										tr1.appendChild(td);
									}
									else {
										if (record.tipo_plato == "Segundo plato") { // SEGUNDO PLATO
											var td = document.createElement("td"); // Se crea el elemento columna.
											
											var ul = document.createElement("ul");
											ul.setAttribute("class", "thumbnails");
											
											var li = document.createElement("li");
											li.setAttribute("class", "span2");
											
											var div = document.createElement("div");
											div.setAttribute("class", "thumbnail");
											div.innerHTML ='<img src="' + record.ruta_img + '">' + '<h5><center>' + record.nombre_plato + '</center></h5>';
											
											li.appendChild(div);
											ul.appendChild(li);
											td.appendChild(ul);
											tr2.appendChild(td);
										}
										else {
											if (record.tipo_plato == "Postre") { //POSTRE
												var td = document.createElement("td"); // Se crea el elemento columna.
											
												var ul = document.createElement("ul");
												ul.setAttribute("class", "thumbnails");
												
												var li = document.createElement("li");
												li.setAttribute("class", "span2");
												
												var div = document.createElement("div");
												div.setAttribute("class", "thumbnail");
												div.innerHTML ='<img src="' + record.ruta_img + '">' + '<h5><center>' + record.nombre_plato + '</center></h5>';
												
												li.appendChild(div);
												ul.appendChild(li);
												td.appendChild(ul);
												tr3.appendChild(td);
											}
											else { // BEBIDA
												var td = document.createElement("td"); // Se crea el elemento columna.
											
												var ul = document.createElement("ul");
												ul.setAttribute("class", "thumbnails");
												
												var li = document.createElement("li");
												li.setAttribute("class", "span2");
												
												var div = document.createElement("div");
												div.setAttribute("class", "thumbnail");
												div.innerHTML ='<img src="' + record.ruta_img + '">' + '<h5><center>' + record.nombre_plato + '</center></h5>';
												
												li.appendChild(div);
												ul.appendChild(li);
												td.appendChild(ul);
												tr4.appendChild(td);
											}
										}
									}
									
								} 
							});
							
							if (opcion == 0) { // COPIAR.
								$('#primeros_platos_menu').append(tr1);
								$('#segundos_platos_menu').append(tr2);
								$('#postres_menu').append(tr3);
								$('#bebidas_menu').append(tr4);
							
								var ristra_fechas ="";
								for (var i =0; i < datos.vec_fechas.length; i++) ristra_fechas += datos.vec_fechas[i] + " / ";
								document.getElementById("fechas_publicaciones").innerHTML =ristra_fechas;
							}
							else { // EDITAR.
								$('#primeros_platos_menu_editar').append(tr1);
								$('#segundos_platos_menu_editar').append(tr2);
								$('#postres_menu_editar').append(tr3);
								$('#bebidas_menu_editar').append(tr4);
							}
						}
					},
					timeout: 1000, // Tiempo de espera por la respuesta del servidor.
					error: problemasServidor
				});
			}
			
			$('#cuerpoTabla').on('click', '.mostrar_dialogo_menu_pagina_copiar', function() {
				
				// Asignamos a la variable "id_menu" del dialog-form_1 el valor del menú que queremos copiar para enviarlo por la URL al pinchar sobre copiar.
				id_menu =id_menu_1; 
				muestraMenuDialog(id_menu_1, 0); // INICIALIZAMOS EL DIALOG CON LOS PLATOS INDICANDO QUE VAMOS A COPIAR.
				$( "#dialog-form_1" ).dialog( "open" );
				
			});
			
			$('#cuerpoTabla').on('click', '.mostrar_dialogo_menu_pagina_editar', function() {
				
				id_menu =id_menu_1; 
				muestraMenuDialog(id_menu_1, 1); // INICIALIZAMOS EL DIALOG CON LOS PLATOS INDICANDO QUE VAMOS EDITAR.
				$( "#dialog-form_3" ).dialog( "open" );
				
			});
			
			// Muestra el dialogo hubicado en la pagina GESTIONAR MENÚS pagina 6.
			$('#cuerpotablaListaMenus').on('click', '.mostrar_dialogo_gestion_menu', function() {
				id_menu =$(this).val();
				muestraMenuDialog(id_menu, 0);	// INICIALIZAMOS EL DIALOG CON LOS PLATOS.				
				$( "#dialog-form_1" ).dialog( "open" );
			});
		});
	</script>
	
	<!------------------------ LIBRERIA PARA USAR BOOTSTRAP PAGINATOR. -------------------------->
	<script src="js/bootstrap-paginator.min.js"></script> 
</head>
<body> 
<?php
	if (isset($_SESSION['nick_usuario']) && ($_SESSION['nick_usuario'] != 'admin')) { // Para que solo sea accesible para usuarios logueados.
?>	
		<div class="container-fluid">	
			<div class="page-header">
				<div class="row-fluid">
					<div class="span3">
						<!--<a href="index.php"><img src="img/logo.jpg" alt="Logo Marketing Digital Networks" style="width: 50%"></a>-->
						<a href="index.php"><img src="img/ulpgc.png" alt="Logo Marketing Digital Networks" style="width: 70%"></a>
					</div>
					 
					<div class="span5">
						<ul class="nav nav-pills">
							<li><a href="index.php"><h4>Inicio</h4></a></li>
							<li class="active"><a href="que_ofrecemos.php"><h4>Aplicaciones</h4></a></li>
						</ul>
					</div>
					
					<div class="span4">
<?php
						if (!isset($_SESSION['nick_usuario'])) {
?>	
							<form class="well form-inline" style="width: 70%">
								<input type="text" class="input-small" id="campo1" placeholder="Empresa"> 
								<input type="password" class="input-small" id="campo2" placeholder="Password">
								<button type="submit" class="btn" id="Loguin">Entrar</button> 
								<br/><p id="small-text1"></p> <!--Aquí se muestra los mensajes enviados desde javaScript.-->
								<a data-toggle="modal" href="#myModal_1">Recuperar Contraseña</a>  
							</form>
<?php
						}
						else { // Muestra la bienvenida y un botom para salir cuando el usuario esta logueado. width: 70%;  style="height: 40%"
							echo '<span id="logoff2">' . 'Bienvenido, ' . '</span>' . '<span id="logoff1">' . $_SESSION["nick_usuario"] .  '</span>' . '<span id="logoff"><a href=logoff.php><img src="img/log_off_16.png" title="Salir"></a></span>';
							echo '<br/>';
							// Para que solo el administrador del sistema pueda acceder a la pagina de registro de usuarios.
							if ($_SESSION["nick_usuario"] == "admin") {
								echo '<br/><span class="small-text2"><a href="administracion.php">Gestionar empresas</a></span>';
							}
						}
?>
					</div>
				</div> <!-- row-fluid-->
			</div> <!-- FIN PAGE HEADER -->
		
			<div class="row-fluid">	
				<div id="zona-navegacion" class="span2 sidebar-nav">
					<ul id="menuLateral" class="nav nav-tabs nav-stacked"> 
						<li class="dropdown"><a class="dropdown-toggle" href="#">Mi perfil</a> 
							<ul class="dropdown-menu submenu"> 
								<li><a href="aplicaciones.php?eleccion=1">Ver Perfil</a></li> 
								<li><a href="aplicaciones.php?eleccion=2">Editar Perfil</a></li> 
							</ul> 
						</li> 
							
						<li class="dropdown"><a class="dropdown-toggle" href="#">Platos</a> 
							<ul class="dropdown-menu submenu"> 
								<li><a href="aplicaciones.php?eleccion=3">Crear Nuevo Plato</a></li> 
								<li><a href="aplicaciones.php?eleccion=4">Gestionar Platos</a></li> 
							</ul> 
						</li> 
							
						<li class="dropdown"><a class="dropdown-toggle" href="#">Menús</a> 
							<ul class="dropdown-menu submenu"> 
								<li><a href="aplicaciones.php?eleccion=5">Crear Nuevo Menú</a></li> 
								<li><a href="aplicaciones.php?eleccion=6">Gestionar Menús</a></li> 
							</ul> 
						</li> 
							
						<!--<li><a href="#">Generar Publicidad</a></li> -->
					</ul> 
				</div>
				
				<div class="span10">
					<?php require("contenido.php"); ?>
				</div> <!-- span10 -->
			</div> <!-- row-fluid -->

			 <!--<div class="row-fluid" id="footer">
				<div class="span12">
					<p><center>Pie de la pagina</center></p>
				</div>
			</div>-->
		</div> <!-- fluid-container -->
		
		<script src="js/blueimp-gallery.min.js"></script>
		<script src="js/bootstrap-carousel.js"></script> 
		<script src="js/bootstrap-modal.js"></script>
		<script src="js/bootstrap-transition.js"></script>
		
		<script src="js/enhance.min.js" type="text/javascript"></script>
		<script src="js/fileinput.jquery.js" type="text/javascript"></script> 
		<!--<script type="text/javascript">
			document.getElementById('links').onclick = function (event) {
				event = event || window.event;
				var target = event.target || event.srcElement,
				link = target.src ? target.parentNode : target,
				options = {index: link, event: event},
				links = this.getElementsByTagName('a');
				blueimp.Gallery(links, options);
			};
		</script>-->
<?php
	}
	else {
		echo '<script>';
			echo 'location.href="index.php"';
		echo '</script>';
	}
?>
</body>
</html>