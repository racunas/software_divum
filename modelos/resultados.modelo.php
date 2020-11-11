<?php

class mdlResultados{

	//******************NUEVO METODO PARA BUSCAR**************************//

	public static function NumeroResultadosBusquedaGeneral($txtBuscar,$filtroSql,$filtroSqlOrto){

		$db = new Conexion();



		/**DESDE AQUI SE COMIENZA A SEPARAR LAS PALABRAS QUE EL USUARIO BUSCÓ**/

		$palabrasBusqueda = explode(" ", $txtBuscar);

		$i = 0;

		$queryLikeProtesis = '(';
		$queryLikeOrtodoncia = '(';

		for($i = 0; $i < count($palabrasBusqueda); $i++) { //POR CADA PALABRA, AGREGAMOS UN CONDICIONAL LIKE DENTRO DE LA CONSULTA
				
			if( (strlen($palabrasBusqueda[$i]) >= 3) && ($i == 0) ){ //Sí la palabra que buscan tiene mas de 2 caracteres y es la primera, continuamos

				$queryLikeProtesis .= 

				"protesis.nomb LIKE '".$palabrasBusqueda[$i]."%' or 
				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."' or 
				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."%' or 
				laboratorio.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				material.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				material.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				material.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				trabajo.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				trabajo.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				trabajo.nomb LIKE '%".$palabrasBusqueda[$i]."%'";

				$queryLikeOrtodoncia .= 

				"ortodoncia_prod.nomb LIKE '".$palabrasBusqueda[$i]."%' or 
				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."' or 
				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				laboratorio.nomb LIKE '".$palabrasBusqueda[$i]."%' or 
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."' or 
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."%'";

			} elseif( strlen($palabrasBusqueda[$i]) >= 3 ){

				$queryLikeProtesis .=  //LA DIFERENCIA RADICA EN QUE EMPIEZA AHORA POR LA CONDICIONAL "OR"

				"OR protesis.nomb LIKE '".$palabrasBusqueda[$i]."%' or 
				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."' or 
				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."%' or 
				laboratorio.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				material.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				material.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				material.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				trabajo.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				trabajo.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				trabajo.nomb LIKE '%".$palabrasBusqueda[$i]."%'";

				$queryLikeOrtodoncia .= 

				"OR ortodoncia_prod.nomb LIKE '".$palabrasBusqueda[$i]."%' or 
				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."' or 
				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				laboratorio.nomb LIKE '".$palabrasBusqueda[$i]."%' or 
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."' or 
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."%'";

			} elseif($i==0) {

				$queryLikeProtesis .=  //LA DIFERENCIA RADICA EN QUE EMPIEZA AHORA POR LA CONDICIONAL "OR"

				"protesis.nomb LIKE '".$palabrasBusqueda[$i]."%'";

				$queryLikeOrtodoncia .= 

				"ortodoncia_prod.nomb LIKE '".$palabrasBusqueda[$i]."%'";

			}

		}

		$queryLikeProtesis .= ')';
		$queryLikeOrtodoncia .= ')';

		/*SE REALIZA LA CONSULTA PARA LA BUSQUEDA*/

		$sql = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, protesis.nomb trabajo, lista_precios_protesis.precio precio, lista_precios_protesis.dias_entrega tiempoEntrega, material.nomb material, laboratorio.img_art imagen, lista_precios_protesis.id_lista_precios_protesis id, laboratorio.id_rep from trabajo, laboratorio, sepomex, protesis left join lista_precios_protesis on lista_precios_protesis.id_pro = protesis.id_pro left join material on material.id_mat = protesis.id_mat where 

			$queryLikeProtesis

			and lista_precios_protesis.id_lab = laboratorio.id_lab and trabajo.id_trab = protesis.id_trab and lista_precios_protesis.disponible = 1 and laboratorio.id_rep = sepomex.id $filtroSql

			UNION

			SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, ortodoncia_prod.nomb trabajo, lista_precios_ortodoncia.precio, lista_precios_ortodoncia.dias_entrega tiempoEntrega, NULL material, laboratorio.img_art imagen, lista_precios_ortodoncia.id_ort idOrto, laboratorio.id_rep FROM lista_precios_ortodoncia left join laboratorio on laboratorio.id_lab = lista_precios_ortodoncia.id_lab left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = lista_precios_ortodoncia.id_ort_prod, sepomex WHERE 

			$queryLikeOrtodoncia

			and lista_precios_ortodoncia.disponible = 1 and laboratorio.id_rep = sepomex.id");

		///***************************TERMINA EL QUERY DE BUSQUEDA POR PALABRA**********************************////

		if($db -> rows($sql) >= 1){

			$resultado = $db->rows($sql);

		} else {

			$resultado = 0;

		}

		return $resultado;

		$db -> exit();

	}

