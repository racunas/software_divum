<?php 

require_once "conexion.php";

class modeloBox{

	public static function mdlActualizarOrdenPendiente($idBox,$nombrePaciente,$pagoInicial,$tipoOrden,$fechaRecepcion,$direccionRecepcion,$fechaEntrega,$direccionEntrega,$estadoOrden,$descripcionOrden,$colorimetria,$numDientesSeleccionados,$dientesSeleccionados,$stringImagenes,$idUsuario){

		$db = new Conexion();

		$respuesta = true;

		if( $db -> query("UPDATE box SET tipo = '$tipoOrden', dientes = '$dientesSeleccionados', recepcion = $direccionRecepcion, entrega = $direccionEntrega, paciente = '$nombrePaciente', specs = '$descripcionOrden', colorimetria = $colorimetria, estado = $estadoOrden, fecha_rec = '$fechaRecepcion', porcentajePagar = $pagoInicial, fecha_ent = '$fechaEntrega' WHERE id_box = $idBox and id_usuario = $idUsuario") ){

			if(strlen($stringImagenes) >= 1){

				if( $db -> query("DELETE FROM archivos WHERE id_box = $idBox") ){

					$imagenesNuevas = explode(",", $stringImagenes);

					for ($i=0; $i < count($imagenesNuevas); $i++) { 
						
						if(! $db -> query("INSERT INTO archivos (nombre, id_box) VALUES ('".$imagenesNuevas[$i]."', $idBox)") ){
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

		if( $db -> query("DELETE FROM archivos WHERE id_archivo = $idImagen and id_box = $idBox") ){

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

		$sql = $db -> query("SELECT protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, laboratorio.img_art imagen, disp_trab.precio precioProtesis, disp_orto.precio precioOrtodoncia, box.paciente, box.porcentajePagar, box.id_box FROM (box left join disp_trab on box.id_prod = disp_trab.id_disp_trab) left join disp_orto on box.id_prod_ort = disp_orto.id_ort left join protesis on disp_trab.id_pro = protesis.id_pro left join ortodoncia_prod on disp_orto.id_ort_prod = ortodoncia_prod.id_ort_prod, laboratorio WHERE box.id_usuario = $idUsuario and laboratorio.id_lab = disp_trab.id_lab UNION SELECT protesis.nomb protesis, ortodoncia_prod.nomb ortodoncia, laboratorio.img_art imagen, disp_trab.precio precioProtesis, disp_orto.precio precioOrtodoncia, box.paciente, box.porcentajePagar, box.id_box FROM (box left join disp_trab on box.id_prod = disp_trab.id_disp_trab) left join disp_orto on box.id_prod_ort = disp_orto.id_ort left join protesis on disp_trab.id_pro = protesis.id_pro left join ortodoncia_prod on disp_orto.id_ort_prod = ortodoncia_prod.id_ort_prod, laboratorio WHERE box.id_usuario = $idUsuario and laboratorio.id_lab = disp_orto.id_lab ORDER BY `paciente` ASC");

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

		$sql = $db -> query("SELECT * FROM archivos WHERE id_box = $idBox");

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