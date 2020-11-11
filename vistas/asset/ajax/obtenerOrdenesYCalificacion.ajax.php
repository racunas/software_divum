<?php 

session_start();

include "../../../modelos/perfil.modelo.php";
include "../../../modelos/orden.modelo.php";

if(isset($_SESSION['tecnico'])){
	$idUsuario = $_SESSION['tecnico'];
	$es = "tecnico";
} elseif(isset($_SESSION['dentista'])){
	$idUsuario = $_SESSION['dentista'];
	$es = "dentista";
}

$calificaciones = modeloPerfil::mdlObtenerCalificaciones($idUsuario,$es);

$totalOrdenes = modeloOrden::mdlTotalOrdenes($idUsuario,$es);
$totalOrdenesFinalizadas = modeloOrden::mdlTotalOrdenesFinalizadas($idUsuario,$es);

$respuesta = array();

$respuesta['totalOrdenes'] = (empty($totalOrdenes)) ? 0 : count($totalOrdenes);
$respuesta['totalOrdenesFinalizadas'] = (empty($totalOrdenesFinalizadas)) ? 0 : count($totalOrdenesFinalizadas);

if(empty($calificaciones)){
	$respuesta['totalCalificacion'] = 0.0;
} else {

	$total = 0;

	foreach ($calificaciones as $key => $value) {
		
		$totalOrden = $value['precio'] + $value['tiempo'] + $value['calidad'];
		$total = $total + ($totalOrden / 3);

	}

	$promedio = $total / count($calificaciones);
	$respuesta['totalCalificacion'] = round($promedio,2);
	
}

echo json_encode($respuesta);



 ?>