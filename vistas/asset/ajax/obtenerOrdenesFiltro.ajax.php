<?php 
session_start();

include "../../../modelos/orden.modelo.php";

$filtro = $_POST['filtro'];

$url = $_POST['url'];

if(isset($_SESSION['tecnico'])){

	$idUsuario = $_SESSION['tecnico'];
	$tipo = "tecnico";
	$icono = '<img src="'.$url.'vistas/asset/images/dentista.png" alt="Dentista" class="mr-2" width="25px"> Dentista/Clinica';

} elseif (isset($_SESSION['dentista'])){

	$idUsuario = $_SESSION['dentista'];
	$tipo = "dentista";
	$icono = '<img src="'.$url.'vistas/asset/images/tecnico.png" alt="Tecnico dental" class="mr-2" width="25px"> Técnico/Laboratorio';

}


$ordenes = modeloOrden::mdlOrdenes($idUsuario,$tipo,$filtro);

switch ($filtro) {
	case 'prioritario':
		$alerta = "No hay ordenes de trabajo...";
		break;

	case 'nuevo':
		$alerta = "No hay ordenes de trabajo nuevas...";
		break;

	case 'atrasado':
		$alerta = "No hay ordenes atrasadas...";
		break;

	case 'finalizado':
		$alerta = "Aún no completadas ninguna orden de trabajo...";
		break;

	case 'cancelado':
		$alerta = "No hay ordenes de trabajo canceladas...";
		break;
	
	default:
		# code...
		break;
}

$respuesta = '';

//AQUI SE DEBEN COLOCAR LOS ELEMENTOS MOVILES

