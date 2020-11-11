$(".fa-arrow-left").popover({
	content:'<span class="text-muted">Regresar</span>',
	html:true,
	trigger: "hover",
	placement:"top"

})

$(".ordenCalificada").popover({

	content:'<i>Gracias por tus comentarios</i>',
	html: true,
	trigger: 'hover',
	placement: 'top'

})

function listaOrdenes(filtro,contenedor){

	var idOrden = $("#idOrdenDetalles").val();

	var datos = {
		"filtro":filtro,
		"url":url,
		"idOrden":idOrden
	}

	$.ajax({
		data:datos,
		type:"POST",
		url:url+"vistas/asset/ajax/listaOrdenesFiltro.ajax.php",
		beforeSend: function(){
			$("#"+contenedor).html('<div class="text-center"><img width="120px;" src="'+url+'vistas/asset/images/cargando.gif" alt="cargando..." /><br /><p class="bold text-muted">Cargando...</p></div>');
		},
		success: function(respuesta){

			setTimeout(function(){

				$("#"+contenedor).html('');
				$("#"+contenedor).html(respuesta);
				$(".conversacion").scrollTop(50000);
				
			}, 500);
		}
	})

}

$("#resumen").click(function(){

	listaOrdenes("resumen","v-resumen");
	clearTimeout(idTimeout); //DETENEMOS LA VERIFICACION DE LOS MENSAJES DEL CHAT

})

$("#pagos").click(function(){

	listaOrdenes("pagos","v-pagos");
	clearTimeout(idTimeout); //DETENEMOS LA VERIFICACION DE LOS MENSAJES DEL CHAT

})

$("#ordenes").click(function(){

	listaOrdenes("ordenes","v-ordenes");
	clearTimeout(idTimeout); //DETENEMOS LA VERIFICACION DE LOS MENSAJES DEL CHAT

})

$("#fotos").click(function(){

	listaOrdenes("fotos","v-fotos");
	clearTimeout(idTimeout); //DETENEMOS LA VERIFICACION DE LOS MENSAJES DEL CHAT

})



function ordenesFiltro(filtro,click){

	var contenedor = $(click).attr("data-contenedor")+"Contenedor";

	var datos = {
		"filtro":filtro,
		"url":url
	}

	console.log(contenedor);

	$.ajax({
		"data":datos,
		"url":url+"vistas/asset/ajax/obtenerOrdenesFiltro.ajax.php",
		"type":"POST",
		beforeSend: function(){
			$("."+contenedor).html('<div class="text-center"><img width="120px;" src="'+url+'vistas/asset/images/cargando.gif" alt="cargando..." /><br /><p class="bold text-muted">Cargando...</p></div>');
		},
		success: function(respuesta){
			setTimeout(function(){

				$("."+contenedor).html('');
				$("."+contenedor).html(respuesta);
				$(".tablaOrdenes").tableSorter();
				
			}, 500);
		}
	})

}

$("#pills-ordenesTotales").click(function(){
	ordenesFiltro("prioritario","#pills-ordenesTotales");
})

$("#pills-ordenesNuevas").click(function(){
	ordenesFiltro("nuevo","#pills-ordenesNuevas")
});

$("#pills-ordenesAtrasadas").click(function(){
	ordenesFiltro("atrasado","#pills-ordenesAtrasadas")
});

$("#pills-ordenesFinalizadas").click(function(){
	ordenesFiltro("finalizado","#pills-ordenesFinalizadas")
});

$("#pills-ordenesCanceladas").click(function(){
	ordenesFiltro("cancelado","#pills-ordenesCanceladas")
});

$(".tab-pane").on("click",".elementoTablaOrdenes",function(){

	var id = $(this).attr("data-id");

	window.location = url+"ordenes?orden="+id;


})

$(".elementoTablaOrdenes").click(function(){

	var id = $(this).attr("data-id");

	window.location = url+"ordenes?orden="+id;

})

