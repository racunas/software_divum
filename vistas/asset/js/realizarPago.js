
$(".btnPagarOrden").click(function(){

	if($(".radioPago input[type=radio]:checked").val() == "mercadopago"){

		location.href = urlMercadopago;

	} else if($(".radioPago input[type=radio]:checked").val() == "paypal") {

		location.href = urlPaypal;

	} else {

		swal({
		  type: 'error',
		  title: 'Selecciona un método de pago',
		  confirmButtonText: 'Ok',
		  confirmButtonColor: '#9ac76d'
		});

	}


})


////CODIGO PARA MANIPULAR LOS CLIC EN TARETAS, DEPOSITOS O TRANSFERENCIAS

$(".formaPago").click(function(){


	var tipoPago = $(this).attr("data-type");
	var idBox = $(this).attr("data-box");

	if(tipoPago != ""){

		var datos = {
			"tipoPago":tipoPago,
			"idBox":idBox
		}

		if(tipoPago == 'divum'){

			$.ajax({
				data:datos,
				type:"POST",
				url:url+"vistas/asset/ajax/generarOrdenDivum.ajax.php",
				success:function(respuesta){

					console.log(respuesta);

					if(respuesta == "ok"){

						swal({
						  type: 'success',
						  title: 'Tu orden de laboratorio se ha enviado exitosamente',
						  confirmButtonText: 'Ok',
						  confirmButtonColor: '#9ac76d'
						}).then((result)=>{
							if(result.value){
								window.location.href = url+"ordenes";
							}
						})

					} else {

						swal({
						  type: 'error',
						  title: 'Ocurrió un error, consulta al administrador',
						  confirmButtonText: 'Ok',
						  confirmButtonColor: '#9ac76d'
						}).then((result)=>{
							if(result.value){
								window.location.reload();
							}
						})
						

					}


				}
			})

		} else if(tipoPago == "paypal"){

			location.href = urlPaypal;

		} else {

			/*console.log(datos);

			$.ajax({

				data:datos,
				type:"POST",
				url:url+"vistas/asset/ajax/obtenerEnlacePagoMercadopago.ajax.php",
				beforeSend: function(){
					//$(".formaPago").attr("data-type",""); //Quitamos el tipo de pago, para que cuando de clic, no se ejecute ésta instrucción
				},
				success: function(respuesta){

					console.log(respuesta);

					if(respuesta != false){

						//console.log(respuesta);

						location.href = respuesta;

					}

				}

			});*/
			
		}


	}



})