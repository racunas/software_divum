<?php 

session_start();

include "../../../modelos/perfil.modelo.php";

$idDireccion = $_POST['id'];

$idUsuario = $_SESSION['dentista'];

$respuesta = modeloPerfil::mdlDireccionPredeterminada($idDireccion,$idUsuario);

echo $respuesta;

 ?>