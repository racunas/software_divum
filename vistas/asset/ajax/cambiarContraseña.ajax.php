<?php 

include "../../../modelos/usuario.modelo.php";	

$key = $_POST['key'];
$nuevaContraseña = $_POST['nuevaContraseña'];
$confirmarNuevaContraseña = $_POST['confirmarNuevaContraseña'];

if( ($nuevaContraseña == $confirmarNuevaContraseña) &&
	(strlen($nuevaContraseña) >= 8) && 
	(strlen($confirmarNuevaContraseña) >= 8)){

	echo mdlUsuario::mdlCambiarNuevoPassword($key,$nuevaContraseña,$confirmarNuevaContraseña);
 
} else {

	echo "no cumple";

}


 ?>