if(!$ordenes){

	$respuesta .= '<div class="alert alert-warning col-xl-0 col-lg-0 col-md-0">No hay ordenes de trabajo canceladas</div>';

} else {

	foreach ($ordenes as $key => $value) {

		$nombre = ucfirst($value['paciente']);

		$primeraLetra = substr($nombre, 0, 1);

		$idOrd = $value['id_ord'];

		if( ($value['fotoPerfil'] == "dentista.png") || ($value['fotoPerfil'] == "imgRelleno.png") ){

			$fotoPerfil = '<div class="letraPerfilOrden mr-2">
								<p>'.$primeraLetra.'</p>
							</div>
						<a href="'.$url.'ordenes?orden='.$idOrd.'">'.$nombre.'</a>';

		} else{

			if($tipo == "tecnico") { $carpeta = "dentistas"; } else { $carpeta = "tecnicos"; }

			$fotoPerfil = '<a href="'.$url.'ordenes?orden='.$idOrd.'">
							<img src="'.$url.'vistas/asset/images/'.$carpeta.'/'.$value['fotoPerfil'].'" alt="'.$nombre.'" class="img-fluid imgPerfilOrdenes mr-2">
							'.$nombre.'
						</a>';

		}

		if($value['protesis'] != NULL){

			$trabajo = ucfirst($value['protesis']);

		} elseif($value['ortodoncia'] != NULL){

			$trabajo = ucfirst($value['ortodoncia']);

		}

		$fecha = explode(" ", $value['fecha']);

		//$fechaEntrega = $value['fechaEntrega'];

		$etapas = modeloOrden::mdlEtapasOrden($idOrd,$tipo);

		$fechaRecepcion = $etapas[0]['fecha_rec'];

		$fechaEntrega = $etapas[0]['fecha_ent'];

		$precioTotal = $value['precio'];

		$tipoTrabajo = ($value['tipo'] == 'urgente') ? '<p class="avisoOrdenes flagAtrasado shadow mr-3">Urgente</p>' : '';

		/*CODIGO PARA VERIFICAR EL STATUS DE LA ORDEN*/

		//echo $etapas[0]['id_ord']."=>".$etapas[0]['estado']."=>".$etapas[0]['estadoPago']."=>".$etapas[0]['confirmacionTecnico'];
										

		//print_r($etapas);

		if( $value['entregado'] == 4 ){

			$estadoTrabajo = '<p class="bold txtRojo estadoOrdenes cancelado"><i class="fas fa-ban mr-2"></i> Cancelado</p>';

		}elseif ($etapas[0]['estado'] == 1 &&
			$etapas[0]['estadoPago'] == 1 &&
			$etapas[0]['confirmacionTecnico'] == 1){

			$estadoTrabajo = '<p class="bold txtVerde estadoOrdenes completado"><i class="fas fa-check-circle mr-2"></i> Orden completa</p>';

		} elseif( ($etapas[0]['estadoPago'] == 1) && ($etapas[0]['confirmacionTecnico'] == 0) ){ //SÍ EL PAGO ESTÁ ACREDITADO Y EL TECNICO AUN NO CONFIRMA QUE LO TERMINO, SIGNIFICA QUE ESTA EN PROCESO

			$estadoTrabajo = '<p class="text-info estadoOrdenes enProceso"><i class="fas fa-spinner fa-spin mr-2"></i> En proceso</p>';

		} elseif( ($etapas[0]['estadoPago'] == 1) && ($etapas[0]['confirmacionTecnico'] == 1) ) { //SÍ EL PAGO ESTÁ ACREDITADO Y EL TÉCNICO YA CONFIRMÓ QUE TERMINÓ LA ORDEN, SIGNIFICA QUE EL TRABAJO ESTÁ EN PROCESO DE ENTREGA

			$estadoTrabajo = '<p class="bold estadoOrdenes ausente"><i class="far fa-check-circle mr-2"></i> Entregado</p>';

		} elseif( ( ($etapas[0]['estadoPago'] == 2) || ($etapas[0]['estadoPago'] == 3) ) && ($etapas[0]['confirmacionTecnico'] == 0) ){ // SÍ EL ESTADO DEL PAGO ES APROBADO (1). SÍ EL TECNICO YA CONFIRMÓ QUE TERMINÓ EL TRABAJO Y EL ESTADO DEL TRABAJO ES TERMINADO, EL TRABAJO DEBERÍA CONSIDERARSE COMO TERMINADO O COMPLETADO.

			$estadoTrabajo = '<p class="bold txtPequeño txtAmarillo estadoOrdenes pagoPendiente"><i class="fas fa-stopwatch txtGrande mr-2"></i> Pago pendiente</p>';

		}



		/*MANEJO DE LOS FLAG EN LA TABLA DE LAS ORDENES*/

		$fechas3 = explode(" ", $etapas[0]['fecha_hist_ord']); //SEPARAMOS LA FECHA DE LA HORA

		$fecha1 = new DateTime($etapas[0]['fecha_ent']); //Fecha de entrega
		$fecha3 = new DateTime($fechas3[0]); //Fecha en que se hizo la orden (SOLO FECHA)
		$fecha2 = new DateTime(date("Y-m-d")); //Fecha actual
		$diff = $fecha1->diff($fecha2); //Diferencia entre esas dos fechas
		// will output 2 days
		$diasRestantesEntrega = $diff->days;
		
		if(count($etapas) == 0){
			$flagOrden = '<p class="avisoOrdenes flagAtrasado shadow">Cancelado</p>';
		} elseif( ($etapas[0]['visto'] == 0) && ($tipo=="tecnico") && ($etapas[0]['estadoPago'] != 4) ){

			$flagOrden = '<p class="avisoOrdenes flagNuevo shadow">¡Nuevo!</p>';
			
		} elseif( ($diasRestantesEntrega == 1) && ($fecha1 > $fecha2) && ($etapas[0]['confirmacionTecnico'] == 0) ){
			$flagOrden = '<p class="avisoOrdenes flagEntregar shadow">Entrega mañana</p>';
			
		} elseif( ($fecha1 < $fecha2) && ($etapas[0]['confirmacionTecnico'] == 0) && ($etapas[0]['visto'] == 1) ){
			$flagOrden = '<p class="avisoOrdenes flagAtrasado shadow">Atrasado</p>';
			
		}  elseif( ($fecha3 >= $fecha2) && ($tipo == "dentista") && ($etapas[0]['estadoPago'] != 4) ){
			$flagOrden = '<p class="avisoOrdenes flagNuevo shadow">¡Nuevo!</p>';
		}else{
			$flagOrden = '';
		}

		/************************************************/

		/*NUMERO DE DIENTES*/

		$dientes = explode(",", $value['dientes']);

		$numeroDientes = count($dientes);
		

		$respuesta .=

		'<hr class="col-xl-0 col-lg-0 col-md-0">

		<div class="row px-3 no-gutters elementoTablaOrdenes col-xl-0 col-lg-0 col-md-0" data-id="'.$idOrd.'">
			
			<div class="col-sm-8 col-8 form-inline">
				'.$fotoPerfil.'
			</div>

			<div class="col-sm-4 col-4 text-right">
				<p class="fechaEntregaOrdenes text-muted">'.$fechaEntrega.'</p>
			</div>

			<div class="col-sm-12 col-12 mt-2">
				<p class="text-muted bold">'.$trabajo.'</p>
			</div>

			<div class="col-sm-9 col-9 form-inline">
				'.$estadoTrabajo.'
				'.$tipoTrabajo.'
				'.$flagOrden.'
			</div>

			<div class="col-sm-3 col-3 text-right">
				<p class="txtVerde bold precioOrdenes">$ '.$precioTotal * $numeroDientes.'</p>
			</div>

		</div>';

	} //FIN DEL FOREACH PARA ELEMENTOS EN MOVILES

	
} //FIN DEL ELSE EN CASO DE QUE NO EXISTAN ORDENES

