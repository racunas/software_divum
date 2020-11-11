<div class="container-fluid separacionFooter text-muted my-5">
	
	<div class="container separacionFooter">
		
		<div class="queTrabajosHace text-center">

			<h1 class="text-muted">Selecciona el tipo de trabajo que realizas</h1>
			
			<div class="row pt-3">
				
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
					
					<form class="trabajoProtesis" method="POST" action="lista-trabajos" id="formTipoProtesis">
						
						<i class="fas fa-tooth"></i>

						<p>Prótesis</p>

						<input type="hidden" name="tipo" value="protesis">

					</form>

				</div>

				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">

					<form class="trabajoOrtodoncia" method="POST" action="lista-trabajos" id="formTipoOrtodoncia">
						
						<i class="fas fa-teeth-open"></i>

						<p>Ortodoncia</p>

						<input type="hidden" name="tipo" value="ortodoncia">

					</form>

				</div>

			</div>

		</div>

	</div>

</div>