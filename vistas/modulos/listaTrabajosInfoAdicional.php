<?php 



 ?>

 <div class="container-fluid my-5 text-muted">
 	
 	<div class="container separacionFooter">

 		<div class="infoLaboratorio">
 			
			<h1>Requerimos más información</h1>
			<p>Completa la información de tu Laboratorio para comenzar a trabajar con los dentistas de la clínica DIVUM</p>

			<hr class="my-3">
			
			<h2 class="text-center py-3">¿Cuál es el nombre de tu Laboratorio?</h2>

	 		<div class="row pb-3">
				
				<div class="col-lg-2 col-xl-2 col-md-0 col-sm-0 col-0"></div>
				<div class="col-lg-8 col-xl-8 col-md-12 col-sm-12 col-12 justify-content-center">
					
					<input type="text" class="form-control nombreLaboratorio" placeholder="Escribe aquí...">
					
				</div>
				<div class="col-lg-2 col-xl-2 col-md-0 col-sm-0 col-0"></div>

				
	 		</div>

	 		<h2 class="text-center py-3">¿Dónde se ubica tu Laboratorio?</h2>

			<div class="row pb-3 mx-lg-5">
				
				<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
					<input type="text" class="form-control cpLaboratorio" id="codigoPostal" placeholder="Tu código postal" onkeypress="return valida(event)" maxlength="5">
				</div>
				<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 separacionMovil justify-content-center">
					
					<select class="form-control coloniaLaboratorio" id="colonia" disabled="">
						<option value="">Primero ingresa tu código postal</option>
					</select>
					
				</div>

				<div class="col-xl-12 col-lg-12 mt-3">
					<input type="text" class="form-control calleLaboratorio" placeholder="Calle y número de tu laboratorio">
				</div>

				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-3">
										
					<input type="text" value="" id="estado" readonly class="form-control" placeholder="Estado">

				</div>

				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-3">
					
					<input type="text" value="" id="municipio" readonly class="form-control" placeholder="Municipio / Delegación">

				</div>
				
	 		</div>
			
			<div class="text-center my-4">
	 			<button class="btn btnBuscalab align-content-center txtEnorme btnInfoAdicional">Siguiente <i class="fas fa-arrow-right ml-2"></i></button>
			</div>

		</div> <!--FIN INFO DE LABORATORIO-->

 	</div>

 </div>