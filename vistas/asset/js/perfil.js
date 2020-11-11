function tiene_letras(texto){
	var letras="abcdefghyjklmnñopqrstuvwxyz";
	texto = texto.toLowerCase();
	for(i=0; i<texto.length; i++){
	  if (letras.indexOf(texto.charAt(i),0)!=-1){
	     return 1;
	  }
	}
	return 0;
}

function agregarDireccion(){

	if( $(".moduloAgregarDireccion").hasClass("hidden") ){ //Sí está oculto el módulo, lo mostramos

		$(".moduloAgregarDireccion").removeClass("hidden");

		$(".nuevaDireccion").addClass("hidden");

		$("#btnGuardarCambiosPerfil").addClass("hidden");

	} else { //Sí no está oculto el modulo, lo ocultamos

		$(".moduloAgregarDireccion").addClass("hidden");

		$(".nuevaDireccion").removeClass("hidden");

		$("#btnGuardarCambiosPerfil").removeClass("hidden");

	}

	/*swal.mixin({
	  input: 'text',
	  confirmButtonText: 'Continuar &rarr;',
	  cancelButtonText: 'Cancelar',
	  confirmButtonColor: '#9ac76d',
	  showCancelButton: true,
	  progressSteps: ['1', '2']
	}).queue([
	  {
	    title: 'Direccion',
	    html: 'Escribe la dirección completa sin Código Postal, se recomienda sacarla de <a href="https://www.google.com/maps/" target="_blank" class="enlaceDantu">Google Maps <i class="fas fa-map-marked-alt"></i></a>'
	  },
	  {
	    title: 'Código postal',
	    html: 'Escribe los 5 digitos de tu código postal'
	  }
	]).then((result) => {
	  if (result.value) {

	  	var valores = result.value;

	  	var datos = {
	  		"direccion":valores[0],
	  		"codigoPostal":valores[1]
	  	}

	  	if(valores[1] != "" && valores[0] != ""){

		  	if( (tiene_letras(valores[1]) == 1) || (valores[1].length > 5) ){ //SÍ EL CODIGO POSTAL TIENE LETRAS, MANDAMOS ERROR
		  		
		  		swal(
		  			'Código postal debe tener solo números y ser de 5 digitos',
		  			'Inténtalo de nuevo',
		  			'error'
		  		);

		  	} else {
		  		
		  		//AQUI SE HACE LA PETICION AJAX PARA ENVIAR LA INFORMACION 
		  		$.ajax({
		  			data:datos,
		  			url:url+'vistas/asset/ajax/agregarDireccion.ajax.php',
		  			type:'POST',
		  			success: function(respuesta){

		  				if(respuesta){

			  				swal({
								title: 'Dirección añadida',
								text: "Ya puedes enviar órdenes a esa dirección",
								type: 'success',
								confirmButtonColor: '#9ac76d',
								confirmButtonText: 'Entendido!'
							}).then((result) => {
								if (result.value) {
									window.location.reload();
								}
							})
		  				
		  				} else {
		  					
	  						swal(
					  			'Ocurrió un error interno',
					  			'Actualiza la página e inténtalo de nuevo',
					  			'error'
					  		);

		  				}

		  				
		  			}
		  		})


		  	}

	  	} else {
	  		
	  		//SE ENVIA ERROR EN CASO DE QUE AL MENOS UNO DE LOS CAMPOS ESTÉ VACIO
	  		swal(
	  			'Llena todos los campos',
	  			'Inténtalo de nuevo',
	  			'error'
	  		);

	  	}

	  }

	}) */
	
}

