<?php 

session_start();

include "../../../modelos/box.modelo.php";
include "../../../modelos/orden.modelo.php";
require_once "../../../modelos/perfil.modelo.php";

if( isset($_SESSION['dentista']) ){
	$idUsuario = $_SESSION['dentista'];

	$datosPerfil = modeloPerfil::mdlDatosPerfil($idUsuario,"dentista");
}

$idBox = $_POST['idBox'];

if( modeloBox::mdlExisteBox($idBox) ){ //SÍ EXISTE EL ID DE BOX, OBTENEMOS SU INFORMACIÓN

	$emailPago = $datosPerfil['email'];
	$idTransaccion = "DIVUM";
	//$cantidadPago = $payment_info['body']['transaction_amount'];
	$estadoPago = "approved";
	$nombreComprador = "DIVUM";
	$idComprador = "DIVUM";
	$moneda = "MXN";

	switch ($estadoPago) {
		case 'approved':
		  $entregado = 0; //VARIABLE PARA EL ESTADO DE LA ORDEN
		  $enviarOrdenTecnico = 1; //VARIABLE PARA LAS ETAPAS DE LA ORDEN
		  break;

		case 'pending':
		  $entregado = 2;
		  $enviarOrdenTecnico = 2;
		  break;

		case 'in_process':
		  $entregado = 3;
		  $enviarOrdenTecnico = 3;
		  break;

		default: //POR DEFAULT SERÁ EL 4, QUE ES EL STATUS DE "cancelled"
		  $entregado = 4;
		  $enviarOrdenTecnico = 4;
		  break;
	}

	$datosBox = modeloBox::mdlDatosBox($idBox);

	$direccionEntrega = $datosBox['entrega'];//
    $direccionRecepcion = $datosBox['recepcion'];//
    $nombrePaciente = $datosBox['paciente'];//
    $colorimetria = $datosBox['colorimetria'];//
    $estadoTrabajo = $datosBox['estado'];//
    $especTrabajo = $datosBox['specs'];//
    $producto = $datosBox['id_prod'];//
    $prodOrto = $datosBox['id_prod_ort'];//
    $fechaRecepcion = $datosBox['fecha_rec'];//
    $horaRecepcion = $datosBox['hora'];//
    $porcientoPagar = $datosBox['porcentajePagar'];//
    $fechaEntrega = $datosBox['fecha_ent'];//
    $tipo = $datosBox['tipo'];//
    $dientes = $datosBox['dientes'];

    if($prodOrto!=null){

		$infoProd = modeloOrden::mdlInfoOrden($prodOrto,$tipo,$colorimetria);
		$trabajo = $prodOrto;
	
	} else {

		$infoProd = modeloOrden::mdlInfoOrden($producto,$tipo,$colorimetria);
		$trabajo = $producto;
			
	}

	$precio = $infoProd['precio'];
	$idLaboratorio  = $infoProd['idLaboratorio'];

    $datos = array(
			"direccionEntrega" => $direccionEntrega,
			"direccionRecepcion" => $direccionRecepcion,
			"nombrePaciente" => $nombrePaciente,
			"colorimetria" => $colorimetria,
			"estadoTrabajo" => $estadoTrabajo,
			"trabajo" => $trabajo,
			"especTrabajo" => $especTrabajo,
			"fechaRecepcion" => $fechaRecepcion,
			"fechaEntrega" => $fechaEntrega,
			"horaRecepcion" => $horaRecepcion,
			"tipo" => $tipo,
			"dientes"=>$dientes,
			"idUsuario" => $idUsuario,
			"idLaboratorio" => $idLaboratorio,
			"cantidadTotal" => $precio,
			"idTransaccion" => $idTransaccion,
			"idBox" => $idBox,
			"emailPago" => $emailPago,
			"nombrePago" => $nombreComprador,
			"idCompradorPago" => $idComprador,
			"paisPago" => $moneda,
			"cantidadPago" => $precio,
			"entregado" => $entregado,
			"estadoEtapa" => $enviarOrdenTecnico,
			"metodoPago" => "mercadopago"
			);

	$respuesta = modeloOrden::mdlGenerarNuevaOrden($datos); //AQUÍ SE GENERA LA NUEVA ORDEN DE TRABAJO

	if($respuesta){

		/*AQUÍ SE ESCRIBE EL SCRIPT PARA ENVIAR LAS notificacion DE PAGO*/

		echo "ok";

	} else{

		/*AQUI COLOCAMOS EL ERROR DE QUE ALGO PASO Y NOS E HIZO LA ORDEN*/

		echo "notOk";

	}

}

 ?>