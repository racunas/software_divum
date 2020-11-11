<?php 

session_start();

include "../../../modelos/box.modelo.php";
include "../../../modelos/perfil.modelo.php";
include "../../../modelos/orden.modelo.php";
include "../../../pasarelas/mercadopago.controlador.php";

if(isset($_SESSION['dentista'])){

	$tipoUsuario = "dentista";
	$usuario = $_SESSION['dentista'];

} else if(isset($_SESSION['tecnico'])){

	$tipUsuario = "tecnico";
	$usuario = $_SESSION['tecnico'];

}

$tipoPago = $_POST['tipoPago'];
$idBox = $_POST['idBox'];

$datosBox = modeloBox::mdlDatosBox($idBox);

if($datosBox['id_prod'] != NULL){

	$idTrabajo = $datosBox['id_prod'];

} else if($datosBox['id_prod_ort'] != NULL) {

	$idTrabajo = $datosBox['id_prod_ort'];

}

$tipoOrden = $datosBox['tipo'];
$colorimetria = $datosBox['colorimetria'];

$infoPerfil = modeloPerfil::mdlDatosPerfil($usuario,$tipoUsuario); //SE TRAE LA INFORMACIÓN DEL PERFIL DEL USUARIO TIPO DENTISTA
$infoOrden = modeloOrden::mdlInfoOrden($idTrabajo,$tipoOrden,$colorimetria);

$dientes = $datosBox['dientes'];
$numDientes = count(explode(",", $dientes));

$nombre = $infoPerfil['nomb'];
$infoOrden['trabajo'] = ucfirst($infoOrden['trabajo']);
$diasEntrega = $infoOrden['tiempo'] + 1;
$precio = ($infoOrden['precio'] * $datosBox['porcentajePagar'])*$numDientes;
$referencia = "nuevoTrabajo_".$idBox."_".$usuario;


//REALIZAMOS EL CODIGO PARA TRAER EL ENLACE DE MERCADOPAGO
$urlMercadopago = Mercadopago::ctrPagoOrden($tipoPago,$infoOrden['trabajo'],$idTrabajo,$precio,$referencia,$nombre,'test_user_43086458@testuser.com',$infoPerfil['tel']);

echo $urlMercadopago;

 ?>