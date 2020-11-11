<?php 

class mdlInfoproducto{

	public static function mdlOrdenesUrgentesMaximas($idUsuario){
		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM `orden_trabajo` left join relacion on relacion.id_rel = orden_trabajo.id_rel WHERE YEARWEEK(orden_trabajo.fecha_orden) = YEARWEEK(CURDATE()) and orden_trabajo.tipo = 'urgente' and relacion.id_clie = $idUsuario");

		$resultado = ($db -> rows($sql) >= 5) ? true : false;

		return $resultado;

	}

	public static function mdlCalificaciones($idTrabajo,$tipoTrabajo){

		$db = new Conexion();

		switch ($tipoTrabajo) {
			case 'protesis':
				
				$sql = $db -> query("SELECT dentista.img_perfil fotoPerfil, dentista.nomb nombreDentista, calificacion.opinion, calificacion.precio, calificacion.tiempo, calificacion.calidad, calificacion.fecha FROM calificacion left join dentista on calificacion.id_clie = dentista.id_clie WHERE calificacion.id_trab = $idTrabajo");

				break;

			case 'ortodoncia':

				$sql = $db -> query("SELECT dentista.img_perfil fotoPerfil, dentista.nomb nombreDentista, calificacion.opinion, calificacion.precio, calificacion.tiempo, calificacion.calidad, calificacion.fecha FROM calificacion left join dentista on calificacion.id_clie = dentista.id_clie WHERE calificacion.id_orto = $idTrabajo");

				break;
			
			default:
				return false;
				break;
		}

		if( $db -> rows($sql) >= 1 ){
			$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);
		} else{
			$respuesta = false;
		}

		return $respuesta;

		$db -> exit();

	}

	public static function datosInfo($laboratorio,$trabajo){

		$db = new Conexion();

		$sql = $db -> query("SELECT protesis.nomb trabajo, laboratorio.nomb laboratorio, laboratorio.nomb_art artista, laboratorio.direc direccion, material.nomb material, lista_precios_protesis.precio precio, lista_precios_protesis.dias_entrega tiempo, laboratorio.img_art imagen, lista_precios_protesis.id_lista_precios_protesis id, laboratorio.img_prod imgProducto, laboratorio.descr descripcion, lista_precios_protesis.porciento_inicial porcentaje, laboratorio.id_rep, lista_precios_protesis_urg.precio precioUrgente, lista_precios_protesis_urg.dias_entrega tiempoUrgente, lista_precios_protesis_urg.disponible disponibleUrgente FROM (lista_precios_protesis left join lista_precios_protesis_urg on lista_precios_protesis.id_lista_precios_protesis = lista_precios_protesis_urg.id_lista_precios_protesis), protesis, laboratorio, material WHERE protesis.id_pro = lista_precios_protesis.id_pro and laboratorio.id_lab = lista_precios_protesis.id_lab and lista_precios_protesis.disponible = 1 and material.id_mat = protesis.id_mat and laboratorio.nomb = '$laboratorio' and protesis.nomb = '$trabajo'");

		if($db->rows($sql) < 1){
			$sql = $db -> query("SELECT ortodoncia_prod.nomb trabajo, laboratorio.nomb laboratorio, laboratorio.nomb_art artista, laboratorio.direc direccion, lista_precios_ortodoncia.precio precio, lista_precios_ortodoncia.dias_entrega tiempo, lista_precios_ortodoncia.id_ort id, laboratorio.img_prod imgProducto, laboratorio.descr descripcion, lista_precios_ortodoncia.porcentaje, laboratorio.id_rep, lista_precios_ortodoncia_urg.precio precioUrgente, lista_precios_ortodoncia_urg.dias_terminado tiempoUrgente, lista_precios_ortodoncia_urg.disponible disponibleUrgente FROM lista_precios_ortodoncia left join ortodoncia_prod on lista_precios_ortodoncia.id_ort_prod = ortodoncia_prod.id_ort_prod left join laboratorio on laboratorio.id_lab = lista_precios_ortodoncia.id_lab left join lista_precios_ortodoncia_urg on lista_precios_ortodoncia_urg.id_ort = lista_precios_ortodoncia.id_ort WHERE laboratorio.nomb = '$laboratorio' and ortodoncia_prod.nomb = '$trabajo'");
		}

		$res = $sql -> fetch_array(MYSQLI_ASSOC);

		return $res;

	}

	public static function mdlTipoTrabajo($laboratorio,$trabajo){

		$db = new Conexion();

		$sql = $db -> query("SELECT protesis.nomb trabajo, laboratorio.nomb laboratorio, laboratorio.nomb_art artista, laboratorio.direc direccion, material.nomb material, lista_precios_protesis.precio precio, lista_precios_protesis.dias_entrega tiempo, laboratorio.img_art imagen, lista_precios_protesis.id_lista_precios_protesis id, laboratorio.img_prod imgProducto, laboratorio.descr descripcion, lista_precios_protesis.porciento_inicial porcentaje FROM lista_precios_protesis, protesis, laboratorio, material WHERE protesis.id_pro = lista_precios_protesis.id_pro and laboratorio.id_lab = lista_precios_protesis.id_lab and lista_precios_protesis.disponible = 1 and material.id_mat = protesis.id_mat and laboratorio.nomb = '$laboratorio' and protesis.nomb = '$trabajo'");

		if($db->rows($sql) < 1){

			//$sql = $db -> query("SELECT ortodoncia_prod.nomb trabajo, laboratorio.nomb laboratorio, laboratorio.nomb_art artista, laboratorio.direc direccion, lista_precios_ortodoncia.precio precio, lista_precios_ortodoncia.dias_entrega tiempo, lista_precios_ortodoncia.id_ort id, laboratorio.img_prod imgProducto, laboratorio.descr descripcion, lista_precios_ortodoncia.porcentaje FROM lista_precios_ortodoncia left join ortodoncia_prod on lista_precios_ortodoncia.id_ort_prod = ortodoncia_prod.id_ort_prod left join laboratorio on laboratorio.id_lab = lista_precios_ortodoncia.id_lab WHERE laboratorio.nomb = '$laboratorio' and ortodoncia_prod.nomb = '$trabajo'");

			$res = "ortodoncia";

		} else {

			$res = "protesis";

		}

		return $res;

	}

	public static function mdlColorimetria($idColor){

		$db = new Conexion();

		if($idColor != NULL){

			$sql = $db -> query("SELECT * FROM color WHERE id_col = $idColor");

		} else {

			$sql = $db -> query("SELECT * FROM color");		

		}

		$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);

		return $respuesta;

	}

}

 ?>