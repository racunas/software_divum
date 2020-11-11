<?php 

session_start();

require_once "../../../modelos/perfil.modelo.php";

$nombTecnico = $_POST["nombTecnico"];
$nombLaboratorio = $_POST["nombLaboratorio"];
$descrLaboratorio = $_POST["descrLaboratorio"];
$telefonoLaboratorio = $_POST["telefonoLaboratorio"];
$calle = $_POST['calle'];
$idSepomex = $_POST['idSepomex'];
$id = $_SESSION['tecnico'];

$exito = modeloPerfil::mdlActualizarDatosTecnico($nombTecnico,$nombLaboratorio,$descrLaboratorio,$telefonoLaboratorio,$calle,$idSepomex,$id);

echo $exito;


 ?>