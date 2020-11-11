<?php 

session_start();

include "../../../modelos/listaTrabajos.modelo.php";

$nombreLaboratorio = $_POST["nombreLaboratorio"];
$calleLaboratorio = $_POST['calleLaboratorio'];
$idSepomex = $_POST['coloniaLaboratorio'];
$id = $_SESSION['tecnico'];

$exito = modeloListaTrabajos::mdlInfoLaboratorio($nombreLaboratorio,$calleLaboratorio,$idSepomex,$id);

echo $exito;

 ?>