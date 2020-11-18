<?php 

require_once "conexion.php";

require_once "listaTrabajos.modelo.php";
require_once "perfil.modelo.php";
require_once "notificaciones.modelo.php";

class modeloOrden{

	public static function mdlTotalOrdenesFinalizadas($idUsuario,$es){

		$db = new Conexion();

		switch ($es) {
			case 'dentista':
				$sql = $db -> query("SELECT orden_trabajo.* FROM orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel WHERE relacion.id_clie = $idUsuario and orden_trabajo.entregado = 1");
				break;

			case 'tecnico':
				$sql = $db -> query("SELECT orden_trabajo.* FROM orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel WHERE relacion.id_lab = $idUsuario and orden_trabajo.entregado = 1");
				break;
			
			default:
				$sql = 0;
				break;
		}

		if($db->rows($sql)>=1){
			$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);
		} else {
			$respuesta = null;
		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlTotalOrdenes($idUsuario,$es){

		$db = new Conexion();

		switch ($es) {
			case 'dentista':
				$sql = $db -> query("SELECT orden_trabajo.* FROM orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel WHERE relacion.id_clie = $idUsuario");
				break;

			case 'tecnico':
				$sql = $db -> query("SELECT orden_trabajo.* FROM orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel WHERE relacion.id_lab = $idUsuario");
				break;
			
			default:
				$sql = 0;
				break;
		}

		if($db->rows($sql)>=1){
			$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);
		} else {
			$respuesta = null;
		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlFotoPerfilOrden($idUsuario,$tipo,$idOrden){

		$db = new Conexion();

		switch ($tipo) {
			case 'dentista':
				$sql = $db -> query("SELECT laboratorio.img_art fotoPerfil FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join laboratorio on relacion.id_lab = laboratorio.id_lab WHERE orden_trabajo.id_ord = '$idOrden' and relacion.id_clie = $idUsuario");
				break;

			case 'tecnico':
				$sql = $db -> query("SELECT cliente.img_perfil fotoPerfil FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join cliente on relacion.id_clie = cliente.id_clie WHERE orden_trabajo.id_ord = '$idOrden' and relacion.id_lab = $idUsuario");
				break;
			
			default:
				$respuesta = null;
				break;
		}

		$respuesta = ($db -> rows($sql) >= 1) ? $sql -> fetch_array(MYSQLI_ASSOC) : null;

		return $respuesta;

		$db -> exit();

	}

	public static function mdlActoresDeUnaOrden($idOrden){

		$db = new Conexion();

		$sql = $db -> query("SELECT cliente.nomb dentista, cliente.id_clie idUsuario, laboratorio.nomb laboratorio, laboratorio.id_lab idLaboratorio, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, orden_trabajo.paciente FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join cliente on relacion.id_clie = cliente.id_clie left join laboratorio on relacion.id_lab = laboratorio.id_lab left join disp_trab on orden_trabajo.id_prod = disp_trab.id_disp_trab left join disp_orto on orden_trabajo.id_orto = disp_orto.id_ort left join protesis on disp_trab.id_pro = protesis.id_pro left join ortodoncia_prod on disp_orto.id_ort_prod = ortodoncia_prod.id_ort_prod WHERE orden_trabajo.id_ord = '$idOrden'");

		$respuesta = ($db->rows($sql) >= 1) ? $sql -> fetch_array(MYSQLI_ASSOC) : 0;

		return $respuesta;

		$db -> exit();

	}

	/*FUNCIONES PARA ENVIAR UN MENSAJE DENTRO DE LA ORDEN*/

	public static function mdlEnviarMensajeOrden($idUsuario,$tipo,$idOrden,$mensaje){

		$db = new Conexion();

		$respuesta = false;

		switch ($tipo) {
			case 'dentista':
				
				if( $db -> query("INSERT INTO mensajeria(id_ord, id_clie, mensaje) VALUES ('$idOrden', $idUsuario, '$mensaje')") ){
					$respuesta = true;
				}
				

				break;

			case 'tecnico':

				if( $db -> query("INSERT INTO mensajeria(id_ord, id_lab, mensaje) VALUES ('$idOrden', $idUsuario, '$mensaje')") ){
					$respuesta = true;
				}

				break;
			
			default:
				$respuesta = false;
				break;
		}

		return $respuesta;

		$db -> exit();


	}

	public static function mdlObtenerUltimoMensajeOrden($idOrden){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM mensajeria WHERE id_ord = '$idOrden' order by fecha desc limit 1");

		$respuesta = ($db->rows($sql) >= 1) ? $sql -> fetch_array(MYSQLI_ASSOC) : false;

		return $respuesta;

		$db -> exit();

	}

	public static function mdlCompararUltimoMensajeOrden($idOrden,$timestamp){

		$db = new Conexion();

		$ultimoMsg = strtotime($timestamp);

		$sql = $db -> query("SELECT * FROM mensajeria WHERE id_ord = '$idOrden' order by fecha desc limit 1");

		$datos = ($db->rows($sql) >= 1) ? $sql -> fetch_array(MYSQLI_ASSOC) : 0;

		$nuevoMsg = strtotime($datos['fecha']);

		if ($nuevoMsg <= $ultimoMsg){
			
			return 0;

		} else {
			
			return $datos;
		}

		$db -> exit();

	}

	public static function mdlNumeroMensajesNoLeidos($idUsuario,$tipo,$idOrden){

		$db = new Conexion();

		switch ($tipo) {
			case 'tecnico':

				$sql = $db -> query("SELECT * FROM mensajeria WHERE id_ord = '$idOrden' and leido = 0 and id_lab is NULL");
				
				break;

			case 'dentista':

				$sql = $db -> query("SELECT * FROM mensajeria WHERE id_ord = '$idOrden' and leido = 0 and id_clie is NULL");

				break;
			
			default:

				$respuesta = 0;
				
				break;
		}

		$respuesta = ($db -> rows($sql) >= 1) ? count($sql->fetch_all(MYSQLI_ASSOC)) : 0;

		return $respuesta;

		$db -> exit();

	}

	public static function mdlMarcarMensajesComoLeidos($idUsuario,$tipo,$idOrden){

		$db = new Conexion();

		switch ($tipo) {
			case 'dentista':
				
				if($db -> query("UPDATE mensajeria SET leido = 1 WHERE id_ord = '$idOrden' and id_clie is NULL")){
					$respuesta = true;
				}

				break;
			
			case 'tecnico':

				if($db -> query("UPDATE mensajeria SET leido = 1 WHERE id_ord = '$idOrden' and id_lab is NULL")){
					$respuesta = true;
				}

				break;

			default:
				$respuesta = false;
				break;
		}

		return $respuesta;

		$db -> exit();

	}

	/*******************************************************/

	/*FUNCIONES PARA BUSCAR LA PALABRA DEL USUARIO DENTRO DE LAS ORDENES*/

	public static function mdlBuscarOrdenDeUsuario($idUsuario,$tipo,$busqueda){

		$db = new Conexion();

		/**DESDE AQUI SE COMIENZA A SEPARAR LAS PALABRAS QUE EL USUARIO BUSCÓ**/

		$palabrasBusqueda = explode(" ", $busqueda);

		$i = 0;

		$queryLike = '(';

		$cliente_Laboratorio = ($tipo == "dentista") ? "laboratorio.nomb" : 'cliente.nomb';

		for($i = 0; $i < count($palabrasBusqueda); $i++) { //POR CADA PALABRA, AGREGAMOS UN CONDICIONAL LIKE DENTRO DE LA CONSULTA
				
			if( (strlen($palabrasBusqueda[$i]) >= 3) && ($i == 0) ){ //Sí la palabra que buscan tiene mas de 2 caracteres y es la primera, continuamos

				$queryLike .= 

				"orden_trabajo.paciente LIKE '%".$palabrasBusqueda[$i]."%' or
				orden_trabajo.paciente LIKE '".$palabrasBusqueda[$i]."%' or
				orden_trabajo.paciente LIKE '%".$palabrasBusqueda[$i]."' or
				
				orden_trabajo.fecha_orden LIKE '%".$palabrasBusqueda[$i]."%' or
				orden_trabajo.fecha_orden LIKE '".$palabrasBusqueda[$i]."%' or
				orden_trabajo.fecha_orden LIKE '%".$palabrasBusqueda[$i]."' or

				orden_trabajo.fecha_entrega LIKE '%".$palabrasBusqueda[$i]."%' or
				orden_trabajo.fecha_entrega LIKE '".$palabrasBusqueda[$i]."%' or
				orden_trabajo.fecha_entrega LIKE '%".$palabrasBusqueda[$i]."' or

				orden_trabajo.cantidad = '%".$palabrasBusqueda[$i]."%' or
				orden_trabajo.cantidad = '".$palabrasBusqueda[$i]."%' or
				orden_trabajo.cantidad = '%".$palabrasBusqueda[$i]."' or

				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				protesis.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."' or

				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				ortodoncia_prod.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."'";


			} elseif( strlen($palabrasBusqueda[$i]) >= 3 ){

				$queryLike .= //LA DIFERENCIA RADICA EN QUE EMPIEZA AHORA POR LA CONDICIONAL "OR"

				"OR orden_trabajo.paciente LIKE '%".$palabrasBusqueda[$i]."%' or
				orden_trabajo.paciente LIKE '".$palabrasBusqueda[$i]."%' or
				orden_trabajo.paciente LIKE '%".$palabrasBusqueda[$i]."' or
				
				orden_trabajo.fecha_orden LIKE '%".$palabrasBusqueda[$i]."%' or
				orden_trabajo.fecha_orden LIKE '".$palabrasBusqueda[$i]."%' or
				orden_trabajo.fecha_orden LIKE '%".$palabrasBusqueda[$i]."' or

				orden_trabajo.fecha_entrega LIKE '%".$palabrasBusqueda[$i]."%' or
				orden_trabajo.fecha_entrega LIKE '".$palabrasBusqueda[$i]."%' or
				orden_trabajo.fecha_entrega LIKE '%".$palabrasBusqueda[$i]."' or

				orden_trabajo.cantidad = '%".$palabrasBusqueda[$i]."%' or
				orden_trabajo.cantidad = '".$palabrasBusqueda[$i]."%' or
				orden_trabajo.cantidad = '%".$palabrasBusqueda[$i]."' or

				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				protesis.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."' or

				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				ortodoncia_prod.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."'";

			} elseif($i==0) {

				$queryLike .=  //LA DIFERENCIA RADICA EN QUE EMPIEZA AHORA POR LA CONDICIONAL "OR"

				"orden_trabajo.paciente LIKE '%".$palabrasBusqueda[$i]."%' or
				orden_trabajo.paciente LIKE '".$palabrasBusqueda[$i]."%' or
				orden_trabajo.paciente LIKE '%".$palabrasBusqueda[$i]."' or
				
				orden_trabajo.fecha_orden LIKE '%".$palabrasBusqueda[$i]."%' or
				orden_trabajo.fecha_orden LIKE '".$palabrasBusqueda[$i]."%' or
				orden_trabajo.fecha_orden LIKE '%".$palabrasBusqueda[$i]."' or

				orden_trabajo.fecha_entrega LIKE '%".$palabrasBusqueda[$i]."%' or
				orden_trabajo.fecha_entrega LIKE '".$palabrasBusqueda[$i]."%' or
				orden_trabajo.fecha_entrega LIKE '%".$palabrasBusqueda[$i]."' or

				orden_trabajo.cantidad = '%".$palabrasBusqueda[$i]."%' or
				orden_trabajo.cantidad = '".$palabrasBusqueda[$i]."%' or
				orden_trabajo.cantidad = '%".$palabrasBusqueda[$i]."' or

				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				protesis.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."' or

				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				ortodoncia_prod.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."'";

			}

		}

		$queryLike .= ')';

		///***************************TERMINA EL QUERY DE BUSQUEDA POR PALABRA**********************************////

		/*SE REALIZA LA CONSULTA PARA LA BUSQUEDA*/

		switch ($tipo) {
			case 'tecnico':

			$sql = $db -> query("SELECT cliente.nomb, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, orden_trabajo.fecha_orden fecha, orden_trabajo.cantidad precio, orden_trabajo.tipo, orden_trabajo.fecha_entrega, orden_trabajo.id_ord, cliente.img_perfil fotoPerfil, orden_trabajo.entregado, orden_trabajo.paciente, orden_trabajo.dientes FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join cliente on cliente.id_clie = relacion.id_clie left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod WHERE relacion.id_lab = $idUsuario and orden_trabajo.entregado != 4 and orden_trabajo.entregado != 3 and orden_trabajo.entregado != 2 and orden_trabajo.entregado != 1 and $queryLike ORDER BY orden_trabajo.fecha_ultima_orden DESC");

			$resultado = ($db->rows($sql)>=1) ? $sql -> fetch_all(MYSQLI_ASSOC) : false;
				
				break;

			case 'dentista':

				$sql = $db -> query("SELECT laboratorio.nomb, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, orden_trabajo.fecha_orden fecha, orden_trabajo.cantidad precio, orden_trabajo.tipo, orden_trabajo.fecha_entrega, orden_trabajo.id_ord, laboratorio.img_art fotoPerfil, orden_trabajo.entregado, orden_trabajo.paciente, orden_trabajo.dientes FROM (orden_trabajo left join disp_trab on orden_trabajo.id_prod = disp_trab.id_disp_trab) left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on disp_trab.id_pro = protesis.id_pro left join ortodoncia_prod on disp_orto.id_ort_prod = ortodoncia_prod.id_ort_prod left join relacion on orden_trabajo.id_rel = relacion.id_rel left join laboratorio on relacion.id_lab = laboratorio.id_lab WHERE relacion.id_clie = $idUsuario and $queryLike ORDER BY orden_trabajo.fecha_ultima_orden DESC");

				$resultado = ($db->rows($sql)>=1) ? $sql -> fetch_all(MYSQLI_ASSOC) : false;
				
				break;

			default:
				$resultado = false;
				break;
		}

		return $resultado;

		$db -> exit();

	}

	/********************************************************************/

	public static function mdlConsultarPago($id){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM pagos WHERE id_pago = '$id'");

		if($db -> rows($sql) >= 1){

			$res = $sql -> fetch_all(MYSQLI_ASSOC);

		} else {

			$res = false;

		}	

		return $res;

		$db -> exit();

	}

	public static function mdlOrdenVista($idHistOrd){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM historial_orden_trabajo WHERE id_hist_ord = $idHistOrd and visto = 0");

		if($db -> rows($sql) >= 1){

			if( $db -> query("UPDATE historial_orden_trabajo SET visto = 1 WHERE id_hist_ord = $idHistOrd") ){

				return true;

			} else {

				return false;

			}
			
		} else{

			return false;

		}


		$db -> exit();

	}


	public static function mdlTodasLasOrdenes(){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM orden_trabajo");

		if($db->rows($sql) >= 1){

			$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);

		} else {

			$respuesta = false;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlCambiarEstadoOrden($estadoOrden,$idOrden){

		$db = new Conexion();

		if( $db -> query("UPDATE orden_trabajo SET entregado = $estadoOrden WHERE id_ord = '$idOrden'") ){

			return true;

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlDatosOrden($idOrden){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM orden_trabajo WHERE id_ord = '$idOrden'");

		if( $db -> rows($sql) >= 1 ){

			$respuesta = $sql -> fetch_array(MYSQLI_ASSOC);

		} else {

			$respuesta = false;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlEtapaOrden($idHistOrd){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM historial_orden_trabajo WHERE id_hist_ord = $idHistOrd");

		if( $db -> rows($sql) >= 1 ){

			$respuesta = $sql -> fetch_array(MYSQLI_ASSOC);

		} else {

			$respuesta = false;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlActualizarPago($idHistOrd,$estadoPagoBD){

		$db = new Conexion();
		$orden = new modeloOrden();
		$notificaciones = new modeloNotificacion();

		if( $db -> query("UPDATE historial_orden_trabajo SET estadoPago = $estadoPagoBD WHERE id_hist_ord = $idHistOrd") ){

			/*MANEJO DEL ENVIO DE NOTIFICACIONES*/

			$datosEtapaOrden = $orden ->  mdlEtapaOrden($idHistOrd);
			$idOrden = $datosEtapaOrden['id_ord'];

			//$orden -> mdlEtapasOrden();

			$datos = $orden -> mdlActoresDeUnaOrden($idOrden);
			$idLaboratorio = $datos['idLaboratorio'];
			$idUsuario = $datos['idUsuario'];
			$nombreTrabajo = ($datos['protesis'] != NULL) ? $datos['protesis'] : $datos['ortodoncia'];
			$nombreDentista = $datos['dentista'];
			$nombreLaboratorio = $datos['laboratorio'];
			$paciente = $datos['paciente'];

			switch ($estadoPagoBD) {
				case 1:
					
					$tituloDentista = '¡GENIAL! Se <b>aprobó</b> tu pago';
					$mensajeDentista = 'El pago de la orden de trabajo: <b>'.$nombreTrabajo.'</b> se realizó con exito';
					$color = '#1a8218';
					$icono = 'fas fa-check-circle';

					break;

				case 4:

					$tituloDentista = '¡LO SENTIMOS! No se pudo realizar tu pago';
					$mensajeDentista = 'El pago de la orden de trabajo: <b>'.$nombreTrabajo.'</b> se ha cancelado, y no se mandará al Laboratorio';
					$color = '#c92222';
					$icono = 'fas fa-ban';
					
				default:
					
					$tituloDentista = 'Estamos verificando tu pago mas a fondo';
					$mensajeDentista = 'Es probable que algo haya salido mal en tu pago, así que lo estamos revisandomas con lupa';
					$color = '#1a67a5';
					$icono = 'fas fa-search-dollar';
					break;
			}



			$tituloLaboratorio = 'Tienes una nueva orden del paciente <b>'.$paciente.'</b>';
			$mensajeLaboratorio = 'El doctor <b>'.$nombreDentista.'</b> mandó una nueva orden del trabajo: <b>'.$nombreTrabajo.'</b>';
			
			$urlDestino = "ordenes?orden=".$idOrden."&filtroOrden=etapas";
			$tipoNotificacion = 'actualizacionDePagos';
			$auxiliar = $idOrden;

			$notificaciones -> mdlInsertarNuevaNotificacion($idUsuario,"dentista",$tituloDentista,$mensajeDentista,$urlDestino,$color,$icono,$tipoNotificacion,$auxiliar); //ENVIAMOS LA NOTIFICACION AL DENTISTA

			if($estadoPagoBD == 1){ //SÓLO ENVIAR NOTIFICACIÓN AL TECNICO CUANDO EL PAGO DE LA ORDEN SE HIZO CORRECTAMENTE, LOS ESTADOS 2, 3 Y 4 SON UTILIZADOS PARA OTRA COSA (1 = success, 2 = pending, 3 = in_process y 4 = cancelled)

				$notificaciones -> mdlInsertarNuevaNotificacion($idLaboratorio,"tecnico",$tituloLaboratorio,$mensajeLaboratorio,$urlDestino,$color,$icono,$tipoNotificacion,$auxiliar); //ENVIAMOS LA NOTIFICACION AL TECNICO
			}

			return true;

		} else {

			return false;

		}

		$db -> exit();

	}

	/*FUNCIONES PARA EL IPN DE MERCADOPAGO*/

	public static function mdlConsultarPagos($idTransaccion){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM pagos WHERE id_pago = '$idTransaccion'");

		if( $db -> rows($sql) >= 1 ){

			$respuesta = count($sql -> fetch_array(MYSQLI_ASSOC));

		} else {

			$respuesta = true;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlConsultarPagoOrden($idOrden){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM pagos WHERE id_orden = '$idOrden'");

		if( $db -> rows($sql) >= 1 ){

			$respuesta = $sql -> fetch_array(MYSQLI_ASSOC);

		} else {

			$respuesta = false;

		}

		return $respuesta;

		$db -> exit();


	}

	/*FUNCIONES QUE SE UTILIZAN EN orden.php*/

	public static function mdlCalificacionOrden($idUsuario,$es,$idOrden){

		$db = new Conexion();

		switch ($es) {
			case 'tecnico':
				$sql = $db -> query("SELECT * FROM calif_trab WHERE id_ord = '$idOrden'");
				break;

			case 'dentista':
				$sql = $db -> query("SELECT * FROM calif_trab WHERE id_ord = '$idOrden' and id_clie = $idUsuario");
				break;
			
			default:
				$sql = 0;
				break;
		}


		if($db -> rows($sql) >= 1){

			$respuesta = $sql -> fetch_array(MYSQLI_ASSOC);

		} else {

			$respuesta = false;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlAgregarOpinion($idUsuario,$calif1,$calif2,$calif3,$opinion,$idOrden,$idTrabajo,$tipo){

		$db = new Conexion();
		$orden = new modeloOrden();
		$notificaciones = new modeloNotificacion();

		$respuesta = false;

		switch ($tipo) {
			case 'protesis':
				
				if($db -> query("INSERT INTO calif_trab (id_trab, opinion, precio, tiempo, calidad, id_clie, id_ord) VALUES ($idTrabajo, '$opinion', $calif1, $calif2, $calif3, $idUsuario, '$idOrden')")){

					$db -> query("UPDATE orden_trabajo SET entregado = 1 WHERE id_ord = '$idOrden'");

					$respuesta = true;

				}

				
				break;

			case 'ortodoncia':

				if($db -> query("INSERT INTO calif_trab (id_orto, opinion, precio, tiempo, calidad, id_clie, id_ord) VALUES ($idTrabajo, '$opinion', $calif1, $calif2, $calif3, $idUsuario, '$idOrden')")){

					$db -> query("UPDATE orden_trabajo SET entregado = 1 WHERE id_ord = '$idOrden'");
					

					$respuesta = true;

				}


				break;
			
			default:
				$respuesta = false;
				break;
		}

		/*MANEJO DEL ENVIO DE NOTIFICACIONES*/

		//$orden -> mdlEtapasOrden();

		$datos = $orden -> mdlActoresDeUnaOrden($idOrden);
		$idLaboratorio = $datos['idLaboratorio'];
		$idUsuario = $datos['idUsuario'];
		$nombreTrabajo = ($datos['protesis'] != NULL) ? $datos['protesis'] : $datos['ortodoncia'];
		$nombreDentista = $datos['dentista'];
		$nombreLaboratorio = $datos['laboratorio'];
		$paciente = $datos['paciente'];

		/*$tituloDentista = '¡GENIAL! Se <b>aprobó</b> tu pago';
		$mensajeDentista = 'El pago de la orden de trabajo: <b>'.$nombreTrabajo.'</b> se realizó con exito';*/

		$tituloLaboratorio = 'El dentista <b>'.$nombreDentista.'</b> ha calificado tu trabajo';
		$mensajeLaboratorio = 'Ya puedes ver la opinión sobre tu trabajo, da clic aquí';
		
		$color = '#1a8218';
		$icono = 'fas fa-user-check';
		$urlDestino = "ordenes?orden=".$idOrden;
		$tipoNotificacion = 'calificacion';
		$auxiliar = $idOrden;

		$notificaciones -> mdlInsertarNuevaNotificacion($idLaboratorio,"tecnico",$tituloLaboratorio,$mensajeLaboratorio,$urlDestino,$color,$icono,$tipoNotificacion,$auxiliar); //ENVIAMOS LA NOTIFICACION AL TECNICO

		return $respuesta;

		$db -> exit();

	}

	/////////////////////////////////////////

	public static function mdlDetallesAntesOrden($idAntesOrden){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM detalles_antes_pago WHERE id_detalle = $idAntesOrden");

		$respuesta = $sql -> fetch_array(MYSQLI_ASSOC);

		return $respuesta;

		$db -> exit();

	}

	public static function mdlBorrarDetallesAntesOrden($idAntesOrden){

		$db = new Conexion();

		if( $db -> query("DELETE FROM detalles_antes_pago WHERE id_detalle = $idAntesOrden") ){

			return true;

		} else {

			return false;

		} 

		$db -> exit();

	}

	public static function mdlAltaPago($idPago, $idNuevaOrden, $metodoPago, $emailPago, $nombrePago, $idCompradorPago, $paisPago){

		$db = new Conexion();

		if( $db -> query("INSERT INTO pagos(id_pago, id_orden, metodo, email, nombre, idPayer, paisPago) VALUES ('$idPago', '$idNuevaOrden', '$metodoPago', '$emailPago', '$nombrePago', '$idCompradorPago', '$paisPago')") ){

			return true;

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlAltaImagenes($nombreImagen,$idOrden,$idNuevaOrden){

		$db = new Conexion();

		if($db -> query("INSERT INTO archivos (nombre, id_ord, id_historial_ord) VALUES ('$nombreImagen','$idOrden',$idNuevaOrden)")){

			return true;

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlContinuarOrden($idOrden, $pago, $etapa, $estado, $estadoPago, $fechaRecepcion, $fechaEntrega, $descripcion, $url){

		$db = new Conexion();

		$orden = new modeloOrden();
		$notificaciones = new modeloNotificacion();

		$datosGeneralesOrden = $orden -> mdlDatosOrden($idOrden);

		if( ($datosGeneralesOrden['entregado'] == 1) && ($estadoPago == 1) ){ //SÍ EL TRABAJO YA SE HABÍA ENTREGADO Y EL PAGO ES EXITOSO, SE CAMBIA EL ESTATUS DE ENTREGADO DE LA ORDEN A 0.

			$db -> query("UPDATE orden_trabajo SET entregado = 0 WHERE id_ord = '$idOrden'");

		}

		if( $db -> query("INSERT INTO historial_orden_trabajo (id_ord, pago, etapa, estado, estadoPago, fecha_rec, fecha_ent, descr) VALUES ('$idOrden', $pago, '$etapa', $estado, $estadoPago, '$fechaRecepcion', '$fechaEntrega', '$descripcion')") ){

			$idSql = $db -> insert_id;

			/*MANEJO DEL ENVIO DE NOTIFICACIONES*/

			$datos = $orden -> mdlActoresDeUnaOrden($idOrden);
			$idLaboratorio = $datos['idLaboratorio'];
			$idUsuario = $datos['idUsuario'];
			$nombreTrabajo = ($datos['protesis'] != NULL) ? $datos['protesis'] : $datos['ortodoncia'];
			$nombreDentista = $datos['dentista'];
			$nombreLaboratorio = $datos['laboratorio'];
			$paciente = $datos['paciente'];

			$tituloLaboratorio = 'Tienes una nueva orden del paciente <b>'.$paciente.'</b>';
			$mensajeLaboratorio = 'El doctor <b>'.$nombreDentista.'</b> mandó una nueva orden del trabajo: <b>'.$nombreTrabajo.'</b>';
			
			$tituloDentista = 'Generaste una nueva orden para el paciente <b>'.$paciente.'</b>';
			$mensajeDentista = 'La orden del trabajo <b>'.$nombreTrabajo.'</b> se realizó con exito';
			
			$urlDestino = "ordenes?orden=".$idOrden."&filtroOrden=etapas";
			$color = '#666666';
			$icono = 'fas fa-tooth';
			$tipoNotificacion = 'nuevaEtapaOrden';
			$auxiliar = $idOrden;

			$notificaciones -> mdlInsertarNuevaNotificacion($idUsuario,"dentista",$tituloDentista,$mensajeDentista,$urlDestino,$color,$icono,$tipoNotificacion,$auxiliar); //ENVIAMOS LA NOTIFICACION AL DENTISTA

			if($estadoPago == 1){ //SÓLO ENVIAR NOTIFICACIÓN AL TECNICO CUANDO EL PAGO DE LA ORDEN SE HIZO CORRECTAMENTE, LOS ESTADOS 2, 3 Y 4 SON UTILIZADOS PARA OTRA COSA (1 = success, 2 = pending, 3 = in_process y 4 = cancelled)

				$notificaciones -> mdlInsertarNuevaNotificacion($idLaboratorio,"tecnico",$tituloLaboratorio,$mensajeLaboratorio,$urlDestino,$color,$icono,$tipoNotificacion,$auxiliar); //ENVIAMOS LA NOTIFICACION AL TECNICO
			}
			
		}


		return $idSql;

		$db -> exit();

	}

	public static function mdlGenerarNuevaEtapaOrden($idOrden,$nombreOrden,$estado,$pago,$fechaRecepcion,$fechaEntrega,$descripcion,$stringImagenes){

		$db = new Conexion();

		$sql = $db -> query("INSERT INTO detalles_antes_pago (etapa, pago, estadoOrden, idOrden, fechaRecepcion, fechaEntrega, descr, imagenes) VALUES ('$nombreOrden',$pago,$estado,'$idOrden','$fechaRecepcion','$fechaEntrega','$descripcion','$stringImagenes')");

		$idSql = $db -> insert_id;

		return $idSql;

		$db -> exit();

	}

	public static function mdlStatusOrden($idUsuario,$es,$idOrden,$status,$idHist){

		$db = new Conexion();
		$orden = new modeloOrden();
		$notificaciones = new modeloNotificacion();

		switch ($es) {
			case 'tecnico':
				
				$sql = $db -> query("SELECT orden_trabajo.* FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join laboratorio on laboratorio.id_lab = relacion.id_lab WHERE orden_trabajo.id_ord = '$idOrden' and laboratorio.id_lab = $idUsuario"); //VERIFICAMOS SÍ LA ORDEN LE CORRESPONDE A LA SESIÓN DEL TECNICO

				if( ($status == "trabajoTerminado") && ($db -> rows($sql) >= 1) ){

					if($db -> query("UPDATE historial_orden_trabajo SET confirmacionTecnico = 1 WHERE id_ord = '$idOrden' and id_hist_ord = $idHist")){

						$sqlFinalizado = $db -> query("SELECT * FROM historial_orden_trabajo WHERE id_ord = '$idOrden' order by id_hist_ord desc");

						$resFinalizado = $sqlFinalizado -> fetch_all(MYSQLI_ASSOC);

						/*MANEJO DEL ENVIO DE NOTIFICACIONES*/

						$datos = $orden -> mdlActoresDeUnaOrden($idOrden);
						$idLaboratorio = $datos['idLaboratorio'];
						$idUsuario = $datos['idUsuario'];
						$nombreTrabajo = ($datos['protesis'] != NULL) ? $datos['protesis'] : $datos['ortodoncia'];
						$nombreDentista = $datos['dentista'];
						$nombreLaboratorio = $datos['laboratorio'];
						$paciente = $datos['paciente'];
						
						$urlDestino = "ordenes?orden=".$idOrden."&filtroOrden=etapas";
						$color = '#1a8218';
						$icono = 'fas fa-check';
						$tipoNotificacion = 'LaboratorioTerminaUnaOrden';
						$auxiliar = $idOrden;

						if($resFinalizado[0]['estado'] == 1){ //SÍ LA ULTIMA ORDEN ESTA MARCADA COMO "TERMINADO" SIGNIFICA QUE FUE LA ULTIMA EN ENVIARSE

							$db -> query("UPDATE orden_trabajo SET entregado = 1 WHERE id_ord = '$idOrden'");

							$tituloDentista = 'Orden del paciente <b>'.$paciente.'</b> finalizada';
						$mensajeDentista = 'El laboratorio <b>'.$nombreLaboratorio.'</b> ha completado tu trabajo, por favor brinda una <b>calificación sobre su servicio</b>';

						} else {

							$tituloDentista = 'Ya puedes enviar la siguiente orden para el paciente: <b>'.$paciente.'</b>';
						$mensajeDentista = 'El laboratorio ha terminado la utlima orden del trabajo: '.$nombreTrabajo.', ya puedes generar otra orden';

						}
						
						$notificaciones -> mdlInsertarNuevaNotificacion($idUsuario,"dentista",$tituloDentista,$mensajeDentista,$urlDestino,$color,$icono,$tipoNotificacion,$auxiliar); //ENVIAMOS LA NOTIFICACION AL DENTISTA

						$respuesta = true;

					}

				}
				
				break;

			case 'dentista':

				$sql = $db -> query("SELECT orden_trabajo.* FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join cliente on cliente.id_clie = relacion.id_clie WHERE orden_trabajo.id_ord = '$idOrden' and cliente.id_clie = $idUsuario"); //VERIFICAMOS SÍ LA ORDEN LE CORRESPONDE A LA SESION DE ESE DENTISTA

				if( ($status == "trabajoTerminadoDentista") && ($db -> rows($sql) >= 1) ){

					if($db -> query("UPDATE historial_orden_trabajo SET confirmacionTecnico = 1 WHERE id_ord = '$idOrden' and id_hist_ord = $idHist")){

						$sqlFinalizado = $db -> query("SELECT * FROM historial_orden_trabajo WHERE id_ord = '$idOrden' order by id_hist_ord desc");

						$resFinalizado = $sqlFinalizado -> fetch_all(MYSQLI_ASSOC);

						/*MANEJO DEL ENVIO DE NOTIFICACIONES*/

						$datos = $orden -> mdlActoresDeUnaOrden($idOrden);
						$idLaboratorio = $datos['idLaboratorio'];
						$idUsuario = $datos['idUsuario'];
						$nombreTrabajo = ($datos['protesis'] != NULL) ? $datos['protesis'] : $datos['ortodoncia'];
						$nombreDentista = $datos['dentista'];
						$nombreLaboratorio = $datos['laboratorio'];
						$paciente = $datos['paciente'];
						
						$urlDestino = "ordenes?orden=".$idOrden."&filtroOrden=etapas";
						$color = '#1a8218';
						$icono = 'fas fa-check';
						$tipoNotificacion = 'LaboratorioTerminaUnaOrden';
						$auxiliar = $idOrden;

						if($resFinalizado[0]['estado'] == 1){ //SÍ LA ULTIMA ORDEN ESTA MARCADA COMO "TERMINADO" SIGNIFICA QUE FUE LA ULTIMA EN ENVIARSE

							$db -> query("UPDATE orden_trabajo SET entregado = 1 WHERE id_ord = '$idOrden'");

							$tituloDentista = 'Orden del paciente <b>'.$paciente.'</b> finalizada';
						$mensajeDentista = 'El laboratorio <b>'.$nombreLaboratorio.'</b> ha completado tu trabajo, por favor brinda una <b>calificación sobre su servicio</b>';

						} else {

							$tituloDentista = 'Ya puedes enviar la siguiente orden para el paciente: <b>'.$paciente.'</b>';
						$mensajeDentista = '<b>'.ucfirst($nombreLaboratorio).'</b> ha terminado la utlima orden de tu paciente <b>'.ucfirst($paciente).'</b>, ya puedes enviar la siguiente orden';

						}
						
						$notificaciones -> mdlInsertarNuevaNotificacion($idUsuario,"dentista",$tituloDentista,$mensajeDentista,$urlDestino,$color,$icono,$tipoNotificacion,$auxiliar); //ENVIAMOS LA NOTIFICACION AL DENTISTA

						$respuesta = true;

					}

				}

				break;
			
			default:
				# code...
				break;
		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlEtapasOrden($idOrd,$es){

		$db = new Conexion();

		switch ($es) {
			case 'tecnico':
				
				$sql = $db -> query("SELECT * FROM historial_orden_trabajo WHERE id_ord = '$idOrd' and estadoPago = 1 order by id_hist_ord desc");
				
				break;

			case 'dentista':

				$sql = $db -> query("SELECT * FROM historial_orden_trabajo WHERE id_ord = '$idOrd' order by id_hist_ord desc");

				break;
			
			default:

				$sql = $db -> query("SELECT * FROM historial_orden_trabajo WHERE id_ord = '$idOrd' order by id_hist_ord desc");
				
				break;
		}


		$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);

		return $respuesta;

		$db -> exit();

	}

	public static function mdlDetallesOrden($idUsuario,$tipo,$idOrden,$filtro){

		$db = new Conexion();

		switch ($tipo) {

			case 'tecnico':

				switch ($filtro) {

					case 'resumen':

						$sql = $db -> query("SELECT cliente.nomb nombre, cliente.img_perfil fotoPerfil, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, disp_trab.id_disp_trab idProtesis, disp_orto.id_ort idOrtodoncia, orden_trabajo.paciente, orden_trabajo.dientes, orden_trabajo.cantidad, orden_trabajo.tipo, color.nomb color, direcciones.calle direcRec, direcciones.cp cpRec, t.calle direcEnt, t.cp cpEnt, orden_trabajo.entregado, sepomex.municipio municipioRec, s.municipio municipioEnt FROM (orden_trabajo left join direcciones on orden_trabajo.dir_rec = direcciones.id_direc)left join direcciones t on orden_trabajo.dir_ent = t.id_direc left join sepomex on sepomex.id = direcciones.id_rep left join sepomex s on s.id = t.id_rep left join color on orden_trabajo.colorimetria = color.id_col left join relacion on orden_trabajo.id_rel = relacion.id_rel left join cliente on cliente.id_clie = relacion.id_clie left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod WHERE orden_trabajo.id_ord = '$idOrden' and relacion.id_lab = $idUsuario");

						break;

					case 'ordenes':
					case 'pagos':
						
						$sql = $db -> query("SELECT historial_orden_trabajo.* FROM (historial_orden_trabajo left join orden_trabajo on historial_orden_trabajo.id_ord = orden_trabajo.id_ord) left join relacion on orden_trabajo.id_rel = relacion.id_rel WHERE relacion.id_lab = $idUsuario and orden_trabajo.id_ord = '$idOrden' and historial_orden_trabajo.estadoPago = 1 order by historial_orden_trabajo.id_hist_ord DESC");

						break;

					case 'fotos':
						
						$sql = $db -> query("SELECT archivos.* FROM (archivos left join orden_trabajo on archivos.id_ord = orden_trabajo.id_ord) left join relacion on orden_trabajo.id_rel = relacion.id_rel WHERE relacion.id_lab = $idUsuario and orden_trabajo.id_ord = '$idOrden'");

						break;

					case 'chat':

						$sql = $db -> query("SELECT * FROM mensajeria WHERE id_ord = '$idOrden' ORDER BY fecha ASC");

						break;
					
					default:
						
						break;
				}
					

			break;


			case 'dentista':

				switch ($filtro) {

					case 'resumen':

						$sql = $db -> query("SELECT laboratorio.nomb nombre, laboratorio.img_art fotoPerfil, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, disp_trab.id_disp_trab idProtesis, disp_orto.id_ort idOrtodoncia, orden_trabajo.paciente, orden_trabajo.dientes, orden_trabajo.cantidad, orden_trabajo.tipo, color.nomb color, direcciones.calle direcRec, direcciones.cp cpRec, t.calle direcEnt, t.cp cpEnt, orden_trabajo.entregado, sepomex.municipio municipioRec, s.municipio municipioEnt FROM (orden_trabajo left join direcciones on orden_trabajo.dir_rec = direcciones.id_direc)left join direcciones t on orden_trabajo.dir_ent = t.id_direc left join sepomex on sepomex.id = direcciones.id_rep left join sepomex s on s.id = t.id_rep left join color on orden_trabajo.colorimetria = color.id_col left join relacion on orden_trabajo.id_rel = relacion.id_rel left join laboratorio on laboratorio.id_lab = relacion.id_lab left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod WHERE orden_trabajo.id_ord = '$idOrden' and relacion.id_clie = $idUsuario");

						break;

					case 'ordenes':
					case 'pagos':
						
						$sql = $db -> query("SELECT historial_orden_trabajo.* FROM (historial_orden_trabajo left join orden_trabajo on historial_orden_trabajo.id_ord = orden_trabajo.id_ord) left join relacion on orden_trabajo.id_rel = relacion.id_rel WHERE relacion.id_clie = $idUsuario and orden_trabajo.id_ord = '$idOrden' order by historial_orden_trabajo.id_hist_ord DESC");

						break;

					case 'fotos':
						
						$sql = $db -> query("SELECT archivos.* FROM (archivos left join orden_trabajo on archivos.id_ord = orden_trabajo.id_ord) left join relacion on orden_trabajo.id_rel = relacion.id_rel WHERE relacion.id_clie = $idUsuario and orden_trabajo.id_ord = '$idOrden'");

						break;

					case 'chat':

						$sql = $db -> query("SELECT * FROM mensajeria WHERE id_ord = '$idOrden' ORDER BY fecha ASC");

						break;
					
					default:
						$sql = 0;
						break;
				}

			break;

			
			default:
				$sql = 0;
			break;
		}

		if($db -> rows($sql) >= 1){

			$res = $sql -> fetch_all(MYSQLI_ASSOC);
			
		} else {

			$res = false;

		}


		return $res;

		$db -> exit();

	}

	public static function mdlResumenOrden($idUsuario,$tipo,$idOrden){

		$db = new Conexion();

		switch ($tipo) {
			case 'tecnico':
				
				$sql = $db -> query("SELECT cliente.nomb nombre, cliente.id_clie id, cliente.img_perfil fotoPerfil, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, disp_trab.id_disp_trab idProtesis, disp_orto.id_ort idOrtodoncia, orden_trabajo.paciente, orden_trabajo.dientes, orden_trabajo.cantidad, orden_trabajo.tipo, color.nomb color, direcciones.calle direcRec, direcciones.cp cpRec, t.calle direcEnt, t.cp cpEnt, orden_trabajo.entregado, sepomex.municipio municipioRec, s.municipio municipioEnt FROM (orden_trabajo left join direcciones on orden_trabajo.dir_rec = direcciones.id_direc)left join direcciones t on orden_trabajo.dir_ent = t.id_direc left join sepomex on sepomex.id = direcciones.id_rep left join sepomex s on s.id = t.id_rep left join color on orden_trabajo.colorimetria = color.id_col left join relacion on orden_trabajo.id_rel = relacion.id_rel left join cliente on cliente.id_clie = relacion.id_clie left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod WHERE orden_trabajo.id_ord = '$idOrden' and relacion.id_lab = $idUsuario");


				break;

			case 'dentista':
				
				$sql = $db -> query("SELECT laboratorio.nomb nombre, laboratorio.id_lab id, laboratorio.img_art fotoPerfil, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, disp_trab.id_disp_trab idProtesis, disp_orto.id_ort idOrtodoncia, orden_trabajo.paciente, orden_trabajo.dientes, orden_trabajo.cantidad, orden_trabajo.tipo, color.nomb color, direcciones.calle direcRec, direcciones.cp cpRec, t.calle direcEnt, t.cp cpEnt, orden_trabajo.entregado, sepomex.municipio municipioRec, s.municipio municipioEnt FROM (orden_trabajo left join direcciones on orden_trabajo.dir_rec = direcciones.id_direc)left join direcciones t on orden_trabajo.dir_ent = t.id_direc left join sepomex on sepomex.id = direcciones.id_rep left join sepomex s on s.id = t.id_rep left join color on orden_trabajo.colorimetria = color.id_col left join relacion on orden_trabajo.id_rel = relacion.id_rel left join laboratorio on laboratorio.id_lab = relacion.id_lab left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod WHERE orden_trabajo.id_ord = '$idOrden' and relacion.id_clie = $idUsuario");

				break;
				
			default:
				$sql = 0;
				break;
		}


		if($db -> rows($sql) >= 1){

			$respuesta = $sql -> fetch_array(MYSQLI_ASSOC);

		} else {

			$respuesta = false;

		}

		return $respuesta;

	}

	public static function mdlRelacion($idUsuario, $tipo){

		$db = new Conexion();

		switch ($tipo) {

			case 'dentista':

				$sql = $db -> query ("SELECT laboratorio.nomb, laboratorio.img_art fotoPerfil, relacion.id_rel, relacion.id_clie, relacion.id_lab FROM relacion left join laboratorio on relacion.id_lab = laboratorio.id_lab WHERE relacion.id_clie = $idUsuario");

				break;
			
			case 'tecnico':

				$sql = $db -> query ("SELECT cliente.nomb, cliente.img_perfil fotoPerfil, relacion.id_rel, relacion.id_clie FROM relacion left join cliente on relacion.id_clie = cliente.id_clie WHERE id_lab = $idUsuario");

				break;

			default:
				
				$respuesta = false;

				break;

		}

		if($db -> rows($sql) >= 1){

			$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);

		} else {

			$respuesta = false;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlInfoTrabajo($idTrabajo,$queEs){

		$db = new Conexion();

		switch ($queEs) {
			case 'protesis':
				
				$sql = $db -> query("SELECT protesis.nomb FROM disp_trab left join protesis on disp_trab.id_pro = protesis.id_pro WHERE disp_trab.id_disp_trab = $idTrabajo");

				break;

			case 'ortodoncia':

				$sql = $db -> query("SELECT ortodoncia_prod.nomb FROM disp_orto left join ortodoncia_prod on disp_orto.id_ort_prod = ortodoncia_prod.id_ort_prod WHERE disp_orto.id_ort = $idTrabajo");

				break;
			
			default:
					
				$respuesta = false;

				break;
		}

		$respuesta = $sql -> fetch_array(MYSQLI_ASSOC);

		return $respuesta;

		$db -> exit();

	}



	public static function mdlOrdenes($idUsuario,$tipo,$filtro){

		$db = new Conexion();

		date_default_timezone_set("America/Mexico_City");

		switch ($tipo) {
			case 'dentista':
				
				switch ($filtro) {
					case 'prioritario':
						
						$sql = $db -> query("SELECT laboratorio.nomb, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, orden_trabajo.fecha_orden fecha, orden_trabajo.cantidad precio, orden_trabajo.tipo, orden_trabajo.fecha_entrega, orden_trabajo.id_ord, laboratorio.img_art fotoPerfil, orden_trabajo.entregado, orden_trabajo.paciente, orden_trabajo.dientes FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join laboratorio on laboratorio.id_lab = relacion.id_lab left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod WHERE relacion.id_clie = $idUsuario and orden_trabajo.entregado != 1 ORDER BY orden_trabajo.fecha_ultima_orden DESC");

						break;
					
					case 'nuevo':

						$fechaActual = date("Y-m-d");

						$sql = $db -> query("SELECT laboratorio.nomb, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, historial_orden_trabajo.fecha_hist_ord fecha, orden_trabajo.cantidad precio, orden_trabajo.tipo, orden_trabajo.fecha_entrega, orden_trabajo.id_ord, laboratorio.img_art fotoPerfil, orden_trabajo.entregado, orden_trabajo.paciente, orden_trabajo.dientes FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join laboratorio on laboratorio.id_lab = relacion.id_lab left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod left join historial_orden_trabajo on orden_trabajo.id_ord = historial_orden_trabajo.id_ord WHERE relacion.id_clie = $idUsuario and historial_orden_trabajo.fecha_hist_ord LIKE '$fechaActual%' ORDER BY orden_trabajo.fecha_ultima_orden DESC");

						break;

					case 'atrasado':

						//$sql = $db -> query("SELECT laboratorio.nomb, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, orden_trabajo.fecha_orden fecha, orden_trabajo.cantidad precio, orden_trabajo.tipo, orden_trabajo.fecha_entrega, orden_trabajo.id_ord, laboratorio.img_art fotoPerfil FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join laboratorio on laboratorio.id_lab = relacion.id_lab left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod WHERE relacion.id_clie = $idUsuario ORDER BY orden_trabajo.fecha_orden DESC");

						break;

					case 'finalizado':

						$sql = $db -> query("SELECT laboratorio.nomb, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, orden_trabajo.fecha_orden fecha, orden_trabajo.cantidad precio, orden_trabajo.tipo, orden_trabajo.fecha_entrega, orden_trabajo.id_ord, laboratorio.img_art fotoPerfil, orden_trabajo.entregado, orden_trabajo.paciente, orden_trabajo.dientes FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join laboratorio on laboratorio.id_lab = relacion.id_lab left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod WHERE relacion.id_clie = $idUsuario and orden_trabajo.entregado = 1 ORDER BY orden_trabajo.fecha_orden DESC");


						break;

					case 'cancelado':

						$sql = $db -> query("SELECT laboratorio.nomb, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, orden_trabajo.fecha_orden fecha, orden_trabajo.cantidad precio, orden_trabajo.tipo, orden_trabajo.fecha_entrega, orden_trabajo.id_ord, laboratorio.img_art fotoPerfil, orden_trabajo.entregado, orden_trabajo.paciente, orden_trabajo.dientes FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join laboratorio on laboratorio.id_lab = relacion.id_lab left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod WHERE relacion.id_clie = $idUsuario and orden_trabajo.entregado = 4 ORDER BY orden_trabajo.fecha_orden DESC");

						break;

					default:
						# code...
						break;
				}

				break;
			
			case 'tecnico':

				switch ($filtro) {
					case 'prioritario':
						
						$sql = $db -> query("SELECT cliente.nomb, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, orden_trabajo.fecha_orden fecha, orden_trabajo.cantidad precio, orden_trabajo.tipo, orden_trabajo.fecha_entrega, orden_trabajo.id_ord, cliente.img_perfil fotoPerfil, orden_trabajo.entregado, orden_trabajo.paciente, orden_trabajo.dientes FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join cliente on cliente.id_clie = relacion.id_clie left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod WHERE relacion.id_lab = $idUsuario and orden_trabajo.entregado != 4 and orden_trabajo.entregado != 3 and orden_trabajo.entregado != 2 and orden_trabajo.entregado != 1 ORDER BY orden_trabajo.fecha_ultima_orden DESC");

						break;
					
					case 'nuevo':

						$fechaActual = date("Y-m-d");

						$sql = $db -> query("SELECT cliente.nomb, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, historial_orden_trabajo.fecha_hist_ord fecha, orden_trabajo.cantidad precio, orden_trabajo.tipo, orden_trabajo.fecha_entrega, orden_trabajo.id_ord, cliente.img_perfil fotoPerfil, orden_trabajo.entregado, orden_trabajo.paciente, orden_trabajo.dientes FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join cliente on cliente.id_clie = relacion.id_clie left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod left join historial_orden_trabajo on orden_trabajo.id_ord = historial_orden_trabajo.id_ord WHERE relacion.id_lab = $idUsuario and historial_orden_trabajo.fecha_hist_ord LIKE '$fechaActual%' and orden_trabajo.entregado = 0 ORDER BY orden_trabajo.fecha_ultima_orden DESC");

						break;

					case 'atrasado':

						//$sql = $db -> query("SELECT laboratorio.nomb, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, orden_trabajo.fecha_orden fecha, orden_trabajo.cantidad precio, orden_trabajo.tipo, orden_trabajo.fecha_entrega, orden_trabajo.id_ord, laboratorio.img_art fotoPerfil FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join laboratorio on laboratorio.id_lab = relacion.id_lab left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod WHERE relacion.id_clie = $idUsuario ORDER BY orden_trabajo.fecha_orden DESC");

						break;

					case 'finalizado':

						$sql = $db -> query("SELECT cliente.nomb, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, orden_trabajo.fecha_orden fecha, orden_trabajo.cantidad precio, orden_trabajo.tipo, orden_trabajo.fecha_entrega, orden_trabajo.id_ord, cliente.img_perfil fotoPerfil, orden_trabajo.entregado, orden_trabajo.paciente, orden_trabajo.dientes FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join cliente on cliente.id_clie = relacion.id_clie left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod WHERE relacion.id_lab = $idUsuario and orden_trabajo.entregado = 1 ORDER BY orden_trabajo.fecha_orden DESC");


						break;

					case 'cancelado':

						$sql = $db -> query("SELECT cliente.nomb, protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, orden_trabajo.fecha_orden fecha, orden_trabajo.cantidad precio, orden_trabajo.tipo, orden_trabajo.fecha_entrega, orden_trabajo.id_ord, cliente.img_perfil fotoPerfil, orden_trabajo.entregado, orden_trabajo.paciente, orden_trabajo.dientes FROM (orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel) left join cliente on cliente.id_clie = relacion.id_clie left join disp_trab on disp_trab.id_disp_trab = orden_trabajo.id_prod left join disp_orto on disp_orto.id_ort = orden_trabajo.id_orto left join protesis on protesis.id_pro = disp_trab.id_pro left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod WHERE relacion.id_lab = $idUsuario and orden_trabajo.entregado = 4 ORDER BY orden_trabajo.fecha_orden DESC");

						break;

					default:
						# code...
						break;
				}

				break;

			default:
				# code...
				break;
		}

		if($db -> rows($sql) >= 1){

			$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);

		} else {

			$respuesta = false;

		}

		return $respuesta;

	}

	public static function mdlOrdenesRelacion($idUsuario,$es,$idRel,$limite){

		$db = new Conexion();

		switch ($es) {
			case 'dentista':
				
				if( $limite != NULL ){

					//UTILIZAMOS EL LIMITE ESTABLECIDO

					$sql = $db -> query("SELECT orden_trabajo.id_prod, orden_trabajo.id_orto, orden_trabajo.fecha_orden, orden_trabajo.id_ord, orden_trabajo.cantidad FROM orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel WHERE orden_trabajo.id_rel = $idRel and relacion.id_clie = $idUsuario and orden_trabajo.entregado = 0 order by orden_trabajo.fecha_orden desc limit $limite");


				} else {

					//NO EXISTE LIMITE

					$sql = $db -> query("SELECT orden_trabajo.id_prod, orden_trabajo.id_orto, orden_trabajo.fecha_orden, orden_trabajo.id_ord, orden_trabajo.cantidad FROM orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel WHERE orden_trabajo.id_rel = $idRel and relacion.id_clie = $idUsuario and orden_trabajo.entregado = 0 order by orden_trabajo.fecha_orden desc");

				}

				break;

			case 'tecnico':

				if( $limite != NULL ){

					//UTILIZAMOS EL LIMITE ESTABLECIDO

					$sql = $db -> query("SELECT orden_trabajo.id_prod, orden_trabajo.id_orto, orden_trabajo.fecha_orden, orden_trabajo.id_ord, orden_trabajo.cantidad FROM orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel WHERE orden_trabajo.id_rel = $idRel and relacion.id_lab = $idUsuario and orden_trabajo.entregado = 0 order by orden_trabajo.fecha_orden desc limit $limite");


				} else {

					//NO EXISTE LIMITE

					$sql = $db -> query("SELECT orden_trabajo.id_prod, orden_trabajo.id_orto, orden_trabajo.fecha_orden, orden_trabajo.id_ord, orden_trabajo.cantidad FROM orden_trabajo left join relacion on orden_trabajo.id_rel = relacion.id_rel WHERE orden_trabajo.id_rel = $idRel and relacion.id_lab = $idUsuario and orden_trabajo.entregado = 0 order by orden_trabajo.fecha_orden desc");

				}

				break;
			
			default:
				
				break;
		}


		$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);

		return $respuesta;

		$db -> exit();

	}

	public static function mdlGenerarNuevaOrden($datos){

		$db = new Conexion();
		$notificaciones = new modeloNotificacion();
		$perfil = new modeloPerfil();
		$listaTrabajos = new modeloListaTrabajos();

		//require_once "vendor/phpqrcode/qrlib.php";

		$respuesta = false;

		function generateRandomString($length = 10) {
		    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
		    return $randomString;
		}

		$fechaOrden = date("Y-m-d H:i:s");

		//SE GENERA EL ID DE LA ORDEN
		$ordenCodigo = "ID-".generateRandomString();

		if($datos['metodoPago'] == "paypal"){
			$metodoPago = "paypal";
		} elseif($datos['metodoPago'] == "mercadopago"){
			$metodoPago = "mercadopago";
		}

		//SE INSERTA LA RELACION DEL LABORATORIO Y DENTISTA 
		$consultaRelacion = $db -> query("SELECT * FROM relacion WHERE id_clie = ".$datos['idUsuario']." and id_lab = ".$datos['idLaboratorio']."");

		if($db->rows($consultaRelacion) >= 1){

			$datosRelacion = $consultaRelacion -> fetch_array(MYSQLI_ASSOC);
			$idRel = $datosRelacion['id_rel'];

		} else {

			$db->query("INSERT INTO relacion (id_clie, id_lab) VALUES (".$datos['idUsuario'].", ".$datos['idLaboratorio'].")");
			$nuevaRelacion = $db->query("SELECT * FROM relacion WHERE id_clie = ".$datos['idUsuario']." and id_lab = ".$datos['idLaboratorio']."");
			$datosRelacion = $nuevaRelacion -> fetch_array(MYSQLI_ASSOC);

			$idRel = $datosRelacion['id_rel'];

		}

		////////////////////////////////////////////////////

		//SE ELIMINA EL SEGUIMIENTO DE ARCHIVOS POR LA BOX Y SE CAMBIA POR EL CÓDIGO DE ORDEN
		$sql = $db -> query("SELECT * FROM archivos WHERE id_box = ".$datos['idBox']."");
			if($db -> rows($sql) >= 1){
				if($db -> query("UPDATE archivos SET id_ord = '".$ordenCodigo."' WHERE id_box = ".$datos['idBox']."")){
				  $db -> query("UPDATE archivos SET id_box = NULL WHERE id_box = ".$datos['idBox']."");
			}
		}

		//echo $ordenCodigo;

		$tipo = $datos['tipo'];
		$dientes = $datos['dientes'];
		$trabajo = $datos['trabajo'];
		$nombrePaciente = $datos['nombrePaciente'];
		$colorimetria = $datos['colorimetria'];
		$direccionRecepcion = $datos['direccionRecepcion'];
		$direccionEntrega = $datos['direccionEntrega'];
		$entregado = $datos['entregado'];
		$cantidadTotal = $datos['cantidadTotal'];
		$fechaEntrega = $datos['fechaEntrega'];

		/*if( $db->query("INSERT INTO orden_trabajo (id_ord, id_rel, tipo, dientes, id_prod, paciente, colorimetria, dir_rec, dir_ent, fecha_orden, entregado, cantidad, fecha_entrega, fecha_ultima_orden) VALUES ('$ordenCodigo', $idRel, '$tipo', '$dientes', $trabajo, '$nombrePaciente', $colorimetria, $direccionRecepcion, $direccionEntrega, '$fechaOrden', $entregado, $cantidadTotal, '$fechaEntrega', '$fechaOrden')") ){
			echo "se hizo bien vato";
		} */


		//print_r( $infoTrabajo);

		//////////////////////////////////////////////////////////////////////////////////////

		if($db -> query ("DELETE FROM box WHERE id_box = ".$datos['idBox']."")){

			if( $datos['colorimetria'] == null ){

				$infoTrabajo = $listaTrabajos -> mdlInfoTrabajo($datos['trabajo'],"ortodoncia");

				//return $infoTrabajo;

				//SI ES ORTODONCIA

				if($db->query("INSERT INTO orden_trabajo (id_ord, id_rel, tipo, id_orto, paciente, dir_rec, dir_ent, fecha_orden, entregado, cantidad, fecha_entrega, fecha_ultima_orden) VALUES ('$ordenCodigo', $idRel, '$tipo', $trabajo, '$nombrePaciente', $direccionRecepcion, $direccionEntrega, '$fechaOrden', $entregado, $cantidadTotal, '$fechaEntrega', '$fechaOrden')")){

		          	//SE TIENE QUE DAR DE ALTA LA PRIMERA ORDEN DEL HISTORIAL DEL TRABAJO

					if($datos['estadoTrabajo'] == 1){
						$etapa = "Trabajo terminado";
					} else {
						$etapa = "Prueba";
					}

		            //SE DA DE ALTA LA ORDEN DE TRABAJO
		            if($db -> query("INSERT INTO historial_orden_trabajo (id_ord, fecha_hist_ord, pago, etapa, estado, estadoPago, fecha_rec, fecha_ent, hora_rec, descr) VALUES ('".$ordenCodigo."', '".$fechaOrden."', ".$datos['cantidadPago'].", '".$etapa."', ".$datos['estadoTrabajo'].", ".$datos['estadoEtapa'].", '".$datos['fechaRecepcion']."', '".$datos['fechaEntrega']."', '".$datos['horaRecepcion']."', '".$datos['especTrabajo']."')")){

		              $sqlHistorial = $db -> query("SELECT * FROM historial_orden_trabajo WHERE id_ord = '".$ordenCodigo."' order by fecha_hist_ord DESC");
		              $datosHistorial = $sqlHistorial -> fetch_array(MYSQLI_ASSOC);
		              $idOrdenHistorial = $datosHistorial['id_hist_ord'];

		              //SE DA DE ALTA EL ID DE TRANSACCION Y EL CODIGO DE LA ORDEN DEL TRABAJO PARA DARLE SEGUIMIENTO AL PAGO Y A LAS ORDENES.
		              $db -> query("INSERT INTO pagos(id_pago, id_orden, metodo, email, nombre, idPayer, paisPago) VALUES ('".$datos['idTransaccion']."', '".$idOrdenHistorial."', '".$metodoPago."', '".$datos['emailPago']."', '".$datos['nombrePago']."', '".$datos['idCompradorPago']."', '".$datos['paisPago']."')");

		              $respuesta = true;

		            }

		    	}

					
			} else {

				//SI ES PROTESIS

				$infoTrabajo = $listaTrabajos -> mdlInfoTrabajo($datos['trabajo'],"protesis");

				if( $db->query("INSERT INTO orden_trabajo (id_ord, id_rel, tipo, dientes, id_prod, paciente, colorimetria, dir_rec, dir_ent, fecha_orden, entregado, cantidad, fecha_entrega, fecha_ultima_orden) VALUES ('$ordenCodigo', $idRel, '$tipo', '$dientes', $trabajo, '$nombrePaciente', $colorimetria, $direccionRecepcion, $direccionEntrega, '$fechaOrden', $entregado, $cantidadTotal, '$fechaEntrega', '$fechaOrden')") ){

		          	//SE TIENE QUE DAR DE ALTA LA PRIMERA ORDEN DEL HISTORIAL DEL TRABAJO

					if($datos['estadoTrabajo'] == 1){
						$etapa = "Trabajo terminado";
					} else {
						$etapa = "Prueba";
					}

		            //SE DA DE ALTA LA ORDEN DE TRABAJO
		            if($db -> query("INSERT INTO historial_orden_trabajo (id_ord, fecha_hist_ord, pago, etapa, estado, estadoPago, fecha_rec, fecha_ent, hora_rec, descr) VALUES ('".$ordenCodigo."', '".$fechaOrden."', ".$datos['cantidadPago'].", '".$etapa."', ".$datos['estadoTrabajo'].", ".$datos['estadoEtapa'].", '".$datos['fechaRecepcion']."', '".$datos['fechaEntrega']."', '".$datos['horaRecepcion']."', '".$datos['especTrabajo']."')")){

		              $sqlHistorial = $db -> query("SELECT * FROM historial_orden_trabajo WHERE id_ord = '".$ordenCodigo."' order by fecha_hist_ord DESC");
		              $datosHistorial = $sqlHistorial -> fetch_array(MYSQLI_ASSOC);
		              $idOrdenHistorial = $datosHistorial['id_hist_ord'];

		              //SE DA DE ALTA EL ID DE TRANSACCION Y EL CODIGO DE LA ORDEN DEL TRABAJO PARA DARLE SEGUIMIENTO AL PAGO Y A LAS ORDENES.
		              $db -> query("INSERT INTO pagos(id_pago, id_orden, metodo, email, nombre, idPayer, paisPago) VALUES ('".$datos['idTransaccion']."', '".$idOrdenHistorial."', '".$metodoPago."', '".$datos['emailPago']."', '".$datos['nombrePago']."', '".$datos['idCompradorPago']."', '".$datos['paisPago']."')");

		              $respuesta = true;

		            }

		    	}

			}

			//MANEJO DEL ENVIO DE NOTIFICACIONES

			$nombreTrabajo = $infoTrabajo['nomb'];

			$idLaboratorio = $datos['idLaboratorio'];
			$idUsuario = $datos['idUsuario'];

			$perfilLaboratorio = $perfil -> mdlDatosPerfil($idLaboratorio,"tecnico");
			$perfilDentista = $perfil -> mdlDatosPerfil($idUsuario,"dentista");

			$tituloLaboratorio = '¡Felicidades! Tienes una nueva orden';
			$mensajeLaboratorio = 'El doctor <b>'.$perfilDentista["nomb"].'</b> ha generado una orden de <b>'.$nombreTrabajo.'</b>';
			
			$tituloDentista = '¡Felicidades! Haz generado una nueva orden';
			$mensajeDentista = 'Generaste una orden de <b>'.$nombreTrabajo.'</b>  para el laboratorio <b>'.$perfilLaboratorio["nomb"].'</b>';
			
			$urlDestino = "ordenes?orden=".$ordenCodigo;
			$color = '#ff9900';
			$icono = 'fas fa-tooth';
			$tipoNotificacion = 'nuevaOrden';
			$auxiliar = $ordenCodigo;

			$notificaciones -> mdlInsertarNuevaNotificacion($idUsuario,"dentista",$tituloDentista,$mensajeDentista,$urlDestino,$color,$icono,$tipoNotificacion,$auxiliar); //ENVIAMOS LA NOTIFICACION AL DENTISTA

			if($datos['entregado'] == 0){ //SÓLO ENVIAR NOTIFICACIÓN AL TECNICO CUANDO EL PAGO DE LA ORDEN SE HIZO CORRECTAMENTE, LOS ESTADOS 1, 2, 3 Y 4 SON UTILIZADOS PARA OTRA COSA (1 = entregado, 2 = pending, 3 = in_process y 4 = cancelled)

				$notificaciones -> mdlInsertarNuevaNotificacion($idLaboratorio,"tecnico",$tituloLaboratorio,$mensajeLaboratorio,$urlDestino,$color,$icono,$tipoNotificacion,$auxiliar); //ENVIAMOS LA NOTIFICACION AL TECNICO
			}

		}

		return $respuesta;

		$db -> exit();

	}

	//********METODOS PARA LA INFORMACION DE LA ORDEN DE UN TRABAJO DE PROTESIS*******************//

	public static function mdlExisteOrdenBox($usuario,$idTrabajo,$dientesSeleccionados,$direccionRecepcion,$direccionEntrega,$nombrePaciente,$especTrabajo,$colorimetria,$estadoTrabajo,$fechaRecepcion,$horaRecepcion,$porcientoPagar,$tipo,$fechaEntrega){

		$db = new Conexion();

		if($colorimetria != NULL){ //SI EXISTE ALGUN DATO DE COLORIMETRIA, SIGNIFICA QUE ES UN TRABAJO DE PROTESIS

			$sql = $db -> query("SELECT * FROM box WHERE id_usuario = $usuario and id_prod = $idTrabajo and recepcion = $direccionRecepcion and entrega = $direccionEntrega and paciente = '$nombrePaciente' and specs = '$especTrabajo' and colorimetria = $colorimetria and estado = $estadoTrabajo and fecha_rec = '$fechaRecepcion' and hora = '$horaRecepcion' and tipo = '$tipo' and fecha_ent = '$fechaEntrega' and dientes = '$dientesSeleccionados'");

			//return "existe colorimetria";


		} else { //SI LA COLORIMETRIA ES NULL, SIGNIFICA QUE EL TRABAJO ES DE ORTODONCIA

			$sql = $db -> query("SELECT * FROM box WHERE id_usuario = $usuario and id_prod_ort = $idTrabajo and recepcion = $direccionRecepcion and entrega = $direccionEntrega and paciente = '$nombrePaciente' and specs = '$especTrabajo' and estado = $estadoTrabajo and fecha_rec = '$fechaRecepcion' and hora = '$horaRecepcion' and fecha_ent = '$fechaEntrega' and dientes = '$dientesSeleccionados'");
			//return "no existe colorimetria";

		}

		if($db->rows($sql) >= 1){

			//ŚÍ EXISTE, LE RETORNAMOS EL ID DE LA BOX

			$datos = $sql -> fetch_array(MYSQLI_ASSOC);

			return $datos['id_box'];

		} else {

			//SI NO EXISTE, RETORNAMOS FALSO

			return false;

		}

		$db -> exit();


	}

	public static function mdlAgregarOrdenBox($usuario,$idTrabajo,$dientesSeleccionados,$direccionRecepcion,$direccionEntrega,$nombrePaciente,$especTrabajo,$colorimetria,$estadoTrabajo,$fechaRecepcion,$horaRecepcion,$porcientoPagar,$tipo,$fechaEntrega){

		$db = new Conexion();

		if($colorimetria != NULL){ //SI EXISTE ALGUN DATO DE COLORIMETRIA, SIGNIFICA QUE ES UN TRABAJO DE PROTESIS

			if( $sql = $db -> query("INSERT INTO box (id_usuario, tipo, dientes, id_prod, recepcion, entrega, paciente, specs, colorimetria, estado, fecha_rec, hora, porcentajePagar, fecha_ent) VALUES ($usuario,'$tipo', '$dientesSeleccionados',$idTrabajo,$direccionRecepcion,$direccionEntrega,'$nombrePaciente','$especTrabajo',$colorimetria,$estadoTrabajo, '$fechaRecepcion', '$horaRecepcion', $porcientoPagar, '$fechaEntrega')") ){


				return true;

			} else {

				return false;

			}

		} else { //SI LA COLORIMETRIA ES NULL, SIGNIFICA QUE EL TRABAJO ES DE ORTODONCIA

			if ($db -> query("INSERT INTO box (id_usuario, tipo, dientes, id_prod_ort, recepcion, entrega, paciente, specs, estado, fecha_rec, hora, porcentajePagar, fecha_ent) VALUES ($usuario,'$tipo','$dientesSeleccionados',$idTrabajo,$direccionRecepcion,$direccionEntrega,'$nombrePaciente','$especTrabajo', $estadoTrabajo, '$fechaRecepcion', '$horaRecepcion', $porcientoPagar, '$fechaEntrega')")){

				return true;

			} else {

				return false;

			}

		}


		$db -> exit();

	}

	public static function mdlExisteImagenes($idBox){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM archivos WHERE id_box = $idBox");

		if($db -> rows($sql) >= 1){

			$resultado = $sql -> fetch_all(MYSQLI_ASSOC);

		} else {

			$resultado = false;

		}

		return $resultado;

	}

	public static function mdlInsertarImagen($nombreImagen,$idBox){

		$db = new Conexion();

		if($db -> query("INSERT INTO archivos (nombre, id_box) VALUES ('$nombreImagen',$idBox)")){

			return true;

		} else {

			return false;

		}


		$db -> exit();

	}

	public static function mdlInfoOrden($idTrabajo,$tipo,$colorimetria){

		$db = new Conexion();

		if($colorimetria != NULL){//SI EXISTE ALGUNA COLORIMETRIA, SIGNIFICA QUE ES UN TRABAJO DE PROTESIS

			if($tipo == "urgente"){ //SÍ ES UN TRABAJO URGENTE, LLEVAR LA INFORMACIÓN CORRESPONDIENTE DEL PRODUCTO URGENTE

				$sql = $db -> query("SELECT protesis.nomb trabajo, laboratorio.nomb laboratorio, laboratorio.descr, disp_trab_urg.precio, disp_trab_urg.dias_entrega tiempo, laboratorio.nomb_art artista, material.nomb material, laboratorio.id_lab idLaboratorio, disp_trab_urg.id_disp_trab idTrabajo FROM protesis left join disp_trab on protesis.id_pro = disp_trab.id_pro, laboratorio, material, disp_trab_urg where disp_trab.id_disp_trab = $idTrabajo and laboratorio.id_lab = disp_trab.id_lab and material.id_mat = protesis.id_mat and disp_trab.id_disp_trab = disp_trab_urg.id_disp_trab");
				
			} else { //SÍ ES UN TRABAJO ORDINARIO, LLEVAR LA INFORMACIÓN CORRESPONDIENTE DEL PRODUCTO ORDINARIO

				$sql = $db -> query("SELECT protesis.nomb trabajo, laboratorio.nomb laboratorio, laboratorio.descr, disp_trab.precio, disp_trab.dias_entrega tiempo, laboratorio.nomb_art artista, material.nomb material, laboratorio.id_lab idLaboratorio, disp_trab.id_disp_trab idTrabajo FROM protesis left join disp_trab on protesis.id_pro = disp_trab.id_pro, laboratorio, material where disp_trab.id_disp_trab = $idTrabajo and laboratorio.id_lab = disp_trab.id_lab and material.id_mat = protesis.id_mat");

			}

		} else { // SI COLORIMETRIA ES NULL, SIGNIFICA QUE ES UNA ORDEN DE ORTODONCIA

			if($tipo == "urgente"){//SÍ ES UN TRABAJO URGENTE, LLEVAR LA INFORMACIÓN CORRESPONDIENTE DEL PRODUCTO URGENTE ORTODONCIA

				$sql = $db->query("SELECT ortodoncia_prod.nomb trabajo, laboratorio.nomb laboratorio, laboratorio.descr, disp_orto_urg.precio, disp_orto_urg.dias_terminado tiempo, laboratorio.nomb_art artista, laboratorio.id_lab idLaboratorio, laboratorio.img_orden imgOrden, disp_orto.id_ort idTrabajo FROM disp_orto left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod left join laboratorio on disp_orto.id_lab = laboratorio.id_lab, disp_orto_urg WHERE disp_orto.id_ort = $idTrabajo and disp_orto.id_ort = disp_orto_urg.id_ort");

			} else {//SÍ ES UN TRABAJO ORDINARIO, LLEVAR LA INFORMACIÓN CORRESPONDIENTE DEL PRODUCTO ORDINARIO ORTODONCIA

				$sql = $db->query("SELECT ortodoncia_prod.nomb trabajo, laboratorio.nomb laboratorio, laboratorio.descr, disp_orto.precio, disp_orto.dias_entrega tiempo, laboratorio.nomb_art artista, laboratorio.id_lab idLaboratorio, laboratorio.img_orden imgOrden, disp_orto.id_ort idTrabajo FROM disp_orto left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = disp_orto.id_ort_prod left join laboratorio on disp_orto.id_lab = laboratorio.id_lab WHERE disp_orto.id_ort = $idTrabajo");
			}
			

		}


		$resultado = $sql -> fetch_array(MYSQLI_ASSOC);

		return $resultado;

		$db -> exit();

	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	


}

 ?>