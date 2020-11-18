<?php 

if(isset($_SESSION['dentista'])){

	$tipoUsuario = "dentista";
	$usuario = $_SESSION['dentista'];

} else if(isset($_SESSION['tecnico'])){

	$tipUsuario = "tecnico";
	$usuario = $_SESSION['tecnico'];

}

if(isset($_POST['ordenarAhora'])){ //SÍ VIENE DESDE EL FORMULARIO DE PRE ORDEN

	/*if(isset($_POST['esUrgente'])){
		$tipoOrden="urgente";
	} else {
		$tipoOrden="ordinario";
	}*/

	if(isset($_POST['colorimetria'])){ //SÍ EXISTE COLORIMETRIA, SIGNIFICA QUE ES UN TRABAJO DE PROTESIS
		$colorimetria = $_POST['colorimetria'];
	} else { //SI NO EXISTE LA COLORIMETRIA, SIGNIFICA QUE ES UN TRABAJO DE ORTODONCIA
		$colorimetria = NULL;
	}

	$idTrabajo = $_POST['idProducto']; //ID DEL PRODUCTO DE PROTESOS U ORTODONCIA
	$direccionRecepcion = $_POST['direccionRecepcion']; //ID DE DIRECCION DE RECEPCION
	$direccionEntrega = $_POST['direccionEntrega'];// ID DE DIRECCION DE ENTREGA
	$porcientoPagar = $_POST['pagoInicial']; //EL PORCENTAJE DE PAGO INICIAL QUE SELECCIONO EL DENTISTA EN LA PREORDEN
	$nombrePaciente = $_POST['nombrePaciente']; //EL NOMBRE DEL PACIENTE DE LA ORDEN
	$especTrabajo = $_POST['descripcion']; //ESPECIFICACIONES ADICIONALES SOBRE EL TRABAJO QUE REALIZARA EL LABORATORIO
	$tipoOrden = $_POST['tipoOrden']; //TIPO DE ORDEN, SI ES URGENTE U ORDINARIO
	$horaRecepcion = $_POST['horaRecepcion']; //LA HORA ESTIMADA DE RECEPCIÓN
	$horaEntrega = $_POST['horaEntrega']; // LA HORA ESTIMADA DE ENTREGA
	$fechaRecepcion = $_POST['fechaRecepcion']; //LA FECHA DE RECEPCION PARA EL DENTISTA
	$fechaEntrega = $_POST['fechaEntrega']; //LA FECHA DE ENTREGA PARA EL DENTISTA
	$estadoTrabajo = $_POST['estadoTrabajo']; //ES EL ESTADO A PRUEBA O TERMINADO DE CADA TRABAJO

	$dientesSeleccionados = substr($_POST['dientesSeleccionados'], 0, -1); //EL ULTIMO CAMPO DEL ARRAY NO VALE, ES NULL
	$numDientesSeleccionados =  count(explode(",", $dientesSeleccionados));


	//$imagenesOrden = controladorOrden::ctrExisteImagenes($idBox);
		
	$idBox = controladorOrden::ctrExisteOrdenBox($usuario); //SABER SI EXISTE LA ORDEN EN LA BOX, ES PORQUE VIENE DESDE LA PAGINA DE PREORDEN

	//TRAEMOS INFORMACION PARA LA ORDEN, DEPENDIENDO SÍ ES URGENTE U ORDINARIO.

	$infoOrden = controladorOrden::ctrInfoOrden($idTrabajo,$tipoOrden,$colorimetria); //SE ENVIA LA COLORIMETRIA PARA IDENTIFICAR SI ES ORTODONCIA O PROTESIS
	$infoPerfil = controladorPerfil::ctrDatosPerfil($usuario,$tipoUsuario); //SE TRAE LA INFORMACIÓN DEL PERFIL DEL USUARIO TIPO DENTISTA
	$infoDireccionEntrega = controladorPerfil::ctrDirecciones($usuario,$tipoUsuario,$direccionEntrega);
	$infoDireccionRecepcion = controladorPerfil::ctrDirecciones($usuario,$tipoUsuario,$direccionRecepcion); //SE OBTIENE LA INFORMACION DE LAS DIRECCIONES

	date_default_timezone_set('UTC');
	date_default_timezone_set("America/Mexico_City");	
	$nombre = $infoPerfil['nomb'];
	$infoOrden['trabajo'] = ucfirst($infoOrden['trabajo']);
	$diasEntrega = $infoOrden['tiempo'] + 1;
	$precio = $infoOrden['precio'] * $porcientoPagar;

	$material = "<br>"; //
	$nombreColor = '<b>Colorimetria:</b> No aplica';

	if($colorimetria != NULL){ //SÍ LA ORDEN ES DE PROTESIS, LE COLOCAMOS EL MATERIAL Y LA COLORIMETRIA

		$infoColorimetria = ctrInfoProducto::ctrColorimetria($colorimetria); //SE OBTIENE LA INFORMACION DEL COLOR QUE ELIGIO
		$nombreColor = "<b>Colorimetria:</b> ".$infoColorimetria[0]['nomb'];
		$infoOrden['material'] = ucfirst($infoOrden['material']);
		$material = "(".$infoOrden['material'].")";

	}

} elseif(isset($_POST['idBox'])) { // SÍ VIENE DESDE LA CAJA DE ORDENES

	$idBox = $_POST['idBox'];

	$datosBox = controladorBox::ctrDatosBox($idBox);

	$tipoOrden = $datosBox['tipo'];
	$colorimetria = $datosBox['colorimetria'];

	$idTrabajo = ($datosBox['id_prod'] != NULL) ? $datosBox['id_prod'] : $datosBox['id_prod_ort']; //ID DEL PRODUCTO DE PROTESOS U ORTODONCIA
	$direccionRecepcion = $datosBox['recepcion']; //ID DE DIRECCION DE RECEPCION
	$direccionEntrega = $datosBox['entrega'];// ID DE DIRECCION DE ENTREGA
	$porcientoPagar = $datosBox['porcentajePagar']; //EL PORCENTAJE DE PAGO INICIAL QUE SELECCIONO EL DENTISTA EN LA PREORDEN
	$nombrePaciente = $datosBox['paciente']; //EL NOMBRE DEL PACIENTE DE LA ORDEN
	$especTrabajo = $datosBox['specs']; //ESPECIFICACIONES ADICIONALES SOBRE EL TRABAJO QUE REALIZARA EL LABORATORIO
	$horaRecepcion = $datosBox['hora']; //LA HORA ESTIMADA DE RECEPCIÓN
	//$horaEntrega = $datosBox['horaEntrega']; // LA HORA ESTIMADA DE ENTREGA
	$fechaRecepcion = $datosBox['fecha_rec']; //LA FECHA DE RECEPCION PARA EL DENTISTA
	$fechaEntrega = $datosBox['fecha_ent']; //LA FECHA DE ENTREGA PARA EL DENTISTA
	$estadoTrabajo = $datosBox['estado']; //ES EL ESTADO A PRUEBA O TERMINADO DE CADA TRABAJO

	$dientesSeleccionados = $datosBox['dientes']; //SECUENCIA DE DIENTES SELECCIONADOS POR EL USUARIO
	$numDientesSeleccionados =  count(explode(",", $dientesSeleccionados)); //NUM DE DIENTES SELECCIONADOS POR EL USUARIO

	$infoOrden = controladorOrden::ctrInfoOrden($idTrabajo,$tipoOrden,$colorimetria); //SE ENVIA LA COLORIMETRIA PARA IDENTIFICAR SI ES ORTODONCIA O PROTESIS
	$infoPerfil = controladorPerfil::ctrDatosPerfil($usuario,$tipoUsuario); //SE TRAE LA INFORMACIÓN DEL PERFIL DEL USUARIO TIPO DENTISTA
	$infoDireccionEntrega = controladorPerfil::ctrDirecciones($usuario,$tipoUsuario,$direccionEntrega);
	$infoDireccionRecepcion = controladorPerfil::ctrDirecciones($usuario,$tipoUsuario,$direccionRecepcion); //SE OBTIENE LA INFORMACION DE LAS DIRECCIONES

	date_default_timezone_set('UTC');
	date_default_timezone_set("America/Mexico_City");	
	$nombre = $infoPerfil['nomb'];
	$infoOrden['trabajo'] = ucfirst($infoOrden['trabajo']);
	$diasEntrega = $infoOrden['tiempo'] + 1;
	$precio = $infoOrden['precio'] * $porcientoPagar;

	$material = "<br>"; //
	$nombreColor = '<b>Colorimetria:</b> No aplica';

	if($colorimetria != NULL){ //SÍ LA ORDEN ES DE PROTESIS, LE COLOCAMOS EL MATERIAL Y LA COLORIMETRIA

		$infoColorimetria = ctrInfoProducto::ctrColorimetria($colorimetria); //SE OBTIENE LA INFORMACION DEL COLOR QUE ELIGIO
		$nombreColor = "<b>Colorimetria:</b> ".$infoColorimetria[0]['nomb'];
		$infoOrden['material'] = ucfirst($infoOrden['material']);
		$material = "(".$infoOrden['material'].")";

	}

}


