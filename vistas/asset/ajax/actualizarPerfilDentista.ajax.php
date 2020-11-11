<?php 

session_start();

require_once "../../../modelos/perfil.modelo.php";

$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$especialidad = $_POST['especialidad'];
$fechaNacimiento = $_POST['fechaNacimiento'];
$clinica = $_POST['clinica'];

$id = $_SESSION['dentista'];

$exito = modeloPerfil::mdlActualizarDatosDentista($nombre,$telefono,$especialidad,$fechaNacimiento,$clinica,$id);

echo $exito;

 ?>