<?php 

session_start();

include "../../../modelos/perfil.modelo.php";

$idDireccion = $_POST['id'];

$idUsuario = $_SESSION['dentista'];

$respuesta = modeloPerfil::mdlEliminarDireccion($idDireccion,$idUsuario);

echo $respuesta;

 ?>