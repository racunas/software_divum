<?php 

$laboratorio = str_replace("-", " ", $rutas[1]);
$trabajo = str_replace("-", " ", $rutas[2]);

$datosInfo = ctrInfoproducto::obtenerDatosInfo($laboratorio,$trabajo);
$tipoTrabajo = ctrInfoproducto::tipoTrabajo($laboratorio,$trabajo);

$entregaUrgenteDoctor = ctrInfoproducto::ctrOrdenesUrgentesMaximas($_SESSION['dentista']);

$datosInfo['trabajo'] = ucfirst($datosInfo['trabajo']);

if(isset($datosInfo['material'])){
	$material = "(".$datosInfo['material'].")";
} else {
	$material = "";
}

if( isset($_SESSION['dentista']) ){

	$btnOrdenarAhora = '<button class="btn btnBuscalab btn-block btnOrdenarAhora" name="ordenarAhora">Ordenar<i class="far fa-check-circle ml-2"></i></button>';

	$direccion = controladorPerfil::ctrdireccion($_SESSION['dentista'],"dentista",NULL);

	//print_r($direccion);

	$perfil = controladorPerfil::ctrDatosPerfil($_SESSION['dentista'],"dentista");

	$direccionRecepcion = '<select name="direccionRecepcion" id="direccionRecepcion" class="form-control form-control-sm inputBorderBottom" required>
								<option value="">Selecciona tu dirección...</option>
								<option value="nueva">Agregar nueva dirección...</option>';

							if($direccion != NULL){

								foreach ($direccion as $key => $value) {

									if($perfil['direccion_predet'] == $value['id_direc']){

										$direccionRecepcion .= '<option value="'.$value['id_direc'].'" selected>'.$value['calle'].'</option>';
										
									} else {

										$direccionRecepcion .= '<option value="'.$value['id_direc'].'">'.$value['calle'].'</option>';

									}
								}

							}

	$direccionRecepcion .=		'</select>';

	$direccionEntrega = '<select name="direccionEntrega" id="direccionEntrega" class="form-control form-control-sm inputBorderBottom" required>
								<option value="">Selecciona tu dirección...</option>
								<option value="nueva">Agregar nueva dirección...</option>';

							if($direccion != NULL){

								foreach ($direccion as $key => $value) {

									if($perfil['direccion_predet'] == $value['id_direc']){

										$direccionEntrega .= '<option value="'.$value['id_direc'].'" selected>'.$value['calle'].'</option>';
										
									} else {

										$direccionEntrega .= '<option value="'.$value['id_direc'].'">'.$value['calle'].'</option>';

									}
								}

							}

	$direccionEntrega .=		'</select>';

} else {

	$btnOrdenarAhora = '<button class="btn btn-block btnBuscalab modalRegistro" name="ordenarAhora">Ordenar<i class="far fa-check-circle ml-2"></i></button>';
	
	$direccionRecepcion = '<input type="text" name="direccionRecepcion" id="direccionRecepcion" class="form-control form-control-sm" placeholder="Escribe tu dirección" required>';

	$direccionEntrega = '<input type="text" name="direccionEntrega" id="direccionEntrega" class="form-control form-control-sm" placeholder="Escribe tu dirección" required>';

}

 ?>

