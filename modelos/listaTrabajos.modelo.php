<?php 	

require_once "conexion.php";

class modeloListaTrabajos{

	/*FUNCIONES PARA REALIZAR LA BUSQUEDA DEL TRABAJO*/

	public static function mdlBusquedaTrabajo($idUsuario,$busquedaTrabajo){

		$db = new Conexion();

		/**DESDE AQUI SE COMIENZA A SEPARAR LAS PALABRAS QUE EL USUARIO BUSCÓ**/

		$palabrasBusqueda = explode(" ", $busquedaTrabajo);

		$i = 0;

		$queryLikeProtesis = '(';
		$queryLikeOrtodoncia = '(';

		for($i = 0; $i < count($palabrasBusqueda); $i++) { //POR CADA PALABRA, AGREGAMOS UN CONDICIONAL LIKE DENTRO DE LA CONSULTA
				
			if( (strlen($palabrasBusqueda[$i]) >= 3) && ($i == 0) ){ //Sí la palabra que buscan tiene mas de 2 caracteres y es la primera, continuamos

				$queryLikeProtesis .= 

				"protesis.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				protesis.nomb LIKE '".$palabrasBusqueda[$i]."%'";

				$queryLikeOrtodoncia .= 

				"ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				ortodoncia_prod.nomb LIKE '".$palabrasBusqueda[$i]."%'";


			} elseif( strlen($palabrasBusqueda[$i]) >= 3 ){

				//LA DIFERENCIA RADICA EN QUE EMPIEZA AHORA POR LA CONDICIONAL "OR"

				$queryLikeProtesis .= 

				"OR protesis.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				protesis.nomb LIKE '".$palabrasBusqueda[$i]."%'";

				$queryLikeOrtodoncia .= 

				"OR ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				ortodoncia_prod.nomb LIKE '".$palabrasBusqueda[$i]."%'";

			} elseif($i==0) {

				$queryLikeProtesis .= 

				"protesis.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				protesis.nomb LIKE '".$palabrasBusqueda[$i]."%'";

				$queryLikeOrtodoncia .= 

				"ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				ortodoncia_prod.nomb LIKE '".$palabrasBusqueda[$i]."%'";

			}

		}

		$queryLikeProtesis .= ')';
		$queryLikeOrtodoncia .= ')';

		///***************************TERMINA EL QUERY DE BUSQUEDA POR PALABRA**********************************////

		$sql = $db -> query("SELECT protesis.nomb nombreProtesis, lista_precios_protesis.id_lista_precios_protesis idTrabajo, lista_precios_protesis.precio, lista_precios_protesis.dias_entrega dias, lista_precios_protesis.dias_terminado diasTerminado, lista_precios_protesis.porciento_inicial porcentaje, lista_precios_protesis.fecha_agregado fecha FROM lista_precios_protesis left join protesis on protesis.id_pro = lista_precios_protesis.id_pro where id_lab = $idUsuario and lista_precios_protesis.disponible = 1 and $queryLikeProtesis UNION SELECT ortodoncia_prod.nomb nombreProtesis, lista_precios_ortodoncia.id_ort idTrabajo, lista_precios_ortodoncia.precio, lista_precios_ortodoncia.dias_entrega dias, lista_precios_ortodoncia.dias_terminado diasTerminado, lista_precios_ortodoncia.porcentaje, lista_precios_ortodoncia.fecha_agregado fecha FROM lista_precios_ortodoncia left join ortodoncia_prod on lista_precios_ortodoncia.id_ort_prod = ortodoncia_prod.id_ort_prod WHERE lista_precios_ortodoncia.id_lab = $idUsuario and lista_precios_ortodoncia.disponible = 1 and $queryLikeOrtodoncia ORDER BY fecha DESC");

		$respuesta = ($db->rows($sql) >= 1) ? $sql->fetch_all(MYSQLI_ASSOC) : false;

		return $respuesta;

	}

