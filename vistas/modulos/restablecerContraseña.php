<div class="container my-5 separacionFooter">
	
	<h4 class="text-muted">Cambiar contraseña de mi cuenta <i class="fas fa-unlock-alt ml-2"></i></h4>

	<div class="row pt-3 text-muted">
		
		<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 form-inlin">
			
			<p><i class="fas fa-key mr-2"></i>Nueva contraseña:</p>

			<input type="password" class="form-control nuevaContraseña" maxlength="64">

		</div>

		<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 separacionMovil form-inlin">
			
			<p><i class="fas fa-key mr-2"></i>Confirmar contraseña:</p>

			<input type="password" class="form-control confirmarNuevaContraseña" maxlength="64">

		</div>

	</div>

	<div class="float-right pt-4">
		
		<button class="btn btnBuscalab bold btnCambiarNuevaContraseña">Cambiar contraseña<i class="fas fa-check-circle ml-2"></i></button>
		
	</div>

</div>

<input type="hidden" class="key" value="<?php echo $_GET['key'];?>" >