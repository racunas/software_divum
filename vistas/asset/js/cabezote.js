/*$(".bloqueFotoPerfil").on("mouseover",".fotoPerfil",function(){

	$(".fotoPerfil .primeraLetraNombre").removeClass("colorBuscalab");
	$(".fotoPerfil .primeraLetraNombre").addClass("txtBlanco");

	
})

$(".bloqueFotoPerfil").on("mouseleave",".fotoPerfil",function(){

	$(".fotoPerfil .primeraLetraNombre").removeClass("txtBlanco");
	$(".fotoPerfil .primeraLetraNombre").addClass("colorBuscalab");

	
})
*/

$(document).ready(function(){
	var primeraLetra = $(".primeraLetra").val();
	$(".fotoPerfil .primeraLetraNombre").html(primeraLetra);

})


$(function () {

	if($( window ).width() >= 991){
		
		var $popoverInbox = $('.bloqueFotoPerfil').popover({
	        placement: 'bottom',
	        trigger:'manual',
	        template: '<div class="popover-all"><div class="popover-content text-center txtGrande"><a class="noDecoration txtNegro" href="'+url+'perfil"><i class="fas fa-user mr-2"></i> Mi perfil</a></div><div class="popover-content text-center txtGrande cerrarSesion"><i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión</div></div>',
	        content:'hola'
	    });

		var timerPopover, popover_parent;

	   	function hidePopover(elem) {
	  		$(elem).popover('hide');
		}

	   	$('.bloqueFotoPerfil').hover(
			function() {
				var self = this;
				clearTimeout(timerPopover);
				//$('.popover-all').hide(); //Hide any open popovers on other elements.
				popover_parent = self;
				$(self).popover('show');
			},
			function() {
				var self = this;
				timerPopover = setTimeout(function(){hidePopover(self);},300);
			}
		);

		$(document).on({
			mouseenter: function() {
				clearTimeout(timerPopover);
			},
			mouseleave: function() {
				var self = this;
				timerPopover = setTimeout(function(){hidePopover(popover_parent);},300);
			}
		}, '.popover-all');

	}
	



});


//*CODIGO PARA RESTABLECER LA CONTRASEÑA*//

$(".olvidastePass").click(function(){

	console.log("diste clic en olvidar la contraseña");

	$("#modalIniciarSesion").modal("hide");

	$("#modalRestablecerContraseña").modal("show");

})

$("#restablecerContraseña").click(function(){

	$(".respuestaFormularioRestablecer").html('');
	$(".respuestaFormularioRestablecer").addClass("hidden");

	var correo = $(".correoRestablecer").val();

	if(correo.length >= 1){

		var datos = {
			"correo":correo
		}


		$.ajax({

			data:datos,
			url:url+"vistas/asset/ajax/verificarExistenciaCorreo.ajax.php",
			type:"POST",
			context:this,
			beforeSend: function(){

				$(this).prop("disabled",true);
				$(this).html('<i class="fas fa-undo fa-spin text-center"></i>');

			},
			success: function(respuesta){

				console.log(respuesta);

				if(respuesta == "no existe"){

					$(this).prop("disabled",false);
					$(this).html('Restablecer');

					$(".respuestaFormularioRestablecer").html('<p class="txtRojo">No está registrado en nuestro sistema <i class="fas fa-ban ml-2"></i></p>');
					$(".respuestaFormularioRestablecer").removeClass("hidden");
				
				} else {

					$(this).addClass("hidden");
					$(".cerrarRestablecerContraseña").removeClass("hidden");

					$(".formularioRestablecer").addClass("hidden"); //Ocultamos el formulario donde solicitamos el correo electrónico

					$(".respuestaFormularioRestablecer").html('<h4 class="text-muted">Te hemos enviado un correo electrónico para que restablezcas tu contraseña <i class="fas fa-check-circle ml-2 text-success"></i></h4>');
					$(".respuestaFormularioRestablecer").removeClass("hidden");

				}

			}

		});

	} else {

		$(".respuestaFormularioRestablecer").html('<p class="txtRojo">Escribe un correo electrónico válido <i class="fas fa-ban ml-2"></i></p>');
		$(".respuestaFormularioRestablecer").removeClass("hidden");

	}


	///TERMINAR LA CONSULTA DE QUE EXISTA EL CORREO EN AJAX Y SI EXISTE, AVISAR QUE SE MANDO EL CORREO ELECTRONICO

})

$('#modalRestablecerContraseña').on('hidden.bs.modal', function (e) {

	$("#restablecerContraseña").removeClass("hidden");
	$("#restablecerContraseña").prop("disabled",false);
	$("#restablecerContraseña").html('Restablecer');
	$(".cerrarRestablecerContraseña").addClass("hidden");

	$(".formularioRestablecer").removeClass("hidden");
	$(".respuestaFormularioRestablecer").addClass("hidden");

})


//CONTROL DE notificacion

function marcarnotificacionLeidas(){
	$.ajax({
		type:"POST",
		url:url+"vistas/asset/ajax/marcarNotificacionesLeidas.ajax.php",
		success:function(respuesta){
			console.log(respuesta);
		}
	})
}

function mostrarnotificacion(){

	if( $(".dropdownnotificacion").hasClass("hidden") ){ //SIGNIFICA QUE ESTA OCULTO, Y HAY QUE MOSTRARLO
		$(".dropdownnotificacion").removeClass("hidden");

		$.ajax({
			type:"POST",
			data:{"url":url},
			url:url+"vistas/asset/ajax/obtenerNotificaciones.ajax.php",
			beforeSend:function(){
				$(".contenidonotificacion").html('<div class="text-center"><img width="120px;" src="'+url+'vistas/asset/images/cargando.gif" alt="cargando..." /><br /><p class="bold text-muted">Cargando...</p></div>');
			},
			success: function(respuesta){
				
				$(".campananotificacion").html("");
				marcarnotificacionLeidas();

				setTimeout(function(){

					$(".contenidonotificacion").html(respuesta);
					
				}, 500);

			}
		})

	} else {
		$(".dropdownnotificacion").addClass("hidden");
	}

}

$(".campananotificacion").click(mostrarnotificacion);