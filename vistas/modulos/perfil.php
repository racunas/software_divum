<?php 

if(isset($_SESSION['tecnico'])){

	$id = $_SESSION['tecnico'];
	$tipo = "tecnico";
	
	$nombreFotoPerfil = controladorPerfil::ctrNombreFotoPerfil($id,$tipo); //OBTENEMOS EL NOMBRE DE SU FOTO DE PERFIL

	$urlFotoPerfil = $url."vistas/asset/images/tecnicos/".$nombreFotoPerfil['img_art'];

} else if(isset($_SESSION['dentista'])){
	
	$id = $_SESSION['dentista'];
	$tipo = "dentista";

	$nombreFotoPerfil = controladorPerfil::ctrNombreFotoPerfil($id,$tipo); //OBTENEMOS EL NOMBRE DE SU FOTO DE PERFIL

	$urlFotoPerfil = $url."vistas/asset/images/dentistas/".$nombreFotoPerfil['img_perfil'];
	
}


//$republica = controladorPerfil::ctrEstadoRepublica($datosPerfil['id_rep']);

$todosEstados = controladorPerfil::ctrTodosEstados();

$datosPerfil = controladorPerfil::ctrPerfil($id,$tipo); //CON ÉSTE MÉTODO OBTENEMOS TODO EL HTML DE LOS DATOS DEL PERFIL

$arrayDatosPerfil = controladorPerfil::ctrDatosPerfil($id,$tipo);

$contador = 1;
$vacios = 0;

$direccion = controladorPerfil::ctrDirecciones($id,$tipo,NULL);

//print_r($direccion);

if( count($direccion) >= 1 ||
	isset($_SESSION['tecnico'])){

	$contador = $contador + 1;

} else {

	$vacios = $vacios + 1;

}

foreach ($arrayDatosPerfil as $key => $value) {
	
	if( $value == NULL ||
		$value == "dentista.png" ||
		$value == "imgRelleno.png" ||
		$value == "imgRellenoProducto.png"){

		$vacios = $vacios + 1;

	}	

	$contador = $contador + 1;

}

$porcientoPerfil = round(100 - ( ($vacios * 100) / ($contador) ));

 ?>

