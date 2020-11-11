<?php 
include "../../../modelos/orden.modelo.php";
include "../../../controladores/ruta.controlador.php";
require_once "../../../modelos/perfil.modelo.php";

session_start();

$url = ruta::obtenerRuta();

$filtro = $_POST['filtro'];
$url = $_POST['url'];
$idOrden = $_POST['idOrden'];

if(isset($_SESSION['tecnico'])){

	$idUsuario = $_SESSION['tecnico'];
	$tipo = "tecnico";

} elseif (isset($_SESSION['dentista'])){

	$idUsuario = $_SESSION['dentista'];
	$tipo = "dentista";

}

$datos = modeloOrden::mdlDetallesOrden($idUsuario,$tipo,$idOrden,$filtro);
$respuesta = '';

switch ($filtro){

	case 'resumen':

		//Sacar el tiemp de entrega

		if($datos[0]['idProtesis'] == NULL){

			$idTrabajo = $datos[0]['idOrtodoncia'];

		} elseif($datos[0]['idOrtodoncia'] == NULL){

			$idTrabajo = $datos[0]['idProtesis'];

		}

		$tipoOrden = $datos[0]['tipo'];
		$colorimetria = $datos[0]['color'];

		$datosOrden = modeloOrden::mdlInfoOrden($idTrabajo,$tipoOrden,$colorimetria);

		$datosUltimaFecha = modeloOrden::mdlDetallesOrden($idUsuario,$tipo,$idOrden,"ordenes");

		$etapasOrden = modeloOrden::mdlEtapasOrden($idOrden,$tipo);

		$tiempoEntrega = $datosOrden['tiempo'];

		///////////////////////////////////////////////////
		
		$respuesta = '<div class="row">
						
						<div class="col-lg-12">
							
							<h5 class="colorBuscalabGris bold">Resumen de tu orden</h5>
							
						</div>

						<div class="col-lg-6 col-md-6">
							
							<div class="colorBuscalab mt-2">

								<i class="fas fa-user mr-2"></i> Paciente:
								
								<span class="colorGris">
									'.ucfirst($datos[0]['paciente']).'
								</span>

							</div>

							<div class="colorBuscalab mt-2">

								<i class="fas fa-dollar-sign mr-2"></i> Precio:
								
								<span class="colorGris">
									$'.ucfirst($datos[0]['cantidad']).' <span class="txtPequeño">(c/diente)</span>
								</span>

							</div>';

		if($datos[0]['color'] != NULL){

						
			$respuesta .=	'<div class="colorBuscalab mt-2">

								<i class="fas fa-fill-drip mr-2"></i> Colorimetria:
								
								<span class="colorGris">
									'.$datos[0]['color'].'
								</span>

							</div>';


		}


			$respuesta .= '

						</div>

						<div class="col-lg-6 col-md-6">
							
							<div class="colorBuscalab mt-2">
								
								<i class="fas fa-clock mr-2"></i> Tiempo de elaboración:

								<span class="colorGris">
									'.$tiempoEntrega.' días
								</span>

							</div>

							<div class="colorBuscalab mt-2">
								
								<i class="fas fa-calendar-check mr-2"></i> Próxima entrega:

								<span class="colorGris">
									'.$datosUltimaFecha[0]['fecha_ent'].'
								</span>

							</div>';

			if($datos[0]['color'] != NULL){

				$respuesta .= 

							'<div class="colorBuscalab mt-2 '.$datos[0]['color'].'">

								<i class="fas fa-teeth-open"></i> Dientes a elaborar:
								
								<span class="colorGris">
									'.$datos[0]['dientes'].'
								</span>

							</div>';

			}

							

			$respuesta .='</div>

						<div class="col-lg-6 col-md-6 mt-3 colorBuscalab">
								
							<i class="fas fa-map-marker-alt mr-2"></i> Dirección de recepción

							<div class="mt-2 colorGris">
								'.$datos[0]['direcRec'].', 

								'.$datos[0]['municipioRec'].'<br>

								'.$datos[0]['cpRec'].'

							</div>

							<i class="fas fa-map-marker-alt mr-2 mt-2"></i> Dirección de entrega

							<div class="mt-2 colorGris">
								'.$datos[0]['direcEnt'].', 

								'.$datos[0]['municipioEnt'].'<br>

								'.$datos[0]['cpEnt'].'

							</div>

						</div>

						<div class="col-lg-6 col-md-6 mt-5 colorBuscalab">
							
							<button class="btn btn-block btn-outline-secondary codigoQr" data-qr="'.$etapasOrden[0]['id_hist_ord'].'">Generar QR de la última orden <i class="fas fa-qrcode ml-2"></i></button>

							<br>

							<button class="btn btn-block btnBuscalab ordenPdf" data-pdf="'.$etapasOrden[0]['id_hist_ord'].'">Generar PDF de la última orden <i class="fas fa-file-alt ml-2"></i></button>

						</div>

					</div>';

		break;

	/////////////////////////////////////////////////////////////////////////////////////////////////////

	case 'ordenes':

		$numOrdenes = count($datos);

		$respuesta = '<h5 class="colorBuscalabGris bold">Ordenes</h5>
					
					<div id="accordion">';



		foreach ($datos as $key => $value) {

			$respuesta .= '<div class="card">
						    	<div class="card-header" id="headingOne">
						    		<h5 class="mb-0">
						        		<button class="btn btn-block btnOrdenes" data-toggle="collapse" data-target="#collapse'.$numOrdenes.'" aria-expanded="true" aria-controls="collapseOne">
						          			<i class="fab fa-wpforms mr-3"></i> Orden '.$numOrdenes.'. '.$value['etapa'].'
						        		</button>
						    		</h5>
						    	</div>

						    	<div id="collapse'.$numOrdenes.'" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
						    		<div class="card-body">
						        		
										<div class="row text-muted">
											
											<div class="col-lg-4 col-md-4 col-sm-6 col-6 text-center">
													
												<span><i class="fas fa-calendar mr-2"></i> Inicio:</span>

												<div>'.$value['fecha_rec'].'</div>

											</div>

											<div class="col-lg-4 col-md-4 col-sm-6 col-6 text-center">
												
												<span><i class="fas fa-calendar-check mr-2"></i> Entrega:</span>

												<div>'.$value['fecha_ent'].'</div>

											</div>

											<div class="col-lg-4 col-md-4 col-sm-12 col-12 text-center">
													
												<span><i class="fas fa-money-bill-wave mr-2"></i> Pago:</span>

												<div>$'.$value['pago'].'</div>

											</div>

										</div>

										<hr>

										<div class="mt-3">
											
											<h5 class="text-muted bold">Especificaciones:</h5>

											<p class="text-muted">'.$value['descr'].'</p>

										</div>

										<div class="row mt-2">

											<div class="col-lg-6 col-md-12 col-sm-12 col-12">

												
												<button class="btn btn-block btn-outline-secondary codigoQr" data-qr="'.$value['id_hist_ord'].'">Generar QR de esta orden <i class="fas fa-qrcode ml-2"></i></button>

												<br>

											</div>

											<div class="col-lg-6 col-md-12 col-sm-12 col-12">

												<button class="btn btn-block btnBuscalab ordenPdf" data-pdf="'.$value['id_hist_ord'].'">Generar PDF de esta orden <i class="fas fa-file-alt ml-2"></i></button>

											</div>

										</div>

						    		</div>
						    	</div>
							</div>';

			$numOrdenes--;
		}


		$respuesta .= '</div>';
		
		break;

	case 'pagos':

		$ordenes = count($datos);
		$totalPagos = 0;

		$respuesta = '<h5 class="colorBuscalabGris bold">Pagos</h5>

					<table class="table table-hover mt-3 text-muted">

						<thead class="thead-buscalab">

							<tr>
								<th scope="col"><i class="fab fa-wpforms mr-2"></i> Orden</th>
								<th scope="col"><i class="fas fa-dollar-sign mr-2"></i> Pago</th>
								<th scope="col"><i class="fas fa-hand-holding-usd mr-2"></i> Pago total</th>
							</tr>

						</thead>

						<tbody>';

		foreach ($datos as $key => $value) {

			$envio = 0;
			$total = $value['pago'] + $envio;

			$respuesta .= '<tr>
								<th scope="row">'.$ordenes.'. '.$value['etapa'].'</th>
								<td>$'.$value['pago'].'</td>
								<td>$'.$total.'</td>
							</tr>';

			$ordenes = $ordenes - 1;

			$totalPagos = $totalPagos + $total;

		}

		$respuesta .= 	'</tbody>

					</table>

					<div class="text-muted bold text-right">
						
						<h5>Pagado: <b>$'.$totalPagos.'</b></h5>

					</div>';
		
		break;

	case 'fotos':

		if(!$datos){
			//SÍ NO EXISTEN FOTOS, MANDAR AVISO DE QUE NO HAY.

			$respuesta = '<div class="alert alert-info">No se han adjuntado fotos a esta orden.</div>';

		} else {

			$respuesta = '<h5 class="colorBuscalabGris bold">Fotos</h5>

							<div class="row galeriaFotos pt-4">';

			foreach ($datos as $key => $value) {
				$respuesta .= '<div class="col-lg-3 col-md-4 col-sm-6 col-6">
									<img src="'.$url.'vistas/asset/images/ordenes/'.$value['nombre'].'" alt="'.$value['nombre'].'" data-action="zoom">
								</div>';
								
			}
								
								
			$respuesta .= '</div>';
			
		}

		
		break;

	case 'chat':

		if(!$datos){

			$respuesta .=

			'<li class="sinMensajes text-center">
				<div class="ilustraciones">
					<i class="far fa-user"></i>
					<i class="far fa-lightbulb"></i>
					<i class="fas fa-angle-right mx-5"></i>
					<i class="fas fa-comment-dots"></i>
				</div>
				<p class="text-muted instrucciones">Pregunta lo que desees, o envía mensajes con detalles de tu trabajo que hayas olvidado<i class="fas fa-arrow-down ml-2"></i></p>
			</li>';

		} else {

			foreach ($datos as $key => $value) {

				$mensaje = $value['mensaje'];
				$fecha = explode(" ", $value['fecha']);

				if($tipo == "dentista"){


					if($value['id_clie'] == $idUsuario){
						
						$datosPerfil = modeloPerfil::mdlDatosPerfil($idUsuario,$tipo);
						$fotoPerfil = "dentistas/".$datosPerfil['img_perfil'];
						$nombre = $datosPerfil['nomb'];
						
						$respuesta .= 
						'<li class="emisor">
							<div class="mensaje">
								
								<div class="contenedor">
									<p>'.$mensaje.'</p>
									<div class="fechaMensaje txtPequeño">'.$fecha[0].'</div>
								</div>										
								
								<div class="foto">
									<img src="'.$url.'vistas/asset/images/'.$fotoPerfil.'" alt="'.$nombre.'" class="foto">
								</div>

							</div>
						</li>';
					} else {

						$datosPerfil = modeloPerfil::mdlDatosPerfil($value['id_lab'],"tecnico");
						$fotoPerfil = "tecnicos/".$datosPerfil['img_art'];
						$nombre = $datosPerfil['nomb'];

						$respuesta .=
						'<li class="receptor">
							<div class="mensaje">
								
								<div class="foto">
									<img src="'.$url.'vistas/asset/images/'.$fotoPerfil.'" alt="'.$nombre.'" class="foto">
								</div>

								<div class="contenedor">
									<p>'.$mensaje.'</p>
									<div class="fechaMensaje txtPequeño">'.$fecha[0].'</div>
								</div>																

							</div>
						</li>';
					}

				} elseif($tipo == "tecnico"){

					$datosPerfil = modeloPerfil::mdlDatosPerfil($idUsuario,$tipo);
					$fotoPerfil = "tecnicos/".$datosPerfil['img_art'];
					$nombre = $datosPerfil['nomb'];

					if($value['id_lab'] == $idUsuario){
						$respuesta .= 
						'<li class="emisor">
							<div class="mensaje">
								
								<div class="contenedor">
									<p>'.$mensaje.'</p>
									<div class="fechaMensaje txtPequeño">'.$fecha[0].'</div>
								</div>										
								
								<div class="foto">
									<img src="'.$url.'vistas/asset/images/'.$fotoPerfil.'" alt="'.$nombre.'" class="foto">
								</div>

							</div>
						</li>';
					} else {

						$datosPerfil = modeloPerfil::mdlDatosPerfil($value['id_clie'],"dentista");
						$fotoPerfil = "dentistas/".$datosPerfil['img_perfil'];
						$nombre = $datosPerfil['nomb'];

						$respuesta .=
						'<li class="receptor">
							<div class="mensaje">
								
								<div class="foto">
									<img src="'.$url.'vistas/asset/images/'.$fotoPerfil.'" alt="'.$nombre.'" class="foto">
								</div>

								<div class="contenedor">
									<p>'.$mensaje.'</p>
									<div class="fechaMensaje txtPequeño">'.$fecha[0].'</div>
								</div>																

							</div>
						</li>';
					}

				}

			}

		}


		//print_r($datos);

		break;
	
	default:
		echo false;
		break;

}

echo $respuesta;


 ?>