$(".statusOrden").click(function(){

	var status = $(this).attr("data-status");
	var idOrden = $("#idOrdenDetalles").val();
	var idHist = $(this).attr("data-idHist");
	
	switch(status){

		case 'trabajoTerminado': //TECNICO DENTAL

			Swal({
				title: 'Orden terminada',
				text: "Informará a tu dentista que haz terminado su trabajo y podrás recibir mas ordenes",
				type: 'info',
				showCancelButton: true,
				confirmButtonColor: '#9ac76d',
				cancelButtonColor: '#a5a5a5',
				confirmButtonText: 'Si, he terminado <i class="fas fa-check-circle"></i>',
				cancelButtonText: 'Cancelar'
			}).then((result) => {

				if (result.value) {
					
					datos = {
						"status":status,
						"idOrden":idOrden,
						"idHist":idHist
					}

					$.ajax({
						data:datos,
						type:"POST",
						url:url+"vistas/asset/ajax/statusOrden.ajax.php",
						success:function(respuesta){

							if(respuesta){

								window.location.reload();

							}

						}
					})

				}

			})

			break;

		case 'generarNuevaOrden': //DENTISTA

			$("#generarNuevaOrdenModal").modal("show");

			break;

		case 'trabajoEnProceso':

			break;

		case 'trabajoFinalizado':

			$("#modalOpinion").modal('show');

			break;

		case 'esperaOtraOrden':

			break;

		case 'trabajoTerminadoDentista':

			swal({
			  type: 'info',
			  title: '¿Ya recibiste tu trabajo?',
			  html: 'Presiona el botón <div class="bold">He recibido el trabajo</div> para enviar la siguiente orden',
			  confirmButtonText: 'He recibido el trabajo <i class="fas fa-check-circle ml-2"></i>',
			  confirmButtonColor: '#9ac76d',
			  showCancelButton:true,
			  cancelButtonText: 'Aún no lo recibo'
			}).then((result) =>{

				if(result.value){

					datos = {
						"status":status,
						"idOrden":idOrden,
						"idHist":idHist
					}

					console.log(datos);

					$.ajax({
						type:"POST",
						url:url+"vistas/asset/ajax/statusOrden.ajax.php",
						data:datos,
						success:function(respuesta){

							console.log(respuesta);

							if(respuesta){

								//window.location.reload();

							}

						}
					})
					
				}


			})

			break;

	}


});

function ocultarPagoNuevaOrden(){

	$(".btnRegresar").addClass("hidden");
	$(".btnGenerarOrden").removeClass("hidden");
	$(".pagoNuevaOrden").addClass("hidden");
	$(".detallesGeneralesNuevaOrden").removeClass("hidden");
	$(".detallesPagoNuevaOrden").addClass("hidden");
	

}

$("#generarNuevaOrdenModal").on("hidden.bs.modal",ocultarPagoNuevaOrden);
$(".btnRegresar").click(ocultarPagoNuevaOrden);

$(function(){

	$("#nuevaOrdenImg").on("change",function(){

		$("#respuestaSubidaImagen").html('');

		var imagenes = document.getElementById('nuevaOrdenImg').files;

		var navegador = window.URL || window.webkitURL;

		if(imagenes.length <= 6){

			for (var i = 0; i < imagenes.length; i++) {
				
				var size = imagenes[i].size;
				var type = imagenes[i].type;
				var name = imagenes[i].name;

				if(size > 2048*2048){

					$("#respuestaSubidaImagen").append('<p class="text-danger">El archivo '+name+' supera el máximo permitido de 2MB</p>');

				} else if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png' && type != 'image/gif'){

					$("#respuestaSubidaImagen").append('<p class="text-danger">El archivo '+name+' no es un formato permitido</p>');

				} else {

					var objeto_url = navegador.createObjectURL(imagenes[i]);
					$("#respuestaSubidaImagen").append('<div class="col-lg-4 col-md-4 col-sm-6 col-6"><img src="'+objeto_url+'" alt="'+name+'" class="previsualizacionImagenOrden"></div>');

					//console.log(objeto_url);

				}

			}
			
		} else {

			$("#respuestaSubidaImagen").append('<p class="text-danger">No puedes subir mas de 6 imágenes</p>');

		}


	})

})

