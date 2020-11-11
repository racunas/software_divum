<?php 

include "../../../modelos/sugerencias.modelo.php";

$respuesta = modeloSugerencias::mdlSugerencias();

echo json_encode($respuesta);

 ?>