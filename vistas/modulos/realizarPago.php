<?php 

if(isset($_SESSION['dentista'])){

	$tipoUsuario = "dentista";
	$usuario = $_SESSION['dentista'];

} else if(isset($_SESSION['tecnico'])){

	$tipUsuario = "tecnico";
	$usuario = $_SESSION['tecnico'];

}



if(isset($_POST['ordenConfirmada'], $_POST['idBox'])){

	$idBox = $_POST['idBox'];
	$datosBox = controladorBox::ctrDatosBox($idBox);


	if($datosBox['id_prod'] != NULL){

		$idTrabajo = $datosBox['id_prod'];

	} else if($datosBox['id_prod_ort'] != NULL) {

		$idTrabajo = $datosBox['id_prod_ort'];

	} else {

		echo '<script>
			index();
			</script>';

	}


	$tipoOrden = $datosBox['tipo'];
	$colorimetria = $datosBox['colorimetria'];

	$infoPerfil = controladorPerfil::ctrDatosPerfil($usuario,$tipoUsuario); //SE TRAE LA INFORMACIÓN DEL PERFIL DEL USUARIO TIPO DENTISTA
	$infoOrden = controladorOrden::ctrInfoOrden($idTrabajo,$tipoOrden,$colorimetria);

	$dientes = $datosBox['dientes'];
	$numDientes = count(explode(",", $dientes));

	$nombre = $infoPerfil['nomb'];
	$infoOrden['trabajo'] = ucfirst($infoOrden['trabajo']);
	$diasEntrega = $infoOrden['tiempo'] + 1;
	$precio = ($infoOrden['precio'] * $datosBox['porcentajePagar'])*$numDientes;
	$referencia = "nuevoTrabajo_".$idBox."_".$usuario;



	// REALIZAMOS EL SCRIPT PARA TRAER EL ENLACE DE PAYPAL
	$urlPagoPaypal = Paypal::ctrPagoPaypal($infoOrden['trabajo'],$idTrabajo,$precio,$idBox);

	//REALIZAMOS EL CODIGO PARA TRAER EL ENLACE DE MERCADOPAGO
	//$urlMercadopago = Mercadopago::ctrPagoOrden($infoOrden['trabajo'],$idTrabajo,$precio,$referencia,$nombre,'test_user_43086458@testuser.com',$infoPerfil['tel']);


} else {

	echo '<script>
		index();
		</script>';

}

 ?>

