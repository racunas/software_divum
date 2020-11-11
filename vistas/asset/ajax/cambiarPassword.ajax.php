<?php 

session_start();

include "../../../modelos/perfil.modelo.php";
include "../../../modelos/usuario.modelo.php";

if(isset($_SESSION['tecnico'])){

    $idUsuario = $_SESSION['tecnico'];
    $es = 0;
    $tipo = "tecnico";


} elseif(isset($_SESSION['dentista'])){

    $idUsuario = $_SESSION['dentista'];
    $es = 1;
    $tipo = "dentista";


}

//VARIABLES QUE SE RECIBEN DESDE JS

$passActual = $_POST['passActual'];

$newPass = $_POST['newPass'];

$confirmNewPass = $_POST['confirmNewPass'];

//OBTENEMOS EL CORREO DEL USUARIO

$usuario = modeloPerfil::mdlDatosPerfil($idUsuario,$tipo);

//OBTENEMOS LA CONTRASEÑA ACTUAL ENCRIPTADA DE LA BD

$datosLogin = mdlUsuario::mdlDatosLoginUsuario($usuario['email'],$es);

if( password_verify($passActual,$datosLogin['pass']) ){ //VERIFICAMOS QUE LA CONTRASEÑA PROPORCIONADA ES LA MISMA EN LA BD

	//SÍ ES LA MISMA CONTRASEÑA, VERIFICAMOS QUE LAS NUEVAS CONTRASEÑAS SEAN IGUALES Y SEAN DE 8 CARACTERES O MAS

	if( ($newPass == $confirmNewPass) && (strlen($newPass) >= 8) && (strlen($confirmNewPass) >= 8) ){

		//SI LAS NUEVAS CONTRASEÑAS SON IGUALES Y TIENEN 8 CARACTERES O MAS, DEBEMOS VERIFICAR QUE NO SEA IGUAL A LA ANTERIOR CONTRASEÑA
		if( !password_verify($newPass,$datosLogin['pass']) ){

			//SÍ NO SON IGUALES, MODIFICAMOS LA CONTRASEÑA EN LA BASE DE DATOS

			echo mdlUsuario::mdlCambiarPassword($newPass,$idUsuario,$tipo); //RETORNA TRUE EN CASO DE QUE SE HAYA CAMBIADO LA CONTRASEÑA EXITOSAMENTE, FALSE EN CASO DE QUE NO.

		} else{

			echo "sameBeforePassword";

		}

	} else {

		echo "noSameNewPassword";

	}
	
} else {

	echo "noSamePassword";

}



 ?>