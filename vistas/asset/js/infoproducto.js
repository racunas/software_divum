var forbidden=['2019-04-15','2019-04-16','2019-04-17','2019-04-18','2019-04-19','2019-04-20','2019-12-21'];

$('.datepicker').datepicker({
    format: 'yyyy-mm-dd',
    startDate: '+1d',
    language: 'es',
    autoclose: true,
    daysOfWeekDisabled: [0],
    beforeShowDay:function(Date){
        var curr_date = Date.toJSON().substring(0,10);

        if (forbidden.indexOf(curr_date)>-1) return false;        
    }
});

$("#fechaEntrega").click(function(){

	$('.datepicker').datepicker("show");

});

$(".btnAgregarDireccion").click(function(){

	if( $("#calle").val().length >= 1 &&
		$("#colonia").val() != null &&
		$("#codigoPostal").val().length >= 1 )  {

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

					swal({
			  			title:'Ocurrió un error',
			  			text:'Recarga la página y vuelve a intentarlo',
			  			type:'error',
			  			confirmButtonText:'Entendido',
			  			confirmButtonColor: '#9ac76d',
			  			showConfirmButton: false,
			  			timer: 1500
			  		})
  				
  				} else {
  					
  					$("#direccionRecepcion").append('<option value="'+respuesta+'">'+calle+'</option>');
  					$("#direccionEntrega").append('<option value="'+respuesta+'">'+calle+'</option>');

  					$("#direccionRecepcion").val(respuesta);
  					$("#direccionEntrega").val(respuesta);

	  				swal({
						title: 'Dirección añadida',
						text: "Ya puedes enviar órdenes a esa dirección",
						type: 'success',
						confirmButtonColor: '#9ac76d',
						confirmButtonText: 'Entendido!',
						showConfirmButton:false,
						timer:2000
					}).then((result) => {
						$("#modalAgregarDireccion").modal("hide");
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
  			confirmButtonColor: '#9ac76d',
  			showConfirmButton: false,
  			timer: 2000
  		})

	}

})

function agregarDireccionPreorden(){

	if( $(this).val() == "nueva" ){

		$("#modalAgregarDireccion").modal("show");
		$("#direccionRecepcion").val(null);
  		$("#direccionEntrega").val(null);

	}
	
}

function calcularFechaEntrega(){

	var tipoOrden = $("input[name='tipoOrden']:checked").val();
	var hoy = new Date( $("#fechaRecepcion").val() );
	var i=0;

	if(tipoOrden == 'urgente'){

		while (i<=tiempoEntregaUrgente) {
		  hoy.setTime(hoy.getTime()+24*60*60*1000); // añadimos 1 día
		  if (hoy.getDay() != 6 && hoy.getDay() != 0 ) 
		    i++;  
		}

	} else if(tipoOrden == 'ordinario'){

		while (i<=tiempoEntrega) {
		  hoy.setTime(hoy.getTime()+24*60*60*1000); // añadimos 1 día
		  if (hoy.getDay() != 6 && hoy.getDay() != 0 )
		    i++;
		}
		
	}
	
	mes = hoy.getMonth()+1;
	if (mes<10) mes = '0'+mes;
	fecha = hoy.getFullYear()+ '-' + mes + '-' + hoy.getDate();


	if(fecha != "NaN-NaN-NaN"){
		$("#fechaEntrega").val(fecha);
		$(".fechaEntrega").removeClass("txtMediano");
		$(".horaEntrega").addClass("separacionGrande");
	}

}

$("#fechaRecepcion").change(calcularFechaEntrega);

$(".icon-archivo").click(function(){

	$(".icon-archivo").popover('hide');

});

$("input").change(function(){ //FUNCIÓN PARA REMOVER LOS ERRORES DEL INPUT
	$(this).removeClass("is-invalid");
});

$("select").change(function(){ //FUNCION PARA REMOVER LOS ERRORES DE LOS SELECT
	$(this).removeClass("is-invalid");
});

