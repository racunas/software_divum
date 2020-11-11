function registroUsuario(){

	/**
	 *
	 * VERIFICAR QUE EL NOMBRE NO TIENE CARACTERES ESPECIALAES
	 *
	 */

	 $(".alertaNombre").remove();
	 $(".alertaCorreo").remove();
	 $(".alertaPassword").remove();
	

	var nombre = $("#nombreCompleto").val();

	if(nombre != ""){

		var expresion = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]*$/;

		if(!expresion.test(nombre)){

			$(".alertaNombre").remove();

			$("#nombreCompleto").parent().before('<div class="col-lg-12 text-center pt-3 alertaNombre"><div class="alert alert-warning mb-0"><strong>ALERTA:</strong> No se permiten caracteres especiales en el nombre</div></div>');

			return false;

		}

	} else {

		$("#nombreCompleto").addClass("inputError");

		return false;

	}

	/**
	 *
	 * VERIFICAR QUE EL CORREO ESTÉ BIEN ESCRITO
	 *
	 */
	
	var correo = $("#correoElectronico").val();

	if(correo != ""){

		var expresion = /^\w+([\.-]?\w)*@\w+([\.-]?\w+)*(\.\w{2,4})*$/;

		if(!expresion.test(correo)){

			$(".alertaCorreo").remove();

			$("#correoElectronico").parent().before('<div class="col-lg-12 text-center pt-3 alertaCorreo"><div class="alert alert-warning mb-0"><strong>ALERTA:</strong> Escribe correctamente el correo electrónico</div></div>');

			return false;

		} /*else {

			var tipo = $("input[name=queEs]:checked").val();

			var datos = {
				"correo":correo,
				"tipo":tipo
			}

			var existe = false;

			$.ajax({
				data:datos,
				type:"POST",
				utl:"vistas/asset/ajax/usuario.ajax.php",
				success: function(respuesta){

					if(respuesta == 1){

						$(".alertaCorreo").remove();

						$("#correoElectronico").parent().before('<div class="col-lg-12 text-center pt-3 alertaCorreo"><div class="alert alert-warning mb-0"><strong>ALERTA:</strong> El correo electrónico ya está registrado</div></div>');

						existe = true;
						
					}


				}
			});

			if(existe){

				return false;

			}

		}*/

	} else {

		$("#correoElectronico").addClass("inputError");

		return false;

	}

	/**
	 *
	 * VERIFICAR QUE LAS DOS CONTRASEÑAS SEAN IGUALES
	 *
	 */
	
	var password1 = $("#password").val();

	var password2 = $("#password2").val();

	if((password1 != "") && (password2 != "")){

		if(password1.length < 8 || password2.length < 8){

			$("#password").addClass('inputError');

			$("#password2").addClass('inputError');

			$(".alertaPassword").remove();

			$("#password").parent().before('<div class="col-lg-12 text-center pt-3 alertaPassword"><div class="alert alert-warning mb-0"><strong>ALERTA:</strong> Las contraseñas deben tener al menos 8 caracteres</div></div>');

			return false;

		} else if(password1 != password2){

			$("#password").addClass('inputError');

			$("#password2").addClass('inputError');

			$(".alertaPassword").remove();

			$("#password").parent().before('<div class="col-lg-12 text-center pt-3 alertaPassword"><div class="alert alert-warning mb-0"><strong>ALERTA:</strong> Las contraseñas deben ser iguales</div></div>');

			return false;
			
		}


	} else {

		$("#password").addClass('inputError');

		$("#password2").addClass('inputError');

		return false;

	}


	return true;
}

/*ALGORITMO PARA VERIFICAR LA EXISTENCIA DEL USUARIO POR AJAX*/

/*SCRIPT CUANDO LE DA CLIC A INICIAR SESION DENTRO DEL MODAL DE REGISTRO*/

$(".enlaceIniciarSesion").click(function(){

	$("#modalRegistro").modal('hide');

	$("#modalIniciarSesion").modal('show');
	$("#modalRegistro").on("hidden.bs.modal",function(e){

		
	});


});

/*SCRIPT CUANDO LE DA CLIC A REGISTRARSE DENTRO DEL MODAL DE INICIO DE SESION*/

$(".enlaceRegistrarme").click(function(){

	$("#modalIniciarSesion").modal('hide');

	$("#modalRegistro").modal('show');
	$("#modalIniciarSesion").on("hidden.bs.modal", function(e){

		
	});


});