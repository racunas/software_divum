<?php 

$tipo = (isset($_SESSION['tecnico'])) ? "tecnico" : "dentista";
$idUsuario = (isset($_SESSION['tecnico'])) ? $_SESSION['tecnico'] : $_SESSION['dentista'];
$carpetaPerfil = (isset($_SESSION['tecnico'])) ? "dentistas/" : "tecnicos/";

$notificaciones = controladorNotificacion::ctrObtenerTodasLasNotificaciones($idUsuario,$tipo);

 ?>

<div class="container-fluid">
	
	<div class="container my-4 pb-5">
		
		<h3 class="text-muted font-weight-bold">Notificaciones</h3>

		<div class="row">
			
			<div class="col-lg-1 col-md-0 col-sm-0 col-0"></div>

			<div class="col-lg-10 col-md-12 col-sm-12 col-12 my-4 notificacionesTotales">

				<?php 

				if(empty($notificaciones)){

					echo 

					'<div class="text-center">

						<h2 class="text-muted font-weight-normal text-center">¡No hay notificaciones!</h2>

						<a href="'.$url.'"><button class="btn btnBuscalab text-center my-4">Página principal <i class="fas fa-undo-alt ml-2"></i></button></a>

					</div>';

				} else {

					foreach ($notificaciones as $key => $value) {

						$auxiliar = $value['auxiliar'];

						$fecha = explode(" ", $value['fecha']);

						$fotoPerfil = controladorOrden::ctrFotoPerfilOrden($idUsuario,$tipo,$auxiliar);

						echo 

						'<a href="'.$url.$value['url'].'">
							<div class="row notificacionIndividual">
							
								<div class="col-lg-1 col-md-1 col-sm-2 col-2">
									<img src="'.$url.'vistas/asset/images/'.$carpetaPerfil.$fotoPerfil['fotoPerfil'].'" alt="Notificacion">
								</div>

								<div class="col-lg-11 col-md-11 col-sm-10 col-10">
									<p>'.$value['mensaje'].'</p>
									<div class="detalles">
										<i class="'.$value['icono'].' mr-2"></i>
										<span>'.$fecha[0].'</span> <span class="ml-2">'.$fecha[1].'</span>
									</div>
								</div>


							</div>
						</a>';

					}					

				}

				

				 ?>


			</div>

			<div class="col-lg-1 col-md-0 col-sm-0 col-0"></div>

		</div>

	</div>

</div>