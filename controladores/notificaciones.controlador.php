<?php 

class controladorNotificacion{

	public static function ctrObtenerTodasLasNotificaciones($idUsuario,$tipo){

		$respuesta = modeloNotificacion::mdlObtenerTodasLasNotificaciones($idUsuario,$tipo);

		return $respuesta;

	}

}

 ?>