<?php 

//EXTRAEMOS EL TEXTO QUE ESCRIBIÓ EL USUARIO, SIN LOS GUIONES

if(isset($rutas[1]) && $rutas[1] != ""){
	
	$txtBuscar = str_replace("-", " ", $rutas[1]);

	//ESTABLECEMOS LA PAGINACION
	if(isset($rutas[2])){

		$pagina = $rutas[2];

	} else {

		$pagina = 1;

	}



	//VERIFICAMOS QUE ES LO QUE ESTÁ BUSCANDO EL USUARIO
	$es = ctrResultados::compararBusqueda($txtBuscar);
	
	if(isset($_GET['filtro'])){

		$filtroPrecioMinimo = ($_GET['filtroPrecioMinimo']!="") ? $_GET['filtroPrecioMinimo'] : 0;
		$filtroPrecioMaximo = ($_GET['filtroPrecioMaximo']!="") ? $_GET['filtroPrecioMaximo'] : 99999;
		$filtroEstado = $_GET['filtroEstado'];
		$filtroEntrega = $_GET['filtroEntrega'];
		$filtroMunicipio = (isset($_GET['filtroMunicipio'])) ? $_GET['filtroMunicipio'] : "";

		$filtroSql = "";
		$filtroSqlOrto = " and lista_precios_ortodoncia.precio between ".$filtroPrecioMinimo." and ".$filtroPrecioMaximo;

		$filtroSql .= ($es != "ortodoncia") ? " and lista_precios_protesis.precio between ".$filtroPrecioMinimo." and ".$filtroPrecioMaximo : " and lista_precios_ortodoncia.precio between ".$filtroPrecioMinimo." and ".$filtroPrecioMaximo;

		$filtroSql .= ($filtroEstado != "") ? " and sepomex.idEstado = ".$filtroEstado : "";

		$filtroSql .= ($filtroMunicipio != "") ? " and sepomex.idMunicipio = ".$filtroMunicipio : "";

		$filtroSql .= ($filtroEntrega != "") ? " and lista_precios_protesis.dias_entrega = ".$filtroEntrega : "";

		$paginacionFiltro = "?filtroPrecioMinimo=".$filtroPrecioMinimo."&filtroPrecioMaximo=".$filtroPrecioMaximo."&filtroEstado=".$filtroEstado."&filtroEntrega=".$filtroEntrega."&filtroMunicipio=".$filtroMunicipio."&filtro=";

		//echo $filtroSql;

	} else {

		$filtroSql = "";
		$filtroSqlOrto = "";
		$paginacionFiltro = "";

	}

	//BUSCAMOS SÍ COINCIDE EXACTAMENTE CON UNO DE NUESTROS REGISTROS DE PROTESIS U ORTODONCIA
	$datosBusquedaTotal = ctrResultados::resultadosTotales($es,$txtBuscar,$filtroSql,$filtroSqlOrto);

	if($datosBusquedaTotal >= 1){ //SÍ EXISTE AL MENOS UNO, CONTINUAMOS CON LA BUSQUEDA GENERAL EN TODAS LAS LISTAS DE PRECIOS
		$datosBusqueda = ctrResultados::realizarBusqueda($es,$txtBuscar,$pagina,$filtroSql,$filtroSqlOrto);
	
	} else {// SÍ NO COINCIDE CON NINGUNO DE NUESTROS REGISTROS, LO BUSCAMOS DE MANERA GENERAL CON OTRO ALGORITMO

		//EXTRAEMOS LOS DATOS DE LA BUSQUEDA
		$datosBusqueda = ctrResultados::realizarBusqueda("general",$txtBuscar,$pagina, $filtroSql, $filtroSqlOrto);


		//EL NUMERO DE RESULTADOS QUE NOS ARROJO EN TOTAL
		//$datosBusquedaTotal = ctrResultados::resultadosTotales($es,$txtBuscar);
		$datosBusquedaTotal = ctrResultados::resultadosTotales("general",$txtBuscar, $filtroSql, $filtroSqlOrto);
		
		
	}


	//NUMERO TOTAL DE PAGINAS PARA LA PAGINACION
	$tamanoPagina = 15;
	$total_paginas = ceil($datosBusquedaTotal / $tamanoPagina);

	//COLOCAMOS LA PALABRA QUE BUSCO EN EL INPUT DEL CABEZOTE

	ctrResultados::palabraCabezote($txtBuscar);

} else {
		
	$datosBusquedaTotal = 0;
	$txtBuscar = "";
	$total_paginas = 0;

	echo 

	"<script>
		
		index();

	</script>";

}



 ?>