var formData; //SE DECLARA DE MANERA GLOBAL PARA QUE LAS DIFERENTES FUNCIONES ACCEDAN AL FORMDATA DEL PAGO

$(".btnGenerarOrden").on("click", function(){

	if( $("#nombreOrden").val().length >= 1 &&
		$("#descripcionNuevaOrden").val().length >= 1 &&
		$("#fechaRecepcionNuevaOrden").val().length >= 1 &&
		$("#fechaEntregaNuevaOrden").val().length >= 1 &&
		(	$(".estadoNuevaOrden input[name='estadoTrabajo']:checked").val() == 1 || 
			$(".estadoNuevaOrden input[name='estadoTrabajo']:checked").val() == 2	) ){

		var imagenes = document.getElementById('nuevaOrdenImg').files;

		if(imagenes.length >= 1){ //SÍ EXISTE ALGUNAS IMAGENES, LAS AGREGAMOS AL FORMDATA

			if(imagenes.length <= 6){ //SÍ SON MENOS DE 6 IMAGENES, LAS AGREGAMOS

				formData = new FormData($("#uploadimage")[0]);

				formData.append("nombreOrden",$("#nombreOrden").val());
				formData.append("descripcionOrden",$("#descripcionNuevaOrden").val());
				formData.append("fechaRecepcionNuevaOrden",$("#fechaRecepcionNuevaOrden").val());
				formData.append("fechaEntregaNuevaOrden",$("#fechaEntregaNuevaOrden").val());
				formData.append("estadoNuevaOrden",$(".estadoNuevaOrden input[name='estadoTrabajo']:checked").val());
				formData.append("idOrden",$("#idOrdenDetalles").val());
				formData.append("pago",$("#pagoNuevaOrden option:selected").val());
				formData.append("nombreTrabajo",$("#nombreTrabajoOrden").val());

			} else {

				$("#respuestaSubidaImagen").append('<p class="text-danger">No puedes subir mas de 6 imágenes</p>');

			}

		} else {

			formData = new FormData();

			formData.append("nombreOrden",$("#nombreOrden").val());
			formData.append("descripcionOrden",$("#descripcionNuevaOrden").val());
			formData.append("fechaRecepcionNuevaOrden",$("#fechaRecepcionNuevaOrden").val());
			formData.append("fechaEntregaNuevaOrden",$("#fechaEntregaNuevaOrden").val());
			formData.append("estadoNuevaOrden",$(".estadoNuevaOrden input[name='estadoTrabajo']:checked").val());
			formData.append("idOrden",$("#idOrdenDetalles").val());
			formData.append("pago",$("#pagoNuevaOrden option:selected").val());
			formData.append("nombreTrabajo",$("#nombreTrabajoOrden").val());

		}

		console.log(formData);

		$(".detallesGeneralesNuevaOrden").addClass("hidden");
		$(this).addClass('hidden');
		$(".btnRegresar").removeClass('hidden');
	    $(".detallesPagoNuevaOrden").removeClass("hidden");

	    //EL PROCESO CONTINUA EN LA FUNCION DE $(".formaPagoNuevaOrden");


	    //////////////////////////////////////////////////////////////////

	} else {

		swal({
		  type: 'error',
		  title: 'Completa los datos del formulario',
		  confirmButtonText: 'Ok',
		  confirmButtonColor: '#9ac76d'
		});

	}


});

