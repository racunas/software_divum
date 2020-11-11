<?php 

class ctrCabezote{

	public static function ctrCabezoteSesion($url){

		if(isset($_SESSION['dentista'])){

			$datosPerfil = controladorPerfil::ctrDatosPerfil($_SESSION['dentista'],"dentista");

			if( ($datosPerfil['img_perfil'] == "dentista.png") ){

				$fotoPerfil = '<li class="bloqueFotoPerfil">
									<div class="fotoPerfil">
										<p class="primeraLetraNombre txtBlanco"></p>
									</div>
								</li>';

			} else{

				$fotoPerfil = '<li class="bloqueFotoPerfil">
									<a href="'.$url.'perfil">
										<img src="'.$url.'vistas/asset/images/dentistas/'.$datosPerfil['img_perfil'].'" alt="'.$datosPerfil['nomb'].'" class="img-fluid fotoPerfil">
									</a>
								</li>';

			}

			$iconos = '<li>
							<a href="#" class="mostrarnotificacion">
								<i class="fas fa-bell campananotificacion mr-0">
									<!--<p class="numnotificacion"></p>-->
								</i>
							</a>

							<div class="dropdownnotificacion hidden">

								<div class="text-left cabezotenotificacion"><b>notificacion</b></div>

								<div class="contenidonotificacion">

									<!--<a href="'.$url.'perfil">
										<div class="notificacion row no-gutters">
											
											<div class="col-lg-3">
												<img src="http://192.168.2.6/buscalabFinal/vistas/asset/images/tecnicos/Tecnico Dental Online041936108927972.jpg" alt="Tecnico Dental Online" class="">
											</div>

											<div class="col-lg-9 mensajeNotificacion text-justify">
												<p><i class="fas fa-check iconoNotificacion mr-2"></i> Ya puedes mandar otra orden para el paciente: <b>Alisson hernandez</b></p>
											</div>

										</div>
									</a>-->

								</div>

								<div class="text-center masnotificacion bold"><a href="'.$url.'notificacion">Ver todas las notificacion</a></div>

							</div>
						</li>

						<li>
							<a href="'.$url.'caja-ordenes"><i class="fas fa-box-open mr-0"></i>Pendientes</a>		
						</li>

						<li>
							<a href="'.$url.'ordenes" class="mr-2"><i class="fas fa-clipboard-list mr-0"></i>Ordenes</a>
						</li>

						'.$fotoPerfil;

		} elseif(isset($_SESSION['tecnico'])){

			$datosPerfil = controladorPerfil::ctrDatosPerfil($_SESSION['tecnico'],"tecnico");

			if( ($datosPerfil['img_art'] == "imgRelleno.png") ){

				$fotoPerfil = '<li class="bloqueFotoPerfil">		
									<div class="fotoPerfil">
										<p class="primeraLetraNombre txtBlanco"></p>
									</div>									
								</li>';

			} else{

				$fotoPerfil = '<li class="bloqueFotoPerfil">
									<img src="'.$url.'vistas/asset/images/tecnicos/'.$datosPerfil['img_art'].'" alt="'.$datosPerfil['nomb'].'" class="img-fluid fotoPerfil">	
								</li>';

			}

			$iconos = '<li>
							<a href="#" class="mostrarnotificacion">
								<i class="fas fa-bell campananotificacion mr-0">
									<!--<p class="numnotificacion"></p>-->
								</i>
							</a>

							<div class="dropdownnotificacion hidden">

								<div class="text-left cabezotenotificacion"><b>notificacion</b></div>

								<div class="contenidonotificacion">

									<!--<a href="'.$url.'perfil">
										<div class="notificacion row no-gutters">
											
											<div class="col-lg-3">
												<img src="http://192.168.2.6/buscalabFinal/vistas/asset/images/tecnicos/Tecnico Dental Online041936108927972.jpg" alt="Tecnico Dental Online" class="">
											</div>

											<div class="col-lg-9 mensajeNotificacion text-justify">
												<p><i class="fas fa-check iconoNotificacion mr-2"></i> Ya puedes mandar otra orden para el paciente: <b>Alisson hernandez</b></p>
											</div>

										</div>
									</a>-->

								</div>

								<div class="text-center masnotificacion bold"><a href="'.$url.'notificacion">Ver todas las notificacion</a></div>

							</div>
						</li>

						<li>
							<a href="'.$url.'ordenes"><i class="fas fa-clipboard-list mr-0"></i>Ordenes</a>
						</li>

						<li>
							<a href="'.$url.'trabajos" class="mr-2"><i class="fas fa-tooth mr-0"></i>Trabajos</a>
						</li>

						'.$fotoPerfil;

		}

		if((isset($_SESSION['dentista'])) || (isset($_SESSION['tecnico']))){

			$sesion =

			'<ul class="opcionesCabezote pt-3">

				'.$iconos.'

			</ul>';

		} else {

			$sesion = 

			'<ul class="inicioLogin pt-3">
				<!--<li>
					<a href="'.$url.'caja-ordenes"><i class="fas fa-box-open"></i></a>
				</li>-->

				<li>
					<a href="#" class="inicioSesion"><i class="fas fa-user"></i> &nbsp; Iniciar sesion</a>
				</li>

				<li>&nbsp;|&nbsp;</li>

				<li>
					<a href="#" class="registro"><i class="fab fa-wpforms"></i> &nbsp; Registrarse</a>
				</li>
			</ul>';

		}

		return $sesion;

	}

}

 ?>