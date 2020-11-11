<?php 

session_start();

include "../../../modelos/orden.modelo.php";

$idOrden = $_POST['idOrden'];
$status = $_POST['status'];
$idHist = $_POST['idHist'];

if(isset($_SESSION['tecnico'])) {
	
	$idUsuario = $_SESSION['tecnico'];
	$es = "tecnico";

} elseif(isset($_SESSION['dentista'])){
		
	$idUsuario = $_SESSION['dentista'];
	$es = "dentista";

}	

$respuesta = modeloOrden::mdlStatusOrden($idUsuario,$es,$idOrden,$status,$idHist);

echo $respuesta;


 ?>