<?php 

require_once "../../../modelos/orden.modelo.php";
require_once "../../../modelos/perfil.modelo.php";

session_start();

$idOrden = $_POST['idOrden'];
$timestamp = $_POST['timestamp'];

if(isset($_SESSION['tecnico'])){

    $idUsuario = $_SESSION['tecnico'];
    $tipo = 'tecnico';


} elseif(isset($_SESSION['dentista'])){

    $idUsuario = $_SESSION['dentista'];
    $tipo = 'dentista';

}

$respuesta = modeloOrden::mdlCompararUltimoMensajeOrden($idOrden,$timestamp);

if($respuesta != 0){ //SI HAY UN NUEVO MENSAJE

	if($tipo == "dentista"){

		if( !($respuesta['id_clie'] == $idUsuario) ){ //SÍ EL MENSAJE NUEVO NO ES DEL DENTISTA, MANDAMOS LOS DATOS DEL RECEPTOR OSEA EL LABORATORIO
			
			$datos = modeloPerfil::mdlDatosPerfil($respuesta['id_lab'],"tecnico");
			$respuesta['fotoPerfil'] = "tecnicos/".$datos['img_art'];
			$respuesta['nombre'] = $datos['nomb'];
			
		} else {

			$respuesta['fotoPerfil'] = NULL;
			$respuesta['nombre'] = NULL;

		}

	} elseif($tipo == "tecnico") {

		if( !($respuesta['id_lab'] == $idUsuario) ){ //SÍ EL MENSAJE NUEVO NO ES DEL TECNICO, MANDAMOS LOS DATOS DEL RECEPTOR OSEA EL DENTISTA

			$datos = modeloPerfil::mdlDatosPerfil($respuesta['id_clie'],"dentista");
			$respuesta['fotoPerfil'] = "dentistas/".$datos['img_perfil'];
			$respuesta['nombre'] = $datos['nomb'];

		} else {

			$respuesta['fotoPerfil'] = NULL;
			$respuesta['nombre'] = NULL;

		}

	}

	echo json_encode($respuesta);

} else {

	echo 0;

}

 ?>