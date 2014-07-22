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
					<a href="index.php"><img src="img/logo.jpg" alt="Logo Marketing Digital Networks" style="width: 50%"></a>
				</div>
				<div class="span5">
					<ul class="nav nav-pills">
						<li><a href="index.php"><h4>Inicio</h4></a></li>				
						<li class="active"><a href="trabaja_con_nosotros.php"><h4>Trabaja con nosotros</h4></a></li>
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
										<!--<legend>Legend text</legend> -->
										<div class="control-group">
											<label class="control-label" for="email"></label>
											<div class="controls">
												<input type="email" name="email" id="email" class="input-large" placeholder="Email ...">
												<p class="help-block">* Introduzca su email.</p>
											</div>
										</div>
										
										<div class="control-group">
											<label class="control-label" for="telefono"></label>
											<div class="controls">
												<input type="text" name="telefono" id="telefono" placeholder="Introduza su telefono ...">
												<p class="help-block">* Introduzca su telefono.</p>
											</div>
										</div>
										
										<div class="control-group">
											<label class="control-label" for="texto"></label>
											<div class="controls">
												<textarea name="texto" rows=8 cols=50 id="texto" placeholder="Introduza su pregunta ..."></textarea>
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
					else { // Muestra la bienvenida y un botom para salir cuando el usuario esta logueado. width: 70%;  style="height: 40%".
						echo '<span id="logoff2">' . 'Bienvenido, ' . '</span>' . '<span id="logoff1">' . $_SESSION["usuariologueado"] .  '</span>' . '<span id="logoff"><a href=logoff.php><img src="img/log_off_16.png" title="Salir"></a></span>';
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
		
<?php		
		echo '<div class="row-fluid">';
			echo '<div class="span3">';
				echo '<br><div class="row-fluid">';
					echo '<div class="span3">';
						if (isset($_REQUEST['Guardar5'])) { 
							include ("conectar_mysql.php");
							$query =mysql_query("select id_img from imagenes where id_img = 1", $link);
							$ruta ="img/" . $_FILES["archivo"]["name"];
							if (mysql_num_rows($query) == 0) {
								mysql_query("insert into imagenes (ruta_img, id_img) values ('$ruta', 1)", $link) or die (mysql_error());
							}
							else mysql_query("UPDATE imagenes SET ruta_img='$ruta' where id_img = 1");
							mysql_close($link); 
						}
						
						if (isset($_SESSION['usuariologueado']) && ($_SESSION['usuariologueado']) == 'admin') {
							echo '<form action="trabaja_con_nosotros.php" method="post" enctype="multipart/form-data">';
								echo '<label for="archivo"></label>';
								echo '<input type="file" name="archivo" id="archivo" /><br>';
								echo '<button type="submit" class="btn" name="Guardar5" value="guardar">Guardar imagen</button>';
							echo '</form>';
						}
						else {
							include ("conectar_mysql.php");
							$query =mysql_query("select ruta_img from imagenes where id_img = 1", $link);
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
							$query =mysql_query("select id_img from imagenes where id_img = 2", $link);
							$ruta ="img/" . $_FILES["archivo"]["name"];
							if (mysql_num_rows($query) == 0) {
								mysql_query("insert into imagenes (ruta_img, id_img) values ('$ruta', 2)", $link) or die (mysql_error());
							}
							else mysql_query("UPDATE imagenes SET ruta_img='$ruta' where id_img = 2");
							mysql_close($link); 
						}
				
						if (isset($_SESSION['usuariologueado']) && ($_SESSION['usuariologueado']) == 'admin') {
							echo '<form action="trabaja_con_nosotros.php" method="post" enctype="multipart/form-data">';
								echo '<label for="archivo"></label>';
								echo '<input type="file" name="archivo" id="archivo" /><br>';
								echo '<button type="submit" class="btn" name="Guardar6" value="guardar">Guardar imagen</button>';
							echo '</form>';
						}
						else {
							include ("conectar_mysql.php");
							$query =mysql_query("select ruta_img from imagenes where id_img = 2", $link);
							$fila =mysql_fetch_array($query);
							echo '<div id="altura"><img src="' . $fila['ruta_img'] . '"width="300"></div>';
							mysql_close($link); 
						}
					echo '</div>'; //div span3
				echo '</div>'; //div row-fluid
			echo '</div>'; //div span3
		
			echo '<div class="span9">';
				echo '<div class="hero-unit">';
					if (isset($_REQUEST['Guardar4'])) { 
						include ("conectar_mysql.php");
						$query =mysql_query("select id_texto from textos where id_texto = 4", $link);
						if (mysql_num_rows($query) == 0) {
							mysql_query("insert into textos (texto, titulo, id_texto) values ('$_REQUEST[texto]', '$_REQUEST[titulo]', 4)", $link) or die (mysql_error());
						}
						else mysql_query("UPDATE textos SET texto='$_REQUEST[texto]', titulo='$_REQUEST[titulo]' where id_texto = 4");
						mysql_close($link); 
					}
						
					if (isset($_SESSION['usuariologueado']) && ($_SESSION['usuariologueado']) == 'admin') {
						include ("conectar_mysql.php");
						$query =mysql_query("select texto, titulo from textos where id_texto = 4", $link);
						$fila =mysql_fetch_array($query);
						echo '<form action="trabaja_con_nosotros.php" method="post">';
							echo '<input type="text" class="input-large" name="titulo" value="' . $fila['titulo'] . '" placeholder="Escriba aquí el título ..." required>';
							echo '<label for="texto"></label>';
							echo '<textarea name="texto" rows=8 id="texto" placeholder="Actualizar información ..." required>' . $fila['texto'] . '</textarea>'; 
							echo '<button type="submit" class="btn" name="Guardar4" value="guardar">Guardar info</button>';
						echo '</form>';
						mysql_close($link);
					}
					else {
						include ("conectar_mysql.php");
						$query =mysql_query("select texto, titulo from textos where id_texto = 4", $link);
						$fila =mysql_fetch_array($query);
						echo '<legend><h1>'. $fila['titulo'] .'</h1></legend>';
						echo '<p>' . $fila['texto'] . '</p>';
						mysql_close($link); 	
					}
				echo '</div>'; //div
				
				echo '<div class="row-fluid">';
					echo '<div class="span4">';
						if (isset($_REQUEST['Guardar1'])) { 
							include ("conectar_mysql.php");
							$query =mysql_query("select id_texto from textos where id_texto = 1", $link);
							if (mysql_num_rows($query) == 0) {
								mysql_query("insert into textos (texto, titulo, id_texto) values ('$_REQUEST[texto]', '$_REQUEST[titulo]', 1)", $link) or die (mysql_error());
							}
							else mysql_query("UPDATE textos SET texto='$_REQUEST[texto]', titulo='$_REQUEST[titulo]' where id_texto = 1");
							mysql_close($link); 
						}
						
						if (isset($_SESSION['usuariologueado']) && ($_SESSION['usuariologueado']) == 'admin') {
							include ("conectar_mysql.php");
							$query =mysql_query("select texto, titulo from textos where id_texto = 1", $link);
							$fila =mysql_fetch_array($query);
							echo '<form action="trabaja_con_nosotros.php" method="post">';
								echo '<input type="text" class="input-large" name="titulo" value="' . $fila['titulo'] . '" placeholder="Escriba aquí el título ..." required>';
								echo '<label for="texto"></label>';
								echo '<textarea name="texto" rows=8 id="texto" placeholder="Actualizar información ..." required>' . $fila['texto'] . '</textarea>'; 
								echo '<button type="submit" class="btn" name="Guardar1" value="guardar">Guardar info</button>';
							echo '</form>';
							mysql_close($link);
						}
						else {
							include ("conectar_mysql.php");
							$query =mysql_query("select texto, titulo from textos where id_texto = 1", $link);
							$fila =mysql_fetch_array($query);
							//echo '<h3>'. $fila['titulo'] .'</h3>';
							echo '<legend><h3>'. $fila['titulo'] .'</h3></legend>';
							echo '<p>' . $fila['texto'] . '</p>';
							mysql_close($link); 	
						}
					echo '</div>'; // span4.
			
					echo '<div class="span4">';
						if (isset($_REQUEST['Guardar2'])) { 
							include ("conectar_mysql.php");
							$query =mysql_query("select id_texto from textos where id_texto = 2", $link);
							if (mysql_num_rows($query) == 0) {
								mysql_query("insert into textos (texto, titulo, id_texto) values ('$_REQUEST[texto]', '$_REQUEST[titulo]', 2)", $link) or die (mysql_error());
							}
							else mysql_query("UPDATE textos SET texto='$_REQUEST[texto]', titulo='$_REQUEST[titulo]' where id_texto = 2");
							mysql_close($link); 
						}
						
						if (isset($_SESSION['usuariologueado']) && ($_SESSION['usuariologueado']) == 'admin') {
							include ("conectar_mysql.php");
							$query =mysql_query("select texto, titulo from textos where id_texto = 2", $link);
							$fila =mysql_fetch_array($query);
							echo '<form action="trabaja_con_nosotros.php" method="post">';
								echo '<input type="text" class="input-large" name="titulo" value="' . $fila['titulo'] . '" placeholder="Escriba aquí el título ..." required>';
								echo '<label for="texto"></label>';
								echo '<textarea name="texto" rows=8 id="texto" placeholder="Actualizar información ..." required>' . $fila['texto'] . '</textarea>'; 
								echo '<button type="submit" class="btn" name="Guardar2" value="guardar">Guardar info</button>';
							echo '</form>';
							mysql_close($link);
						}
						else {
							include ("conectar_mysql.php");
							$query =mysql_query("select texto, titulo from textos where id_texto = 2", $link);
							$fila =mysql_fetch_array($query);
							echo '<legend><h3>'. $fila['titulo'] .'</h3></legend>';
							echo '<p>' . $fila['texto'] . '</p>';
							mysql_close($link); 	
						}
					echo '</div>'; // span4.
					
					echo '<div class="span4">';
						if (isset($_REQUEST['Guardar3'])) { 
							include ("conectar_mysql.php");
							$query =mysql_query("select id_texto from textos where id_texto = 3", $link);
							if (mysql_num_rows($query) == 0) {
								mysql_query("insert into textos (texto, titulo, id_texto) values ('$_REQUEST[texto]', '$_REQUEST[titulo]', 3)", $link) or die (mysql_error());
							}
							else mysql_query("UPDATE textos SET texto='$_REQUEST[texto]', titulo='$_REQUEST[titulo]' where id_texto = 3");
							mysql_close($link); 
						}
						
						if (isset($_SESSION['usuariologueado']) && ($_SESSION['usuariologueado']) == 'admin') {
							include ("conectar_mysql.php");
							$query =mysql_query("select texto, titulo from textos where id_texto = 3", $link);
							$fila =mysql_fetch_array($query);
							echo '<form action="trabaja_con_nosotros.php" method="post">';
								echo '<input type="text" class="input-large" name="titulo" value="' . $fila['titulo'] . '" placeholder="Escriba aquí el título ..." required>';
								echo '<label for="texto"></label>';
								echo '<textarea name="texto" rows=8 id="texto" placeholder="Actualizar información ..." required>' . $fila['texto'] . '</textarea>'; 
								echo '<button type="submit" class="btn" name="Guardar3" value="guardar">Guardar info</button>';
							echo '</form>';
							mysql_close($link);
						}
						else {
							include ("conectar_mysql.php");
							$query =mysql_query("select texto, titulo from textos where id_texto = 3", $link);
							$fila =mysql_fetch_array($query);
							echo '<legend><h3>'. $fila['titulo'] .'</h3></legend>';
							echo '<p>' . $fila['texto'] . '</p>';
							mysql_close($link); 	
						}
					echo '</div>'; // span4
				echo '</div>';  //row fluid 
				
				if (isset($_REQUEST['enviar'])) { 
					include("class.phpmailer.php");							  // Clase para usar funciones para enviar email.
					include("class.smtp.php");								  // Clase para conexiónn via smtp. 
					$mail = new PHPMailer();
					$mail->IsSMTP();
					$mail->CharSet = 'UTF-8';
					$mail->SMTPAuth = true;
					$mail->SMTPSecure = 'ssl';
					$mail->Host = "smtp.gmail.com"; 						  // Servidor smtp.
					$mail->Port = 465; 										  // Puerto smtp de gmail.  	
					$mail->Username = 'marketingdigitalnetworks@gmail.com';
					$mail->Password = 'mdnj2013';
					$mail->FromName = 'Marketing Digital Networks';	 
					$mail->From = 'marketingdigitalnetworks@gmail.com';	 // Email de remitente desde donde se envía el correo.
					$mail->WordWrap = 85; 								  
					$mail->AltBody = 'text plano';   
					$mail->Subject = 'Contacto';
					$mail->AddAddress("marketingdigitalnetworks@gmail.com");
					
					$body ='<strong>A continuaci&oacute;n se detallan los datos para entrar en contacto con el emisor del mensaje:</strong><br><br>';
					$body .= '<strong><font color="blue">Nombre:  </strong></font>' . $_REQUEST['nombre'] . '<br>';
					$body .= '<strong><font color="blue">Email:  </strong></font>' . $_REQUEST['email'] . '<br>';
					$body .= '<strong><font color="blue">Telefono:  </strong></font>' . $_REQUEST['telefono'] . '<br><br><br>';
					$body .= '<strong><font color="blue">Mensaje del emisor:  </strong></font><br><br>' . $_REQUEST['mensaje'];
					$mail->Body =$body;
					
					if ($_FILES["archivo"]["size"] > 0) { // Para saber si se ha seleccionado un archivo.
						//if (strpos($_FILES["archivo"]["type"], "pdf") && ($_FILES["archivo"]["size"] < 1000000)) {
						if ((strpos($_FILES["archivo"]["type"], "pdf") || strpos($_FILES["archivo"]["type"], "doc")) && ($_FILES["archivo"]["size"] < 1000000)) {
							mkdir("dir_tmp");
							//Si es que hubo un error en la subida, mostrarlo, de la variable $_FILES podemos extraer el valor de [error], que almacena un valor booleano (1 o 0).
							if ($_FILES["archivo"]["error"] > 0) echo $_FILES["archivo"]["error"] . "<br />";
							// Si no hubo ningun error, procedemos a subir a la carpeta /dir_tmp.
							else move_uploaded_file($_FILES["archivo"]["tmp_name"], "dir_tmp/" . $_FILES["archivo"]["name"]);					
		
							$mail->AddAttachment("dir_tmp/" . $_FILES["archivo"]["name"], $_FILES["archivo"]["name"]); // Anexa un archivo al mensaje.
							if (!$mail->Send()) echo $mail->ErrorInfo;
					
							// Borra la carpeta temporal creada anteriormente.
							function eliminarDir($carpeta) {
								foreach (glob($carpeta . "/*") as $archivos_carpeta) {
									if (is_dir($archivos_carpeta)) eliminarDir($archivos_carpeta);
									else unlink($archivos_carpeta);
								}
								rmdir($carpeta);
							}
							eliminarDir("dir_tmp");
						}
						else echo '<script>' . 'alert("ERROR => Solo se permite subir archivos en formato PDF o DOC")' . '</script>';
					}
					else {
						if (!$mail->Send()) echo $mail->ErrorInfo;				 // Error si no se envía correctamente el email.
					}
				}
				
				echo '<div class="row-fluid">';
					echo '<div class="span7">'; 
						echo '<br><br><form action="trabaja_con_nosotros.php" method="post" class="form-horizontal" enctype="multipart/form-data">';
							echo '<fieldset>';
								echo '<legend>Pongase en contacto con nuestra empresa</legend>';
								echo '<div class="control-group">';
									echo '<div class="controls">';
										echo '<label for="nombre">Nombre:</label>';
										echo '<input type="text" name="nombre" class="input-xlarge" id="nombre" required>';
										
										echo '<label for="email"><img src="img/email.png"> Email:</label>';
										echo '<input type="email" name="email" class="input-xlarge" id="email" required>';
										
										echo '<label for="telefono"><img src="img/phone.png"> Telefono:</label>';
										echo '<input type="text" name="telefono" class="input-xlarge" id="telefono" required>';
										
										echo '<label for="contacto">Mensaje:</label>';
										echo '<textarea name="mensaje" rows=10 class="input-xlarge" id="contacto" placeholder="Introduzca su mensaje ..." required></textarea>';
										
										echo '<br><br><label for="archivo"><i class="icon-file"></i> Seleccione el currículum:</label>';
										echo '<input type="file" name="archivo" id="archivo" /><br>';
									echo '</div>';
								echo '</div>';
							echo '</fieldset>';
							echo '<div class="form-actions">';
								echo '<button type="submit" class="btn btn-primary offset1" name="enviar" value="enviar">Enviar Datos</button>';
							echo '</div>';
						echo '</form>';
					echo '</div>';  //span7
					
				echo '</div>';   //row fluid
				echo '</div>';  //row fluid
			echo '</div>';  //span9
		echo '</div>';  //row fluid
		
		echo '<hr>';
		echo '<footer>';
			echo '<p class="offset1">&copy; Marketing Digital Networks 2013 </p>';
		echo '</footer>';
    echo '</div>'; //.fluid-container
    echo '<br />';
?>	
	<script src="js/bootstrap-carousel.js"></script> 
	<script src="js/bootstrap-modal.js"></script>
	<script src="js/bootstrap-transition.js"></script>
</body>
</html>