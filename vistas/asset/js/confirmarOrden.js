var paginaActual = $("#title").val();

if(paginaActual=='Confirma tu orden - Buscalab'){

	$(window).scroll(function(){

		if($( window ).width() <= 767){

			var windowHeight = $(window).scrollTop();
			var alturaDelLogo = $(".tituloConfirmarDetalles").offset().top;

			if(windowHeight >= alturaDelLogo){
				$("#botonesMovilesConfirmarOrden").fadeIn();
			}else{
				$("#botonesMovilesConfirmarOrden").fadeOut();
			}
			
		}


	})

}

