<?php 

//include "../modelos/correo.modelo.php";

class controladorCorreo{

	public function ctrRestablecerContraseña($correo){

		$respuesta = modeloCorreo::mdlRestablecerContraseña($correo);
			
		return $respuesta;

	}


}

if(isset($_GET['correo'])){

	$correo = new controladorCorreo();

	echo controladorCorreo::ctrRestablecerContraseña($_GET['correo']);
	
}

 ?>