$(".formaPagoNuevaOrden").click(function(){

	var tipoPago = $(this).attr("data-type");

	formData.append("tipoPago",tipoPago); //AGREGAMOS EL TIPO DE PAGO QUE SELECCIONO EL USUARIO AL FORMULARIO PARA ENVIAR POR AJAX
	formData.append("url",url);

	if(tipoPago.length >= 1){

		if(tipoPago == "divum"){
			//AQUI SE GENERA LA ORDEN SIN PAGO PARA DIVUM

			//console.log("divum");

			$.ajax({
				type:"POST",
				data:formData,
				contentType: false,
		        processData: false,		    
				url:url+"vistas/asset/ajax/generarNuevaOrdenDivum.ajax.php",
				success:function(respuesta){

					console.log(respuesta);

					if(respuesta == "1"){

						swal({
						  type: 'success',
						  title: 'Se ha enviado la orden al Laboratorio',
						  confirmButtonText: 'Ok',
						  confirmButtonColor: '#9ac76d'
						}).then((result)=>{
							window.location.reload();
						})


					}

				}
			})

		} else {

			$.ajax({
				data:formData,
				type:"POST",
				contentType: false,
		        processData: false,
		        context:this,
				url:url+"vistas/asset/ajax/obtenerEnlacePagoNuevaOrden.ajax.php",
				beforeSend: function(){
					$(".pagoSeleccionado",this).html('<i class="fas fa-undo fa-spin ml-4"></i>');
					$(".formaPagoNuevaOrden").attr("data-type",""); //Quitamos el tipo de pago, para que cuando de clic, no se ejecute ésta instrucción
					$(".btnRegresar").prop("disabled",true);
				},
				success: function(respuesta){

					console.log(respuesta);

					if(respuesta != false){

						//console.log(respuesta);

						location.href = respuesta;

					}
				}
			});

		}


	} else {


	}

});




$(".estadoNuevaOrden input[name='estadoTrabajo']").on("change",function(){
	
	if($(this).val() == 1){
		//SÍ ESCOGE QUE LO QUIERE TERMINADO, DEBE PAGAR TODO

		if( $("#pagoNuevaOrden").val() > 0 ){

			$("#pagoNuevaOrden").val("1");
			$("#pagoNuevaOrden").popover({
				placement: "top",
				title: "",
				content: '<span class="text-muted">Para mandar a terminar el trabajo, debe pagar todo</span>',
				trigger: "manual",
				html: true
			});

			$("#pagoNuevaOrden").popover("show");

			setTimeout(function(){
				$("#pagoNuevaOrden").popover("hide");
			}, 4000);

			$("#pagoNuevaOrden").attr("disabled",true);

		}

	} else {

		if( $("#pagoNuevaOrden").val() > 0 ){

			$("#pagoNuevaOrden").attr("disabled",false);		
			
		}


	}

})

function calcularFechaEntregaNuevaOrden(){

	//VARIABLE tiempoEntrega SE DECLARO EN LA VISTA orden.php
	//var tiempoEntrega = $(".tiempoEntrega").val();
	var hoy = new Date($("#fechaRecepcionNuevaOrden").val());
	var i=0;
	while (i<tiempoEntrega) {
	  hoy.setTime(hoy.getTime()+24*60*60*1000); // añadimos 1 día
	  if (hoy.getDay() != 6 && hoy.getDay() != 0)
	    i++;  
	}
	mes = hoy.getMonth()+1;
	if (mes<10) mes = '0'+mes;
	fecha = hoy.getFullYear()+ '-' + mes + '-' + hoy.getDate();

	/*var fechaEntrega = new Date($("#fechaRecepcionNuevaOrden").val());
	
	fechaEntrega.setDate(fechaEntrega.getDate() + tiempoEntrega);

	var fecha =  fechaEntrega.getFullYear() + "-" + (fechaEntrega.getMonth() + 1) + "-" + fechaEntrega.getDate();
	*/

	if(fecha != "NaN-NaN-NaN"){
		$("#fechaEntregaNuevaOrden").val(fecha);
	}
}

