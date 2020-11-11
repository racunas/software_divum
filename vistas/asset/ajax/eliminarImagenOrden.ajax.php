<?php 

session_start();

include "../../../modelos/box.modelo.php";

$idUsuario = $_SESSION['dentista'];
$idBox = $_POST['idBox'];
$idImagen = $_POST['idImagen'];

$respuesta = modeloBox::mdlEliminarImagenBox($idBox,$idImagen);

echo $respuesta;

 ?>