<div class="container mt-4">
	
	<div class="row">
		
		<div class="col-xl-0 col-lg-0 col-md-0 col-sm-0 col-0">
			
			<div id="mapa"></div>

		</div>
		
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			
			<input type="hidden" class="precioUrgente" value="<?php echo $datosInfo['precioUrgente']; ?>">
			<input type="hidden" class="tiempoUrgente" value="<?php echo $datosInfo['tiempoUrgente']; ?>">
			<input type="hidden" class="precioOrdinario" value="<?php echo $datosInfo['precio']; ?>">
			<input type="hidden" class="tiempoOrdinario" value="<?php echo $datosInfo['tiempo']; ?>">

			<form class="formularioOrden" action="<?php echo $url; ?>confirmar-orden" method="POST" enctype="multipart/form-data">

				<div class="row">
				
					<div class="col-xl-6 col-lg-6 col-md-5 col-sm-12 col-12">
						
						<div class="imagenInfoproducto">
							
							<img src="<?php echo $url ?>vistas/asset/images/producto/<?php echo $datosInfo['imgProducto'] ?>" alt="">

						</div>

					</div>

					<div class="col-xl-6 col-lg-6 col-md-7 col-sm-12 col-12 separacionMovil">

						<!--CABECERA DE PRE ORDEN-->

						<input type="hidden" name="idProducto" value="<?php echo $datosInfo['id']; ?>">
						
						<h3 class="text-muted nombreTrabajo"><?php echo $datosInfo['trabajo'] ?></h3>

						<p class="txtPequeño colorBuscalab bold">por <?php echo $datosInfo['laboratorio'] ?> </p>

						<h1 class="precioTrabajo">$ <span class="precioTipoTrabajo"><?php echo $datosInfo['precio']; ?></span> <span class="txtPequeño"><i>por unidad</i></span></h1>
						
						<div class="row py-2 text-muted datosExtra">

							<div class="col-xl-6 col-lg-6 col-md-5 col-sm-6 col-5">

								<?php 

									$calificacionesOpiniones = ctrInfoproducto::ctrCalificaciones($datosInfo['id'],$tipoTrabajo);

									if(!$calificacionesOpiniones){

										//SÍ NO EXISTEN CALIFICACIONES, MOSTRAMOS LO SIGUIENTE

										$numCalificaciones = 0;

									} else{

										$numCalificaciones = count($calificacionesOpiniones);

									}

								 ?>
								
								<i class="fas fa-star mr-1 text-info"></i> <?php echo $numCalificaciones; echo ($numCalificaciones == 1) ? ' opinión' : ' opiniones'; ?>
								
							</div>

							<div class="col-xl-6 col-lg-6 col-md-7 col-sm-6 col-7">
								
								<p class="text-muted"><i class="fas fa-clock mr-2 text-success"></i>Entrega en <span class="diasEntrega"><?php echo $datosInfo['tiempo'] ?></span> días</p>
								
							</div>

						</div>

						<div class="row mb-2">

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 text-muted text-left">
								
								<p><i class="fas fa-tooth mr-2 mt-2"></i>Tipo:</p>

							</div>

						<?php 

						if( ($datosInfo['tiempoUrgente'] != NULL) && ($datosInfo['precioUrgente'] != NULL) && ($datosInfo['disponibleUrgente'] == 1) && !$entregaUrgenteDoctor ){


						 ?>
								
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6 tipoOrden text-center">
								
								<input type="radio" id="tipoOrdenOrdinario" name="tipoOrden" value="ordinario" required checked>
								<label for="tipoOrdenOrdinario" class="etiqueta">Ordinario</label>

							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6 tipoOrden text-center">
								
								<input type="radio" id="tipoOrdenTerminado" name="tipoOrden" value="urgente" required>
								<label for="tipoOrdenTerminado" class="etiqueta">Urgente</label>

							</div>

						<?php 

						} else {
						
						 ?>
							
							<div class="col-xl-8 col-lg-8 col-md-8 tipoOrden text-center">
								
								<input type="radio" id="tipoOrdenOrdinario" name="tipoOrden" value="ordinario" required checked>
								<label for="tipoOrdenOrdinario" class="etiqueta">Ordinario</label>

							</div>

						<?php 

						}

						 ?>


						</div>	


						<!--FIN CABECERA DE PRE ORDEN-->


						<div class="capaTotal">

						<?php 

						if(isset($_SESSION['tecnico'])){

							echo 

							'<div class="capaProtectora txtBlanco text-center">
								<p><i class="fas fa-exclamation-circle mr-2"></i> Para ordenar, primero debes crear una cuenta como Dentista</p>
							</div>';

						} elseif (!isset($_SESSION['tecnico']) && !isset($_SESSION['dentista'])) {
							
							echo 

							'<div class="capaProtectora txtBlanco text-center">
								<p><i class="fas fa-exclamation-circle mr-2"></i> Para ordenar, primero debes crear una cuenta como Dentista</p>
								
								<button class="btn btnBuscalab btnRegistroPreOrden">Registrarme <i class="fas fa-user-plus ml-2"></i></button>
							</div>';

						}

						 ?>

						

						<div class="formulario text-muted shadow">

							<div class="row pt-2">

								<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-5">
									
									<p><i class="far fa-user mr-2"></i>Paciente:</p>

								</div>

								<div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-7">
									
									<input type="text" placeholder="Nombre completo" name="nombrePaciente" class="form-control form-control-sm inputBorderBottom">

								</div>

								<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-6">
										
									<p><i class="fas fa-dollar-sign mr-3"></i>Pago inicial:</p>

								</div>

								<div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-6">

									<input type="hidden" class="porcentajeInicial" value="<?php echo $datosInfo['porcentaje']; ?>">
									
									<select name="pagoInicial" id="pagoInicial" class="form-control form-control-sm inputBorderBottom" required>
										
										<?php
										$porciento = $datosInfo['porcentaje']*10;
										//$min = $porciento * 10;

										if($porciento == 10){
											
											//IMPRIMIMOS QUE ES 100% CUANDO SEA TRABAJO URGENTE O PIDA EL 100%
											echo '<option value="1" selected>$'.$datosInfo['precio'].' por unidad (100%)</option>';

										} else {

											$porcientoSelect = $porciento;
											
											while($porciento<=10){

												$precioTotal = $datosInfo['precio'];
												$precioParcial = ($precioTotal * $porciento) / 10 ;
												$porcientoTotal = $porciento*10;


												if($porciento == 10){
													echo '<option value="'.($porciento/10).'" selected>$'.$precioParcial.' por unidad ('.$porcientoTotal.'%)</option>';												
												} else {
													echo '<option value="'.($porciento/10).'">$'.$precioParcial.' por unidad ('.$porcientoTotal.'%)</option>';
												}

											

												if( (++$porciento) > 10 ){

													--$porciento;
													$porciento = $porciento + 0.5;

												}

											}

										}


										?>
									</select>

								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 my-2 text-left ">
									
									<div>Recolección:</div>

								</div>

								<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-6 my-2 text-left hidden">
									
									<select name="horaRecepcion" id="horaRecepcion" class="form-control form-control-sm" required>
										
										<option value="">Hora...</option>
										<option value="8:00:00" selected>8:00</option>
										<option value="9:00:00">9:00</option>
										<option value="10:00:00">10:00</option>
										<option value="11:00:00">11:00</option>
										<option value="12:00:00">12:00</option>
										<option value="13:00:00">13:00</option>
										<option value="14:00:00">14:00</option>
										<option value="15:00:00">15:00</option>
										<option value="16:00:00">16:00</option>
										<option value="17:00:00">17:00</option>
										<option value="18:00:00">18:00</option>

									</select>

								</div>

								<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-6 my-2 fechaRecepcion">
									
									<input type="text" placeholder="yyyy/mm/dd" name="fechaRecepcion" id="fechaRecepcion" class="form-control form-control-sm datepicker inputBorderBottom" readonly="true" style="background-color: white" required>

								</div>

								<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-6 my-2">
									
									<?php 

									echo $direccionRecepcion;

									 ?>

								</div>

								<!----------------------------------------------->
						
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 my-2 text-left">
									
									<div>Entrega:</div>

								</div>

								<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 my-2 text-left hidden">
									
									<select name="horaEntrega" id="horaEntrega" class="form-control form-control-sm" required>
										
										<option value="">Hora...</option>
										<option value="8:00:00" selected>8:00</option>
										<option value="9:00:00">9:00</option>
										<option value="10:00:00">10:00</option>
										<option value="11:00:00">11:00</option>
										<option value="12:00:00">12:00</option>
										<option value="13:00:00">13:00</option>
										<option value="14:00:00">14:00</option>
										<option value="15:00:00">15:00</option>
										<option value="16:00:00">16:00</option>
										<option value="17:00:00">17:00</option>
										<option value="18:00:00">18:00</option>

									</select>

								</div>

								<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-6 my-2 fechaEntrega">
									
									<input type="text" placeholder="yyyy/mm/dd" name="fechaEntrega" id="fechaEntrega" class="form-control form-control-sm inputBorderBottom" required readonly="true" style="background-color: white">

								</div>

								<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-6 my-2">
									
									<?php 

									echo $direccionEntrega;

									 ?>

								</div>

		
								<!--TERMINAN LOS DETALLES DE ENTREGA-->

							<?php 

								if($tipoTrabajo == "protesis"){



							 ?>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6 mt-4 radioEstado txtPequeño">
									
									<input type="radio" id="prueba" name="estadoTrabajo" value="2" required>
									<label for="prueba" class="etiqueta"><b>Prueba</b></label>

								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6 mt-4 radioEstado txtPequeño">
									
									<input type="radio" id="terminado" name="estadoTrabajo" value="1" required>
									<label for="terminado" class="etiqueta"><b>Terminado</b></label>

								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mt-4">

									<select name="colorimetria" id="colorimetria" class="form-control form-control-sm inputBorderBottom" required>

										<option value="">Colorimetria...</option>

									<?php 

										$colorimetria = ctrInfoproducto::ctrColorimetria(NULL);//SE ENVIA NULL PORQUE QUIERO QUE ME TRAIGA TODAS LAS COLORIMETRIAS EN UN ARRAY

										foreach ($colorimetria as $key => $value) {
											echo '<option value="'.$value['id_col'].'">'.$value['nomb'].'</option>';
										}

									 ?>
										
									</select>

								</div>

								<?php 

								} else if($tipoTrabajo == "ortodoncia"){

								 ?>

								 <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 mt-4 radioEstado text-center">
									
									<input type="radio" id="prueba" name="estadoTrabajo" value="2" required>
									<label for="prueba" class="etiqueta prueba"><b>Prueba</b></label>

								</div>

								<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 mt-4 radioEstado text-center">
									
									<input type="radio" id="terminado" name="estadoTrabajo" value="1" required>
									<label for="terminado" class="etiqueta terminado"><b>Terminado</b></label>

								</div>

							<?php } ?>
							
							</div>

							<hr class="my-2">

							<div class="row">

								<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-lg-2 text-center">
									
									<button class="btn btn-block btnOutlineBuscalab btnGuardarOrden" name="guardarOrden" disabled>Guardar<i class="fas fa-box-open ml-2"></i></button>

								</div>

								<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-lg-2 text-center separacionMovil">

									<?php 
										echo $btnOrdenarAhora;
									 ?>

								</div>

							</div>

						</div>

						</div> <!--FIN DE LA CAPA PROTECTORA-->

					</div> 

				</div>


				<div class="detallesOrden text-muted mb-5">
					
					<!--<hr class="my-3">

					<textarea name="descripcion" id="descripcion" cols="10" rows="4" class="form-control form-control-sm" placeholder="Descripción y detalles sobre la orden que vas a generar" required></textarea>
					
					<br>

					<input type="file" name="imagenes[]" id="fotosOrden" multiple accept="image/*" class="form-control form-control-sm">-->

					<div class="modal fade" id="modalOdontograma" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

						<div class="modal-dialog modal-lg" role="document">

							<div class="modal-content">

								<div class="modal-header fondoBuscalab text-center">

									<h3 class="text-center"><i class="fas fa-teeth-open mr-2"></i> Odontograma</h3>

									<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white">
									 	<span aria-hidden="true">&times;</span>
									</button>

								</div>

								<div class="modal-body">
									
									<div class="odontogramaGeneral <?php echo ($tipoTrabajo == 'ortodoncia') ? 'hidden' : ''; ?>">

										<p class="text-muted text-center">Selecciona los dientes a realizar:</p>

										<div id="odontograma" class="row">

											<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

												<ul class="dientesOdonto diez">

													<li class="diente" data-num="18"><p>18</p></li>
													<li class="diente" data-num="17"><p>17</p></li>
													<li class="diente" data-num="16"><p>16</p></li>
													<li class="diente" data-num="15"><p>15</p></li>
													<li class="diente" data-num="14"><p>14</p></li>
													<li class="diente" data-num="13"><p>13</p></li>
													<li class="diente" data-num="12"><p>12</p></li>
													<li class="diente" data-num="11"><p>11</p></li>

												</ul>

											</div>

											<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

												<ul class="dientesOdonto veinte">

													<li class="diente" data-num="21"><p>21</p></li>
													<li class="diente" data-num="22"><p>22</p></li>
													<li class="diente" data-num="23"><p>23</p></li>
													<li class="diente" data-num="24"><p>24</p></li>
													<li class="diente" data-num="25"><p>25</p></li>
													<li class="diente" data-num="26"><p>26</p></li>
													<li class="diente" data-num="27"><p>27</p></li>
													<li class="diente" data-num="28"><p>28</p></li>

												</ul>

											</div>

											<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 pt-3">

												<ul class="dientesOdonto cuarenta">

													<li class="diente" data-num="48"><p>48</p></li>
													<li class="diente" data-num="47"><p>47</p></li>
													<li class="diente" data-num="46"><p>46</p></li>
													<li class="diente" data-num="44"><p>44</p></li>
													<li class="diente" data-num="43"><p>43</p></li>
													<li class="diente" data-num="45"><p>45</p></li>
													<li class="diente" data-num="42"><p>42</p></li>
													<li class="diente" data-num="41"><p>41</p></li>

												</ul>

											</div>

											<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 pt-3">

												<ul class="dientesOdonto treinta">

													<li class="diente" data-num="31"><p>31</p></li>
													<li class="diente" data-num="32"><p>32</p></li>
													<li class="diente" data-num="33"><p>33</p></li>
													<li class="diente" data-num="34"><p>34</p></li>
													<li class="diente" data-num="35"><p>35</p></li>
													<li class="diente" data-num="36"><p>36</p></li>
													<li class="diente" data-num="37"><p>37</p></li>
													<li class="diente" data-num="38"><p>38</p></li>

												</ul>

											</div>

										</div>
									
										<h2 class="text-center pt-2">
											<span class="txtGrande">Total:</span> 
											<span class="precioTotal">$ <span class="precioTipoTrabajo"><?php echo $datosInfo['precio']; ?></span> </span> 
										</h2>

										<hr class="my-2">

									</div>

									<?php 
									echo ($tipoTrabajo == 'ortodoncia') ? '<h4 class="text-center">Escribe una descripción sobre tu orden:</h4>' : '';
									 ?>

									<div class="pt-2">

										<textarea name="descripcion" id="descripcion" cols="10" rows="4" class="form-control form-control-sm" placeholder="(OBLIGATORIO) Escribe aquí una descripción detallada sobre la orden que vas a generar" required></textarea>
										
									</div>
									
									<br>

									<div id="subidaImagenPreOrden" action="" class="text-center mt-4" method="post" enctype="multipart/form-data">
						
										<label for="nuevaOrdenImg" class="colorBuscalab subirImagenes">Subir imágenes (opcional) <i class="fas fa-images ml-2"></i><i class="fas fa-arrow-up ml-2"></i></label>
										<input type="file" name="nuevaOrdenImg[]" id="nuevaOrdenImg" accept="image/*" multiple>
										<input type="hidden" name="url" value="<?php echo $url; ?>">

									</div>

									<p class="text-danger text-center pt-2">** Sí seleccionaste una foto por error, tienes que volver a seleccionar las imágenes <i class="fas fa-arrow-up ml-2"></i></p>

									<div id="respuestaSubidaImagen" class="row"></div>

								</div>

								<div class="modal-footer">
									
									<button class="btn btnBuscalab btnOrdenarAhora" name="ordenarAhora">Aceptar <i class="fas fa-check ml-2"></i></button>

								</div>

							</div>

						</div>

					</div>

				</div>

				<input type="hidden" class="dientesSeleccionados" name="dientesSeleccionados">
				<input type="hidden" class="cantidadTrabajos" name="cantidadTrabajos">

			</form><!--FIN DEL FORMULARIO-->
			
		</div>

	</div>


