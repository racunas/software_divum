<?php 
session_start();

require_once("../../../modelos/orden.modelo.php");
require_once("../../../modelos/listaTrabajos.modelo.php");
require_once("../../../modelos/infoproducto.modelo.php");
require_once ("../../../vendor/phpqrcode/qrlib.php");

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


//Declaramos una carpeta temporal para guardar la imagenes generadas
$dir = '../images/qr/';

//Si no existe la carpeta la creamos
if (!file_exists($dir))
    mkdir($dir);

//Declaramos la ruta y nombre del archivo a generar
$filename = $dir.$datos['id_ord'].".png";

//Parametros de Condiguración

$tamaño = 10; //Tamaño de Pixel
$level = 'L'; //Precisión Baja
$framSize = 3; //Tamaño en blanco
$contenido = "ID Orden: ".$datos['id_ord']."\012Paciente: ".$datos['paciente']."\012Trabajo: ".ucfirst($nombreProtesis['nomb']).$dientes.$colorimetria."\012Entrega: ".$etapaOrden['fecha_ent']; //Texto

    //Enviamos los parametros a la Función para generar código QR 
QRcode::png($contenido, $filename, $level, $tamaño, $framSize);

    //Mostramos la imagen generada
echo basename($filename); 



////GENERAR PDF



/*include "../../../vendor/tcpdf/tcpdf.php";

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
	
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Buscalab');
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
		<p>Recolección: 15-01-19</p>
		<p>Entrega: '.$datos['fecha_entrega'].'</p>
    </div>
	
	<div class="col-lg-6" style="text-align:left;">
    	<span>Creador </span><a href="https://buscalab.com">Buscalab</a>
    </div>
</div>
';

$pdf->writeHTML($content, true, 0, true, 0);

$pdf->lastPage();
if(!$pdf->output("../pdf/".$datos['id_ord'].'.pdf', 'F')){
	echo "ok";
} else {
	echo "notOk";
}*/




/*
include "../../../vendor/mike42/escpos-php/autoload.php";
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
$connector = new FilePrintConnector("POS");
$printer = new Printer($connector);
$printer -> text("Hello World!\n");
$printer -> cut();
$printer -> close();
*/
 ?>