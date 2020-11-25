<?php 

require_once "conexion.php";

class modeloBox{

	public static function mdlActualizarOrdenPendiente($idBox,$nombrePaciente,$pagoInicial,$tipoOrden,$fechaRecepcion,$direccionRecepcion,$fechaEntrega,$direccionEntrega,$estadoOrden,$descripcionOrden,$colorimetria,$numDientesSeleccionados,$dientesSeleccionados,$stringImagenes,$idUsuario){

		$db = new Conexion();

		$respuesta = true;

		if( $db -> query("UPDATE box SET tipo = '$tipoOrden', dientes = '$dientesSeleccionados', recepcion = $direccionRecepcion, entrega = $direccionEntrega, paciente = '$nombrePaciente', specs = '$descripcionOrden', colorimetria = $colorimetria, estado = $estadoOrden, fecha_rec = '$fechaRecepcion', porcentajePagar = $pagoInicial, fecha_ent = '$fechaEntrega' WHERE id_box = $idBox and id_usuario = $idUsuario") ){

			if(strlen($stringImagenes) >= 1){

				if( $db -> query("DELETE FROM imagenes WHERE id_box = $idBox") ){

					$imagenesNuevas = explode(",", $stringImagenes);

					for ($i=0; $i < count($imagenesNuevas); $i++) { 
						
						if(! $db -> query("INSERT INTO imagenes (nombre, id_box) VALUES ('".$imagenesNuevas[$i]."', $idBox)") ){
							$respuesta = false;
						}

					}

				} else {

					$respuesta = false;

				}
			
			}

		} else {

			$respuesta = false;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlEliminarImagenBox($idBox,$idImagen){

		$db = new Conexion();

		if( $db -> query("DELETE FROM imagenes WHERE id_archivo = $idImagen and id_box = $idBox") ){

			return true;

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlEliminarOrden($idUsuario,$idBox){

		$db = new Conexion();

		if( $db -> query("DELETE FROM box WHERE id_box = $idBox and id_usuario = $idUsuario") ){

			return true;

		}else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlOrdenesBox($idUsuario){

		$db = new Conexion();

		$sql = $db -> query("SELECT protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, laboratorio.img_art imagen, lista_precios_protesis.precio precioProtesis, lista_precios_ortodoncia.precio precioOrtodoncia, box.paciente, box.porcentajePagar, box.id_box FROM (box left join lista_precios_protesis on box.id_prod = lista_precios_protesis.id_lista_precios_protesis) left join lista_precios_ortodoncia on box.id_prod_ort = lista_precios_ortodoncia.id_ort left join protesis on lista_precios_protesis.id_pro = protesis.id_pro left join ortodoncia_prod on lista_precios_ortodoncia.id_ort_prod = ortodoncia_prod.id_ort_prod, laboratorio WHERE box.id_usuario = $idUsuario and laboratorio.id_lab = lista_precios_protesis.id_lab UNION SELECT protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, laboratorio.img_art imagen, lista_precios_protesis.precio precioProtesis, lista_precios_ortodoncia.precio precioOrtodoncia, box.paciente, box.porcentajePagar, box.id_box FROM (box left join lista_precios_protesis on box.id_prod = lista_precios_protesis.id_lista_precios_protesis) left join lista_precios_ortodoncia on box.id_prod_ort = lista_precios_ortodoncia.id_ort left join protesis on lista_precios_protesis.id_pro = protesis.id_pro left join ortodoncia_prod on lista_precios_ortodoncia.id_ort_prod = ortodoncia_prod.id_ort_prod, laboratorio WHERE box.id_usuario = $idUsuario and laboratorio.id_lab = lista_precios_ortodoncia.id_lab ORDER BY `paciente` ASC");

		if( $db -> rows($sql) >= 1 ){

			$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);

			return $respuesta;

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlExisteBox($idBox){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM box WHERE id_box = $idBox");

		if($db->rows($sql) >= 1){

			return true;

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlDatosBox($idBox){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM box WHERE id_box = $idBox");

		$respuesta = $sql -> fetch_array(MYSQLI_ASSOC);

		return $respuesta;

		$db -> exit();

	}

	public static function mdlImagenesBox($idBox){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM imagenes WHERE id_box = $idBox");

		if($db -> rows($sql) >= 1){

			$resultado = $sql -> fetch_all(MYSQLI_ASSOC);

			return $resultado;

		} else {

			return false;

		}

		$db -> exit();

	}

}	

 ?>