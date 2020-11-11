<div class="container-fluid" style="padding:0">

	<div class="buscador text-center shadow" id="buscador">

		<!--<img src="<?php echo $url ?>vistas/asset/images/mapa.gif" class="img-fluid mapaPrincipal">-->
		
		<div class="mapaPrincipal"></div>

		<div class="buscadorPrincipal">
			
			<div class="row no-gutters my-3">
				<div class="col-xl col-lg col-md col-sm-0 col-0"></div>
				<div class="col-xl-8 col-lg-9 col-md-10 col-sm-12 col-12">
					
					<div id="busqueda" class="input-group busqueda">
						
						<input id="buscar" type="text" class="form-control typer typeahead buscar" placeholder="">
					
						<a href="<?php echo $url; ?>resultados">
							<button class="btn" id="btnBuscar"><i class="fas fa-search-location"></i></button>
						</a>

					</div>

				</div>
				<div class="col-xl col-lg col-md col-sm-0 col-0"></div>

			</div>


			<h1 class="txtBlanco pt-3">Encuentra tu trabajo dental ideal</h1>
			
		</div>


	</div>	

</div>

<div class="container-fluid my-4 elementosBusqueda text-muted text-center">

	<h4 class="pb-2">Búsqueda rápida</h4>
	
	<ul>
		<li>
			<a href="<?php echo $url ?>resultados/carilla">
				<img src="<?php echo $url; ?>vistas/asset/images/carilla.png" alt="Carillas">
				<p>Carilla</p>
			</a>
		</li>

		<li>
			<a href="<?php echo $url ?>resultados/prostodoncia-total">
				<img src="<?php echo $url; ?>vistas/asset/images/prostodonciaTotal.jpg" alt="Prostodoncia total">
				<p>Prostodoncia</p>
			</a>
		</li>

		<li>
			<a href="<?php echo $url ?>resultados/corona">
				<img src="<?php echo $url; ?>vistas/asset/images/corona.jpg" alt="Coronas">
				<p>Corona</p>
			</a>
		</li>

		<li>
			<a href="<?php echo $url ?>resultados/retenedor">
				<img src="<?php echo $url; ?>vistas/asset/images/retenedor.jpg" alt="Retenedores">
				<p>Retenedor</p>
			</a>
		</li>
	</ul>

</div>

<div class="container-fluid bloqueComunidad pb-4 shadow">
		
	<!--TEXTO INFORMATIVO-->

	<div class="row no-gutters">

		<div class="col-lg-12 col-md-12 col-sm-12 col-12 pt-xl-5 pt-lg-5 pt-md-4 pt-4 text-center text-muted">
			
			<h2><strong class="colorBuscalab bold">PRIMER</strong> BUSCADOR DE LABORATORIOS DENTALES</h2>

		</div>

	</div>

	<div class="">

		<div class="baseTriangulo"></div>
		
		<div class="lineaComunidad"></div>

		<!--<div class="triangulo"></div>-->
		
	</div>

	<div class="row pt-5 no-gutters">

		<div class="col-lg-3 col-md-2 col-sm-0 col-0"></div>

		<div class="col-lg-6 col-md-8 col-sm-12 col-12 text-center text-muted txtGrande">

			<p>Mejoramos la comunicación, conectamos pagos, pedidos y envíos entre dentistas/clínicas y técnicos dentales/laboratorios de la <i class="colorBuscalab bold"><u>Clínica DIVUM</u></i></p>

		</div>

		<div class="col-lg-3 col-md-2 col-sm-0 col-0"></div>

	</div>
	

</div>



<!--<div class="container py-xl-5 py-lg-5 py-md-3 py-3 pasos">
	
	<div class="row">
		
		<div class="col-lg-5 col-md-12 col-sm-12 col-12 text-center texto">
			
			<h1 class="bold colorBuscalab">Busca y filtra cientos de laboratorios dentales<i class="fas fa-teeth-open ml-4"></i></h1>

			<p class="text-muted">Busca algún <i>material o trabajo (corona, metal porcelana, prostodoncia, emax...)</i>, filtra los resultados y cotiza tu <b>laboratorio dental ideal.</b></p>
			

		</div>

		<div class="col-lg-7 col-md-12 col-sm-12 col-12">
			
			<img src="<?php echo $url; ?>vistas/asset/images/paso1.gif" alt="Paso 1" data-gif="<?php echo $url; ?>vistas/asset/images/buscador.gif" class="paso1 img-fluid" width="700px">

		</div>

	</div>

