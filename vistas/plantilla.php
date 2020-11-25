<!DOCTYPE html>
<html lang="es">
<head>

	<?php 

	//OBTENIENDO RUTA GENERAL 

	$url = ruta::obtenerRuta();

	$rutasCss = (isset($_GET['ruta'])) ? explode("/", $_GET['ruta']) : NULL;

	 ?>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1">
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
	<meta name="generator" content="2018.0.0.379"/>
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="expires" content="0">
	<link rel="manifest" href="<?php echo $url; ?>manifest.json">
	
	<!--FAVICON-->
	<link rel="shortcut icon" href="<?php echo $url; ?>vistas/asset/images/favicon.png" type="image/x-icon">
	<link rel="icon" href="<?php echo $url; ?>vistas/asset/images/favicon.png" type="image/x-icon">

	<title>DIVUM | Buscador de laboratorios dentales</title>
	
	<!--BOOTSTRAP-->
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/bootstrap.min.css">
	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/css/bootstrap.min.css">-->

	<!--ESTILOS PROPIOS-->
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/plantilla.css">
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/cabezote.css">
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/paginaPrincipal.css">
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/resultados.css">
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/infoproducto.css">

	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/recibo.css" >
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/confirmarOrden.css" >
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/ordenCompleta.css" >
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/notificaciones.css">
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/perfil.css" >
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/cajaOrdenes.css" >
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/realizarPago.css">
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/ordenes.css" >
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/trabajos.css">
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/orden.css">
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/odontograma.css" >
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/listaTrabajos.css" >

	<!--FUENTE PRINCIPAL QUESTRIAL-->
	<link href="<?php echo $url; ?>vistas/asset/css/questrial.css" rel="stylesheet">
	<!--<link href="https://fonts.googleapis.com/css?family=Questrial" rel="stylesheet">-->

	<!--FONTAWESOME-->
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/all.min.css">
	<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">-->

	<!--IMG AREA SELECT-->
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/imgareaselect-animated.css">
	<!--Side Nav
	<script src="<?php echo $url; ?>vistas/asset/scripts/materialize.min.js"></script>
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/materialize.min.css">-->

	<!--ZOOM (Imagenes)-->
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/toolkit-minimal.min.css">

	<!--SLICK-->
	<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>vistas/asset/css/slick.css"/>

	<!--BOOTSTRAP DATEPICKER-->
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/bootstrap-datepicker.min.css">
	
	<!--JQUERY-->
	<!--<script async src="<?php echo $url; ?>vistas/asset/scripts/jquery.min.js"></script>-->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

	<!--SWEET ALERT 2-->
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/sweetalert2.min.css">
	<script type="text/javascript" src="<?php echo $url; ?>vistas/asset/scripts/sweetalert2.all.min.js"></script>

	<!--GOOGLE MAPS-->
	<script type="text/javascript" src="<?php echo $url; ?>vistas/asset/scripts/gmaps.js"></script>
	<script charset="utf-8" src="https://maps.google.com/maps/api/js?key=AIzaSyDYVWdmbrK5NpNI_lUE3MBtQDVuKUjPh1k"></script>

	<!--TABLESORTER-->
	<script type="text/javascript" src="<?php echo $url; ?>vistas/asset/scripts/jquery.tablesorter.js"></script>

	<!--METRO NOTIFICATIONS-->
	<script type="text/javascript" src="<?php echo $url; ?>vistas/asset/scripts/MetroNotification.min.js"></script>
	<link rel="stylesheet" href="<?php echo $url; ?>vistas/asset/css/MetroNotificationStyle.min.css">

	<script type="text/javascript" async>
		
		/*FUNCION QUE RETORNA A LA PAGINA PRINCIPAL DE BSUCALAB*/

		function index(){
			window.location.href = "<?php echo $url; ?>";
		}

		/*FUNCION PARA QUE RETORNE A LA PAGINA DE ORDENES*/

		function returnOrdenes(){
			window.location.href = "<?php echo $url; ?>ordenes";
		}

	</script>


</head>
<body class="">

