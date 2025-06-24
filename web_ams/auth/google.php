<?php
use Google\Service\Oauth2;

require_once(__DIR__ . '/../config/constants.php');
require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/models/Usuario.php';

session_start();

$client = new Google_Client();
$client->setClientId('59149942943-1pfft8ievm0sh1o2fni3o8hcjcl40h6i.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri(BASE_URL . '/auth/google.php');
$client->addScope('email');
$client->addScope('profile');

if (!isset($_GET['code'])) {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    exit;
} else {
    $client->authenticate($_GET['code']);
    $token = $client->getAccessToken();
    $client->setAccessToken($token);

    $oauth = new Oauth2($client);
    $userInfo = $oauth->userinfo->get();

    $usuario = new Usuario();
    $existe = $usuario->buscarPorCorreo($userInfo->email);

    if ($existe) {
        $_SESSION['usuario_id'] = $existe['ID_USUARIO'];
        $_SESSION['rol_id'] = (int) $existe['ID_ROL'];
        $_SESSION['rol_nombre'] = $existe['ROL'];
    } else {
        error_log("🟡 Usuario no existe en DB. Registrando con Google: {$userInfo->email}");
        $id = $usuario->registrarOAuth($userInfo->givenName, $userInfo->familyName, $userInfo->email);
        $_SESSION['usuario_id'] = $id;
        $_SESSION['rol_id'] = 1; 
        $_SESSION['rol_nombre'] = 'VISITANTE';
    }

    header('Location: ' . BASE_URL . '/index.php');
    exit;
}
