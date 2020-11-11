<?php 

$clase = 'sidenav';

if(isset($_GET['ruta'])){

//	$clase = "sidenavGeneral";

}

if(isset($_SESSION['dentista'])){

	$datosPerfil = controladorPerfil::ctrDatosPerfil($_SESSION['dentista'],"dentista");

	if( ($datosPerfil['img_perfil'] == "dentista.png") ){

		$menuLateral = 

		'<div class="bloqueFotoPerfil">
			<a href="'.$url.'perfil">
				<div class="fotoPerfil">
					<p class="primeraLetraNombre txtBlanco"></p>
				</div>
			</a>
		</div>';

	} else{

		$menuLateral = 

		'<div class="bloqueFotoPerfil">
			<a href="'.$url.'perfil">
				<img src="'.$url.'vistas/asset/images/dentistas/'.$datosPerfil['img_perfil'].'" alt="'.$datosPerfil['nomb'].'" class="img-fluid fotoPerfil">
			</a>
		</div>';
	}

	$menuLateral .= 

	'<a href="'.$url.'caja-ordenes"><i class="fas fa-box-open mr-0"></i>Pendientes</a>

	<a href="'.$url.'ordenes" class="mr-2"><i class="fas fa-clipboard-list mr-0"></i>Ordenes</a>

	<a class=" cerrarSesion"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>';

}elseif(isset($_SESSION['tecnico'])) {

	$datosPerfil = controladorPerfil::ctrDatosPerfil($_SESSION['tecnico'],"tecnico");

	if( ($datosPerfil['img_art'] == "imgRelleno.png") ){

		$menuLateral = 

		'<div class="bloqueFotoPerfil">
			<a href="'.$url.'perfil">
				<div class="fotoPerfil">
					<p class="primeraLetraNombre txtBlanco"></p>
				</div>
			</a>
		</div>';

	} else{

		$menuLateral = 

		'<div class="bloqueFotoPerfil">
			<a href="'.$url.'perfil">
				<img src="'.$url.'vistas/asset/images/tecnicos/'.$datosPerfil['img_art'].'" alt="'.$datosPerfil['nomb'].'" class="img-fluid fotoPerfil">
			</a>
		</div>';

	}

	$menuLateral .= 

	'<a href="'.$url.'ordenes"><i class="fas fa-clipboard-list mr-0"></i>Ordenes</a>

	<a href="'.$url.'trabajos" class="mr-2"><i class="fas fa-tooth mr-0"></i>Trabajos</a>

	<a class="cerrarSesion"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>';

} else {

	$menuLateral = 

	'<a href="#" class="inicioSesionLateral"><i class="fas fa-user"></i> &nbsp; Iniciar sesion</a>

	<a href="#" class="registroLateral"><i class="fab fa-wpforms"></i> &nbsp; Registrarse</a>';

}

 ?>

<div id="mySidenav" class="<?php echo $clase; ?>">
	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
	<?php echo $menuLateral; ?>

	<!--<a href="<?php echo $url; ?>perfil">Mi perfil</a>
	<?php 
		if(isset($_SESSION['tecnico'])){
			echo '<a href="'.$url.'trabajos">Mis trabajos</a>';
		}
	 ?>
	<a href="<?php echo $url; ?>ordenes">Mis ordenes</a>
	<a href="<?php echo $url; ?>historial-ordenes">Ordenes terminadas</a>
	<a class="cerrarSesion"><i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;Cerrar sesión</a>-->
</div>