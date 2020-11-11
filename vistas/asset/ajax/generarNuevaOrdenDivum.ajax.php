<?php 

session_start();

include "../../../modelos/box.modelo.php";
include "../../../modelos/orden.modelo.php";
require_once "../../../modelos/perfil.modelo.php";

if( isset($_SESSION['dentista']) ){

	$idUsuario = $_SESSION['dentista'];

}

$stringImagenes = "";
$reporte = "";


if (isset($_FILES["nuevaOrdenImg"])){ //SÍ EXISTEN IMAGENES EN EL FORMULARIO, LAS AGREGAMOS AL SERVIDOR

    for ($i=0; $i < count($_FILES["nuevaOrdenImg"]['name']); $i++) { 
        
        $aleatorio = rand();
        $url = $_POST['url'];  
        $file = $_FILES["nuevaOrdenImg"];
        $nombre = $file["name"][$i];
        $tipo = $file["type"][$i];
        $ruta_provisional = $file["tmp_name"][$i];
        $size = $file["size"][$i];
        $dimensiones = getimagesize($ruta_provisional);
        $width = $dimensiones[0];
        $height = $dimensiones[1];
        $fechaActual  = date("dHi");  //Fecha Actual   
        $carpeta = "../images/ordenes/";

        $nombreCompleto = $fechaActual.$aleatorio.$nombre;

        if( (count($_FILES['nuevaOrdenImg']['name']) > 1) && ($i < count($_FILES["nuevaOrdenImg"]['name']) - 1) ){

            $stringImagenes .= $nombreCompleto;
            $stringImagenes .= ",";

        } else {
            
            $stringImagenes .= $nombreCompleto;

        }


        if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
        {
            $reporte .= "Error, el archivo no es una imagen"; 
        }
        else if ($size > 2048*2048)
        {
            $reporte .= "Error, el tamaño máximo permitido es un 2MB";
        }
        else if($width < 60 || $height < 60)
        {
            $reporte .= "Error la anchura y la altura mínima permitida es 60px";
        }
        else
        {
            $src = $carpeta.$nombreCompleto;
            move_uploaded_file($ruta_provisional, $src);
        }

    }

    echo $reporte;

}

$etapa = $_POST['nombreOrden'];
$pagoOrden = $_POST['pago'];
$estadoOrden = $_POST['estadoNuevaOrden'];
$idOrden = $_POST['idOrden'];
$fechaRecepcion = $_POST['fechaRecepcionNuevaOrden'];
$fechaEntrega = $_POST['fechaEntregaNuevaOrden'];
$descripcion = $_POST['descripcionOrden'];
$imagenes = $stringImagenes;
$fechaOrden = date("Y-m-d H:i:s");
$url = $_POST['url'];

///////////////////////////////////////////////////////////////////7

$idNuevaOrden = modeloOrden::mdlContinuarOrden($idOrden, $pagoOrden, $etapa, $estadoOrden, 1, $fechaRecepcion, $fechaEntrega, $descripcion, $url); //DAMOS DE ALTA LA NUEVA ETAPA DE LA ORDEN

$imagenes = explode(",", $stringImagenes);

for ($i=0; $i < count($imagenes); $i++) { //DAMOS DE ALTA TODAS LAS IMAGENES QUE HAYA AGREGADO

	modeloOrden::mdlAltaImagenes($imagenes[$i],$idOrden,$idNuevaOrden);

}


echo 1;


 ?>