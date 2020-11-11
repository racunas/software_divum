$(".btnListaTrabajos1").click(function(){
	
	var formulario = $("#formListaTrabajos");
	
	var box = $("input[type='checkbox']");
	var seleccionado = false;

	console.log(box.length);

	for (var i = 0; i < box.length; i++) {
		
		if(box[i].checked){
			seleccionado = true;
		}

	}

	if(seleccionado){

		formulario.submit();

	} else if(box.length == 0) {

		location.href = url+"lista-trabajos";

	} else{

		swal({
		  type: 'error',
		  title: 'Selecciona al menos un trabajo',
		  confirmButtonColor: "#9ac76d"
		});

	}


});


$(".btnListaTrabajos2").click(function(){

	var formulario = $("#formListaPrecios");
	var inputs = $("#formListaPrecios input[type='text']");
	var vacio = false;
	var precioMinimo = false;

	console.log(inputs.length);

	for(var i = 0; i < inputs.length; i++){
		console.log(inputs[i].value);
		if(inputs[i].value.length <= 0){
			vacio = true;
		}
	}

	for(var i = 0; i < inputs.length; i++){
		console.log(inputs[i].value);
		if(inputs[i].value < 100){
			precioMinimo = true;
		}
	}

	if(vacio){

		swal({
		  type: 'error',
		  title: 'Llena todos los precios de tus trabajos',
		  confirmButtonColor: "#9ac76d"
		});

	} else if(precioMinimo){

		swal({
		  type: 'error',
		  title: 'El precio mínimo es de $100 por trabajo',
		  confirmButtonColor: "#9ac76d"
		});

	} else {
	
		formulario.submit();

	}

});


$(".btnListaTrabajos3").click(function(){

	var formulario = $("#formDatosGenerales");
	var tipoTrabajo = $(".tipoTrabajo").val();

	var datos  = [];
	var objeto = {};

	if($("#checkUrgente").prop("checked")){

		for(var i= 0; i < listaPrecios.length; i++) {

		    var precio = listaPrecios[i];
		    var idProtesis = listaUsuario[i];

		   	datos.push({ 
		        "idProtesis"    : idProtesis,
		        "precio"  : precio,
		        "prueba"    : $("#prueba").val(),
		        "terminado" : $("#terminado").val(),
		        "porcientoAdelanto" : $("#porcientoAdelanto").val(),
		        "terminadoUrgente" : $("#terminadoUrgente").val(),
		        "porcientoAdelantoUrgente" : $("#porcientoAdelantoUrgente").val(),
		        "tipoTrabajo":tipoTrabajo
		    });

		}
		
		objeto[i] = datos;

	} else {

		for(var i= 0; i < listaPrecios.length; i++) {

		    var precio = listaPrecios[i];
		    var idProtesis = listaUsuario[i];

		   	datos.push({ 
		        "idProtesis"    : idProtesis,
		        "precio"  : precio,
		        "prueba"    : $("#prueba").val(),
		        "terminado" : $("#terminado").val(),
		        "porcientoAdelanto" : $("#porcientoAdelanto").val(),
		        "tipoTrabajo":tipoTrabajo
		    });

		}
		
		objeto[i] = datos;

	}

	console.log(objeto);

	$.ajax({

		type:"post",
		url:url+"vistas/asset/ajax/datosGeneralesLista.php",
		data:objeto,
		beforeSend:function(){
			$(".btnListaTrabajos3").html('Publicando... <i class="fas fa-undo-alt fa-spin"></i>');
			$(".btnListaTrabajos3").prop('disabled',true);
		},
		success: function(respuesta){

			$(".btnListaTrabajos3").html('Finalizar <i class="fas fa-check-circle ml-2"></i>');

			console.log(respuesta);

			if (respuesta == "1"){

				swal({
	              type: "success",
	              title: "¡Enhorabuena! Haz subido tu lista de precios",
	              text: "Ya se encuentran disponibles en Buscalab",
	              confirmButtonText: "<i class='fas fa-check'></i>&nbsp;&nbsp;Entendido",
	              confirmButtonColor: "#9ac76d"
	            }).then((result)=>{
	              
	              if(result.value){
	                window.location.href=url+"trabajos";
	              }

	            });	

			} else {

				swal({
	              type: "error",
	              title: "Algo salió mal",
	              text: "Vuelve a realizar el proceso desde el inicio",
	              confirmButtonText: "<i class='fas fa-check'></i>&nbsp;&nbsp;Entendido",
	              confirmButtonColor: "#9ac76d",
	              cancelButtonText: "Salir",
	              showCancelButton: true
	            }).then((result)=>{
	              
	              if(result.value){
	                window.location.href=url+"lista-trabajos";
	              } else {
	              	window.location.href=url+"perfil";
	              }

	            });	

			}
         

		}

	})

});

