<?php 

$listaSeleccionada = $_POST['lista'];
$tipo = $_POST['tipoTrabajo'];

$objListaTrabajos = new controladorListaTrabajos();

 ?>


 <div class="container-fluid">
 	
	<div class="container py-4">
		
		<div class="row">
		
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				
				<h3 class="text-left text-muted">
					Ahora, agrega los precios correspondientes de cada uno de los trabajos que seleccionaste
				</h3>

				<hr class="my-3">

				<div class="listaPrecios">
					
					<div class="row">
						
						<div class="col-xl-9 col-lg-9 col-md-8 col-sm-8 col-0 text-left colorBuscalab txtIcono">

							<span>
								<i class="fas fa-tooth txtIcono"></i> &nbsp;Trabajos dentales
							</span>



						</div>

						<div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-0 text-center colorBuscalab txtIcono">
							
							<i class="fas fa-dollar-sign txtIcono"></i> &nbsp;Precio

						</div>

					</div>

					<hr class="my-3 col-0">
					
					<form action="lista-trabajos" id="formListaPrecios" method="POST">

						<div class="row listaTrabajosPrecios">

							<?php 

							foreach ($listaSeleccionada as $key => $value) {
								$trabajo = $objListaTrabajos -> obtenerTrabajo($tipo,$value);

								$trabajo['nomb'] = ucfirst($trabajo['nomb']);

								$idTrabajo = ($tipo=="ortodoncia") ? $trabajo['id_ort_prod'] : $trabajo['id_pro'];

								echo '<div class="col-xl-9 col-lg-9 col-md-8 col-sm-8 col-12 text-muted">
								
										<p class="mt-2">'.$trabajo['nomb'].'</p>

									</div>

									<div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-12 mt-2">
										
										<div class="input-group mb-3 input-group-sm">
										  <div class="input-group-prepend">
										    <span class="input-group-text">$</span>
										  </div>
										  <input type="text" class="form-control form-control-sm inputBorderBottom precioInput" aria-label="Precio" name="precio[]" id="'.$idTrabajo.'" onkeypress="return valida(event);" maxlength="5">
										  <input type="hidden" name="listaSeleccionada[]" value="'.$idTrabajo.'">
										</div>

									</div>';

							}


							 ?>
							

						</div>

						<input type="hidden" name="tipoTrabajo" value="<?php echo $_POST['tipoTrabajo']; ?>">

					</form>
					
					<hr class="my-2 col-0">

					<div class="text-right col-sm-0 col-0">
						
						<button class="btn btn-sm btnBuscalab txtEnorme btnListaTrabajos2" id="btnListaTrabajos2" type="submit">Siguiente <i class="fas fa-arrow-right ml-2"></i></button>

					</div>

				</div>

			</div>


		</div>

	</div>

 </div>

<div class="fixed-bottom col-xl-0 col-lg-0 col-md-0" id="botonesMovilesListaTrabajos">

	<div class="row">
		
		<div class="col-md-6 col-sm-6 col-6 regresar">
						
			<a href="javascript:history.back(-1);"><button class="btn btnOutlineBuscalab">Regresar <i class="fas fa-undo-alt ml-2"></i></button></a>

		</div>

		<div class="col-md-6 col-sm-6 col-6 confirmar">
			<button class="btn btn-sm btnBuscalab txtEnorme btnListaTrabajos2" id="btnListaTrabajos2" type="submit">Siguiente <i class="fas fa-arrow-right ml-2"></i></button>
		</div>

	</div>

</div>