<div class="container py-4 mb-5">

	<h2 class="text-muted bold">
		Perfil
	</h2>

	<p class="text-muted">Porcentaje que has completado de tu perfil</p>
	
	<div class="row mt-3">
			
		<div class="col-xl-11 col-lg-11 col-md-10 col-sm-9 col-9">
	
			<div class="progress mt-2">
			 	<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $porcientoPerfil; ?>%"></div>
			</div>
			
		</div>

		<div class="col-xl-1 col-lg-1 col-md-1 col-sm-3 col-3">
			
			<h2 class="colorBuscalabGris bold"><?php echo $porcientoPerfil ?>%</h2>

		</div>

	</div>
	
	<div class="row py-5">
		
		<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
			
			<div class="fotoPerfil text-center">

				<form id="subirFotoPerfil" action="" class="text-center" method="post" enctype="multipart/form-data">

					<label for="nuevaFotoPerfil" class="colorBuscalab bold">
					<img src="<?php echo $urlFotoPerfil; ?>" class="img-fluid">
					<br>
					<br>
					Cambiar foto de perfil</label>
					<input type="file" class="hidden" id="nuevaFotoPerfil" name="nuevaFotoPerfil" accept="image/jpeg" required>
					<input type="hidden" name="url" value="<?php echo $url; ?>">

				</form>

			</div>
			
			<hr class="my-2">

			<div class="mt-3">
				
				<?php 

				echo $datosPerfil['correo']; //IMPRIMOS LOS DATOS DE CORREO Y CONTRASEÑA

				 ?>

			</div>
			
		</div>

		<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 separacionTablet separacionMovil ">
			
			<?php 

			echo $datosPerfil['datosGenerales']; //IMPRIMIMOS LOS DATOS GENERALES DEL PERFIL

			 ?>

			 <button class="btn btn-block btnBuscalab <?php echo $tipo; ?> py-2 mt-4" id="btnGuardarCambiosPerfil">Guardar cambios <i class="fas fa-edit ml-2"></i></button>

		</div>
	
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 txtGrande">

			<?php  


				if($tipo == "dentista"){

					$direccion = controladorPerfil::ctrDirecciones($id,$tipo,NULL); //SE ENVÍA NULL AL FINAL PORQUE QUIERO QUE ME TRAIGA TODAS LAS direccion EN UN ARRAY

					if($direccion != NULL){

						echo '<div class="direccionPerfil separacionTablet separacionMovil text-muted" data-numdireccion='.count($direccion).'>

								<div class="bold">
									<i class="fas fa-map-marker-alt"></i> &nbsp;Direccion de recepción y envio:
								</div>

								<ul class="direccion pt-2">';

								foreach ($direccion as $key => $value) {

								if($value['id_direc'] == $arrayDatosPerfil['direccion_predet']){
									$icono = 'fa-check-circle direccionPredeterminada';
								} else {
									$icono = 'fa-circle seleccionarDireccion';
								}
									
								echo '<li class="row">
										<div class="col-lg-9 col-md-9 col-sm-9 col-9">
											'.$value['calle'].', '.$value['cp'].'
										</div>

										<div class="col-lg-3 col-md-3 col-sm-3 col-3 text-right direccion" data-id="'.$value['id_direc'].'">
											<i class="fas fa-minus-circle eliminarDireccion"></i>
											<i class="far '.$icono.'"></i>
										</div>
									</li>';

								}

						echo '		<li class="row nuevasdireccion"></li>

									<li class="row colorBuscalab pt-3 nuevaDireccion">
										<div class="col-lg-10 col-md-10 col-sm-10 col-10">
											<a class="agregarDireccion enlaceBuscalab">Agregar nueva dirección</a>
										</div>

										<div class="col-lg-2 col-md-2 col-sm-2 col-2 text-right">
											<i class="fas fa-plus-circle agregarDireccion"></i>
										</div>
									</li>

								</ul>
						
							</div>';

					} else {

						echo '<div class="direccionPerfil separacionTablet text-muted" data-numdireccion="0">
				
								<div class="bold">
									<i class="fas fa-map-marker-alt"></i> &nbsp;Direccion de recepción y envio:
								</div>

								<ul class="direccion pt-2">
									
									<li class="row nuevasdireccion">
										<div class="col-lg-9">
											No existen direccion 
										</div>
									</li>

									<li class="row colorBuscalab pt-3 nuevaDireccion">
										<div class="col-lg-10">
											<a class="agregarDireccion enlaceBuscalab">Agregar nueva dirección</a>
										</div>

										<div class="col-lg-2 text-right">
											<i class="fas fa-plus-circle agregarDireccion"></i>
										</div>
									</li>

								</ul>
					
							</div>';

					}

					echo 

							'<div class="moduloAgregarDireccion hidden mb-3">

								<div class="text-left my-3">
									<i class="fas fa-times"></i>
								</div>

								<div class="row mb-3">
				
									<div class="col-lg-8">
										
										<input type="text" id="codigoPostal" autocomplete="off" placeholder="Introduce tu código postal" class="form-control" onkeypress="return valida(event)" maxlength="5">

									</div>

									<div class="col-lg-4">
										
										<button class="btn btn-block btn-outline-secondary separacionTablet separacionMovil" id="buscarDireccion">Localizar <i class="fas fa-map-marker-alt ml-2"></i></button>

									</div>

								</div>

								<div class="row hidden datosDireccionBuscada">

									<div class="col-lg-12 my-2">
										
										<input type="text" id="calle" placeholder="Ingresa el nombre de tu calle con # ext. e int." class="form-control">

									</div>

									<div class="col-lg-12 my-2">
										
										<select name="colonia" id="colonia" class="form-control inputBorderBottom"></select>

									</div>

									<div class="col-lg-6 col-md-6 my-2">
										
										<input type="text" id="estado" readonly class="form-control" placeholder="Estado">

									</div>

									<div class="col-lg-6 col-md-6 my-2">
										
										<input type="text" id="municipio" readonly class="form-control" placeholder="Municipio / Delegación">

									</div>

									<div class="col-lg-12 my-2">

										<button id="btnAgregarDireccion" class="btn btn-block btnOutlineBuscalab">Agregar dirección <i class="fas fa-plus ml-2"></i></button>

									</div>

								</div>
								

							</div>';


				} elseif($tipo == "tecnico"){

					$idDirec = $arrayDatosPerfil['id_rep'];

					$direccion = controladorPerfil::ctrDirecciones($id,$tipo,$idDirec); //SE ENVÍA NULL AL FINAL PORQUE QUIERO QUE ME TRAIGA TODAS LAS direccion EN UN ARRAY

					//print_r($direccion);

					if($direccion != null){

						$hidden = "";

					} else {

						$hidden = "hidden";

					}

					echo 

					'<div class="row pt-5">

						<div class="col-lg-4">
					
							<div class="direccionPerfil separacionTablet separacionMovil text-muted pb-2">
				
								<div class="bold">
									<i class="fas fa-map-marker-alt"></i> &nbsp;Direccion de recepción y envio:
								</div>

								<div class="row my-3">
					
									<div class="col-lg-8">
										
										<input type="text" id="codigoPostal" autocomplete="off" placeholder="Introduce tu código postal" class="form-control" value="'.$direccion['cp'].'" onkeypress="return valida(event)" maxlength="5">

									</div>

									<div class="col-lg-4">
										
										<button class="btn btn-block btn-outline-secondary separacionTablet separacionMovil" id="buscarDireccion">Localizar <i class="fas fa-map-marker-alt ml-2"></i></button>

									</div>

								</div>

								<div class="row '.$hidden.' datosDireccionBuscada"> 	

									<div class="col-lg-12 my-2">
										
										<input type="text" value="'.$arrayDatosPerfil['direc'].'" id="calle" placeholder="Calle con número exterior e interior" class="form-control">

									</div>

									<div class="col-lg-12 my-2">
										
										<select name="colonia" data-defecto="'.$direccion['id'].'" id="colonia" class="form-control inputBorderBottom"></select>

									</div>

									<div class="col-lg-6 col-md-6 my-2">
										
										<input type="text" value="'.$direccion['estado'].'" id="estado" readonly class="form-control" placeholder="Estado">

									</div>

									<div class="col-lg-6 col-md-6 my-2">
										
										<input type="text" value="'.$direccion['municipio'].'" id="municipio" readonly class="form-control" placeholder="Municipio / Delegación">

									</div>


								</div>
					
							</div>

						</div>
						
						<div class="col-lg-8">';

							if($arrayDatosPerfil['img_prod'] != "imgRellenoProducto.png"){

								echo 

								'<div class="bold text-muted mb-2">
									<i class="fas fa-image"></i> &nbsp;Foto/Imagen de pre-orden: <i class="far fa-question-circle ml-2"></i>
								</div>
						
								<div class="imagenPreOrden mb-3">
									
									<form class="conImagen" id="subirImagenPreOrden">

										<label for="imagenPreOrden" class="colorBuscalab bold">Subir foto/imagen <i class="fas fa-arrow-up ml-2"></i></label>
										<img src="'.$url.'vistas/asset/images/producto/'.$arrayDatosPerfil['img_prod'].'" alt="'.$arrayDatosPerfil['img_prod'].'" class="previsualizacionImagenPreOrden" data-action="zoom">
										<input type="file" name="imagenPreOrden" id="imagenPreOrden" class="hidden" accept="image/jpeg">

									</form>

								</div>';

							} else {

								echo 

								'<div class="bold text-muted mb-2">
									<i class="fas fa-image"></i> &nbsp;Foto/Imagen de pre-orden: <i class="far fa-question-circle ml-2"></i>
								</div>
						
								<div class="imagenPreOrden mb-3">
									
									<form class="sinImagen" id="subirImagenPreOrden">

										<label for="imagenPreOrden" class="colorBuscalab bold">Subir foto/imagen <i class="fas fa-arrow-up ml-2"></i></label>
										<input type="file" name="imagenPreOrden" id="imagenPreOrden" class="hidden" accept="image/jpeg">

									</form>

								</div>';

							}

					echo '
						</div>

					</div>';

					



				}


			?>

			<!--<hr class="my-2">
			
			<div class="bold text-muted mb-2">
				<i class="fas fa-image"></i> &nbsp;Foto/Imagen de pre-orden: <i class="far fa-question-circle ml-2"></i>
			</div>
	
			<div class="imagenPreOrden mb-3">
				
				<form class="conImagen" id="subirImagenPreOrden">

					<label for="imagenPreOrden" class="colorBuscalab bold">Subir foto/imagen <i class="fas fa-arrow-up ml-2"></i></label>
					<img src="<?php echo $url; ?>vistas/asset/images/producto/imgRellenoProducto.png" alt="" class="previsualizacionImagenPreOrden" data-action="zoom">
					<input type="file" name="imagenPreOrden" id="imagenPreOrden" class="hidden" accept="image/jpeg">

				</form>

			</div>-->

			<!--<button class="btn btn-block btnBuscalab <?php echo $tipo; ?>" id="btnGuardarCambiosPerfil">Guardar cambios <i class="fas fa-edit ml-2"></i></button>-->

		</div>

	</div>

