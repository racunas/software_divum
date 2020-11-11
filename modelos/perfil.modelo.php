<?php 

require_once "conexion.php";

class modeloPerfil{

	public static function mdlObtenerCalificaciones($idUsuario,$es){

		$db = new Conexion();

		switch ($es) {
			case 'dentista':
				$respuesta = null;

				break;

			case 'tecnico':
				$sql = $db -> query("SELECT calificacion.* FROM (calificacion left join orden_trabajo on calificacion.id_ord = orden_trabajo.id_ord) left join relacion on orden_trabajo.id_rel = relacion.id_rel WHERE relacion.id_lab = $idUsuario");
				break;
			
			default:
				$respuesta = null;
				break;
		}

		if($db->rows($sql)>=1){

			$respuesta = $sql->fetch_all(MYSQLI_ASSOC);

		} else {

			$respuesta = null;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlTodoSepomex($idEstado){

		$db = new Conexion();

		if($idEstado != NULL){

			$sql = $db -> query("SELECT * FROM sepomex WHERE idEstado = $idEstado");

		} else {

			$sql = $db -> query("SELECT * FROM sepomex");

		}

		$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);

		return $respuesta;

	}

	public static function mdlSepomex($id){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM sepomex WHERE id = $id");

		if( $db->rows($sql)>=1 ){

			$resultado = $sql -> fetch_array(MYSQLI_ASSOC);

			return $resultado;

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlActualizarFotoPreOrden($idUsuario,$nombreCompleto){

		$db = new Conexion();

		if( $db -> query("UPDATE laboratorio SET img_prod = '$nombreCompleto' WHERE id_lab = $idUsuario") ){

			return "ok";

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlActualizarFotoPerfil($idUsuario,$tipo,$nombreFotoPerfil){

		$db = new Conexion();

		switch ($tipo) {
			case 'tecnico':
				
				if( $db -> query("UPDATE laboratorio SET img_art = '$nombreFotoPerfil' WHERE id_lab = $idUsuario") ){

					return "ok";

				} else {

					return false;

				}

				break;

			case 'dentista':

				if( $db -> query("UPDATE dentista SET img_perfil = '$nombreFotoPerfil' WHERE id_clie = $idUsuario") ){

					return "ok";

				} else {

					return false;

				}

				break;
			
			default:
					
				return false;

				break;
		}

		$db -> exit();

	}

	public static function mdlDireccionPredeterminada($idDireccion,$idUsuario){

		$db = new Conexion();

		if( $db -> query("UPDATE dentista SET direccion_predet = $idDireccion WHERE id_clie = $idUsuario") ){

			return true;

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlEliminarDireccion($idDireccion,$idUsuario){

		$db = new Conexion();

		if( $db -> query("DELETE FROM direccion WHERE id_direc = $idDireccion and id_usuario = $idUsuario") ){

			return true;

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlObtenerDireccion($cp){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM sepomex WHERE cp = '$cp'");

		$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);

		return $respuesta;

		$db -> exit();

	}

	public static function mdlDatosPerfil($id,$tipo){

		$db = new Conexion();

		switch ($tipo) {

			case 'tecnico':
		
				$sql = $db -> query("SELECT * FROM laboratorio WHERE id_lab = $id");
				
				break;

			case 'dentista':

				$sql = $db -> query("SELECT * FROM dentista WHERE id_clie = $id");

				break;
			
			default:

				return false;
				
				break;
				
		}


		$datos = $sql -> fetch_array(MYSQLI_ASSOC);

		$db -> exit();

		return $datos;

	}

	public static function mdlEstadoRepublica($idEstado){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM republica WHERE id_rep = $idEstado");

		$datos = $sql -> fetch_array(MYSQLI_ASSOC);

		$db -> exit();

		return $datos;

	}

	public static function mdlTodosEstados(){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM republica");

		$datos = $sql -> fetch_all(MYSQLI_ASSOC);

		$db -> exit();

		return $datos;

	}

	public static function mdlActualizarDatosDentista($nombre,$telefono,$especialidad,$fechaNacimiento,$clinica,$id){

		$db = new Conexion();

		///////////////////////////////////////////////////////////////////

		if($sql = $db -> query("UPDATE dentista SET nomb = '$nombre', tel = '$telefono', esp = '$especialidad', fecha_nac = '$fechaNacimiento', clinica = '$clinica' WHERE id_clie = $id")){

				return true;

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlActualizarDatosTecnico($nombTecnico,$nombLaboratorio,$descrLaboratorio,$telefonoLaboratorio,$calle,$idSepomex,$id){

		$db = new Conexion();

		//PRIMERO VERIFICAMOS SI ES LA PRIMERA VEZ QUE MODIFICA SU PERFIL//

		$sql2 = $db -> query("SELECT * FROM laboratorio WHERE id_lab = $id");

		$perfil = $sql2 -> fetch_array(MYSQLI_ASSOC);

		///////////////////////////////////////////////////////////////////

		if($sql = $db -> query("UPDATE laboratorio SET nomb = '$nombLaboratorio', direc = '$calle', tel = '$telefonoLaboratorio', id_rep = $idSepomex, nomb_art = '$nombTecnico', descr = '$descrLaboratorio' WHERE id_lab = $id")){

			if( ($perfil['perfil_completo'] == 0) /*&&
				($perfil['img_art'] != 'imgRelleno.png') &&
				($perfil['img_prod'] != 'imgRellenoProducto.png')*/ ){
				
				$db -> query("UPDATE lista_precios_protesis SET disponible = 1 WHERE id_lab = $id");
				$db -> query("UPDATE lista_precios_ortodoncia SET disponible = 1 WHERE id_lab = $id");
				$db -> query("UPDATE laboratorio SET perfil_completo = 1 WHERE id_lab = $id");	
				$db -> query("UPDATE lista_precios_protesis, lista_precios_protesis_urg SET lista_precios_protesis_urg.disponible = 1 WHERE lista_precios_protesis.id_lista_precios_protesis = lista_precios_protesis_urg.id_lista_precios_protesis");
				$db -> query("UPDATE lista_precios_ortodoncia, lista_precios_ortodoncia_urg SET lista_precios_ortodoncia_urg.disponible = 1 WHERE lista_precios_ortodoncia.id_ort = lista_precios_ortodoncia_urg.id_ort");

				return true;

			} else {

				return true;

			}


		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlDireccion($id,$tipo,$idDirec){

		$db = new Conexion();

		switch ($tipo) {

			case 'tecnico':

				if($idDirec != NULL){

					$sql = $db -> query("SELECT * FROM sepomex WHERE id = $idDirec");
					
					if($db -> rows($sql) >= 1){

						$resultado = $sql -> fetch_array(MYSQLI_ASSOC);
					
					} else {

						$resultado = null;

					}

				} else {

					$resultado = null;

				}

				break;

			case 'dentista':

				if($idDirec != NULL){

					$sql = $db -> query("SELECT direccion.id_direc, direccion.calle, direccion.id_rep, sepomex.* FROM (sepomex left join direccion on sepomex.id = direccion.id_rep) left join dentista on direccion.id_usuario = dentista.id_clie WHERE dentista.id_clie = $id and direccion.id_direc = $idDirec");


				} else {

					$sql = $db -> query("SELECT direccion.id_direc, direccion.calle, direccion.id_rep, sepomex.* FROM (sepomex left join direccion on sepomex.id = direccion.id_rep) left join dentista on direccion.id_usuario = dentista.id_clie WHERE dentista.id_clie = $id");

				}

				if($db -> rows($sql) >= 1){

					$resultado = $sql -> fetch_all(MYSQLI_ASSOC);
				
				} else {

					$resultado = null;

				}


				break;
			
			
			default:
				# code...
				break;
		}




		$db -> exit();

		return $resultado;

	}

	public static function mdlAgregarDireccion($calle, $idSepomex, $cp, $id){

		$db = new Conexion();

		if( $db -> query("INSERT INTO direccion (id_usuario, calle, cp, id_rep) VALUES ($id, '$calle', '$cp', $idSepomex)" ) ){

			$insertDirec = $db -> insert_id; //obtenemos el ultimo id que insertamos;

			return $insertDirec;

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlNombreFotoPerfil($id,$tipo){

		$db = new Conexion();

		switch ($tipo) {

			case 'tecnico':
		
				$sql = $db -> query("SELECT img_art FROM laboratorio WHERE id_lab = $id");
				
				break;

			case 'dentista':

				$sql = $db -> query("SELECT img_perfil FROM dentista WHERE id_clie = $id");

				break;
			
			default:

				return false;
				
				break;
				
		}

		$datos = $sql -> fetch_array(MYSQLI_ASSOC);

		$db -> exit();

		return $datos;

	}


}


 ?>