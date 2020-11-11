<?php 	

//echo controladorListaTrabajos::obtenerPerfilDisponible("12"); //SE VERIFICA SI TIENE COMPLETO SUS DATOS DE LABORATORIO O NO

 ?>

<div class="container-fluid">
 	
	<div class="container py-4">
		
		<div class="row">
		
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			
				<div class="text-muted text-left">

					<h2 class="colorBuscalab">¡Último paso!</h2>
					
					<p class="txtGrande">Establece el tiempo de entrega y el porcentaje de adelanto general de tus trabajos</p>

				</div>

				<hr class="my-2">	

				<div class="datosGenerales">

					<form id="formDatosGenerales">

						<input type="hidden" class="tipoTrabajo" value="<?php echo $_POST['tipoTrabajo']; ?>">

						<div class="text-left colorBuscalab txtGrande bold">
							
							<i class="fas fa-tooth"></i> &nbsp;&nbsp;&nbsp;Trabajos Ordinarios

						</div>
					
						<div class="row text-left ml-3 mt-2 text-muted txtGrande">
							
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
								
								<label for="prueba">Prueba <i class="fas fa-info-circle infoPrueba"></i></label>

								<div class="separacionMediana">
									<select name="prueba" id="prueba" class="form-control formcontrol-sm inputBorderBottom">
										<?php 

										for($i = 1; $i <= 30; $i++){

											echo '<option value="'.$i.'">'.$i.' días</option>';

										}

										 ?>

									</select>
								</div>

							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 separacionMovil">
								
								<label for="terminado">Terminado <i class="fas fa-info-circle infoTerminado"></i></label>

								<div class="separacionMediana">
								
									<select name="terminado" id="terminado" class="form-control formcontrol-sm inputBorderBottom">
										<?php 

										for($i = 1; $i <= 30; $i++){

											echo '<option value="'.$i.'">'.$i.' días</option>';

										}

										 ?>

									</select>
									
								</div>

							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 separacionMovil">
								
								<label for="porcientoAdelanto">Previo <i class="fas fa-info-circle infoPorcientoAdelanto"></i></label>

								<div class="separacionMediana">
								
									<select name="porcientoAdelanto" id="porcientoAdelanto" class="form-control formcontrol-sm inputBorderBottom">
										
										<option value="0">0%</option>
										<option value="0.25">25%</option>
										<option value="0.5" selected="true">50%</option>
										<option value="0.75">75%</option>
										<option value="1">100%</option>

									</select>
								
								</div>

							</div>

						</div> <!--FIN DEL ROW DEL FORMULARIO-->

						
						<div class="urgente text-left">
							
							<input type="checkbox" id="checkUrgente" value="siUrgente" name="checkUrgente">
							<label for="checkUrgente">Clic aquí si quieres ofrecer trabajos urgentes&nbsp;&nbsp;&nbsp;<i class="icon-urgente font-weight-bold"></i></label>

						</div>
						
						<div class="trabajosUrgentes hidden">
								
							<div class="row text-left text-muted txtGrande ml-3">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 separacionMovil">
									
									<label for="terminadoUrgente">Terminado <i class="fas fa-info-circle infoTerminadoUrgente"></i></label>

									<div class="separacionMediana">
									
										<select name="terminadoUrgente" id="terminadoUrgente" class="form-control formcontrol-sm inputBorderBottom" disabled>
											<?php 

											for($i = 1; $i <= 15; $i++){

												echo '<option value="'.$i.'">'.$i.' días</option>';

											}

											 ?>

										</select>
										
									</div>

								</div>

								<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 separacionMovil">
									
									<label for="porcientoAdelantoUrgente">Porcentaje del incremento de tu precio <i class="fas fa-info-circle infoPorcientoAdelantoUrgente"></i></label>

									<div class="separacionMediana">
						
										<select name="porcientoAdelantoUrgente" id="porcientoAdelantoUrgente" class="form-control formcontrol-sm inputBorderBottom form" disabled>
											
											<option value="0.25">25%</option>
											<option value="0.5" selected="true">50%</option>
											<option value="0.75">75%</option>
											<option value="1">100%</option>

										</select>
							
									</div>

								</div>

							</div> <!--FIN DEL ROW DEL FORMULARIO-->

						</div>

					</form>


					<hr class="my-3">

					<div class="text-right col-sm-0 col-0">
						
						<button class="btn btn-sm btnBuscalab txtEnorme btnListaTrabajos3" id="btnListaTrabajos3">Finalizar <i class="fas fa-check-circle ml-2"></i></button>

					</div>


				</div>

			</div>
			
		</div>

	</div>

</div>

<div class="fixed-bottom col-xl-0 col-lg-0 col-md-0" id="botonesMovilesListaTrabajos">

	<div class="row">

		<div class="col-sm-12 col-12 confirmar">
			<button class="btn btn-sm btnBuscalab txtEnorme btnListaTrabajos3" id="btnListaTrabajos3">Finalizar <i class="fas fa-check-circle ml-2"></i></button>
		</div>

	</div>

</div>