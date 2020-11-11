<?php 

session_start();

include "../../../modelos/orden.modelo.php";

if(isset($_SESSION['dentista'])){
	$tipo = "dentista";
	$idUsuario = $_SESSION['dentista'];
} else if(isset($_SESSION['tecnico'])){
	$tipo = "tecnico";
	$idUsuario = $_SESSION['tecnico'];
}

$idOrden = $_POST['idOrden'];

$respuesta = modeloOrden::mdlMarcarMensajesComoLeidos($idUsuario,$tipo,$idOrden);

 ?>