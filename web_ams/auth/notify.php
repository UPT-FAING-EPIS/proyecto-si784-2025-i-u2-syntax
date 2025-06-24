<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../utils/CorreoConfirmacion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); 
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$email = $_POST['email'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); 
    echo json_encode(['success' => false, 'message' => 'Correo inválido']);
    exit;
}

$correo = new CorreoConfirmacion();
$codigo = $correo->mtdNotificar($email);

if ($codigo !== null) {
    session_start();
    $_SESSION['codigo_verificacion'] = $codigo;
    $_SESSION['correo_temp'] = $email;

    echo json_encode([
        'success' => true,
        'message' => 'Código enviado',
        'codigo' => $codigo 
    ]);
} else {
    http_response_code(500); 
    echo json_encode(['success' => false, 'message' => 'No se pudo enviar el correo']);
}
