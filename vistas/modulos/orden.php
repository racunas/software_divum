<?php 

//AQUI SE DEBE ESCRIBIR EL PROCEDIMIENTO PARA OBTENER INFORMACION DEL PAGO (PAYPAL)
require "pasarelas/configPP.php";
require "pasarelas/configMP.php";
require_once "vendor/phpqrcode/qrlib.php";


use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

$idOrden = $_GET['orden'];

if(isset($_SESSION['tecnico'])){

	$idUsuario = $_SESSION['tecnico'];
	$es = 'tecnico';

	/*ACTUALIZAR LA ORDEN, QUE YA LA VIO POR PRIMERA VEZ EL TÉCNICO*/

	$etapasOrden = controladorOrden::ctrEtapasOrden($idOrden,$es);
	$idHistOrd = $etapasOrden[0]['id_hist_ord'];

	$respuestaVistaOrden = controladorOrden::ctrOrdenVista($idHistOrd);

	if($respuestaVistaOrden){

		$descargarQR = "Descargar QR <i class='fab fa-wpforms ml-2'></i>";

		$descargarOrden = "Imprimir Orden <i class='fas fa-qrcode ml-2'></i>";

		echo

		'<script>

			var idOrden = "'.$idOrden.'";
			var idHistOrd = "'.$idHistOrd.'";
		
			swal({
				title: "¡Felicidades!",
				text: "Tienes una nueva orden, puedes imprimirla para que tu mensajero pase por el trabajo",
				type: "success",
				confirmButtonColor: "#ff9900",
				confirmButtonText: "'.$descargarQR.'",
				showCancelButton: true,
				cancelButtonText:"'.$descargarOrden.'"
			}).then((result) => {
				if (result.value) {
					console.log("descargar QR");
					generarQr(idOrden,idHistOrd);
				} else {
					console.log("descargar orden");
					generarPdf(idOrden,idHistOrd);
				}
			})

		</script>';

	}

} elseif(isset($_SESSION['dentista'])){

	$idUsuario = $_SESSION['dentista'];
	$es = 'dentista';

}

$resumenOrden = controladorOrden::ctrResumenOrden($idUsuario,$es,$idOrden);

//print_r($resumenOrden);

if(!$resumenOrden){
	echo '<script>
			returnOrdenes();
			</script>';
}

//print_r($resumenOrden);

if($resumenOrden['color'] != NULL){

	$nombreTrabajo = ucfirst($resumenOrden['protesis']); 
	$tipoTrabajo = "protesis";

} else {

	$nombreTrabajo = ucfirst($resumenOrden['ortodoncia']); 
	$tipoTrabajo = "ortodoncia";

}


//Sacar el tiemp de entrega

if($resumenOrden['idProtesis'] == NULL){

	$idTrabajo = $resumenOrden['idOrtodoncia'];

} elseif($resumenOrden['idOrtodoncia'] == NULL){

	$idTrabajo = $resumenOrden['idProtesis'];

}

$tipo = $resumenOrden['tipo'];
$colorimetria = $resumenOrden['color'];


$datosOrden = controladorOrden::ctrInfoOrden($idTrabajo,$tipo,$colorimetria);


$tiempoEntrega = $datosOrden['tiempo'];

$tipoOrdenTrabajo = ($tipo == 'urgente') ? '<span class="avisoOrdenes ml-3 flagAtrasado shadow mr-3">URGENTE</span>' : '<span class="avisoOrdenes ml-3 flagEntregar shadow">ORDINARIO</span>';

 ///////////////////////////////////////////////////////////////////////////////




////////////////////CODIGO PARA DAR DE ALTA LAS NUEVAS ORDENES /////////////////////////////

