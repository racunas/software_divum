<?php 
date_default_timezone_set('UTC');
date_default_timezone_set("America/Mexico_City");
session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

include "../../../modelos/notificaciones.modelo.php";
include "../../../modelos/perfil.modelo.php";
include "../../../modelos/orden.modelo.php";

if(isset($_SESSION['tecnico'])){

    $idUsuario = $_SESSION['tecnico'];
    $tipo = "tecnico";
    $carpetaPerfil = "dentistas/";

} elseif(isset($_SESSION['dentista'])){

    $idUsuario = $_SESSION['dentista'];
    $tipo = "dentista";
    $carpetaPerfil = "tecnicos/";

}

$fecha = date("Y-m-d");
//$fecha = "2019-04-12";
$url = $_POST['url'];
$notificacion = "";

$respuesta = modeloNotificacion::mdlObtenerNotificaciones($idUsuario,$tipo,$fecha);
//$datosUsuario = modeloPerfil::mdlDatosPerfil($idUsuario,$tipo);

//$fotoPerfil = ($tipo == "tecnico") ? "tecnicos/".$datosUsuario['img_art'] : "dentistas/".$datosUsuario['img_perfil'];

if($respuesta != null){

	foreach ($respuesta as $key => $value) {

		$auxiliar = $value['auxiliar'];

		$fecha = explode(" ", $value['fecha']);

		$fotoPerfil = modeloOrden::mdlFotoPerfilOrden($idUsuario,$tipo,$auxiliar);

		$notificacion .=

		'<a href="'.$url.$value['url'].'">
			<div class="notificacion row no-gutters">
				
				<div class="col-lg-3 col-md-3 col-sm-3 col-3">
					<img src="'.$url.'vistas/asset/images/'.$carpetaPerfil.$fotoPerfil['fotoPerfil'].'" alt="Tecnico Dental Online">
				</div>

				<div class="col-lg-9 col-md-9 col-sm-9 col-9 mensajeNotificacion text-justify">
					<p><i class="'.$value['icono'].' iconoNotificacion mr-2"></i> '.$value['mensaje'].' <br> <span class="float-right">'.$fecha[1].'</span></p>
				</div>

			</div>
		</a>';

	}

	echo $notificacion;

} else {

	$notificacion = '<h3 class="text-muted text-center pt-5">¡No hay notificacion recientes!</h3>';

	echo $notificacion;

}

 ?>