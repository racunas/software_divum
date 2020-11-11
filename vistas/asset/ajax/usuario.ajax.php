<?php 

include "../../../modelos/usuario.modelo.php";

$correo = $_POST['correo'];
$rol = $_POST['tipo'];

$respuesta = mdlUsuario::mdlExisteUsuario($correo, $tipo);

if($respuesta){

	echo 1;

} else {

	echo 0;

}

 ?>