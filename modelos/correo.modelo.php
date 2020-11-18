<?php 

use PHPMailer\PHPMailer\PHPMailer;
require_once '../../../vendor/autoload.php';
require_once 'conexion.php';
require_once '../../../controladores/ruta.controlador.php';

class modeloCorreo{

	public static function mdlRestablecerContraseña($correo){

		$mail = new PHPMailer;
		$db = new Conexion();
		$url = ruta::obtenerRuta();

		$aleatorio = uniqid();

		$sqlTecnicos = $db -> query("SELECT * FROM usuarios_tecnicos WHERE correo = '$correo'");
		$sqlDentistas = $db -> query("SELECT * FROM usuarios WHERE correo = '$correo'");

		if( ($db->rows($sqlDentistas) >= 1) ){


			$datos = $sqlDentistas -> fetch_array(MYSQLI_ASSOC);
			$idClie = $datos['id_clie'];
			$sqlInfoDentista = $db -> query("SELECT * FROM cliente WHERE id_clie = $idClie");
			$datosInfoDentista = $sqlInfoDentista -> fetch_array(MYSQLI_ASSOC);


			$nombreReceptor = $datosInfoDentista['nomb'];

			//DAR DE ALTA EL CODIGO ALEATORIO EN LA TABLA DE USUARIOS

			if($db -> query("UPDATE usuarios SET aleatorio = '$aleatorio' WHERE id_clie = '$idClie'")){

				$mensaje = 'Hola '.ucfirst($nombreReceptor).', para restablecer tu contraseña, debes dar clic en el siguiente enlace: <br>';

				$mensaje .= $url.'restablecer-password?key='.$aleatorio;

				$mail->isSMTP(); //SOLO PARA LOCALHOST
				//$mail->isSendMail();
				$mail->SMTPDebug = 2;
				$mail->Host = 'smtp.ionos.mx';
				$mail->Port = 587;
				$mail->SMTPAuth = true;
				$mail->Username = 'contacto@buscalab.com';
				$mail->Password = 'PINTOhome.1';
				$mail->setFrom('contacto@buscalab.com', 'Buscalab');
				//$mail->addReplyTo('reply-box@hostinger-tutorials.com', 'Your Name');
				$mail->addAddress($correo, $nombreReceptor);
				$mail->Subject = 'Restablece tu password de Buscalab';
				$mail->msgHTML($mensaje);
				$mail->AltBody = $mensaje;
				//$mail->addAttachment('test.txt');
				if (!$mail->send()) {
				    //return 'Mailer Error: ' . $mail->ErrorInfo;
				    return 'Mailer Error: ';
				} else {
				    return "dentista";
				}

			}

		} elseif( ($db->rows($sqlTecnicos) >= 1 ) ) {


			$datos = $sqlTecnicos -> fetch_array(MYSQLI_ASSOC);
			$idLab = $datos['id_lab'];
			$sqlInfoLaboratorio = $db -> query("SELECT * FROM laboratorio WHERE id_lab = $idLab");
			$datosInfoLaboratorio = $sqlInfoLaboratorio -> fetch_array(MYSQLI_ASSOC);


			$nombreReceptor = $datosInfoLaboratorio['nomb_art'];

			//DAR DE ALTA EL CODIGO ALEATORIO EN LA TABLA DE USUARIOS_TECNICOS

			if($db -> query("UPDATE usuarios_tecnicos SET aleatorio = '$aleatorio' WHERE id_lab = '$idLab'")){

				$mensaje = 'Hola '.ucfirst($nombreReceptor).', para restablecer tu contraseña, debes dar clic en el siguiente enlace: <br>';

				$mensaje .= $url.'restablecer-password?key='.$aleatorio;

				$mail->isSMTP(); //SOLO PARA LOCALHOST
				//$mail->isSendMail(); //SOLO PARA HOSTING 1AND1
				$mail->SMTPDebug = 2;
				$mail->Host = 'smtp.ionos.mx';
				$mail->Port = 587;
				$mail->SMTPAuth = true;
				$mail->Username = 'contacto@buscalab.com';
				$mail->Password = 'PINTOhome.1';
				$mail->setFrom('contacto@buscalab.com', 'Buscalab');
				//$mail->addReplyTo('reply-box@hostinger-tutorials.com', 'Your Name');
				$mail->addAddress($correo, $nombreReceptor);
				$mail->Subject = 'Restablece tu contraseña de Buscalab';
				//$mail->msgHTML(file_get_contents('message.html'), __DIR__);
				$mail->msgHTML($mensaje);
				$mail->AltBody = $mensaje;
				//$mail->addAttachment('test.txt');
				if (!$mail->send()) {
				    //return 'Mailer Error: ' . $mail->ErrorInfo;
				    return 'Mailer Error: ';
				} else {
				    return "tecnico";
				}
				

			}

		} else {

			return "no existe";

		}


	}

}

 ?>