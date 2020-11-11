<?php 

session_start();

include "../../../modelos/listaTrabajos.modelo.php";

$idTrabajo = $_POST['idTrabajo'];
$queEs = $_POST['queEs'];
$filtroTrabajo = $_POST['filtroTrabajo'];

if(isset($_SESSION['tecnico'])){

    $idLab = $_SESSION['tecnico'];

}


$respuesta = modeloListaTrabajos::mdlEliminarTrabajoLista($idLab,$idTrabajo,$queEs,$filtroTrabajo);

echo $respuesta;

 ?>