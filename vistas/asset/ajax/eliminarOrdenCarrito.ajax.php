<?php 

session_start();

include "../../../modelos/box.modelo.php";

if(isset($_SESSION['dentista'])){
	$idUsuario = $_SESSION['dentista'];
} else {
	$idUsuario = NULL;
}

$idBox = $_POST['idBox'];

$respuesta = modeloBox::mdlEliminarOrden($idUsuario,$idBox);

echo $respuesta;

 ?>