	public static function busquedaGeneral($txtBuscar,$pag,$filtroSql,$filtroSqlOrto){

		$db = new Conexion();

		$palabrasBusqueda = explode(" ", $txtBuscar);

		$i = 0;

		$queryLikeProtesis = '';
		$queryLikeOrtodoncia = '';

		for($i = 0; $i < count($palabrasBusqueda); $i++) { //POR CADA PALABRA, AGREGAMOS UN CONDICIONAL LIKE DENTRO DE LA CONSULTA
				
			if( (strlen($palabrasBusqueda[$i]) >= 3) && ($i == 0) ){ //Sí la palabra que buscan tiene mas de 2 caracteres y es la primera, continuamos

				$queryLikeProtesis .= 

				"protesis.nomb LIKE '".$palabrasBusqueda[$i]."%' or 
				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."' or 
				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."%' or 
				laboratorio.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				material.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				material.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				material.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				trabajo.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				trabajo.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				trabajo.nomb LIKE '%".$palabrasBusqueda[$i]."%'";

				$queryLikeOrtodoncia .= 

				"ortodoncia_prod.nomb LIKE '".$palabrasBusqueda[$i]."%' or 
				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."' or 
				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				laboratorio.nomb LIKE '".$palabrasBusqueda[$i]."%' or 
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."' or 
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."%'";

			} elseif( strlen($palabrasBusqueda[$i]) >= 3 ){

				$queryLikeProtesis .=  //LA DIFERENCIA RADICA EN QUE EMPIEZA AHORA POR LA CONDICIONAL "OR"

				"OR protesis.nomb LIKE '".$palabrasBusqueda[$i]."%' or 
				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."' or 
				protesis.nomb LIKE '%".$palabrasBusqueda[$i]."%' or 
				laboratorio.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				material.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				material.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				material.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				trabajo.nomb LIKE '".$palabrasBusqueda[$i]."%' or
				trabajo.nomb LIKE '%".$palabrasBusqueda[$i]."' or
				trabajo.nomb LIKE '%".$palabrasBusqueda[$i]."%'";

				$queryLikeOrtodoncia .= 

				"OR ortodoncia_prod.nomb LIKE '".$palabrasBusqueda[$i]."%' or 
				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."' or 
				ortodoncia_prod.nomb LIKE '%".$palabrasBusqueda[$i]."%' or
				laboratorio.nomb LIKE '".$palabrasBusqueda[$i]."%' or 
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."' or 
				laboratorio.nomb LIKE '%".$palabrasBusqueda[$i]."%'";

			} elseif($i==0) {

				$queryLikeProtesis .=  //LA DIFERENCIA RADICA EN QUE EMPIEZA AHORA POR LA CONDICIONAL "OR"

				"protesis.nomb LIKE '".$palabrasBusqueda[$i]."%'";

				$queryLikeOrtodoncia .= 

				"ortodoncia_prod.nomb LIKE '".$palabrasBusqueda[$i]."%'";

			}

		}


		/*SE REALIZA LA CONSULTA PARA LA BUSQUEDA*/

		$sql1 = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, protesis.nomb trabajo, lista_precios_protesis.precio precio, lista_precios_protesis.dias_entrega tiempoEntrega, material.nomb material, laboratorio.img_art imagen, lista_precios_protesis.id_lista_precios_protesis id, laboratorio.id_rep from trabajo, laboratorio, sepomex, protesis left join lista_precios_protesis on lista_precios_protesis.id_pro = protesis.id_pro left join material on material.id_mat = protesis.id_mat where 

			($queryLikeProtesis)

			and lista_precios_protesis.id_lab = laboratorio.id_lab and trabajo.id_trab = protesis.id_trab and lista_precios_protesis.disponible = 1 and laboratorio.id_rep = sepomex.id $filtroSql

			UNION

			SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, ortodoncia_prod.nomb trabajo, lista_precios_ortodoncia.precio, lista_precios_ortodoncia.dias_entrega tiempoEntrega, NULL material, laboratorio.img_art imagen, lista_precios_ortodoncia.id_ort idOrto, laboratorio.id_rep FROM lista_precios_ortodoncia left join laboratorio on laboratorio.id_lab = lista_precios_ortodoncia.id_lab left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = lista_precios_ortodoncia.id_ort_prod, sepomex WHERE 

			($queryLikeOrtodoncia)

			and lista_precios_ortodoncia.disponible = 1 and laboratorio.id_rep = sepomex.id");

		if($db->rows($sql1) >= 1){

			$numResultados = $db->rows($sql1);
		
			//LIMITE DE BUSQUEDA
			$tamanoPagina = 15;

			//examino la página a mostrar y el inicio del registro a mostrar
			$pagina = 0;

			if (!isset($pag)) {
			   $inicio = 0;
			   $pagina = 1;
			   
			}
			else {
				if($pag == 1){
					$inicio = 0;
			  		$pagina = 1;
				} else {
					$pagina = $pag;
			   		$inicio = ($pagina - 1) * $tamanoPagina;
				}
				
			}

			//calculo el total de páginas
			$total_paginas = ceil($numResultados / $tamanoPagina);

			$sql2 = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, protesis.nomb trabajo, lista_precios_protesis.precio precio, lista_precios_protesis.dias_entrega tiempoEntrega, material.nomb material, laboratorio.img_art imagen, lista_precios_protesis.id_lista_precios_protesis id, laboratorio.id_rep from trabajo, laboratorio, sepomex, protesis left join lista_precios_protesis on lista_precios_protesis.id_pro = protesis.id_pro left join material on material.id_mat = protesis.id_mat where 

				($queryLikeProtesis)

				and lista_precios_protesis.id_lab = laboratorio.id_lab and trabajo.id_trab = protesis.id_trab and lista_precios_protesis.disponible = 1 and laboratorio.id_rep = sepomex.id $filtroSql

				UNION

				SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, ortodoncia_prod.nomb trabajo, lista_precios_ortodoncia.precio, lista_precios_ortodoncia.dias_entrega tiempoEntrega, NULL material, laboratorio.img_art imagen, lista_precios_ortodoncia.id_ort idOrto, laboratorio.id_rep FROM lista_precios_ortodoncia left join laboratorio on laboratorio.id_lab = lista_precios_ortodoncia.id_lab left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = lista_precios_ortodoncia.id_ort_prod, sepomex WHERE 

				($queryLikeOrtodoncia)

				and lista_precios_ortodoncia.disponible = 1 and laboratorio.id_rep = sepomex.id $filtroSql ORDER BY trabajo limit $inicio, $tamanoPagina");

			if($db->rows($sql2)>=1){

				$resultado = $sql2 -> fetch_all(MYSQLI_ASSOC);

			} else {

				$resultado = null;

			}

		} else {

			$resultado = null;

		}


		return $resultado;

		$db -> exit();

	}