$(".btnOrdenarAhora").click(function(e){

	var estadoTrabajo = $("input[name='estadoTrabajo']:checked").val();

	var numSeleccionDientes = 0;

	var tipoTrabajo = $("input#tipoTrabajo").val();

	//console.log(tipoTrabajo);

	if( $("input[name='nombrePaciente']").val().length == 0 ){

		console.log("falta nombre paciente");

		swal({
		  type: 'error',
		  title: 'Introduce el nombre de tu paciente',
		  timer:1800,
		  showConfirmButton: false
		});

		$("input[name='nombrePaciente']").addClass("is-invalid");

	} else if( $("#fechaRecepcion").val().length == 0 ){

		console.log("falta fecha recepcion");

		swal({
		  type: 'error',
		  title: 'Selecciona una fecha de recepción',
		  timer:1800,
		  showConfirmButton: false
		});

		$("#fechaRecepcion").addClass("is-invalid");

		e.preventDefault();

	} else if(  $("#direccionRecepcion").val().length == 0 ||
				$("#direccionEntrega").val().length == 0){

		console.log("falta seleccionar una direccion de entrega o recepcion");

		swal({
		  type: 'error',
		  title: 'Selecciona o añade una dirección de entrega/recepcion',
		  timer:1800,
		  showConfirmButton: false
		});

		($("#direccionRecepcion").val().length == 0) ? $("#direccionRecepcion").addClass("is-invalid") : '';
		($("#direccionEntrega").val().length == 0) ? $("#direccionEntrega").addClass("is-invalid") : '';

	} else if(  estadoTrabajo != 1 && estadoTrabajo != 2 ) {

		console.log("falta seleccionar un estado"+$("input[name='estadoTrabajo']:checked").val());

		swal({
		  type: 'error',
		  title: 'Selecciona el estado de tu trabajo',
		  timer:1800,
		  showConfirmButton: false
		});

		e.preventDefault();

	} else if( ($("#colorimetria").val() == "") &&
				tipoTrabajo == 'protesis' ){

		swal({
		  type: 'error',
		  title: 'Selecciona una colorimetria',
		  timer:1800,
		  showConfirmButton: false
		});

		$("#colorimetria").addClass("is-invalid");

	}else if( !($(".diente").hasClass("selected")) &&
				tipoTrabajo == 'protesis' ){

		console.log("falta agregar un diente");

		/*swal({
		  type: 'error',
		  title: 'Selecciona al menos 1 diente del odontograma',
		  timer:1800,
		  showConfirmButton: false
		});*/

		e.preventDefault();

		$("#modalOdontograma").modal("show");

	} else if( $("#descripcion").val().length == 0 ) { //SÍ NO HA SELECCIONADO UN DIENTE, NO MANDAR EL FORMULARIO

		console.log("falta agregar una descripción");

		e.preventDefault();

		swal({
		  type: 'error',
		  title: 'Escribe mas detalles sobre el trabajo',
		  timer:1800,
		  showConfirmButton: false
		});

		$("#descripcion").addClass("is-invalid");

		$("#modalOdontograma").modal("show");

	} else {

		$("#pagoInicial").prop("disabled",false); //QUITARLO DE DESHABILITADO EN CASO DE QUE NI SE HAYA SELECCIONADO COMO TERMINADO EL TRABAJO

		var dientesSeleccionados = "";

		for (var i = 11; i <= 48; i++) {


			if($(".diente[data-num='"+i+"']").hasClass("selected")){

				numSeleccionDientes++;

				console.log($(".diente[data-num='"+i+"']").attr("data-num"));

				dientesSeleccionados += $(".diente[data-num='"+i+"']").attr("data-num")+",";

			}

		}

		$(".dientesSeleccionados").val(dientesSeleccionados);
		$(".cantidadTrabajos").val(numSeleccionDientes);

	}


});

$("#direccionRecepcion").change(agregarDireccionPreorden);

