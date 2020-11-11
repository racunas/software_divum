<?php 

$idUsuario = $_SESSION['tecnico'];

$base = 0;
$tope = 25;

if(isset($rutas[1])){

	if($rutas[1] == ""){
    
		$rutas[1] = 1;
  
  	}

    //SÍ RUTAS 1 NO ES IGUAL A PENDIENTES O APROBADOS, SIGNIFICA QUE VIENE UNA PAGINACION

    $base = ($rutas[1] - 1) * $tope;

    $trabajos = controladorListaTrabajos::obtenerListaTrabajos($idUsuario, $base, $tope);

    $totalTrabajos = controladorListaTrabajos::obtenerListaTrabajos($idUsuario, NULL, NULL);
} else {

	$rutas[1] = 1;

	$base = ($rutas[1] - 1) * $tope;

	$trabajos = controladorListaTrabajos::obtenerListaTrabajos($idUsuario, $base, $tope);

    $totalTrabajos = controladorListaTrabajos::obtenerListaTrabajos($idUsuario, NULL, NULL);

}


 ?>

<div class="container pb-3">
	
	<div class="row my-4">
		
		<div class="col-lg-12">

			<div class="row">
				
				<div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-12">
					
					<h3 class="text-muted bold">Mi lista de precios</h3>

					<p class="text-muted">Aquí encuentra/modifica toda la información sobre tu lista de precios</p>
					
				</div>
				
				<div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 separacionTablet separacionMovil">
					
					<div id="busquedaTrabajo" class="input-group" style="border-bottom: 1px solid #a5a5a5">
				
						<input id="buscarTrabajo" type="text" class="form-control" placeholder="Buscar un trabajo..." autocomplete="off">
						
						<button class="btn btnBuscarTrabajo"><i class="fas fa-search"></i></button>
						

					</div>

				</div>

			</div>


			<div class="row my-2 separacionTablet separacionMovil">
				
				<div class="col-xl-6 col-lg-6">
					
					<a href="<?php echo $url; ?>lista-trabajos" style="text-decoration: none;">
						<button class="btn btn-block btnBuscalab txtEnorme"><i class="fas fa-plus"></i> &nbsp;Subir lista de precios</button>
					</a>

				</div>

				<div class="col-xl-6 col-lg-6">
					
				</div>

			</div>

			<hr class="my-4">

			<ul class="nav nav-pills mt-3 pb-3 barraNavegacionTrabajos" id="pills-tab" role="tablist">

				<li class="nav-item">
					<a class="nav-link active" id="pills-trabajosTotales" data-contenedor="pills-total" data-toggle="pill" href="#pills-total" role="tab" aria-controls="pills-home" aria-selected="true">Total <span class="badge badge-light"><?php echo count($totalTrabajos); ?></span></a>
				</li>

				<li class="nav-item">
					<a class="nav-link" id="pills-trabajoOrdinario" data-contenedor="pills-ordinario" data-toggle="pill" href="#pills-ordinario" role="tab" aria-controls="pills-profile" aria-selected="false">Ordinario</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" id="pills-trabajoUrgente" data-contenedor="pills-urgente" data-toggle="pill" href="#pills-urgente" role="tab" aria-controls="pills-contact" aria-selected="false">Urgente</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" id="pills-trabajoNoDisponible" data-contenedor="pills-urgente" data-toggle="pill" href="#pills-noDisponible" role="tab" aria-controls="pills-contact" aria-selected="false">No disponible</a>
				</li>

				<li class="nav-item hidden">
					<a class="nav-link" id="pills-busquedaTrabajo" data-contenedor="pills-busquedaTrabajo" data-toggle="pill" href="#pills-busqueda" role="tab" aria-controls="pills-contact" aria-selected="false">Busqueda</a>
				</li>

			</ul>

			<div class="tab-content my-3" id="pills-tabTrabajos">

				<div class="tab-pane fade show active" id="pills-total" role="tabpanel" aria-labelledby="pills-home-tab">
					
					<h4 class="text-muted">Todos mis trabajos</h4>

					<div class="pills-totalContenedor py-3">

						<div class="row text-muted cabeceraTablaTrabajos no-gutters col-sm-0 col-0">
							
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

						</div>

						<?php 

						if(count($trabajos) >= 1){

							//AQUI SE ESCRIBE EL CODIGO EN CASO DE QUE TENGA TRABAJOS AGREGADOS

							foreach ($trabajos as $key => $value) {

								$idTrabajo = $value['idTrabajo'];
								$respuesta = "";
								$porcentaje = $value['porcentaje'] * 100;
								$ganancia = $value['precio'] - 50;

								$trabajoUrgente = controladorListaTrabajos::obtenerTrabajoUrgentePorNombre($idTrabajo,$value['nombreProtesis']);

								$es = controladorListaTrabajos::tipoTrabajo($idTrabajo,$value['nombreProtesis']);

								$existePromocion = controladorListaTrabajos::verificarExistenciaPromocion($idTrabajo,$es);

								if($existePromocion){

									$boton = '<button class="btn bordesRedondos fondoBlanco btnDetallesPromocion" disabled><i style="color: #ededed; font-size: 27px;" class="fas fa-chart-bar"></i></button>';

								} else {
									
									$boton = '<button class="btn bordesRedondos fondoBlanco btnPromocionar" disabled><i style="color: #ededed; font-size: 27px;" class="fas fa-chart-line"></i></button>';

								}


								$value['nombreProtesis'] = ucfirst($value['nombreProtesis']);

								$respuesta .= 

										'<div class="trabajoLista">';

								$respuesta .= 

										'<div class="row text-muted cuerpoTablaTrabajos no-gutters">
							
											<div class="col-xl-5 col-lg-5 col-md-5 col-sm-12" id="'.$idTrabajo.'_'.$es.'" data-filtro="total">
												
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

										<hr class="my-1">';

										$respuesta .= '</div>';

										echo $respuesta;

									} else {

										$respuesta .= '<hr class="my-1">';

										$respuesta .= '</div>';
										
										echo $respuesta;

									}


							} //FIN DEL CICLO FOREACH

						} else {

							//AQUI SE ESCRIBE EL CODIGO EN CASO DE QUE NO TENGA TRABAJOS AGREGADOS
							
							echo '<div class="alert alert-danger">No hay trabajos agregados</div>';
						}

						 ?>

					</div>

				</div>

				<div class="tab-pane fade" id="pills-ordinario" role="tabpanel" aria-labelledby="pills-contact-tab">
					
					<h4 class="text-muted">Trabajos ordinarios</h4>

					<div class="pills-ordinarioContenedor py-3"></div>

				</div>

				<div class="tab-pane fade" id="pills-urgente" role="tabpanel" aria-labelledby="pills-contact-tab">
					
					<h4 class="text-muted">Trabajos urgentes</h4>	

					<div class="pills-urgenteContenedor py-3"></div>	

				</div>

				<div class="tab-pane fade" id="pills-noDisponible" role="tabpanel" aria-labelledby="pills-contact-tab">
					
					<h4 class="text-muted">Trabajos no disponibles en el buscador</h4>	

					<div class="pills-noDisponibleContenedor py-3"></div>	

				</div>

				<div class="tab-pane fade" id="pills-busqueda" role="tabpanel" aria-labelledby="pills-contact-tab">
					
					<h4 class="text-muted">Resultados de busqueda para: <span class="busquedaUsuario"></span></h4>	

					<div class="pills-busquedaTrabajoContenedor py-3"></div>	

				</div>


			</div>
			
			

		</div>

	</div>

	<?php 

		if(count($trabajos) != 0){

	    	$pagTrabajos = ceil(count($totalTrabajos) / $tope);

	        $rutasBak = $rutas[1];

	        echo '<input type="hidden" value="'.$rutas[1].'" class="paginaActual">';

	        if($pagTrabajos > 4){

	          //SÍ RUTAS 1 ES IGUAL A 1, MOSTRAMOS LA PAGINACION DEL 1 AL 4 SIN FLECHA IZQUIERDA

	          if ($rutasBak == 1) {

	             echo '<div class="paginacion">

	                      <ul class="pagination">';

	                        for ($i=1; $i <= 4; $i++) { 

	                          if($i == 1){

	                            echo '<li class="page-item active"> 
	                                    <a class="page-link" href="'.$url.'trabajos/'.$i.'">'.$i.'</a>
	                                  </li>';

	                          } else {

	                            echo '<li class="page-item" id="item'.$i.'"> 
	                                    <a class="page-link" href="'.$url.'trabajos/'.$i.'">'.$i.'</a>
	                                  </li>'; 
	                            
	                          }
	                          

	                        }

	                        echo '<li class="page-item disabled"> 
	                                <a class="page-link disabled">...</a>
	                              </li>

	                              <li class="page-item" id="item'.$pagTrabajos.'"> 
	                                <a class="page-link" href="'.$url.'trabajos/'.$pagTrabajos.'">'.$pagTrabajos.'</a>
	                              </li>

	                              <li class="page-item">
	                                <a class="page-link" href="'.$url.'trabajos/2"><i class="fas fa-caret-right"></i></a>
	                              </li>';
	              

	              echo '  </ul>

	                    </div>';

	          } else if( ($rutasBak != $pagTrabajos) &&
	                     ($rutasBak != 1) && 
	                     ($rutasBak < ($pagTrabajos/2)) &&
	                     ($rutasBak) < ($pagTrabajos-3) ){

	            //BOTONES DE LA MITAD DE LAS PAGINAS HACIA ABAJO

	            $numPaginaActual = $rutasBak;

	            echo '<div class="paginacion">

	                      <ul class="pagination">

	                      <li class="page-item">
	                        <a class="page-link" href="'.$url.'trabajos/'.($numPaginaActual-1).'"><i class="fas fa-caret-left"></i></a>
	                      </li>';

	                        for ($i=$numPaginaActual; $i <= ($numPaginaActual+3); $i++) { 
	                          
	                          echo '<li class="page-item" id="item'.$i.'"> 
	                                  <a class="page-link" href="'.$url.'trabajos/'.$i.'">'.$i.'</a>
	                                </li>'; 

	                        }

	                        echo '<li class="page-item disabled"> 
	                                <a class="page-link disabled">...</a>
	                              </li>

	                              <li class="page-item" id="item'.$pagTrabajos.'"> 
	                                <a class="page-link" href="'.$url.'trabajos/'.$pagTrabajos.'">'.$pagTrabajos.'</a>
	                              </li>

	                              <li class="page-item">
	                                <a class="page-link" href="'.$url.'trabajos/'.($numPaginaActual+1).'"><i class="fas fa-caret-right"></i></a>
	                              </li>';
	              

	              echo '  </ul>

	                    </div>';

	          } else if( ($rutasBak != $pagTrabajos) &&
	                     ($rutasBak != 1) &&
	                     ($rutasBak >= ($pagTrabajos/2)) &&
	                     ($rutasBak < ($pagTrabajos-3))
	                   ){

	            //BOTOS DE LA MITAD DE PAGINAS HACIA ARRIBA

	            $numPaginaActual = $rutasBak;

	            echo '<div class="paginacion">

	                      <ul class="pagination">

	                        <li class="page-item">
	                          <a class="page-link" href="'.$url.'trabajos/'.($numPaginaActual-1).'"><i class="fas fa-caret-left"></i></a>
	                        </li>
	                        
	                        <li class="page-item" id="item1">
	                          <a href="'.$url.'trabajos/1" class="page-link">1</a>
	                        </li>

	                        <li class="page-item disabled"> 
	                          <a class="page-link disabled">...</a>
	                        </li>';

	                        for ($i=$numPaginaActual; $i <= ($numPaginaActual+3); $i++) { 
	                          
	                          echo '<li class="page-item" id="item'.$i.'"> 
	                                  <a class="page-link" href="'.$url.'trabajos/'.$i.'">'.$i.'</a>
	                                </li>'; 

	                        }

	              echo '    <li class="page-item">
	                          <a class="page-link" href="'.$url.'trabajos/'.($numPaginaActual+1).'"><i class="fas fa-caret-right"></i></a>
	                        </li>

	                      </ul>

	                    </div>';

	          } else {

	            //SON LOS BOTONES PARA LAS ULTIMAS 4 PAGINAS Y LA PRIMERA PAGINA

	            $numPaginaActual = $rutasBak;

	            echo '<div class="paginacion">

	                      <ul class="pagination">

	                        <li class="page-item">
	                          <a class="page-link" href="'.$url.'trabajos/'.($numPaginaActual-1).'"><i class="fas fa-caret-left"></i></a>
	                        </li>
	                        
	                        <li class="page-item">
	                          <a href="'.$url.'trabajos/1" class="page-link">1</a>
	                        </li>

	                        <li class="page-item disabled"> 
	                          <a class="page-link disabled">...</a>
	                        </li>';

	                        for ($i=($pagTrabajos-3); $i <= $pagTrabajos; $i++) { 
	                          
	                          echo '<li class="page-item" id="item'.$i.'"> 
	                                  <a class="page-link" href="'.$url.'trabajos/'.$i.'">'.$i.'</a>
	                                </li>'; 

	                        }

	              echo '  </ul>

	                    </div>';
	          }

	        } else {

	          echo '<div class="paginacion">

	                  <ul class="pagination">';

	                    for ($i=1; $i <= $pagTrabajos; $i++) { 
	                      
	                      echo '<li class="page-item" id="item'.$i.'"> 
	                              <a class="page-link" href="'.$url.'trabajos/'.$i.'">'.$i.'</a>
	                            </li>'; 

	                    }
	          

	          echo '  </ul>

	                </div>';

	        }


	    }
	 ?>