	//********************************************************************//

	public static function buscarProtesis($txt,$pag,$filtroSql){

		$db = new Conexion();

		$sql1 = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, protesis.nomb trabajo, lista_precios_protesis.precio precio, lista_precios_protesis.dias_terminado tiempoEntrega, material.nomb material, laboratorio.img_art imagen, laboratorio.id_rep FROM laboratorio, material, protesis left join lista_precios_protesis on lista_precios_protesis.id_pro = protesis.id_pro, sepomex WHERE protesis.nomb LIKE '%$txt%' and lista_precios_protesis.id_lab = laboratorio.id_lab and material.id_mat = protesis.id_mat and lista_precios_protesis.disponible = 1 and laboratorio.id_rep = sepomex.id ".$filtroSql." ORDER BY protesis.nomb");

		$numResultados = $db->rows($sql1);
	
		//LIMITE DE BUSQUEDA
		$tamanoPagina = 15;

		//examino la página a mostrar y el inicio del registro a mostrar
		$pagina = 0;

		if (!isset($pag)) {
		   $inicio = 0;
		   $pagina = 1;
		   
		}
		else {
			if($pag == 1){
				$inicio = 0;
		  		$pagina = 1;
			} else {
				$pagina = $pag;
		   		$inicio = ($pagina - 1) * $tamanoPagina;
			}
			
		}

		//calculo el total de páginas
		$total_paginas = ceil($numResultados / $tamanoPagina);

		$sql2 = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, protesis.nomb trabajo, lista_precios_protesis.precio precio, lista_precios_protesis.dias_entrega tiempoEntrega, material.nomb material, laboratorio.img_art imagen, lista_precios_protesis.id_lista_precios_protesis id, laboratorio.id_rep FROM laboratorio, material, protesis left join lista_precios_protesis on lista_precios_protesis.id_pro = protesis.id_pro, sepomex WHERE protesis.nomb LIKE '%$txt%' and lista_precios_protesis.id_lab = laboratorio.id_lab and material.id_mat = protesis.id_mat and lista_precios_protesis.disponible = 1 and laboratorio.id_rep = sepomex.id ".$filtroSql." ORDER BY protesis.nomb limit $inicio, $tamanoPagina");

		$resultado = ($db->rows($sql2) >= 1) ? $sql2 -> fetch_all(MYSQLI_ASSOC) : 0;

		return $resultado;

		$db -> exit();

	}