function editarPerfilDentista(){

	if( $(".datosPerfil .editarPerfil").hasClass("hidden") ){

  		/*OBTENGO LOS VALORES QUE EL USUARIO INTRODUJO PARA CREAR UN OBJETO JSON*/

		var nombre = $(".datosPerfil .nombre input").val();
		var telefono = $(".datosPerfil .telefono input").val();
		var especialidad = $(".datosPerfil .especialidad input").val();
		var fechaNacimiento = $(".datosPerfil .fechaNacimiento input").val();
		var clinica = $(".datosPerfil .clinica input").val();

		/*************************************************************************/

		if( (nombre != "") &&
			(telefono != "") &&
			(especialidad != "") &&
			(fechaNacimiento != "") &&
			(clinica != "") ) {

			//SI NINGUN DATO ESTÁ VACIO, CREAR EL JSON Y ENVIAR POR AJAX

			var datos = {
				"nombre":nombre,
				"telefono":telefono,
				"especialidad":especialidad,
				"fechaNacimiento":fechaNacimiento,
				"clinica":clinica
			}

			$.ajax({
				data:datos,
				url:url+"vistas/asset/ajax/actualizarPerfilDentista.ajax.php",
				type: "POST",
				success: function(respuesta){

					if(respuesta){

						swal({
				  			title:'Completado',
				  			text:'Tu perfil ha sido modificado',
				  			type:'success',
				  			confirmButtonText:'Entendido',
				  			confirmButtonColor: '#9ac76d'
				  		})
						
						/*AGREGO LO QUE EL USUARIO ESCRIBIO EN LAS ETIQUETAS DEL PERFIL*/

						$(".datosPerfil .nombre span").html(nombre);
						$(".datosPerfil .telefono span").html(telefono);
						$(".datosPerfil .especialidad span").html(especialidad);
						$(".datosPerfil .fechaNacimiento span").html(fechaNacimiento);
						$(".datosPerfil .clinica span").html(clinica);

						/***************************************************************/	

						/*OCULTAMOS EL RESTO DE INPUTS Y DEJAMOS LAS ETIQUETAS SPAN*/

						$(".datosPerfil .dato input").addClass("hidden");
						$(".datosPerfil .dato textarea").addClass("hidden"); //OCULTAMOS TODOS LOS INPUT DE LOS CAMPOS

						$(".datosPerfil .dato span").removeClass("hidden"); //MOSTRAMOS TODAS LAS ETIQUETAS SPAN

						$(".datosPerfil .editarPerfil").removeClass("hidden");
						$(".datosPerfil .completarEdicion").addClass("hidden"); //CAMBIAMOS EL ICONO DE LA PALOMITA POR EL DE EDITAR

						/***********************************************************/

					} else {

						swal(
				  			'Ocurrió un error interno',
				  			'Actualiza la página e inténtalo de nuevo',
				  			'error'
				  		);

					}

					
					
				}
			});


		} else {

			swal({
	  			title:'Ocurrió un error',
	  			text:'No dejes vacío ningún campo',
	  			type:'error',
	  			confirmButtonText:'Entendido',
	  			confirmButtonColor: '#9ac76d'
	  		})

		}



	} else {

		//QUE PASA SÍ EL ICONO DE EDITAR NO ESTA OCULTO

		$(".datosPerfil .dato input").removeClass("hidden");

		$(".datosPerfil .dato span").addClass("hidden"); //OCULTAMOS TODAS LAS ETIQUETAS SPAN

		$(".datosPerfil .editarPerfil").addClass("hidden");
		$(".datosPerfil .completarEdicion").removeClass("hidden"); //CAMBIAMOS EL ICONO DE EDITAR POR LA PALOMITA

	}


}

//////FUNCION PARA EDITAR EL PERFIL DEL DENTISTA

function editarPerfilTecnico(){

	

	/*OBTENGO LOS VALORES QUE EL USUARIO INTRODUJO PARA CREAR UN OBJETO JSON*/

	var nombTecnico = $(".datosPerfilTecnico .nombTecnico input").val();
	var nombLaboratorio = $(".datosPerfilTecnico .nombLaboratorio input").val();
	var descrLaboratorio = $(".datosPerfilTecnico .descrLaboratorio textarea").val();
	var telefonoLaboratorio = $(".datosPerfilTecnico .telefonoLaboratorio input").val();

	/*************************************************************************/

	if( (nombTecnico.length != 0) &&
		(nombLaboratorio.length != 0) &&
		(descrLaboratorio.length != 0) &&
		(telefonoLaboratorio.length != 0) ) {

		//SI NINGUN DATO ESTÁ VACIO, CREAR EL JSON Y ENVIAR POR AJAX

		var datos = {
			"nombTecnico":nombTecnico,
			"nombLaboratorio":nombLaboratorio,
			"descrLaboratorio":descrLaboratorio,
			"telefonoLaboratorio":telefonoLaboratorio,
			"direccionLaboratorio":direccionLaboratorio,
			"cpLaboratorio":cpLaboratorio
		}

		$.ajax({
			data:datos,
			url:url+"vistas/asset/ajax/actualizarPerfilTecnico.ajax.php",
			type: "POST",
			success: function(respuesta){

				if(respuesta){

					swal({
			  			title:'Completado',
			  			text:'Tu perfil ha sido modificado',
			  			type:'success',
			  			confirmButtonText:'Entendido',
			  			confirmButtonColor: '#9ac76d'
			  		})
					
					/*AGREGO LO QUE EL USUARIO ESCRIBIO EN LAS ETIQUETAS DEL PERFIL*/

					$(".datosPerfilTecnico .nombTecnico span").html(nombTecnico);
					$(".datosPerfilTecnico .nombLaboratorio span").html(nombLaboratorio);
					$(".datosPerfilTecnico .descrLaboratorio span").html(descrLaboratorio);
					$(".datosPerfilTecnico .telefonoLaboratorio span").html(telefonoLaboratorio);
					$(".datosPerfilTecnico .direccionLaboratorio span").html(direccionLaboratorio);
					$(".datosPerfilTecnico .cpLaboratorio span").html(cpLaboratorio);

					/***************************************************************/	

					/*OCULTAMOS EL RESTO DE INPUTS Y DEJAMOS LAS ETIQUETAS SPAN*/

					$(".datosPerfilTecnico .dato input").addClass("hidden");
					$(".datosPerfilTecnico .dato textarea").addClass("hidden"); //OCULTAMOS TODOS LOS INPUT DE LOS CAMPOS

					$(".datosPerfilTecnico .dato span").removeClass("hidden"); //MOSTRAMOS TODAS LAS ETIQUETAS SPAN

					$(".datosPerfilTecnico .editarPerfilTecnico").removeClass("hidden");
					$(".datosPerfilTecnico .completarEdicion").addClass("hidden"); //CAMBIAMOS EL ICONO DE LA PALOMITA POR EL DE EDITAR

					/***********************************************************/

				} else {

					swal(
			  			'Ocurrió un error interno',
			  			'Actualiza la página e inténtalo de nuevo',
			  			'error'
			  		);

				}

				
				
			}
		});


	} else {

		swal({
  			title:'Ocurrió un error',
  			text:'No dejes vacío ningún campo',
  			type:'error',
  			confirmButtonText:'Entendido',
  			confirmButtonColor: '#9ac76d'
  		})

	}


}

