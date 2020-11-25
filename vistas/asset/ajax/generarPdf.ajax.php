<?php 
session_start();
require_once ("../../../vendor/tcpdf/tcpdf.php");
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

$dientes = ( $datos['colorimetria'] != NULL ) ? "\012Dientes: ".$datos['dientes'] : "\012Dientes: NO";

$idTrabajo = ( $datos['colorimetria'] == NULL ) ? $datos['id_orto'] : $datos['id_prod'];

$nombreProtesis = modeloListaTrabajos::mdlInfoTrabajo($idTrabajo,$queEs);

$etapaOrden = modeloOrden::mdlEtapaOrden($idHistOrd);

/*----------------------------------------------------------------------------------------*/

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
	
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('DIVUM');
$pdf->SetTitle('Orden de Laboratorio');

$pdf->setPrintHeader(false); 
$pdf->setPrintFooter(false);
//$pdf->SetMargins(20, 20, 20, false); 
//$pdf->SetAutoPageBreak(true, 20); 
$pdf->SetFont('Helvetica', '', 10);
$pdf->addPage();

$content = '';

$content .= '
<div class="row">
	<div class="col-lg-6">
    	<h1 style="text-align:left;">Orden de trabajo</h1>
		<p>Folio: <b>'.$datos['id_ord'].'</b></p>
		<p>Paciente: '.ucfirst($datos['paciente']).'</p>
		<p>Trabajo: '.ucfirst($nombreProtesis['nomb']).'</p>
		<p>'.$dientes.'</p>
		<p>'.$colorimetria.'</p>
		<p>Recolección: '.$etapaOrden['fecha_rec'].'</p>
		<p>Entrega: '.$etapaOrden['fecha_ent'].'</p>
    </div>
	
	<div class="col-lg-6" style="text-align:left;">
    	<span>Creador </span>DIVUM SOFT
    </div>
</div>
';

$pdf->writeHTML($content, true, 0, true, 0);

$pdf->lastPage();
if(!$pdf->output("../pdf/".$datos['id_ord'].'.pdf', 'F')){
	echo $datos['id_ord'].'.pdf';
} else {
	echo "notOk";
}

 ?>