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
	<script type="text/javascript" src="js/funcionesJquery.js"></script>
		
	<link rel="stylesheet" href="css/mi_estilo.css"/>
		
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
	<div class="container-fluid">
		<div class="page-header">
			<div class="row-fluid">
				<div class="span3">
					<!--<a href="index.php"><img src="img/logo.jpg" alt="Logo Marketing Digital Networks" style="width: 50%"></a>-->
					<a href="index.php"><img src="img/ulpgc.png" alt="Logo Marketing Digital Networks" style="width: 70%"></a>
				</div>
				<div class="span5">
					<ul class="nav nav-pills">
						<li class="active"><a href="index.php"><h4>Inicio</h4></a></li>
<?php
						/*if (!isset($_SESSION['nick_usuario']) || (isset($_SESSION['nick_usuario']) && ($_SESSION['nick_usuario'] == 'admin'))) {
							echo '<li><a href="quien_somos.php"><h4>Quien Somos</h4></a></li>';
							echo '<li><a href="que_ofrecemos.php"><h4>¿Que ofrecemos?</h4></a></li>';
						}*/
						// Se muestra un link con aplicaciones unicamente para usuarios debidamente logueados.
						if (isset($_SESSION['nick_usuario']) && ($_SESSION['nick_usuario'] != 'admin')) {
							echo '<li><a href="aplicaciones.php"><h4>Aplicaciones</h4></a></li>';
						}

						/*if (!isset($_SESSION['nick_usuario']) || (isset($_SESSION['nick_usuario']) && ($_SESSION['nick_usuario'] == 'admin'))) {
							echo '<li><a href="trabaja_con_nosotros.php"><h4>Trabaja con nosotros</h4></a></li>';
							echo '<li><a data-toggle="modal" href="#myModal"><h4>Contacto</h4></a></li>';
						}*/
?>			
					</ul>
					
					<!-- MODAL -->
					<div class="modal hide fade" id="myModal">
						<div class="modal-header">
							<a class="close" data-dismiss="modal">×</a>
							<h3>Contacto</h3>
						</div>
						<div class="modal-body"> <!-- Cuerpo del modal -->
							
							<form class="form-horizontal" method="get">
								<fieldset>
									<div class="control-group">
										<label class="control-label" for="email"></label>
										<div class="controls">
											<input type="email" name="email" id="email" class="input-large" placeholder="Email ..." required>
											<p class="help-block">* Introduzca su email.</p>
										</div>
									</div>
										
									<div class="control-group">
										<label class="control-label" for="telefono"></label>
										<div class="controls">
											<input type="text" name="telefono" id="telefono" placeholder="Introduza su telefono ..." required>
											<p class="help-block">* Introduzca su telefono.</p>
										</div>
									</div>
										
									<div class="control-group">
										<label class="control-label" for="texto"></label>
										<div class="controls">
											<textarea name="texto" rows=8 cols=50 id="texto" placeholder="Introduza su pregunta ..." required></textarea>
											<p class="help-block">* Introduzca su pregunta.</p>
										</div>
									</div>
										
									<div class="control-group">
										<div class="controls">
											<button type="submit" class="btn" name="Enviar">Enviar Consulta</button>
										</div>
									</div>
								</fieldset>
							</form>
						</div>
						<div class="modal-footer">
							<a href="#" class="btn" data-dismiss="modal">Cerrar</a>
						</div>
					</div> <!-- FIN MODAL -->
				</div> <!-- FIN SPAN5 -->
				
				<div class="span4">