<div class="container">

	<div class="row mt-4">
		
		<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 mt-3 filtrosDeBusqueda ocultarFiltrosDeBusqueda">
			
			<h5 class="text-muted"><i class="fas fa-filter mr-2	"></i>Filtros de búsqueda</h5>

			<button type="button" class="close btnMostrarFiltros col-xl-0 col-lg-0" style="color:gray;top: 0;position: absolute;right: 0;padding-right: 25px;font-size: 2rem;">
			 	<span aria-hidden="true">&times;</span>
			</button>
			
			<hr class="my-3">	

			<form class="filtros" method="GET" action="<?php echo $url; ?>resultados/<?php echo $rutas[1]; ?>">
				
				<div class="precio my-3">
					
					<i class="fas fa-dollar-sign mr-2 txtVerde"  style="font-size:1.5rem;"></i>
					<span class="text-muted"><b>Precio</b></span>
				
					<div class="row py-2">
						
						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
							<input type="text" name="filtroPrecioMinimo" id="filtroPrecioMinimo" class="form-control inputBorderBottom" placeholder="mínimo" maxlength="6" onkeypress="return valida(event)">
						</div>

						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
							<input type="text" name="filtroPrecioMaximo" id="filtroPrecioMaximo" class="form-control inputBorderBottom" placeholder="máximo" maxlength="6" onkeypress="return valida(event)">
						</div>

					</div>

				</div>

				<div class="ubicacion my-3">
					
					<i class="fas fa-map-marker-alt mr-2 txtRojo" style="font-size:1.5rem;"></i>
					<span class="text-muted"><b>Ubicación de laboratorio</b></span>
					
					<div class="row py-2">

						<div class="col-xl-12 col-lg-12 col-md-6 col-sm-6 col-6 py-2">
						
							<select name="filtroEstado" id="filtroEstado" class="form-control form-control-sm inputBorderBottom">
				
								<option value="">Estado...</option>

								<?php 

								$estados = controladorPerfil::ctrTodosEstados();

								foreach ($estados as $key => $value) {
									echo '<option value="'.$value['id_rep'].'">'.$value['nomb'].'</option>';
								}

								 ?>
							</select>

						</div>
						
						<div class="col-xl-12 col-lg-12 col-md-6 col-sm-6 col-6 py-2">
							<select name="filtroMunicipio" id="filtroMunicipio" class="form-control form-control-sm inputBorderBottom" disabled>
								<option value="">Municipio/Delegación...</option>
								<?php 

								/*$sepomex = controladorPerfil::ctrTodoSepomex(32);

								$filtrado = controladorPlantilla::unique_multidim_array($sepomex,"idMunicipio");
								$filtradoAux = controladorPlantilla::orderMultiDimensionalArray ($filtrado, "idMunicipio", false);*/

								//$ordenado = sort($filtrado, "idMunicipio");

								//print_r($filtradoAux);

								 ?>
							</select>
						</div>


					</div>

				</div>

				<div class="tiempo my-3">
					
					<i class="fas fa-clock mr-2" style="color: gray; font-size:1.5rem;"></i>
					<span class="text-muted"><b>Tiempo</b></span>
				
					<div class="row py-2">

						<div class="col-xl-12 col-lg-12 py-2">
							
							<select name="filtroEntrega" id="filtroEntrega" class="form-control form-control-sm inputBorderBottom">
								<option value="">Dias de entrega...</option>
								<?php 

								for ($i=1; $i <= 30 ; $i++) { 
									echo '<option value="'.$i.'">'.$i.' días</option>';
								}

								 ?>
							</select>

						</div>

					</div>
					
				</div>

				<!--<div class="categorias row my-3">
					
					<div class="col-lg-2 text-center">
						<i class="fas fa-sitemap" style="color:gray; font-size:1.5rem;"></i>
					</div>

					<div class="col-lg-10 pt-1">
						<span class="text-muted"><b>Categoría</b></span>
					</div>

				</div>-->

				<button class="btn btn-block btnBuscalab" name="filtro">Filtrar <i class="fas fa-arrow-right ml-2"></i></button>


			</form>
			
			<?php 

			if(isset($_GET['filtro'])){
				echo 
				'<a href="'.$url.'resultados/'.$rutas[1].'" class="noDecoration"><button class="btn btn-block btn-outline-danger mt-3">Borrar filtros <i class="fas fa-times ml-2"></i></button></a>';
			}

			 ?>

		</div> <!--TERMINA DIV DE LOS FILTROS-->

		<div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
			
			<div class="row my-3">
					
				<div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-7">

					<?php 

					if($datosBusquedaTotal > 0){
						$numDatosBusqueda = count($datosBusqueda);

						echo '<p class="text-muted">'.$numDatosBusqueda*$pagina.' de '.$datosBusquedaTotal.' resultados para <b>'.$txtBuscar.'</b></p>';
					}

					 ?>

				</div>

				<div class="col-xl-0 col-lg-0 col-md-3 col-sm-3 col-5 text-right">
					
					<!--<select name="ordenamiento" id="ordenamiento" class="form-control-sm form-control">
						<option value="">Ordenar</option>
						<option value="">Precio más bajo</option>
						<option value="">Precio más alto</option>
						<option value="">Ascendente</option>
						<option value="">Descendente</option>
					</select>-->

					<button class="btn btn-sm btn-secondary btnMostrarFiltros">Filtros <i class="fas fa-filter ml-2"></i></button>

					<?php 

					if(isset($_GET['filtro'])){
						echo 
						'<a href="'.$url.'resultados/'.$rutas[1].'" class="noDecoration"><button class="btn btn-sm btn-block btn-outline-danger mt-3">Borrar filtros <i class="fas fa-times ml-2"></i></button></a>';
					}

					 ?>

				</div>

			</div>

			<!--<a href="'.$url.'pre-orden/'.$labRuta.'/'.$trabajoRuta.'" class="productoAnclar" style="text-decoration: none;">

			<div class="row text-muted productoNuevo">
								
				<div class="col-lg-3">
					
					<div class="elementoImagenProductoNuevo">
						<img src="<?php echo $url; ?>vistas/asset/images/tecnicos/Laboratorio rickyrickon311051172169265.jpg" alt="imagen" class="img-fluid imagenProductoNuevo">
					</div>

				</div>

				<div class="col-lg-9 noPadding">
					<div class="nombreTrabajo">Corona de metal porcelana feldespatica total </div>
					<div class="nombreLaboratorio">por: <span class="colorBuscalab">Laboratorio dental antonio parra</span></div>
					<div class="py-3 precio">$ 500</div>

					<div class="row">
						
						<div class="col-lg-8 entrega">Entrega en 5 días</div>

						<div class="col-lg-4 calificacion"><i class="fas fa-smile mr-2 colorBuscalab iconoCalificacion"></i> <p>( 0 )</p></div>

					</div>

				</div>

			</div>

			</a>-

			<hr class="my-2">-->

			<?php 

			//ANTERIORES RESULTADOS
			/*if($datosBusquedaTotal > 0){

				foreach ($datosBusqueda as $key => $value) {

					echo '<script>
							GMaps.geocode({
							  address: "'.$value["direccion"].'",
							  callback: function(results, status) {
							    if (status == "OK") {
							      var latlng = results[0].geometry.location;
							      mapa.addMarker({
							        lat: latlng.lat(),
							        lng: latlng.lng(),
							        infoWindow: {
									  content: "<p>Laboratorio: '.$value["laboratorio"].'</p>"
									}
							      });
							    }
							  }
							});
		            	</script>';

					if(!isset($value['material'])){
						$material = "<br>";
					} else {
						$material = "(".$value['material'].")";
					}

					$value['trabajo'] = ucfirst($value['trabajo']);

					$labRuta = strtolower(str_replace(" ", "-", $value['laboratorio']));

					$trabajoRuta = strtolower(str_replace(" ", "-", $value['trabajo']));

				    echo '<a href="'.$url.'pre-orden/'.$labRuta.'/'.$trabajoRuta.'" class="productoAnclar" style="text-decoration: none;">

							<div class="row">
								
								<div class="col-lg-3">
									
									<div class="elementoImagenProducto">
										<img src="'.$url.'vistas/asset/images/tecnicos/'.$value["imagen"].'" alt="imagen" class="img-fluid imagenProducto">
										<!--<span class="descripcionLaboratorio"><span class="far fa-folder"></span></span>-->
									</div>

								</div>

								<div class="col-lg-5 pt-3">
									<div class="colorBuscalab nombreLaboratorio txtCorto">'.$value['laboratorio'].'</div>
									<div class="text-muted pt-2 nombreTecnico">'.$value['artista'].'</div>
									<div class="text-muted pt-2 direccion txtCorto">'.$value['direccion'].'</div>

									<div class="iconosClasificacion">
										<i class="fas fa-smile ml-2" style="color: #9ac76d;"></i>
										<i class="fas fa-exclamation-triangle ml-2 txtRojo"></i>
										<i class="fas fa-clock ml-2 text-muted"></i>
										<i class="fas fa-dollar-sign ml-2 txtVerde"></i>
									</div>
								</div>

								<div class="col-lg-4">
									
									<div class="elementoPrecios py-2">
										
										<div class="text-muted pt-1 nombreTrabajo txtCorto">'.$value['trabajo'].'</div>
										<div class="text-muted nombreMaterial">'.$material.'</div>
										<div class="text-muted pt-1 precioTrabajo">$'.$value['precio'].'</div>
										<div class="precioPremium mt-3">
											<div class="bold">PREMIUM</div>
											<div>orden sin costo</div>
										</div>

									</div>

								</div>

							</div>

						</a>

						<hr class="my-2">';
				}

			} else {
				echo '<div class="alert alert-danger">No hay resultados para ésta búsqueda</div>';
			} // ANTERIORES RESULTADOS*/

			
			if($datosBusquedaTotal > 0){

				foreach ($datosBusqueda as $key => $value) {

					/*CODIGO PARA BUSCAR LA DIRECCION COMPLETA DEL LABORATORIO Y MOSTRARLO EN EL MAPA*/

					$infoGeolocalizacion = controladorPerfil::ctrSepomex($value['id_rep']);

					$direccionCompleta = $value['direccion']." ".$infoGeolocalizacion['asentamiento']." ".$infoGeolocalizacion['municipio']." ".$infoGeolocalizacion['cp'];

					echo '<script>
							GMaps.geocode({
							  address: "'.$direccionCompleta.'",
							  callback: function(results, status) {
							    if (status == "OK") {
							      var latlng = results[0].geometry.location;
							      mapa.addMarker({
							        lat: latlng.lat(),
							        lng: latlng.lng(),
							        infoWindow: {
									  content: "<p>Laboratorio: '.$value["laboratorio"].'</p>"
									}
							      });
							    }
							  }
							});
		            	</script>';

		            /*********************************************************************************/

					if(!isset($value['material'])){
						$material = "<br>";
					} else {
						$material = "(".$value['material'].")";
					}

					$value['trabajo'] = ucfirst($value['trabajo']);

					$labRuta = strtolower(str_replace(" ", "-", $value['laboratorio']));

					$trabajoRuta = strtolower(str_replace(" ", "-", $value['trabajo']));

					if( isset($value['id']) ){
						$queEs = "protesis";
						$idTrabajo = $value['id'];
					} elseif ( isset($value['idOrto']) ) {
						$queEs = "ortodoncia";
						$idTrabajo = $value['idOrto'];
					}

					$calificaciones = controladorListaTrabajos::ctrCalificaciones($idTrabajo,$queEs);

					if(!$calificaciones){
						$numTotalCalificaciones = 0;
						$htmlPromedioCalificacion = "";
					} else {

						$numTotalCalificaciones = count($calificaciones);

						$totalCalificaciones = 0;

						foreach ($calificaciones as $key => $value2) {
							
							$precio = $value2['precio'];
							$calidad = $value2['calidad'];
							$tiempo = $value2['tiempo'];

							$total = ($precio + $calidad + $tiempo) / 3;

							$totalCalificaciones = $totalCalificaciones + $total;

						}

						$promedioCalificacion = bcdiv($totalCalificaciones, $numTotalCalificaciones, 1);

						$htmlPromedioCalificacion = $promedioCalificacion.'<i class="fas fa-star ml-2 text-muted"></i>';

					}

					echo '<a href="'.$url.'pre-orden/'.$labRuta.'/'.$trabajoRuta.'" class="productoAnclar" style="text-decoration: none;">

						<div class="row text-muted productoNuevo">
											
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-5">
								
								<div class="elementoImagenProductoNuevo">
									<img src="'.$url.'vistas/asset/images/tecnicos/'.$value["imagen"].'" alt="imagen" class="img-fluid imagenProductoNuevo">
									<!--<span class="descripcionLaboratorio"><span class="far fa-folder"></span></span>-->
								</div>

							</div>

							<div class="col-xl-9 col-lg-9 col-md-9 col-sm-8 col-7">
								<div class="nombreTrabajo">'.$value['trabajo'].'</div>
								<div class="nombreLaboratorio">por: <span class="colorBuscalab">'.$value['laboratorio'].'</span></div>
								<div class="ubicacionLaboratorio pt-1"><i class="fas fa-map-marker-alt mr-1"></i> '.$infoGeolocalizacion['estado'].', '.$infoGeolocalizacion['municipio'].'</div>
								<div class="py-3 precio">$ '.$value['precio'].'</div>

								<div class="row">
									
									<div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-5 entrega"><span class="col-0">Entrega en </span>'.$value['tiempoEntrega'].' días</div>

									<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-7 calificacion"><i class="fas fa-smile mr-2 colorBuscalab iconoCalificacion"></i> <p>( '.$numTotalCalificaciones.' ) <i class="ml-2">'.$htmlPromedioCalificacion.'</i></p></div>

								</div>

							</div>

						</div>

						</a>

						<hr class="my-1">';
				}

			} else {
				echo '<div class="alert alert-danger">No hay resultados para ésta búsqueda</div>';
			}

			 ?>

			<!--<a href="infoproducto" class="productoAnclar" style="text-decoration: none;">

				<div class="row">
					
					<div class="col-lg-3">
						
						<div class="elementoImagenProducto">
							<img src="https://via.placeholder.com/180x180" alt="imagen" class="img-fluid imagenProducto">
							<span class="descripcionLaboratorio"><span class="icon-dantu_icono_carrito"></span></span>
						</div>

					</div>

					<div class="col-lg-5 pt-3">
						<div class="colorDantu nombreLaboratorio">Laboratorio Dental Online</div>
						<div class="text-muted pt-2 nombreTecnico">Tec. Antonio Dominguez</div>
						<div class="text-muted pt-2 direccion">Condominio 48 Lote 1 Colonia Fernando de Alba</div>

						<div class="iconosClasificacion">
							<span class="icon-dantu_icono_muy-bien" style="color: gray; font-size: 30px;"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></span>
							<span class="icon-dantu_icono_rayo" style="color: gray; font-size: 30px;"><span class="path1"></span><span class="path2"></span></span>
							<span class="icon-dantu_icono_urgente" style="color: gray; font-size: 30px;"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></span>
							<span class="icon-dantu_icono_reloj" style="color: gray; font-size: 30px;"><span class="path1"></span><span class="path2"></span><span class="path3"></span></span>
							<span class="icon-dantu_icono_precio" style="color: gray; font-size: 30px;"><span class="path1"></span><span class="path2"></span><span class="path3"></span></span>
						</div>
					</div>

					<div class="col-lg-4">
						
						<div class="elementoPrecios py-2">
							
							<div class="text-muted pt-1 nombreTrabajo">Corona de Acrilico</div>
							<div class="text-muted nombreMaterial">(Acrilico termocurado)</div>
							<div class="text-muted pt-1 precioTrabajo">$150</div>
							<div class="precioPremium mt-2">
								<div>PREMIUM</div>
								<div>-25%</div>
							</div>

						</div>

					</div>

				</div>

			</a>

			<hr class="my-2">-->

			<!--INICIA EL CODIGO DEL CARROUSEL WOOCOMMERCE-->

			<!--<div id="carruselWoocommerce" class="carousel slide mb-2" data-ride="carousel">

			  	<div class="carousel-inner">

				    <div class="carousel-item active">

				      	<div class="row">
				      	
							<div class="col-lg-4">
									
								<div class="item">

									<div class="imagenProductoMarket">
										
										<img src="https://www.dentalcost.es/2890-large_default/coltosol-f-cemento-provisional-bote-38gr.jpg" alt="producto market" class="img-fluid">

										<span class="descuentoProductoMarket">-25%</span>

									</div>


									<hr class="my-2">

									<div class="row">
										
										<div class="col-lg-8">
											
											<span class="text-muted">Cemento temporal</span>
											<div class="text-muted">$510</div>

										</div>

										<div class="col-lg-4 text-right">
											
											<span class="icon-dantu_icono_carrito" style="font-size: 40px; color:gray"></span>

										</div>

									</div>

								</div>

							</div>

							<div class="col-lg-4">
								
								<div class="item">

									<div class="imagenProductoMarket">
										
										<img src="https://www.dentalcost.es/4502-thickbox_default/proviscell-cemento-temporal-sin-eugenol-2-tubos-25g.jpg" alt="producto market" class="img-fluid">

										<span class="descuentoProductoMarket">-25%</span>

									</div>


									<hr class="my-2">

									<div class="row">
										
										<div class="col-lg-8">
											
											<span class="text-muted">Cemento temporal</span>
											<div class="text-muted">$510</div>

										</div>

										<div class="col-lg-4 text-right">
											
											<span class="icon-dantu_icono_carrito" style="font-size: 40px; color:gray"></span>

										</div>

									</div>

								</div>

							</div>

							<div class="col-lg-4">
								
								<div class="item">

									<div class="imagenProductoMarket">
										
										<img src="https://www.dentalcost.es/6136-thickbox_default/kit-cemento-temporal-sin-eugenol-jeringa-4x6gr.jpg" alt="producto market" class="img-fluid">

										<span class="descuentoProductoMarket">-25%</span>

									</div>


									<hr class="my-2">

									<div class="row">
										
										<div class="col-lg-8">
											
											<span class="text-muted">Cemento temporal</span>
											<div class="text-muted">$510</div>

										</div>

										<div class="col-lg-4 text-right">
											
											<span class="icon-dantu_icono_carrito" style="font-size: 40px; color:gray"></span>

										</div>

									</div>

								</div>

							</div>

				      	</div>
			    	</div> <!--TERMINA PRIMER PAGINACION DE ITEMS--

			    	<div class="carousel-item">
				      	<div class="row">
				      	
							<div class="col-lg-4">
									
								<div class="item">

									<div class="imagenProductoMarket">
										
										<img src="https://cdn.totalcode.com/newstetic/product-zoom/es/vitrebond-ionomero-de-vidrio-tipo-liner-base-cavitaria-kit-polvo-9-g--liqudio-5.5-ml-1.jpg" alt="producto market" class="img-fluid">

										<span class="descuentoProductoMarket">-50%</span>

									</div>


									<hr class="my-2">

									<div class="row">
										
										<div class="col-lg-8">
											
											<span class="text-muted">Ionomero de vidrio</span>
											<div class="text-muted">$300</div>

										</div>

										<div class="col-lg-4 text-right">
											
											<span class="icon-dantu_icono_carrito" style="font-size: 40px; color:gray"></span>

										</div>

									</div>

								</div>

							</div>

							<div class="col-lg-4">
								
								<div class="item">

									<div class="imagenProductoMarket">
										
										<img src="https://www.dentalcost.es/6543-thickbox_default/ionolux-ionomero-vidrio-polvo-12gr-liq-5gr.jpg" alt="producto market" class="img-fluid">

										<span class="descuentoProductoMarket">-50%</span>

									</div>


									<hr class="my-2">

									<div class="row">
										
										<div class="col-lg-8">
											
											<span class="text-muted">Ionomero de vidrio</span>
											<div class="text-muted">$300</div>

										</div>

										<div class="col-lg-4 text-right">
											
											<span class="icon-dantu_icono_carrito" style="font-size: 40px; color:gray"></span>

										</div>

									</div>

								</div>

							</div>

							<div class="col-lg-4">
								
								<div class="item">

									<div class="imagenProductoMarket">
										
										<img src="http://cosmodent.cl/img/p/4/6/5/465-large_default.jpg" alt="producto market" class="img-fluid">

										<span class="descuentoProductoMarket">-50%</span>

									</div>


									<hr class="my-2">

									<div class="row">
										
										<div class="col-lg-8">
											
											<span class="text-muted">Ionomero de vidrio</span>
											<div class="text-muted">$300</div>

										</div>

										<div class="col-lg-4 text-right">
											
											<span class="icon-dantu_icono_carrito" style="font-size: 40px; color:gray"></span>

										</div>

									</div>

								</div>

							</div>

				      	</div>

			    	</div> <!--TERMINA SEGUNDA PAGINACION DE ITEMS--
				    	
			  	</div>

				<a class="carousel-control-prev" href="#carruselWoocommerce" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="carousel-control-next" href="#carruselWoocommerce" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>

			</div> <!--TERMINA EL DIV DEL CARROUSEL-->

			<!--<hr class="my-3">-->

			<div id="paginacion" class="mb-3">
					
				<div class="row">

					<div class="col-12 txtAzul text-right">
							<b>
						<?php if ($total_paginas > 1) {

				            	$txtBuscar = str_replace ( " " , "+" , $txtBuscar);

							    if ($pagina != 1){

							    	//ICONO DE LA FLECHA
							    	echo '<a class="paginacion" href="'.$url.'resultados/'.$rutas[1].'/'.$pagina.'"><span class="icon-buscalab-icono-flecha2-azul-izquierda"></span></a>';
							  	}

						      	for ($i=1;$i<=$total_paginas;$i++) {

						        	if ($pagina == $i){
							            //si muestro el índice de la página actual, no coloco enlace
							            echo '<span class="indice text-muted"> '.$pagina.'</span>';
						            } else{
							            //si el índice no corresponde con la página mostrada actualmente,
							            //coloco el enlace para ir a esa página
								        echo ' <a class="paginacion" href="'.$url.'resultados/'.$rutas[1].'/'.$i.'/'.$paginacionFiltro.'"><span class="">'.$i.'</span></a>';
						            
						            }

						      	}

							     	if ($pagina != $total_paginas){
							     		//ICONO DE LA FLECHA
							        	echo '<a class="paginacion" href="resultados'.($pagina+1).'"><span class="icon-buscalab-icono-flecha2-azul-derecha"></span></a>';
							    	}
							}
							?>
								</b>
					</div>

				</div>

			</div>


		</div> <!--TERMINA DIV DE LOS RESULTADOS-->

	</div>

</div>
