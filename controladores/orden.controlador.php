<?php 

class controladorOrden{

	public static function ctrFotoPerfilOrden($idUsuario,$tipo,$idOrden){

		$respuesta = modeloOrden::mdlFotoPerfilOrden($idUsuario,$tipo,$idOrden);

		return $respuesta;

	}

	public static function ctrOrdenVista($idHistOrd){

		$respuesta = modeloOrden::mdlOrdenVista($idHistOrd);

		return $respuesta;

	}

	public static function ctrTodasLasOrdenes(){

		$respuesta = modeloOrden::mdlTodasLasOrdenes();

		return $respuesta;

	}

	public static function ctrConsultarPagoOrden($idOrden){

		$respuesta = modeloOrden::mdlConsultarPagoOrden($idOrden);

		return $respuesta;

	}

	public static function ctrCalificacionOrden($idUsuario,$es,$idOrden){

		$respuesta = modeloOrden::mdlCalificacionOrden($idUsuario,$es,$idOrden);

		return $respuesta;

	}

	public static function ctrDetallesAntesOrden($idAntesOrden){

		$respuesta = modeloOrden::mdlDetallesAntesOrden($idAntesOrden);

		return $respuesta;

	}

	public static function ctrContinuarOrden($idOrden, $pago, $etapa, $estado, $estadoPago, $fechaRecepcion, $fechaEntrega, $descripcion, $url){

		$respuesta = modeloOrden::mdlContinuarOrden($idOrden, $pago, $etapa, $estado, $estadoPago, $fechaRecepcion, $fechaEntrega, $descripcion, $url);

		return $respuesta;

	}

	public static function ctrAltaImagenes($nombreImagen,$idOrden,$idNuevaOrden){

		$respuesta = modeloOrden::mdlAltaImagenes($nombreImagen,$idOrden,$idNuevaOrden);

		return $respuesta;

	}

	public static function ctrAltaPago($idPago, $idNuevaOrden, $metodoPago, $emailPago, $nombrePago, $idCompradorPago, $paisPago){

		$respuesta = modeloOrden::mdlAltaPago($idPago, $idNuevaOrden, $metodoPago, $emailPago, $nombrePago, $idCompradorPago, $paisPago);

		return $respuesta;

	}

	public static function ctrBorrarDetallesAntesOrden($idAntesOrden){

		$respuesta = modeloOrden::mdlBorrarDetallesAntesOrden($idAntesOrden);

		return $respuesta;

	}

	public static function ctrResumenOrden($idUsuario,$tipo,$idOrden){

		$respuesta = modeloOrden::mdlResumenOrden($idUsuario,$tipo,$idOrden);

		return $respuesta;

	}

	public static function ctrRelacion($idUsuario,$tipo){

		$respuesta = modeloOrden::mdlRelacion($idUsuario,$tipo);

		return $respuesta;

	}

	public static function ctrOrdenes($idUsuario,$tipo,$filtro){

		$respuesta = modeloOrden::mdlOrdenes($idUsuario,$tipo,$filtro);

		return $respuesta;

	}

	public static function ctrOrdenesRelacion($idUsuario,$es,$idRel,$limite){

		$respuesta = modeloOrden::mdlOrdenesRelacion($idUsuario,$es,$idRel,$limite);

		return $respuesta;

	}

	public static function ctrEtapasOrden($idOrd,$es){

		$respuesta = modeloOrden::mdlEtapasOrden($idOrd,$es);

		return $respuesta;

	}

	public static function ctrInfoTrabajo($idTrabajo,$queEs){

		$respuesta = modeloOrden::mdlInfoTrabajo($idTrabajo,$queEs);

		return $respuesta;
		

	}

	public static function ctrGenerarNuevaOrden($datos){

		$respuesta = modeloOrden::mdlGenerarNuevaOrden($datos);

		return $respuesta;

	}

	public static function ctrInfoOrden($idTrabajo,$tipo,$colorimetria){

		$infoOrden = modeloOrden::mdlInfoOrden($idTrabajo,$tipo,$colorimetria);

		return $infoOrden;

	}

	public static function ctrExisteImagenes($idBox){

		$imagenes = modeloOrden::mdlExisteImagenes($idBox);

		return $imagenes;

	}

