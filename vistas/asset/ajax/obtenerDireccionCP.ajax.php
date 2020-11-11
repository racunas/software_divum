<?php 

include "../../../modelos/perfil.modelo.php";

$cp = $_POST['codigoPostal'];

$direccion = modeloPerfil::mdlObtenerDireccion($cp);

echo json_encode($direccion);

 ?>