$(".direccion").on("click",".eliminarDireccion",function(){

	var id = $(this).parent().attr("data-id");
	var elementoPadre = $(this).parent().parent();

	swal({
		title: '¿Seguro que quieres eliminar la dirección?',
		text: "El cambio es permanente",
		type: 'question',
		confirmButtonColor: '#c42d2d',
		confirmButtonText: 'Eliminar'
	}).then((result) => {

		if (result.value) {
			
			//CODIGO PARA ELIMINAR LA DIRECCION DE LA BASE DE DATOS

			var datos = {
				"id":id
			}

			$.ajax({
				type:"POST",
				data:datos,
				url:url+"vistas/asset/ajax/eliminarDireccion.ajax.php",
				context:this,
				success:function(respuesta){

					console.log(respuesta);

					if(respuesta){

						//window.location.reload();
						elementoPadre.html('');

					} else {

						console.log("ocurrio un error interno");

					}

				}
			})

		}

	})

})

$(".direccion").on("click",".seleccionarDireccion",function(){


	var id = $(this).parent().attr("data-id");
	var elementoPadre = $(this).parent().parent();

	var datos = {
		"id":id
	}

	$.ajax({
		type:"POST",
		data:datos,
		url:url+"vistas/asset/ajax/direccionPredeterminada.ajax.php",
		context:this,
		success:function(respuesta){

			//console.log(respuesta);

			if(respuesta){

				//window.location.reload();
				$(".direccionPredeterminada").addClass('fa-circle');
				$(".direccionPredeterminada").addClass('seleccionarDireccion');
				$(".direccionPredeterminada").removeClass('fa-check-circle');
				$(".direccionPredeterminada").removeClass('direccionPredeterminada');

				$(this).addClass('fa-check-circle');
				$(this).addClass('direccionPredeterminada');
				$(this).removeClass('fa-circle');
				$(this).removeClass('seleccionarDireccion');


			} else {

				console.log("ocurrio un error interno");

			}

		}
	})



})

/*$(".direccion .seleccionarDireccion").popover({
	title:'',
	content:'Direccion predeterminada',
	trigger:'hover',
	placement:'top'
})*/

$(".direccion").on("mouseover",".seleccionarDireccion",function(){

	$(this).removeClass("fa-circle");

	$(this).addClass("fa-check-circle");

	$(this).css("color","green");

	$(this).css("cursor","pointer");

});

$(".direccion").on("mouseleave",".seleccionarDireccion",function(){

	$(this).removeClass("fa-check-circle");

	$(this).addClass("fa-circle");

	$(this).css("color","gray");

});

$(".direccion .agregarDireccion").click(agregarDireccion);
$(".moduloAgregarDireccion .fa-times").click(agregarDireccion);

// METODOS PARA EDITAR EL PERFIL DEL TECNICO
$(".editarPerfilTecnico").click(editarPerfilTecnico);

$(".completarEdicion").click(editarPerfilTecnico);

//METODOS PARA EDITAR EL PERFIL DEL DENTISTA

$(".editarPerfil").click(editarPerfilDentista);

$(".completarEdicion").click(editarPerfilDentista);

$('.fechaNacimientoDentista').datepicker({
    format: 'yyyy-mm-dd',
    language: 'es',
    autoclose: true
});

