<?php  

$idUsuario = $_SESSION['dentista'];
$ordenesBox = controladorBox::ctrOrdenesBox($idUsuario);
	
//print_r($ordenesBox);

?>

<div class="container py-4 separacionFooter">
	

	<div class="row">

		<?php 

		if(!$ordenesBox){ //SÍ NO EXISTE NINGUNA ORDEN; MOSTRAMOS UN AVISO

		?>

			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-center">

				<h1><i class="fas fa-shopping-cart colorBuscalab"></i></h1>
			
				<h2 class="colorBuscalab bold">UPSS... NO HAY ORDENES GUARDADAS</h2>

				<p class="text-muted pt-2">Busca, encuentra y ordena a tu Laboratorio dental ideal</p>

				<button class="btn btn-secondary txtEnorme mt-3 btnVerLaboratorios">Ver laboratorios</button>

			</div>

		<?php

		} else { //SÍ EXISTEN 1 ORDEN O MAS, LO MOSTRAMOS

		 ?>
		
			<div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 col-12">
				
				<h3 class="text-muted bold pb-3">Ordenes pendientes</h3>

				<?php 

				$precioTotal = 0;

				foreach ($ordenesBox as $key => $value) {

					$nombreTrabajo = ($value['protesis'] != NULL) ? ucfirst($value['protesis']) : ucfirst($value['ortodoncia']);
					$nombrePaciente = ($value['paciente'] != NULL) ? ucfirst($value['paciente']) : 'SIN NOMBRE';
					$precio = ($value['precioProtesis'] != NULL) ? $value['precioProtesis'] : $value['precioOrtodoncia'];

					$precioTotal = $precio + $precioTotal; //ES LA SUMA DE TODOS LOS PRECIOS

					$precioPorcentaje = $precio*$value['porcentajePagar'];
					$precioRestante = $precio - ($precio * $value['porcentajePagar']);
					
					echo 

					'<div class="elementoBox" data-id="'.$value['id_box'].'">
				
						<div class="row text-muted">
							
							<div class="col-xl-3 col-lg-4 col-md-4 col-sm-4 col-4 espacioImagen">
								
								<img src="'.$url.'vistas/asset/images/tecnicos/'.$value['imagen'].'" alt="imagen" class="img-fluid imagenOrdenBox">

							</div>

							<div class="col-xl-9 col-lg-8 col-md-8 col-sm-8 col-8 noPadding">
								
								<h3 class="nombreTrabajo">'.$nombreTrabajo.'</h3>

								<!--<div class="nombreLaboratorioBox my-1">
									por:
									<span class="colorBuscalab">Técnico Dental Online</span>
								</div>-->
								
								<div class="form-inline txtPequeño">
									
									<h4 class="previo">Previo: <span class="text-dark">$ '.$precioPorcentaje.'</span></h4>
									<p class="float-right ml-4 pt-3"> <span class="colorBuscalab">Restante:</span> $ '.$precioRestante.'</p>
								</div>

								<div class="py-2 paciente">
									
									<p><b><i class="far fa-user mr-1"></i> Paciente:</b> &nbsp; '.$nombrePaciente.'</p>

								</div>

								<div class="row no-gutters">
									
									<div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">
										
										<button class="btn btn-outline-danger btnEliminarOrdenCarrito" data-id="'.$value['id_box'].'"><i class="fas fa-trash-alt mx-2"></i> <span class="col-0">Borrar</span></button>
										
									</div>

									<div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">
										
										<button class="btn btn-outline-info btnEditarOrdenCarrito" data-id="'.$value['id_box'].'"><i class="far fa-edit mx-2"></i> <span class="col-0">Editar</span></button>

									</div>

									<div class="col-xl-5 col-lg-6 col-md-6 col-sm-4 col-6">

										<form action="'.$url.'confirmar-orden" class="formularioGenerarOrden" method="POST">
										
											<input type="hidden" name="idBox" value="'.$value['id_box'].'">

											<button class="btn btnBuscalab btnGenerarOrden"><i class="fas fa-check-circle mr-2"></i><span class="col-0">Generar orden</span> <span class="col-xl-0 col-lg-0 col-md-0 col-sm-0">Confirmar</span></button>

										</form>
										
									</div>

								</div>

							</div>

						</div>

					</div>

					<hr class="separadorBox" data-id="'.$value['id_box'].'">';

				}

				?>

			</div>

			<!--RESUMEN DE LAS ORDENES-->

			<div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-12">
					
				<div class="resumenOrdenes text-muted">
					
					<p class="text-dark">Resumen de tus ordenes</p>

					<hr class="my-1 no-gutters">

					<div class="text-left">
						Subtotal (<?php echo count($ordenesBox); ?>)
						<span class="float-right txtRojo bold">$ <?php echo $precioTotal; ?></span>
					</div>

					<hr class="my-1 no-gutters">

					<div class="text-left">
						Comisiones
						<span class="float-right txtRojo bold">$ <?php echo ( count($ordenesBox)*50 ) ?></span>
					</div>

					<hr class="my-1 no-gutters">

					<div class="text-left">
						
						<a href="#">Aplicar cupón</a>
						
					</div>

					<hr class="my-1 no-gutters">

					<div class="text-left txtEnorme bold">
						TOTAL:
						<span class="float-right txtRojo">$ <?php echo $precioTotal + (count($ordenesBox) * 50); ?></span>
					</div>

					<!--<button class="btn btn-block mt-3 btn-danger bold py-2">MANDAR TODOS LOS TRABAJOS</button>-->

				</div>

			</div>
			
			<!--ORDENES EN LA BOX-->


		<?php 

		}

		?>

	</div>