if(!$ordenes){

	$respuesta .= '<div class="alert alert-warning col-sm-0 col-0">'.$alerta.'</div>';

} else {

	$respuesta .= '<table class="table text-muted table-hover col-sm-0 col-0 tablaOrdenes">

		<thead>

			<tr>
				<th scope="col-lg-3" class="txtCorto"><i class="fas fa-user mr-2"></i> Paciente</th>
				<th scope="col-lg-3" class="txtCorto"><i class="fas fa-tooth mr-2"></i> Trabajo</th>
				<th scope="col-lg-2" class="col-md-0"><i class="fas fa-calendar mr-2"></i> Recepcion</th>
				<th scope="col-lg-2" class="txtCorto"><i class="fas fa-calendar-check mr-2"></i> Entrega</th>
				<th scope="col-lg-2" class="txtCorto"><i class="fas fa-dollar-sign mr-2"></i> Total</th>
				<th scope="col-lg-2" class="txtCorto"><i class="fas fa-tasks mr-2"></i> Estado</th>
			</tr>

		</thead>

		<tbody>';

	foreach ($ordenes as $key => $value) {

		$nombre = ucfirst($value['paciente']);

		$primeraLetra = substr($nombre, 0, 1);

		$idOrd = $value['id_ord'];

		if( ($value['fotoPerfil'] == "dentista.png") || ($value['fotoPerfil'] == "imgRelleno.png") ){

			$fotoPerfil = '<div class="letraPerfilOrden mr-2">
								<p>'.$primeraLetra.'</p>
							</div>
						<a href="'.$url.'ordenes?orden='.$idOrd.'">'.$nombre.'</a>';

		} else{

			if($tipo == "tecnico") { $carpeta = "dentistas"; } else { $carpeta = "tecnicos"; }

			$fotoPerfil = '<a href="'.$url.'ordenes?orden='.$idOrd.'">
							<img src="'.$url.'vistas/asset/images/'.$carpeta.'/'.$value['fotoPerfil'].'" alt="'.$nombre.'" class="img-fluid imgPerfilOrdenes mr-2">
							'.$nombre.'
						</a>';

		}

		if($value['protesis'] != NULL){

			$trabajo = ucfirst($value['protesis']);

		} elseif($value['ortodoncia'] != NULL){

			$trabajo = ucfirst($value['ortodoncia']);

		}

		$fecha = explode(" ", $value['fecha']);

		//$fechaEntrega = $value['fechaEntrega'];

		$etapas = modeloOrden::mdlEtapasOrden($idOrd,$tipo);

		//print_r($etapas);

		$fechaEntrega = $etapas[0]['fecha_ent'];

		$fechaRecepcion = $etapas[0]['fecha_rec'];

		$precioTotal = $value['precio'];

		$tipoTrabajo = ($value['tipo'] == 'urgente') ? 'tipoTrabajoUrgente' : '';

		#MANEJAR LOS DIFERENTES ESTADOS DE LA ORDEN

		if( $value['entregado'] == 4 ){

			$estadoTrabajo = '<p class="bold txtRojo"><i class="fas fa-ban mr-2"></i> Cancelado</p>';

		}elseif ($etapas[0]['estado'] == 1 &&
			$etapas[0]['estadoPago'] == 1 &&
			$etapas[0]['confirmacionTecnico'] == 1){

			$estadoTrabajo = '<p class="bold txtVerde"><i class="fas fa-check-circle mr-2"></i> Orden completa</p>';

		} elseif( ($etapas[0]['estadoPago'] == 1) && ($etapas[0]['confirmacionTecnico'] == 0) ){ //SÍ EL PAGO ESTÁ ACREDITADO Y EL TECNICO AUN NO CONFIRMA QUE LO TERMINO, SIGNIFICA QUE ESTA EN PROCESO

			$estadoTrabajo = '<p class="text-info"><i class="fas fa-spinner fa-spin mr-2"></i> En proceso</p>';

		} elseif( ($etapas[0]['estadoPago'] == 1) && ($etapas[0]['confirmacionTecnico'] == 1) ) { //SÍ EL PAGO ESTÁ ACREDITADO Y EL TÉCNICO YA CONFIRMÓ QUE TERMINÓ LA ORDEN, SIGNIFICA QUE EL TRABAJO ESTÁ EN PROCESO DE ENTREGA

			$estadoTrabajo = '<p class="bold estadoOrdenes ausente"><i class="far fa-check-circle mr-2"></i> Entregado</p>';

		} elseif( ( ($etapas[0]['estadoPago'] == 2) || ($etapas[0]['estadoPago'] == 3) ) && ($etapas[0]['confirmacionTecnico'] == 0) ){ // SÍ EL ESTADO DEL PAGO ES APROBADO (1). SÍ EL TECNICO YA CONFIRMÓ QUE TERMINÓ EL TRABAJO Y EL ESTADO DEL TRABAJO ES TERMINADO, EL TRABAJO DEBERÍA CONSIDERARSE COMO TERMINADO O COMPLETADO.

			$estadoTrabajo = '<p class="bold txtPequeño txtAmarillo"><i class="fas fa-stopwatch txtGrande mr-2"></i> Pago pendiente</p>';

		}

		/*MANEJO DE LOS FLAG EN LA TABLA DE LAS ORDENES*/

		$fecha1 = new DateTime($etapas[0]['fecha_ent']); //Fecha de entrega
		$fecha3 = new DateTime($etapas[0]['fecha_hist_ord']); //Fecha en que se hizo la orden
		$fecha2 = new DateTime(date("Y/m/d")); //Fecha actual
		$diff = $fecha1->diff($fecha2); //Diferencia entre esas dos fechas
		// will output 2 days
		$diasRestantesEntrega = $diff->days;

		if(count($etapas) == 0){
			$flagOrden = '<p class="flagOrdenes flagAtrasado shadow">Cancelado</p>';
		} elseif( ($etapas[0]['visto'] == 0) && ($tipo=="tecnico") && ($etapas[0]['estadoPago'] != 4) ){

			$flagOrden = '<p class="flagOrdenes flagNuevo shadow">¡Nuevo!</p>';
			
		} elseif( ($diasRestantesEntrega == 1) && ($fecha1 > $fecha2) && ($etapas[0]['visto'] == 1) && ($etapas[0]['confirmacionTecnico'] == 0) ){
			$flagOrden = '<p class="flagOrdenes flagEntregar shadow">Entrega mañana</p>';
			
		} elseif( ($fecha1 < $fecha2) && ($etapas[0]['confirmacionTecnico'] == 0) && ($etapas[0]['visto'] == 1) ){
			$flagOrden = '<p class="flagOrdenes flagAtrasado shadow">Atrasado</p>';
			
		} elseif( ($fecha3 >= $fecha2) && ($tipo == "dentista") && ($etapas[0]['estadoPago'] != 4) ){
			$flagOrden = '<p class="flagOrdenes flagNuevo shadow">¡Nuevo!</p>';
		}else{
			$flagOrden = '';
		}

		/*NUMERO DE DIENTES*/

		$dientes = explode(",", $value['dientes']);

		$numeroDientes = count($dientes);

		/************************************************/
		
		$respuesta .= '<tr class="elementoTablaOrdenes '.$tipoTrabajo.'" data-id="'.$idOrd.'">

						<th class="form-inline padreFlag list-inline">
							'.$flagOrden.'
							'.$fotoPerfil.'
						</th>

						<td class="pt-4">
							'.$trabajo.'
						</td>

						<td class="pt-4 col-md-0">
							'.$fechaRecepcion.'
						</td>

						<td class="pt-4">
							<b>'.$fechaEntrega.'</b>
						</td>

						<td class="pt-4">
							$ '.$precioTotal * $numeroDientes.'
						</td>

						<td class="pt-4 txtCorto">
							'.$estadoTrabajo.'
						</td>

					</tr>';

	} //FIN DEL FOREACH Y FIN DE LA TABLA PARA COMPUTADORA

	$respuesta .= '</tbody>

			</table>';
	
} //FIN DEL IF PARA LA TABLA DE COMPUTADORA NORMAL

echo $respuesta;

 ?>