<?php 

session_start();

include "../../../modelos/perfil.modelo.php";

$calle = $_POST['calle'];
$idSepomex = $_POST['idSepomex'];
$cp = $_POST['cp'];
$id = $_SESSION['dentista'];

$exito = modeloPerfil::mdlAgregarDireccion($calle, $idSepomex, $cp, $id);

echo $exito;



 ?>