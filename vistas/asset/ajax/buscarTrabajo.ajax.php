<?php 

session_start();

include "../../../modelos/listaTrabajos.modelo.php";

$busquedaTrabajo = $_POST['busquedaTrabajo'];

if(isset($_SESSION['tecnico'])){

    $idUsuario = $_SESSION['tecnico'];

}

$respuesta = "";

$datos = modeloListaTrabajos::mdlBusquedaTrabajo($idUsuario,$busquedaTrabajo);


if(!$datos){

	//AQUI SE ESCRIBE EL CODIGO EN CASO DE QUE NO TENGA TRABAJOS AGREGADOS
	
	$respuesta .= '<div class="alert alert-danger">No hay trabajos agregados</div>';

} else{

	//AQUI SE ESCRIBE EL CODIGO EN CASO DE QUE TENGA TRABAJOS AGREGADOS

	$respuesta .= 

				'<div class="row text-muted cabeceraTablaTrabajos">
					
					<div class="col-lg-5">
						
						<i class="fas fa-tooth mr-2"></i>Trabajo

					</div>

					<div class="col-lg">
						
						<i class="fas fa-dollar-sign mr-2"></i> Precio 

					</div>

					<div class="col-lg">
						
						<i class="far fa-calendar-alt mr-2"></i> Prueba

					</div>

					<div class="col-lg">
						
						<i class="fas fa-calendar-check mr-2"></i> Terminado

					</div>

					<div class="col-lg">
						
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

				'<div class="row text-muted cuerpoTablaTrabajos">
	
					<div class="col-lg-5 txtCorto" id="'.$idTrabajo.'_'.$es.'" data-filtro="total">
						
						<i class="far fa-trash-alt mx-2 btnEliminarTrabajo txtRojo"></i>
						
						<i class="far fa-edit btn_Editar mx-2 colorBuscalab"></i>

						<span>'.$value['nombreProtesis'].'</span>

					</div>

					<div class="col-lg">
						
						$'.$value['precio'].'

					</div>

					<div class="col-lg">
						
						'.$value['dias'].' días

					</div>

					<div class="col-lg">
						
						'.$value['diasTerminado'].' días

					</div>

					<div class="col-lg">
						
						'.$porcentaje.'%

					</div>

				</div>';

			if($trabajoUrgente != false){

				$gananciaUrg = $trabajoUrgente['precio'] - 50;

				$respuesta .= 

							'<div class="row text-muted trabajoUrgente">
		
									<div class="col-lg-5">
										
										<i class="fas fa-level-up-alt fa-rotate-90 ml-5 mr-3"></i>

										<span class="txtRojo">Urgente</span>

									</div>

									<div class="col-lg">
									
										$'.$trabajoUrgente['precio'].'

									</div>

									<div class="col-lg">
									
										-

									</div>

									<div class="col-lg">
									
										'.$trabajoUrgente['dias_entrega'].' días

									</div>

									<div class="col-lg">
									
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

}

echo $respuesta;

 ?>