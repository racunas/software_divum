$(document).ready(function (){

	var paginaActual = $(".paginaActual").val();

	if(paginaActual != "#"){

		$("#item"+paginaActual).addClass("active");

	}


});

function editarInfoProducto(){

	var infoTrabajo = $(this).parent().attr("id");
	var trabajo = infoTrabajo.split("_");
	var idTrabajo = trabajo[0];
	var queEs = trabajo[1];

	var datos = {
		"queEs":queEs,
		"idTrabajo":idTrabajo
	}

	$.ajax({
		"url": url+"vistas/asset/ajax/informacionTrabajo.ajax.php",
		"data":datos,
		"type": "POST",
		success: function(respuesta){

			var datos = JSON.parse(respuesta);

			$("#modalPorcientoAdelanto option").attr("selected",false); //DESELECCIONAMOS TODOS LOS OPTION ANTES
			$("#modalPrueba option").attr("selected",false);
			$("#modalTerminado option").attr("selected",false);

			$("#modalPrecio").val(datos['precio']);
			$("#modalPorcientoAdelanto option[value='"+datos['porcentaje']+"']").attr("selected",true);
			$("#modalPrueba option[value='"+datos['diasEntrega']+"']").attr("selected",true);
			$("#modalTerminado option[value='"+datos['diasTerminado']+"']").attr("selected",true);
			$(".nombreTrabajo").html(datos['nomb']);

			$(".tipoTrabajo").val(queEs);
			$(".idTrabajo").val(idTrabajo);

			if(datos['disponibleUrgente'] == 1){

				$("#checkUrgente").prop("checked",true);
				$(".trabajosUrgentes").removeClass("hidden");

				//CONECTAMOS LOS DATOS DE URGENTE

				$("#modalTerminadoUrgente option[value='"+datos['diasEntregaUrgente']+"']").attr("selected",true);
				$("#modalPrecioUrgente").val(datos['precioUrgente']);

			} else {

				$("#checkUrgente").prop("checked",false);
				$(".trabajosUrgentes").addClass("hidden");
				$("#modalTerminadoUrgente option").attr("selected",false);
				$("#modalPrecioUrgente").val("");

			}

			$("#modalEditarInfoTrabajo").modal("show");

		}
	});
	
}

function guardarInfoProducto(){

	var errores = 0;

	var precio = $("#modalPrecio").val();
	var porcentaje = $("#modalPorcientoAdelanto option:selected").val();
	var diasPrueba = $("#modalPrueba option:selected").val();
	var diasTerminado = $("#modalTerminado option:selected").val();
	var tipoTrabajo = $(".tipoTrabajo").val();
	var idTrabajo = $(".idTrabajo").val();

	if(	precio == "" || 
		precio < 50 ){

		errores = errores + 1;

	}

	if( $("#checkUrgente").prop("checked") ){

		if( $("#modalTerminadoUrgente option:selected").val() != "" &&
			$("#modalPrecioUrgente").val() != "" &&
			$("#modalPrecioUrgente").val() >= 50){

			var precioUrgente = $("#modalPrecioUrgente").val();
			var diasTerminadoUrgente = $("#modalTerminadoUrgente option:selected").val();
			
		} else {

			errores = errores + 1;

		}

		
	} else {

		var precioUrgente = null;
		var diasTerminadoUrgente = null;

	}

	if(errores == 0){

		var datos = {
			"precio":precio,
			"porcientoAdelanto":porcentaje,
			"diasPrueba":diasPrueba,
			"diasTerminado":diasTerminado,
			"precioUrgente":precioUrgente,
			"diasTerminadoUrgente":diasTerminadoUrgente,
			"tipoTrabajo":tipoTrabajo,
			"idTrabajo":idTrabajo
		}

		console.log(datos);

		$.ajax({

			"url":url+"vistas/asset/ajax/actualizarInformacionTrabajo.ajax.php",
			"type":"POST",
			"data":datos,
			success: function(respuesta){

				console.log(respuesta);

				if(respuesta){

					swal({

						type: "success",
						title: "Actualizado",
						text: "Información actualizada correctamente",
						confirmButtonText: "Ok",
						confirmButtonColor: "#9ac76d",
						timer:800,
						showConfirmButton:false

					}).then((result) => {

						if(result.value){

							$("#modalEditarInfoTrabajo").modal("hide");

							//window.location.reload();

						}

					});

					$("#modalEditarInfoTrabajo").modal("hide");
				
				} else {

					//console.log("algo salio mal");

					swal({
					  type: 'error',
					  title: 'Ocurrió un error interno',
					  text: 'Por favor, inténtalo de nuevo',
					  confirmButtonText: 'Ok',
					  confirmButtonColor: '#9ac76d'
					}).then( (result) => {

						if(result.value){

							$("#modalEditarInfoTrabajo").modal("hide");

						}

					});

				}

			}

		});

	} else {

		//SIGNIFICA QUE HAY UN ERROR

		swal({
		  type: 'error',
		  title: 'Error en los datos',
		  text: 'Por favor, verifica que todos los datos sean correctos',
		  confirmButtonText: 'Ok',
		  confirmButtonColor: '#9ac76d'
		});

	}


}

$("#pills-tabTrabajos").on("click",".btn_Editar",editarInfoProducto);

//$(".btn_Editar").click(editarInfoProducto);

$(".btnGuardarProductoModal").click(guardarInfoProducto);


//CODIGO PARA FILTRAR LOS TRABAJOS DE LA LISTA EL TECNICO