</div>



<!--CALIFICACIONES DEL TRABAJO-->

<div class="container pb-5 my-3">
	
	<h4 class="text-muted bold">Calificaciones de los dentistas:</h4>
	
	<hr class="my-2">
	
	
	<?php 

	$calificaciones = ctrInfoproducto::ctrCalificaciones($datosInfo['id'],$tipoTrabajo);

	if(!$calificaciones){

		//SÍ NO EXISTEN CALIFICACIONES, MOSTRAMOS LO SIGUIENTE

		echo '<div class="alert alert-warning text-center">No existen opiniones</div>';

	} else{

		foreach ($calificaciones as $key => $value) {

			$totalCalif = bcdiv(( $value['precio'] + $value['calidad'] + $value['tiempo'] ), '3', 1);

			$fecha = explode(" ", $value['fecha']);
				
			echo

			'<div class="opinion row text-muted">
		
				<div class="col-xl-4 col-lg-4 col-md-5 col-sm-5 col-12">
					
					<div class="datosGenerales">

					
						<img src="'.$url.'vistas/asset/images/dentistas/'.$value['fotoPerfil'].'" alt="">

						<div class="ml-3 txtCorto">						
							<p class="colorBuscalab">'.$fecha[0].'</p>
							<p class="txtCorto">'.ucfirst($value['nombreDentista']).'</p>
						</div>

					
					</div>

				</div>

				<div class="col-xl-8 col-lg-8 col-md-7 col-sm-7 col-12">
					
					<p>Calificación: <i>'.$totalCalif.'</i> de 5 <i class="fas fa-star ml-2 colorBuscalab"></i></p>

					<p>'.$value['opinion'].'</p>

				</div>

			</div>';

		}

	}

	 ?>

