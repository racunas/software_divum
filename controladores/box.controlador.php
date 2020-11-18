<?php 

class controladorBox{

	public static function ctrOrdenesBox($idUsuario){

		$resultado = modeloBox::mdlOrdenesBox($idUsuario);

		return $resultado;

	}

	public static function ctrExisteBox($idBox){

		$resultado = modeloBox::mdlExisteBox($idBox);

		return $resultado;

	}

	public static function ctrDatosBox($idBox){

		$resultado = modeloBox::mdlDatosBox($idBox);

		return $resultado;

	}

	public static function ctrImagenesBox($idBox){

		$resultado = modeloBox::mdlImagenesBox($idBox);

		return $resultado;

	}

}

 ?>