	public static function mdlInfoLaboratorio($nombreLaboratorio,$calleLaboratorio,$idSepomex,$id){

		$db = new Conexion();

		if($db -> query("UPDATE laboratorio SET nomb = '$nombreLaboratorio', direc = '$calleLaboratorio', id_rep = $idSepomex, perfil_completo = 1 WHERE id_lab = $id")){

			return true;

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlLista($tipo){

		$db = new Conexion();

		switch ($tipo) {
			case 'protesis':
				$sql = $db -> query("SELECT id_pro FROM protesis order by nomb ASC");
				
				break;

			case 'ortodoncia':
				$sql = $db -> query("SELECT id_ort_prod FROM ortodoncia_prod order by nomb ASC");
				break;
			
			default:
				$sql = false;
				break;
		}


		$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);

		return $respuesta;

	}

	public static function mdlInfoTrabajoUrgente($idTrabajo,$queEs){

		$db = new Conexion();

		switch ($queEs) {

			case 'protesis':
				
				$sql = $db -> query("SELECT * FROM lista_precios_protesis_urg WHERE id_lista_precios_protesis = $idTrabajo");

				break;

			case 'ortodoncia':

				$sql = $db -> query("SELECT * FROM lista_precios_ortodoncia_urg WHERE id_ort = $idTrabajo");

				break;
			
			default:
				return false;
				break;
		}

		if($db -> rows($sql) >= 1){

			$respuesta = $sql -> fetch_array(MYSQLI_ASSOC);

		} else {

			$respuesta = false;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlInfoTrabajo($idTrabajo,$queEs){

		$db = new Conexion();

		switch ($queEs) {

			case 'protesis':
				
				$sql = $db -> query("SELECT * FROM lista_precios_protesis left join protesis on protesis.id_pro = lista_precios_protesis.id_pro WHERE lista_precios_protesis.id_lista_precios_protesis = $idTrabajo");

				break;

			case 'ortodoncia':

				$sql = $db -> query("SELECT * FROM lista_precios_ortodoncia left join ortodoncia_prod on lista_precios_ortodoncia.id_ort_prod = ortodoncia_prod.id_ort_prod WHERE lista_precios_ortodoncia.id_ort = $idTrabajo");

				break;
			
			default:
				return false;
				break;
		}

		if($db -> rows($sql) >= 1){

			$respuesta = $sql -> fetch_array(MYSQLI_ASSOC);

		} else {

			$respuesta = false;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlCalificaciones($idTrabajo,$queEs){

		$db = new Conexion();

		switch ($queEs) {
			case 'protesis':
				
				$sql = $db -> query("SELECT * FROM calificacion WHERE id_trab = $idTrabajo");

				break;

			case 'ortodoncia':

				$sql = $db -> query("SELECT * FROM calificacion WHERE id_orto = $idTrabajo");

				break;
			
			default:
				return false;
				break;
		}

		if($db -> rows($sql) >= 1){

			$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);

		} else {

			$respuesta = false;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlEliminarTrabajoLista($idLab,$idTrabajo,$queEs,$filtroTrabajo){

		$db = new Conexion();

		switch ($queEs) {
			case 'protesis':
				
				switch ($filtroTrabajo) {
					case 'ordinario':
					case 'total':

					//QUITAMOS DEL BUSCADOR EL TRABAJO ORDINARIO

						if( $db -> query("UPDATE lista_precios_protesis SET disponible = 0 WHERE id_lista_precios_protesis = $idTrabajo and id_lab = $idLab") ){

							if( $db -> query("UPDATE lista_precios_protesis_urg SET disponible = 0 WHERE id_lista_precios_protesis = $idTrabajo") ){

								return true;

							} else {

								return false;

							}

						} else {

							return fasle;

						}
						
						break;

					case 'urgente':

					//QUITAMOS DEL BUSCADOR EL TRABAJO URGENTE

						$sql = $db -> query("SELECT * FROM lista_precios_protesis WHERE id_lista_precios_protesis = $idTrabajo and id_lab = $idLab");

						if($db -> rows($sql) >= 1){

							if( $db -> query("UPDATE lista_precios_protesis_urg SET disponible = 0 WHERE id_lista_precios_protesis = $idTrabajo") ){

								return true;

							} else {

								return fasle;

							}

						} else {

							return false;

						}


						break;

					case 'noDisponible':

						//EN ESTE CASO TENEMOS QUE COLOCAR EL TRABAJO COMO DISPONIBLE

						$sql = $db -> query("SELECT * FROM lista_precios_protesis WHERE id_lista_precios_protesis = $idTrabajo and id_lab = $idLab");

						if($db -> rows($sql) >= 1){

							if( $db -> query("UPDATE lista_precios_protesis SET disponible = 1 WHERE id_lista_precios_protesis = $idTrabajo") ){

								return true;

							} else {

								return fasle;

							}

						} else {

							return false;

						}

						break;
					
					default:
						return false;
						break;
				}
				

				break;
			
			case 'ortodoncia':

				switch ($filtroTrabajo) {
					case 'ordinario':
					case 'total':

					//QUITAMOS DEL BUSCADOR EL TRABAJO ORDINARIO

						if( $db -> query("UPDATE lista_precios_ortodoncia SET disponible = 0 WHERE id_ort = $idTrabajo and id_lab = $idLab") ){

							if( $db -> query("UPDATE lista_precios_ortodoncia_urg SET disponible = 0 WHERE id_ort = $idTrabajo") ){

								return true;

							} else {

								return false;

							}

						} else {

							return fasle;

						}
						
						break;

					case 'urgente':

					//QUITAMOS DEL BUSCADOR EL TRABAJO URGENTE

						$sql = $db -> query("SELECT * FROM lista_precios_ortodoncia WHERE id_ort = $idTrabajo and id_lab = $idLab");

						if($db -> rows($sql) >= 1){

							if( $db -> query("UPDATE lista_precios_ortodoncia_urg SET disponible = 0 WHERE id_ort = $idTrabajo") ){

								return true;

							} else {

								return fasle;

							}

						} else {

							return false;

						}


						break;

					case 'noDisponible':

						//EN ESTE CASO TENEMOS QUE COLOCAR EL TRABAJO COMO DISPONIBLE

						$sql = $db -> query("SELECT * FROM lista_precios_ortodoncia WHERE id_ort = $idTrabajo and id_lab = $idLab");

						if($db -> rows($sql) >= 1){

							if( $db -> query("UPDATE lista_precios_ortodoncia SET disponible = 1 WHERE id_ort = $idTrabajo") ){

								return true;

							} else {

								return fasle;

							}

						} else {

							return false;

						}

						break;
					
					default:
						return false;
						break;
				}

				break;

			default:
				# code...
				break;
		}


		$db -> exit();

	}

	public static function mdlTrabajosFiltro($idUsuario,$filtro){

		$db = new Conexion();

		switch ($filtro) {

			case 'total':
			case 'ordinario':
				
				$sql = $db -> query("SELECT protesis.nomb nombreProtesis, lista_precios_protesis.id_lista_precios_protesis idTrabajo, lista_precios_protesis.precio, lista_precios_protesis.dias_entrega dias, lista_precios_protesis.dias_terminado diasTerminado, lista_precios_protesis.porciento_inicial porcentaje, lista_precios_protesis.fecha_agregado fecha FROM lista_precios_protesis left join protesis on protesis.id_pro = lista_precios_protesis.id_pro where id_lab = $idUsuario and lista_precios_protesis.disponible = 1 UNION SELECT ortodoncia_prod.nomb nombreProtesis, lista_precios_ortodoncia.id_ort idTrabajo, lista_precios_ortodoncia.precio, lista_precios_ortodoncia.dias_entrega dias, lista_precios_ortodoncia.dias_terminado diasTerminado, lista_precios_ortodoncia.porcentaje, lista_precios_ortodoncia.fecha_agregado fecha FROM lista_precios_ortodoncia left join ortodoncia_prod on lista_precios_ortodoncia.id_ort_prod = ortodoncia_prod.id_ort_prod WHERE lista_precios_ortodoncia.id_lab = $idUsuario and lista_precios_ortodoncia.disponible = 1 ORDER BY fecha DESC");

				break;

			case 'urgente':

				$sql = $db -> query("SELECT protesis.nomb nombreProtesis, lista_precios_protesis_urg.id_lista_precios_protesis idTrabajo, lista_precios_protesis_urg.precio, lista_precios_protesis_urg.dias_entrega diasTerminado, lista_precios_protesis_urg.fecha_agregado fecha FROM (lista_precios_protesis_urg left join lista_precios_protesis on lista_precios_protesis_urg.id_lista_precios_protesis = lista_precios_protesis.id_lista_precios_protesis) left join protesis on protesis.id_pro = lista_precios_protesis.id_pro WHERE lista_precios_protesis.id_lab = $idUsuario and lista_precios_protesis_urg.disponible = 1 UNION SELECT ortodoncia_prod.nomb nombreProtesis, lista_precios_ortodoncia_urg.id_ort idTrabajo, lista_precios_ortodoncia_urg.precio, lista_precios_ortodoncia_urg.dias_terminado diasTerminado, lista_precios_ortodoncia_urg.fecha_agregado fecha FROM (lista_precios_ortodoncia_urg left join lista_precios_ortodoncia on lista_precios_ortodoncia_urg.id_ort = lista_precios_ortodoncia.id_ort) left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = lista_precios_ortodoncia.id_ort_prod WHERE lista_precios_ortodoncia.id_lab = $idUsuario and lista_precios_ortodoncia_urg.disponible = 1 ORDER BY fecha DESC");

				break;

			case 'noDisponible':

				$sql = $db -> query("SELECT protesis.nomb nombreProtesis, lista_precios_protesis.id_lista_precios_protesis idTrabajo, lista_precios_protesis.precio, lista_precios_protesis.dias_entrega dias, lista_precios_protesis.dias_terminado diasTerminado, lista_precios_protesis.porciento_inicial porcentaje, lista_precios_protesis.fecha_agregado fecha FROM lista_precios_protesis left join protesis on protesis.id_pro = lista_precios_protesis.id_pro where id_lab = $idUsuario and lista_precios_protesis.disponible = 0 UNION SELECT ortodoncia_prod.nomb nombreProtesis, lista_precios_ortodoncia.id_ort idTrabajo, lista_precios_ortodoncia.precio, lista_precios_ortodoncia.dias_entrega dias, lista_precios_ortodoncia.dias_terminado diasTerminado, lista_precios_ortodoncia.porcentaje, lista_precios_ortodoncia.fecha_agregado fecha FROM lista_precios_ortodoncia left join ortodoncia_prod on lista_precios_ortodoncia.id_ort_prod = ortodoncia_prod.id_ort_prod WHERE lista_precios_ortodoncia.id_lab = $idUsuario and lista_precios_ortodoncia.disponible = 0 ORDER BY fecha DESC");
					
				break;

			default:
				# code...
				break;
		}

		$respuesta = $sql -> fetch_all(MYSQLI_ASSOC);

		return $respuesta;

		$db -> exit();

	}

	public static function mdlDesactivarTrabajoUrgente($tipoTrabajo,$idTrabajo){

		$db = new Conexion();

		$respuesta = false;

		switch ($tipoTrabajo) {

			case 'protesis':

				if($db -> query("UPDATE lista_precios_protesis_urg SET disponible = 0 WHERE id_lista_precios_protesis = $idTrabajo")){

					$respuesta = true;

				}
				
				break;
			
			case 'ortodoncia':

				if($db -> query("UPDATE lista_precios_ortodoncia_urg SET disponible = 0 WHERE id_ort = $idTrabajo")){

					$respuesta = true;

				}

				break;

			default:
				break;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlInsertarTrabajoUrgente($precioUrgente,$diasTerminadoUrgente,$tipoTrabajo,$idTrabajo){

		$db = new Conexion();

		$respuesta = false;

		switch ($tipoTrabajo) {

			case 'protesis':
				
				if($db -> query("INSERT INTO lista_precios_protesis_urg (id_lista_precios_protesis, precio, dias_entrega, disponible) VALUES ($idTrabajo, $precioUrgente, $diasTerminadoUrgente, 1)")){

					$respuesta = true;

				}

				break;

			case 'ortodoncia':

				if($db -> query("INSERT INTO lista_precios_ortodoncia_urg (id_ort, precio, dias_terminado, disponible) VALUES ($idTrabajo, $precioUrgente, $diasTerminadoUrgente, 1)")){

					$respuesta = true;

				}

				break;
			
			default:
				break;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlActualizarTrabajoUrgente($precioUrgente,$diasTerminadoUrgente,$tipoTrabajo,$idTrabajo){

		$db = new Conexion();

		$respuesta = false;

		switch ($tipoTrabajo) {

			case 'protesis':
				
				if($db -> query("UPDATE lista_precios_protesis_urg SET precio = $precioUrgente, dias_entrega = $diasTerminadoUrgente, disponible = 1 WHERE id_lista_precios_protesis = $idTrabajo")){

					$respuesta = true;

				}

				break;
			
			case 'ortodoncia':

				if($db -> query("UPDATE lista_precios_ortodoncia_urg SET precio = $precioUrgente, dias_terminado = $diasTerminadoUrgente, disponible = 1 WHERE id_ort = $idTrabajo")){

					$respuesta = true;

				}

				break;

			default:

				break;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlActualizarTrabajo($precio,$porcentaje,$diasPrueba,$diasTerminado,$tipoTrabajo,$idTrabajo){

		$db = new Conexion();

		$respuesta = false;

		switch ($tipoTrabajo) {

			case 'protesis':

				if($db -> query("UPDATE lista_precios_protesis SET precio = $precio, dias_entrega = $diasPrueba, dias_terminado = $diasTerminado, porciento_inicial = $porcentaje WHERE id_lista_precios_protesis = $idTrabajo")){

					$respuesta = true;

				}

				break;
			
			case 'ortodoncia':

				if($db -> query("UPDATE lista_precios_ortodoncia SET precio = $precio, dias_entrega = $diasPrueba, dias_terminado = $diasTerminado, porcentaje = $porcentaje WHERE id_ort = $idTrabajo")){

					$respuesta = true;

				}

				break;

			default:
				
				break;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlExisteTrabajoUrgente($tipo,$idTrabajo,$idUsuario){

		$db = new Conexion();

		switch ($tipo) {

			case 'protesis':
				
				$sql = $db -> query("SELECT lista_precios_protesis_urg.* FROM lista_precios_protesis left join lista_precios_protesis_urg on lista_precios_protesis.id_lista_precios_protesis = lista_precios_protesis_urg.id_lista_precios_protesis WHERE lista_precios_protesis_urg.id_lista_precios_protesis = $idTrabajo and lista_precios_protesis.id_lab = $idUsuario");

				break;

			case 'ortodoncia':

				$sql = $db -> query("SELECT lista_precios_ortodoncia_urg.* FROM lista_precios_ortodoncia left join lista_precios_ortodoncia_urg on lista_precios_ortodoncia.id_ort = lista_precios_ortodoncia_urg.id_ort WHERE lista_precios_ortodoncia_urg.id_ort = $idTrabajo and lista_precios_ortodoncia.id_lab = $idUsuario");

				break;
			
		}

		if($db -> rows($sql) >= 1){

			return true;

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlObtenerInfoTrabajo($idUsuario,$queEs,$idTrabajo){

		$db = new Conexion();

		switch ($queEs) {

			case 'protesis':
				
				$sql = $db -> query("SELECT protesis.nomb, lista_precios_protesis.precio, lista_precios_protesis.dias_entrega diasEntrega, lista_precios_protesis.disponible, lista_precios_protesis.dias_terminado diasTerminado, lista_precios_protesis.porciento_inicial porcentaje, lista_precios_protesis_urg.precio precioUrgente, lista_precios_protesis_urg.dias_entrega diasEntregaUrgente, lista_precios_protesis_urg.disponible disponibleUrgente FROM (lista_precios_protesis left join lista_precios_protesis_urg on lista_precios_protesis.id_lista_precios_protesis = lista_precios_protesis_urg.id_lista_precios_protesis) left join protesis on protesis.id_pro = lista_precios_protesis.id_pro WHERE lista_precios_protesis.id_lab = $idUsuario and lista_precios_protesis.id_lista_precios_protesis = $idTrabajo");

				break;

			case 'ortodoncia':

				$sql = $db -> query("SELECT ortodoncia_prod.nomb, lista_precios_ortodoncia.precio, lista_precios_ortodoncia.dias_entrega diasEntrega, lista_precios_ortodoncia.disponible, lista_precios_ortodoncia.dias_terminado diasTerminado, lista_precios_ortodoncia.porcentaje, lista_precios_ortodoncia_urg.precio precioUrgente, lista_precios_ortodoncia_urg.dias_terminado diasEntregaUrgente, lista_precios_ortodoncia_urg.disponible disponibleUrgente FROM (lista_precios_ortodoncia left join lista_precios_ortodoncia_urg on lista_precios_ortodoncia.id_ort = lista_precios_ortodoncia_urg.id_ort) left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = lista_precios_ortodoncia.id_ort_prod WHERE lista_precios_ortodoncia.id_lab = $idUsuario and lista_precios_ortodoncia.id_ort = $idTrabajo");

				break;

		}

		$respuesta = $sql -> fetch_array(MYSQLI_ASSOC);

		return $respuesta;

		$db -> exit();


	}

	static public function mdlListaTrabajos($idUsuario,$base,$tope){

		$db = new Conexion();

		if( ($base == NULL) && ($tope == NULL) ){

			//SE UTILIZA union EN EL QUERY PARA HACER LA CONSULTA DE LAS PROTESIS Y OTRA CONSULTA DE LOS PRODUCTOS DE ORTO
			$sql = $db -> query("SELECT protesis.nomb nombreProtesis, lista_precios_protesis.id_lista_precios_protesis idTrabajo, lista_precios_protesis.precio, lista_precios_protesis.dias_entrega dias, lista_precios_protesis.dias_terminado diasTerminado, lista_precios_protesis.porciento_inicial porcentaje, lista_precios_protesis.fecha_agregado fecha FROM lista_precios_protesis left join protesis on protesis.id_pro = lista_precios_protesis.id_pro where id_lab = $idUsuario and disponible = 1 UNION SELECT ortodoncia_prod.nomb nombreProtesis, lista_precios_ortodoncia.id_ort idTrabajo, lista_precios_ortodoncia.precio, lista_precios_ortodoncia.dias_entrega dias, lista_precios_ortodoncia.dias_terminado diasTerminado, lista_precios_ortodoncia.porcentaje, lista_precios_ortodoncia.fecha_agregado fecha FROM lista_precios_ortodoncia left join ortodoncia_prod on lista_precios_ortodoncia.id_ort_prod = ortodoncia_prod.id_ort_prod WHERE lista_precios_ortodoncia.id_lab = $idUsuario and disponible = 1 ORDER BY fecha DESC");

		} else {

			//SE UTILIZA union EN EL QUERY PARA HACER LA CONSULTA DE LAS PROTESIS Y OTRA CONSULTA DE LOS PRODUCTOS DE ORTO
			$sql = $db -> query("SELECT protesis.nomb nombreProtesis, lista_precios_protesis.id_lista_precios_protesis idTrabajo, lista_precios_protesis.precio, lista_precios_protesis.dias_entrega dias, lista_precios_protesis.dias_terminado diasTerminado, lista_precios_protesis.porciento_inicial porcentaje, lista_precios_protesis.fecha_agregado fecha FROM lista_precios_protesis left join protesis on protesis.id_pro = lista_precios_protesis.id_pro where id_lab = $idUsuario and disponible = 1 UNION SELECT ortodoncia_prod.nomb nombreProtesis, lista_precios_ortodoncia.id_ort idTrabajo, lista_precios_ortodoncia.precio, lista_precios_ortodoncia.dias_entrega dias, lista_precios_ortodoncia.dias_terminado diasTerminado, lista_precios_ortodoncia.porcentaje, lista_precios_ortodoncia.fecha_agregado fecha FROM lista_precios_ortodoncia left join ortodoncia_prod on lista_precios_ortodoncia.id_ort_prod = ortodoncia_prod.id_ort_prod WHERE lista_precios_ortodoncia.id_lab = $idUsuario and disponible = 1 ORDER BY fecha DESC limit $base, $tope");

		}

		$res = $sql -> fetch_all(MYSQLI_ASSOC);

		return $res;

		$db -> exit();

	}

	public static function mdlTrabajoUrgentePorNombre($idTrabajo,$nombre){

		$db = new Conexion();

		$sqlProtesis = $db ->query ("SELECT * FROM protesis WHERE nomb = '$nombre'");

		$sqlOrto = $db -> query("SELECT * FROM ortodoncia_prod WHERE nomb = '$nombre'");

		if($db->rows($sqlProtesis) >= 1){

			$sqlUrg = $db -> query ("SELECT * FROM lista_precios_protesis_urg WHERE id_lista_precios_protesis = $idTrabajo and disponible = 1");

		}elseif($db->rows($sqlOrto) >= 1){

			$sqlUrg = $db -> query ("SELECT id_ort, precio, dias_terminado dias_entrega, disponible, fecha_agregado FROM lista_precios_ortodoncia_urg WHERE id_ort = $idTrabajo and disponible = 1");

		}

		if($db -> rows($sqlUrg) >= 1){

			$respuesta = $sqlUrg -> fetch_array(MYSQLI_ASSOC);

		} else {

			$respuesta = false;

		}


		return $respuesta;

		$db -> exit();

	}

	public static function mdlVerificarExistenciaPromocion($idTrabajo,$es){

		$db = new Conexion();

		switch ($es) {

			case 'ortodoncia':

				$sqlPromocion = $db -> query("SELECT * FROM prom_trab WHERE id_ort = $idTrabajo and pagado = 0");
				
				break;
			
			case 'protesis':

				$sqlPromocion = $db -> query("SELECT * FROM prom_trab WHERE id_lista_precios_protesis = $idTrabajo and pagado = 0");
				
				break;

			default:
				# code...
				break;
				
		}

		if($db -> rows($sqlPromocion) >= 1){

			$respuesta = true;

		} else {

			$respuesta = false;

		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlTipoTrabajo($idTrabajo,$nombre){

		$db = new Conexion();

		$sqlProtesis = $db ->query ("SELECT * FROM protesis WHERE nomb = '$nombre'");

		$sqlOrto = $db -> query("SELECT * FROM ortodoncia_prod WHERE nomb = '$nombre'");

		if($db->rows($sqlProtesis) >= 1){

			$respuesta = "protesis";	

		}elseif($db->rows($sqlOrto) >= 1){

			$respuesta = "ortodoncia";

		}

		return $respuesta;

		$db -> exit();

	}


	static public function mdlObtenerTrabajo($tipo,$id){

		$db = new Conexion();

		switch ($tipo) {
			case 'ortodoncia':
					
				$sql = $db -> query ("SELECT * FROM ortodoncia_prod WHERE id_ort_prod = $id order by nomb ASC");

				break;

			case 'protesis':
				
				$sql = $db -> query ("SELECT * FROM protesis WHERE id_pro = $id order by nomb ASC");

				break;
			
			default:
				# code...
				break;
		}


		$res = $sql -> fetch_array(MYSQLI_ASSOC);

		return $res;

		$db -> exit();

	}

	static public function mdlAgregarTrabajo($tipo, $idLab, $precio, $idTrabajo, $prueba, $terminado, $porcentaje){

		$db = new Conexion();
		
		$sql = $db -> query("SELECT * FROM laboratorio WHERE id_lab = $idLab");

		$disponible = 1;

		$datos = $sql -> fetch_all(MYSQLI_ASSOC);

		foreach ($datos as $key => $value) { //VERIFICAMOS QUE TENGA LOS DATOS MINIMOS PARA QUE LOS TRABAJOS APAREZCAN EN EL BUSCADOR
			
			if( $value['nomb'] == NULL ||
				$value['direc'] == NULL ||
				$value['id_rep'] == NULL ){

				$disponible = 0;

			}

		}

		switch ($tipo) {
			case 'protesis':
				
				if($db->query("INSERT INTO lista_precios_protesis (id_pro, id_lab, precio, dias_entrega, disponible, dias_terminado, porciento_inicial) VALUES ($idTrabajo, $idLab, $precio, $prueba, $disponible, $terminado, $porcentaje)")){

					return true;

				} else {

					return false;

				}
				
				break;
			
			case 'ortodoncia':

				if($db->query("INSERT INTO lista_precios_ortodoncia (id_lab, precio, id_ort_prod, dias_entrega, dias_terminado, porcentaje, disponible) VALUES ($idLab, $precio, $idTrabajo, $prueba, $terminado, $porcentaje, $disponible)")){

					return true;

				} else {

					return false;

				}

				break;

			default:
				return false;
				break;
		}


		$db -> exit();

	}

	//METODO PARA AGREGAR TRABAJOS DESDE EL MODULO DE LA LISTA DE PRECIOS

	public static function mdlAgregarTrabajoUrgente($tipo,$terminadoUrg,$porcentajeUrg,$idLab,$idTrabajo){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM laboratorio WHERE id_lab = $idLab");

		$disponible = 1;

		/*$datos = $sql -> fetch_all(MYSQLI_ASSOC);

		foreach ($datos as $key => $value) { //VERIFICAMOS QUE TENGA LOS DATOS MINIMOS PARA QUE LOS TRABAJOS APAREZCAN EN EL BUSCADOR
			
			if( $value['nomb'] == NULL ||
				$value['direc'] == NULL ||
				$value['id_rep'] == NULL ){

				$disponible = 0;

			}

		}*/

		switch ($tipo) {
			case 'protesis':
				
				$sql2 = $db -> query("SELECT * FROM lista_precios_protesis WHERE id_lab = $idLab and id_pro = $idTrabajo");

				foreach ($sql2 as $key => $value) {
					
					$costoExtra = $value['precio'] * $porcentajeUrg;

					$precioUrgente = $value['precio'] + $costoExtra;

					$idDisp = $value['id_lista_precios_protesis'];

					if($db -> query("INSERT INTO lista_precios_protesis_urg (id_lista_precios_protesis, precio, dias_entrega, disponible) VALUES ($idDisp, $precioUrgente, $terminadoUrg, $disponible)")){

						return true;

					} else {

						return false;

					}

				}
				
				break;
			
			case 'ortodoncia':

				$sql2 = $db -> query("SELECT * FROM lista_precios_ortodoncia WHERE id_lab = $idLab and id_ort_prod = $idTrabajo");

				foreach ($sql2 as $key => $value) {
					
					$costoExtra = $value['precio'] * $porcentajeUrg;

					$precioUrgente = $value['precio'] + $costoExtra;

					$idDisp = $value['id_ort'];

					if($db -> query("INSERT INTO lista_precios_ortodoncia_urg (id_ort, precio, dias_terminado, disponible) VALUES ($idDisp, $precioUrgente, $terminadoUrg, $disponible)")){

						return true;

					} else {

						return false;

					}

				}

				break;

			default:
				return false;
				break;
		}

		

		$db -> exit();


	}

	public static function mdlExistenciaTrabajo($tipo,$idTrabajo,$idLab){

		$db = new Conexion();

		switch ($tipo) {
			case 'ortodoncia':
				
				$sql = $db -> query("SELECT * FROM lista_precios_ortodoncia WHERE id_ort_prod = $idTrabajo and id_lab = $idLab");

				break;
			case 'protesis':

				$sql = $db -> query("SELECT * FROM lista_precios_protesis WHERE id_pro = $idTrabajo and id_lab = $idLab");
				
				break;
				
			default:
				$sql = 0;
				break;
		}


		if($db->rows($sql) >= 1){

			return true;

		} else {

			return false;

		}

		$db -> exit();

	}

	public static function mdlPerfilCompleto($idLab){

		$db = new Conexion();

		$sql = $db -> query("SELECT * FROM laboratorio WHERE id_lab = $idLab");

		$completo = 1;

		$datos = $sql -> fetch_all(MYSQLI_ASSOC);

		foreach ($datos as $key => $value) {
			
			if( $value['nomb'] == NULL || 
				$value['direc'] == NULL ||
				$value['id_rep'] == NULL){

				$completo = 0;

			}

		}

		return $completo;

		$db -> exit();

	}

	

}


 ?>