$(".icon-editar").popover({
	title:'',
	content:'Editar',
	trigger:'hover',
	placement:'right'
})

$(".fa-check").popover({
	title:'',
	content:'Confirmar',
	trigger:'hover',
	placement:'right'
})



///////////////////////////////////
//////AQUI SE ESCRIBE EL CODIGO PARA BUSCAR LAS direccion CON EL CODIGO POSTAL
//////////////////////////////////

function buscarDireccion(){

	var cp = $("#codigoPostal").val();

	if( cp != "" &&
		cp.length == 5 ){ //SI el codigo postal es de 5 digitos y es diferente a vacio

		var datos = {

			"codigoPostal":cp

		}


		$.ajax({
			data:datos,
			type:"POST",
			url:url+"vistas/asset/ajax/obtenerDireccionCP.ajax.php",
			success: function(respuesta){

				$("#codigoPostal").removeClass("is-invalid");

				$(".datosDireccionBuscada").removeClass("hidden");

				if(respuesta != null){

					//console.log(respuesta);
					var valorDefecto = $("#colonia").attr("data-defecto");

					var direccion = JSON.parse(respuesta);

					var colonias = '<option value="" selected>Selecciona tu colonia o fraccionamiento</option>';

					if(direccion.length == 1){

						colonias += '<option value="'+direccion[0]['id']+'" selected>'+direccion[0]['asentamiento']+'</option>';

					} else {

						for(var i=0; i<direccion.length; i++){

							colonias += (i==0) ? '<option value="'+direccion[i]['id']+'" selected>'+direccion[i]['asentamiento']+'</option>' : '<option value="'+direccion[i]['id']+'">'+direccion[i]['asentamiento']+'</option>';

							//colonias += '<option value="'+direccion[i]['id']+'">'+direccion[i]['asentamiento']+'</option>';

						}

					}


					//console.log(direccion);
					//console.log(colonias);

					$("#estado").val(direccion[0]['estado']);
					$("#municipio").val(direccion[0]['municipio']);
					$("#colonia").prop("disabled",false);
					$("#colonia").html(colonias);


					if( valorDefecto != "" ){

						document.ready = document.getElementById("colonia").value = valorDefecto;

					}

				} else {

					$("#colonia").html('<option value="">REVISTA TU CODIGO POSTAL</option>');
					$("#codigoPostal").addClass("is-invalid");

				}


			}

		})

	} else {

		//ENVIAMOS UNA ALERTA DE QUE TIENE QUE CUMPLIR CON MAS CARACTERISTICAS EL CODIGO POSTAL

		$("#colonia").html('<option value="">REVISA TU CODIGO POSTAL</option>');

		$("#codigoPostal").addClass("is-invalid");

		$(".datosDireccionBuscada").addClass("hidden");

	}

}

$('#codigoPostal').each(function() {
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
	
	//BUSCAR LA DIRECCION Y MOSTRARLA
	buscarDireccion();
     
    //// end action
   }
 });
});

function obtenerOrdenesYCalificacion(){

	$.ajax({
		type:"POST",
		url:url+"vistas/asset/ajax/obtenerOrdenesYCalificacion.ajax.php",
		success:function(respuesta){

			console.log(respuesta);

			if(respuesta != null){

				var datos = JSON.parse(respuesta);

				$(".totalOrdenesHechas").html(datos['totalOrdenes']);
				$(".totalOrdenesFinalizadas").html(datos['totalOrdenesFinalizadas']);
				$(".totalCalificacion").html(datos['totalCalificacion']);

			}

		}
	})

}

$(document).ready(function(){

	let paginaActual = $("#paginaActual").val();

	if( paginaActual == "perfil" ){

		buscarDireccion();

		obtenerOrdenesYCalificacion();

	}

});



$("#buscarDireccion").click(buscarDireccion);

//////


