<?php 

require_once "conexion.php";

class mdlUsuario{

	public static function mdlCambiarNuevoPassword($key,$nuevaContraseña,$confirmarNuevaContraseña){

		$db = new Conexion();

		$sqlDentista = $db -> query("SELECT * FROM usuarios WHERE aleatorio = '$key'");
		$sqlTecnico = $db -> query("SELECT * FROM usuarios_tecnicos WHERE aleatorio = '$key'");

		if($db->rows($sqlDentista) >= 1){

			if ( $db -> query("UPDATE usuarios SET pass = '".password_hash($nuevaContraseña,PASSWORD_BCRYPT)."' WHERE aleatorio = '$key' ") ){

				if($db -> query("UPDATE usuarios SET aleatorio = '' WHERE aleatorio = '$key'")){

					return true;

				}else {

					return false;

				}

			}

		} elseif($db->rows($sqlTecnico) >= 1){

			if ( $db -> query("UPDATE usuarios_tecnicos SET pass = '".password_hash($nuevaContraseña,PASSWORD_BCRYPT)."' WHERE aleatorio = '$key' ") ){

				if($db -> query("UPDATE usuarios_tecnicos SET aleatorio = '' WHERE aleatorio = '$key'")){

					return true;

				} else{

					return false;
				} 

			}

		} else{

			return false; //SIGNIFICA QUE YA EXPIRÓ EL KEY ALEATORIO

		}

		$db -> exit();

	}

	public static function mdlCambiarPassword($nuevoPassword,$idUsuario,$tipo){

		$db = new Conexion();

		switch ($tipo) {
			case 'tecnico':
				
				if ( $db -> query("UPDATE usuarios_tecnicos SET pass = '".password_hash($nuevoPassword,PASSWORD_BCRYPT)."' WHERE id_lab = $idUsuario ") ){

					return true;

				} else {

					return false;

				}

				break;

			case 'dentista':

				if ( $db -> query("UPDATE usuarios SET pass = '".password_hash($nuevoPassword,PASSWORD_BCRYPT)."' WHERE id_clie = $idUsuario ") ){

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

	public static function mdlDatosLoginUsuario($correo,$tipo){

		$db = new Conexion();

		switch ($tipo) {
			/*TECNICO*/
			case 0:
				
				$sql = $db -> query("SELECT * FROM usuarios_tecnicos WHERE correo = '$correo'");

				$respuesta = $sql -> fetch_array(MYSQLI_ASSOC);

				break;
			
			/*DENTISTA*/	
			case 1:
				
				$sql = $db -> query("SELECT * FROM usuarios WHERE correo = '$correo'");

				$respuesta = $sql -> fetch_array(MYSQLI_ASSOC);

				break;
		}

		return $respuesta;

		$db -> exit();

	}

	public static function mdlVerificarUsuario($correo,$password){

		$modelo = new mdlUsuario();

		$existe = false;

		if($modelo -> mdlExisteUsuario($correo,0)){ //Verificar sí existe como Técnico

			$datos = $modelo -> mdlDatosLoginUsuario($correo,0);
			
			if(password_verify($password,$datos['pass'])){

				$existe = true;
				
				$_SESSION['tecnico'] = $datos['id_lab'];
				
			}
			
		} elseif($modelo -> mdlExisteUsuario($correo,1)){//VERIFICAR SÍ EXISTE COMO DENTISTA

			$datos = $modelo -> mdlDatosLoginUsuario($correo,1);

			if(password_verify($password,$datos['pass'])){

				$existe = true;

				$_SESSION['dentista'] = $datos['id_clie'];

			}
		} 

		return $existe;

	}

	public static function mdlRegistrarUsuario($correo,$password,$nombre,$telefono,$rol){

		$db = new Conexion();

		$hash = password_hash($password,PASSWORD_BCRYPT);

		switch($rol){
			/*TECNICO DENTAL*/
			case "tecnico": 

				if($db -> query("INSERT INTO laboratorio (tel,email,nomb_art) VALUES ('$telefono', '$correo', '$nombre')")){

					$res = $db->query("SELECT id_lab FROM laboratorio WHERE email = '$correo'");
					
					$id = $res -> fetch_array(MYSQLI_NUM);

					if($db -> query ("INSERT INTO usuarios_tecnicos (correo, pass, id_lab, activo) VALUES ('$correo','$hash',$id[0],1)")){
						
						$_SESSION['tecnico'] = $id[0];
						
						return "tecnico"; // SE RETORNA "tecnico" PARA DAR A ENTENDER QUE SER EGISTRO UN TECNICO
					
					}

				}
				
				break;

			/*DENTISTA*/
			case "dentista": 

				if($db -> query("INSERT INTO dentista (nomb,tel,email) VALUES ('$nombre', '$telefono', '$correo')")){

					$res = $db->query("SELECT id_clie FROM dentista WHERE email = '$correo'");

					$id = $res -> fetch_array(MYSQLI_NUM);

					if($db -> query ("INSERT INTO usuarios(correo, pass, id_clie, premium, activo) VALUES ('$correo','$hash',$id[0],0,1)")){

						$_SESSION['dentista'] = $id[0];
						
						return "dentista"; //SE RETORNA "dentista" PARA DAR A ENTENDER QUE SE REGISTRO UN DENTISTA

					}

				}

				break;

			default:

				return false; //SE RETORNA FALSE PARA DAR A ENTENDER QUE NO SE REGISTRO NADA
				
				break;
		}

		$db -> exit();

	}

	public static function mdlExisteUsuario($correo,$rol){

		$db = new Conexion();

		switch($rol){

			/*TECNICO DENTAL*/
			case 0: 

				$existUser = $db->query("SELECT * FROM usuarios_tecnicos WHERE correo = '$correo'");

				if($db->rows($existUser) >= 1){
					
					return true;

				} else {

					return false;

				}

				break;

			/*DENTISTA*/
			case 1: 

				$existUser = $db->query("SELECT * FROM usuarios WHERE correo = '$correo'");

				if($db->rows($existUser) >= 1){
					
					return true;

				} else {

					return false;

				}

				break;

			default:
				//echo "No andes jugando con eso pillo";
				break;
		}

		$db -> exit();

	}

}

 ?>