</div>

<!------------------------------------------------------------------------->

<div class="modal fade" id="modalAgregarDireccion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">

			<div class="modal-header fondoBuscalab text-center">

				<h3 class="text-center"><i class="fas fa-map-marker-alt mr-2"></i> Agrega una nueva dirección</h3>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white">
				 	<span aria-hidden="true">&times;</span>
				</button>

			</div>

			<div class="modal-body">
				
				<div class="moduloAgregarDireccion mb-3">

					<input type="text" id="codigoPostal" autocomplete="off" placeholder="Introduce tu código postal" class="form-control" onkeypress="return valida(event)" maxlength="5">

					<div class="row hidden datosDireccionBuscada">

						<div class="col-lg-12 my-2">
							
							<input type="text" id="calle" placeholder="Ingresa el nombre de tu calle con # ext. e int." class="form-control">

						</div>

						<div class="col-lg-12 my-2">
							
							<select name="colonia" id="colonia" class="form-control inputBorderBottom"></select>

						</div>

						<div class="col-lg-6 col-md-6 my-2">
							
							<input type="text" id="estado" readonly class="form-control" placeholder="Estado">

						</div>

						<div class="col-lg-6 col-md-6 my-2">
							
							<input type="text" id="municipio" readonly class="form-control" placeholder="Municipio / Delegación">

						</div>

					</div>
					

				</div>

			</div>

			<div class="modal-footer">
				
				<button class="btn btnBuscalab btnAgregarDireccion">Guardar <i class="fas fa-check ml-2"></i></button>

			</div>

		</div>

	</div>