$("#btnGuardarCambiosPerfil").click(function(){

	if( $(this).hasClass("tecnico") ){

  		/*OBTENGO LOS VALORES QUE EL USUARIO INTRODUJO PARA CREAR UN OBJETO JSON*/

		var nombTecnico = $(".datosPerfilTecnico .nombTecnico input").val();
		var nombLaboratorio = $(".datosPerfilTecnico .nombLaboratorio input").val();
		var descrLaboratorio = $(".datosPerfilTecnico .descrLaboratorio textarea").val();
		var telefonoLaboratorio = $(".datosPerfilTecnico .telefonoLaboratorio input").val();
		var idSepomex = $("#colonia").val();
		var calle = $("#calle").val();
		var estado = $("#estado").val();
		var municipio = $("#municipio").val();


		/*************************************************************************/

		if( (nombTecnico != "") &&
			(nombLaboratorio != "") &&
			(descrLaboratorio != "") &&
			(telefonoLaboratorio != "") &&
			(idSepomex != "") &&
			(calle != "") &&
			(estado != "") &&
			(municipio != "") ){

			//SI NINGUN DATO ESTÁ VACIO, CREAR EL JSON Y ENVIAR POR AJAX

			var datos = {
				"nombTecnico":nombTecnico,
				"nombLaboratorio":nombLaboratorio,
				"descrLaboratorio":descrLaboratorio,
				"telefonoLaboratorio":telefonoLaboratorio,
				"idSepomex":idSepomex,
				"calle":calle
			}

			console.log(datos);

			$.ajax({
				data:datos,
				url:url+"vistas/asset/ajax/actualizarPerfilTecnico.ajax.php",
				type: "POST",
				success: function(respuesta){

					if(respuesta){

						swal({
				  			title:'Completado',
				  			text:'Tu perfil ha sido modificado',
				  			type:'success',
				  			confirmButtonText:'Entendido',
				  			confirmButtonColor: '#9ac76d'
				  		})
					
					} else {

						swal(
				  			'Ocurrió un error interno',
				  			'Actualiza la página e inténtalo de nuevo',
				  			'error'
				  		);

					}

					
					
				}
			});


		} else {

			swal({
	  			title:'Ocurrió un error',
	  			text:'No dejes vacío ningún campo',
	  			type:'error',
	  			confirmButtonText:'Entendido',
	  			confirmButtonColor: '#9ac76d'
	  		})

		} 



	} else if($(this).hasClass("dentista")) {

		//QUE PASA SÍ ES DENTISTA

		/*OBTENGO LOS VALORES QUE EL USUARIO INTRODUJO PARA CREAR UN OBJETO JSON*/

		var nombre = $(".datosPerfil .nombre input").val();
		var telefono = $(".datosPerfil .telefono input").val();
		var especialidad = $(".datosPerfil .especialidad input").val();
		var fechaNacimiento = $(".datosPerfil .fechaNacimiento input").val();
		var clinica = $(".datosPerfil .clinica input").val();

		/*************************************************************************/

		if( (nombre != "") &&
			(telefono != "") &&
			(especialidad != "") &&
			(fechaNacimiento != "") &&
			(clinica != "") ) {

			//SI NINGUN DATO ESTÁ VACIO, CREAR EL JSON Y ENVIAR POR AJAX

			var datos = {
				"nombre":nombre,
				"telefono":telefono,
				"especialidad":especialidad,
				"fechaNacimiento":fechaNacimiento,
				"clinica":clinica
			}

			console.log(datos);

			$.ajax({
				data:datos,
				url:url+"vistas/asset/ajax/actualizarPerfilDentista.ajax.php",
				type: "POST",
				success: function(respuesta){

					if(respuesta){

						swal({
				  			title:'Completado',
				  			text:'Tu perfil ha sido modificado',
				  			type:'success',
				  			confirmButtonText:'Entendido',
				  			confirmButtonColor: '#9ac76d'
				  		})

					} else {

						swal(
				  			'Ocurrió un error interno',
				  			'Actualiza la página e inténtalo de nuevo',
				  			'error'
				  		);

					}

					
					
				}
			});


		} else {

			swal({
	  			title:'Ocurrió un error',
	  			text:'No dejes vacío ningún campo',
	  			type:'error',
	  			confirmButtonText:'Entendido',
	  			confirmButtonColor: '#9ac76d'
	  		})

		}

	}

})


/////////////

$("#btnAgregarDireccion").click(function(){

	if( $("#calle").val() != "" &&
		$("#colonia").val() != null &&
		$("#codigoPostal").val() != "")  {

		var calle = $("#calle").val();
		var colonia = $("#colonia").val();
		var cp = $("#codigoPostal").val();

		var datos = {

			"calle":calle,
			"idSepomex":colonia,
			"cp":cp

		}

		$.ajax({
  			data:datos,
  			url:url+'vistas/asset/ajax/agregarDireccion.ajax.php',
  			type:'POST',
  			success: function(respuesta){

  				if(!respuesta){

					swal(
			  			'Ocurrió un error interno',
			  			'Actualiza la página e inténtalo de nuevo',
			  			'error'
			  		);
  				
  				} else {
  					
  					var nuevaDireccion = 
  										'<div class="col-lg-9 col-md-9 col-sm-9 col-9">'+

											calle+', '+cp+

										'</div>'+

										'<div class="col-lg-3 col-md-3 col-sm-3 col-3 text-right direccion" data-id="'+respuesta+'">'+

											'<i class="fas fa-minus-circle eliminarDireccion"></i>'+
											'<i class="far fa-circle seleccionarDireccion"></i>'+

										'</div>';

  					$(".nuevasdireccion").append(nuevaDireccion);

  					agregarDireccion();

	  				swal({
						title: 'Dirección añadida',
						text: "Ya puedes enviar órdenes a esa dirección",
						type: 'success',
						confirmButtonColor: '#9ac76d',
						confirmButtonText: 'Entendido!'
					}).then((result) => {
						if (result.value) {
							//window.location.reload();
						}
					})

  				}

  				
  			}
  		})

	} else {

		swal({
  			title:'Ocurrió un error',
  			text:'No dejes vacío ningún campo',
  			type:'error',
  			confirmButtonText:'Entendido',
  			confirmButtonColor: '#9ac76d'
  		})

	}

})

