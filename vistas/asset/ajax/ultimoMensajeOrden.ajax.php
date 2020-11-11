<?php 

include "../../../modelos/orden.modelo.php";
include "../../../modelos/perfil.modelo.php";

session_start();

$idOrden = $_POST['idOrden'];

$respuesta = modeloOrden::mdlObtenerUltimoMensajeOrden($idOrden);

echo $respuesta['fecha'];

 ?>