$("#fechaRecepcionNuevaOrden").change(calcularFechaEntregaNuevaOrden);

///////////////////////////////////////////////////////////
/*ALGORITMOS PARA ENVIAR LAS OPINIONES A LA BASE DE DATOS*/
///////////////////////////////////////////////////////////


$(".fa-qrcode.codigoQr").popover({
	title:'',
	content:'<b>Generar QR de la última orden</b>',
	html:true,
	trigger:'hover',
	placement:'top'
})

$(".fa-file-alt.ordenPdf").popover({
	title:'',
	content:'<b>Generar PDF de la última orden</b>',
	html:true,
	trigger:'hover',
	placement:'top'
})



/**********************************************************/
/***SCRIPT PARA GENERAR EL QR Y EL PDF DESDE LAS ORDENES***/
/**********************************************************/

//LA VARIABLE idOrden SE CREO EN EL ARCHIVO orden.php HASTA EL FINAL DEL DOCUMENTO

function generarQr(idOrden, idHistOrd){
	var datos = {
		"id":idOrden,
		"idHistOrd":idHistOrd
	}

	$.ajax({
		data:datos,
		type:"POST",
		url:url+"vistas/asset/ajax/generarQr.ajax.php",
		success:function(respuesta){
			console.log(respuesta);

			window.open(url+"vistas/asset/images/qr/"+respuesta);
		}
	})
}


function generarPdf(idOrden, idHistOrd){
	var datos = {
		"id":idOrden,
		"idHistOrd":idHistOrd
	}

	$.ajax({
		data:datos,
		type:"POST",
		url:url+"vistas/asset/ajax/generarPdf.ajax.php",
		success:function(respuesta){
			console.log(respuesta);

			window.open(url+"vistas/asset/pdf/"+respuesta);
		}
	})

	$.ajax({
		data:datos,
		type:"POST",
		url:url+"vistas/asset/ajax/datosTicket.ajax.php",
		success:function(respuesta){

			var datos = JSON.parse(respuesta);

			console.log(datos);

			var datosTicket = {
				"folio":datos[0],
				"paciente":datos[1],
				"trabajo":datos[2],
				"dientes":datos[3],
				"colorimetria":datos[4],
				"recoleccion":datos[5],
				"entrega":datos[6],
				"indicaciones":datos[7],
				"estado":datos[8],
				"tipo":datos[9]
			}

			$.ajax({
				data:datosTicket,
				type:"POST",
				url:"http://localhost/imprimir.php",
				dataType:'jsonp',
				success: function(respuesta){

					console.log(respuesta);

				}
			})

		}
	})

	
}

$(".nombreTrabajoOrden").on("click",".codigoQr",function(){
	var idHistOrd = $(this).attr("data-qr");
	generarQr(idOrden,idHistOrd);
});

$(".nombreTrabajoOrden").on("click",".ordenPdf",function(){
	var idHistOrd = $(this).attr("data-pdf");
	generarPdf(idOrden,idHistOrd);
});

$("#v-resumen").on("click",".codigoQr",function(){
	var idHistOrd = $(this).attr("data-qr");
	generarQr(idOrden,idHistOrd);

});

$("#v-ordenes").on("click",".codigoQr",function(){
	var idHistOrd = $(this).attr("data-qr");
	generarQr(idOrden,idHistOrd);

});

$("#v-resumen").on("click",".ordenPdf",function(){
	var idHistOrd = $(this).attr("data-pdf");
	generarPdf(idOrden,idHistOrd);

});

$("#v-ordenes").on("click",".ordenPdf",function(){
	var idHistOrd = $(this).attr("data-pdf");
	generarPdf(idOrden,idHistOrd);

});


/*ORDENAR TABLA DE ORDENES*/

$(".tablaOrdenes").tableSorter();
/***************************************/
//////////BUSCADOR DE ORDENES///////////
/***************************************/