///////////////////////////////////////////////
//////////CODIGO PARA LA PREVISUALIZACION DE LA IMAGEN Y EL RECORTE DE LA MISMA
///////////////////////////////////////////////

var datosFoto;
var x1;
var x2;
var y1;
var y2;
var ancho;
var alto;

$("#nuevaFotoPerfil").on("change",function(){

	$("#respuestaSubidaFotoPerfil").html('');

	var imagenes = document.getElementById('nuevaFotoPerfil').files;

	var navegador = window.URL || window.webkitURL;

	if(imagenes.length >= 1){
			
		var size = imagenes[0].size;
		var type = imagenes[0].type;
		var name = imagenes[0].name;
		var width = imagenes[0].width;
		var height = imagenes[0].height;

		if(size > 2048*2048){

			$("#respuestaSubidaFotoPerfil").append('<p class="text-danger">El archivo '+name+' supera el máximo permitido de 2MB</p>');

		} else if(type != 'image/jpeg' && type != 'image/jpg'){

			$("#respuestaSubidaFotoPerfil").append('<p class="text-danger">El archivo '+name+' no es un formato permitido</p>');

		} else {

			if(ancho > 1000){

				ancho = ancho / 2;
				alto = alto / 2;

			}

			//EN CASO DE QUE PASE LOS DOS FILTROS, MOSTRAMOS EL MODAL CON LA IMAGEN PARA QUE LA PUEDA RECORTAR
			var objeto_url = navegador.createObjectURL(imagenes[0]);
			$("#respuestaSubidaFotoPerfil").append('<img src="'+objeto_url+'" alt="'+name+'" class="previsualizacionFotoPerfil img-fluid" style="height:'+height+'; width:'+width+'">');

			var imagen = $(".previsualizacionFotoPerfil").imgAreaSelect({instance:true});

			imagen.setOptions({
				aspectRatio: '1:1',
				handles: true,
				fadeSpeed: 100,
				movable:true,
				outerColor:'#ffffff',
				selectionColor:'#ffffff',
				outerOpacity:0,
				show: true,
				onSelectEnd: function(img, sel){

					if(sel.height <= 0){

						$(".btnGuardarFotoPerfil").attr("disabled","disabled");

						//console.log("deseleccionaste la imagen");

					} else {

						$(".btnGuardarFotoPerfil").removeAttr("disabled");

						x1 = sel.x1;
						x2 = sel.x2;
						y1 = sel.y1;
						y2 = sel.y2;
						ancho = sel.width;
						alto = sel.height;

						datosFoto = {
							"x1":x1,
							"x2":x2,
							"y1":y1,
							"y2":y2,
							"ancho":ancho,
							"alto":alto
						}

						//console.log(datosFoto);

					}


				}
			})

			$("#cambiarFotoPerfilModal").modal("show");

			$("#cambiarFotoPerfilModal").on("hide.bs.modal",function(){

				imagen.cancelSelection();

			})

		}
		
	} else {

		$("#respuestaSubidaFotoPerfil").append('<p class="text-danger">No puedes subir mas de 1 imagen</p>');

	}


})


$(".btnGuardarFotoPerfil").click(function(){

	console.log($("#respuestaSubidaFotoPerfil img").width());
	console.log($("#respuestaSubidaFotoPerfil img").height());

	var formData = new FormData($("#subirFotoPerfil")[0]);

	formData.append("x1",x1);
	formData.append("x2",x2);
	formData.append("y1",y1);
	formData.append("y2",y2);
	formData.append("ancho",ancho);
	formData.append("alto",alto);
	formData.append("anchoOriginal",$("#respuestaSubidaFotoPerfil img").width());
	formData.append("altoOriginal",$("#respuestaSubidaFotoPerfil img").height());

	console.log(datosFoto);

	$.ajax({
        url: url+"vistas/asset/ajax/cambiarFotoPerfil.ajax.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function(){
        	$(".btnGuardarFotoPerfil").prop("disabled",true);
        },
        success: function(respuesta)
        {	
        	//AQUI RECIBO LOS ENLACES DE COBRO PARA PAYPAL O MERCADOPAGO
       		
        	if (respuesta == "ok"){ //SÍ ES DIFERENTE DE 0, SIGNIFICA QUE RETORNO ALGUN NOMBRE DE FOTO DE PERFIL

        		//console.log("todo bien");

        		swal({
					title: 'Foto actualizada',
					type: 'success',
					confirmButtonColor: '#9ac76d',
					confirmButtonText: 'Ok!'
				}).then((result) => {
					if (result.value) {
						window.location.reload();
					}
				})


        	} else {

        		swal({
					title: 'Algo salio mal',
					type: 'error',
					confirmButtonColor: '#9ac76d',
					confirmButtonText: 'Volver a intentar!'
				}).then((result) => {
					if (result.value) {
						window.location.reload();
					}
				})

        	}

        }
    });

})