$("#direccionEntrega").change(agregarDireccionPreorden);


$(".btnRegistroPreOrden").click(function(e){
	e.preventDefault();

	$("#modalRegistro").modal('show');
})

$("input[name='estadoTrabajo']").change(function(){

	if( $(this).val() == 1 ){

		$("#pagoInicial").val('1');
		$("#pagoInicial").prop("disabled",true);

	} else {

		$("#pagoInicial").prop("disabled",false);

	}
	
});


/**FUNCIONALIDAD DEL ODONTOGRAMA**/

$(".diente").click(function(){

	var numDiente = $(this).attr("data-num");

	var numSeleccionDientes = 0;

	if( $(this).hasClass("selected") ){

		//SÍ ESTÁ SELECCIONADO, LO DESELECCIONAMOS

		$(this).removeClass("selected");

	} else {

		$(this).addClass("selected");
		
	}

	for (var i = 11; i <= 48; i++) {


		if($(".diente[data-num='"+i+"']").hasClass("selected")){

			numSeleccionDientes++;

			console.log($(".diente[data-num='"+i+"']").attr("data-num"));

		}

	}

	console.log(numSeleccionDientes);

	if(numSeleccionDientes > 0){

		var totalPrecioOrden = ($("input[name='tipoOrden']:checked").val() == 'urgente' ) ? numSeleccionDientes * precioTotalUrgente : numSeleccionDientes * precioTotal;
	
		//var totalPrecioOrden = numSeleccionDientes * precioTotal;

		$(".precioTotal").html("$ "+totalPrecioOrden);
		
	}



});

$(".fa-paperclip").click(function(){

	$("#modalOdontograma").modal("show");

});


/*VERIFICAR QUE ES TRABAJO URGENTE U ORDINARIO, PARA MODIFICAR LOS PRECIOS DE LA PREORDEN*/

function verificarTipoOrden(){

	if( $(this).val() == 'urgente' ){

		var precio = $(".precioUrgente").val();
		var tiempoEntrega = $(".tiempoUrgente").val();

		//console.log("urgentes: "+precio+" "+tiempoEntrega);

		calcularFechaEntrega();

	} else if( $(this).val() == 'ordinario' ){

		var precio = $(".precioOrdinario").val();
		var tiempoEntrega = $(".tiempoOrdinario").val();

		//console.log("ordinario: "+precio+" "+tiempoEntrega);

		calcularFechaEntrega();

	}

	var porcientoLaboratorio = $(".porcentajeInicial").val();
	var porciento = porcientoLaboratorio * 10;
	var precioTotal, precioParcial, porcientoTotal, optionsPrecios = '';
	//$min = $porciento * 10;

	console.log(porciento);

	if(porciento == 10){
		
		//IMPRIMIMOS QUE ES 100% CUANDO SEA TRABAJO URGENTE O PIDA EL 100%
		/*$('#mySelect').append($('<option>', {
		    value: 1,
		    text: 'My option'
		}));*/
		optionsPrecios +=  '<option value="1" selected>$'+precio+' (100%)</option>';

	} else {

		var porcientoSelect = porciento;

		while(porciento<=10){

			precioTotal = precio;
			precioParcial = (precioTotal * porciento) / 10 ;
			porcientoTotal = porciento*10;

			var seleccionarTodoElPago = (porciento == 10) ? 'selected' : '';
			
			optionsPrecios += '<option value="'+(porciento/10)+'" '+seleccionarTodoElPago+'>$'+precioParcial+' por unidad ('+porcientoTotal+'%)</option>';
		

			if( (++porciento) > 10 ){

				--porciento;
				porciento = porciento + 0.5;

			}

		}

	}


	$(".precioTipoTrabajo").html(precio);
	$(".diasEntrega").html(tiempoEntrega);
	$("#pagoInicial").html(optionsPrecios);

	console.log(optionsPrecios);

}

$("input[name='tipoOrden']").click(verificarTipoOrden);