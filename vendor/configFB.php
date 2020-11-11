<?php


// Incluir el autoloader del the SDK
require_once __DIR__ . '/facebook/vendor/autoload.php';

// Include required libraries
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

/*
 * Configuración de Facebook SDK
 */
$appId         = '2518404598192049'; //Identificador de la Aplicación
$appSecret     = '2a9691b761325af2bbaef66989514253'; //Clave secreta de la aplicación
$redirectURL   = 'https://buscalab.com/'; //Callback URL
$fbPermissions = array('');  //Permisos opcionales

$fb = new Facebook(array(
    'app_id' => $appId,
    'app_secret' => $appSecret,
    'default_graph_version' => 'v5.7',
));

// Obtener el apoyo de logueo
$helper = $fb->getRedirectLoginHelper();

if(isset($_SESSION['facebook_access_token'])){
    $accessToken = $_SESSION['facebook_access_token'];
}else{
    $accessToken = $helper->getAccessToken();
}

?>