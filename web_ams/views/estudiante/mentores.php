<?php
require_once '../../config/constants.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 2) {
    header('Location: ' . BASE_URL . '/index.php?accion=login');
    exit;
}

include BASE_PATH . '/views/components/head.php';
include BASE_PATH . '/views/components/header.php';
include BASE_PATH . '/views/components/nav.php';

require_once BASE_PATH . '/models/Usuario.php';
require_once BASE_PATH . '/models/Clase.php';

$usuarioModel = new Usuario();
$id_estudiante = $usuarioModel->obtenerIdEstudiante($_SESSION['usuario_id']);

$claseModel = new ClaseModel();
//$mentores = $claseModel->obtenerMentoresPorEstudiante($id_estudiante);
?>

<div class="contenido-dashboard">
    <h2>Mentores Asignados</h2>
    <ul>
        <?php foreach ($mentores as $mentor): ?>
            <li><strong><?= $mentor['nombre'] ?></strong> - <?= $mentor['email'] ?></li>
        <?php endforeach; ?>
    </ul>
</div>
