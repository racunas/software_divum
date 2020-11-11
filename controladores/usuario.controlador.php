<?php 

class ctrUsuario{

	public static function ctrVerificarUsuario($correo,$password){

		$respuesta = mdlUsuario::mdlVerificarUsuario($correo,$password);

		if(!$respuesta){

			echo "<script>
					
					swal({
					  type: 'error',
					  title: 'Correo / Contraseña incorrecta',
					  text: 'Verifica tus datos e inténtalo de nuevo',
					  confirmButtonText: 'Ok',
					  confirmButtonColor: '#9ac76d'
					}).then((result) =>{

						if(result.value){

							$('#modalIniciarSesion').modal('show');
							$('#iniciarSesionCorreo').focus();

						}

					});

				</script>";

		} else {

			echo '<script>
								
					index();

				</script>';

		}

	}

	public static function ctrRegistrarUsuario(){

		$url = ruta::obtenerRuta();

		if(isset($_POST['submitRegistro'])){

			$correo = $_POST['correoElectronico'];
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
			$nombre = $_POST['nombreCompleto'];
			$telefono = NULL;
			$rol = $_POST['queEs'];

			if(isset($_POST['telefono'])){

				$telefono = $_POST['telefono'];
				
			}

			if($password != $password2){

				echo "<script>
					
					swal({
					  type: 'error',
					  title: 'Contraseñas diferentes',
					  text: 'Al registrarse, verifica que tus contraseñas sean iguales',
					  confirmButtonText: 'Ok',
					  confirmButtonColor: '#9ac76d'
					}).then((result) =>{
						
						if(result){
							$('#modalRegistro').modal('show');
							$('#correoElectronico').focus();
						}

					});

				</script>";

			} else if(mdlUsuario::mdlExisteUsuario($correo,$rol)) {

				echo "<script>
					
					swal({
					  type: 'error',
					  title: 'Ya se ha registrado el correo electrónico',
					  text: 'Utiliza otra direccion de correo ó recupera tu contraseña',
					  confirmButtonText: 'Ok',
					  confirmButtonColor: '#9ac76d'
					}).then((result) =>{
						
						if(result){
							$('#modalRegistro').modal('show');
							$('#correoElectronico').focus();
						}

					});

				</script>";

			} else {

				$registroCorrecto = mdlUsuario::mdlRegistrarUsuario($correo,$password,$nombre,$telefono,$rol);

				switch ($registroCorrecto) {

					case "tecnico":

						echo '<script>
								
								window.location.href = "'.$url.'lista-trabajos"

							</script>';
						
						break;
					
					case "dentista":

						echo '<script>
						
								swal({

									type: "success",
									title: "Registrado correctamente",
									text: "Bienvenido '.$nombre.', antes de ordenar, no olvides completar tu perfil",
									confirmButtonText: "Completar perfil",
									confirmButtonColor: "#9ac76d",
									showCancelButton: true,
									cancelButtonText: "Ok",
									cancelButtonColor: "#777"

								}).then((result) => {

									if(result.value){
										window.location.href = "'.$url.'perfil";
									} else {
										window.location.href = "'.$url.'";
									}

								});
								
							</script>';

						break;

					default:
						
						echo "<script>
					
								swal({
								  type: 'error',
								  title: 'Ocurrió un error interno',
								  text: 'Por favor, intenta registrarte de nuevo',
								  confirmButtonText: 'Ok',
								  confirmButtonColor: '#9ac76d'
								});

							</script>";

						break;

				}


			}




		}

	}

}

 ?>