$('#buscarOrden').each(function() {
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

				$("#pills-busquedaUsuario").tab('show');
				//colocar la funcion para buscar en la BD

				var datos = {
					"busquedaUsuario":busqueda,
					"url":url
				}

				$.ajax({
					type:"POST",
					url:url+"vistas/asset/ajax/buscarOrden.ajax.php",
					data:datos,
					beforeSend:function(){

						$(".pills-busquedaContenedor").html('<div class="text-center"><img width="120px;" src="'+url+'vistas/asset/images/cargando.gif" alt="cargando..." /><br /><p class="bold text-muted">Cargando...</p></div>');

					},
					success:function(respuesta){

						console.log(respuesta);

						$(".pills-busquedaContenedor").html(respuesta);

					}
				})

				
			}

			//// end action
		}
	});
});


//////////////////////////////////////////
//////////////////////////////////////////


/***CÓDIGO PARA ENVIAR MENSAJES EN EL CHAT***/

function enviarMensaje(){

	var mensaje = $(".mensajeChat");
	var mensajeEscrito = mensaje.val();

	if(mensajeEscrito.length >= 1){

		var idOrden = $("#idOrdenDetalles").val();

		var datos = {
			"idOrden":idOrden,
			"mensaje":mensajeEscrito,
			"url":url
		}


		$.ajax({
			type:"POST",
			url:url+"vistas/asset/ajax/enviarMensajeOrden.ajax.php",
			data:datos,
			context:this,
			success:function(respuesta){

				console.log(respuesta);

				let datos = JSON.parse(respuesta);

				let msg = datos['mensaje'];
				let perfil = datos['fotoPerfil'];
				let fecha = datos['fechaActual'].split(" ");
				let nombre = datos['nombre'];
				
				if(!respuesta){

					swal({
						title:"Ocurrió un error",
						text:"Inténtalo de nuevo mas tarde",
						type:"error"
					})
					
				} else {

					let mensajeHtml = 

					'<li class="emisor">'
						+'<div class="mensaje">'
							
							+'<div class="contenedor">'
								+'<p> '+msg+' </p>'
								+'<div class="fechaMensaje txtPequeño">'+fecha[0]+'</div>'
							+'</div>'										
							
							+'<div class="foto">'
								+'<img src="'+url+'vistas/asset/images/'+perfil+'" alt="'+nombre+'" class="foto">'
							+'</div>'

						+'</div>'
					+'</li>';

					$(".conversacion").append(mensajeHtml);
					$(".conversacion").scrollTop(50000);
					mensaje.val('');

					///QUITAMOS LA ILUSTRACIÓN DE INSTRUCCIONES

					$(".sinMensajes").remove();

				}
				
			}
		})



	} else {

		swal({
			title:"Primero escribe un mensaje",
			text:"",
			type:"error",
			timer: 1000
		})

	}

}

function sendMessage(e){

	if(e.keyCode === 13 && !e.shiftKey){
		e.preventDefault();
		enviarMensaje();
	}

}

$(".btnEnviarMensaje").click(function(){
	enviarMensaje();
})