</div>

<hr>

<div class="container py-xl-5 py-lg-5 py-md-3 py-3 pasos">
	
	<div class="row">
		
		<div class="col-lg-5 col-md-12 col-sm-12 col-12 text-center texto">
			
			<h1 class="bold colorBuscalab">Selecciona un laboratorio, llena la orden y confírmala<i class="fas fa-check-circle ml-4"></i></h1>

			<p class="text-muted">Cuando selecciones tu laboratorio dental ideal, proporciona todos los detalles posibles a la orden, <b>incluso puedes agregar fotos</b>. Buscalab <i>recogerá la impresión</i> en tu clínica dental.</p>
			

		</div>

		<div class="col-lg-7 col-md-12 col-sm-12 col-12">
			
			<img src="<?php echo $url; ?>vistas/asset/images/paso2.gif" alt="Paso 2" data-gif="<?php echo $url; ?>vistas/asset/images/resultados.gif" class="paso2 img-fluid" width="700px">

		</div>

	</div>

</div>

<hr>

<div class="container py-xl-5 py-lg-5 py-md-3 py-3 pasos pb-5">
	
	<div class="row">
		
		<div class="col-lg-5 col-md-12 col-sm-12 col-12 text-center texto">
			
			<h1 class="bold colorBuscalab">Selecciona un método de pago y genera tu orden<i class="fas fa-credit-card ml-4"></i></h1>

			<p class="text-muted">Puedes pagar con <b>tarjeta de crédito, débito, depósito bancario, pago en OXXO</b> <i>(Mercado Pago)</i> y con tu cuenta <i>PayPal</i>.</p>
			

		</div>

		<div class="col-lg-7 col-md-12 col-sm-12 col-12">
			
			<img src="<?php echo $url; ?>vistas/asset/images/paso3.gif" alt="Paso 3" data-gif="<?php echo $url; ?>vistas/asset/images/pago.gif" class="paso3 img-fluid" width="700px">

		</div>

	</div>

</div>-->

<hr>

<div class="container py-xl-5 py-lg-5 py-md-3 py-3">
	
	<div class="row my-3 crearCuenta text-muted">
		
		<div class="col-lg-6 col-md-12 col-sm-12 col-12 my-md-4 my-4 text-center">
			
			<img src="<?php echo $url; ?>vistas/asset/images/tecnicos.png" alt="Protesistas" class="img-fluid mb-xl-5 mb-lg-5 mb-md-4 mb-4">

			<h3>Soy un Laboratorio Dental</h3>

			<p class="pt-3">Sube la información de tu laboratorio y promociona tus trabajos <b>gratis</b> dentro de la clínica</p>

			<button class="btn btn-block py-3 mt-4 btnBuscalab crearCuentaTecnico" <?php if(isset($_SESSION['tecnico']) || isset($_SESSION['dentista'])){echo 'disabled';} ?> >Crear cuenta y sube tu lista de <br class="col-xl-0 col-lg-0 col-md-0 col-sm-0"> precios <i class="fas fa-user-plus ml-2"></i></button>

		</div>

		<div class="col-lg-6 col-md-12 col-sm-12 col-12 my-md-4 my-4 text-center">
			
			<img src="<?php echo $url; ?>vistas/asset/images/dentistas.png" alt="Dentistas" class="img-fluid mb-xl-5 mb-lg-5 mb-md-4 mb-4">

			<h3>Soy un Dentista</h3>
			
			<p class="pt-3">Regístrate y cotiza el precio de los laboratorios para encontrar el mejor trabajo para tu paciente.</p>

			<button class="btn btn-block py-3 mt-4 btnBuscalab crearCuentaDentista" <?php if(isset($_SESSION['tecnico']) || isset($_SESSION['dentista'])){echo 'disabled';} ?>>Crear cuenta y ordena <b>AHORA</b> <i class="fas fa-arrow-right ml-2"></i></button>

		</div>

	</div>