if(isset($_GET['pago'])){

	if($_GET['pago'] == "mercadopago"){

		$pago = $_GET['pago'];
		$collection_id = $_GET['collection_id'];
		$collection_status = $_GET['collection_status'];
		$preference_id = $_GET['preference_id'];
		$external_reference = $_GET['external_reference'];
		$payment_type = $_GET['payment_type'];
		$merchant_order_id = $_GET['merchant_order_id'];

		//AQUI TENEMOS QUE ESCRIBIR EL CODIGO PARA ENVIAR UNA ALERTA AL USUARIO DE QUE SU ORDEN SE ENVIÓ CON EXITO, QUE ESTÁ PENDIENTE O QUE FALLÓ.

		switch ($collection_status) {
			case 'approved':
					
				echo 

				"<script>
							
					swal({
					  type: 'success',
					  title: 'Se ha enviado la orden al Laboratorio',
					  text: 'Su mensajero pasará por el trabajo a tu consultorio',
					  confirmButtonText: 'Entendido',
					  confirmButtonColor: '#ff9900'
					});

					
				</script>";

				break;

			case 'in_process':
				echo 

				"<script>
							
					swal({
					  type: 'info',
					  title: 'Se está procesando tu pago',
					  text: 'Te notificaremos en menos de 24hrs',
					  confirmButtonText: 'Entendido',
					  confirmButtonColor: '#ff9900'
					});

					
				</script>";
				break;

			case 'pending':
				echo 

				"<script>
							
					swal({
					  type: 'info',
					  title: 'Esperamos a que realices tu pago',
					  text: 'Se enviará la orden al Laboratorio en cuanto hagas tu pago',
					  confirmButtonText: 'Entendido',
					  confirmButtonColor: '#ff9900'
					});

					
				</script>";
				break;
			
			default:
				echo 

				"<script>
							
					swal({
					  type: 'error',
					  title: 'Procesando orden',
					  text: 'Consulta el estado de tu orden en 24 hrs',
					  confirmButtonText: 'Entendido',
					  confirmButtonColor: '#ff9900'
					});

					
				</script>";
				break;
		}

		
	} elseif ($_GET['pago'] == "paypal") {
		
		$orden = $_GET['orden'];
		$pago = $_GET['pago'];
		$success = $_GET['success'];
		$reference = $_GET['reference'];
		$paymentId = $_GET['paymentId'];
		$token = $_GET['token'];
		$payerId = $_GET['PayerID'];

		$payment = Payment::get($paymentId,$apiContext);
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

		if($success=="true"){

			$idAntesOrden = explode("_", $reference);

			$detallesOrden = controladorOrden::ctrDetallesAntesOrden($idAntesOrden[1]);

			$idOrden = $detallesOrden['idOrden'];
			$pago = $detallesOrden['pago'];
			$etapa = $detallesOrden['etapa'];
			$estado = $detallesOrden['estadoOrden'];
			$estadoPago = 1; //SI ES PAYPAL, SIEMPRE ES 1 CUANDO ES ACEPTADO
			$fechaRecepcion = $detallesOrden['fechaRecepcion'];
			$fechaEntrega = $detallesOrden['fechaEntrega'];
			$descripcion = $detallesOrden['descr'];

			//ANTES DE TODO, TENGO QUE VERIFICAR QUE LA ORDEN PERTENEZCA AL USUARIO CORRECTO

			$detallesOrdenGeneral = controladorOrden::ctrResumenOrden($idUsuario,$es,$idOrden);

			if($detallesOrdenGeneral != false){

				$datos = modeloOrden::mdlDatosOrden($idOrden);

				$color = mdlInfoproducto::mdlColorimetria($datos['colorimetria']);

				$idNuevaOrden = controladorOrden::ctrContinuarOrden($idOrden, $pago, $etapa, $estado, $estadoPago, $fechaRecepcion, $fechaEntrega, $descripcion, $url);

				$imagenes = explode(",", $detallesOrden['imagenes']);

				for ($i=0; $i < count($imagenes); $i++) { 

					controladorOrden::ctrAltaImagenes($imagenes[$i],$idOrden,$idNuevaOrden);
				}

				if( controladorOrden::ctrAltaPago($paymentId, $idNuevaOrden, "paypal", $emailPago, $nombrePago, $idCompradorPago, $paisPago) ){

					controladorOrden::ctrBorrarDetallesAntesOrden($idAntesOrden[1]);

					echo "<script>
							
							swal({
							  type: 'success',
							  title: 'Se ha enviado la orden al Laboratorio',
							  text: 'Pasaremos por el trabajo a tu consultorio',
							  confirmButtonText: 'Entendido',
							  confirmButtonColor: '#ff9900'
							});

							
						</script>";

				}			 	
				

			}


		}

		


	}




}


////////////////////////////////////////////////////////////////////////////////////////////

 ?>

