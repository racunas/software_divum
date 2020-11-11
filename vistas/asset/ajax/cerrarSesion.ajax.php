<?php 

session_start();


if(isset($_SESSION['dentista'])){

	session_destroy();

	echo "1";

} else if(isset($_SESSION['tecnico'])) {

	session_destroy();

	echo "1";

}

 ?>