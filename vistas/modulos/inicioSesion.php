<?php 

$loginURL = $helper->getLoginUrl($redirectURL, $fbPermissions);

 ?>

 <div class="modal fade" id="modalIniciarSesion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">

			<div class="modal-header fondoBuscalab text-center">

				<h5 class="modal-title" id="exampleModalLabel"><b>Inicia sesión en Buscalab</b></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white">
				 	<span aria-hidden="true">&times;</span>
				</button>

			</div>

			<div class="modal-body">
				
				<!--<a href="<?php echo htmlspecialchars($loginURL); ?>"><button class="btn btn-block" style="background-color: #3b5998; color:white"><i class="fab fa-facebook-f mr-2"></i> Iniciar sesión con Facebook</button></a>

				<hr>-->

				<form class="row my-2 text-muted" method="POST">

					<div class="col-lg-12">

						<span> <i class="fas fa-envelope"></i> Correo electrónico</span>
						
						<input type="text" id="iniciarSesionCorreo" name="correo" class="form-control form-control-sm inputBorderBottom" autocomplete="false" placeholder="Escribe tu correo...">
						
					</div>

					<div class="col-lg-12 my-3">
						
						<span> <i class="fas fa-key"></i> Contraseña</span>
						
						<input type="password" id="iniciarSesionPass" name="password" class="form-control form-control-sm inputBorderBottom" autocomplete="false" placeholder="Escribe tu contraseña...">

					</div>

					<div class="col-lg-12 pt-2">
						
						<button class="btn btn-block btnOutlineBuscalab text-center" name="submit"> Iniciar sesión con tu correo <i class="fas fa-arrow-circle-right ml-2"></i></button>

					</div>

					<div class="col-lg-0 hidden pt-2">
						
						<button class="btn btn-block btnOutlineBuscalab" name="submitDentista"><i class="fas fa-tooth"></i>&nbsp;&nbsp;&nbsp;Iniciar como dentista</button>

					</div>

					<div class="col-lg-12 text-left">
						
						<div class="my-3">
							
							<a href="#" class="olvidastePass">¿Olvidaste tu contraseña?</a>

						</div>

						<div class="">
							
							<span>¿No tienes cuenta? <a href="#" class="colorBuscalab enlaceRegistrarme"><b>Registrarme</b></a></span>

						</div>

					</div>

				</form>

			</div>

		</div>

	</div>

</div>