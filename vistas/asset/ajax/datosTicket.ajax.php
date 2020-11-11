<?php 

session_start();
require_once("../../../vendor/tcpdf/tcpdf.php");
require_once("../../../modelos/orden.modelo.php");
require_once("../../../modelos/listaTrabajos.modelo.php");
require_once("../../../modelos/infoproducto.modelo.php");

if(isset($_SESSION['tecnico'])){

    $idUsuario = $_SESSION['tecnico'];
    $tipo = "tecnico";

} elseif(isset($_SESSION['dentista'])){

    $idUsuario = $_SESSION['dentista'];
    $tipo = "dentista";

}
	
$id = $_POST['id'];
$idHistOrd = $_POST['idHistOrd'];

$datos = modeloOrden::mdlDatosOrden($id);

$color = mdlInfoproducto::mdlColorimetria($datos['colorimetria']);

$queEs = ( $datos['colorimetria'] == NULL ) ? 'ortodoncia' : 'protesis';

$colorimetria = ( $datos['colorimetria'] != NULL ) ? "\012Colorimetria: ".$color[0]['nomb'] : "\012Colorimetria: NO";

$dientes = ( $datos['colorimetria'] != NULL ) ? "\012O.D.: ".$datos['dientes'] : "\012O.D.: NO";

$idTrabajo = ( $datos['colorimetria'] == NULL ) ? $datos['id_orto'] : $datos['id_prod'];

$nombreProtesis = modeloListaTrabajos::mdlInfoTrabajo($idTrabajo,$queEs);

$etapaOrden = modeloOrden::mdlEtapaOrden($idHistOrd);

$datosOrden = array();

$datosOrden[] = 'Folio: '.$datos['id_ord'];
$datosOrden[] = 'Paciente: '.ucfirst($datos['paciente']);
$datosOrden[] = 'Trabajo: '.ucfirst($nombreProtesis['nomb']);
$datosOrden[] = $dientes;
$datosOrden[] = $colorimetria;
$datosOrden[] = 'Recoleccion: '.$etapaOrden['fecha_rec'];
$datosOrden[] = 'Entrega: '.$etapaOrden['fecha_ent'];
$datosOrden[] = 'Indicaciones: '.$etapaOrden['descr'];
$datosOrden[] = ($etapaOrden['estado'] == 2) ? 'Estado del trabajo: Prueba' : 'Estado del trabajo: Terminado';
$datosOrden[] = 'Tipo de orden: '.$datos['tipo'];

echo json_encode($datosOrden);

 ?>