	public static function ctrExisteOrdenBox($idUsuario){

		if(isset($_POST['ordenarAhora'])){ //SÍ VIENE DESDE EL FORMULARIO DE PRE ORDEN, EJECUTAMOS ÉSTE CÓDIGO

			if(isset($_POST['colorimetria'])){ //SÍ EXISTE COLORIMETRIA, SIGNIFICA QUE ES UN TRABAJO DE PROTESIS
				$colorimetria = $_POST['colorimetria'];
			} else { //SI NO EXISTE LA COLORIMETRIA, SIGNIFICA QUE ES UN TRABAJO DE ORTODONCIA
				$colorimetria = NULL;
			}

			$idTrabajo = $_POST['idProducto'];
			$porcientoPagar = $_POST['pagoInicial'];
			$nombrePaciente = $_POST['nombrePaciente'];
			$horaRecepcion = $_POST['horaRecepcion'];
			$fechaRecepcion = $_POST['fechaRecepcion'];
			$direccionRecepcion = $_POST['direccionRecepcion'];
			$horaEntrega = $_POST['horaEntrega'];
			$fechaEntrega = $_POST['fechaEntrega'];
			$direccionEntrega = $_POST['direccionEntrega'];
			$estadoTrabajo = $_POST['estadoTrabajo'];
			$especTrabajo = $_POST['descripcion'];
			$tipoOrden = $_POST['tipoOrden']; //TIPO DE ORDEN, SI ES URGENTE U ORDINARIO
			$dientesSeleccionados = substr($_POST['dientesSeleccionados'], 0, -1); //EL ULTIMO CAMPO DEL ARRAY NO VALE, ES NULL


			//////////////////////////////AQUI SE EMPIEZA CON TODO EL PROCESO DE COMPROBACION Y OBTENCION DE INFORMACION////////////////

			$existe = modeloOrden::mdlExisteOrdenBox($idUsuario,$idTrabajo,$dientesSeleccionados,$direccionRecepcion,$direccionEntrega,$nombrePaciente,$especTrabajo,$colorimetria,$estadoTrabajo,$fechaRecepcion,$horaRecepcion,$porcientoPagar,$tipoOrden,$fechaEntrega);

			if(!$existe){ //SÍ NO EXISTE LA ORDEN EN LA BOX, LA AGREGAMOS Y OBTENEMOS SU ID DE BOX

				if (modeloOrden::mdlAgregarOrdenBox($idUsuario,$idTrabajo,$dientesSeleccionados,$direccionRecepcion,$direccionEntrega,$nombrePaciente,$especTrabajo,$colorimetria,$estadoTrabajo,$fechaRecepcion,$horaRecepcion,$porcientoPagar,$tipoOrden,$fechaEntrega) ){

					//AL INSERTAR LA ORDEN EN LA BOX, OBTENEMOS SU ID
					$idBox = modeloOrden::mdlExisteOrdenBox($idUsuario,$idTrabajo,$dientesSeleccionados,$direccionRecepcion,$direccionEntrega,$nombrePaciente,$especTrabajo,$colorimetria,$estadoTrabajo,$fechaRecepcion,$horaRecepcion,$porcientoPagar,$tipoOrden,$fechaEntrega);

					if(isset($_FILES['nuevaOrdenImg']['name'][0])){

						$numArchivos = count($_FILES['nuevaOrdenImg']['name']);

						for($i = 0; $i < $numArchivos; $i++){

							$fechaActual  = date("dHi");  //Fecha Actual       
			  				$no_aleatorio  = rand(10, 99);
			  				$nombreRuta = "vistas/asset/images/ordenes";
			  				$nombreArchivo = basename($_FILES['nuevaOrdenImg']['name'][$i]);
			  				$nombreCompleto = $fechaActual.$no_aleatorio.$nombreArchivo;
			  				
			  				$resultado = move_uploaded_file($_FILES['nuevaOrdenImg']['tmp_name'][$i], "$nombreRuta/$nombreCompleto");

						   	if ($resultado){ //SÍ SE MOVIO LA IMAGEN AL DIRECTORIO, LO DAMOS DE ALTA EN LA BASE DE DATOS
							    $imagen = $fechaActual.$no_aleatorio.$nombreArchivo;
							    if(modeloOrden::mdlInsertarImagen($imagen,$idBox)){
							    	//echo 'Se inserto la imagen '.$imagen;
							    } else {
							    	//echo "no se insertó el nombre de la imagen ".$imagen." en la base de datos.";
							    }
							  
							} else {
							    //AQUI VA EL CODIGO EN CASO DE QUE NO SE HAYA MOVIDO LA IMAGEN.
							    echo '';
							}
						}

					}
					

				}

			} else { //SÍ EXISTE, LA ASIGNAMOS A LA ID DE BOX

				$idBox = $existe;

			}

			return $idBox;
		}

	}



}

 ?>