<?php 

	include "vistas/modulos/menuLateral.php";
	
	$rutas = array();

	if(isset($_GET['ruta'])){
		
		//SE MANDA A LLAMAR EL CABEZOTE PRINCIPAL

		include "vistas/modulos/cabezotePrincipal.php";


		//AQUÍ SE HACE EL EXPLODE DE LA RUTA AMIGABLE

		$rutas = explode("/",$_GET['ruta']);

		switch ($rutas[0]) {

			case 'resultados':

				//CODIGO PARA AGREGAR EL TITLE DE RESULTADOS
				$palabra = explode("-", $rutas[1]);
				$palabraFinal = "";
				foreach ($palabra as $key => $value) {
					$palabraFinal .= $value." ";
				}

				echo '<input type="hidden" id="title" value="DIVUM: '.ucfirst($palabraFinal).'">';
				echo '<input type="hidden" id="paginaActual" value="resultados">';
				/////////////////////////////////777

				include "vistas/modulos/resultados.php";

				//include "vistas/modulos/DIVUM/resultadosBusqueda.php";

				break;

			case 'pre-orden':

				$_laboratorio = explode("-", $rutas[1]);
				$_laboratorioFinal = "";
				foreach ($_laboratorio as $key => $value) {
					$_laboratorioFinal .= $value." ";
				}

				$_trabajo = explode("-", $rutas[2]);
				$_trabajoFinal = "";
				foreach ($_trabajo as $key => $value) {
					$_trabajoFinal .= $value." ";
				}

				echo '<input type="hidden" id="title" value="'.ucfirst($_trabajoFinal).", ".ucfirst($_laboratorioFinal).' - DIVUM">';
				echo '<input type="hidden" id="paginaActual" value="pre-orden">';

				include "vistas/modulos/infoproducto.php";

				break;

			case 'recibo':

				if(isset($_SESSION['dentista']) || isset($_SESSION['tecnico'])){

					echo '<input type="hidden" id="title" value="¡Pago exitoso! - Buscalab">';
					echo '<input type="hidden" id="paginaActual" value="recibo">';

					include "vistas/modulos/recibo.php";
				
				} else {

					//SI NO EXISTE NINGUNA SESION, LO MANDAMOS A LA PAGINA PRINCIPAL
					echo '<script>
							index();
						</script>';

				}

				break;

			case 'confirmar-orden':
				echo '<input type="hidden" id="title" value="Confirma tu orden - Buscalab">';
				echo '<input type="hidden" id="paginaActual" value="confirmar-orden">';

				include "vistas/modulos/confirmarOrden.php"; //PAGINA DE CONFIRMACION DE LA PÁGINA

				break;

			case 'orden-completa':

				echo '<input type="hidden" id="title" value="Tu orden ha sido generada - Buscalab">';
				echo '<input type="hidden" id="paginaActual" value="orden-completa">';

				include "vistas/modulos/ordenCompleta.php"; //VISTA FINAL DE LA PÁGINA

				break;

			case 'perfil':

				if(isset($_SESSION['dentista']) || isset($_SESSION['tecnico'])){

					echo '<input type="hidden" id="title" value="Mi perfil - DIVUM">';
					echo '<input type="hidden" id="paginaActual" value="perfil">';

					include "vistas/modulos/perfil.php";
				
				} else {

					//SI NO EXISTE NINGUNA SESION, LO MANDAMOS A LA PAGINA PRINCIPAL
					echo '<script>
							index();
						</script>';

				}


				break;

			case 'caja-ordenes':
				if (isset($_SESSION['dentista'])) {

					echo '<input type="hidden" id="title" value="Carrito de ordenes - Buscalab">';
					echo '<input type="hidden" id="paginaActual" value="caja-ordenes">';

					include "vistas/modulos/cajaOrdenes.php";

				}

				break;

			case 'lista-trabajos':

				if(isset($_SESSION['tecnico'])){

					echo '<input type="hidden" id="title" value="Subir mi lista de precios - DIVUM">';
					echo '<input type="hidden" id="paginaActual" value="lista-trabajos">';

					controladorListaTrabajos::ctrRegistroLista($_SESSION['tecnico']);
					
				} else {

					//SÍ NO EXISTE UNA SESION DE UN TECNICO, LO MANDAMOS A LA PÁGINA PRINCIPAL
					echo '<script>
							index();
						</script>';

				}


				break;	

			case 'trabajos':

				if(isset($_SESSION['tecnico'])){

					echo '<input type="hidden" id="title" value="Mi lista de precios - DIVUM">';
					echo '<input type="hidden" id="paginaActual" value="trabajos">';

					include "vistas/modulos/trabajos.php";
					
				} else {

					//SÍ NO EXISTE UNA SESION DE UN TECNICO, LO MANDAMOS A LA PÁGINA PRINCIPAL
					echo '<script>
							index();
						</script>';

				}

				break;

			case 'realizar-pago':
				if(isset($_SESSION['dentista'],$_POST['idBox'])){

					echo '<input type="hidden" id="title" value="Realizar pago - Buscalab">';
					echo '<input type="hidden" id="paginaActual" value="realizar-pago">';

					include "vistas/modulos/realizarPago.php";
				
				} else {

					//SI NO EXISTE NINGUNA SESION, LO MANDAMOS A LA PAGINA PRINCIPAL
					echo '<script>
							index();
						</script>';

				}
			
				break;

			case 'ordenes':
				if(isset($_SESSION['dentista']) || isset($_SESSION['tecnico'])){

					if(isset($_GET['orden'])){

						echo '<input type="hidden" id="title" value="Orden '.$_GET['orden'].' - Buscalab">';
						echo '<input type="hidden" id="paginaActual" value="orden">';

						include "vistas/modulos/orden.php";

						include "vistas/modulos/calificacionModal.php";

					} else {

						echo '<input type="hidden" id="title" value="Mis ordenes - Buscalab">';
						echo '<input type="hidden" id="paginaActual" value="ordenes">';

						include "vistas/modulos/ordenes.php";

					}

				
				} else {

					//SI NO EXISTE NINGUNA SESION, LO MANDAMOS A LA PAGINA PRINCIPAL
					echo '<script>
							index();
						</script>';

				}

				break;

			case 'restablecer-password':
				if(isset($_GET['key'])){

					include "vistas/modulos/restablecerContraseña.php";

				}

				break;

			case 'notificacion':
				/*if(isset($_SESSION['dentista']) || isset($_SESSION['tecnico'])){

					echo '<input type="hidden" id="title" value="Mis notificaciones - Buscalab">';
					echo '<input type="hidden" id="paginaActual" value="notificaciones">';

					include "vistas/modulos/notificaciones.php";
				
				} else {

					//SI NO EXISTE NINGUNA SESION, LO MANDAMOS A LA PAGINA PRINCIPAL
					echo '<script>
							index();
						</script>';

				}*/
				break;
			
			default:
				
				echo '<script>
							index();
						</script>';

				break;
		}

		
	} else {

		//include "vistas/modulos/mantenimientoCabezote.php";
	
		include "vistas/modulos/cabezote.php";

		include "vistas/modulos/paginaPrincipal.php";


	}

	//SE INCLUYEN LOS MODALES DE REGISTRO E INICIO DE SESION

	include "vistas/modulos/inicioSesion.php";

	include "vistas/modulos/registro.php";

	include "vistas/modulos/restablecerContraseñaModal.php";

	include "vistas/modulos/pie.php";
	

	if(isset($_GET['iniciarSesion'])){

		echo 

		'<script>
	
			$("#modalIniciarSesion").modal("show");

		</script>';

	} elseif(isset($_GET['registro'])){

		echo 

		'<script>
	
			$("#modalRegistro").modal("show");

		</script>';

	}

