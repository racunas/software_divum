<?php

	class Conexion extends mysqli {

		public function __construct(){
			parent::__construct('localhost','root','root','buscalab_divum');
			$this->query("SET NAMES 'utf8';");
			$this->connect_errno ? die ('Error con la conexion') : $x = '';
			echo $x;
			unset($x);
		}

		public function recorrer($y){
			return mysqli_fetch_array($y);
		}

		public function rows($y){
			return mysqli_num_rows($y);
		}

		public function exit(){
			return mysqli::close();
		}

		public function error(){
			return $mysqli->error;
		}

		public function ultimoID(){
			return $mysqli->insert_id;
		}
	}


?>
