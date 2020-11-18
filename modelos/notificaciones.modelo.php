<?php 

require_once "conexion.php";

class modeloNotificacion{

	public static function mdlObtenerTodasLasNotificaciones($idUsuario,$tipo){

		$db = new Conexion();

		switch ($tipo) {

			case 'dentista':

				$sql = $db -> query("SELECT * FROM notificaciones WHERE id_clie = $idUsuario ORDER BY fecha DESC");
				
				break;

			case 'tecnico':

				$sql = $db -> query("SELECT * FROM notificaciones WHERE id_lab = $idUsuario ORDER BY fecha DESC");
				
				break;
			
			default:

				$respuesta = null;

				break;
		}

		$respuesta = ($db->rows($sql)>=1) ? $sql->fetch_all(MYSQLI_ASSOC) : null;

		return $respuesta;

	}

	public static function mdlMarcarNotificacionesLeidas($idUsuario,$tipo,$fecha){

		$db = new Conexion();

		$respuesta = false;

		switch ($tipo) {
			case 'tecnico':
				if($db->query("UPDATE notificaciones SET visto = 1 WHERE id_lab = $idUsuario and fecha LIKE '%$fecha%'")){
					$respuesta = true;
				}
				break;

			case 'dentista':
				if($db->query("UPDATE notificaciones SET visto = 1 WHERE id_clie = $idUsuario and fecha LIKE '%$fecha%'")){
					$respuesta = true;
				}
				break;
			
			default:
				$respuesta = false;
				break;
		}

		return $respuesta;

	}

	public static function mdlObtenerNotificacionesNoVistas($idUsuario,$tipo,$fecha){

		$db = new Conexion();

		switch ($tipo) {
			case 'tecnico':
				$sql = $db -> query("SELECT * FROM notificaciones WHERE id_lab = $idUsuario and fecha LIKE '%$fecha%' and visto = 0 order by fecha desc");
				break;

			case 'dentista':
				$sql = $db -> query("SELECT * FROM notificaciones WHERE id_clie = $idUsuario and fecha LIKE '%$fecha%' and visto = 0 order by fecha desc");
				break;
			
			default:
				$sql = null;
				$respuesta = 0;
				break;
		}

		$respuesta = ($db->rows($sql)>=1) ? $db -> rows($sql) : 0;

		return $respuesta;

	}

	public static function mdlObtenerNotificaciones($idUsuario,$tipo,$fecha){

		$db = new Conexion();

		switch ($tipo) {
			case 'tecnico':
				$sql = $db -> query("SELECT * FROM notificaciones WHERE id_lab = $idUsuario and fecha LIKE '%$fecha%' order by fecha desc");
				break;

			case 'dentista':
				$sql = $db -> query("SELECT * FROM notificaciones WHERE id_clie = $idUsuario and fecha LIKE '%$fecha%' order by fecha desc");
				break;
			
			default:
				$respuesta = null;
				break;
		}

		$respuesta = ($db->rows($sql)>=1) ? $sql -> fetch_all(MYSQLI_ASSOC) : null;

		return $respuesta;

	}	

	public static function mdlUltimaNotificacion($idUsuario, $tipo){

		$db = new Conexion();

		$sql = ($tipo=="dentista" && $tipo != NULL) ? $db -> query("SELECT * FROM notificaciones WHERE id_clie = $idUsuario order by fecha desc limit 1") : $db -> query("SELECT * FROM notificaciones WHERE id_lab = $idUsuario order by fecha desc limit 1");

		$resultado = ($db->rows($sql) >= 1) ? $sql -> fetch_array(MYSQLI_ASSOC) : 0;

		return $resultado;

		$db -> exit();

	}

	public static function mdlCompararUltimaNotificacion($idUsuario, $tipo, $timestamp){

		$db = new Conexion();

		$ultimaNotificacion = strtotime($timestamp);

		$sql = ($tipo == "dentista" && $tipo != NULL) ? $db -> query("SELECT * FROM notificaciones WHERE id_clie = $idUsuario ORDER BY fecha DESC LIMIT 1") : $db -> query("SELECT * FROM notificaciones WHERE id_lab = $idUsuario ORDER BY fecha DESC LIMIT 1");

		$datos = ($db->rows($sql) >= 1) ? $sql -> fetch_array(MYSQLI_ASSOC) : 0;

		$nuevaNotificacion = strtotime($datos['fecha']);

		if ($nuevaNotificacion <= $ultimaNotificacion){
			
			return 0;

		} else {
			
			return $datos;
		}

		$db -> exit();

	}

	public static function mdlInsertarNuevaNotificacion($idUsuario,$tipo,$titulo,$mensaje,$url,$color,$icono,$tipoNotificacion, $auxiliar){

		$db = new Conexion();

		$respuesta = false;

		switch($tipo){
			case 'dentista':

				if( $db -> query("INSERT INTO notificaciones(id_clie, titulo, mensaje, url, color, icono, tipo, auxiliar) VALUES ($idUsuario, '$titulo', '$mensaje', '$url', '$color', '$icono', '$tipoNotificacion', '$auxiliar')") ){
					$respuesta = true;
				}
				
				break;

			case 'tecnico':

				if( $db -> query("INSERT INTO notificaciones(id_lab, titulo, mensaje, url, color, icono, tipo, auxiliar) VALUES ($idUsuario, '$titulo', '$mensaje', '$url', '$color', '$icono', '$tipoNotificacion', '$auxiliar')") ){
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

}

?>