<div class="container py-3 separacionFooter">
	
	<div class="row mt-3">
		
		<div class="col-lg-12 form-inline">
				
			<div class="mr-3 txtEnorme">
				<a href="<?php echo $url; ?>ordenes" class="noDecoration colorBuscalab"><i class="fas fa-arrow-left"></i></a>
			</div>

			<?php 

			if( ($resumenOrden['fotoPerfil'] == "dentista.png") || ($resumenOrden['fotoPerfil'] == "imgRelleno.png") ){

				$primeraLetra = substr( ucfirst($resumenOrden['nombre']), 0, 1);
				echo '<div class="imagenPerfilOrden"><p>'.$primeraLetra.'</p></div>';

			} else{

				if($es == "tecnico") { $carpeta = "dentistas"; } else { $carpeta = "tecnicos"; }

				echo '<img src="'.$url.'vistas/asset/images/'.$carpeta.'/'.$resumenOrden['fotoPerfil'].'" alt="'.$resumenOrden['nombre'].'" class="fotoPerfilOrden">';

			}

			?>

			<h3 class="text-muted mt-1"><span class="bold"><?php echo ucfirst($resumenOrden['nombre']); ?></span></h3>

		</div>

	</div>

	<div class="row my-4">

		<div class="col-lg-8">

			<input type="hidden" id="nombreTrabajoOrden" value="<?php echo $nombreTrabajo; ?>">
			
			<h3 class="colorBuscalab bold nombreTrabajoOrden">

				<?php 

				echo $nombreTrabajo;

				/*PARA OBTENER EL ULTIMO QR DE LA ULTIMA ORDEN*/
				$idOrd = $_GET['orden'];
				$etapas = controladorOrden::ctrEtapasOrden($idOrd,$es);
				////////////////////////////////////////////////

				?>

				
				<i class="codigoQr fas fa-qrcode ml-2 text-muted" data-qr="<?php echo $etapas[0]['id_hist_ord']; ?>"></i>

				<i class="ordenPdf fas fa-file-alt ml-2 text-muted" data-pdf="<?php echo $etapas[0]['id_hist_ord']; ?>"></i>
					
			</h3>

			<div class="text-muted my-1">Folio: <b><?php echo $idOrden; ?></b> <?php echo $tipoOrdenTrabajo; ?></div>

		</div>

		<div class="col-lg-4 pt-2 pt-lg-1 text-center">

			<?php 

			if($es == "tecnico"){

				$idOrd = $_GET['orden'];
				$etapas = controladorOrden::ctrEtapasOrden($idOrd,$es);

				//print_r($etapas);

				if(count($etapas) == 0){
					
					echo '<div class="alert alert-danger statusOrden" data-status="trabajoCancelado" data-idHist="'.$etapas[0]['id_hist_ord'].'">
							
							Orden cancelada <i class="fas fa-ban ml-3"></i>

						</div>';				

				} elseif($etapas[0]['confirmacionTecnico'] == 0){

					echo '<div class="alert alert-warning statusOrden" data-status="trabajoTerminado" data-idHist="'.$etapas[0]['id_hist_ord'].'">
							
							¿Terminaste el trabajo? <i class="fas fa-check-circle ml-3"></i>

						</div>';

				} elseif($etapas[0]['confirmacionTecnico'] == 1){

					if($etapas[0]['estado'] == 1){

						$calificacion = controladorOrden::ctrCalificacionOrden($idUsuario,$es,$idOrd);

						if($calificacion){

							$califPrecio = $calificacion['precio'];
							$califTiempo = $calificacion['tiempo'];
							$califCalidad = $calificacion['calidad'];

							$califTotal = bcdiv(($califPrecio+$califTiempo+$califCalidad), '3', 1);

							echo '<div class="alert alert-success statusOrden" >
									
									<div>Orden terminada <i class="fas fa-check-circle ml-3"></i></div>
									Calificación: '.$califTotal.' de 5 <i class="fas fa-star ml-2"></i>

								</div>';
							
						} else {

							echo '<div class="alert alert-success statusOrden" data-status="" data-idHist="'.$etapas[0]['id_hist_ord'].'">
									
									<div>Orden terminada <i class="fas fa-check-circle ml-3"></i></div>

								</div>';
							
						}


					} else {

						echo '<div class="alert alert-info statusOrden" data-status="esperaOtraOrden" data-idHist="'.$etapas[0]['id_hist_ord'].'">
								
								Esperando otra orden de éste trabajo  <i class="fas fa-clock ml-3"></i>

							</div>';
						
					}


				}


			} elseif($es == "dentista"){

				$idOrd = $_GET['orden'];				
				$etapas = controladorOrden::ctrEtapasOrden($idOrd,$es);

				//print_r($etapas);

				if($etapas[0]['estadoPago'] == 4){
					
					echo '<div class="alert alert-danger statusOrden" data-status="trabajoCancelado" data-idHist="'.$etapas[0]['id_hist_ord'].'">
							
							Orden cancelada <i class="fas fa-ban ml-3"></i>

						</div>';				

				} elseif($etapas[0]['estadoPago'] == 1){

					if($etapas[0]['confirmacionTecnico'] == 0){

						echo '<div class="alert alert-info statusOrden" data-status="trabajoEnProceso" data-idHist="'.$etapas[0]['id_hist_ord'].'" >
								
								Trabajo en proceso... <i class="fas fa-clock ml-3"></i>

							</div>';

					} elseif($etapas[0]['confirmacionTecnico'] == 1){

						if($etapas[0]['estado'] == 1){

							//SÍ LA ORDEN ES TERMINADA, DEBEMOS DE VERIFICAR QUE AÚN NO SE HAYA AGREGADO UNA CALIFICACIÓN

							$calificacion = controladorOrden::ctrCalificacionOrden($idUsuario,$es,$idOrd);

							if(!$calificacion){
								//SÍ NO EXISTE NINGUNA CALIFICACION, MOSTRAMOS QUE AGREGUE ALGUNA
								echo '<div class="alert alert-success statusOrden ordenSinCalificar" data-status="trabajoFinalizado" data-idHist="'.$etapas[0]['id_hist_ord'].'" >
										
										Calificar al laboratorio <i class="far fa-star ml-2"></i>

									</div>';
							} else {

								$califPrecio = $calificacion['precio'];
								$califTiempo = $calificacion['tiempo'];
								$califCalidad = $calificacion['calidad'];

								$califTotal = bcdiv(($califPrecio+$califTiempo+$califCalidad), '3', 1);

								echo '<div class="alert alert-success statusOrden ordenCalificada" >
										
										Calificación: '.$califTotal.' de 5 <i class="fas fa-star ml-2"></i>

									</div>';
								
							}



							echo '

								<div class="alert alert-warning statusOrden" data-status="generarNuevaOrden" data-idHist="'.$etapas[0]['id_hist_ord'].'" >
									
									Nueva modificación <i class="fas fa-arrow-right ml-3"></i>

								</div>';

						} else {

							echo '<div class="alert alert-success statusOrden" data-status="generarNuevaOrden" data-idHist="'.$etapas[0]['id_hist_ord'].'" >
									
									Continuar orden de '.$nombreTrabajo.' <i class="fas fa-arrow-right ml-3"></i>

								</div>';
							
						}


					}

				} elseif($etapas[0]['estadoPago'] == 2) {

					$consultaPago = controladorOrden::ctrConsultarPagoOrden($etapas[0]['id_hist_ord']);

					$payment_info = MercadoPago\SDK::get('/v1/payments/'.$consultaPago['id_pago']);

					$cantidadPago = $payment_info['body']['transaction_amount'];

					//print_r($consultaPago);

					//print_r($payment_info);

					$linkTicket = $payment_info['body']['transaction_details']['external_resource_url'];

					echo '<a href="'.$linkTicket.'" target="_blank">
							<div class="alert alert-warning statusOrden" data-status="trabajoPendientePago" data-idHist="'.$etapas[0]['id_hist_ord'].'">
								
								PAGO PENDIENTE: Clic para ver tu recibo de pago de <b>$'.$cantidadPago.'</b> <i class="fab fa-wpforms ml-2"></i>

							</div>
						</a>';

				} elseif($etapas[0]['estadoPago'] == 3) {

					echo '<div class="alert alert-warning statusOrden" data-status="trabajoPendientePago" data-idHist="'.$etapas[0]['id_hist_ord'].'">
								
							Estamos verificando tu pago, puede tardar hasta 24 hrs <i class="fas fa-clock ml-3"></i>

							</div>';

				}

				$fechaUltimaOrden = $etapas[0]['fecha_ent'];
				$fechaActual = date("Y-m-d");

				$fecha1 = new DateTime($fechaUltimaOrden); //Fecha de entrega
				$fecha2 = new DateTime($fechaActual); //Fecha actual
				$diff = $fecha1->diff($fecha2); //Diferencia entre esas dos fechas
				
				// will output 2 days
				$diasRestantesEntrega = $diff->days; //NUMERO DE DIAS TRANSCURRIDOS

				//echo ($fecha1 > $fecha2) ? "no se muestra el aviso" : "se muestra el aviso";

				if($fecha1 <= $fecha2 && ($etapas[0]['confirmacionTecnico'] == 0)){ //SI LA FECHA DE ENTREGA ES MENOR O IGUAL QUE LA ACTUAL Y EL TECNICO DENTAL NO HA CONFIRMADO LA ORDEN

					echo '<div class="alert alert-success statusOrden" data-status="trabajoTerminadoDentista" data-idHist="'.$etapas[0]['id_hist_ord'].'">
								
								Ya recibí el trabajo <i class="fas fa-check-circle ml-3"></i>

							</div>';

				}


			}

			 ?>

		</div>

		<div class="col-lg-12">
			
			<hr class="my-2">

		</div>
		
		<div class="col-lg-4 mt-3">

			<div class="nav flex-column nav-pills listaOrden" id="v-pills-tab" role="tablist" aria-orientation="vertical">

				<a class="nav-link active" id="resumen" data-toggle="pill" href="#v-resumen" role="tab" aria-controls="v-pills-home" aria-selected="true"><i class="fas fa-tooth mr-3"></i> Resumen<span class="col-sm-0 col-0">de tu orden</span></a>

				<a class="nav-link" id="ordenes" data-toggle="pill" href="#v-ordenes" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="fas fa-check-double mr-3"></i> Ordenes</a>
				
				<a class="nav-link" id="chat" data-toggle="pill" href="#v-chat" role="tab" aria-controls="v-pills-settings" aria-selected="false"><i class="fas fa-comments mr-3"></i> Chat <span class="float-right badge badgeBuscalab badge-pill numeroMensajesNoLeidos"></span></a>

				<a class="nav-link" id="pagos" data-toggle="pill" href="#v-pagos" role="tab" aria-controls="v-pills-home" aria-selected="true"><i class="fas fa-hand-holding-usd mr-3"></i> Pagos</a>

				<a class="nav-link" id="fotos" data-toggle="pill" href="#v-fotos" role="tab" aria-controls="v-pills-messages" aria-selected="false"><i class="fas fa-images mr-3"></i> Fotos</a>

			</div>


		</div>

		<div class="col-lg-8 mt-3">
			
			<div class="tab-content" id="v-pills-tabContent">

				<div class="tab-pane fade show active" id="v-resumen" role="tabpanel" aria-labelledby="v-datosGenerales-tab">
					
					<div class="row">
						
						<div class="col-lg-12">
							
							<h5 class="colorBuscalabGris bold">Resumen de tu orden</h5>
							
						</div>

						<div class="col-lg-6 col-md-6">
							
							<div class="colorBuscalab mt-2">

								<i class="fas fa-user mr-2"></i> Paciente:
								
								<span class="colorGris">
									<?php echo ucfirst($resumenOrden['paciente']); ?>
								</span>

							</div>

							<div class="colorBuscalab mt-2">

								<i class="fas fa-dollar-sign mr-2"></i> Precio:
								
								<span class="colorGris">
									$<?php echo ucfirst($resumenOrden['cantidad']); ?> <span class="txtPequeño">(c/diente)</span>
								</span>

							</div>

						<?php 

						if($resumenOrden['color'] != NULL){

						?>

							<div class="colorBuscalab mt-2">

								<i class="fas fa-fill-drip mr-2"></i> Colorimetria:
								
								<span class="colorGris">
									<?php echo $resumenOrden['color']; ?>
								</span>

							</div>

						<?php 

						}

						 ?>

						</div>

						<div class="col-lg-6 col-md-6">

							<div class="colorBuscalab mt-2">
								
								<i class="fas fa-clock mr-2"></i> Tiempo de elaboración:

								<span class="colorGris">
									<?php echo $tiempoEntrega; ?> días
								</span>

							</div>

							<div class="colorBuscalab mt-2">
								
								<i class="fas fa-calendar-check mr-2"></i> Próxima entrega:

								<span class="colorGris">
									<?php echo $etapas[0]['fecha_ent']; ?>
								</span>

							</div>

						<?php 

						if($resumenOrden['color'] != NULL){

						?>

							<div class="colorBuscalab mt-2">

								<i class="fas fa-teeth-open"></i> Dientes a elaborar:
								
								<span class="colorGris">
									<?php echo $resumenOrden['dientes']; ?>
								</span>

							</div>

						<?php 

						}

						 ?>

						</div>

						<div class="col-lg-6 col-md-6 mt-3 colorBuscalab">
								
							<i class="fas fa-map-marker-alt mr-2"></i> Dirección de recepción

							<div class="mt-2 colorGris">
								<?php 

								echo $resumenOrden['direcRec'].", ";

								echo $resumenOrden['municipioRec']."<br>";

								echo $resumenOrden['cpRec'];

								 ?>
							</div>

							<i class="fas fa-map-marker-alt mr-2 mt-2"></i> Dirección de entrega

							<div class="mt-2 colorGris">
								<?php 

								echo $resumenOrden['direcEnt'].", ";

								echo $resumenOrden['municipioEnt']."<br>";

								echo $resumenOrden['cpEnt'];

								 ?>
							</div>

						</div>

						<div class="col-lg-6 col-md-6 mt-5 colorBuscalab">
							
							<button class="btn btn-block btn-outline-secondary codigoQr" data-qr="<?php echo $etapas[0]['id_hist_ord']; ?>">Generar QR de la última orden <i class="fas fa-qrcode ml-2"></i></button>

							<br>

							<button class="btn btn-block btnBuscalab ordenPdf" data-pdf="<?php echo $etapas[0]['id_hist_ord']; ?>">Generar PDF de la última orden <i class="fas fa-file-alt ml-2"></i></button>

						</div>

					</div>

				</div>

				<div class="tab-pane fade" id="v-pagos" role="tabpanel" aria-labelledby="v-pagos-tab">

				</div>

				<div class="tab-pane fade" id="v-ordenes" role="tabpanel" aria-labelledby="v-etapas-tab">
		
				</div>

				<div class="tab-pane fade" id="v-fotos" role="tabpanel" aria-labelledby="v-fotos-tab">

				</div>

				<div class="tab-pane fade" id="v-chat" role="tabpanel" aria-labelledby="v-chat-tab">
					
					<h5 class="colorBuscalabGris bold">Chat</h5>

					<p class="text-muted">Aquí puedes enviar mensajes en tiempo real sobre tu orden de laboratorio</p>

					<div class="row pt-2">
							
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								
							<ul id="conversacion" class="conversacion">
								 
								<!--<li class="receptor">
									<div class="mensaje">
										
										<div class="foto">
											<img src="http://192.168.2.6/buscalabFinal/vistas/asset/images/dentistas/Ricardo Sandoval1208071300179171.jpg" alt="Ricardo Sandoval" class="foto">
										</div>
										
										<div class="contenedor">
											<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sequi, commodi</p>
											<div class="fechaMensaje txtPequeño">2019-03-16</div>
										</div>																		

									</div>
								</li>

								<li class="emisor">
									<div class="mensaje">
										
										<div class="contenedor">
											<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sequi, commodi</p>
											<div class="fechaMensaje txtPequeño">2019-03-16</div>
										</div>										
										
										<div class="foto">
											<img src="http://192.168.2.6/buscalabFinal/vistas/asset/images/dentistas/Ricardo Sandoval1208071300179171.jpg" alt="Ricardo Sandoval" class="foto">
										</div>

									</div>
								</li>-->

								<li class="sinMensajes text-center">
									<div class="ilustraciones">
										<i class="far fa-user"></i>
										<i class="far fa-lightbulb"></i>
										<i class="fas fa-angle-right mx-5"></i>
										<i class="fas fa-comment-dots"></i>
									</div>
									<p class="text-muted instrucciones">Pregunta lo que desees, o envía mensajes con detalles de tu trabajo que hayas olvidado<i class="fas fa-arrow-down ml-2"></i></p>
								</li>

							</ul>

						</div>

					</div>

					<div class="input-group my-3">
						<input type="text" class="form-control mensajeChat" placeholder="Escribe un mensaje aquí..." aria-label="Escribe un mensaje aquí" onkeypress="sendMessage(event)">
						<div class="input-group-append">
							<button class="btn btnBuscalab btnEnviarMensaje" type="button">Enviar</button>
						</div>
					</div>

				</div>

			</div>

		</div>

	</div>

