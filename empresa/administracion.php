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
	
	<link rel="stylesheet" href="css/bootstrap.css"/>
	<link rel="stylesheet" href="css/bootstrap-responsive.css"/>
		
	<!--<script src="js/bootstrap.js"></script>-->
	<script src="js/jquery.js"></script>
	
	<!---------------------------- LIBRERIA PARA USAR JQUERY-UI. ------------------------------>
	<!--<link type="text/css" href="css/ui-lightness/jquery-ui-1.9.2.custom.css" rel="Stylesheet" />  -->
	<link type="text/css" rel="stylesheet" href="css/custom-theme/jquery-ui-1.10.0.custom.css">
	<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="js/funcionesJquery.js"></script>
		
	<link rel="stylesheet" href="css/mi_estilo.css"/>
	
	<script>
		function problemasServidor() {
			alert("Error en el servidor.");
		}
		
		$(function() {
			
			var id_usuario =0;
			$("#dialog-form-administracion").dialog({
				autoOpen: false,
				height: 350,
				width: 400,
				modal: true,
				buttons: {
					"Actualizar": function() {  
						var nombre_empresa =$("#nombre_empresa").val(), email =$("#email").val(), telefono =$("#telefono").val();
						var emailreg = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
						
						if ((email == "") || (!emailreg.test(email))) {
							$("#small-text8").html("* Ingrese un email valido.");
							return false;
						} 
		
						// Se comprueba que el teléfono  unicamente este constituida por números.
						var telefono_aux =telefono, numeros ="0123456789";
						
						if (telefono.length > 9) {
							document.getElementById("small-text9").innerHTML ="* El número  de teléfono  solo puede contener nueve caracteres.";
							return false;
						}
						
						while (telefono_aux.length > 0) { 
							if (numeros.indexOf(telefono_aux.substring(0, 1)) == -1) {
								document.getElementById("small-text9").innerHTML ="* El número de teléfono  solo puede contener números.";
								return false;
							}
							telefono_aux =telefono_aux.substring(1);
						}
		
						if (confirm("¿Esta seguro que desea aplicar los cambios?")) {	
							$.ajax({
								async: false,
								type: "POST",
								dataType: "html",
								contentType: "application/x-www-form-urlencoded",
								url: "gestion_usuarios.php",
								data: {id_usuario: id_usuario, nombre_empresa: nombre_empresa, email: email, telefono: telefono, opcion: 6},
								success: function(datos) {
									location.reload();
								},
								timeout: 9000, // Tiempo de espera por la respuesta del servidor.
								error: problemasServidor
							});
							$(this).dialog("close");
						}
					},
					Cancelar: function() {
						$(this).dialog("close");
					}
				},
			});
			
			// Detecta si hemos pusado en editar y inicializa el DIALOG.
			$(".editar").click(function() { 
		
				id_usuario =$(this).val();
				$.ajax({
					async: false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					url: "gestion_usuarios.php",
					data: {id_usuario: id_usuario, opcion: 5},
					success: function(datos) {
						var dato =JSON.parse(datos);
						$("#nombre_empresa").val(dato.nombre_empresa);
						$("#email").val(dato.email);
						$("#telefono").val(dato.telefono);
					},
					timeout: 1000, // Tiempo de espera por la respuesta del servidor.
					error: problemasServidor
				});
				$( "#dialog-form-administracion" ).dialog( "open" );
				return false;
			});
		});
	</script>
	
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body> 
	<div class="container-fluid">
<?php
		if (isset($_SESSION['nick_usuario']) && ($_SESSION['nick_usuario'] == 'admin')) { // Para que solo sea accesible por el administrador.
?>
			<div class="page-header">
				<div class="row-fluid">
					<div class="span3">
						<a href="index.php"><img src="img/logo.jpg" alt="Logo Marketing Digital Networks" style="width: 50%"></a>
					</div>
					 
					<div class="span5">
						<ul class="nav nav-pills">
							<li><a href="index.php">Inicio</a></li>
							<li class="active"><a href="administracion.php"><h4>Administración</h4></a></li>
						</ul>
					</div>
					
					<div class="span4">
<?php
						if (isset($_SESSION['usuariologueado'])) {
							echo '<span id="logoff2">' . 'Bienvenido, ' . '</span>' . '<span id="logoff1">' . $_SESSION["nick_usuario"] .  '</span>' . '<span id="logoff"><a href=logoff.php><img src="img/log_off_16.png" title="Salir"></a></span>';
						}
?>
					</div>
				</div> <!-- row-fluid-->
			</div> <!-- FIN PAGE HEADER -->
		
			<div class="row-fluid">
				<div id="zona-navegacion" class="span2 sidebar-nav" style="width: 12%">
					<ul id="menuLateral" class="nav nav-tabs nav-stacked"> 
						<li class="nav-header">Administración</li>
						<li class="dropdown"><a class="dropdown-toggle" href="#">Gestionar empresas</a> 
							<ul class="dropdown-menu submenu"> 
								<li><a href="administracion.php?eleccion=0">Alta</a></li> 
								<li><a href="administracion.php?eleccion=1">Baja</a></li> 
							</ul> 
						</li> 
					</ul> 
				</div>
		
				<div class="span10">
					<?php include("contenido_admin.php"); ?>
				</div> <!-- span10 -->
			</div> <!-- row-fluid -->

			 <!--<div class="row-fluid" id="footer">
				<div class="span12">
					<p><center>Pie de la pagina</center></p>
				</div>
			</div> -->
	
<?php
		}
		else {
			echo '<script>';
				echo 'location.href="index.php"';
			echo '</script>';
		}
?>
	</div> <!-- fluid-container -->
	
	<script src="js/bootstrap-carousel.js"></script> 
</body>
</html>