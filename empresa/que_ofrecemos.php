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
		
	<script src="js/bootstrap.js"></script>
	<script src="js/jquery.js"></script>
	<script type="text/javascript" src="js/funcionesJquery.js"></script>
	
	<!---------------------------- LIBRERIA PARA USAR JQUERY-UI. ------------------------------>
	<link type="text/css" href="css/ui-lightness/jquery-ui-1.9.2.custom.css" rel="Stylesheet" />      
	<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
	<!-- ----------------------------------------------------------------------------------- -->
	
	<!-- LIBRERIA PARA USAR GALERIA DE IMAGENES BOOTSTRAP. -->
	<link rel="stylesheet" href="css/blueimp-gallery.min.css">
	<!-- ------------------------------------------------- -->
	
	<link rel="stylesheet" href="css/mi_estilo.css"/>
	
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
		
	<script>
		$(function() {
			$( "#datepicker" ).datepicker();
		});
		
		document.getElementById('links').onclick = function (event) {
			event = event || window.event;
			var target = event.target || event.srcElement,
			link = target.src ? target.parentNode : target,
			options = {index: link, event: event},
			links = this.getElementsByTagName('a');
			blueimp.Gallery(links, options);
		};
	</script>
</head>
<body> 
	<div class="container-fluid">		
		<div class="page-header">
			<div class="row-fluid">
				<div class="span3">
					<a href="index.php"><img src="img/logo.jpg" alt="Logo Marketing Digital Networks" style="width: 50%"></a>
				</div>
				 
				<div class="span5">
					<ul class="nav nav-pills">
						<li><a href="index.php"><h4>Inicio</h4></a></li>
						<li class="active"><a href="que_ofrecemos.php"><h4>¿Que Ofrecemos?</h4></a></li>
					</ul>
				</div>
				
				<div class="span4">
<?php
					if (!isset($_SESSION['usuariologueado'])) {
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
						echo "Bienvenido, " . $_SESSION["usuariologueado"] . '<span id="logoff"><a href=logoff.php><img src="img/log_off_16.png" title="Salir"></a></span>';
						echo '<br/>';
						// Para que solo el administrador del sistema pueda acceder a la pagina de registro de usuarios.
						if ($_SESSION["usuariologueado"] == "admin") {
							echo '<br/><span class="small-text2"><a href="registro_empresas.php">Gestionar empresas</a></span>';
						}
					}
?>
					<!-- myModal_1 -->
					<div class="modal hide fade" id="myModal_1">
						<div class="modal-header">
							<a class="close" data-dismiss="modal">×</a>
							<h3>Recuperar Contraseña</h3>
						</div>
						<div class="modal-body"> <!-- Cuerpo del modal -->
							<form class="form-horizontal" method="get">
								<fieldset>
									<!--<legend>Legend text</legend> -->
									<div class="control-group">
										<label class="control-label" for="email_1"></label>
										<div class="controls">
											<input type="email" name="email" class="input-large" id="email_1" placeholder="Email ...">
											<p class="help-block">* Introduzca su email.</p>
										</div>
									</div>
										
									<div class="control-group">
										<div class="controls">
											<button type="submit" class="btn" id="Recuperar_Contrasena">Enviar Contraseña</button>
											<p id="small-text2"></p>
										</div>
									</div>
								</fieldset>
							</form>
						</div>
						<div class="modal-footer">
							<a href="#" class="btn" data-dismiss="modal">Cerrar</a>
						</div>
					</div> <!-- FIN myModal_1 -->
				</div>
			</div> <!-- row-fluid-->
		</div> <!-- FIN PAGE HEADER -->
		
		<div class="row-fluid">
			<div class="span2">
				<div class="well sidebar-nav">
					<ul class="nav nav-list">
						<?php
							if (isset($_SESSION['usuariologueado'])) { // Solo muestra los links si el usuario esta logueado.
								echo '<li class="nav-header">Menú</li>'; 
								echo '<li><a href="aplicaciones.php?eleccion=1"><h4>Cambiar Contraseña</h4></a></li>';  //  class="active" - Para indicar en que paginas estamos navegando
								echo '<li><a href="aplicaciones.php?eleccion=2"><h4>Subir Foto Plato</h4></a></li>';
								echo '<li><a href="aplicaciones.php?eleccion=3"><h4>Actualizar Menú</h4></a></li>';
							}
						?>
						<li><a href="aplicaciones.php?eleccion=4"><h4>Galeria de fotos</h4></a></li>
					</ul>
				</div> <!-- well -->
			</div> <!-- span2 -->
		
			<div class="span10">
				<?php include("contenido.php"); ?>
			</div> <!-- span10 -->
		</div> <!-- row-fluid -->

		<div class="row-fluid" id="footer">
			<div class="span12">
				<p><center>Pie de la pagina</center></p>
			</div>
		</div>
	</div> <!-- fluid-container -->

	
	<?php
		if (isset($_GET['Enviar'])) {
			include("class.phpmailer.php");							  // Clase para usar funciones para enviar email.
			include("class.smtp.php");								  // Clase para conexión via smtp. 
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->CharSet = 'UTF-8';
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = 'ssl';
			$mail->Host = "smtp.gmail.com"; 						  // Servidor smtp.
			$mail->Port = 465; 										  // Puerto smtp de gmail.  
			$mail->Username = 'reynier.tellez@gmail.com';  
			$mail->Password = 'sensuelle1991';
			$mail->FromName = 'Marketing Digital Netowork';	 
			$mail->From = 'reynier.tellez@gmail.com';				// Email de remitente desde donde se envía el correo.
			
			$mail->WordWrap = 85; 								  
			$mail->AltBody = 'text plano';   
			$mail->Subject = 'Contacto cliente';  
			$mail->Body = $_GET['texto'] . '<br><br>Email del remitente: ' . $_GET['email'] . '<br>Telefono del remitente: ' . $_GET['telefono'];
	
			$mail->AddAddress('reynierlima@hotmail.com'); 			
			if (!$mail->Send()) echo $mail->ErrorInfo;				 // Error si no se envía correctamente el email.
		}
	?>
	
	<script src="js/blueimp-gallery.min.js"></script>
	<script src="js/bootstrap-carousel.js"></script> 
	<script src="js/bootstrap-modal.js"></script>
	<script src="js/bootstrap-transition.js"></script>
	<script>
		document.getElementById('links').onclick = function (event) {
			event = event || window.event;
			var target = event.target || event.srcElement,
			link = target.src ? target.parentNode : target,
			options = {index: link, event: event},
			links = this.getElementsByTagName('a');
			blueimp.Gallery(links, options);
		};
	</script>
</body>
</html>