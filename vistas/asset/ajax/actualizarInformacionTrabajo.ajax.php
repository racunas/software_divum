<?php 

session_start();

include "../../../modelos/listaTrabajos.modelo.php";

$precio = $_POST['precio'];
$porcientoAdelanto = $_POST['porcientoAdelanto'];
$diasPrueba = $_POST['diasPrueba'];
$diasTerminado = $_POST['diasTerminado'];
$precioUrgente = $_POST['precioUrgente'];
$diasTerminadoUrgente = $_POST['diasTerminadoUrgente'];
$tipoTrabajo = $_POST['tipoTrabajo'];
$idTrabajo = $_POST['idTrabajo'];

$idUsuario = $_SESSION['tecnico'];

$exito = true;

if(modeloListaTrabajos::mdlExisteTrabajoUrgente($tipoTrabajo,$idTrabajo,$idUsuario)){

	//Sí ya existe el registro del trabajo urgente en la base de datos...

	if( $precioUrgente == NULL ||
		$diasTerminadoUrgente == NULL){

		// Sí no hay datos urgentes...
		if(!modeloListaTrabajos::mdlDesactivarTrabajoUrgente($tipoTrabajo,$idTrabajo)){

			$exito = false;

		}

	} else {

		//Sí hay datos urgentes...
		if(!modeloListaTrabajos::mdlActualizarTrabajoUrgente($precioUrgente,$diasTerminadoUrgente,$tipoTrabajo,$idTrabajo)){
			$exito = false;
		}

	}

	if(!modeloListaTrabajos::mdlActualizarTrabajo($precio,$porcientoAdelanto,$diasPrueba,$diasTerminado,$tipoTrabajo,$idTrabajo)){
		$exito = false;
	}

} else {

	//Sí no existe el registro del trabajo urgente en la base de datos...

	if( $precioUrgente != NULL &&
		$diasTerminadoUrgente != NULL){

		// Sí hay datos urgentes...

		if(!modeloListaTrabajos::mdlInsertarTrabajoUrgente($precioUrgente,$diasTerminadoUrgente,$tipoTrabajo,$idTrabajo)){
			$exito = false;
		}

	}

	if(!modeloListaTrabajos::mdlActualizarTrabajo($precio,$porcientoAdelanto,$diasPrueba,$diasTerminado,$tipoTrabajo,$idTrabajo)){
		$exito = false;
	}

}

echo $exito;

 ?>