?>

 <div class="container pt-2">

 	<div class="text-center text-muted py-3 tituloConfirmarDetalles">
 			
 		<h3><i class="fas fa-check mr-2"></i>Confirma los detalles de tu orden para continuar:</h3>

 	</div>

	
	<div class="row my-4">
		
		<div class="col-xl-2 col-lg col-md-0 col-sm-0 col-0"></div>

		<div class="col-xl-8 col-lg-9 col-md-12 col-sm-12 col-12 confirmacionOrden text-muted">
			
			<div class="text-left">
				
				<img src="<?php echo $url; ?>vistas/asset/images/logo.png" alt="Buscalab">

				<span class="txtLogo pt-2">
					<span class="colorBuscalabGris bold"><span class="colorBuscalab">Busca</span>lab</span>
				</span>

			</div>

			<div class="row pt-3">
				
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-left">
					
					<h3 class="colorBuscalab"><?php echo $infoOrden['laboratorio']; ?></h3>

				</div>

				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">

					<div class="nombreDoctor"><b>Doctor:</b> <?php echo $infoPerfil['nomb']; ?></div>

				</div>
				
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
					
					<p class="nombreTecnico"><b>Técnico:</b> <?php echo $infoOrden['artista']; ?></p>

				</div>

			</div>

			<div class="informacionOrden shadow my-2">
				
				<div class="row">
					
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-3">
						
						<h4 class="colorBuscalab"><?php echo $infoOrden['trabajo']; ?></h4>		
						<!--<p><?php echo $material; ?></p>-->

					</div>

					<div class="col-xl-6 col-lg-6 col-md-6">

						<p><i class="fas fa-user mr-2"></i><b>Paciente:</b> <?php echo $nombrePaciente; ?></p>

					</div>

					<div class="col-xl-6 col-lg-6 col-md-6">

						<p><i class="fas fa-teeth-open mr-2"></i><b>Dientes:</b> <?php echo ($dientesSeleccionados == "") ? 'No aplica' : $dientesSeleccionados; ?></p>

					</div>

					<div class="col-xl-6 col-lg-6 col-md-6">

						<p><i class="fas fa-clock mr-2"></i> <b>Tiempo de entrega:</b> <?php echo $infoOrden['tiempo']; echo ($infoOrden['tiempo'] == 1) ? ' día' : ' días'; ?> </p>
						
						
					</div>

					<div class="col-xl-6 col-lg-6 col-md-6">
						
						<p><i class="fas fa-fill-drip mr-2"></i><?php echo $nombreColor; ?></p>

					</div>

					<div class="col-xl-6 col-lg-6 col-md-6">
						
						<p><b><i class="fas fa-tooth mr-2"></i>Costo por unidad:</b> $ <?php echo $infoOrden['precio'];  ?></p>
						
					</div>

					<div class="col-xl-6 col-lg-6 col-md-6">
						
						<p><b>Porcentaje a pagar por unidad:</b> <?php echo $porcientoPagar*100; ?> %</p>
						
					</div>

					<?php 
					if($estadoTrabajo == 2){ //TRABAJO A PRUEBA

					 ?>
					

					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 mb-2">
						
						<span class="inputFalseSelected bold">A prueba</span>

					</div>

					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 mb-2">
						
						<span class="inputFalse">Terminado</span>

					</div>

				<?php } else if($estadoTrabajo == 1){// TRABAJO TERMINADO ?>


					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 mb-2">
						
						<span class="inputFalse">A prueba</span>

					</div>

					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 mb-2">
						
						<span class="inputFalseSelected bold">Terminado</span>

					</div>

				<?php } ?>

					<!--<div class="col-lg-3 text-left"></div>

					<div class="col-lg-4 py-2">
						
						<span><i class="fas fa-calendar mr-2"></i><b>Fecha:</b></span>

					</div>

					<div class="col-lg-5 py-2">
						
						<span><i class="fas fa-map-marker-alt mr-2"></i><b>Dirección:</b></span>

					</div>-->

					<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 pt-3">
						
						<span><b>Recolección:</b></span>

					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-5 pt-3 text-left">
						
						<span><i class="fas fa-calendar-alt mr-2"></i><?php echo $fechaRecepcion; ?></span>

					</div>

					<div class="col-xl-5 col-lg-5 col-md-5 col-sm-7 col-7 pt-3 text-left txtCorto">
						
						<span><i class="fas fa-map-marker-alt mr-2"></i><?php echo $infoDireccionRecepcion[0]['calle']; ?></span>

					</div>

					<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 pt-3">
						
						<span><b>Entrega:</b></span>

					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-5 pt-3 text-left">
						
						<span><i class="fas fa-calendar-alt mr-2"></i><?php echo $fechaEntrega; ?></span>

					</div>

					<div class="col-xl-5 col-lg-5 col-md-5 col-sm-7 col-7 pt-3 text-left txtCorto">
						
						<span><i class="fas fa-map-marker-alt mr-2"></i><?php echo $infoDireccionEntrega[0]['calle']; ?></span>

					</div>

				</div>

				<!--<div class="row mt-4">

					<div class="col-lg-6">
						
						<p><b><i class="fas fa-tooth mr-2"></i>Costo por unidad:</b> $ <?php echo $infoOrden['precio'];  ?></p>
						
					</div>

					<div class="col-lg-6">
						
						<p><b>Porcentaje a pagar por unidad:</b> <?php echo $porcientoPagar*100; ?> %</p>
						
					</div>
		
				</div>-->

				<div class="text-left pt-4 bold">
					
					<h3><i class="fas fa-dollar-sign mr-2 "></i>TOTAL: $ <?php echo ($infoOrden['precio']*$porcientoPagar)*$numDientesSeleccionados; ?></h3>

				</div>

			</div> <!--FIN DE LA ORDEN-->

			<h5 class="py-3">Especificaciones:</h5>

			<div class="especificacionesOrden py-3">
				
				<?php echo $especTrabajo; ?>

			</div>


			<!------------------------------------------------------------------------>

			<div class="my-3 imagenesOrden">

				<div id="carouselExample" class="carousel slide mb-5" data-ride="carousel">

				  	<div class="carousel-inner">

				  		<?php 

						$imagenesOrden = controladorOrden::ctrExisteImagenes($idBox); //SÍ NO EXISTEN IMAGENES, RETORNA FALSE Y SÍ TIENE IMAGENES RETORNA EL ARREGLO CON EL NOMBRE DE LAS IMAGENES

						if(!$imagenesOrden){

							echo '<div class="carousel-item active">

					    			<div class="row">

										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
											<div class="text-muted txtBold">No se agregaron imágenes para éste trabajo</div>
										</div>

									</div>

								</div>';

						} else {

							if(count($imagenesOrden) > 3){ //SÍ HAY MAS DE 3 IMAGENES, TENGO QUE MOSTRARLAS EN UN CARRUSEL

								$numItemsCarrousel = ceil(count($imagenesOrden) / 3);

								$htmlImagenes = "";

					    		for ($i=1; $i <= $numItemsCarrousel; $i++) {

					    			if($i == 1){

										$htmlImagenes .= '<div class="carousel-item active">

							    				<div class="row">';
					    			} else {

					    				$htmlImagenes .= '<div class="carousel-item">

							    				<div class="row">';

					    			} 

					    			
					    			$inicioArregloImagenes = ($i - 1) * 3;

					    			for ($y=$inicioArregloImagenes; $y < ($inicioArregloImagenes + 3); $y++) {

					    				if(isset($imagenesOrden[$y]['nombre'])){

						    				$htmlImagenes .= '<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
								
												<img src="'.$url.'vistas/asset/images/ordenes/'.$imagenesOrden[$y]['nombre'].'" alt="" data-action="zoom">

											</div>';

					    				} 

					    			}

					    			$htmlImagenes .= '</div>

						    				</div>';

					    		}

					    		echo $htmlImagenes;

							} else {

								echo '<div class="carousel-item active">

					    				<div class="row">';

								foreach ($imagenesOrden as $key => $value) {
									
									echo '<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
							
											<img src="'.$url.'vistas/asset/images/ordenes/'.$value['nombre'].'" alt="" data-action="">

										</div>';

								}

								echo '	</div>

								</div>';

							}


						}

						 ?>
						

					    
					    	
				  	</div>

				  	<?php 

				  	if(!$imagenesOrden){ //SÍ NOE XISTEN IMAGENES, NO IMPRIMIMOS NADA

					} else { //SÍ EXISTEN IMAGENES, IMPRIMIMOS LAS FLECHAS DE NAVEGACION DE LAS IMAGENES

						if(count($imagenesOrden) > 3){

				  	 ?>

				  	<a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>

				<?php 	}
					} ?>

					

				</div> <!--TERMINA EL DIV DEL CARROUSEL-->

				

			</div>

			<!-------------------------------------------------------------------------->

			

			<div class="txtPequeño">
				
				* La orden elaborada está apegada a las políticas establecidas dentro de Buscalab

			</div>

			<hr>

			<!--<div class="text-left">
				
				<div class="pb-3">Tiempo de entrega: <?php echo $infoOrden['tiempo']; ?> días</div>
				<div>Realización de orden:</div>
				<div><?php echo $infoOrden['trabajo']; ?> ............ $<?php echo $infoOrden['precio'];  ?></div>
				<div><b>Pago inical ............ $<?php echo ($infoOrden['precio']*$porcientoPagar); ?></b></div>
	
			</div>

			<div class="text-left pt-2 bold">
				
				<span>TOTAL: $<?php echo ($infoOrden['precio']*$porcientoPagar); ?></span>

			</div>-->

			<div class="row text-center my-4 col-sm-0 col-0">
				
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
					
					<a href="javascript:history.back(-1);"><button class="btn btnOutlineBuscalab">Regresar <i class="fas fa-undo-alt ml-2"></i></button></a>

				</div>

				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 separacionMovil">
					<form action="realizar-pago" method="POST">
					<input type="hidden" name="idBox" value="<?php echo $idBox; ?>">
					<button class="btn btnBuscalab" name="ordenConfirmada">Confirmar <i class="fas fa-arrow-right ml-2"></i></button>
					</form>

				</div>

			</div>

		</div>

		<div class="col-xl-2 col-lg col-md-0 col-sm-0 col-0"></div>

	</div>

</div>

<div class="fixed-bottom col-xl-0 col-lg-0 col-md-0 col-sm-0 col-0" id="botonesMovilesConfirmarOrden">

	<div class="row">
		
		<div class="col-sm-6 col-6 regresar">
						
			<a href="javascript:history.back(-1);"><button class="btn btnOutlineBuscalab">Regresar <i class="fas fa-undo-alt ml-2"></i></button></a>

		</div>

		<div class="col-sm-6 col-6 confirmar">
			<form action="realizar-pago" method="POST">
				<input type="hidden" name="idBox" value="<?php echo $idBox; ?>">
				<button class="btn btnBuscalab" name="ordenConfirmada">Confirmar <i class="fas fa-arrow-right ml-2"></i></button>
			</form>

		</div>

	</div>

</div>