<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

date_default_timezone_set('UTC');
date_default_timezone_set("America/Mexico_City");

require_once "controladores/cabezote.controlador.php";
require_once "controladores/infoproducto.controlador.php";
require_once "controladores/listaTrabajos.controlador.php";
require_once "controladores/perfil.controlador.php";
require_once "controladores/plantilla.controlador.php";
require_once "controladores/resultados.controlador.php";
require_once "controladores/ruta.controlador.php";
require_once "controladores/usuario.controlador.php";

require_once "modelos/comparador.modelo.php";
require_once "modelos/conexion.php";
require_once "modelos/infoproducto.modelo.php";
require_once "modelos/listaTrabajos.modelo.php";
require_once "modelos/perfil.modelo.php";
require_once "modelos/resultados.modelo.php";
require_once "modelos/sugerencias.modelo.php";
require_once "modelos/usuario.modelo.php";

session_start();

$plantilla = new controladorPlantilla();
$plantilla -> plantilla();