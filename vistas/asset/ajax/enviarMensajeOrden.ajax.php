<?php 

session_start();
date_default_timezone_set('UTC');
date_default_timezone_set("America/Mexico_City");

require_once("../../../modelos/orden.modelo.php");
require_once("../../../modelos/perfil.modelo.php");
require_once("../../../modelos/notificaciones.modelo.php");

if(isset($_SESSION['tecnico'])){
    $idUsuario = $_SESSION['tecnico'];
    $tipo = "tecnico";
} elseif(isset($_SESSION['dentista'])){
    $idUsuario = $_SESSION['dentista'];
    $tipo = "dentista";
}

$idOrden = $_POST['idOrden'];
$mensaje = $_POST['mensaje'];
$url = $_POST['url'];

$respuesta = modeloOrden::mdlEnviarMensajeOrden($idUsuario,$tipo,$idOrden,$mensaje);

$fechaActual = date("Y-m-d");

if($respuesta){

	$datosPerfil = modeloPerfil::mdlDatosPerfil($idUsuario,$tipo);

	$fotoPerfil = ($tipo == "tecnico") ? "tecnicos/".$datosPerfil['img_art'] : "dentistas/".$datosPerfil['img_perfil'];
	$nombre = $datosPerfil['nomb'];

	$array = array();
	$array['fechaActual'] = $fechaActual;
	$array['mensaje'] = $mensaje;
	$array['fotoPerfil'] = $fotoPerfil;
	$array['nombre'] = $nombre;

	//MANDAMOS LA NOTIFICACION AL RECEPTOR DE ESTE MENSAJE

	$datosOrden = modeloOrden::mdlResumenOrden($idUsuario,$tipo,$idOrden);

	$paciente = $datosOrden['paciente'];
	$receptor = $datosOrden['nombre'];
	$idReceptor = $datosOrden['id'];
	$tipoReceptor = ($tipo == "dentista") ? 'tecnico' : 'dentista';

	$tipoNotificacion = "nuevoMensaje";
	
	$titulo = "Tienes un mensaje nuevo";
	$mensaje = ($tipo == "dentista") ? 
				'El dentista <b>'.$nombre.'</b> te mandó un mensaje en la orden del paciente: <b>'.$paciente.'</b>'
				: 

				'El laboratorio <b>'.$nombre.'</b> te mandó un mensaje en la orden del paciente: <b>'.$paciente.'</b>'; 
	$color = '#21447c';
	$icono = 'fas fa-comment-dots';
	$ruta = "ordenes?orden=".$idOrden."&filtroOrden=chat";
	$auxiliar = $idOrden;

	if( modeloNotificacion::mdlInsertarNuevaNotificacion($idReceptor,$tipoReceptor,$titulo,$mensaje,$ruta,$color,$icono,$tipoNotificacion,$auxiliar) ){

		echo json_encode($array);

	}

	//////////////////////////////////////////////////////


} else {

	echo $respuesta;

}

 ?>