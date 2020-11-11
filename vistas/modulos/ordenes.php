<?php 

setlocale(LC_TIME, 'es_MX.UTF-8', 'esp_esp'); 

if(isset($_SESSION['tecnico'])){

	$idUsuario = $_SESSION['tecnico'];
	$tipo = "tecnico";
	$iconoTabla = '<img src="'.$url.'vistas/asset/images/dentista.png" alt="Dentista" class="mr-2" width="25px"> Dentista/Clínica';

} elseif (isset($_SESSION['dentista'])){

	$idUsuario = $_SESSION['dentista'];
	$tipo = "dentista";
	$iconoTabla = '<img src="'.$url.'vistas/asset/images/tecnico.png" alt="Tecnico dental" class="mr-2" width="25px"> Técnico/Laboratorio';

}

//$relaciones = controladorOrden::ctrRelacion($idUsuario,$tipo);

$filtro = "prioritario";

$ordenes = controladorOrden::ctrOrdenes($idUsuario,$tipo,$filtro);

?>

<div class="container py-3">
	
	<div class="row">
		
		<div class="col-xl-7 col-lg-7 col-md-8 col-sm-12 col-12 mt-1">
				
			<h3 class="text-muted bold">Ordenes de trabajo</h3>

		</div>

		<div class="col-xl-5 col-lg-7 col-md-4 col-sm-12 col-12 mt-1">

			<div id="busquedaOrden" class="input-group" style="border-bottom: 1px solid #a5a5a5">
				
				<input id="buscarOrden" type="text" class="form-control buscarOrden" placeholder="Buscar una orden..." autocomplete="off">
				
				
				<button class="btn btnBuscarOrden"><i class="fas fa-search"></i></button>
				

			</div>

		</div>

	</div>

	<ul class="nav nav-pills mt-3 pb-3 barraNavegacionOrdenes" id="pills-tab" role="tablist">

		<li class="nav-item">
			<a class="nav-link active" id="pills-ordenesTotales" data-contenedor="pills-prioritario" data-toggle="pill" href="#pills-prioritario" role="tab" aria-controls="pills-home" aria-selected="true">Total <span class="badge badge-light"></span></a>
		</li>

		<li class="nav-item">
			<a class="nav-link" id="pills-ordenesNuevas" data-contenedor="pills-nuevo" data-toggle="pill" href="#pills-nuevo" role="tab" aria-controls="pills-profile" aria-selected="false">Nuevo</a>
		</li>

		<!--<li class="nav-item">
			<a class="nav-link" id="pills-ordenesAtrasadas" data-contenedor="pills-atrasado" data-toggle="pill" href="#pills-atrasado" role="tab" aria-controls="pills-contact" aria-selected="false">Atrasado</a>
		</li>-->

		<li class="nav-item">
			<a class="nav-link" id="pills-ordenesFinalizadas" data-contenedor="pills-completado" data-toggle="pill" href="#pills-completado" role="tab" aria-controls="pills-contact" aria-selected="false">Finalizado</a>
		</li>

		<li class="nav-item">
			<a class="nav-link" id="pills-ordenesCanceladas" data-contenedor="pills-cancelado" data-toggle="pill" href="#pills-cancelado" role="tab" aria-controls="pills-contact" aria-selected="false">Cancelado</a>
		</li>

		<li class="nav-item hidden">
			<a class="nav-link" id="pills-busquedaUsuario" data-contenedor="pills-busqueda" data-toggle="pill" href="#pills-busqueda" role="tab" aria-controls="pills-contact" aria-selected="false">Busqueda</a>
		</li>

	</ul>

	<p class="txtRojo col-sm-0 col-0">Las ordenes marcadas con rojo, son <b>URGENTES</b> <i class="fas fa-exclamation-circle mr-3"></i></p>

</div>

