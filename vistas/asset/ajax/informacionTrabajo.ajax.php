<?php 

session_start();

include "../../../modelos/listaTrabajos.modelo.php";

$idUsuario = $_SESSION['tecnico'];
$queEs = $_POST['queEs'];
$idTrabajo = $_POST['idTrabajo'];

$infoTrabajo = modeloListaTrabajos::mdlObtenerInfoTrabajo($idUsuario,$queEs,$idTrabajo);

echo json_encode($infoTrabajo);

 ?>