</div>


<div class="modal fade" id="cambiarFotoPerfilModal" tabindex="-1" role="dialog" aria-hidden="true">
	
	<div class="modal-dialog modal-lg">

		<div class="modal-content">

			<div class="modal-header fondoBuscalab">
				<h5 class="modal-title" id="exampleModalLabel">Cambiar foto de perfil</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Recorta lo que será tu foto de perfil</p>
				
				<div id="respuestaSubidaFotoPerfil"></div>

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btnBuscalab btnGuardarFotoPerfil" disabled>Subir foto de perfil</button>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
			</div>

		</div>

	</div>

</div>

<!--CAMBIAR CONTRASEÑA PREDETERMINADA-->

<div class="modal fade" id="cambiarContraseñaModal" tabindex="-1" role="dialog" aria-hidden="true">
	
	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header fondoBuscalab">
				<h5 class="modal-title" id="exampleModalLabel">Cambiar contraseña</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

				<p class="text-muted bold">Para modificar la contraseña actual, debes ingresar la siguiente información:</p>

				<div class="error"></div>

				<form id="cambiarContraseña">

					<div class="ml-1 text-muted py-2"><i class="fas fa-key mr-2"></i>Contraseña actual:</div>

					<input type="password" name="passActual" id="passActual" class="form-control" class="py-2" maxlength="64">

					<div class="ml-1 text-muted my-2"><i class="fas fa-unlock mr-2"></i>Nueva contraseña:</div>

					<input type="password" name="newPass" id="newPass" class="form-control" class="py-2" maxlength="64">

					<div class="ml-1 text-muted my-2"><i class="fas fa-unlock mr-2"></i>Confirmar nueva contraseña:</div>

					<input type="password" name="confirmNewPass" id="confirmNewPass" class="form-control" class="py-2" maxlength="64">
			
				</form>

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btnBuscalab btnCambiarPassword">Cambiar contraseña</button>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
			</div>

		</div>

	</div>

</div>

<!--CAMBIAR LA FOTO DE PRE ORDEN-->

<div class="modal fade" id="cambiarFotoPreOrdenModal" tabindex="-1" role="dialog" aria-hidden="true">
	
	<div class="modal-dialog modal-lg">

		<div class="modal-content">

			<div class="modal-header fondoBuscalab">
				<h5 class="modal-title" id="exampleModalLabel">Cambiar foto de pre-orden</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Selecciona lo que será tu foto/imagen de pre-orden</p>
				
				<div id="respuestaSubidaFotoPreOrden"></div>

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btnBuscalab btnGuardarFotoPreOrden" disabled>Subir foto de pre-orden</button>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
			</div>

		</div>

	</div>

</div>