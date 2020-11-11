<?php 

require_once "vendor/configFB.php";

if(isset($_POST['submit'])){

	$correo = $_POST['correo'];
	$password = $_POST['password'];
	// 0 es Tecnico dental
	//$tipo = 0;

	ctrUsuario::ctrVerificarUsuario($correo,$password);

} else if(isset($_POST['submitRegistro'])){

	ctrUsuario::ctrRegistrarUsuario();
	
}

if(isset($_SESSION['dentista'])){

	$es = "dentista";
	$idUsuario = $_SESSION['dentista'];
	$nombrePerfil = controladorPerfil::ctrDatosPerfil($idUsuario,$es);
	echo '<input type="hidden" class="primeraLetra" value="'.substr(ucfirst($nombrePerfil['nomb']),0,1).'">';

} elseif(isset($_SESSION['tecnico'])){

	$es = "tecnico";
	$idUsuario = $_SESSION['tecnico'];
	$nombrePerfil = controladorPerfil::ctrDatosPerfil($idUsuario,$es);
	echo '<input type="hidden" class="primeraLetra" value="'.substr(ucfirst($nombrePerfil['nomb_art']),0,1).'">';

}



 ?>

<div class="container-fluid shadow cabezotePrincipal sticky-top">
	
	<div class="row mx-1">
		
		<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6">
			
			<a href="<?php echo $url; ?>" style="text-decoration: none;">

				<img src="<?php echo $url; ?>vistas/asset/images/logo.png" alt="Buscalab" class="img-fluid logoPrincipal">
				
				<span class="txtLogo">
					<span class="colorBuscalabGris bold">DIVUM Soft</span>
				</span>

			</a>

		</div>
		
		<div class="col-xl-4 col-lg-3 col-md-6 col-sm-6 col-6">

			<div class="menuDispositivoMovil text-right col-lg-0 col-xl-0">

				<?php 

				if(isset($_SESSION['dentista']) || isset($_SESSION['tecnico'])){

				 ?>

				<i class="fas fa-bell campananotificacion mr-3">
					<!--<p class="numnotificacion">2</p>-->
				</i>
				
				<div class="dropdownnotificacion hidden">

					<div class="text-left cabezotenotificacion"><b>notificacion</b></div>

					<div class="contenidonotificacion">

					</div>

					<div class="text-center masnotificacion bold"><a href="<?php echo $url; ?>notificacion">Ver todas las notificacion</a></div>

				</div>

				<?php 
				}
				 ?>

				<i class="fas fa-search-location mr-3"></i>

				<i class="fas fa-bars"></i>
				
			</div>

			<div class="col-md-0 col-sm-0 col-0">
				
				<div class="input-group pt-3 text-right busquedaCabezote busqueda" style="border-bottom: 1px solid #a5a5a5">
					
					<input name="q" type="text" class="form-control typeahead typer buscarCabezote" placeholder="Buscar..." autocomplete="off">
					
					<a href="<?php echo $url; ?>resultados">
						<button class="btn btnBuscarCabezote" id="btnBuscarCabezote"><i class="fas fa-search-location"></i></button>
					</a>

				</div>
				
			</div>
			

		</div>

		<div class="col-xl-5 col-lg-6 col-md-0 col-sm-0 col-0 text-right">
			
			<?php 

				echo ctrCabezote::ctrCabezoteSesion($url);

			 ?>

		</div>

		<div class="col-xl-0 col-lg-0 col-md-12 col-sm-12 col-12 buscadorMovil">
			
			<div class="input-group my-3 text-right busquedaCabezote busqueda" style="border-bottom: 1px solid #a5a5a5">
					
				<input name="q" type="text" class="form-control typeahead typer buscarCabezote" placeholder="Buscar..." autocomplete="off">
				
				<a href="<?php echo $url; ?>resultados" class="buscadorMovilBoton">
					<button class="btn btnBuscarCabezote" id="btnBuscarCabezote"><i class="fas fa-search-location"></i></button>
				</a>

			</div>

		</div>

	</div>

</div>