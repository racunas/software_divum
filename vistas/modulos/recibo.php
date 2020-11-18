<?php  

//AQUI SE DEBE ESCRIBIR EL PROCEDIMIENTO PARA OBTENER INFORMACION DEL PAGO (PAYPAL)
require "pasarelas/configPP.php";
require "pasarelas/configMP.php";

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

if(isset($_SESSION['tecnico'])){

	$idUsuario = $_SESSION['tecnico'];

} else if(isset($_SESSION['dentista'])){

	$idUsuario = $_SESSION['dentista'];

}

$metodoPago = $_GET['pago'];

//PROCESOS EN CASO DE QUE EL PAGO SEA CON PAYPAL
if($metodoPago == "paypal"){

	$boxReference = $_GET['boxReference']; //ID de BOX de los datos de la orden comprada
	$idTransaccion = $_GET['paymentId'];
	$token = $_GET['token'];
	$payerId = $_GET['PayerID'];

	$payment = Payment::get($idTransaccion,$apiContext);
	$datosTransaccion = $payment->toJSON();
	$datosUsuario = json_decode($datosTransaccion);
	$emailPago = $datosUsuario->payer->payer_info->email;
	$nombrePago = $datosUsuario->payer->payer_info->first_name." ".$datosUsuario->payer->payer_info->last_name;
	$idCompradorPago = $datosUsuario->payer->payer_info->payer_id;
	$paisPago = $datosUsuario->payer->payer_info->country_code;
	$cantidadPago = $datosUsuario->transactions[0]->amount->total;
	$fechaPago = explode("T", $datosUsuario->create_time);
	$itemsComprados = $datosUsuario->transactions[0]->item_list->items;
	$numItemsComprados = count($itemsComprados);

	if(controladorBox::ctrExisteBox($boxReference)){ //Sí existe el ID de la box, damos de alta la orden
		

		$execution = new PaymentExecution();
		$execution -> setPayerId($payerId);

		$payment -> execute($execution,$apiContext);
		

		$datosBox = controladorBox::ctrDatosBox($boxReference);

		if($datosBox['id_usuario'] == $idUsuario){ //Sí la orden de la box le pertenece al usuario, continuamos.




			$direccionEntrega = $datosBox['entrega'];
			$direccionRecepcion = $datosBox['recepcion'];
			$nombrePaciente = $datosBox['paciente'];
			$colorimetria = $datosBox['colorimetria'];
			$estadoTrabajo = $datosBox['estado'];
			$producto = $datosBox['id_prod'];
			$prodOrto = $datosBox['id_prod_ort'];
			$especTrabajo = $datosBox['specs'];
			$fechaRecepcion = $datosBox['fecha_rec'];
			$fechaEntrega = $datosBox['fecha_ent'];
			$horaRecepcion = $datosBox['hora'];
			$tipo = $datosBox['tipo'];
			$dientes = $datosBox['dientes'];

			if($prodOrto!=null){

				$infoProd = controladorOrden::ctrInfoOrden($prodOrto,$tipo,$colorimetria);
				$trabajo = $prodOrto;
			
			} else {

				$infoProd = controladorOrden::ctrInfoOrden($producto,$tipo,$colorimetria);
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
					"idBox" => $boxReference,
					"emailPago" => $emailPago,
					"nombrePago" => $nombrePago,
					"idCompradorPago" => $idCompradorPago,
					"paisPago" => $paisPago,
					"cantidadPago" => $cantidadPago,
					"entregado" => 0,
					"estadoEtapa" => 1,
					"url" => $url,
					"metodoPago" => "paypal"
					);

			$respuesta = controladorOrden::ctrGenerarNuevaOrden($datos);

			if($respuesta){

				//echo "se inserto correctamente";
				//ENVIAMOS NOTIFICACION AL LABORATORIO DE QUE YA SE REALIZO UNA ORDEN

				//ENVIAMOS UNA NOTIFICACION AL DENTISTA DE QUE YA SE REALIZO UNA ORDEN
			} else {

				echo "Ocurrio un error, intenta pagar de nuevo";

			}
		}

	} else {

		//AQUI SE COLOCA EL REDIRECCIONAMIENTO A LA PAGINA DE LAS ORDENES, EN CASO DE QUE REFRESQUE LA PÁGINA EL USUARIO

	}

} else if($metodoPago = "mercadopago"){

	$idTransaccion = $_GET['collection_id'];
	$status = $_GET['collection_status'];
	$datosTransaccion = MercadoPago\SDK::get('/v1/payments/'.$idTransaccion);

	$emailPago = $datosTransaccion['body']['payer']['email'];
	$cantidadPago = $datosTransaccion['body']['transaction_details']['total_paid_amount'];
	$fechaPago = explode("T", $datosTransaccion['body']['date_created']);

	//SUSTITUIR ESTE PROCESO CUANDO SE HAGA CON MAS DE 2 PRODUCTOS
	$itemsComprados = array();
	$item = array(	
			"name"=>$datosTransaccion['body']['description'],
			"price"=>$datosTransaccion['body']['transaction_amount']
				);

	$itemsComprados[0] = $item;

	/////////////////////////////////////////////////////////////////

	$numItemsComprados = count($itemsComprados);
	
}