//////////////////////////////////////////////////////////////
/////////////////CAMBIAR CONTRASEÑA MODAL
//////////////////////////////////////////////////////////////

$(".cambiarContraseña").click(function(e){

	e.preventDefault();

	$("#cambiarContraseñaModal").modal('show');

});

$(".btnCambiarPassword").click(function(){

	var passActual = $("#passActual").val();

	var newPass = $("#newPass").val();

	var confirmNewPass = $("#confirmNewPass").val();

	if( passActual.length >= 8 &&
		newPass.length >= 8 &&
		confirmNewPass.length >= 8 ) {


		if(newPass == confirmNewPass){

			//SIGNIFICA QUE EL PASSWORD PASO LOS FILTROS
	
			$("#cambiarContraseñaModal .error").html('');

			var formData = new FormData($("#cambiarContraseña")[0]);

			$.ajax({

				type:"POST",
				data:formData,
				url:url+"vistas/asset/ajax/cambiarPassword.ajax.php",
			    contentType: false,
			    processData: false,
				success:function(respuesta){

					console.log(respuesta);

					if(respuesta == "noSamePassword"){

						swal({
							title: 'Inténtalo de nuevo',
							text: 'Tu contraseña actual no es la misma',
							type: 'error',
							confirmButtonColor: '#9ac76d',
							confirmButtonText: 'Ok!'
						}).then((result) => {
							if (result.value) {

								$("#passActual").val('');
								$("#passActual").focus();

							}
						});

					} else if(respuesta == "noSameNewPassword") {

						swal({
							title: 'Inténtalo de nuevo',
							text: 'Confirma tu nueva contraseña correctamente',
							type: 'error',
							confirmButtonColor: '#9ac76d',
							confirmButtonText: 'Ok!'
						}).then((result) => {
							if (result.value) {

								$("#newPass").val('');
								$("#newPass").focus();

								$("#confirmNewPass").val('');

							}
						});


					} else if(respuesta == "sameBeforePassword"){

						swal({
							title: 'Cambia por una contraseña nueva',
							text: 'No puedes utilizar tu contraseña actual como nueva contraseña',
							type: 'error',
							confirmButtonColor: '#9ac76d',
							confirmButtonText: 'Ok!'
						}).then((result) => {
							if (result.value) {

								$("#newPass").val('');
								$("#newPass").focus();

								$("#confirmNewPass").val('');

							}
						});

					} else {

						//SI NO TIENE NINGUN ERROR ANTERIOR, MOSTRAMOS EL AVISO DE QUE TODO SALIO BIEN
						let timerInterval
						Swal({
							title: 'Contraseña actualizada',
							timer: 2000,
							type: 'success',
							onClose: () => {
						    	clearInterval(timerInterval)
							}
						}).then((result) => {

							if(result.value){

								$("#cambiarContraseñaModal").modal('hide');

							}

						})
					}

				}

			})

		} else {

			$("#cambiarContraseñaModal .error").html('<div class="alert alert-danger txtPequeño">Las contraseñas nuevas deben ser iguales</div>')

		}



	} else{

		$("#cambiarContraseñaModal .error").html('<div class="alert alert-danger txtPequeño">Tu contraseña y la nueva debe ser de 8 caracteres o más</div>');


	}

})



///////////////////////////////////////////////
//////////CODIGO PARA LA PREVISUALIZACION DE LA IMAGEN DE PREORDEN Y EL RECORTE DE LA MISMA
///////////////////////////////////////////////

var datosFoto;
var x1;
var x2;
var y1;
var y2;
var ancho;
var alto;

