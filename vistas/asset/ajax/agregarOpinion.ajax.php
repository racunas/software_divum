<?php 

session_start();

include "../../../modelos/orden.modelo.php";

if(isset($_SESSION['dentista'])){

	$idUsuario = $_SESSION['dentista'];

}

$calif1 = $_POST['calif1'];
$calif2 = $_POST['calif2'];
$calif3 = $_POST['calif3'];
$opinion = $_POST['textopinion'];
$idOrden = $_POST['id'];
$idTrabajo = $_POST['idTrabajo'];
$tipo = $_POST['tipo'];

$respuesta = modeloOrden::mdlAgregarOpinion($idUsuario,$calif1,$calif2,$calif3,$opinion,$idOrden,$idTrabajo,$tipo);

echo $respuesta;

 ?>