</div>


<div class="modal fade" id="modalEditarOrden" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">

			<div class="modal-header fondoBuscalab text-center">

				<h5 class="modal-title" id="exampleModalLabel"><span class="nombreTrabajoModal"></span> de: <b><span class="nombrePacienteModal">Ricardo acuña</span></b></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white">
				 	<span aria-hidden="true">&times;</span>
				</button>

			</div>

			<div class="modal-body text-muted">

				<input type="hidden" class="porcentajeInicial">

				<div class="cargandoOrden"></div>

				<div class="datosGeneralesOrden hidden">

					<button class="btn btn-block btnOutlineBuscalab btnMostrarOdontogramaEditarOrden mb-3"><i class="fas fa-teeth-open mr-2"></i> Odontograma y detalles <i class="fas fa-arrow-right ml-2"></i></button>

					<div class="row">					

						<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-6">
									
							<p><i class="far fa-user mr-2"></i>Paciente:</p>

						</div>

						<div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-6">
							
							<input type="text" placeholder="Nombre completo" name="nombrePaciente" class="form-control form-control-sm inputBorderBottom nombrePacienteOrden">

						</div>

						<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-6">
								
							<p><i class="fas fa-dollar-sign mr-3"></i>Pago inicial:</p>

						</div>

						<div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-6">
							
							<select name="pagoInicial" id="pagoInicial" class="form-control form-control-sm inputBorderBottom pagoInicialOrden" required>

							</select>

						</div>


					</div>

					<div class="tipoOrdenBox row">

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-3 text-muted text-left">
								
							<p><i class="fas fa-tooth mr-2 mt-2"></i>Tipo:</p>

						</div>
						
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col tipoOrden text-center">
								
							<input type="radio" id="tipoOrdenOrdinario" name="tipoOrdenModal" value="ordinario" required>
							<label for="tipoOrdenOrdinario" class="etiqueta">Ordinario</label>

						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col tipoOrden text-center">
							
							<input type="radio" class="" id="tipoOrdenUrgente" name="tipoOrdenModal" value="urgente" required>
							<label for="tipoOrdenUrgente" class="etiqueta">Urgente</label>

						</div>

					</div>

					<div class="row">

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 my-2 text-left ">
									
							<div>Recolección:</div>

						</div>

						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-6 my-2 fechaRecepcion">
							
							<input type="text" placeholder="yyyy/mm/dd" name="fechaRecepcionOrden" class="form-control form-control-sm datepicker inputBorderBottom fechaRecepcionOrden" readonly="true" style="background-color: white" required>

						</div>

						<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-6 my-2">
									
							<select name="direccionRecepcion" id="direccionRecepcion" class="form-control form-control-sm inputBorderBottom direccionRecepcionOrden" required>

								<?php 

								$direccion = controladorPerfil::ctrdireccion($_SESSION['dentista'],"dentista",NULL);

								//print_r($direccion);

								$perfil = controladorPerfil::ctrDatosPerfil($_SESSION['dentista'],"dentista");

								if($direccion != NULL){

									foreach ($direccion as $key => $value) {

										if($perfil['direccion_predet'] == $value['id_direc']){

											echo '<option value="'.$value['id_direc'].'" selected>'.$value['calle'].'</option>';
											
										} else {

											echo '<option value="'.$value['id_direc'].'">'.$value['calle'].'</option>';

										}
									}

								}

								 ?>

							</select>

						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 my-2 text-left">
									
							<div>Entrega:</div>

						</div>

						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-6 my-2 fechaEntrega">
							
							<input type="text" placeholder="yyyy/mm/dd" name="fechaEntregaOrden" class="form-control form-control-sm inputBorderBottom fechaEntregaOrden" required readonly="true" style="background-color: white">

						</div>

						<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-6 my-2">

							<select name="direccionEntrega" id="direccionEntrega" class="form-control form-control-sm inputBorderBottom direccionEntregaOrden" required>
								
								<?php 

								$direccion = controladorPerfil::ctrdireccion($_SESSION['dentista'],"dentista",NULL);

								//print_r($direccion);

								$perfil = controladorPerfil::ctrDatosPerfil($_SESSION['dentista'],"dentista");

								if($direccion != NULL){

									foreach ($direccion as $key => $value) {

										if($perfil['direccion_predet'] == $value['id_direc']){

											echo '<option value="'.$value['id_direc'].'" selected>'.$value['calle'].'</option>';
											
										} else {

											echo '<option value="'.$value['id_direc'].'">'.$value['calle'].'</option>';

										}
									}

								}
								
								 ?>

							</select>

						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6 mt-4 radioEstado txtPequeño">
									
							<input type="radio" id="prueba" name="estadoTrabajoOrden" value="2" required>
							<label for="prueba" class="etiqueta"><b>Prueba</b></label>

						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6 mt-4 radioEstado txtPequeño">
							
							<input type="radio" id="terminado" name="estadoTrabajoOrden" value="1" required>
							<label for="terminado" class="etiqueta"><b>Terminado</b></label>

						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mt-4">

							<select name="colorimetria" id="colorimetria" class="form-control form-control-sm inputBorderBottom colorimetriaOrden" required>

							<?php 

								$colorimetria = ctrInfoproducto::ctrColorimetria(NULL);//SE ENVIA NULL PORQUE QUIERO QUE ME TRAIGA TODAS LAS COLORIMETRIAS EN UN ARRAY

								foreach ($colorimetria as $key => $value) {
									echo '<option value="'.$value['id_col'].'">'.$value['nomb'].'</option>';
								}

							 ?>
								
							</select>

						</div>


					</div>

				</div>

				<div class="odontogramaOrden hidden">

					<div class="regresarADetallesGenerales colorBuscalab">
						<p><i class="fas fa-arrow-left mr-2"></i> <b class="text-muted">Regresar</b></p>
					</div>

					<div class="odontogramaGeneral"> <!--OCULTAR EL ODONTOGRAMA EN ORDENES DE ORTODONCIA-->

					<p class="text-muted text-center">Selecciona los dientes a realizar:</p>

					<div id="odontograma" class="row">

						<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

							<ul class="dientesOdontoOrden diez">

								<li class="dienteOrden" data-num="18"><p>18</p></li>
								<li class="dienteOrden" data-num="17"><p>17</p></li>
								<li class="dienteOrden" data-num="16"><p>16</p></li>
								<li class="dienteOrden" data-num="15"><p>15</p></li>
								<li class="dienteOrden" data-num="14"><p>14</p></li>
								<li class="dienteOrden" data-num="13"><p>13</p></li>
								<li class="dienteOrden" data-num="12"><p>12</p></li>
								<li class="dienteOrden" data-num="11"><p>11</p></li>

							</ul>

						</div>

						<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

							<ul class="dientesOdontoOrden veinte">

								<li class="dienteOrden" data-num="21"><p>21</p></li>
								<li class="dienteOrden" data-num="22"><p>22</p></li>
								<li class="dienteOrden" data-num="23"><p>23</p></li>
								<li class="dienteOrden" data-num="24"><p>24</p></li>
								<li class="dienteOrden" data-num="25"><p>25</p></li>
								<li class="dienteOrden" data-num="26"><p>26</p></li>
								<li class="dienteOrden" data-num="27"><p>27</p></li>
								<li class="dienteOrden" data-num="28"><p>28</p></li>

							</ul>

						</div>

						<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 pt-3">

							<ul class="dientesOdontoOrden cuarenta">

								<li class="dienteOrden" data-num="48"><p>48</p></li>
								<li class="dienteOrden" data-num="47"><p>47</p></li>
								<li class="dienteOrden" data-num="46"><p>46</p></li>
								<li class="dienteOrden" data-num="44"><p>44</p></li>
								<li class="dienteOrden" data-num="43"><p>43</p></li>
								<li class="dienteOrden" data-num="45"><p>45</p></li>
								<li class="dienteOrden" data-num="42"><p>42</p></li>
								<li class="dienteOrden" data-num="41"><p>41</p></li>

							</ul>

						</div>

						<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 pt-3">

							<ul class="dientesOdontoOrden treinta">

								<li class="dienteOrden" data-num="32"><p>32</p></li>
								<li class="dienteOrden" data-num="33"><p>33</p></li>
								<li class="dienteOrden" data-num="34"><p>34</p></li>
								<li class="dienteOrden" data-num="31"><p>31</p></li>
								<li class="dienteOrden" data-num="35"><p>35</p></li>
								<li class="dienteOrden" data-num="36"><p>36</p></li>
								<li class="dienteOrden" data-num="37"><p>37</p></li>
								<li class="dienteOrden" data-num="38"><p>38</p></li>

							</ul>

						</div>

					</div>
				
					<h2 class="text-center pt-2">
						<span class="txtGrande">Total:</span> 
						<span class="precioTotal"></span> 
					</h2>

					<hr class="my-2">

				</div>

				
				<h2 class="text-center text-muted">Escribe una descripción sobre tu orden:</h2>
				 

				<div class="pt-2">

					<textarea name="descripcion" id="descripcion" cols="10" rows="4" class="form-control form-control-sm descripcionOrden" placeholder="(OBLIGATORIO) Escribe aquí una descripción detallada sobre la orden que vas a generar" required></textarea>
					
				</div>
				
				<br>

				<form id="subidaImagenPreOrden" action="" class="text-center mt-4" method="post" enctype="multipart/form-data">
	
					<label for="nuevaOrdenImg" class="colorBuscalab subirImagenes">Subir imágenes (sustituirá las actuales) <i class="fas fa-images ml-2"></i><i class="fas fa-arrow-up ml-2"></i></label>
					<input type="file" name="nuevaOrdenImg[]" id="nuevaOrdenImg" accept="image/*" multiple>
					<input type="hidden" name="url" value="<?php echo $url; ?>">

				</form>

				<div id="respuestaSubidaImagen" class="row">
					<!--<div class="col-lg-4">
						<img src="http://localhost/buscalabFinal/vistas/asset/images/ordenes/09230425ya-nos-exhibiste1.jpg" alt="09230425ya-nos-exhibiste1.jpg" class="previsualizacionImagenOrden">
						<button class="btn btn-danger btnBorrarImagenOrden btn-sm">Borrar <i class="fas fa-minus-circle ml-2"></i></button>					
					</div>-->
				</div>

				</div>

			</div>


			<div class="modal-footer">

				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
				<button class="btn btnBuscalab btnGuardarEdicionOrdenCarrito">Aceptar y guardar <i class="fas fa-check ml-2"></i></button>

			</div>

		</div>

	</div>

</div>

<script>
	
	var tiempoEntrega = 0;
	var tiempoEntregaUrgente = 0;
	var precioTotal = 0;
	var precioTotalUrgente = 0;

</script>