?>

	<!--BOOTSTRAP-->
	<script type="text/javascript" src="<?php echo $url; ?>vistas/asset/scripts/popper.min.js"></script>
	<script type="text/javascript" src="<?php echo $url; ?>vistas/asset/scripts/bootstrap.min.js"></script>
	<!--<link src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">-->
	<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>-->
	
	<!--ZOOM (FOTOS)-->
	<script type="text/javascript"  src="<?php echo $url; ?>vistas/asset/scripts/zoom.js"></script>
	
	<!--JS SLICK-->

	<script type="text/javascript" src="<?php echo $url; ?>vistas/asset/scripts/slick.min.js"></script>

	<!--JS BOOTSTRAP DATEPICKER-->

	<script type="text/javascript" src="<?php echo $url; ?>vistas/asset/scripts/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="<?php echo $url; ?>vistas/asset/scripts/bootstrap-datepicker.es.js"></script>
	
	<!--IMAGE AREA SELECT-->
	<script type="text/javascript" src="<?php echo $url; ?>vistas/asset/scripts/jquery.imgareaselect.js"></script>

	<!--TYPEAHEAD (SUGERENCIAS)-->
	<script type="text/javascript" src="<?php echo $url; ?>vistas/asset/scripts/typeahead.bundle.js"></script>

	<!--JS PROPIO-->

	<script>
	
		var url = <?php echo '"'.$url.'"'; ?>;

	</script>
	
	<script type="text/javascript" async src="<?php echo $url; ?>vistas/asset/js/plantilla.js"></script>
	<script type="text/javascript" async src="<?php echo $url; ?>vistas/asset/js/cabezote.js"></script>
	<script type="text/javascript" async src="<?php echo $url; ?>vistas/asset/js/usuario.js"></script>
	<script type="text/javascript" async src="<?php echo $url; ?>vistas/asset/js/cajaOrdenes.js"></script>
	<script type="text/javascript" async src="<?php echo $url; ?>vistas/asset/js/infoproducto.js"></script>
	<script type="text/javascript" async src="<?php echo $url; ?>vistas/asset/js/perfil.js"></script>
	<script type="text/javascript" async src="<?php echo $url; ?>vistas/asset/js/listaTrabajos.js"></script>
	<script type="text/javascript" async src="<?php echo $url; ?>vistas/asset/js/realizarPago.js"></script>
	<script type="text/javascript" async src="<?php echo $url; ?>vistas/asset/js/trabajos.js"></script>
	<script type="text/javascript" async src="<?php echo $url; ?>vistas/asset/js/orden.js"></script>
	<script type="text/javascript" async src="<?php echo $url; ?>vistas/asset/js/resultados.js"></script>
	<script type="text/javascript" async src="<?php echo $url; ?>vistas/asset/js/confirmarOrden.js"></script>



</body>
</html>
