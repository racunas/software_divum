<?php

class mdlComparador{

	public function compararProtesis($txt){

		$db = new Conexion();
		$sql = $db -> query("SELECT nomb FROM protesis WHERE protesis.nomb LIKE '$txt%'");
		$find = false;
		if($db -> rows($sql) > 0){
			$find = true;
		}
		$db -> exit();
		return $find;
	}


	public function compararMaterial($txt){

		$db = new Conexion();
		$sql = $db -> query("SELECT nomb FROM material WHERE material.nomb LIKE '$txt%'");
		$find = false;
		if($db -> rows($sql) > 0){
			$find = true;
		}
		$db -> exit();
		return $find;
	}

	public function compararTrabajo($txt){

		$db = new Conexion();
		$sql = $db -> query("SELECT nomb FROM trabajo WHERE trabajo.nomb LIKE '$txt%'");
		$find = false;
		if($db -> rows($sql) > 0){
			$find = true;
		}
		$db -> exit();
		return $find;
	}

	public function compararOrtodoncia($txt){

		$db = new Conexion();
		$sql = $db -> query("SELECT nomb FROM ortodoncia_prod WHERE nomb LIKE '$txt%'");
		$find = false;
		if($db -> rows($sql) > 0){
			$find = true;
		}
		$db -> exit();
		return $find;
	}

}

?>