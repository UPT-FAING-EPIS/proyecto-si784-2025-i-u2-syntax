<?php
$pageTitle = $pageTitle ?? "Ver Calificaciones";
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
?>

<div class="container mt-5">
    <h2><i class="fas fa-clipboard-list me-2"></i><?= $pageTitle ?></h2>

    <?php if (isset($clase)): ?>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title"><strong>Curso:</strong> <?= $clase['NOMBRE_CURSO'] ?></h5>
                <p class="card-text"><strong>Ciclo:</strong> <?= $clase['NOMBRE_CICLO'] ?></p>
                <p class="card-text"><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($clase['FECHA_INICIO'])) ?> - <?= date('d/m/Y', strtotime($clase['FECHA_FIN'])) ?></p>
            </div>
        </div>

        <?php if (!empty($calificaciones)): ?>
            <div class="table-responsive mt-4">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Nota</th>
                            <th>Observación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($calificaciones as $i => $alumno): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $alumno['CODIGO'] ?></td>
                                <td><?= $alumno['NOMBRE'] . ' ' . $alumno['APELLIDO'] ?></td>
                                <td><?= $alumno['EMAIL_CORPORATIVO'] ?></td>
                                <td><?= $alumno['CALIFICACION'] ?? '-' ?></td>
                                <td><?= $alumno['OBSERVACION'] ?? '' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info mt-4">No se han registrado calificaciones para esta clase.</div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-warning mt-4">Clase no encontrada.</div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>
