<?php 	

class controladorPerfil{

	public static function ctrTodoSepomex($idEstado){

		$resultado = modeloPerfil::mdlTodoSepomex($idEstado);

		return $resultado;

	}

	public static function ctrSepomex($id){

		$resultado = modeloPerfil::mdlSepomex($id);

		return $resultado;
		
	}

	public static function ctrDirecciones($id,$tipo,$idDirec){

		
		$resultado = modeloPerfil::mdlDireccion($id,$tipo,$idDirec);

		return $resultado;


	}

	public static function ctrDatosPerfil($id,$tipo){

		$datos = modeloPerfil::mdlDatosPerfil($id,$tipo);

		return $datos;

	}

	public static function ctrPerfil($id,$tipo){

		$datosPerfil = modeloPerfil::mdlDatosPerfil($id,$tipo);

		$respuesta = array();

		switch ($tipo) {

			case 'dentista':

				$respuesta["datosGenerales"] = 

					'<div class="datosPerfil text-muted">
				
						<div class="bold pb-3">
							<i class="far fa-user"></i> &nbsp;Nombre de usuario:
						</div>

						<div class="nombre">
							<input type="text" class="form-control" value="'.$datosPerfil['nomb'].'" placeholder="Introduce tu nombre completo">
						</div>


						<div class="bold py-3">
							<i class="fas fa-phone"></i> &nbsp;Telefono:
						</div>

						<div class="telefono">
							<input type="text" class="form-control" value="'.$datosPerfil['tel'].'" onkeypress="return valida(event)" maxlength="10" placeholder="Introduce tu número celular">
						</div>


						<div class="bold py-3">
							<i class="fas fa-tooth"></i> &nbsp;Especialidad:
						</div>

						<div class="especialidad">
							<input type="text" class="form-control" value="'.$datosPerfil['esp'].'" placeholder="Ejemplo: Endodoncia, protesis...">
						</div>


						<div class="bold py-3">
							<i class="fas fa-calendar"></i> &nbsp;Edad:
						</div>

						<div class="fechaNacimiento">
							<input type="text" class="form-control fechaNacimientoDentista" value="'.$datosPerfil['fecha_nac'].'" readonly="readonly" placeholder="Selecciona tu fecha de nacimiento">
						</div>


						<div class="bold py-3">
							<i class="fas fa-hospital-alt"></i> &nbsp;Nombre de tu clinica:
						</div>

						<div class="clinica">
							<input type="text" class="form-control" value="'.$datosPerfil['clinica'].'" placeholder="Introduce el nombre de tu clinica">

						</div>

					</div>';


				$respuesta["correo"] = 

					'<div class="datosLogin text-muted">
						
						<div class="bold mb-2">
							<i class="fas fa-envelope"></i> &nbsp;Correo:
						</div>

						<div class="">
							'.$datosPerfil['email'].'
						</div>


						<div class="bold pt-3 mb-2">
							<i class="fas fa-unlock-alt"></i> &nbsp;Contraseña:
						</div>

						<div class="">
							**************
						</div>

						<div class="mb-1 txtPequeño">
							<a href="perfil/cambiar-password" class="noDecoration cambiarContraseña">Cambiar contraseña</a>
						</div>

					</div>';

				break;


			/*******************CASO DEL TECNICO*******************/

			case 'tecnico':

				$respuesta["datosGenerales"] = 

					'<div class="datosPerfilTecnico text-muted">
						
						<div class="row text-center">
							
							<div class="col-lg-4 totalOrdenes">
									
								<i class="fas fa-clipboard-list"></i>

								<h3 class="bold">Total de ordenes:</h3>
								<h1 class="totalOrdenesHechas">0</h1>

							</div>

							<div class="col-lg-4 ordenesFinalizadas">
								
								<i class="fas fa-clipboard-check"></i>

								<h3 class="bold">Ordenes finalizadas:</h3>
								<h1 class="totalOrdenesFinalizadas">0</h1>	

							</div>

							<div class="col-lg-4 calificacionGeneral">
								
								<i class="fas fa-star"></i>

								<h3 class="bold">Calificacion:</h3>
								<h1 class="totalCalificacion">5.0<h1>

							</div>

						</div>

						<hr>
						
						<div class="row">
							
							<div class="col-lg-6">
								
								<div class="bold pb-3">
									<i class="far fa-user"></i> &nbsp;Nombre del Técnico: <i class="far fa-question-circle ml-2"></i>
								</div>

								<div class="nombTecnico">
									<input type="text" class="form-control" placehoder="Introduce tu nombre completo" value="'.$datosPerfil['nomb_art'].'">
								</div>

							</div>
							<div class="col-lg-6">
								<div class="bold pb-3 separacionMovil">
									<i class="fas fa-hospital-alt"></i> &nbsp;Nombre del Laboratorio: <i class="far fa-question-circle ml-2"></i>
								</div>

								<div class="nombLaboratorio">
									<input type="text" class="form-control" placeholder="Introduce el nombre de tu laboratorio dental" value="'.$datosPerfil['nomb'].'">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="bold py-3">
									<i class="fas fa-tooth"></i> &nbsp;Descripción de tu laboratorio: <i class="far fa-question-circle ml-2"></i>
								</div>

								<div class="descrLaboratorio">
									<textarea class="form-control" cols="30" rows="3" maxlength="130" placeholder="Escribe una descripción sobre tu laboratorio dental">'.$datosPerfil['descr'].'</textarea>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="bold py-3">
									<i class="fas fa-phone"></i> &nbsp;Telefono:
								</div>

								<div class="telefonoLaboratorio">
									<input type="text" class="form-control" value="'.$datosPerfil['tel'].'" onkeypress="return valida(event)" maxlength="10" placeholder="Introduce tu número celular">
								</div>
							</div>

						</div>


					</div>';



				$respuesta["correo"] = 

					'<div class="datosLogin text-muted">
						
						<div class="bold mb-2">
							<i class="fas fa-envelope"></i> &nbsp;Correo:
						</div>

						<div class="">
							'.$datosPerfil['email'].'
						</div>


						<div class="bold pt-3 mb-2">
							<i class="fas fa-unlock-alt"></i> &nbsp;Contraseña:
						</div>

						<div class="">
							**************
						</div>

						<div class="mb-1 txtPequeño">
							<a href="perfil/cambiar-password" class="noDecoration cambiarContraseña">Cambiar contraseña</a>
						</div>

					</div>';

				break;
			
			default:
				
				break;

		}

		return $respuesta;

	}

	public static function ctrEstadoRepublica($idEstado){

		$datosEstado = modeloPerfil::mdlEstadoRepublica($idEstado);

		return $datosEstado;

	}

	public static function ctrTodosEstados(){

		$estados = modeloPerfil::mdlTodosEstados();

		return $estados;

	}

	public static function ctrNombreFotoPerfil($id,$tipo){

		$respuesta = modeloPerfil::mdlNombreFotoPerfil($id,$tipo);

		return $respuesta;

	}

	public static function ctrAgregarDireccion($direccion,$cp,$id){

		$respuesta = modeloPerfil::mdlAgregarDireccion($direccion, $cp, $id);

		return $respuesta;

	}

}



 ?>