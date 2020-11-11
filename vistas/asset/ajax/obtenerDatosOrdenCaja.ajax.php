<?php 

session_start();

include "../../../modelos/box.modelo.php";
include "../../../modelos/listaTrabajos.modelo.php";

$idUsuario = $_SESSION['dentista'];
$idBox = $_POST['idBox'];

$datosBox = modeloBox::mdlDatosBox($idBox);
$imagenesBox = modeloBox::mdlImagenesBox($idBox);
$datosBox['imagenes'] = $imagenesBox; //Agregamos las imagenes al array en caso de que tenga

if( strlen($datosBox['id_prod']) >= 1 ){
	$infoTrabajoBox = modeloListaTrabajos::mdlInfoTrabajo($datosBox['id_prod'],"protesis");
	$infoTrabajoUrgenteBox = modeloListaTrabajos::mdlInfoTrabajoUrgente($datosBox['id_prod'],"protesis");
} elseif( strlen($datosBox['id_prod_ort']) >= 1 ){
	$infoTrabajoBox = modeloListaTrabajos::mdlInfoTrabajo($datosBox['id_prod_ort'],"ortodoncia");
	$infoTrabajoUrgenteBox = modeloListaTrabajos::mdlInfoTrabajoUrgente($datosBox['id_prod_ort'],"ortodoncia");
}

$datosBox['trabajo'] = $infoTrabajoBox; //Agregamos la información del producto de laboratorio
$datosBox['trabajoUrgente'] = $infoTrabajoUrgenteBox; //Agregamos la información del producto urgente del laboratorio

echo json_encode($datosBox);

 ?>