// SE DEBE CREAR EL OYENTE IPN DE MERCADOPAGO, PARA ACTUALIZAR ESTADOS DE PAGO.

?>


<div class="container">
	
	<div class="row my-4">
		
		<div class="col-xl-3 col-lg-2 col-md-0 col-sm-0 col-0"></div>

		<div class="col-xl-6 col-lg-8 col-md-12 col-sm-12 col-12 text-muted">
			
			<div class="recibo shadow">
				
				<div class="text-left">
					
					<img src="<?php echo $url; ?>vistas/asset/images/logo.png" alt="" class="img-fluid">

					<span class="txtLogo mt-4 pt-2" style="font-size: 35px;">
						<span class="colorBuscalabGris bold"><span class="colorBuscalab">Busca</span>lab</span>
					</span>

				</div>

				<h2 class="pt-4 bold">Pago realizado con éxito</h2>

				<h3 class="pt-4">Detalles de compra</h3>

				<div class="row pt-3">
					
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-2">
						
						<h5><i class="fas fa-tooth mr-2"></i> Pago por <?php echo $numItemsComprados; ?> trabajos:</h5>

					</div>

					<?php 


					foreach ($itemsComprados as $key => $value) {

						
						if($metodoPago == "mercadopago"){

							echo '<div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 text-left">
								
									<span>'.$value['name'].'</span>

								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 text-right">
									
									<span>$'.$value['price'].'</span>

								</div>';

						} else {

							echo '<div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 text-left">
								
									<span>'.$value->name.'</span>

								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 text-right">
									
									<span>$'.$value->price.'</span>

								</div>';
							
						}
					}

					 ?>

					<div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 text-left mt-3">
						
						<h2 class="colorBuscalab"><b><i class="fas fa-dollar-sign mr-2"></i> Total:</b></h2>

					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 text-right mt-3">
						
						<h2 class="colorBuscalab"><b>$<?php echo $cantidadPago; ?></b></h2>

					</div>

					<div class="col-lg-12 text-left pt-3">

						<hr>
						
						<div><b><i class="fas fa-envelope mr-2 py-2"></i> Correo electrónico:</b><br><?php echo $emailPago; ?></div>
						<div><b><i class="fas fa-hand-holding-usd mr-2 py-2"></i> Forma de pago:</b><br> <?php echo $metodoPago; ?></div>
						<div><b><i class="fas fa-file-invoice-dollar mr-2 py-2"></i> ID de pago:</b> <br><?php echo $idTransaccion; ?></div>
						<div><b><i class="fas fa-dollar-sign mr-2 py-2"></i> Cantidad:</b> <br>$<?php echo $cantidadPago; ?></div>

					</div>

				</div>

			</div> <!--FIN DEL RECIBO-->

			<div class="row mb-4">

				<div class="col-lg-12 text-center">
					
					<a href="<?php echo $url; ?>ordenes" class="noDecoration"><button class="btn btn-block btnBuscalab txtEnorme">Continuar <i class="fas fa-arrow-right ml-2"></i></button></a>

				</div>

			</div>

		</div>

		<div class="col-xl-3 col-lg-2 col-md-0 col-sm-0 col-0"></div>

	</div>

</div>