	public static function buscarMaterial($txt,$pag,$filtroSql){

		$db = new Conexion();

		$sql1 = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, protesis.nomb trabajo, lista_precios_protesis.precio precio, lista_precios_protesis.dias_terminado tiempoEntrega, material.nomb material, laboratorio.img_art imagen, laboratorio.id_rep from laboratorio, sepomex, protesis left join lista_precios_protesis on lista_precios_protesis.id_pro = protesis.id_pro, material where material.nomb LIKE '%$txt%' and lista_precios_protesis.id_lab = laboratorio.id_lab and material.id_mat = protesis.id_mat and lista_precios_protesis.disponible = 1 and laboratorio.id_rep = sepomex.id ".$filtroSql." ORDER BY material.nomb");

		$numResultados = $db->rows($sql1);
	
		//LIMITE DE BUSQUEDA
		$tamanoPagina = 15;

		//examino la página a mostrar y el inicio del registro a mostrar
		$pagina = 0;

		if (!isset($pag)) {
		   $inicio = 0;
		   $pagina = 1;
		   
		}
		else {
			if($pag == 1){
				$inicio = 0;
		  		$pagina = 1;
			} else {
				$pagina = $pag;
		   		$inicio = ($pagina - 1) * $tamanoPagina;
			}
			
		}

		//calculo el total de páginas
		$total_paginas = ceil($numResultados / $tamanoPagina);

		$sql2 = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, protesis.nomb trabajo, lista_precios_protesis.precio precio, lista_precios_protesis.dias_entrega tiempoEntrega, material.nomb material, laboratorio.img_art imagen, lista_precios_protesis.id_lista_precios_protesis id, laboratorio.id_rep from laboratorio, sepomex, protesis left join lista_precios_protesis on lista_precios_protesis.id_pro = protesis.id_pro, material where material.nomb LIKE '%$txt%' and lista_precios_protesis.id_lab = laboratorio.id_lab and material.id_mat = protesis.id_mat and lista_precios_protesis.disponible = 1 and laboratorio.id_rep = sepomex.id ".$filtroSql." ORDER BY material.nomb limit $inicio, $tamanoPagina");

		$resultado = ($db->rows($sql2) >= 1) ? $sql2 -> fetch_all(MYSQLI_ASSOC) : 0;

		return $resultado;

		$db -> exit();

	}

