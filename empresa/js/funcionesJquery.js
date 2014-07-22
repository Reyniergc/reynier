//INICIALIZA EL PAGINADOR Y EL MENÚ CON SUS PLATOS CUANDO PINCHAMOS EN EDITAR MENÚ. CARGA LOS DATOS EN LA INTERFAZ CREAR MENÚ.
var cont_primerPlato =0, cont_segundoPlato =0, cont_postre =0, cont_bebida =0, indice =0, vec_platos_seleccionados = new Array();

// DEVUELVE VERDADERO SI UN PLATO A SIDO SELECCIONADO Y FALSO EN CASO CONTRARIO.
function platoSeleccionado (id_plato) {

	for (var i =0; i < indice; i++) if (vec_platos_seleccionados[i] == id_plato) return true;
	return false;
}

function init(id_menu) { 

	var tipo_plato = $("#filtroSelect option:selected").val(), mostrar_titulo =$("#mostrar_titulo").val();
	
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
	
	$.ajax({
		async: false,
		type: "POST",
		dataType: "json",
		contentType: "application/x-www-form-urlencoded",
		url: "gestionar_menu.php",
		data: {id_menu: id_menu, opcion: 3},
		success: function(datos) {
			if (datos.sucess) {
				$.each(datos, function (index, record) { 
					if ($.isNumeric(index)) {
						if (record.tipo_plato == "Primer plato") { // primeros_platos_menu
							var td = document.createElement("td"); // Se crea el elemento columna.
							td.setAttribute("id", "identificador" + record.id);
						
							var ul = document.createElement("ul");
							ul.setAttribute("class", "thumbnails");
						
							var li = document.createElement("li");
							li.setAttribute("class", "span2");
						
							var div = document.createElement("div");
							div.setAttribute("class", "thumbnail");
							div.innerHTML ='<img src="' + record.ruta_img + '">' + '<br><div style="text-align: center;"><button value="' + record.id + '" class="btn btn-danger btn-small eliminardelMenu">Eliminar</button></div>';
							
							li.appendChild(div);
							ul.appendChild(li);
							td.appendChild(ul);
							$('#primer_plato').append(td);
							cont_primerPlato++;
						}
						else {
							if (record.tipo_plato == "Segundo plato") {
								var td = document.createElement("td"); // Se crea el elemento columna.
								td.setAttribute("id", "identificador" + record.id);
							
								var ul = document.createElement("ul");
								ul.setAttribute("class", "thumbnails");
							
								var li = document.createElement("li");
								li.setAttribute("class", "span2");
							
								var div = document.createElement("div");
								div.setAttribute("class", "thumbnail");
								div.innerHTML ='<img src="' + record.ruta_img + '">' + '<br><div style="text-align: center;"><button value="' + record.id + '" class="btn btn-danger btn-small eliminardelMenu1">Eliminar</button></div>';
							
								li.appendChild(div);
								ul.appendChild(li);
								td.appendChild(ul);
								$('#segundo_plato').append(td);
								cont_segundoPlato++;
							}
							else {
								if (record.tipo_plato == "Postre") {
									var td = document.createElement("td"); // Se crea el elemento columna.
									td.setAttribute("id", "identificador" + record.id);
								
									var ul = document.createElement("ul");
									ul.setAttribute("class", "thumbnails");
								
									var li = document.createElement("li");
									li.setAttribute("class", "span2");
								
									var div = document.createElement("div");
									div.setAttribute("class", "thumbnail");
									div.innerHTML ='<img src="' + record.ruta_img + '">' + '<br><div style="text-align: center;"><button value="' + record.id + '" class="btn btn-danger btn-small eliminardelMenu2">Eliminar</button></div>';
								
									li.appendChild(div);
									ul.appendChild(li);
									td.appendChild(ul);
									$('#postre').append(td);
									cont_postre++;
								}
								else {
									if (record.tipo_plato == "Bebida") {
										var td = document.createElement("td"); // Se crea el elemento columna.
										td.setAttribute("id", "identificador" + record.id);
										
										var ul = document.createElement("ul");
										ul.setAttribute("class", "thumbnails");
										
										var li = document.createElement("li");
										li.setAttribute("class", "span2");
										
										var div = document.createElement("div");
										div.setAttribute("class", "thumbnail");
										div.innerHTML ='<img src="' + record.ruta_img + '">' + '<br><div style="text-align: center;"><button value="' + record.id + '" class="btn btn-danger btn-small eliminardelMenu3">Eliminar</button></div>';
										
										li.appendChild(div);
										ul.appendChild(li);
										td.appendChild(ul);
										$('#bebida').append(td);
										cont_bebida++;
									}
								}
							}
						}
						if (mostrar_titulo == null) $("#titulo").val(record.titulo_menu); // SOLO MOSTRAMOS EL TÍTULO SI NO QUEREMOS COPIAR EL MENÚ.
						vec_platos_seleccionados[indice] =record.id;
						++indice;
					}
				});
			}
		},
		timeout: 1000,  
		error: problemasServidor
	});
}

