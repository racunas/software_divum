<?php 

session_start();

include "../../../modelos/perfil.modelo.php";

if(isset($_SESSION['tecnico'])){

    $idUsuario = $_SESSION['tecnico'];
    $es = 'tecnicos';
    $tipo = "tecnico";

} elseif(isset($_SESSION['dentista'])){

    $idUsuario = $_SESSION['dentista'];
    $es = 'dentistas';
    $tipo = "dentista";

}

if (isset($_FILES["nuevaFotoPerfil"])){ //SÍ EXISTEN IMAGENES EN EL FORMULARIO, LAS AGREGAMOS AL SERVIDOR

	$datosPerfil = modeloPerfil::mdlDatosPerfil($idUsuario,$tipo);

    $url = $_POST['url'];
    $x1 = $_POST['x1'];
    $x2 = $_POST['x2'];
    $y1 = $_POST['y1'];
    $y2 = $_POST['y2'];
    $ancho = $_POST['ancho'];
    $alto = $_POST['alto'];
    $anchoOriginal = $_POST['anchoOriginal']; //DIMENSIONES DEL LA IMAGEN EN CSS
    $altoOriginal = $_POST['altoOriginal']; //DIMENSIONES DEL LA IMAGEN EN CSS

	$aleatorio = rand();
    $file = $_FILES["nuevaFotoPerfil"];
    $nombre = $file["name"];
    //$tipo = $file["type"];
    $ruta_provisional = $file["tmp_name"];
    $size = $file["size"];

    $dimensiones = getimagesize($ruta_provisional);
    $width = $dimensiones[0]; //DIMENSIONES ORIGINALES
    $height = $dimensiones[1]; //DIMENSIONES ORIGINALES

    $z1 = ($width * $x1) / $anchoOriginal; //REGLA DE TRES PARA OBTENER EL ANCHO Y ALTO DEPENDIENDO LAS DIMENSIONES DE LA IMAGEN EN CSS
    $z2 = ($width * $x2) / $anchoOriginal;
    $w1 = ($height * $y1) / $altoOriginal;
    $w2 = ($height * $y2) / $altoOriginal;

    $nuevoAncho = $z2 - $z1;
    $nuevoAlto = $w2 - $w1;

    $fechaActual  = date("dHi");  //Fecha Actual 

    $carpeta = "../images/".$es."/";

    $nombreCompleto = $datosPerfil["nomb"].$fechaActual.$aleatorio.".jpg";

    $manejadorDeOrigen = imagecreatefromjpeg($ruta_provisional);
    $manejadorDeDestino = imagecreatetruecolor($nuevoAncho,$nuevoAlto);
    imagecopyresampled($manejadorDeDestino, $manejadorDeOrigen, 0, 0, $z1, $w1, $nuevoAncho, $nuevoAlto, $nuevoAncho, $nuevoAlto);
    
    $src = $carpeta.$nombreCompleto;
    imagejpeg($manejadorDeDestino, $src);

   	$respuesta = modeloPerfil::mdlActualizarFotoPerfil($idUsuario,$tipo,$nombreCompleto); //RETORNA FALSE EN CASO DE QUE NO SE HAYA INSERTADO EL NOMBRE DE LA IMAGEN, Y RETORNA TRUE EN CASO DE QUE HAYA INSERTADO EL NOMBRE DE LA IMAGEN

   	echo $respuesta;
    
}

 ?>