//POPOVER DE INFORMACION PRECIO, TRABAJO DENTAL Y DEL MÓDULO DE URGENTE

$(".infoTrabajo").popover({
	"html":true,
	"placement":"top",
	"content":"Es el trabajo que realizarás",
	"trigger":"hover"
});

$(".infoPrecio").popover({
	"html":true,
	"placement":"top",
	"content":"Es el precio de tu trabajo, se aumenta antes de publicarlo en Buscalab",
	"trigger":"hover"
});

$(".infoPrueba").popover({
	"html": true,
	"placement":"top",
	"content":"Tiempo que tardas en hacer un trabajo en estado de prueba",
	"trigger":"hover"
})

$(".infoTerminado").popover({
	"html": true,
	"placement":"top",
	"content":"Tiempo que tardas para finalizar el trabajo",
	"trigger":"hover"
})

$(".infoPorcientoAdelanto").popover({
	"html": true,
	"placement":"top",
	"content":"Porcentaje del precio que necesitas para comenzar un trabajo",
	"trigger":"hover"
})

$(".infoTerminadoUrgente").popover({
	"html": true,
	"placement":"top",
	"content":"Tiempo que tardas en finalizar un trabajo urgente",
	"trigger":"hover"
})

$(".infoPorcientoAdelantoUrgente").popover({
	"html": true,
	"placement":"top",
	"content":"Porcentaje de aumento que se aplicará sobre el precio original",
	"trigger":"hover"
})


//SCRIPT PARA LOS TRABAJOS URGENTES

$("#checkUrgente").click(function(){

	if($(this).is(":checked")){
		
		$(".trabajosUrgentes").removeClass("hidden");
		$("#terminadoUrgente").prop("disabled",false);
		$("#porcientoAdelantoUrgente").prop("disabled",false);

	} else {
		
		$(".trabajosUrgentes").addClass("hidden");
		$("#terminadoUrgente").prop("disabled",true);
		$("#porcientoAdelantoUrgente").prop("disabled",true);

	}

});


//SCRIPT PARA EL MODULO DE INFORMACION DEL LABORATORIO EN LISTA TRABAJOS

$(".btnInfoAdicional").click(function(){

	var nombreLaboratorio = $(".nombreLaboratorio").val();
	var cpLaboratorio = $(".cpLaboratorio").val();
	var coloniaLaboratorio = $(".coloniaLaboratorio").val();
	var calleLaboratorio = $(".calleLaboratorio").val();

	if( nombreLaboratorio.length >= 1 &&
		cpLaboratorio.length <= 5 &&
		coloniaLaboratorio.length >= 1 &&
		calleLaboratorio.length >= 1){

		var datos = {
			"nombreLaboratorio":nombreLaboratorio,
			"cpLaboratorio":cpLaboratorio,
			"coloniaLaboratorio":coloniaLaboratorio,
			"calleLaboratorio":calleLaboratorio
		}

		console.log(datos);

		$.ajax({

			type:"POST",
			data:datos,
			url:url+"vistas/asset/ajax/listaTrabajosInfoLaboratorio.ajax.php",
			context:this,
			beforeSend: function(){

				$(this).prop("disabled",true);
				$(this).html('<i class="fas fa-undo-alt fa-spin"></i>');

			},success:function(respuesta){

				if(respuesta){

					location.reload();

				} else {

					swal(
						'Ocurrió un error interno',
						'Repórtalo a Buscalab',
						'rerro'
					)

				}

			}

		})

	} else {

		swal(
			'Verifica todos los campos',
			'',
			'error'
		)

	}

});


$(".trabajoProtesis").click(function(){

	$("#formTipoProtesis").submit();

})

$(".trabajoOrtodoncia").click(function(){

	$("#formTipoOrtodoncia").submit();

})