<div class="container noPadding separacionFooter">

	<div class="tab-content my-2" id="pills-tabContent">



		<div class="tab-pane fade show active" id="pills-prioritario" role="tabpanel" aria-labelledby="pills-home-tab">
			
			<!--<h4 class="text-muted mb-3">Ordenes</h4>-->

			<!--<hr>

			<div class="row px-3 no-gutters elementoTablaOrdenes col-xl-0 col-lg-0 col-md-0" data-id="ID-XVLYGgF3Ak">
				
				<div class="col-sm-8 col-8 form-inline">
					<a href="<?php echo $url; ?>ordenes?orden=ID-XVLYGgF3Ak">
						<img src="<?php echo $url; ?>vistas/asset/images/logo.png" alt="'.$nombre.'" class="img-fluid imgPerfilOrdenes mr-1">
						José ricardo
					</a>
				</div>

				<div class="col-sm-4 col-4 text-right">
					<p class="fechaEntregaOrdenes text-muted">28-03-2019</p>
				</div>

				<div class="col-sm-12 col-12 mt-2">
					<p class="text-muted bold">Prostodoncia total de acrilico</p>
				</div>

				<div class="col-sm-9 col-9 form-inline">
					<p class="text-info estadoOrdenes"><i class="fas fa-spinner fa-spin mr-2"></i> En proceso</p>
					<p class="avisoOrdenes flagNuevo shadow">¡Nuevo!</p>
				</div>

				<div class="col-sm-3 col-3 text-right">
					<p class="txtVerde bold precioOrdenes">$500</p>
				</div>

			</div>

			<hr>-->

			<div class="pills-prioritarioContenedor">

				<?php 

				//AQUI SE DEBEN COLOCAR LOS ELEMENTOS MOVILES

				if(!$ordenes){

					//echo '<div class="alert alert-warning">No existen ordenes de trabajo por parte de los dentistas... aún</div>';

				} else {

					foreach ($ordenes as $key => $value) {

						$nombre = ucfirst($value['paciente']);

						$primeraLetra = substr($nombre, 0, 1);

						$idOrd = $value['id_ord'];

						if( ($value['fotoPerfil'] == "dentista.png") || ($value['fotoPerfil'] == "imgRelleno.png") ){

							$fotoPerfil = '<div class="letraPerfilOrden mr-2">
												<p>'.$primeraLetra.'</p>
											</div>
										<a href="'.$url.'ordenes?orden='.$idOrd.'">'.$nombre.'</a>';

						} else{

							if($tipo == "tecnico") { $carpeta = "dentistas"; } else { $carpeta = "tecnicos"; }

							$fotoPerfil = '<a href="'.$url.'ordenes?orden='.$idOrd.'">
											<img src="'.$url.'vistas/asset/images/'.$carpeta.'/'.$value['fotoPerfil'].'" alt="'.$nombre.'" class="img-fluid imgPerfilOrdenes mr-2">
											'.$nombre.'
										</a>';

						}

						if($value['protesis'] != NULL){

							$trabajo = ucfirst($value['protesis']);

						} elseif($value['ortodoncia'] != NULL){

							$trabajo = ucfirst($value['ortodoncia']);

						}

						$fecha = explode(" ", $value['fecha']);

						//$fechaEntrega = $value['fechaEntrega'];

						$etapas = controladorOrden::ctrEtapasOrden($idOrd,$tipo);

						$fechaRecepcion = $etapas[0]['fecha_rec'];

						$fechaEntrega = $etapas[0]['fecha_ent'];
						
						$fechaRecepcion = strftime("%a, %d de %B", strtotime($fechaRecepcion));
						$fechaEntrega = strftime("%a, %d de %B", strtotime($fechaEntrega));

						$precioTotal = $value['precio'];

					    $tipoTrabajo = ($value['tipo'] == 'urgente') ? '<p class="avisoOrdenes flagAtrasado shadow mr-3">Urgente</p>' : '';

						/*CODIGO PARA VERIFICAR EL STATUS DE LA ORDEN*/

						//echo $etapas[0]['id_ord']."=>".$etapas[0]['estado']."=>".$etapas[0]['estadoPago']."=>".$etapas[0]['confirmacionTecnico'];
														

						//print_r($etapas);

						if( $value['entregado'] == 4 ){

							$estadoTrabajo = '<p class="bold txtRojo estadoOrdenes cancelado"><i class="fas fa-ban mr-2"></i> Cancelado</p>';

						}elseif ($etapas[0]['estado'] == 1 &&
							$etapas[0]['estadoPago'] == 1 &&
							$etapas[0]['confirmacionTecnico'] == 1){

							$estadoTrabajo = '<p class="bold txtVerde estadoOrdenes completado"><i class="fas fa-check-circle mr-2"></i> Orden completa</p>';

						} elseif( ($etapas[0]['estadoPago'] == 1) && ($etapas[0]['confirmacionTecnico'] == 0) ){ //SÍ EL PAGO ESTÁ ACREDITADO Y EL TECNICO AUN NO CONFIRMA QUE LO TERMINO, SIGNIFICA QUE ESTA EN PROCESO

							$estadoTrabajo = '<p class="text-info estadoOrdenes enProceso"><i class="fas fa-spinner fa-spin mr-2"></i> En proceso</p>';

						} elseif( ($etapas[0]['estadoPago'] == 1) && ($etapas[0]['confirmacionTecnico'] == 1) ) { //SÍ EL PAGO ESTÁ ACREDITADO Y EL TÉCNICO YA CONFIRMÓ QUE TERMINÓ LA ORDEN, SIGNIFICA QUE EL TRABAJO ESTÁ EN PROCESO DE ENTREGA

							$estadoTrabajo = '<p class="bold estadoOrdenes ausente"><i class="far fa-check-circle mr-2"></i> Entregado</p>';

						} elseif( ( ($etapas[0]['estadoPago'] == 2) || ($etapas[0]['estadoPago'] == 3) ) && ($etapas[0]['confirmacionTecnico'] == 0) ){ // SÍ EL ESTADO DEL PAGO ES APROBADO (1). SÍ EL TECNICO YA CONFIRMÓ QUE TERMINÓ EL TRABAJO Y EL ESTADO DEL TRABAJO ES TERMINADO, EL TRABAJO DEBERÍA CONSIDERARSE COMO TERMINADO O COMPLETADO.

							$estadoTrabajo = '<p class="bold txtPequeño txtAmarillo estadoOrdenes pagoPendiente"><i class="fas fa-stopwatch txtGrande mr-2"></i> Pago pendiente</p>';

						}



						/*MANEJO DE LOS FLAG EN LA TABLA DE LAS ORDENES*/

						$fechas3 = explode(" ", $etapas[0]['fecha_hist_ord']); //SEPARAMOS LA FECHA DE LA HORA

						$fecha1 = new DateTime($etapas[0]['fecha_ent']); //Fecha de entrega
						$fecha3 = new DateTime($fechas3[0]); //Fecha en que se hizo la orden (SOLO FECHA)
						$fecha2 = new DateTime(date("Y-m-d")); //Fecha actual
						$diff = $fecha1->diff($fecha2); //Diferencia entre esas dos fechas
						// will output 2 days
						$diasRestantesEntrega = $diff->days;
						
						if(count($etapas) == 0){
							$flagOrden = '<p class="avisoOrdenes flagAtrasado shadow">Cancelado</p>';
						} elseif( ($etapas[0]['visto'] == 0) && ($tipo=="tecnico") && ($etapas[0]['estadoPago'] != 4) ){

							$flagOrden = '<p class="avisoOrdenes flagNuevo shadow">¡Nuevo!</p>';
							
						} elseif( ($diasRestantesEntrega == 1) && ($fecha1 > $fecha2) && ($etapas[0]['confirmacionTecnico'] == 0) ){
							$flagOrden = '<p class="avisoOrdenes flagEntregar shadow">Entrega mañana</p>';
							
						} elseif( ($fecha1 < $fecha2) && ($etapas[0]['confirmacionTecnico'] == 0) ){
							$flagOrden = '<p class="avisoOrdenes flagAtrasado shadow">Atrasado</p>';
							
						}  elseif( ($fecha3 >= $fecha2) && ($tipo == "dentista") && ($etapas[0]['estadoPago'] != 4) ){
							$flagOrden = '<p class="avisoOrdenes flagNuevo shadow">¡Nuevo!</p>';
						}else{
							$flagOrden = '';
						}

						/*NUMERO DE DIENTES*/

						$dientes = explode(",", $value['dientes']);

						$numeroDientes = count($dientes);

						/************************************************/
						

						echo 

						'<hr class="col-xl-0 col-lg-0 col-md-0 my-1">

						<div class="row px-3 no-gutters elementoTablaOrdenes col-xl-0 col-lg-0 col-md-0" data-id="'.$idOrd.'">
							
							<div class="col-sm-8 col-8 form-inline">
								'.$fotoPerfil.'
							</div>

							<div class="col-sm-4 col-4 text-right">
								<p class="fechaEntregaOrdenes text-muted">'.$fechaEntrega.'</p>
							</div>

							<div class="col-sm-12 col-12 mt-2">
								<p class="text-muted bold">'.$trabajo.'</p>
							</div>

							<div class="col-sm-9 col-9 form-inline">
								'.$estadoTrabajo.'
								'.$tipoTrabajo.'
								'.$flagOrden.'
							</div>

							<div class="col-sm-3 col-3 text-right">
								<p class="txtVerde bold precioOrdenes">$ '.$precioTotal*$numeroDientes.'</p>
							</div>

						</div>';

					} //FIN DEL FOREACH PARA ELEMENTOS EN MOVILES

					
				} //FIN DEL ELSE EN CASO DE QUE NO EXISTAN ORDENES

				 ?>

				<table class="table text-muted table-hover col-sm-0 col-0 tablaOrdenes">

					<thead>

						<tr>
							<th scope="col-lg-3" class="txtCorto"><i class="fas fa-user mr-2"></i> Paciente</th>
							<th scope="col-lg-3" class="txtCorto"><i class="fas fa-tooth mr-2"></i> Trabajo</th>
							<th scope="col-lg-2" class="col-md-0"><i class="fas fa-calendar mr-2"></i> Recepción</th>
							<th scope="col-lg-2" class="txtCorto"><i class="fas fa-calendar-check mr-2"></i> Entrega</th>
							<th scope="col-lg-2" class="txtCorto"><i class="fas fa-dollar-sign mr-2"></i> Total</th>
							<th scope="col-lg-2" class="txtCorto"><i class="fas fa-tasks mr-2"></i> Estado</th>
						</tr>

					</thead>

					<tbody>

						<?php 

						if(!$ordenes){

							//echo '<div class="alert alert-warning">Todavía no tienes ordenes de trabajo</div>';

						} else {

							foreach ($ordenes as $key => $value) {

								$nombre = ucfirst($value['paciente']);

								$primeraLetra = substr($nombre, 0, 1);

								$idOrd = $value['id_ord'];

								if( ($value['fotoPerfil'] == "dentista.png") || ($value['fotoPerfil'] == "imgRelleno.png") ){

									$fotoPerfil = '<div class="letraPerfilOrden mr-2">
														<p>'.$primeraLetra.'</p>
													</div>
												<a href="'.$url.'ordenes?orden='.$idOrd.'">'.$nombre.'</a>';

								} else{

									if($tipo == "tecnico") { $carpeta = "dentistas"; } else { $carpeta = "tecnicos"; }

									$fotoPerfil = '<a href="'.$url.'ordenes?orden='.$idOrd.'">
													<img src="'.$url.'vistas/asset/images/'.$carpeta.'/'.$value['fotoPerfil'].'" alt="'.$nombre.'" class="img-fluid imgPerfilOrdenes mr-2">
													'.$nombre.'
												</a>';

								}

								if($value['protesis'] != NULL){

									$trabajo = ucfirst($value['protesis']);

								} elseif($value['ortodoncia'] != NULL){

									$trabajo = ucfirst($value['ortodoncia']);

								}

								$fecha = explode(" ", $value['fecha']);

								//$fechaEntrega = $value['fechaEntrega'];

								$etapas = controladorOrden::ctrEtapasOrden($idOrd,$tipo);

								$fechaRecepcion = $etapas[0]['fecha_rec'];

								$fechaEntrega = $etapas[0]['fecha_ent'];

								$fechaRecepcion = strftime("%a, %d de %B", strtotime($fechaRecepcion));
								$fechaEntrega = strftime("%a, %d de %B", strtotime($fechaEntrega));

								$precioTotal = $value['precio'];

								$tipoTrabajo = ($value['tipo'] == 'urgente') ? 'tipoTrabajoUrgente' : '';

								/*CODIGO PARA VERIFICAR EL STATUS DE LA ORDEN*/

								//echo $etapas[0]['id_ord']."=>".$etapas[0]['estado']."=>".$etapas[0]['estadoPago']."=>".$etapas[0]['confirmacionTecnico'];
																

								//print_r($etapas);

								if( $value['entregado'] == 4 ){

									$estadoTrabajo = '<p class="bold txtRojo"><i class="fas fa-ban mr-2"></i> Cancelado</p>';

								}elseif ($etapas[0]['estado'] == 1 &&
									$etapas[0]['estadoPago'] == 1 &&
									$etapas[0]['confirmacionTecnico'] == 1){

									$estadoTrabajo = '<p class="bold txtVerde"><i class="fas fa-check-circle mr-2"></i> Orden completa</p>';

								} elseif( ($etapas[0]['estadoPago'] == 1) && ($etapas[0]['confirmacionTecnico'] == 0) ){ //SÍ EL PAGO ESTÁ ACREDITADO Y EL TECNICO AUN NO CONFIRMA QUE LO TERMINO, SIGNIFICA QUE ESTA EN PROCESO

									$estadoTrabajo = '<p class="text-info"><i class="fas fa-spinner fa-spin mr-2"></i> En proceso</p>';

								} elseif( ($etapas[0]['estadoPago'] == 1) && ($etapas[0]['confirmacionTecnico'] == 1) ) { //SÍ EL PAGO ESTÁ ACREDITADO Y EL TÉCNICO YA CONFIRMÓ QUE TERMINÓ LA ORDEN, SIGNIFICA QUE EL TRABAJO ESTÁ EN PROCESO DE ENTREGA

									$estadoTrabajo = '<p class="bold"><i class="far fa-check-circle mr-2"></i> Entregado</p>';

								} elseif( ( ($etapas[0]['estadoPago'] == 2) || ($etapas[0]['estadoPago'] == 3) ) && ($etapas[0]['confirmacionTecnico'] == 0) ){ // SÍ EL ESTADO DEL PAGO ES APROBADO (1). SÍ EL TECNICO YA CONFIRMÓ QUE TERMINÓ EL TRABAJO Y EL ESTADO DEL TRABAJO ES TERMINADO, EL TRABAJO DEBERÍA CONSIDERARSE COMO TERMINADO O COMPLETADO.

									$estadoTrabajo = '<p class="bold txtPequeño txtAmarillo"><i class="fas fa-stopwatch txtGrande mr-2"></i> Pago pendiente</p>';

								}



								/*MANEJO DE LOS FLAG EN LA TABLA DE LAS ORDENES*/

								$fechas3 = explode(" ", $etapas[0]['fecha_hist_ord']); //SEPARAMOS LA FECHA DE LA HORA

								$fecha1 = new DateTime($etapas[0]['fecha_ent']); //Fecha de entrega
								$fecha3 = new DateTime($fechas3[0]); //Fecha en que se hizo la orden (SOLO FECHA)
								$fecha2 = new DateTime(date("Y-m-d")); //Fecha actual
								$diff = $fecha1->diff($fecha2); //Diferencia entre esas dos fechas
								// will output 2 days
								$diasRestantesEntrega = $diff->days;
								
								if(count($etapas) == 0){
									$flagOrden = '<p class="flagOrdenes flagAtrasado shadow">Cancelado</p>';
								} elseif( ($etapas[0]['visto'] == 0) && ($tipo=="tecnico") && ($etapas[0]['estadoPago'] != 4) ){

									$flagOrden = '<p class="flagOrdenes flagNuevo shadow">¡Nuevo!</p>';
									
								} elseif( ($diasRestantesEntrega == 1) && ($fecha1 > $fecha2) && ($etapas[0]['confirmacionTecnico'] == 0) ){
									$flagOrden = '<p class="flagOrdenes flagEntregar shadow">Entrega mañana</p>';
									
								} elseif( ($fecha1 < $fecha2) && ($etapas[0]['confirmacionTecnico'] == 0) ){
									$flagOrden = '<p class="flagOrdenes flagAtrasado shadow">Atrasado</p>';
									
								}  elseif( ($fecha3 >= $fecha2) && ($tipo == "dentista") && ($etapas[0]['estadoPago'] != 4) ){
									$flagOrden = '<p class="flagOrdenes flagNuevo shadow">¡Nuevo!</p>';
								}else{
									$flagOrden = '';
								}

								/*NUMERO DE DIENTES*/

								$dientes = explode(",", $value['dientes']);

								$numeroDientes = count($dientes);


								/************************************************/
								
								echo '<tr class="elementoTablaOrdenes '.$tipoTrabajo.'" data-id="'.$idOrd.'">
										<th class="form-inline padreFlag list-inline">
											'.$flagOrden.'
											'.$fotoPerfil.'
										</th>

										<td class="pt-4">
											'.$trabajo.'
										</td>

										<td class="pt-4 col-md-0">
											'.$fechaRecepcion.'
										</td>

										<td class="pt-4">
											<b>'.$fechaEntrega.'</b>
										</td>

										<td class="pt-4">
											$ '.$precioTotal * $numeroDientes.'
										</td>

										<td class="pt-4 txtCorto">
											'.$estadoTrabajo.'
										</td>
									</tr>';

							} //FIN DEL FOREACH PARA TABLA DE COMPUTADORAS


							///INICIO DEL FOREACH PARA LOS ELEMENTOS DE MÓVILES


							
						} //FIN DEL ELSE EN CASO DE QUE NO EXISTAN ORDENES


						?>

					</tbody>

				</table>
				
			</div>

		</div>

		<div class="tab-pane fade" id="pills-nuevo" role="tabpanel" aria-labelledby="pills-contact-tab">
			
			<h4 class="text-muted">Ordenes nuevas</h4>

			<div class="pills-nuevoContenedor"></div>

		</div>

		<!--<div class="tab-pane fade" id="pills-atrasado" role="tabpanel" aria-labelledby="pills-contact-tab">
		
			<h5 class="text-muted">Ordenes atrasadas</h5>	

			<div class="pills-atrasadoContenedor"></div>	

		</div>-->

		<div class="tab-pane fade" id="pills-completado" role="tabpanel" aria-labelledby="pills-contact-tab">
			
			<h4 class="text-muted">Ordenes finalizadas</h4>	

			<div class="pills-completadoContenedor"></div>	

		</div>

		<div class="tab-pane fade" id="pills-cancelado" role="tabpanel" aria-labelledby="pills-contact-tab">
			
			<h4 class="text-muted">Ordenes canceladas</h4>

			<div class="pills-canceladoContenedor"></div>

		</div>

		<div class="tab-pane fade" id="pills-busqueda" role="tabpanel" aria-labelledby="pills-contact-tab">
			
			<h4 class="text-muted">Resultados para: <span class="busquedaDelUsuario"></span></h4>

			<div class="pills-busquedaContenedor"></div>

		</div>

	</div>

</div>
