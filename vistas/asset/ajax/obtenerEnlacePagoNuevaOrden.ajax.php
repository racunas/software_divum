<?php 

$reporte = null;
$stringImagenes = '';

session_start();

require_once "../../../modelos/orden.modelo.php";
require_once "../../../modelos/perfil.modelo.php";
require_once "../../../modelos/listaTrabajos.modelo.php";
require_once "../../../pasarelas/paypal.controlador.php";
require_once "../../../pasarelas/mercadopago.controlador.php";

if (isset($_FILES["nuevaOrdenImg"])){ //SÍ EXISTEN IMAGENES EN EL FORMULARIO, LAS AGREGAMOS AL SERVIDOR

    for ($i=0; $i < count($_FILES["nuevaOrdenImg"]['name']); $i++) { 
        
        $aleatorio = rand();
        $url = $_POST['url'];  
        $file = $_FILES["nuevaOrdenImg"];
        $nombre = $file["name"][$i];
        $tipo = $file["type"][$i];
        $ruta_provisional = $file["tmp_name"][$i];
        $size = $file["size"][$i];
        $dimensiones = getimagesize($ruta_provisional);
        $width = $dimensiones[0];
        $height = $dimensiones[1];
        $fechaActual  = date("dHi");  //Fecha Actual   
        $carpeta = "../images/ordenes/";

        $nombreCompleto = $fechaActual.$aleatorio.$nombre;

        if( (count($_FILES['nuevaOrdenImg']['name']) > 1) && ($i < count($_FILES["nuevaOrdenImg"]['name']) - 1) ){

            $stringImagenes .= $nombreCompleto;
            $stringImagenes .= ",";

        } else {
            
            $stringImagenes .= $nombreCompleto;

        }


        if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
        {
            $reporte .= "Error, el archivo no es una imagen"; 
        }
        else if ($size > 2048*2048)
        {
            $reporte .= "Error, el tamaño máximo permitido es un 2MB";
        }
        else if($width < 60 || $height < 60)
        {
            $reporte .= "Error la anchura y la altura mínima permitida es 60px";
        }
        else
        {
            $src = $carpeta.$nombreCompleto;
            move_uploaded_file($ruta_provisional, $src);
        }

    }

    echo $reporte;

}

$idOrden = $_POST['idOrden'];
$nombreOrden = $_POST['nombreOrden'];
$estado = $_POST['estadoNuevaOrden'];
$fechaRecepcion = $_POST['fechaRecepcionNuevaOrden'];
$fechaEntrega = $_POST['fechaEntregaNuevaOrden'];
$descripcion = $_POST['descripcionOrden'];
$nombreTrabajo = $_POST['nombreTrabajo'];
$tipoPago = $_POST['tipoPago'];
$porcientoPago = $_POST['pago'];
$comision = 50;

if(isset($_SESSION['tecnico'])){

    $idUsuario = $_SESSION['tecnico'];
    $es = 'tecnico';


} elseif(isset($_SESSION['dentista'])){

    $idUsuario = $_SESSION['dentista'];
    $es = 'dentista';

}
    

///VARIABLES PARA ENVIAR AL MODELO Y NOS RETORNE LOS ENLACES DE PAGO

$detallesOrden = modeloOrden::mdlResumenOrden($idUsuario,$es,$idOrden);
$etapas = modeloOrden::mdlEtapasOrden($idOrden,$es);
$total = $detallesOrden['cantidad'] * count(explode(",", $detallesOrden['dientes']));

foreach ($etapas as $key => $value) {
    $total = $total - $value['pago'];
}

$totalPagar = ($total * $porcientoPago) + $comision;

$datosPerfil = modeloPerfil::mdlDatosPerfil($idUsuario,$es);

$nombre = $datosPerfil['nomb'];
$correo = $datosPerfil['email'];
$telefono = $datosPerfil['tel'];
$correoElectronico = $datosPerfil['email'];


$idDetallesAntesOrden = modeloOrden::mdlGenerarNuevaEtapaOrden($idOrden,$nombreOrden,$estado,($total*$porcientoPago),$fechaRecepcion,$fechaEntrega,$descripcion,$stringImagenes);

$referencia = "nuevaOrden_".$idDetallesAntesOrden."_".$idUsuario;

/////////////////////////////////////////////////////////////////////////

switch ($tipoPago) {
	case 'paypal':

		$urlPago = Paypal::ctrPagoPaypalNuevaOrden($nombreTrabajo,$idOrden,$totalPagar,$referencia);

		break;

	case 'debit_card':
	case 'credit_card':
	case 'transfer':
	case 'oxxo':

		$urlPago = Mercadopago::ctrPagoNuevaOrden($tipoPago,$nombreTrabajo,$idOrden,$totalPagar,$referencia,$nombre,'test_user_43086458@testuser.com',$telefono);

		break;

	default:
		echo false;
		break;
}

if($idDetallesAntesOrden != null){

    //echo json_encode($arraydireccion);

    echo $urlPago;

}

 ?>