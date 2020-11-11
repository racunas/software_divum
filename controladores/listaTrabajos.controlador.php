<?php 	

class controladorListaTrabajos{

	public static function ctrPerfilCompleto($idLab){
		$respuesta = modeloListaTrabajos::mdlPerfilCompleto($idLab);
		return $respuesta;
	}

	public static function ctrLista($tipo){

		$respuesta = modeloListaTrabajos::mdlLista($tipo);

		return $respuesta;

	}

	public static function ctrCalificaciones($idTrabajo,$queEs){

		$trabajo = modeloListaTrabajos::mdlCalificaciones($idTrabajo,$queEs);

		return $trabajo;

	}

	public static function obtenerInfoTrabajo($idUsuario,$queEs,$idTrabajo){

		$trabajo = modeloListaTrabajos::mdlObtenerInfoTrabajo($idUsuario,$queEs,$idTrabajo);

		return $trabajo;

	}

	public static function obtenerListaTrabajos($idUsuario,$base,$tope){

		$trabajo = modeloListaTrabajos::mdlListaTrabajos($idUsuario,$base,$tope);

		return $trabajo;

	}

	public static function obtenerTrabajoUrgentePorNombre($idTrabajo,$nombre){

		$trabajo = modeloListaTrabajos::mdlTrabajoUrgentePorNombre($idTrabajo,$nombre);

		return $trabajo;
	}

	public static function verificarExistenciaPromocion($idTrabajo,$es){

		//REGRESA true O false DEPENDIENDO SI EXISTE LA PROMOCION O NO
		$existe = modeloListaTrabajos::mdlVerificarExistenciaPromocion($idTrabajo,$es);

		return $existe;

	}

	public static function tipoTrabajo($idTrabajo,$nombre){

		//REGRESA "protesis" U "ortodoncia" DEPENDIENDO QUE TIPO DE TRABAJO SEA
		$tipoTrabajo = modeloListaTrabajos::mdlTipoTrabajo($idTrabajo,$nombre);

		return $tipoTrabajo;

	}

	public static function obtenerTrabajo($tipo,$id){
		
		$trabajo = modeloListaTrabajos::mdlObtenerTrabajo($tipo,$id);

		return $trabajo;

	}

	public static function obtenerPerfilDisponible($id){
		
		$trabajo = modeloListaTrabajos::mdlPerfilCompleto($id);

		return $trabajo;

	}

	public static function ctrRegistroLista($idLab){

		$perfilCompleto = modeloListaTrabajos::mdlPerfilCompleto($idLab); //PERFIL COMPLETO TRUE O FALSE

		if($perfilCompleto){ //SÍ YA COMPLETÓ LA INFO DE SU LABORATORIO, NO MOSTRAR EL MÓDULO PARA QUE LLENE LOS DATOS

			if(isset($_POST['tipo'])){//SI YA ESCOGIO SI HACE ORTO O PROTESIS, MOSTRAMOS LA LISTA DE TRABAJOS DE ESE TIPO

				include "vistas/modulos/listaTrabajos.php";

			} elseif(isset($_POST['lista'],$_POST['tipoTrabajo'])){ //SÍ YA SELECCIONÓ LOS TRABAJOS DE LA LISTA, AHORA MOSTRAR QUE QUEREMOS SUS PRECIOS

			    include "vistas/modulos/listaTrabajoPrecios.php";
			    
			} else if (isset($_POST['precio'], $_POST['listaSeleccionada'], $_POST['tipoTrabajo'])){ //SÍ YA SELECCIONÓ LA LISTA Y DIO LOS PRECIOS, CONTINUAMOS CON LA ÚLTIMA VERIFICACIÓN Y PASO DEL FORMULARIO

				$length = count($_POST['precio']); //NUMERO TOTAL DE TRABAJOS SELECCIONADOS Y DE PRECIOS

				$arrayPrecio = $_POST['precio'];
				$arrayLista = $_POST['listaSeleccionada'];
				
				$todosLosPrecios = true;
				$todaLaLista = true;

				for ($i = 0; $i < $length; $i++){
				    
				    if(!$arrayPrecio[$i] != ""){
				      $todosLosPrecios = false;
				    }

				    if(!$arrayLista[$i] != ""){
				      $todaLaLista = false;
				    }

				}

				if( $todosLosPrecios && $todaLaLista ){ //SÍ EL NÚMERO DE PRECIOS CORRESPONDEN AL NÚMERO DE TRABAJOS SELECCIONADOS, MOSTRAMOS EL ÚLTIMO MODULO DEL FORMULARIO

					//HAY QUE ESPERAR A QUE ENVÍE EL FORMULARIO DE DATOS GENERALES DE LOS TRABAJOS POR AJAX

					echo '<script>

						var listaPrecios =';
				
					echo json_encode($arrayPrecio);

					echo ';var listaUsuario =';

					echo json_encode($arrayLista);

					echo ';</script>';

				  	include "vistas/modulos/listaTrabajosGeneral.php";

				} else {

				  	include "vistas/modulos/listaTrabajosTipo.php";              

				}

			} else {

				include "vistas/modulos/listaTrabajosTipo.php";              

			}


		} else {

			//SÍ EL PERFIL NO ESTÁ COMPLETO, MOSTRAR EL MODULO PARA QUE NOS BRINDE LA INFORMACIÓN MINIMA PARA PROMOCIONAR SU LABORATORIO

			include "vistas/modulos/listaTrabajosInfoAdicional.php";

		}


	}

	public static function verificarExistenciaTrabajo($tipo,$idTrabajo,$idLab){

		$existencia = modeloListaTrabajos::mdlExistenciaTrabajo($tipo,$idTrabajo,$idLab);

		return $existencia;

	}

}


 ?>