<?php 

require_once __DIR__. "/conexion.php";

class modeloSugerencias{

	public static function mdlSugerencias(){

		$db = new Conexion();

		$sql = $db->query("SELECT nomb FROM protesis order by nomb");
		$sql3 = $db->query("SELECT nomb FROM trabajo order by nomb");
		$sql4 = $db->query("SELECT nomb FROM material order by nomb");
		$sql5 = $db->query("SELECT nomb FROM ortodoncia_prod order by nomb");

		$res = array();

		while($array = $db->recorrer($sql)){
			$palabra = ucfirst($array['nomb']);
			$res[] = $palabra;
		}

		while($array = $db->recorrer($sql3)){
			$palabra = $array['nomb'];
			$res[] = $palabra;
		}

		while($array = $db->recorrer($sql4)){
			$palabra = ucfirst($array['nomb']);
			$res[] = $palabra;
		}

		while($array = $db->recorrer($sql5)){
			$palabra = ucfirst($array['nomb']);
			$res[] = $palabra;
		}

		$resNuevo = array();
		$resNuevo = array_unique($res);
		
		sort($resNuevo);

		return $resNuevo;

	}

}

 ?>