function cargarMensajeNuevoPush(){

	var idOrden = "";

	idOrden += $("#idOrdenDetalles").val();

	var datos = {
		"timestamp":timestamp,
		"idOrden":idOrden
	};

	//console.log(datos);

	marcarMensajesVistos();
	
	$.ajax({
		data: datos,
		url: url+"vistas/asset/ajax/obtenerNuevoMensajeOrden.ajax.php",
		type: "POST",
		success: function(respuesta){
			//console.log(respuesta);
			if(respuesta != 0){

				var nuevoMensaje = JSON.parse(respuesta);
				timestamp = nuevoMensaje['fecha'];
			 	
			 	let msg = nuevoMensaje['mensaje'];
				let perfil = nuevoMensaje['fotoPerfil'];
				let fecha = nuevoMensaje['fecha'].split(" ");
				let nombre = nuevoMensaje['nombre'];

				if( nombre != null && perfil != null ){ //SI EXISTE UN NOMBRE Y UNA FOTO DE PERFIL, SIGNIFICA QUE EL NUEVO MENSAJE VIENE DEL RECEPTOR

					let mensajeHtml = 

					'<li class="receptor">'
						+'<div class="mensaje">'
							
							+'<div class="foto">'
								+'<img src="'+url+'vistas/asset/images/'+perfil+'" alt="'+nombre+'" class="foto">'
							+'</div>'
							
							+'<div class="contenedor">'
								+'<p> '+msg+' </p>'
								+'<div class="fechaMensaje txtPequeño">'+fecha[0]+'</div>'
							+'</div>'																

						+'</div>'
					+'</li>';

					$(".conversacion").append(mensajeHtml);
					$(".conversacion").scrollTop(50000);

					///QUITAMOS LA ILUSTRACIÓN DE INSTRUCCIONES

					$(".sinMensajes").remove();

				}
				
			} 

			idTimeout = setTimeout('cargarMensajeNuevoPush()',2000);
		}
	});
}

var timestamp = '';
var idTimeout;

function ultimoMensajeOrden(){

	var idOrden = $("#idOrdenDetalles").val();

	var datos = {
		"idOrden":idOrden
	}

	$.ajax({
		type:"POST",
		url:url+"vistas/asset/ajax/ultimoMensajeOrden.ajax.php",
		data:datos,
		success:function(respuesta){
			timestamp = respuesta;
			cargarMensajeNuevoPush();
		}
	});

}

$("#chat").click(cargarMensajesChat());

function cargarMensajesChat(){

	listaOrdenes("chat","conversacion");

	ultimoMensajeOrden(); //FUNCION PARA CARGAR LOS MENSAJES DEL CHAT EN TIEMPO REAL 

	$(".numeroMensajesNoLeidos").html('');

	marcarMensajesVistos();

}

//Número de mensajes no leídos notificación

function marcarMensajesVistos(){
	
	let datos = {
		"idOrden":$("#idOrdenDetalles").val()
	}

	$.ajax({
		type:"POST",
		url:url+"vistas/asset/ajax/marcarMensajesComoLeido.ajax.php",
		data:datos,
		success:function(respuesta){

		}
	})
}

function obtenerNumeroMensajesNoLeidos(){

	if($("#chat").hasClass("active")){
		//console.log("mensajes vistos");
		marcarMensajesVistos();

	} else {

		var datos = {
			"idOrden":$("#idOrdenDetalles").val()
		}

		$.ajax({
			type:"POST",
			url:url+"vistas/asset/ajax/numeroMensajesNoLeidos.ajax.php",
			data:datos,
			success:function(respuesta){

				//console.log(respuesta);

				if(respuesta != 0){
					$(".numeroMensajesNoLeidos").html(respuesta); //RETORNA EL NUMERO DE MENSAJES NO LEÍDOS
				}

				setTimeout('obtenerNumeroMensajesNoLeidos()',2000);

			}
		})

	}


}

$(document).ready(function(){

	let paginaActual = $("#paginaActual").val();

	if( paginaActual == "orden" ){

		obtenerNumeroMensajesNoLeidos();

	}

	var filtroOrden = getParameterByName("filtroOrden");

	switch(filtroOrden){

		case 'chat':

			$('.listaOrden a[href="#v-chat"]').tab('show');
			cargarMensajesChat();

			break;

		case 'ordenes':

			$('.listaOrden a[href="#v-ordenes"]').tab('show');

			break;

		case 'pagos':

			$('.listaOrden a[href="#v-pagos"]').tab('show');

			break;

		case 'fotos':

			$('.listaOrden a[href="#v-fotos"]').tab('show');

			break;

		default:

			break;

	}

})