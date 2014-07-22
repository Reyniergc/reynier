<?php
	function envioEmail($opcion) {
	
		require("class.phpmailer.php");							  // Clase para usar funciones para enviar email.
		require("class.smtp.php");								  // Clase para conexión via smtp. 
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->CharSet = 'UTF-8';
		$mail->SMTPAuth = true;									  // Le indicamos que el servidor smtp requiere autenticación.
		$mail->SMTPSecure = 'ssl';
		
		$mail->Host = "smtp.gmail.com"; 						  // Servidor smtp.
		
		$mail->Port = 465; 										  // Puerto smtp de gmail. 
		
		$mail->Username = 'marketingdigitalnetworks@gmail.com';
		
		$mail->Password = 'mdnj2013';
		
		$mail->FromName = 'Marketing Digital Networks';	 
		
		$mail->From = 'marketingdigitalnetworks@gmail.com';	 // Email de remitente desde donde se envía el correo.

		require("conectar_mysql.php");
		
		if ($opcion == 1) { // Para comprobar si el correo electronico existe antes de enviarle la contraseña.
			$query = mysql_query("select email from usuarios where email = '$_REQUEST[email]'", $link);
			if (mysql_num_rows($query) > 0) echo '1';
			return;
		}

		$query = mysql_query("select AES_DECRYPT(password, cadena), nick, email from usuarios where email = '$_REQUEST[email]'", $link);
		
		$Fila = mysql_fetch_array($query);
		$mail->WordWrap = 85; 								  
		$mail->AltBody = 'text plano';   
		$mail->Subject = 'Datos de usuario'; 
		
		if ($opcion == 2) {
			$mail->Body = 'A continuaci&oacute;n se detallan sus datos de acceso para la web: Marketing Digital Networks.'
			.'<br><br>Usuario: ' . $Fila['nick'] . '<br><br>Contraseña: ' . $Fila["AES_DECRYPT(password, cadena)"];
		}
		elseif ($opcion == 3) {			
			$mail->Body = '<h3><b>Esta web utiliza Cookies de sesión exclusivamente para garantizar el correcto funcionamiento del portal. Las cookies de sesión almacenan datos únicamente mientras el usuario accede a la web.</b></h3>' 
			
			.'<br><br><br>A continuaci&oacute;n se detallan sus datos con los que fueron dados de alta su empresa para acceso a la web: Marketing Digital Networks. <br>
			Puede modificarlos en cualquier momento desde su cuenta "Editar Perfil.'
			
			.'<br><br>Usuario: ' . $Fila['nick'] . '<br><br>Contraseña: ' . $Fila["AES_DECRYPT(password, cadena)"];
		}
		mysql_close($link); 
		
		$mail->AddAddress($Fila['email']); 			
		if (!$mail->Send()) echo $mail->ErrorInfo;				 // Error si no se envía correctamente el email.
	}
	
	envioEmail($_REQUEST['opcion']);
?>