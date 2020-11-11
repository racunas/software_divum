<?php 

include "../../../modelos/perfil.modelo.php";
include "../../../controladores/plantilla.controlador.php";

$idEstado = $_POST['idEstado'];

$sepomex = modeloPerfil::mdlTodoSepomex($idEstado);

$filtrado = controladorPlantilla::unique_multidim_array($sepomex,"idMunicipio");
$filtradoAux = controladorPlantilla::orderMultiDimensionalArray ($filtrado, "idMunicipio", false);

echo json_encode($filtradoAux);

 ?>