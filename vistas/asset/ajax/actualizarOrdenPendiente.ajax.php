<?php 

session_start();

include "../../../modelos/box.modelo.php";

$stringImagenes = '';
$reporte = '';

$idUsuario = $_SESSION['dentista'];

$nombrePaciente = $_POST["nombrePaciente"];
$pagoInicial = $_POST["pagoInicial"];
$tipoOrden = $_POST["tipoOrden"];
$fechaRecepcion = $_POST["fechaRecepcion"];
$direccionRecepcion = $_POST["direccionRecepcion"];
$fechaEntrega = $_POST["fechaEntrega"];
$direccionEntrega = $_POST["direccionEntrega"];
$estadoOrden = $_POST["estadoOrden"];
$descripcionOrden = $_POST["descripcionOrden"];
$colorimetria = $_POST["colorimetria"];
//$numSeleccionDientes = $_POST["numSeleccionDientes"];
$idBox = $_POST["idBox"];

$dientesSeleccionados = substr($_POST['dientesSeleccionados'], 0, -1); //EL ULTIMO CAMPO DEL ARRAY NO VALE, ES NULL
$numDientesSeleccionados =  count(explode(",", $dientesSeleccionados));


if (isset($_FILES["nuevaOrdenImg"]['name'])){ //SÍ EXISTEN IMAGENES EN EL FORMULARIO, LAS AGREGAMOS AL SERVIDOR

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

$respuesta = modeloBox::mdlActualizarOrdenPendiente($idBox,$nombrePaciente,$pagoInicial,$tipoOrden,$fechaRecepcion,$direccionRecepcion,$fechaEntrega,$direccionEntrega,$estadoOrden,$descripcionOrden,$colorimetria,$numDientesSeleccionados,$dientesSeleccionados,$stringImagenes,$idUsuario);

echo $respuesta;


 ?>