<?php
					if (!isset($_SESSION['nick_usuario'])) {
?>	
						<form class="well form-inline" style="width: 70%">
							<input type="text" class="input-small" id="campo1" placeholder="Usuario"> 
							<input type="password" class="input-small" id="campo2" placeholder="Password">
							<button type="submit" class="btn" id="Loguin">Entrar</button> 
							<br/><p id="small-text1"></p> <!--Aquí se muestra los mensajes enviados desde javaScript.-->
							<a data-toggle="modal" href="#myModal_1">Recuperar Contraseña</a> 
						</form>
<?php
					}
					else { // Muestra la bienvenida y un botom para salir cuando el usuario esta logueado. width: 70%;  style="height: 40%".
						echo '<span id="logoff2">' . 'Bienvenido, ' . '</span>' . '<span id="logoff1">' . $_SESSION["nick_usuario"] .  '</span>' . '<span id="logoff"><a href=logoff.php><img src="img/log_off_16.png" title="Salir"></a></span>';
						echo '<br/>';
						// Para que solo el administrador del sistema pueda acceder a la pagina de registro de usuarios.
						if ($_SESSION["nick_usuario"] == "admin") {
							echo '<br/><span class="small-text2"><a href="administracion.php">Gestionar usuarios</a></span>';
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
											<p class="help-block">* Para recuperar su contraseña debe <br> introducir el correo electrónico con el <br> que fue dado de alta en el sistema.</p>
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
	
		<div class="row-fluid" id="carousel">
			<div class="span12">
				<div id="myCarousel" class="carousel slide">
					<!-- Carousel items -->
					<div class="carousel-inner">
						<div class="item active"><img src="img/2.png" alt="" style="width: 100%"/></div>
						<div class="item"><img src="img/3.png" alt="" style="width: 100%" /></div>
						<div class="item"><img src="img/5.png" alt="" style="width: 100%" /></div>
						<div class="item"><img src="img/7.png" alt="" style="width: 100%" /></div>
					</div>
					<!-- Carousel nav -->
					<a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
					<a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
				</div>
			</div>
		</div>
	</div>

<?php		
		/*echo '<div class="row-fluid">';
			echo '<div class="span3">';
				echo '<div class="row-fluid">';
					echo '<div class="span3">';
						if (isset($_REQUEST['Guardar5'])) { 
							include ("conectar_mysql.php");
							$query =mysql_query("select id_img from imagenes where id_img = 3", $link);
							$ruta ="img/" . $_FILES["archivo"]["name"];
							if (mysql_num_rows($query) == 0) {
								mysql_query("insert into imagenes (ruta_img, id_img) values ('$ruta', 3)", $link) or die (mysql_error());
							}
							else mysql_query("UPDATE imagenes SET ruta_img='$ruta' where id_img = 3");
							mysql_close($link); 
						}
						
						if (isset($_SESSION['nick_usuario']) && ($_SESSION['nick_usuario']) == 'admin') {
							echo '<form action="index.php" method="post" enctype="multipart/form-data">';
								echo '<label for="archivo"></label>';
								echo '<input type="file" name="archivo" id="archivo" /><br>';
								echo '<button type="submit" class="btn" name="Guardar5" value="guardar">Guardar imagen</button>';
							echo '</form>';
						}
						else {
							include ("conectar_mysql.php");
							$query =mysql_query("select ruta_img from imagenes where id_img = 3", $link);
							$fila =mysql_fetch_array($query);
							echo '<div id="altura"><img src="' . $fila['ruta_img'] . '"width="300"></div>';
							mysql_close($link); 
						}
					echo '</div>';
				echo '</div>';	
				
				echo '<br><br><br><div class="row-fluid">';
					echo '<div class="span3">';
						if (isset($_REQUEST['Guardar6'])) { 
							include ("conectar_mysql.php");
							$query =mysql_query("select id_img from imagenes where id_img = 4", $link);
							$ruta ="img/" . $_FILES["archivo"]["name"];
							if (mysql_num_rows($query) == 0) {
								mysql_query("insert into imagenes (ruta_img, id_img) values ('$ruta', 4)", $link) or die (mysql_error());
							}
							else mysql_query("UPDATE imagenes SET ruta_img='$ruta' where id_img = 4");
							mysql_close($link); 
						}
				
						if (isset($_SESSION['nick_usuario']) && ($_SESSION['nick_usuario']) == 'admin') {
							echo '<form action="index.php" method="post" enctype="multipart/form-data">';
								echo '<label for="archivo"></label>';
								echo '<input type="file" name="archivo" id="archivo" /><br>';
								echo '<button type="submit" class="btn" name="Guardar6" value="guardar">Guardar imagen</button>';
							echo '</form>';
						}
						else {
							include ("conectar_mysql.php");
							$query =mysql_query("select ruta_img from imagenes where id_img = 4", $link);
							$fila =mysql_fetch_array($query);
							echo '<div id="altura"><img src="' . $fila['ruta_img'] . '"width="300"></div>';
							mysql_close($link); 
						}
					echo '</div>'; //div span3
				echo '</div>'; //div row-fluid
			echo '</div>'; //div span3
			
			echo '<div class="span9">';
				echo '<div class="row-fluid">';
					if (isset($_REQUEST['Guardar1'])) { 
						include ("conectar_mysql.php");
						$query =mysql_query("select id_texto from textos where id_texto = 5", $link);
						if (mysql_num_rows($query) == 0) {
							mysql_query("insert into textos (texto, titulo, id_texto) values ('$_REQUEST[texto]', '$_REQUEST[titulo]', 5)", $link) or die (mysql_error());
						}
						else mysql_query("UPDATE textos SET texto='$_REQUEST[texto]', titulo='$_REQUEST[titulo]' where id_texto = 5");
						mysql_close($link); 
					}
						
					if (isset($_SESSION['nick_usuario']) && ($_SESSION['nick_usuario']) == 'admin') {
						include ("conectar_mysql.php");
						$query =mysql_query("select texto, titulo from textos where id_texto = 5", $link);
						$fila =mysql_fetch_array($query);
						echo '<form action="index.php" method="post">';
							echo '<input type="text" class="input-large" name="titulo" value="' . $fila['titulo'] . '" placeholder="Escriba aquí el título ..." required>';
							echo '<label for="texto"></label>';
							echo '<textarea name="texto" rows=8 id="texto" placeholder="Actualizar información ..." required>' . $fila['texto'] . '</textarea>'; 
							echo '<button type="submit" class="btn" name="Guardar1" value="guardar">Guardar info</button>';
						echo '</form>';
						mysql_close($link);
					}
					else {
						include ("conectar_mysql.php");
						$query =mysql_query("select texto, titulo from textos where id_texto = 5", $link);
						$fila =mysql_fetch_array($query);
						echo '<legend><h3>'. $fila['titulo'] .'</h3></legend>';
						echo '<p>' . $fila['texto'] . '</p>';
						mysql_close($link); 	
					}
				echo '</div>';
				
				echo '<br><div class="row-fluid">';
					if (isset($_REQUEST['Guardar2'])) { 
						include ("conectar_mysql.php");
						$query =mysql_query("select id_texto from textos where id_texto = 6", $link);
						if (mysql_num_rows($query) == 0) {
							mysql_query("insert into textos (texto, titulo, id_texto) values ('$_REQUEST[texto]', '$_REQUEST[titulo]', 6)", $link) or die (mysql_error());
						}
						else mysql_query("UPDATE textos SET texto='$_REQUEST[texto]', titulo='$_REQUEST[titulo]' where id_texto = 6");
						mysql_close($link); 
					}
						
					if (isset($_SESSION['nick_usuario']) && ($_SESSION['nick_usuario']) == 'admin') {
						include ("conectar_mysql.php");
						$query =mysql_query("select texto, titulo from textos where id_texto = 6", $link);
						$fila =mysql_fetch_array($query);
						echo '<form action="index.php" method="post">';
							echo '<input type="text" class="input-large" name="titulo" value="' . $fila['titulo'] . '" placeholder="Escriba aquí el título ..." required>';
							echo '<label for="texto"></label>';
							echo '<textarea name="texto" rows=8 id="texto" placeholder="Actualizar información ..." required>' . $fila['texto'] . '</textarea>'; 
							echo '<button type="submit" class="btn" name="Guardar2" value="guardar">Guardar info</button>';
						echo '</form>';
						mysql_close($link);
					}
					else {
						include ("conectar_mysql.php");
						$query =mysql_query("select texto, titulo from textos where id_texto = 6", $link);
						$fila =mysql_fetch_array($query);
						echo '<legend><h3>'. $fila['titulo'] .'</h3></legend>';
						echo '<p>' . $fila['texto'] . '</p>';
						mysql_close($link); 	
					}
				echo '</div>';
			echo '</div>'; // div span9
			
		echo '</div>'; //div row-fluid
		*/
?>
		
		<!--<br><div class="row-fluid" id="footer">
			<div class="span12">
				<p><center>Pie de la pagina</center></p>
			</div>
		</div>
	</div>-->
	
	<?php
		/*if (isset($_GET['Enviar'])) {
			include("class.phpmailer.php");							  // Clase para usar funciones para enviar email.
			include("class.smtp.php");								  // Clase para conexión via smtp. 
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->CharSet = 'UTF-8';
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = 'ssl';
			$mail->Host = "smtp.gmail.com"; 						  // Servidor smtp.
			$mail->Port = 465; 										  // Puerto smtp de gmail.  
			$mail->Username = 'marketingdigitalnetworks@gmail.com';  
			$mail->Password = 'mdnj2013';
			$mail->FromName = 'Marketing Digital Netowork';	 
			$mail->From = 'marketingdigitalnetworks@gmail.com';				// Email de remitente desde donde se envía el correo.
			
			$mail->WordWrap = 85; 								  
			$mail->AltBody = 'text plano';   
			$mail->Subject = 'Contacto cliente'; 

			$body ='<strong>A continuaci&oacute;n se detallan los datos para entrar en contacto con el emisor del mensaje:</strong><br><br>';
			$body .= '<strong><font color="blue">Email:  </strong></font>' . $_REQUEST['email'] . '<br>';
			$body .= '<strong><font color="blue">Telefono:  </strong></font>' . $_REQUEST['telefono'] . '<br><br><br>';
			$body .= '<strong><font color="blue">Mensaje del emisor:  </strong></font><br><br>' . $_REQUEST['texto'];
			$mail->Body =$body;
	
			$mail->AddAddress("marketingdigitalnetworks@gmail.com");			
			if (!$mail->Send()) echo $mail->ErrorInfo;				 // Error si no se envía correctamente el email.
		}*/
	?>
	<script src="js/bootstrap-carousel.js"></script> 
	<script src="js/bootstrap-modal.js"></script>
	<script src="js/bootstrap-transition.js"></script>
</body>
</html>