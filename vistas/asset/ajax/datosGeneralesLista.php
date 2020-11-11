<?php 	

session_start();

include "../../../modelos/listaTrabajos.modelo.php";

$idLab = $_SESSION['tecnico'];


$datosGenerales = true;
$datosGeneralesUrgentes = true;

foreach ($_POST as $key => $value) {
	$totalTrabajos = count($value);
}


for ($i=0; $i < $totalTrabajos; $i++) {

	if(isset($_POST[$totalTrabajos][$i]['terminadoUrgente'])){

		$idProtesis = $_POST[$totalTrabajos][$i]['idProtesis'];
		$precio = $_POST[$totalTrabajos][$i]['precio'];
		$prueba = $_POST[$totalTrabajos][$i]['prueba'];
		$terminado = $_POST[$totalTrabajos][$i]['terminado'];
		$porcientoAdelanto = $_POST[$totalTrabajos][$i]['porcientoAdelanto'];
		$terminadoUrgente = $_POST[$totalTrabajos][$i]['terminadoUrgente'];
		$porcientoAdelantoUrgente = $_POST[$totalTrabajos][$i]['porcientoAdelantoUrgente'];
		$tipoTrabajo = $_POST[$totalTrabajos][$i]['tipoTrabajo'];

		$datosGenerales = modeloListaTrabajos::mdlAgregarTrabajo($tipoTrabajo, $idLab, $precio, $idProtesis, $prueba, $terminado, $porcientoAdelanto);
		$datosGeneralesUrgentes = modeloListaTrabajos::mdlAgregarTrabajoUrgente($tipoTrabajo,$terminadoUrgente,$porcientoAdelantoUrgente,$idLab,$idProtesis);

	} else {

		$idProtesis = $_POST[$totalTrabajos][$i]['idProtesis'];
		$precio = $_POST[$totalTrabajos][$i]['precio'];
		$prueba = $_POST[$totalTrabajos][$i]['prueba'];
		$terminado = $_POST[$totalTrabajos][$i]['terminado'];
		$porcientoAdelanto = $_POST[$totalTrabajos][$i]['porcientoAdelanto'];
		$tipoTrabajo = $_POST[$totalTrabajos][$i]['tipoTrabajo'];

		$datosGenerales = modeloListaTrabajos::mdlAgregarTrabajo($tipoTrabajo, $idLab, $precio, $idProtesis, $prueba, $terminado, $porcientoAdelanto);

	}

}

$perfilCompleto = modeloListaTrabajos::mdlPerfilCompleto($idLab);

if($datosGenerales && $datosGeneralesUrgentes){

	echo "1";

} else {

	echo "0";

}

 ?>