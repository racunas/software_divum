<?php 
date_default_timezone_set('UTC');
date_default_timezone_set("America/Mexico_City");
session_start();

include "../../../modelos/notificaciones.modelo.php";

if(isset($_SESSION['tecnico'])){

    $idUsuario = $_SESSION['tecnico'];
    $tipo = "tecnico";

} elseif(isset($_SESSION['dentista'])){

    $idUsuario = $_SESSION['dentista'];
    $tipo = "dentista";

}

$fecha = date("Y-m-d");

$respuesta = modeloNotificacion::mdlMarcarnotificacionLeidas($idUsuario,$tipo,$fecha);

echo $respuesta;

?>