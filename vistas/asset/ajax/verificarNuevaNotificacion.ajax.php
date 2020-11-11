<?php 

session_start();

include "../../../modelos/notificaciones.modelo.php";

if(isset($_SESSION['dentista'])){
	$tipo = "dentista";
	$idUsuario = $_SESSION['dentista'];
} else if(isset($_SESSION['tecnico'])){
	$tipo = "tecnico";
	$idUsuario = $_SESSION['tecnico'];
} else {
	$idUsuario = NULL;
	$tipo = NULL;
}

$timestamp = $_POST['timestampNotificacion'];

$respuesta = modeloNotificacion::mdlCompararUltimaNotificacion($idUsuario, $tipo, $timestamp);

if($respuesta != 0){

	echo json_encode($respuesta);

} else {

	echo $respuesta;

}


 ?>