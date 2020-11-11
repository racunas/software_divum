<div class="modal fade" id="modalRegistro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">

			<div class="modal-header fondoBuscalab text-center">

				<h5 class="modal-title" id="exampleModalLabel"><b>Regístrate en Buscalab</b></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white">
				 	<span aria-hidden="true">&times;</span>
				</button>

			</div>

			<div class="modal-body">

				<form action="<?php echo $url; ?>" class="row my-3 text-muted formRegistro" method="POST" onsubmit="return registroUsuario()">
					
					<div class="col-lg-12">
						
						<span><i class="fas fa-envelope"></i>&nbsp;&nbsp; Correo electrónico:</span>

						<input type="email" name="correoElectronico" id="correoElectronico" class="form-control form-control-sm inputBorderBottom" placeholder="Escribe tu correo..." autocomplete="false" required>

					</div>

					<div class="col-lg-6 pt-3">
						
						<span><i class="fas fa-key"></i>&nbsp;&nbsp; Contraseña:</span>

						<input type="password" name="password" id="password" class="form-control form-control-sm inputBorderBottom" placeholder="Escribe tu contraseña..." required autocomplete="new-password">

					</div>

					<div class="col-lg-6 pt-3">
						
						<span><i class="fas fa-key"></i>&nbsp;&nbsp; Confirmar contraseña:</span>

						<input type="password" name="password2" id="password2" class="form-control form-control-sm inputBorderBottom" placeholder="Escribe de nuevo tu contraseña..." required autocomplete="new-password">

					</div>

					<div class="col-lg-12 pt-3">
						
						<span><i class="fas fa-user"></i>&nbsp;&nbsp; Nombre:</span>

						<input type="text" name="nombreCompleto" id="nombreCompleto" class="form-control form-control-sm inputBorderBottom" placeholder="Escribe tu nombre completo..." required>

					</div>

					<div class="col-lg-12 pt-3">
						
						<span><i class="fas fa-phone"></i>&nbsp;&nbsp; Teléfono (opcional):</span>

						<input type="text" name="telefono" id="telefono" class="form-control form-control-sm inputBorderBottom" onkeypress="return valida(event)" maxlength="12">

					</div>

					<div class="col-lg-6 text-center radioQueEs pt-lg-4 separacionMovil">
						
						<input type="radio" name="queEs" id="tecnico" value="tecnico" required>
						<label for="tecnico" class="etiqueta">Soy Técnico / Laboratorio &nbsp;&nbsp;<i class="fas fa-teeth"></i></label>


					</div>

					<div class="col-lg-6 text-center radioQueEs pt-lg-4 separacionMovil">
						
						<input type="radio" name="queEs" id="dentista" value="dentista" required>
						<label for="dentista" class="etiqueta">Soy Dentista / Clínica &nbsp;&nbsp;<i class="fas fa-tooth"></i></label>

					</div>

					<div class="col-lg-12 pt-4">
						
						<button class="btn btn-block btnOutlineBuscalab" type="submit" name="submitRegistro">Registrarme <i class="fas fa-arrow-circle-right ml-2"></i></button>

						<p class="pt-4 txtPequeño">Al dar clic en <i>Registrarme</i> aceptas las Condiciones de uso y políticas de privacidad estipuladas aquí: <a href="https://www.iubenda.com/privacy-policy/15533357" class="iubenda-white iubenda-embed pl-2" title="Condiciones de uso y políticas de privacidad">Política de Privacidad</a><script type="text/javascript">(function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src="https://cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);</script></p>

					</div>

					<div class="col-lg-12 my-3">
						
						<!--<a href="#" class="colorBuscalab preguntasFrecuentes"><b>Preguntas frecuentes</b></a>-->

						<div class="">
							
							<span>¿Ya tienes una cuenta? <a href="#" class="colorBuscalab enlaceIniciarSesion"><b>Iniciar sesión</b></a></span>

						</div>

					</div>

				</form>

			</div>

		</div>

	</div>

</div>