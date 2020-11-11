$(document).ready(function(){

	var filtroPrecioMinimo = getParameterByName("filtroPrecioMinimo");
	var filtroPrecioMaximo = getParameterByName("filtroPrecioMaximo");
	var filtroEstado = getParameterByName("filtroEstado");
	var filtroEntrega = getParameterByName("filtroEntrega");
	//var filtroMunicipio = getParameterByName("filtroMunicipio");

	(filtroPrecioMinimo.length >= 1) ? $("#filtroPrecioMinimo").val(filtroPrecioMinimo) : "";
	(filtroPrecioMaximo.length >= 1) ? $("#filtroPrecioMaximo").val(filtroPrecioMaximo) : "";
	(filtroEntrega.length >= 1) ? $("#filtroEntrega").val(filtroEntrega) : "";
	(filtroEstado.length >= 1) ? $("#filtroEstado").val(filtroEstado) : "";
	(filtroEstado.length >= 1) ? cargarMunicipios() : "";

	//(filtroMunicipio.length >= 1) ? $("#filtroMunicipio").prop("disabled",false) : "";
	//(filtroMunicipio.length >= 1) ? $("#filtroMunicipio").val(filtroMunicipio) : "";

})

function cargarMunicipios(){

	var idEstado = $("#filtroEstado").val();

	console.log(idEstado);

	if(idEstado.length >= 1){

		$.ajax({
			type:"POST",
			url:url+"vistas/asset/ajax/cargarMunicipios.ajax.php",
			data:{"idEstado":idEstado},
			success:function(respuesta){


				//console.log(respuesta);

				var datos = JSON.parse(respuesta);
				var htmlOptions = "<option value=''>Municipio/Delegacion</option>";

				$.each(datos, function(index, value){

					htmlOptions += '<option value="'+value["idMunicipio"]+'">'+value["municipio"]+'</option>';

				})

				$("#filtroMunicipio").prop("disabled",false);
				$("#filtroMunicipio").html(htmlOptions);

				var filtroMunicipio = getParameterByName("filtroMunicipio");
				(filtroMunicipio.length >= 1) ? $("#filtroMunicipio").prop("disabled",false) : "";
				(filtroMunicipio.length >= 1) ? $("#filtroMunicipio").val(filtroMunicipio) : "";

			}
		})
		
	}


}

$("#filtroEstado").change(cargarMunicipios);

$(".btnMostrarFiltros").click(function(){

	if( $(".filtrosDeBusqueda").hasClass("ocultarFiltrosDeBusqueda") ){

		$(".filtrosDeBusqueda").fadeIn();
		$(".filtrosDeBusqueda").removeClass("ocultarFiltrosDeBusqueda");

	} else {
		
		$(".filtrosDeBusqueda").fadeOut();
		$(".filtrosDeBusqueda").addClass("ocultarFiltrosDeBusqueda");

	}

})
	