<?php 

class ctrResultados{

	/*ALGORITMO PARA VERIFICAR QUE TIPO DE BUSQUEDA ESTÁ REALIZANDO EL USUARIO*/

	public static function compararBusqueda($txtBuscar){

		$ctrl = new mdlComparador();

		$paginacionOrd = "";
		$es = "";

		if(isset($_GET['min'],$_GET['max'])){
			$filtroPrecio = "and lista_precios_protesis.precio between ".$_GET['min']." and ".$_GET['max'];
			$paginacionPrecio = "&min=".$_GET['min']."&max=".$_GET['max'];
		} else {
			$filtroPrecio = "";
			$paginacionPrecio = "";
		}

		if($ctrl -> compararTrabajo($txtBuscar)){

			$es = "trabajo";
			if(isset($_GET['ord'])){
				$ord = $_GET['ord'];
				$paginacionOrd = "&ord=".$ord;
			} else {
				$ord = "order by trabajo.nomb";
			}

		} else {

			if($ctrl -> compararMaterial($txtBuscar)){
				$es = "material";
				if(isset($_GET['ord'])){
				$ord = $_GET['ord'];
				$paginacionOrd = "&ord=".$ord;
			} else {
				$ord = "order by material.nomb";
			}


			} else {

				if($ctrl -> compararOrtodoncia($txtBuscar)){
					$es = "ortodoncia";

					if(isset($_GET['ord'])){
						$ord = $_GET['ord'];
						$paginacionOrd = "&ord=".$ord;
					} else {
						$ord = "order by ortodoncia_prod.nomb";
					}


				} else {

					if($ctrl -> compararProtesis($txtBuscar)){
						$es = "protesis";

						if(isset($_GET['ord'])){
							$ord = $_GET['ord'];
							$paginacionOrd = "&ord=".$ord;
						} else {
							$ord = "order by protesis.nomb";
						}
						
					} else {

						$es = "otra cosa";

						//echo '<div class="alert alert-danger" role="alert" style="">No andes jugando con eso, niño travieso</div>';
					}
				}
			

			}
				
		}

		return $es;
		

	}

	/*BUSQUEDA DEL USUARIO, PASANDO QUE ES, EL TEXTO A BUSCAR Y LA PAGINA*/

	public static function realizarBusqueda($es,$txtBuscar,$pagina,$filtroSql,$filtroSqlOrto){

		switch ($es) {
        	case 'protesis':
				
				$res = mdlResultados::buscarProtesis($txtBuscar,$pagina,$filtroSql);

				return $res;

        		break;

        	case 'material':

				$res = mdlResultados::buscarMaterial($txtBuscar,$pagina,$filtroSql);

				return $res;

        		break;

        	case 'trabajo':

        		$res = mdlResultados::buscarTrabajo($txtBuscar,$pagina,$filtroSql);

        		return $res;

        		break;

        	case 'ortodoncia':

        		$res = mdlResultados::buscarOrtodoncia($txtBuscar,$pagina,$filtroSql);

        		return $res;

        		break;

        	case 'general':

        		$res = mdlResultados::busquedaGeneral($txtBuscar,$pagina,$filtroSql,$filtroSqlOrto);

        		return $res;

        		break;
        	
        	default:
        		
        		#

        		break;
        }

	}

	/*NUMERO DE RESULTADOS TOTALES PARA LA BUSQUEDA DEL USUARIO*/

	public static function resultadosTotales($es,$txtBuscar,$filtroSql,$filtroSqlOrto){

		switch ($es) {
        	case 'protesis':
				
				$res = mdlResultados::buscarProtesisNum($txtBuscar,$filtroSql);

				return $res;

        		break;

        	case 'material':

				$res = mdlResultados::buscarMaterialNum($txtBuscar,$filtroSql);

				return $res;

        		break;

        	case 'trabajo':

        		$res = mdlResultados::buscarTrabajoNum($txtBuscar,$filtroSql);

        		return $res;

        		break;

        	case 'ortodoncia':

        		$res = mdlResultados::buscarOrtodonciaNum($txtBuscar,$filtroSql);

        		return $res;

        		break;

        	case 'general':

        		$res = mdlResultados::NumeroResultadosBusquedaGeneral($txtBuscar,$filtroSql,$filtroSqlOrto);

        		return $res;

        		break;
        	
        	default:
        		
        		#

        		break;
        }

	}

	public static function palabraCabezote($txtBuscar){

		echo '<script>
			
			$(".buscarCabezote").val("'.$txtBuscar.'");

			var rutaBuscador2 = $("#busqueda a").attr("href");
			var rutaBuscador3 = rutaBuscador2+"/'.$txtBuscar.'";

			$("#busqueda a").attr("href",rutaBuscador3);

		</script>';

	}

}

 ?>