function listaTrabajos(filtro,contenedor){

	var datos = {
		"filtro":filtro
	}

	//console.log(contenedor);

	$.ajax({
		data:datos,
		type:"POST",
		url:url+"vistas/asset/ajax/listaTrabajosFiltro.ajax.php",
		beforeSend: function(){
			$("."+contenedor).html('<div class="text-center"><img width="120px;" src="'+url+'vistas/asset/images/cargando.gif" alt="cargando..." /><br /><p class="bold text-muted">Cargando...</p></div>');
		},
		success: function(respuesta){

			//console.log(respuesta);

			setTimeout(function(){

				$("."+contenedor).html('');
				$("."+contenedor).html(respuesta);
				
			}, 500);
		}
	})

}

$("#pills-trabajosTotales").click(function(){

	console.log("hola");

	listaTrabajos("total","pills-totalContenedor");

});

$("#pills-trabajoOrdinario").click(function(){

	listaTrabajos("ordinario","pills-ordinarioContenedor");

});

$("#pills-trabajoUrgente").click(function(){

	listaTrabajos("urgente","pills-urgenteContenedor");

});

$("#pills-trabajoNoDisponible").click(function(){

	listaTrabajos("noDisponible","pills-noDisponibleContenedor");

});


///////// CÓDIGO PARA ELIMINAR DEL BUSCADOR UN TRABAJO DE LABORATORIO /////////////

$("#pills-tabTrabajos").on("click",".btnEliminarTrabajo", function(){

	var filtroTrabajo = $(this).parent().attr("data-filtro");
	var infoTrabajo = $(this).parent().attr("id");

	var trabajoLista = $(this).parent().parent().parent();

	var trabajo = infoTrabajo.split("_");

	var idTrabajo =  trabajo[0];
	var queEs = trabajo[1];

	var titleAlerta = "Eliminar del buscador";
	var textAlerta = "Ya no se podrá encontrar y ordenar en Buscalab";
	var titleAlerta2 = "Trabajo eliminado";
	var textAlerta2 = "Y ano recibirás mas ordenes de éste trabajo, hasta que lo vuelvas a habilitar";
	var textButton = "Si, eliminar";

	if(filtroTrabajo == "noDisponible"){

		titleAlerta = "Subir trabajo al buscador";
		textAlerta = "El trabajo volverá a estar disponible en Buscalab y comenzar a recibir ordenes";
		titleAlerta2 = "Trabajo activado";
		textAlerta2 = "Ya se encuentra disponible a partir de ahora";
		textButton = "Si, volver a subir";
	}

	swal({
		type:"info",
		title:titleAlerta,
		text:textAlerta,
		confirmButtonText: textButton,
		confirmButtonColor: '#9ac76d',
		cancelButtonText:'Cancelar',
		cancelButtonColor: '#7D7D7D',
		showCancelButton: true
	}).then( (result) => {

		if(result.value){ //SÍ EL USUARIO DA CLIC EN Si, eliminar, ELIMINAMOS EL TRABAJO DEPENDIENDO EL FILTRO

			var datos = {
				"idTrabajo":idTrabajo,
				"queEs":queEs,
				"filtroTrabajo":filtroTrabajo
			}

			console.log(datos);

			$.ajax({
				type:"POST",
				url:url+"vistas/asset/ajax/eliminarTrabajoLista.ajax.php",
				data:datos,
				context:this,
				success:function(respuesta){

					if(respuesta){

						trabajoLista.html('');

						swal({
						  type: 'success',
						  title: titleAlerta2,
						  text: textAlerta2,
						  confirmButtonText: 'Ok',
						  confirmButtonColor: '#9ac76d'
						});

					} else {

						swal({
						  type: 'error',
						  title: 'Inténtalo de nuevo más tarde',
						  text: 'Hubo un error interno',
						  confirmButtonText: 'Ok',
						  confirmButtonColor: '#9ac76d'
						});

					}

				}
			})

		}

	})

})


/*SCRIPT PARA LA BUSQUEDA DE TRABAJOS INTERNA*/

$('#buscarTrabajo').each(function() {
	var elem = $(this);

	// Save current value of element
	elem.data('oldVal', elem.val());

	// Look for changes in the value
	elem.bind("propertychange change click keyup input paste", function(event){

		// If value has changed...
		if (elem.data('oldVal') != elem.val()) {
			// Updated stored value
			elem.data('oldVal', elem.val());

			//// DO action 
			var busqueda = $(this).val().trim();

			//busqueda = busqueda.replace(/[áéíóúÁÉÍÓÚ ]/g, "-");

			var expression = /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]*$/;

			if(!expression.test(busqueda)){

				$(this).val("");

			} else {
				
				//$(".pills-busquedaContenedor").html("Estas buscando la palabra: <b>"+busqueda+"</b>");

				$("#pills-busquedaTrabajo").tab('show');
				//colocar la funcion para buscar en la BD

				var datos = {
					"busquedaTrabajo":busqueda,
					"url":url
				}

				console.log(busqueda);

				$.ajax({
					type:"POST",
					url:url+"vistas/asset/ajax/buscarTrabajo.ajax.php",
					data:datos,
					beforeSend:function(){

						$(".pills-busquedaTrabajoContenedor").html('<div class="text-center"><img width="120px;" src="'+url+'vistas/asset/images/cargando.gif" alt="cargando..." /><br /><p class="bold text-muted">Cargando...</p></div>');

					},
					success:function(respuesta){

						//console.log(respuesta);

						$(".pills-busquedaTrabajoContenedor").html(respuesta);
						$(".paginacion").addClass("hidden");

					}
				})

				
			}

			//// end action
		}
	});
});