</div>

<!------------------------------------------------------------------------>

<input type="hidden" id="tipoTrabajo" value="<?php echo $tipoTrabajo; ?>">

<script>
	
	var tiempoEntrega = <?php echo (strlen($datosInfo['tiempo']) >= 1) ? $datosInfo['tiempo'] : "0"; ?>;
	var tiempoEntregaUrgente = <?php echo (strlen($datosInfo['tiempoUrgente']) >= 1) ? $datosInfo['tiempoUrgente'] : "0"; ?>;
	var precioTotal = <?php echo (strlen($datosInfo['precio']) >= 1) ? $datosInfo['precio'] : "0"; ?>;
	var precioTotalUrgente = <?php echo (strlen($datosInfo['precioUrgente']) >= 1) ? $datosInfo['precioUrgente'] : "0"; ?>;

</script>

<?php 

//CODIGO PARA GEOLOCALIZAR AL LABORATORIO Y MOSTRARLO EN GOOGLE MAPS

$idRep = $datosInfo['id_rep'];

$infoGeolocalizacion = controladorPerfil::ctrSepomex($idRep);

$direccionCompleta = $datosInfo['direccion']." ".$infoGeolocalizacion['asentamiento']." ".$infoGeolocalizacion['municipio']." ".$infoGeolocalizacion['cp'];

ctrInfoproducto::geolocalizar($direccionCompleta,$datosInfo['laboratorio']);

 ?>