</div>
<!--<div class="container">

	<h3 class="text-center">Odontograma <i class="fas fa-teeth-open ml-2"></i></h3>

	<div id="odontograma" class="row">

		<div class="col-lg-6 float-left">

			<ul class="dientesOdonto">

				<li class="diente" data-num="18"><p>18</p></li>
				<li class="diente" data-num="17"><p>17</p></li>
				<li class="diente" data-num="16"><p>16</p></li>
				<li class="diente" data-num="15"><p>15</p></li>
				<li class="diente" data-num="14"><p>14</p></li>
				<li class="diente" data-num="13"><p>13</p></li>
				<li class="diente" data-num="12"><p>12</p></li>
				<li class="diente" data-num="11"><p>11</p></li>

			</ul>

		</div>

		<div class="col-lg-6 text-right">

			<ul class="dientesOdonto">

				<li class="diente" data-num="21"><p>21</p></li>
				<li class="diente" data-num="22"><p>22</p></li>
				<li class="diente" data-num="23"><p>23</p></li>
				<li class="diente" data-num="24"><p>24</p></li>
				<li class="diente" data-num="25"><p>25</p></li>
				<li class="diente" data-num="26"><p>26</p></li>
				<li class="diente" data-num="27"><p>27</p></li>
				<li class="diente" data-num="28"><p>28</p></li>

			</ul>

		</div>

		<div class="col-lg-6 float-left pt-3">

			<ul class="dientesOdonto">

				<li class="diente" data-num="48"><p>48</p></li>
				<li class="diente" data-num="47"><p>47</p></li>
				<li class="diente" data-num="46"><p>46</p></li>
				<li class="diente" data-num="44"><p>44</p></li>
				<li class="diente" data-num="43"><p>43</p></li>
				<li class="diente" data-num="45"><p>45</p></li>
				<li class="diente" data-num="42"><p>42</p></li>
				<li class="diente" data-num="41"><p>41</p></li>

			</ul>

		</div>

		<div class="col-lg-6 text-right pt-3">

			<ul class="dientesOdonto">

				<li class="diente" data-num="31"><p>31</p></li>
				<li class="diente" data-num="32"><p>32</p></li>
				<li class="diente" data-num="33"><p>33</p></li>
				<li class="diente" data-num="34"><p>34</p></li>
				<li class="diente" data-num="35"><p>35</p></li>
				<li class="diente" data-num="36"><p>36</p></li>
				<li class="diente" data-num="37"><p>37</p></li>
				<li class="diente" data-num="38"><p>38</p></li>

			</ul>

		</div>

	</div>

	<div class="text-center py-2">

		<button class="btn btnOutlineBuscalab btnAceptarOdontograma">Aceptar <i class="fas fa-check ml-2"></i></button>

	</div>

</div> -->


<!--<div class="container pt-5">

	<div class="row my-3 caracteristicas">
		
		<div class="col-lg-3">
			
			<img src="<?php echo $url; ?>vistas/asset/images/usos.png" alt="Formas de uso" class="img-fluid mb-lg-5">

			<h5 class="colorBuscalab mb-lg-4">FORMAS DE USO</h5>

			<p class="text-muted">Buscalab es una plataforma dinámica e intuitiva para potenciar tu negocio</p>

		</div>

		<div class="col-lg-3">
			
			<img src="<?php echo $url; ?>vistas/asset/images/pago.png" alt="Formas de pago" class="img-fluid mb-lg-5">

			<h5 class="colorBuscalab mb-lg-4">FORMAS DE PAGO</h5>

			<p class="text-muted">Contamos con pagos en diferentes plataformas digitales y tiendas autorizadas</p>

		</div>

		<div class="col-lg-3">
			
			<img src="<?php echo $url; ?>vistas/asset/images/dentistas.png" alt="Dentistas" class="img-fluid mb-lg-5">

			<h5 class="colorBuscalab mb-lg-4">ODONTÓLOGOS</h5>

			<p class="text-muted">Todos los laboratorios dentales y productos que mejoran tu servicio</p>

		</div>

		<div class="col-lg-3">
			
			<img src="<?php echo $url; ?>vistas/asset/images/tecnicos.png" alt="Protesistas" class="img-fluid mb-lg-5">

			<h5 class="colorBuscalab mb-lg-4">PROTESISTAS</h5>
			
			<p class="text-muted">Logramos que tu trabajo obtenga mejor rendimiento y aumentes ventas</p>

		</div>

	</div>

</div>-->