</div>

<input type="hidden" id="idOrdenDetalles" value="<?php echo $_GET['orden']; ?>">

<div class="modal fade" id="generarNuevaOrdenModal" tabindex="-1" role="dialog" aria-hidden="true">
	
	<div class="modal-dialog modal-lg">

		<div class="modal-content">

			<div class="modal-header fondoBuscalab">
				<h5 class="modal-title" id="exampleModalLabel">Nueva orden de <b><?php echo $nombreTrabajo; ?></b></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				
				<div class="detallesGeneralesNuevaOrden">

					<div class="row">

						<div class="col-lg-2 txtCorto mt-1">
							
							<span class="text-muted">Prueba:</span>
							
						</div>
						
						<div class="col-lg-4">

							<input type="text" id="nombreOrden" class="form-control inputBorderBottom" placeholder="Ej. prueba de bizcocho...">

						</div>
						
						<div class="col-lg-2 mt-1 separacionMovil separacionTablet">
							
							<span class="text-muted">Pago: </span>
							
						</div>

						<div class="col-lg-4">

							<?php 

							$precioTotal = $resumenOrden['cantidad']*count(explode(",", $resumenOrden['dientes']));

							foreach ($etapas as $key => $value) {
								$precioTotal = $precioTotal - $value['pago'];
							}

							if($precioTotal >= 200){

							?>

								<select name="pagoNuevaOrden" id="pagoNuevaOrden" class="form-control inputBorderBottom">
									<option value="1">$<?php echo $precioTotal; ?> (100%)</option>
									<option value="0.75">$<?php echo $precioTotal*0.75; ?> (75%)</option>
									<option value="0.50">$<?php echo $precioTotal*0.50; ?> (50%)</option>
									<option value="0.25">$<?php echo $precioTotal*0.25; ?> (25%)</option>
								</select>

							<?php 

							} elseif($precioTotal <= 0) {

							?>
				
								<select name="pagoNuevaOrden" id="pagoNuevaOrden" class="form-control inputBorderBottom" disabled>
									<option value="0">Haz pagado todo el trabajo.</option>
								</select>	

							<?php

							} else {

							?>

							 	<select name="pagoNuevaOrden" id="pagoNuevaOrden" class="form-control inputBorderBottom">
									<option value="1">$<?php echo $precioTotal; ?> (100%)</option>
								</select>								

							<?php 

							}

							 ?>
		
						</div>

					</div>

					<div class="row mt-3">

						<div class="col-lg-2 mt-1">
							
							<span class="text-muted">Recepcion:</span>
							
						</div>
						
						<div class="col-lg-4">

							<input type="text" class="form-control inputBorderBottom datepicker" style="background-color: white;" placeholder="aaaa-mm-dd" id="fechaRecepcionNuevaOrden" readonly>

						</div>
						
						<div class="col-lg-2 mt-1">
							
							<span class="text-muted">Entrega: </span>
							
						</div>

						<div class="col-lg-4">

							<input type="text" class="form-control inputBorderBottom" id="fechaEntregaNuevaOrden" style="background-color: white;" readonly>
		
						</div>

					</div>


					<div class="row text-center estadoNuevaOrden separacionTablet separacionMovil">
						
						<div class="col-lg-6 mt-lg-4 radioEstado">
										
							<input type="radio" id="prueba" name="estadoTrabajo" value="2" required>
							<label for="prueba" class="etiqueta">A prueba <i class="fas fa-tooth ml-2"></i></label>

						</div>

						<div class="col-lg-6 mt-lg-4 radioEstado">
							
							<input type="radio" id="terminado" name="estadoTrabajo" value="1" required>
							<label for="terminado" class="etiqueta">Terminado <i class="fas fa-teeth-open ml-2"></i></label>

						</div>

					</div>

					<textarea name="descripcionNuevaOrden" id="descripcionNuevaOrden" cols="30" rows="4" class="form-control mt-2" placeholder="Aquí escribe una descripción detallada..."></textarea>



					<form id="uploadimage" action="" class="text-center mt-4" method="post" enctype="multipart/form-data">
						
						<label for="nuevaOrdenImg" class="colorBuscalab subirImagenes">Subir imágenes <i class="fas fa-images ml-2"></i><i class="fas fa-arrow-up ml-2"></i> (opcional)</label>
						<input type="file" name="nuevaOrdenImg[]" id="nuevaOrdenImg" accept="image/*" required multiple>
						<input type="hidden" name="url" value="<?php echo $url; ?>">

					</form>

					<div id="respuestaSubidaImagen" class="row"></div>

				</div>

				<div class="detallesPagoNuevaOrden hidden text-muted">

					<h2 class="colorBuscalab">Selecciona un método de pago</h2>

					<div class="row">					
						
						<div class="col-lg-7">

							<div class="formaPagoNuevaOrden" data-type="divum">
						
								<span>Continuar sin pagar <i class="fas fa-chevron-circle-right ml-2"></i></span>

							</div>
							
							<div class="formaPagoNuevaOrden" data-type="credit_card">
							
								<span><i class="far fa-credit-card mr-2 txtAzul"></i> Tarjeta de crédito <i class="fab fa-cc-mastercard ml-2 txtMastercard"></i> <i class="fab fa-cc-visa ml-2 txtVisa"></i> <i class="fab fa-cc-amex ml-2 txtAmex"></i>
								
								<span class="pagoSeleccionado"></span>

								</span>

							</div>

							<div class="formaPagoNuevaOrden" data-type="debit_card">
								
								<span><i class="fas fa-credit-card mr-2 txtRojo"></i> Tarjeta de débito <i class="fab fa-cc-mastercard ml-2 txtMastercard"></i> <i class="fab fa-cc-visa ml-2 txtVisa"></i>
								
								<span class="pagoSeleccionado"></span>

								</span>

							</div>

							<div class="formaPagoNuevaOrden" data-type="oxxo">
								
								<span><i class="fas fa-money-check-alt mr-2 txtOxxo"></i> Depósito en <img src="<?php echo $url; ?>vistas/asset/images/oxxo.png" class="oxxoPago" alt="">
								
								<span class="pagoSeleccionado"></span>

								</span>

							</div>

							<div class="formaPagoNuevaOrden" data-type="paypal">
								
								<span><i class="fab fa-paypal mr-2 txtPaypal"></i> PayPal
								
								<span class="pagoSeleccionado"></span>

								</span>

							</div>

							<div class="formaPagoNuevaOrden" data-type="transfer">
								
								<span><i class="fas fa-university mr-2 text-secondary"></i> Transferencia bancaria
								
								<span class="pagoSeleccionado"></span>

								</span>

							</div>

						</div>

						<div class="col-lg-5 pt-lg-4">

							<hr class="my-2 col-xl-0 col-lg-0 col-md-0">
								
							<div>

								<span class="float-right">$ <span class="precioPagarNuevaOrden">500</span></span>

								Continuidad de pago:
								
							</div>

							<div>

								<span class="float-right">$ 50</span>

								Comisión por el servicio:
								
							</div>

							<h4 class="colorBuscalab font-weight-bold py-3">
								
								<span class="float-right">$ 550</span>

								Total <span class="col-md-0 col-sm-0 col-0"> a pagar</span>: 
									
							</h4>
							
						</div>

					</div>				


				</div>

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btnBuscalab btnGenerarOrden">Generar orden <i class="fas fa-arrow-right ml-2"></i></button>
				<button type="button" class="btn btnBuscalab btnRegresar hidden"><i class="fas fa-undo-alt mr-2"></i> Regresar</button>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
			</div>

		</div>

	</div>

</div>

<script>
		
	var tiempoEntrega = <?php echo $tiempoEntrega; ?> + 1;

	var idOrden = "<?php echo $idOrden; ?>";

</script>