</div>

<div class="modal fade" id="modalEditarInfoTrabajo" tabindex="-1">

	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">

			<div class="modal-header fondoBuscalabGris">

				<h5 class="modal-title bold nombreTrabajo"></h5>

				<button type="button" class="close txtBlanco" data-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">&times;</span>

				</button>

			</div>

			<div class="modal-body">
				
				<div class="row text-muted txtGrande">
					
					<div class="col-lg-3">

						<b>Precio <i class="fas fa-info-circle"></i></b>

						<div class="input-group input-group-sm pt-2">
						  <div class="input-group-prepend">
						    <span class="input-group-text">$</span>
						  </div>
						  <input type="text" class="form-control form-control-sm inputBorderBottom" aria-label="Precio" id="modalPrecio" onkeypress="return valida(event);" maxlength="5">
						</div>

					</div>

					<div class="col-lg-3">

						<b>Parte inicial <i class="fas fa-info-circle"></i></b>
						<select id="modalPorcientoAdelanto" class="form-control formcontrol-sm inputBorderBottom">
										
							<option value="0">0%</option>
							<option value="0.25">25%</option>
							<option value="0.5">50%</option>
							<option value="0.75">75%</option>
							<option value="1">100%</option>

						</select>

					</div>

					<div class="col-lg-3">
						<b>Días prueba <i class="fas fa-info-circle"></i></b>
						<select id="modalPrueba" class="form-control formcontrol-sm inputBorderBottom">
							<?php 

							for($i = 1; $i <= 30; $i++){

								echo '<option value="'.$i.'">'.$i.' días</option>';

							}

							 ?>

						</select>
					</div>

					<div class="col-lg-3">
						<b>Días terminado <i class="fas fa-info-circle"></i></b>
						<select id="modalTerminado" class="form-control formcontrol-sm inputBorderBottom">
							<?php 

							for($i = 1; $i <= 30; $i++){

								echo '<option value="'.$i.'">'.$i.' días</option>';

							}

							 ?>

						</select>
					</div>

				</div>

				<div class="urgente text-left">
							
					<input type="checkbox" id="checkUrgente" value="siUrgente" name="checkUrgente">
					<label for="checkUrgente">¿Hace trabajos urgentes?&nbsp;&nbsp;&nbsp;<i class="icon-urgente font-weight-bold"></i></label>

				</div>

				<div class="trabajosUrgentes hidden">
								
					<div class="row text-left text-muted txtGrande">

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 separacionMovil">
							
							<label for="terminadoUrgente" class="bold">Días terminado <i class="fas fa-info-circle infoTerminadoUrgente"></i></label>

							<div class="separacionMediana">
							
								<select id="modalTerminadoUrgente" class="form-control formcontrol-sm inputBorderBottom">
									<?php 

									for($i = 1; $i <= 15; $i++){

										echo '<option value="'.$i.'">'.$i.' días</option>';

									}

									 ?>

								</select>
								
							</div>

						</div>

						<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 separacionMovil">
							
							<label for="porcientoAdelantoUrgente" class="bold">Precio de trabajo urgente <i class="fas fa-info-circle infoPrecioUrgente"></i></label>

							<div class="separacionMediana">
				
								<div class="input-group input-group-sm pt-2">
								  <div class="input-group-prepend">
								    <span class="input-group-text">$</span>
								  </div>
								  <input type="text" class="form-control form-control-sm inputBorderBottom" id="modalPrecioUrgente" onkeypress="return valida(event);" maxlength="5">
								</div>
					
							</div>

						</div>

					</div> <!--FIN DEL ROW DEL FORMULARIO-->

					<input type="hidden" class="tipoTrabajo">
					<input type="hidden" class="idTrabajo">

				</div>

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btnBuscalab btnGuardarProductoModal">Guardar cambios</button>

			</div>

		</div>

	</div>

</div>