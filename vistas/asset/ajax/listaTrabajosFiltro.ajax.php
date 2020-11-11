<?php 
session_start();

include "../../../modelos/listaTrabajos.modelo.php";

$filtro = $_POST['filtro'];

if(isset($_SESSION['tecnico'])){

    $idUsuario = $_SESSION['tecnico'];

}

$respuesta = "";

$datos = modeloListaTrabajos::mdlTrabajosFiltro($idUsuario,$filtro);

switch ($filtro) {

	case 'total':

		if(count($datos) >= 1){

			//AQUI SE ESCRIBE EL CODIGO EN CASO DE QUE TENGA TRABAJOS AGREGADOS

			$respuesta .= 

						'<div class="row text-muted cabeceraTablaTrabajos no-gutters col-sm-0 col-0">
							
							<div class="col-xl-5 col-lg-5 col-md-4 col-sm-5 col-5">
								
								<i class="fas fa-tooth mr-2"></i>Trabajo

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="fas fa-dollar-sign mr-2"></i> Precio 

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="far fa-calendar-alt mr-2"></i> Prueba

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="fas fa-calendar-check mr-2"></i> Terminado

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="fas fa-percentage mr-2"></i> Parte inicial

							</div>

						</div>';

			foreach ($datos as $key => $value) {

				$idTrabajo = $value['idTrabajo'];
				$porcentaje = $value['porcentaje'] * 100;
				$ganancia = $value['precio'] - 50;

				$trabajoUrgente = modeloListaTrabajos::mdlTrabajoUrgentePorNombre($idTrabajo,$value['nombreProtesis']);

				$es = modeloListaTrabajos::mdlTipoTrabajo($idTrabajo,$value['nombreProtesis']);

				$existePromocion = modeloListaTrabajos::mdlVerificarExistenciaPromocion($idTrabajo,$es);


				$value['nombreProtesis'] = ucfirst($value['nombreProtesis']);

				$respuesta .= 

						'<div class="trabajoLista">';

				$respuesta .= 

						'<div class="row text-muted cuerpoTablaTrabajos no-gutters">
							
							<div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 pr-4" id="'.$idTrabajo.'_'.$es.'" data-filtro="total">
								
								<i class="far fa-trash-alt btnEliminarTrabajo mx-2 txtRojo"></i>
								
								<i class="far fa-edit btn_Editar mx-2 colorBuscalab"></i>

								<span>'.$value['nombreProtesis'].'</span>

							</div>

							<div class="col-xl col-lg col-md col-sm-6 col-6 separacionMovil">
								
								<span class="col-xl-0 col-lg-0 col-md-0">Precio: </span> $ '.$value['precio'].'

							</div>

							<div class="col-xl col-lg col-md col-sm-6 col-6 separacionMovil">
								
								<span class="col-xl-0 col-lg-0 col-md-0">Entrega: </span> '.$value['dias'].' días

							</div>

							<div class="col-xl col-lg col-md col-sm-0 col-0 separacionMovil">
								
								'.$value['diasTerminado'].' días

							</div>

							<div class="col-xl col-lg col-md col-sm-0 col-0 separacionMovil">
								
								'.$porcentaje.'%

							</div>

						</div>';

					if($trabajoUrgente != false){

						$gananciaUrg = $trabajoUrgente['precio'] - 50;

						$respuesta .= 

									'<div class="row text-muted trabajoUrgente no-gutters">
					
											<div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">
												
												<i class="fas fa-level-up-alt fa-rotate-90 ml-5 mr-3 col-sm-0 col-0"></i>

												<span class="txtRojo">Urgente</span>

											</div>

											<div class="col-xl col-lg col-md col-sm-6 col-6 separacionMovil">
											
												<span class="col-xl-0 col-lg-0 col-md-0">Precio: </span> $ '.$trabajoUrgente['precio'].'

											</div>

											<div class="col-xl col-lg col-md col-sm-0 col-0 separacionMovil">
											
												-

											</div>

											<div class="col-xl col-lg col-md col-sm-6 col-6 separacionMovil">
											
												<span class="col-xl-0 col-lg-0 col-md-0">Entrega: </span> '.$trabajoUrgente['dias_entrega'].' días

											</div>

											<div class="col-xl col-lg col-md col-sm-0 col-0 separacionMovil">
											
												100%

											</div>

									</div>

									<hr class="my-1">

								</div>';

					} else {

						$respuesta .= '<hr class="my-1">

								</div>';
					}

			} //FIN DEL CICLO FOREACH

		} else {

			//AQUI SE ESCRIBE EL CODIGO EN CASO DE QUE NO TENGA TRABAJOS AGREGADOS
			
			$respuesta .= '<div class="alert alert-danger">No hay trabajos agregados</div>';
		}

		break;

	case 'ordinario':

		if(count($datos) >= 1){

			//AQUI SE ESCRIBE EL CODIGO EN CASO DE QUE TENGA TRABAJOS AGREGADOS

			$respuesta .= 

						'<div class="row text-muted cabeceraTablaTrabajos col-sm-0 col-0">
							
							<div class="col-xl-5 col-lg-5 col-md-4 col-sm-5 col-5">
								
								<i class="fas fa-tooth mr-2"></i>Trabajo

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="fas fa-dollar-sign mr-2"></i> Precio 

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="far fa-calendar-alt mr-2"></i> Prueba

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="fas fa-calendar-check mr-2"></i> Terminado

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="fas fa-percentage mr-2"></i> Parte inicial

							</div>

						</div>';

			foreach ($datos as $key => $value) {

				$idTrabajo = $value['idTrabajo'];
				$porcentaje = $value['porcentaje'] * 100;
				$ganancia = $value['precio'] - 50;

				$es = modeloListaTrabajos::mdlTipoTrabajo($idTrabajo,$value['nombreProtesis']);

				$value['nombreProtesis'] = ucfirst($value['nombreProtesis']);

				$respuesta .= 

						'<div class="trabajoLista">';

				$respuesta .= 

						'<div class="row text-muted cuerpoTablaTrabajos no-gutters">
							
							<div class="col-xl-5 col-lg-5 col-md-5 col-sm-12" id="'.$idTrabajo.'_'.$es.'" data-filtro="ordinario">
								
								<i class="far fa-trash-alt btnEliminarTrabajo mx-2 txtRojo"></i>
								
								<i class="far fa-edit btn_Editar mx-2 colorBuscalab"></i>

								<span>'.$value['nombreProtesis'].'</span>

							</div>

							<div class="col-xl col-lg col-md col-sm-6 col-6 separacionMovil">
								
								<span class="col-xl-0 col-lg-0 col-md-0">Precio: </span> $ '.$value['precio'].'

							</div>

							<div class="col-xl col-lg col-md col-sm-6 col-6 separacionMovil">
								
								<span class="col-xl-0 col-lg-0 col-md-0">Entrega: </span> '.$value['dias'].' días

							</div>

							<div class="col-xl col-lg col-md col-sm-0 col-0 separacionMovil">
								
								'.$value['diasTerminado'].' días

							</div>

							<div class="col-xl col-lg col-md col-sm-0 col-0 separacionMovil">
								
								'.$porcentaje.'%

							</div>

						</div>

						<hr class="my-1">

					</div>';
		

			} //FIN DEL CICLO FOREACH

		} else {

			//AQUI SE ESCRIBE EL CODIGO EN CASO DE QUE NO TENGA TRABAJOS AGREGADOS
			
			$respuesta .= '<div class="alert alert-danger">No hay trabajos agregados</div>';
		}

		break;

	case 'urgente':

		if(count($datos) >= 1){

			//AQUI SE ESCRIBE EL CODIGO EN CASO DE QUE TENGA TRABAJOS AGREGADOS

			$respuesta .= 

						'<div class="row text-muted cabeceraTablaTrabajos col-sm-0 col-0">
							
							<div class="col-xl-5 col-lg-5 col-md-4 col-sm-5 col-5">
								
								<i class="fas fa-tooth mr-2"></i>Trabajo

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="fas fa-dollar-sign mr-2"></i> Precio 

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="far fa-calendar-alt mr-2"></i> Prueba

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="fas fa-calendar-check mr-2"></i> Terminado

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="fas fa-percentage mr-2"></i> Parte inicial

							</div>

						</div>';

			foreach ($datos as $key => $value) {

				$idTrabajo = $value['idTrabajo'];
				$porcentaje = 100;
				$ganancia = $value['precio'] - 50;

				$es = modeloListaTrabajos::mdlTipoTrabajo($idTrabajo,$value['nombreProtesis']);

				$value['nombreProtesis'] = ucfirst($value['nombreProtesis']);

				$respuesta .= 

						'<div class="trabajoLista">';

				$respuesta .= 

						'<div class="row text-muted cuerpoTablaTrabajos no-gutters">
			
							<div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12" id="'.$idTrabajo.'_'.$es.'" data-filtro="urgente">
								
								<i class="far fa-trash-alt mx-2 btnEliminarTrabajo txtRojo"></i>
								
								<i class="far fa-edit btn_Editar mx-2 colorBuscalab"></i>

								<span>'.$value['nombreProtesis'].'</span>

							</div>

							<div class="col-lg col-md col-sm-6 col-6 separacionMovil">
								
								<span class="col-xl-0 col-lg-0 col-md-0">Precio: </span> $'.$value['precio'].'

							</div>

							<div class="col-lg col-md col-sm-6 col-0 separacionMovil">
								
								-

							</div>

							<div class="col-lg col-md col-sm-6 col-6 separacionMovil">
								
								<span class="col-xl-0 col-lg-0 col-md-0">Entrega: </span> '.$value['diasTerminado'].' días

							</div>

							<div class="col-lg col-md col-sm-6 col-0 separacionMovil">
								
								'.$porcentaje.'%

							</div>

						</div>

						<hr class="my-1">

					</div>';
		

			} //FIN DEL CICLO FOREACH

		} else {

			//AQUI SE ESCRIBE EL CODIGO EN CASO DE QUE NO TENGA TRABAJOS AGREGADOS
			
			$respuesta .= '<div class="alert alert-danger">No hay trabajos agregados</div>';
		}

		break;

	case 'noDisponible':

		if(count($datos) >= 1){

			//AQUI SE ESCRIBE EL CODIGO EN CASO DE QUE TENGA TRABAJOS AGREGADOS

			$respuesta .= 

						'<div class="row text-muted cabeceraTablaTrabajos col-sm-0 col-0">
							
							<div class="col-xl-5 col-lg-5 col-md-4 col-sm-5 col-5">
								
								<i class="fas fa-tooth mr-2"></i>Trabajo

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="fas fa-dollar-sign mr-2"></i> Precio 

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="far fa-calendar-alt mr-2"></i> Prueba

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="fas fa-calendar-check mr-2"></i> Terminado

							</div>

							<div class="col-xl col-lg col-md col-sm col txtCorto">
								
								<i class="fas fa-percentage mr-2"></i> Parte inicial

							</div>

						</div>';

			foreach ($datos as $key => $value) {

				$idTrabajo = $value['idTrabajo'];
				$porcentaje = 100;
				$ganancia = $value['precio'] - 50;

				$es = modeloListaTrabajos::mdlTipoTrabajo($idTrabajo,$value['nombreProtesis']);

				$value['nombreProtesis'] = ucfirst($value['nombreProtesis']);

				$respuesta .= 

						'<div class="trabajoLista">';

				$respuesta .= 

						'<div class="row text-muted cuerpoTablaTrabajos no-gutters">
			
							<div class="col-lg-5 col-md-5 col-sm-12 col-12" id="'.$idTrabajo.'_'.$es.'" data-filtro="noDisponible">
								
								<i class="far fa-check-square btnEliminarTrabajo mx-2 txtVerde"></i>
								
								<i class="far fa-edit btn_Editar mx-2 colorBuscalab"></i>

								<span>'.$value['nombreProtesis'].'</span>

							</div>

							<div class="col-lg col-md col-sm-6 col-6">
								
								<span class="col-xl-0 col-lg-0 col-md-0">Precio: </span> $'.$value['precio'].'

							</div>

							<div class="col-lg col-md col-sm-6 col-0">
								
								-

							</div>

							<div class="col-lg col-md col-sm-6 col-6">
								
								<span class="col-xl-0 col-lg-0 col-md-0">Entrega: </span> '.$value['diasTerminado'].' días

							</div>

							<div class="col-lg col-md col-sm-6 col-0">
								
								'.$porcentaje.'%

							</div>

						</div>

						<hr class="my-1">

					</div>';
		

			} //FIN DEL CICLO FOREACH

		} else {

			//AQUI SE ESCRIBE EL CODIGO EN CASO DE QUE NO TENGA TRABAJOS AGREGADOS
			
			$respuesta .= '<div class="alert alert-danger">No hay trabajos agregados</div>';
		}

		break;
	
	default:
		$respuesta = false;
		break;
}

echo $respuesta;

 ?>