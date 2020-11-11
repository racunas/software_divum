<?php 

$tipo = $_POST['tipo']; //ORTODONCIA O PROTESIS

//$listaBase1 = array("1","2","3","4","5","6","7","8","9","10","11","13","22","32","33","35","40","41","44");

$listaBase1 = controladorListaTrabajos::ctrLista($tipo);
$listaBase = array();

foreach ($listaBase1 as $key => $value) {

	if($tipo == "protesis"){
		$listaBase[] = $value['id_pro'];
	} else {
		$listaBase[] = $value['id_ort_prod'];
	}

	
}


$objListaTrabajos = new controladorListaTrabajos();

$idLab = $_SESSION['tecnico'];

/*SE VERIFICA SI YA HA AGREGADO ALGUN TRABAJO EL TECNICO, Y SE CREA UNA NUEVA LISTA*/

$listaPersonal = array();

foreach ($listaBase as $key => $value) {
	
	if(!$objListaTrabajos -> verificarExistenciaTrabajo($tipo,$value,$idLab)){

		$listaPersonal[] = $value;

	}

}

/**********************************************************************************/

 ?>

<div class="container-fluid">
	
	<div class="container py-5">
		
		<div class="row">

			<div class="col-lg-0"></div>
			
			<div class="col-lg-12 text-muted">
				
				<div class="text-left">

					<h3 class="">Es momento de subir tu lista de precios. Selecciona los trabajos que <span class="colorBuscalab"><u>SI</u></span> realizas.</h3>

					<p class="bold">Existen <?php echo count($listaPersonal); ?> trabajos que puedes agregar</p>
					
				</div>

				<hr class="my-2">

				<div class="lista">

					<form method="POST" action="lista-trabajos" id="formListaTrabajos">
						
						<div class="row">
							
							<?php 

								foreach ($listaPersonal as $key => $value) {
									$trabajo = $objListaTrabajos -> obtenerTrabajo($tipo,$value);
									$trabajo['nomb'] = ucfirst($trabajo['nomb']);

									$idTrabajo = ($tipo=="ortodoncia") ? $trabajo['id_ort_prod'] : $trabajo['id_pro'];

									echo '<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 no-gutters">
											
											<input type="checkbox" id="'.$idTrabajo.'" value="'.$idTrabajo.'" name="lista[]">
											<label for="'.$idTrabajo.'">'.$trabajo['nomb'].'</label>

										</div>';
								}

							 ?>
							
						</div>

						<input type="hidden" name="tipoTrabajo" value="<?php echo $tipo; ?>">

					</form>

					<div class="text-right my-3 col-md-0 col-sm-0 col-0">
						<button class="btn btn-sm btnBuscalab txtEnorme btnListaTrabajos1" id="btnListaTrabajos1" name="submit" type="submit"><?php echo (count($listaPersonal)==0) ? 'Regresar <i class="fas fa-undo ml-2"></i>' : 'Siguiente <i class="fas fa-arrow-right ml-2"></i>'; ?></button>
					</div>

				
				</div>


			</div>

			<div class="col-lg-0"></div>

		</div>

	</div>

</div>

<div class="fixed-bottom col-xl-0 col-lg-0" id="botonesMovilesListaTrabajos">

	<div class="row">
		
		<div class="col-md-6 col-sm-6 col-6 regresar">
						
			<a href="javascript:history.back(-1);"><button class="btn btnOutlineBuscalab">Regresar <i class="fas fa-undo-alt ml-2"></i></button></a>

		</div>

		<div class="col-md-6 col-sm-6 col-6 confirmar">
			<button class="btn btn-sm btnBuscalab txtEnorme btnListaTrabajos1" id="btnListaTrabajos1" name="submit" type="submit" <?php echo (count($listaPersonal)==0) ? 'disabled' : ''; ?>>Siguiente <i class="fas fa-arrow-right ml-2"></i></button>
		</div>

	</div>

</div>