<div class="container">
		
	<div class="row py-4">

		<div class="col-xl-1 col-lg-1 col-md-0 col-sm-0 col-0"></div>
			
		<div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 text-muted">
			
			<h3 class="bold">Por último, elige una forma de pago</h3>

			<div class="pt-3 listaPago row">

				<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 text-left">

					<div class="formaPago" data-type="divum" data-box="<?php echo $idBox; ?>">
						
						<span>Continuar sin pagar <i class="fas fa-chevron-circle-right ml-2"></i></span>

					</div>

					<div class="formaPago" data-type="credit_card" data-box="<?php echo $idBox; ?>">
					
						<span><i class="far fa-credit-card mr-2 txtAzul"></i> Tarjeta de crédito <i class="fab fa-cc-mastercard ml-2 txtMastercard"></i> <i class="fab fa-cc-visa ml-2 txtVisa"></i> <i class="fab fa-cc-amex ml-2 txtAmex"></i></span>

					</div>

					<div class="formaPago" data-type="debit_card" data-box="<?php echo $idBox; ?>">
						
						<span><i class="fas fa-credit-card mr-2 txtRojo"></i> Tarjeta de débito <i class="fab fa-cc-mastercard ml-2 txtMastercard"></i> <i class="fab fa-cc-visa ml-2 txtVisa"></i></span>

					</div>

					<div class="formaPago" data-type="oxxo" data-box="<?php echo $idBox; ?>">
						
						<span><i class="fas fa-money-check-alt mr-2 txtOxxo"></i> Depósito en <img src="<?php echo $url; ?>vistas/asset/images/oxxo.png" class="oxxoPago" alt=""></span>

					</div>

					<div class="formaPago" data-type="paypal" data-box="<?php echo $idBox; ?>">
						
						<span><i class="fab fa-paypal mr-2 txtPaypal"></i> PayPal</span>

					</div>

					<div class="formaPago" data-type="transfer" data-box="<?php echo $idBox; ?>">
						
						<span><i class="fas fa-university mr-2 text-secondary"></i> Transferencia bancaria</span>

					</div>

					
					<!--<div class="row pt-3 radioPago">
					
						<div class="col-lg-6 ">
							
							<input type="radio" id="paypal" name="pago" value="paypal">
							<label for="paypal" class="etiqueta">PayPal</label>

							
							
							<label for="paypal">
								<img src="<?php echo $url; ?>vistas/asset/images/paypal.png" alt="">
							</label>
						
						</div>

						<div class="col-lg-6 ">

							<input type="radio" id="mercadopago" name="pago" value="mercadopago">
							<label for="mercadopago" class="etiqueta">MercadoPago</label>
							
							<label for="mercadopago">
								<img src="<?php echo $url; ?>vistas/asset/images/mercadopago.png" alt="">
							</label>

						</div>

						<div class="col-lg-12 text-right pt-3">
							
							<button class="btn btnBuscalab py-3 btnPagarOrden">Pagar <i class="fas fa-money-bill ml-2"></i></button>

						</div>

					</div>-->
					
				</div>

				
				<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">

					<hr class="my-2 col-xl-0 col-lg-0 col-md-0">
					
					<div>
						
						<span class="float-right">$<?php echo $infoOrden['precio']; ?></span>

						Precio por diente:
							
					</div>

					<div>

						<span class="float-right"><?php echo $datosBox['porcentajePagar']*100; ?>%</span>

						Porcentaje de pago:
						
					</div>

					<div>

						<span class="float-right"><?php echo $numDientes; ?></span>

						Número de unidades:
						
					</div>

					<h4 class="colorBuscalab font-weight-bold py-3">
						
						<span class="float-right">$<?php echo $precio; ?></span>

						Total <span class="col-md-0 col-sm-0 col-0"> a pagar</span>: 
							
					</h4>
					

					<!--<div><?php echo $infoOrden['trabajo']; ?> <strong class="txtCorto">...................</strong> $<?php echo $infoOrden['precio']; ?></div>
					<div>Porcentaje de pago <strong class="txtCorto">............................................</strong> <?php echo $datosBox['porcentajePagar']*100; ?>%</div>
					<div>Cargos adicionales .............................................. $25</div>-->

				</div>

			</div>

			<hr class="my-3">

			<h5 class="colorBuscalab col-md-0 col-sm-0 col-0">Agrega un producto antes de ordenar:</h4>

			<div id="carruselWoocommerce" class="carousel slide py-3 col-md-0 col-sm-0 col-0" data-ride="carousel">

			  	<div class="carousel-inner">

				    <div class="carousel-item active">

				      	<div class="row">
				      	
							<div class="col-lg-4">
									
								<div class="item">

									<div class="imagenProductoMarket">
										
										<img src="https://www.dentalcost.es/2890-large_default/coltosol-f-cemento-provisional-bote-38gr.jpg" alt="producto market" class="img-fluid">

										<span class="descuentoProductoMarket">-25%</span>

									</div>


									<hr class="my-2">

									<div class="row">
										
										<div class="col-lg-8">
											
											<span class="text-muted">Cemento temporal</span>
											<div class="text-muted">$510</div>

										</div>

										<div class="col-lg-4 text-right">
											
											<span class="icon-dantu_icono_carrito" style="font-size: 40px; color:gray"></span>

										</div>

									</div>

								</div>

							</div>

							<div class="col-lg-4">
								
								<div class="item">

									<div class="imagenProductoMarket">
										
										<img src="https://www.dentalcost.es/4502-thickbox_default/proviscell-cemento-temporal-sin-eugenol-2-tubos-25g.jpg" alt="producto market" class="img-fluid">

										<span class="descuentoProductoMarket">-25%</span>

									</div>


									<hr class="my-2">

									<div class="row">
										
										<div class="col-lg-8">
											
											<span class="text-muted">Cemento temporal</span>
											<div class="text-muted">$510</div>

										</div>

										<div class="col-lg-4 text-right">
											
											<span class="icon-dantu_icono_carrito" style="font-size: 40px; color:gray"></span>

										</div>

									</div>

								</div>

							</div>

							<div class="col-lg-4">
								
								<div class="item">

									<div class="imagenProductoMarket">
										
										<img src="https://www.dentalcost.es/6136-thickbox_default/kit-cemento-temporal-sin-eugenol-jeringa-4x6gr.jpg" alt="producto market" class="img-fluid">

										<span class="descuentoProductoMarket">-25%</span>

									</div>


									<hr class="my-2">

									<div class="row">
										
										<div class="col-lg-8">
											
											<span class="text-muted">Cemento temporal</span>
											<div class="text-muted">$510</div>

										</div>

										<div class="col-lg-4 text-right">
											
											<span class="icon-dantu_icono_carrito" style="font-size: 40px; color:gray"></span>

										</div>

									</div>

								</div>

							</div>

				      	</div>
			    	</div> <!--TERMINA PRIMER PAGINACION DE ITEMS-->

			    	<div class="carousel-item">
				      	<div class="row">
				      	
							<div class="col-lg-4">
									
								<div class="item">

									<div class="imagenProductoMarket">
										
										<img src="https://cdn.totalcode.com/newstetic/product-zoom/es/vitrebond-ionomero-de-vidrio-tipo-liner-base-cavitaria-kit-polvo-9-g--liqudio-5.5-ml-1.jpg" alt="producto market" class="img-fluid">

										<span class="descuentoProductoMarket">-50%</span>

									</div>


									<hr class="my-2">

									<div class="row">
										
										<div class="col-lg-8">
											
											<span class="text-muted">Ionomero de vidrio</span>
											<div class="text-muted">$300</div>

										</div>

										<div class="col-lg-4 text-right">
											
											<span class="icon-dantu_icono_carrito" style="font-size: 40px; color:gray"></span>

										</div>

									</div>

								</div>

							</div>

							<div class="col-lg-4">
								
								<div class="item">

									<div class="imagenProductoMarket">
										
										<img src="https://www.dentalcost.es/6543-thickbox_default/ionolux-ionomero-vidrio-polvo-12gr-liq-5gr.jpg" alt="producto market" class="img-fluid">

										<span class="descuentoProductoMarket">-50%</span>

									</div>


									<hr class="my-2">

									<div class="row">
										
										<div class="col-lg-8">
											
											<span class="text-muted">Ionomero de vidrio</span>
											<div class="text-muted">$300</div>

										</div>

										<div class="col-lg-4 text-right">
											
											<span class="icon-dantu_icono_carrito" style="font-size: 40px; color:gray"></span>

										</div>

									</div>

								</div>

							</div>

							<div class="col-lg-4">
								
								<div class="item">

									<div class="imagenProductoMarket">
										
										<img src="http://cosmodent.cl/img/p/4/6/5/465-large_default.jpg" alt="producto market" class="img-fluid">

										<span class="descuentoProductoMarket">-50%</span>

									</div>


									<hr class="my-2">

									<div class="row">
										
										<div class="col-lg-8">
											
											<span class="text-muted">Ionomero de vidrio</span>
											<div class="text-muted">$300</div>

										</div>

										<div class="col-lg-4 text-right">
											
											<span class="icon-dantu_icono_carrito" style="font-size: 40px; color:gray"></span>

										</div>

									</div>

								</div>

							</div>

				      	</div>

			    	</div> <!--TERMINA SEGUNDA PAGINACION DE ITEMS-->
				    	
			  	</div>

				<a class="carousel-control-prev" href="#carruselWoocommerce" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="carousel-control-next" href="#carruselWoocommerce" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>

			</div> <!--TERMINA EL DIV DEL CARROUSEL-->



		</div>

		<div class="col-lg-1"></div>

	</div>

</div>

<script>

	var urlPaypal = "<?php echo $urlPagoPaypal; ?>";

	var idBox = "<?php echo $idBox; ?>";

</script>