	public static function buscarTrabajo($txt,$pag,$filtroSql){

		$db = new Conexion();

		$sql1 = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, protesis.nomb trabajo, lista_precios_protesis.precio precio, lista_precios_protesis.dias_terminado tiempoEntrega, material.nomb material, laboratorio.img_art imagen, laboratorio.id_rep from trabajo, sepomex, laboratorio, protesis left join lista_precios_protesis on lista_precios_protesis.id_pro = protesis.id_pro left join material on material.id_mat = protesis.id_mat where trabajo.nomb LIKE '%$txt%' and lista_precios_protesis.id_lab = laboratorio.id_lab and trabajo.id_trab = protesis.id_trab and lista_precios_protesis.disponible = 1 and laboratorio.id_rep = sepomex.id ".$filtroSql." ORDER BY trabajo.nomb");

		$numResultados = $db->rows($sql1);
	
		//LIMITE DE BUSQUEDA
		$tamanoPagina = 15;

		//examino la página a mostrar y el inicio del registro a mostrar
		$pagina = 0;

		if (!isset($pag)) {
		   $inicio = 0;
		   $pagina = 1;
		   
		}
		else {
			if($pag == 1){
				$inicio = 0;
		  		$pagina = 1;
			} else {
				$pagina = $pag;
		   		$inicio = ($pagina - 1) * $tamanoPagina;
			}
			
		}

		//calculo el total de páginas
		$total_paginas = ceil($numResultados / $tamanoPagina);

		$sql2 = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, protesis.nomb trabajo, lista_precios_protesis.precio precio, lista_precios_protesis.dias_entrega tiempoEntrega, material.nomb material, laboratorio.img_art imagen, lista_precios_protesis.id_lista_precios_protesis id, laboratorio.id_rep from trabajo, sepomex, laboratorio, protesis left join lista_precios_protesis on lista_precios_protesis.id_pro = protesis.id_pro left join material on material.id_mat = protesis.id_mat where trabajo.nomb LIKE '%$txt%' and lista_precios_protesis.id_lab = laboratorio.id_lab and trabajo.id_trab = protesis.id_trab and lista_precios_protesis.disponible = 1 and laboratorio.id_rep = sepomex.id ".$filtroSql." ORDER BY trabajo.nomb limit $inicio, $tamanoPagina");

		$resultado = ($db->rows($sql2) >= 1) ? $sql2 -> fetch_all(MYSQLI_ASSOC) : 0;

		return $resultado;

		$db -> exit();

	}

	public static function buscarOrtodoncia($txt,$pag,$filtroSql){

		$db = new Conexion();

		$sql1 = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, ortodoncia_prod.nomb trabajo, lista_precios_ortodoncia.precio, lista_precios_ortodoncia.dias_terminado tiempoEntrega, laboratorio.img_art imagen, lista_precios_ortodoncia.id_ort id, laboratorio.id_rep FROM lista_precios_ortodoncia left join laboratorio on laboratorio.id_lab = lista_precios_ortodoncia.id_lab left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = lista_precios_ortodoncia.id_ort_prod, sepomex WHERE ortodoncia_prod.nomb LIKE '%$txt%' and lista_precios_ortodoncia.disponible = 1 and laboratorio.id_rep = sepomex.id ".$filtroSql."");

		$numResultados = $db -> rows($sql1);

		//LIMITE DE BUSQUEDA
		$tamanoPagina = 15;

		//examino la página a mostrar y el inicio del registro a mostrar
		$pagina = 0;

		if (!isset($pag)) {
		   $inicio = 0;
		   $pagina = 1;
		   
		}
		else {
			if($pag == 1){
				$inicio = 0;
		  		$pagina = 1;
			} else {
				$pagina = $pag;
		   		$inicio = ($pagina - 1) * $tamanoPagina;
			}
			
		}

		//calculo el total de páginas
		$total_paginas = ceil($numResultados / $tamanoPagina);

		$sql2 = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, ortodoncia_prod.nomb trabajo, lista_precios_ortodoncia.precio, lista_precios_ortodoncia.dias_entrega tiempoEntrega, laboratorio.img_art imagen, lista_precios_ortodoncia.id_ort idOrto, laboratorio.id_rep FROM lista_precios_ortodoncia left join laboratorio on laboratorio.id_lab = lista_precios_ortodoncia.id_lab left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = lista_precios_ortodoncia.id_ort_prod, sepomex WHERE ortodoncia_prod.nomb LIKE '%$txt%' and lista_precios_ortodoncia.disponible = 1 and laboratorio.id_rep = sepomex.id ".$filtroSql." ORDER BY ortodoncia_prod.nomb limit $inicio, $tamanoPagina");

		$resultado = ($db->rows($sql2) >= 1) ? $sql2 -> fetch_all(MYSQLI_ASSOC) : 0;

		return $resultado;

		$db -> exit();
	}