function altaPlatos() {
	var nombre =document.formPlatos.nombre_plato.value, tipo_plato = $("#filtroSelect option:selected").val(), mensaje ="";
	//var tipo_plato =document.formPlatos.filtroSelect.options[document.getElementById('filtroSelect').selectedIndex].text;
	
	if (nombre.length == 0) {
		alert("El nombre del plato es obligatorio.");
		return false;
	}
	else {
		if (nombre.substring(0, 1) == " ") {
			alert("El nombre del plato, debe empezar por una letra distinta del blanco.");
			document.formPlatos.nombre_plato.value ="";
			return false;
		}
	}
	
	if (document.formPlatos.archivo.value == "") {
		alert("Por favor introduzca la imagen representativa del plato.");
		return false;
	}
	

	if ((tipo_plato == "Primer plato") || (tipo_plato == "Segundo plato")) mensaje ="¿Está seguro que desea crear este plato?";
	else {
		if (tipo_plato == "Postre") mensaje ="¿Está seguro que desea crear este postre?";
		else if (tipo_plato == "Bebida") mensaje ="¿Está seguro que desea crear esta bebida?";
	}

	if (confirm(mensaje)) {
		return true;
	}
	return false;
}
	
$(document).ready(function() {		
	
	// Regresa a la pagina anterior sea cual sea.
	$("#paginaAnterior").click(function() {
		window.history.back();
		return false;
	});

	function dibujarTabla(datos) {
		numeropagina =$("#numeropagina").val(), mostrar_titulo =$("#mostrar_titulo").val()
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
							tabla +='<p><b>Nombre:</b> ' + record.nombre_plato + '</p>';
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
								else {
									tabla +='<button id="idbutton' + record.id + '" value="' + record.id + '" class="btn btn-info btn-mini select_img">Añadir al <br>menú</button>';
								}
							}
						tabla +='</td>'; 
					tabla +='</tr>';
				}
			});
			document.getElementById("cuerpoTabla").innerHTML =tabla;
		}
	}
	
	/* FILTRA POR TIPO DE PLATOS */
	$("#filtroSelect").change(function() {
	
		var tipo_plato = $("#filtroSelect option:selected").val();
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
		
		pagina_actual =1; // Siempre que hacemos un filtro por plato debemos poner nuevamente la variable a 1.
		$.ajax({
			async: false,
			type: "POST",
			dataType: "json",
			contentType: "application/x-www-form-urlencoded",
			url: "filtro_consulta.php",
			data: {tipo_plato: tipo_plato, pagina_actual: 1, limite: limite, opcion: 1},
			success: dibujarTabla,
			timeout: 1000, // Tiempo de espera por la respuesta del servidor.
			error: problemasServidor
		});
	});
	
	
	/* ORDENACIÓN DESCENDENTE */
	$('#ordenacion').on('click', '#ordenacionDesc', function() {
		var tipo_plato = $("#filtroSelect option:selected").val();

		$.ajax({
			async: false,
			type: "POST",
			dataType: "json",
			contentType: "application/x-www-form-urlencoded",
			url: "filtro_consulta.php",
			data: {tipo_plato: tipo_plato, pagina_actual: pagina_actual, limite: limite, opcion: 2},
			success: dibujarTabla,
			timeout: 1000, // Tiempo de espera por la respuesta del servidor.
			error: problemasServidor
		});
		
		$("#ordenacionDesc").remove(); // Elimina del DOM el elemento "img" de la tabla. 
		
		var img = document.createElement("img"); // Se crea el elemento columna.
		img.setAttribute("id", "ordenacionAsce");
		img.setAttribute("src", "img/ordenar_asc.png");
		img.setAttribute("title", "Ordenar ascendente");
		$('#ordenacion').append(img);
	});
	
	
	/* ORDENACIÓN ASCENDENTE */
	$('#ordenacion').on('click', '#ordenacionAsce', function() {
		var tipo_plato = $("#filtroSelect option:selected").val();

		$.ajax({
			async: false,
			type: "POST",
			dataType: "json",
			contentType: "application/x-www-form-urlencoded",
			url: "filtro_consulta.php",
			data: {tipo_plato: tipo_plato, pagina_actual: pagina_actual, limite: limite, opcion: 3},
			success: dibujarTabla,
			timeout: 1000, // Tiempo de espera por la respuesta del servidor.
			error: problemasServidor
		});
		
		$("#ordenacionAsce").remove(); // Elimina del DOM el elemento "img" de la tabla. 
		
		var img = document.createElement("img"); // Se crea el elemento columna.
		img.setAttribute("id", "ordenacionDesc");
		img.setAttribute("src", "img/ordenar_desc.png");
		img.setAttribute("title", "Ordenar descendente");
		$('#ordenacion').append(img);
	});
	
	/* ELIMINAR PLATO */
	$('#cuerpoTabla').on('click', '.eliminar', function() {
		var tipo_plato = $("#filtroSelect option:selected").val();
		var id =$(this).val(), bool =false, mensaje ="";
		
		$.ajax({
			async: false,
			type: "POST",
			dataType: "html",
			contentType: "application/x-www-form-urlencoded",
			url: "filtro_consulta.php",
			data: {id_plato: id, opcion: 10},
			success: function (datos) {
				if (datos == "true") bool =true;
			},
			timeout: 1000, // Tiempo de espera por la respuesta del servidor.
			error: problemasServidor
		});
	
		if (bool) {
			alert("No se puede eliminar un plato que está incluido en un menú. Elimine primero el plato de los menús guardados o elimine todos los menús de los cuales está incluido el plato.");
			return false;
		}
		
		/* Impide que se elimine un plato si este se encuentra en un menú que va ser creado. */
		for (var i=0; i < indice; i++) { 
			if (vec_platos_seleccionados[i] == id) {
				alert("Para eliminar el plato, debe eliminarlo del menú que está siendo creado.");
				return false;
			}
		}
		
		if ((tipo_plato == "Primer plato") || (tipo_plato == "Segundo plato")) mensaje ="¿Está seguro que desea eliminar este plato?";
		else {
			if (tipo_plato == "Postre") mensaje ="¿Está seguro que desea eliminar este postre?";
			else if (tipo_plato == "Bebida") mensaje ="¿Está seguro que desea eliminar esta bebida?";
		}
		
		if (confirm(mensaje)) {
			$.ajax({
				async: false,
				type: "POST",
				dataType: "json",
				contentType: "application/x-www-form-urlencoded",
				url: "filtro_consulta.php",
				data: {tipo_plato: tipo_plato, pagina_actual: pagina_actual, limite: limite, id: id, opcion: 4},
				success: dibujarTabla,
				timeout: 1000, // Tiempo de espera por la respuesta del servidor.
				error: problemasServidor
			});
			
			location.reload();
		}
	}); 

	//BUSCA SEGÚN EL NOMBRE DEL PLATO
	$("#inputbuscar").keyup(function () {
		
		var tipo_plato = $("#filtroSelect option:selected").val();
		var inputbuscar = $("#inputbuscar").val();
		$.ajax({
			async: false,
			type: "POST",
			dataType: "json",
			contentType: "application/x-www-form-urlencoded",
			url: "filtro_consulta.php",
			data: {tipo_plato: tipo_plato, inputbuscar: inputbuscar, opcion: 7},
			success: dibujarTabla,
			timeout: 1000, // Tiempo de espera por la respuesta del servidor.
			error: problemasServidor
		});
	});
	
	
	// CREA EL MENÚ CON LOS PLATOS SELCCIONADOS.
	$('#cuerpoTabla').on('click', '.select_img', function() {
		
		var tipo_plato = $("#filtroSelect option:selected").val();
		var id =$(this).val(), dato;
		
		// Obtiene el nombre del plato y la ruta de la imagen.
		if (((tipo_plato == "Primer plato") && (cont_primerPlato < 3)) || ((tipo_plato == "Segundo plato") && (cont_segundoPlato < 3))
		|| ((tipo_plato == "Postre") && (cont_postre < 3)) || ((tipo_plato == "Bebida") && (cont_bebida < 3))) { 
			vec_platos_seleccionados[indice] =id;
			++indice;
			
			$.ajax({
				async: false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				url: "filtro_consulta.php",
				data: {id: id, opcion: 9},
				success: function(datos) {
					dato =JSON.parse(datos);
				},
				timeout: 1000, 
				error: problemasServidor
			});
			
			// Pone los botones que añaden los platos al menú inactivos una vez que se pinche sobre ellos.
			var elemento = document.querySelector('#idbutton' + id); //Busca el botton en el que hemos pinchado y lo pone inactivo.
			elemento.setAttribute("disabled", "disabled");
		}
		
		if (tipo_plato == "Primer plato") { // cont_primerPlato
			if (cont_primerPlato < 3) {
				var td = document.createElement("td"); // Se crea el elemento columna.
				td.setAttribute("id", "identificador" + id);
				
				var ul = document.createElement("ul");
				ul.setAttribute("class", "thumbnails");
				
				var li = document.createElement("li");
				li.setAttribute("class", "span2");
				
				var div = document.createElement("div");
				div.setAttribute("class", "thumbnail");
				div.innerHTML ='<img src="' + dato.ruta_img + '">' + '<br><div style="text-align: center;"><button value="' + id + '" class="btn btn-danger btn-small eliminardelMenu">Eliminar</button></div>';
				
				li.appendChild(div);
				ul.appendChild(li);
				td.appendChild(ul);
				$('#primer_plato').append(td);
				++cont_primerPlato;
			}
			else alert("Solo puede elegir tres opciones de cada tipo de plato.");
		}
		else {
			if (tipo_plato == "Segundo plato") {
				if (cont_segundoPlato < 3) {
					var td = document.createElement("td"); // Se crea el elemento columna.
					td.setAttribute("id", "identificador" + id);

					var ul = document.createElement("ul");
					ul.setAttribute("class", "thumbnails");
					
					var li = document.createElement("li");
					li.setAttribute("class", "span2");
					
					var div = document.createElement("div");
					div.setAttribute("class", "thumbnail");
					div.innerHTML ='<img src="' + dato.ruta_img + '">' + '<br><div style="text-align: center;"><button value="' + id + '" class="btn btn-danger btn-small eliminardelMenu1">Eliminar</button></div>';
					
					li.appendChild(div);
					ul.appendChild(li);
					td.appendChild(ul);
					$('#segundo_plato').append(td);
					++cont_segundoPlato;
				}
				else alert("Solo puede elegir tres opciones de cada tipo de plato.");
			}
			else {
				if (tipo_plato == "Postre") {
					if (cont_postre < 3) {
						var td = document.createElement("td"); // Se crea el elemento columna.
						td.setAttribute("id", "identificador" + id);
						
						var ul = document.createElement("ul");
						ul.setAttribute("class", "thumbnails");
						
						var li = document.createElement("li");
						li.setAttribute("class", "span2");
						
						var div = document.createElement("div");
						div.setAttribute("class", "thumbnail");
						div.innerHTML ='<img src="' + dato.ruta_img + '">' + '<br><div style="text-align: center;"><button value="' + id + '" class="btn btn-danger btn-small eliminardelMenu2">Eliminar</button></div>';
						
						li.appendChild(div);
						ul.appendChild(li);
						td.appendChild(ul);
						$('#postre').append(td);
						++cont_postre;
					}
					else alert("Solo puede elegir tres opciones de cada tipo de plato.");
				}
				else { // Bebida
					if (cont_bebida < 3) {
						var td = document.createElement("td"); // Se crea el elemento columna.
						td.setAttribute("id", "identificador" + id);
						
						var ul = document.createElement("ul");
						ul.setAttribute("class", "thumbnails");
						
						var li = document.createElement("li");
						li.setAttribute("class", "span2");
						
						var div = document.createElement("div");
						div.setAttribute("class", "thumbnail");
						div.innerHTML ='<img src="' + dato.ruta_img + '">' + '<br><div style="text-align: center;"><button value="' + id + '" class="btn btn-danger btn-small eliminardelMenu3">Eliminar</button></div>';
						
						li.appendChild(div);
						ul.appendChild(li);
						td.appendChild(ul);
						$('#bebida').append(td);
						++cont_bebida;
					}
					else alert("Solo puede elegir tres opciones de cada tipo de plato.");
				}
			}
		}
	}); 
	
	// BUSCA UN MENÚ SEGÚN UN NOMBRE.
	$("#inputbuscarMenu").keyup(function () {
		
		var inputbuscarMenu =$("#inputbuscarMenu").val();
		$.ajax({
			async: false,
			type: "POST",
			dataType: "json",
			contentType: "application/x-www-form-urlencoded",
			url: "gestionar_menu.php",
			data: {inputbuscarMenu: inputbuscarMenu, opcion: 7},
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
									else tabla += '<p>' + record.fecha_envio_menu + '</p>'; 
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
			error: problemasServidor
		});
	});
	
	
	// ********************* ELIMINA DEL DOM EL PLATO AÑADIDO AL MENÚ *********************** /
	$('#primer_plato').on('click', '.eliminardelMenu', function() {
		
		--cont_primerPlato;
		var id_plato =$(this).val();
		
		var i=0;
		while ((i < indice) && (vec_platos_seleccionados[i] != id_plato)) ++i;
		
		for (var j =i; j < indice-1; j++) { 
			vec_platos_seleccionados[j] =vec_platos_seleccionados[j+1];
		}
		--indice;  
		
		// Eliminamos el atributo "disabled" automaticamente se asigna el atributo por defecto "ENABLE" cuando eliminamos un plato del menú.		
		$("#idbutton" + id_plato).removeAttr("disabled");
		
		$('#identificador' + id_plato).remove(); // Elimina del DOM el elemento "TD" de la tabla. 
		return false;
	});
	
	$('#segundo_plato').on('click', '.eliminardelMenu1', function() {
		
		--cont_segundoPlato
		var id_plato =$(this).val();
		
		var i=0;
		while ((i < indice) && (vec_platos_seleccionados[i] != id_plato)) ++i;
		
		for (var j =i; j < indice-1; j++) { 
			vec_platos_seleccionados[j] =vec_platos_seleccionados[j+1];
		}
		--indice; 
		
		// Eliminamos el atributo "disabled" automaticamente se asigna el atributo por defecto cuando eliminamos un plato del menú.		
		$("#idbutton" + id_plato).removeAttr("disabled");
		
		$('#identificador' + id_plato).remove(); // Elimina del DOM el elemento "TD" de la tabla.
		return false;
	});
	
	$('#postre').on('click', '.eliminardelMenu2', function() {
	
		--cont_postre;
		var id_plato =$(this).val();
		
		var i=0;
		while ((i < indice) && (vec_platos_seleccionados[i] != id_plato)) ++i;
		
		for (var j =i; j < indice-1; j++) { 
			vec_platos_seleccionados[j] =vec_platos_seleccionados[j+1];
		}
		--indice; 
		
		// Eliminamos el atributo "disabled" automaticamente se asigna el atributo por defecto cuando eliminamos un plato del menú.	
		$("#idbutton" + id_plato).removeAttr("disabled");
		
		$('#identificador' + id_plato).remove(); // Elimina del DOM el elemento "TD" de la tabla.
		return false;
	});
	
	$('#bebida').on('click', '.eliminardelMenu3', function() {
	
		--cont_bebida;
		var id_plato =$(this).val();
		
		var i=0;
		while ((i < indice) && (vec_platos_seleccionados[i] != id_plato)) ++i;
		
		for (var j =i; j < indice-1; j++) { 
			vec_platos_seleccionados[j] =vec_platos_seleccionados[j+1];
		}
		--indice; 
		
		// Eliminamos el atributo "disabled" automaticamente se asigna el atributo por defecto cuando eliminamos un plato del menú.	
		$("#idbutton" + id_plato).removeAttr("disabled");
		
		$('#identificador' + id_plato).remove(); // Elimina del DOM el elemento "TD" de la tabla.
		return false;
	});
	//********************************************************************************************************/
	
	
	// ******************************************* CREA EL MENÚ ********************************************* /
	$("#exportarMenu").click(function() { 
	
		var fecha =$("#datepicker1").val(), titulo =$("#titulo").val(), id_menu =$("#id_menu").val(), opcion =1;
		
		if (id_menu != null) opcion =3;
		
		if (indice == 0) { 
			alert ("El menú no puede ser creado vacío.");
			return false;
		}
		
		if (titulo.length == 0) { // Para no permitir envios al servidor si no se ha elegido un título.
			alert ("El título del menú es obligatorio.");
			return false;
		}
		else {
			if (titulo.substring(0, 1) == " ") {
				alert("El nombre del menú, debe empezar por una letra distinta del blanco.");
				$("#titulo").val("");
				return false;
			}
		}
		
		if (!$("#datepicker1").val()) { 
			alert ("Por favor introduzca la fecha de publicación.");
			return false;
		}
		
		if (confirm("¿Está seguro que desea crear el menú?")) { // Solo se envian los datos si el usario pincha en aceptar.
			
			var dato =0, dato1 =0;
			if (opcion == 1) {
				$.ajax({
					async: false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					url: "crear_menu.php",
					data: {fecha: fecha, titulo: titulo, vec_platos: vec_platos_seleccionados, indice_vec: indice, opcion: 1},
					success: function(datos) {
						if (datos == 1) dato =1; 
					},				
					//beforeSend: cerrar, // Llama a una función que muestra una imagen de carga.
					timeout: 1000,
					error: problemasServidor
				});
				
				if (dato == 0) location.href="aplicaciones.php?eleccion=5";
				else {
					alert ("Ya existe un menú con este título. Elija otro título para el menú.");
					return false;
				}
			}
			else {
				if (opcion == 3) { // PUBLICA EL MENÚ SI LO ESTAMOS EDITANDO.
					$.ajax({
						async: false,
						type: "POST",
						dataType: "html",
						contentType: "application/x-www-form-urlencoded",
						url: "crear_menu.php",
						data: {fecha: fecha, titulo: titulo, vec_platos: vec_platos_seleccionados, indice_vec: indice, id_menu: id_menu, opcion: 3},
						success: function(datos) {
							if (datos == 3) alert('Error => Solo es posible exportar el menú una única vez si está siendo editado.');
							else {
								if (datos == 1) dato1 =1;
							}
						},	
						timeout: 1000,
						error: problemasServidor
					});
					
					if (dato1 == 0) location.href="aplicaciones.php?eleccion=5";
					else {
						alert ("Ya existe un menú con este título. Elija otro título para el menú.");
						return false;
					}
				}
			}
		}
		return false; // Para no recargar la pagina en caso del usuario cancele en envio de datos.
	});
	
	
	// ******************************************* GUARDA EL MENÚ ********************************************* /
	$("#guardarMenu").click(function() { 
		
		var titulo =$("#titulo").val(), opcion =2, id_menu =$("#id_menu").val();
		
		if (id_menu != null) opcion =4;
		
		if (indice == 0) { 
			alert ("El menú no puede ser guardado vacío.");
			return false;
		}
		
		if (titulo.length == 0) { // Para no permitir envios al servidor si no se ha elegido un título.
			alert ("El título del menú es obligatorio.");
			return false;
		}
		else {
			if (titulo.substring(0, 1) == " ") {
				alert("El nombre del menú, debe empezar por una letra distinta del blanco.");
				$("#titulo").val("");
				return false;
			}
		}
		
		if (opcion == 2) { // GUARDA UN MENÚ PARA LUEGO SER EDITADO O EXPORTADO.
			
			if (confirm("¿Está seguro que desea guardar el menú?")) { 
				
				var dato =0;
				$.ajax({
					async: false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					url: "crear_menu.php",
					data: {titulo: titulo, vec_platos: vec_platos_seleccionados, indice_vec: indice, opcion: 2},			
					success: function(datos) {
						if (datos == 1) dato =1; 
					},
					timeout: 1000, // Tiempo de espera por la respuesta del servidor.
					error: problemasServidor
				});
				
				if (dato == 0) location.href="aplicaciones.php?eleccion=5";
				else {
					alert ("Ya existe un menú con este título. Elija otro título para el menú.");
					return false;
				}
				//location.href="aplicaciones.php?eleccion=5";
			}
		}
		else {
			var dato1 =0;
			if (opcion == 4) {  // GUARDA UN MENÚ QUE ESTA SIENDO EDITADO.
				if (confirm("¿Está seguro que desea guardar los cambios del menú?")) {
					$.ajax({
						async: false,
						type: "POST",
						dataType: "html",
						contentType: "application/x-www-form-urlencoded",
						url: "crear_menu.php",
						data: {titulo: titulo, vec_platos: vec_platos_seleccionados, indice_vec: indice, id_menu: id_menu, opcion: 4},
						success: function(datos) {
							if (datos == 4) alert('Error => No es posible editar un menú que ya ha sido enviado.');
							else {
								if (datos == 1) dato1 =1;
							}
						},		
						//beforeSend: cerrar, // Llama a una función que muestra una imagen de carga.
						timeout: 1000,
						error: problemasServidor
					});
					
					if (dato1 == 0) location.href="aplicaciones.php?eleccion=5";
					else {
						alert ("Ya existe un menú con este título. Elija otro título para el menú.");
						return false;
					}
					//location.href="aplicaciones.php?eleccion=5";
				}
			}
		}
		
		return false; // Para no recargar la pagina en caso del usuario cancele en envio de datos.
	});
	
	// ******************************************************************************************************* /
	
	/* ELIMINAR MENÚ */
	$('#cuerpotablaListaMenus').on('click', '.eliminarMenu', function() {
		var id_menu =$(this).val();
		
		if (confirm("¿Está seguro que desea eliminar el menú?")) {
			$.ajax({
				async: false,
				type: "POST",
				dataType: "json",
				contentType: "application/x-www-form-urlencoded",
				url: "gestionar_menu.php",
				data: {id_menu: id_menu, opcion: 1},
				success: function(datos) {
					location.reload();
				},
				timeout: 1000, // Tiempo de espera por la respuesta del servidor.
				error: problemasServidor
			});
		}
	});
	
	
	// ******************************************* COPIAR MENÚ ********************************************* /
	$("#copiarMenu").click(function() { 
		
		var titulo =$("#titulo").val(); 
		
		if (indice == 0) { 
			alert ("El menú no puede ser copiado vacío.");
			return false;
		}
		
		if (titulo.length == 0) { // Para no permitir envios al servidor si no se ha elegido un título.
			alert ("El título del menú es obligatorio.");
			return false;
		}
		else {
			if (titulo.substring(0, 1) == " ") {
				alert("El nombre del menú, debe empezar por una letra distinta del blanco.");
				$("#titulo").val("");
				return false;
			}
		}
		
		if (confirm("¿Está seguro que desea copiar el menú?")) {
			var dato =0;
			$.ajax({
				async: false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				url: "gestionar_menu.php",
				data: {titulo: titulo, vec_platos: vec_platos_seleccionados, indice_vec: indice, opcion: 5},
				success: function(datos) {
					if (datos == 1) dato =1;
					//location.href="aplicaciones.php?eleccion=5";
					//location.reload();
				},
				timeout: 1000, // Tiempo de espera por la respuesta del servidor.
				error: problemasServidor
			});
			
			if (dato == 0) location.href="aplicaciones.php?eleccion=5";
			else {
				alert ("Ya existe un menú con este título. Elija otro título para el menú.");
				return false;
			}
		}
		return false;
	});
	
	// ******************************************************************************************************** /
	
	$('.carousel').carousel({
		interval: 5000
	});
	
	var emailreg = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
	
	// Muestra un mensaje en caso que ocurra algún fallo en el servidor.
	function problemasServidor() {
		alert("Error en el servidor.");
	}
	
	// PARA EL FORM LOGIN.
	$("#Loguin").click(function() {
		if (($("#campo1").val() == "") || (($("#campo2").val()) == "")) {
			document.getElementById("small-text1").innerHTML ="* Por favor, introduce tu dni y contraseña para entrar."; 
			return false;
		}
		
		var nick =$("#campo1").val();
		var contrasena =$("#campo2").val(), bool = false;
		
		$.ajax({
			async: false,
			type: "POST",
			dataType: "html",
			contentType: "application/x-www-form-urlencoded",
			url: "ingresar.php",
			data: {nick: nick, contrasena: contrasena},
			success: function(datos) {
				if (datos == 0) $("#small-text1").html("* Los datos introducidos no son correctos.");
				else {
					if (datos == 1) bool =true;
					else if (datos == 2) $("#small-text1").html('Su cuenta ha sido deshabilitada. Pongase en contacto con el administrador.');
				}
			},
			timeout: 9000, // Tiempo de espera por la respuesta del servidor.
			error: problemasServidor
		});	
		
		if (bool == true) return true;
		return false;
	});	
	
	
	// EDITAR PERFIL.
	$("#Editar_Perfil").click(function() {	
		var contrasena1 =$("#input01").val(), contrasena2 =$("#input02").val();
		
		document.getElementById("small-text1").innerHTML =""; 
		document.getElementById("small-text5").innerHTML =""; 
		document.getElementById("small-text7").innerHTML =""; 
		document.getElementById("small-text8").innerHTML =""; 
		document.getElementById("small-text10").innerHTML =""; 
		
		if ((contrasena1 != "") && (contrasena2 == "")) {
			document.getElementById("small-text7").innerHTML ="* Por favor rellene este campo."; 
			return false;	
		}
		if ((contrasena1 == "") && (contrasena2 != "")) {
			document.getElementById("small-text5").innerHTML ="* Por favor rellene este campo."; 
			return false;	
		}
		
		if ((contrasena1 != "") && (contrasena2 != "")) {
			if ((contrasena1.length >= 7) && (contrasena2.length >= 7)) {
				if (contrasena1 == contrasena2) {
					// Ahora comprobamos si las dos contraseña2 contienen algún espacio en blanco.
					if (contrasena1.indexOf(" ") != -1) {
						document.getElementById("small-text5").innerHTML ="* Las contrase&ntilde;as no pueden tener espacios en blanco."; 
						document.getElementById("small-text7").innerHTML ="* Las contrase&ntilde;as no pueden tener espacios en blanco."; 
						return false; // indexOf devuelve la posiciónn donde encontro el blanco.
					}
				}
				else {
					document.getElementById("small-text5").innerHTML ="* Las contrase&ntilde;as no son iguales."; 
					document.getElementById("small-text7").innerHTML ="* Las contrase&ntilde;as no son iguales.";
					return false;		
				}
			}
			else {
				document.getElementById("small-text5").innerHTML ="* La longitud de la contraseña debe ser mayor que 7."; 
				document.getElementById("small-text7").innerHTML ="* La longitud de la contraseña debe ser mayor que 7.";
				return false;
			}
		}
		else { // Si no se introduce nada en el campo de la contraseña del formulario mantenemos el valor actual.
			contrasena1 =$("#input06").val();
		}

		// Se comprueba que el telefono unicamente este constituida por números.
		var telefono =$("#input03").val(), numeros ="0123456789";
		if (telefono.length <= 9) {
			var telefono_aux =telefono;
			while (telefono_aux.length > 0) { 
				if (numeros.indexOf(telefono_aux.substring(0, 1)) == -1) {
					document.getElementById("small-text8").innerHTML ="* El telefono solo puede contener números.";
					return false;
				}
				telefono_aux =telefono_aux.substring(1);
			}
		}
		else {
			document.getElementById("small-text8").innerHTML ="* El telefono solo puede contener 9 caracteres.";
			return false;
		}
		
		var email =$("#input04").val(); 
		if ((email == "") || (!emailreg.test(email))) {
			$("#small-text9").html("* Ingrese un email valido.");
			return false;
		} 
		
		// Se comprueba que la contraseña actual es correcta.
		var contrasena_actual =$("#input05").val(), bool = false;
		$.ajax({
			async: false,
			type: "POST",
			dataType: "html",
			contentType: "application/x-www-form-urlencoded",
			url: "editar_perfil.php",
			data: {contrasena_actual: contrasena_actual, opcion: 1},
			success: function(datos) {
				if (datos == 0) {
					$("#small-text10").html("* La contraseña actual no es correcta.");
					bool =true;
				}
			},
			timeout: 1000, // Tiempo de espera por la respuesta del servidor.
			error: problemasServidor
		}); 
		
		if (bool == true) return false;
		
		if (confirm("¿Esta seguro que desea actualizar los datos?")) {
			// Si la contraseña actual es correcta se actulizan los datos.
			$.ajax({
				async: false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				url: "editar_perfil.php",
				data: {contrasena: contrasena1, telefono: telefono, email: email, opcion: 2},
				timeout: 1000, // Tiempo de espera por la respuesta del servidor.
				error: problemasServidor
			}); 
			$("#small-text1").html("* Su perfil ha sido modificado correctamente.");
		}
		
		$("#input01").val("");
		$("#input02").val("");
		$("#input05").val("");
		return false;
	});
	
	
	// RECUPERAR CONTRASEÑA.
	$("#Recuperar_Contrasena").click(function() {
		
		var email =$("#email_1").val(), bool =false;
		if ((email == "") || (!emailreg.test(email))) {
			$("#small-text2").html("* Ingrese un email valido.");
			return false;
		} 
		
		$.ajax({ // Primero se comprueba que el correo electronico existe en la base de datos.
			async: false,
			type: "POST",
			dataType: "html",
			contentType: "application/x-www-form-urlencoded",
			url: "envio_email.php",
			data: {email: email, opcion: 1},
			success: function(datos) {
				if (datos != "") bool = true; 
				else $("#small-text2").html("* El correo electronico no existe.");
			},
			timeout: 1000, // Tiempo de espera por la respuesta del servidor.
			error: problemasServidor
		});	
		
		if (bool == false) return false;
		$.post("envio_email.php", {email: email, opcion: 2});
	});
	
	
	// MUESTRA UNA IMAGEN DE CARGA.
	function cerrar() { 
		document.getElementById("cargaImg").style.visibility ="visible";
		
		$("#cargaImg").animate({"opacity": "0"}, 5000, function() {
			$("#cargaImg").css("display", "none");
			document.getElementById("small-text6").innerHTML ="* En unos instantes se actualizará en su pantalla.";
		});
	}
	
	// CREA UNA EMPRESA EN LA BASE DE DATOS Y SUS CARPETAS.
	$("#altaEmpresa").click(function() { 
		
		var nombre_empresa =$("#nombre_empresa").val();
		var contrasena1 =$("#contraseña1").val();
		var contrasena2 =$("#contraseña2").val(), bool =false;
		var nick_usuario =$("#nick_usuario").val();
		
		$("#small-text1").html("");
		$("#small-text5").html("");
		$("#small-text7").html("");
		$("#small-text8").html("");
		$("#small-text9").html("");
		$("#small-text10").html("");
		
		// Comprueba que el nick no este en uso.
		$.ajax({
			async: false,
			type: "POST",
			dataType: "html",
			contentType: "application/x-www-form-urlencoded",
			url: "gestion_usuarios.php",
			data: {nick_usuario: nick_usuario, opcion: 0},
			success: function(datos) {
				if (datos == 1) {
					bool =true;
					$("#small-text10").html("* El nick ya se encuentra en uso, elija otro por favor.");
				}
			},
			timeout: 1000, // Tiempo de espera por la respuesta del servidor.
			error: problemasServidor
		}); 

		if (bool == true) return false; // Para que no se envien los datos si el nick esta siendo usado por otro usuario.
		
		if (nick_usuario.length > 10) {
			document.getElementById("small-text10").innerHTML ="* El nombre de usuario no puede exceder a 10 caracteres.";
			return false;
		}
		
		
		if ((contrasena1.length >= 7) && (contrasena2.length >= 7)) {
			if (contrasena1 == contrasena2) {
				// Ahora comprobamos si las dos contraseña2 contienen algún espacio en blanco.
				if (contrasena1.indexOf(" ") != -1) {
					document.getElementById("small-text5").innerHTML ="* Las contrase&ntilde;as no pueden tener espacios en blanco."; 
					document.getElementById("small-text7").innerHTML ="* Las contrase&ntilde;as no pueden tener espacios en blanco."; 
					return false; // indexOf devuelve la posiciónn donde encontro el blanco.
				}
			}
			else {
				document.getElementById("small-text5").innerHTML ="* Las contrase&ntilde;as no son iguales."; 
				document.getElementById("small-text7").innerHTML ="* Las contrase&ntilde;as no son iguales.";
				return false;		
			}
		}
		else {
			document.getElementById("small-text5").innerHTML ="* La longitud de la contraseña debe ser mayor que 7."; 
			document.getElementById("small-text7").innerHTML ="* La longitud de la contraseña debe ser mayor que 7.";
			return false;
		}
		
		// Se comprueba que el telefono unicamente este constituida por números.
		var telefono =$("#telefono").val(), numeros ="0123456789";
		
		if (telefono.length > 0) {
			if (telefono.length <= 9) {
				var telefono_aux =telefono;
				while (telefono_aux.length > 0) { 
					if (numeros.indexOf(telefono_aux.substring(0, 1)) == -1) {
						document.getElementById("small-text9").innerHTML ="* El teléfono solo puede contener números.";
						return false;
					}
					telefono_aux =telefono_aux.substring(1);
				}
			}
			else {
				document.getElementById("small-text9").innerHTML ="* El número de teléfono debe contener como máximo nueve caracteres.";
				return false;
			}
		}
		else {
			document.getElementById("small-text9").innerHTML ="* El número de teléfono es obligatorio.";
			return false;
		}
		
		var email =$("#email").val();
		
		if ((email == "") || (!emailreg.test(email))) {
			$("#small-text8").html("* Ingrese un email valido.");
			return false;
		} 
		
		if (confirm("¿Está seguro que desea dar de alta a está empresa?")) {
			$.ajax({
				async: false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				url: "gestion_usuarios.php",
				data: {nombre_empresa: nombre_empresa, nick_usuario: nick_usuario, contrasena1: contrasena1, email: email, telefono: telefono, opcion: 1},
				timeout: 1000, // Tiempo de espera por la respuesta del servidor.
				error: problemasServidor
			}); 
			$("#small-text1").html("* La empresa se ha dado de alta correctamente.");
		}
		
		return false;
	});
	
	// HABILITA UNA EMPRESA.
	$(".habilitar").click(function() { 
		var id_usuario =$(this).val();
	
		if (confirm("¿Está seguro que desea habilitar está empresa?")) {
			$.ajax({
				async: false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				url: "gestion_usuarios.php",
				data: {id_usuario: id_usuario, opcion: 3},		
				timeout: 1000, // Tiempo de espera por la respuesta del servidor.
				error: problemasServidor
			}); 
		}
		location.reload();
	});
	
	// DESHABILITA UNA EMPRESA.
	$(".deshabilitar").click(function() { 
		var id_usuario =$(this).val();
	
		if (confirm("¿Está seguro que desea deshabilitar está empresa?")) {
			$.ajax({
				async: false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				url: "gestion_usuarios.php",
				data: {id_usuario: id_usuario, opcion: 4},		
				timeout: 1000, // Tiempo de espera por la respuesta del servidor.
				error: problemasServidor
			}); 
		}
		location.reload();
	});
	
	// ELIMINA UN USUARIO DE LA BASE DE DATOS Y SUS CARPETAS.
	$(".bajaEmpresa").click(function() { 
		var id_usuario =$(this).val();
	
		if (confirm("¿Está seguro que desea eliminar todos los datos relacionados con está empresa?")) {
			$.ajax({
				async: false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				url: "gestion_usuarios.php",
				data: {id_usuario: id_usuario, opcion: 2},		
				timeout: 1000, // Tiempo de espera por la respuesta del servidor.
				error: problemasServidor
			}); 
		}
		location.reload();
	});
});