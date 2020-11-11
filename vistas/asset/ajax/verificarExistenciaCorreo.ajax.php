<?php 

include "../../../modelos/correo.modelo.php";	

$correo = $_POST['correo'];

echo modeloCorreo::mdlRestablecerContraseña($correo);

/*if(!modeloCorreo::mdlRestablecerContraseña($correo)){ //SI RETORNA FALSE, SIGNIFICA QUE NO EXISTE EL CORREO ELECTRONICO

	echo "No existe";

} else {

	echo "Enviado";

}*/

 ?>