	public static function buscarProtesisNum($txt,$filtroSql){

		$db = new Conexion();

		$sql = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, protesis.nomb trabajo, lista_precios_protesis.precio precio, material.nomb material, laboratorio.img_art imagen FROM laboratorio, material, protesis left join lista_precios_protesis on lista_precios_protesis.id_pro = protesis.id_pro, sepomex WHERE protesis.nomb LIKE '%$txt%' and lista_precios_protesis.id_lab = laboratorio.id_lab and material.id_mat = protesis.id_mat and lista_precios_protesis.disponible = 1 and laboratorio.id_rep = sepomex.id ".$filtroSql." ORDER BY protesis.nomb");

		return $db -> rows($sql);

		$db -> exit();

	}

	public static function buscarMaterialNum($txt,$filtroSql){

		$db = new Conexion();

		$sql = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, protesis.nomb trabajo, lista_precios_protesis.precio precio, material.nomb material, laboratorio.img_art imagen from laboratorio, protesis left join lista_precios_protesis on lista_precios_protesis.id_pro = protesis.id_pro, material, sepomex where material.nomb LIKE '%$txt%' and lista_precios_protesis.id_lab = laboratorio.id_lab and material.id_mat = protesis.id_mat and lista_precios_protesis.disponible = 1 and laboratorio.id_rep = sepomex.id ".$filtroSql." ORDER BY material.nomb");

		return $db->rows($sql);

		$db -> exit();

	}

	public static function buscarTrabajoNum($txt,$filtroSql){

		$db = new Conexion();

		$sql = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, protesis.nomb trabajo, lista_precios_protesis.precio precio, material.nomb material, laboratorio.img_art imagen from trabajo, laboratorio, protesis left join lista_precios_protesis on lista_precios_protesis.id_pro = protesis.id_pro left join material on material.id_mat = protesis.id_mat, sepomex where trabajo.nomb LIKE '%$txt%' and lista_precios_protesis.id_lab = laboratorio.id_lab and trabajo.id_trab = protesis.id_trab and lista_precios_protesis.disponible = 1 and laboratorio.id_rep = sepomex.id ".$filtroSql." ORDER BY trabajo.nomb");

		return $db->rows($sql);

		$db -> exit();

	}

	public static function buscarOrtodonciaNum($txt,$filtroSql){

		$db = new Conexion();

		$sql = $db -> query("SELECT laboratorio.nomb_art artista, laboratorio.nomb laboratorio, laboratorio.direc direccion, ortodoncia_prod.nomb trabajo, lista_precios_ortodoncia.precio, laboratorio.img_art imagen, lista_precios_ortodoncia.id_ort id FROM lista_precios_ortodoncia left join laboratorio on laboratorio.id_lab = lista_precios_ortodoncia.id_lab left join ortodoncia_prod on ortodoncia_prod.id_ort_prod = lista_precios_ortodoncia.id_ort_prod, sepomex WHERE ortodoncia_prod.nomb LIKE '%$txt%' and lista_precios_ortodoncia.disponible = 1 and laboratorio.id_rep = sepomex.id ".$filtroSql." ORDER BY ortodoncia_prod.nomb");

		return $db -> rows($sql);

		$db -> exit();

	}

}

 ?>