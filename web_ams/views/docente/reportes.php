<?php
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
?>

<div class="container mt-4">
    <h2><i class="fas fa-chart-bar me-2"></i>Reportes Académicos por Clase</h2>
    
    <?php if (empty($resumen)): ?>
        <div class="alert alert-info mt-4">No hay clases registradas para mostrar reportes.</div>
    <?php else: ?>
        <div class="table-responsive mt-4">
            <table class="table table-striped table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Curso</th>
                        <th>Ciclo</th>
                        <th>Cantidad de Estudiantes</th>
                        <th>Promedio General</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resumen as $i => $fila): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($fila['curso']) ?></td>
                            <td><?= htmlspecialchars($fila['ciclo']) ?></td>
                            <td><?= $fila['estudiantes'] ?></td>
                            <td><strong><?= $fila['promedio'] ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Gráfico de Promedios -->
        <div class="card mt-5">
            <div class="card-body">
                <h5 class="card-title">Gráfico de Promedio General por Clase</h5>
                <canvas id="graficoPromedios" height="100"></canvas>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        const ctx = document.getElementById('graficoPromedios').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($resumen, 'curso')) ?>,
                datasets: [{
                    label: 'Promedio',
                    data: <?= json_encode(array_column($resumen, 'promedio')) ?>,
                    backgroundColor: 'rgba(30, 58, 95, 0.7)',
                    borderColor: 'rgba(30, 58, 95, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        min: 0,
                        max: 20,
                        ticks: { stepSize: 2 }
                    }
                }
            }
        });
        </script>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>