$("#imagenPreOrden").on("change",function(){

	$("#respuestaSubidaFotoPreOrden").html('');

	var imagenes = document.getElementById('imagenPreOrden').files;

	var navegador = window.URL || window.webkitURL;

	if(imagenes.length >= 1){
			
		var size = imagenes[0].size;
		var type = imagenes[0].type;
		var name = imagenes[0].name;
		var objeto_url = navegador.createObjectURL(imagenes[0]);
		var pixeles = false;

		var file,img;

		img = new Image();
		img.onload = function(){
			var width = this.width; //ANCHO DE LA IMAGEN DEL INPUT FILE
			var height = this.height; //ALTO DE LA IMAGEN DEL INPUT FILE
			console.log(img.height+" "+img.width);

			if(size > 2048*2048){

				swal({
					text: 'El archivo '+name+' supera el máximo permitido de 2MB',
					type: 'error',
					confirmButtonColor: '#9ac76d',
					confirmButtonText: 'Volver a intentar!'
				})

			} else if(type != 'image/jpeg' && type != 'image/jpg'){

				swal({
					text: 'El archivo '+name+' no es un formato permitido',
					type: 'error',
					confirmButtonColor: '#9ac76d',
					confirmButtonText: 'Volver a intentar!'
				})

			} else if(height < 439 || width < 439){

				swal({
					text: 'La resolución mínima de la imagen es de 440x440 píxeles',
					type: 'error',
					confirmButtonColor: '#9ac76d',
					confirmButtonText: 'Volver a intentar!'
				})

			} else {

				//EN CASO DE QUE PASE LOS DOS FILTROS, MOSTRAMOS EL MODAL CON LA IMAGEN PARA QUE LA PUEDA RECORTAR
				$("#respuestaSubidaFotoPreOrden").append('<img src="'+objeto_url+'" alt="'+name+'" class="previsualizacionFotoPreOrden img-fluid">');

				var imagen = $(".previsualizacionFotoPreOrden").imgAreaSelect({instance:true});

				imagen.setOptions({
					aspectRatio: '3:4',
					handles: true,
					fadeSpeed: 100,
					movable:true,
					minHeight: 350,
					onSelectEnd: function(img, sel){

						if(sel.height <= 0){

							$(".btnGuardarFotoPreOrden").attr("disabled","disabled");

							//console.log("deseleccionaste la imagen");

						} else {

							$(".btnGuardarFotoPreOrden").removeAttr("disabled");

							x1 = sel.x1;
							x2 = sel.x2;
							y1 = sel.y1;
							y2 = sel.y2;
							ancho = sel.width;
							alto = sel.height;

							datosFoto = {
								"x1":x1,
								"x2":x2,
								"y1":y1,
								"y2":y2,
								"ancho":ancho,
								"alto":alto
							}

							//console.log(datosFoto);

						}


					}
				})

				$("#cambiarFotoPreOrdenModal").modal("show");

				$("#cambiarFotoPreOrdenModal").on("hide.bs.modal",function(){

					imagen.cancelSelection();

				})

			}

		}
		img.src = navegador.createObjectURL(imagenes[0]);

		
		
	} else {

		$("#respuestaSubidaFotoPreOrden").append('<p class="text-danger">No puedes subir mas de 1 imagen</p>');

	}


})


$(".btnGuardarFotoPreOrden").click(function(){

	var formData = new FormData($("#subirImagenPreOrden")[0]);

	formData.append("x1",x1);
	formData.append("x2",x2);
	formData.append("y1",y1);
	formData.append("y2",y2);
	formData.append("ancho",ancho);
	formData.append("alto",alto);
	formData.append("anchoOriginal",$(".previsualizacionFotoPreOrden").width());
	formData.append("altoOriginal",$(".previsualizacionFotoPreOrden").height());


	//console.log(datosFoto);

	$.ajax({
        url: url+"vistas/asset/ajax/cambiarFotoPreOrden.ajax.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function(){
        	$(".btnGuardarFotoPreOrden").prop("disabled",true);
        },
        success: function(respuesta)
        {	
        	//AQUI RECIBO LOS ENLACES DE COBRO PARA PAYPAL O MERCADOPAGO

        	console.log(respuesta);
       		
        	if (respuesta == "ok"){ //SÍ ES DIFERENTE DE 0, SIGNIFICA QUE RETORNO ALGUN NOMBRE DE FOTO DE PERFIL

        		//console.log("todo bien");

        		swal({
					title: 'Foto de pre-orden actualizada',
					type: 'success',
					confirmButtonColor: '#9ac76d',
					confirmButtonText: 'Ok!'
				}).then((result) => {
					if (result.value) {
						window.location.reload();
					}
				})


        	} else {

        		swal({
					title: 'Algo salio mal',
					type: 'error',
					confirmButtonColor: '#9ac76d',
					confirmButtonText: 'Volver a intentar!'
				}).then((result) => {
					if (result.value) {
						window.location.reload();
					}
				})

        	}

        }
    });

})
