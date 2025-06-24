<?php
$pageTitle = "Alumnos por Clase";
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
require_once BASE_PATH . '/models/DocenteModel.php';

$docenteModel = new DocenteModel();
$docente = $docenteModel->obtenerIdDocente($_SESSION['usuario_id']);
$clases = $docenteModel->obtenerClasesAsignadas($docente['ID_DOCENTE']);
?>

<div class="container mt-4">
    <h2><i class="fas fa-users me-2"></i>Gestionar Alumnos</h2>

    <div class="mb-3 mt-4">
        <label for="claseSelect" class="form-label fw-bold">Selecciona una clase:</label>
        <select id="claseSelect" class="form-select" onchange="cargarEstudiantes()">
            <option value="">-- Selecciona --</option>
            <?php foreach ($clases as $clase): ?>
                <option value="<?= $clase['ID_CLASE'] ?>">
                    <?= $clase['NOMBRE_CURSO'] ?> - <?= $clase['NOMBRE_CICLO'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="resultadoEstudiantes" class="mt-4"></div>
</div>

<script>
function cargarEstudiantes() {
    const idClase = document.getElementById('claseSelect').value;
    const contenedor = document.getElementById('resultadoEstudiantes');

    if (!idClase) {
        contenedor.innerHTML = '<div class="alert alert-info">Selecciona una clase para ver sus alumnos.</div>';
        return;
    }

    contenedor.innerHTML = '<div class="text-center my-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';

    fetch(`<?= BASE_URL ?>/index.php?accion=obtener_estudiantes_clase&id_clase=${idClase}`)
        .then(r => r.json())
        .then(data => {
            if (!Array.isArray(data) || data.length === 0) {
                contenedor.innerHTML = '<div class="alert alert-warning">No hay estudiantes en esta clase.</div>';
                return;
            }

            let html = `<table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Nota Actual</th>
                        <th>Nueva Nota</th>
                        <th>Observación</th>
                        <th>Acción</th>
                    </tr>
                </thead><tbody>`;

            data.forEach((est, i) => {
                html += `
                <tr>
                    <td>${i + 1}</td>
                    <td>${est.CODIGO}</td>
                    <td>${est.NOMBRE} ${est.APELLIDO}</td>
                    <td>${est.CALIFICACION_ACTUAL ?? '-'}</td>
                    <td>
                        <input type="number" id="nota_${est.ID_ESTUDIANTE}" min="0" max="20" class="form-control form-control-sm" required>
                    </td>
                    <td>
                        <input type="text" id="obs_${est.ID_ESTUDIANTE}" class="form-control form-control-sm">
                    </td>
                    <td>
                        <button onclick="guardarNota(${idClase}, ${est.ID_ESTUDIANTE})" class="btn btn-success btn-sm">
                            <i class="fas fa-check"></i> Guardar
                        </button>
                    </td>
                </tr>`;
            });

            html += '</tbody></table>';
            contenedor.innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            contenedor.innerHTML = '<div class="alert alert-danger">Error al cargar estudiantes.</div>';
        });
}

function guardarNota(idClase, idEstudiante) {
    const nota = document.getElementById(`nota_${idEstudiante}`).value;
    const obs = document.getElementById(`obs_${idEstudiante}`).value;

    const formData = new FormData();
    formData.append('id_clase', idClase);
    formData.append('id_estudiante', idEstudiante);
    formData.append('calificacion', nota);
    formData.append('observacion', obs);

    fetch('<?= BASE_URL ?>/index.php?accion=calificar_estudiante', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('✅ Calificación guardada con éxito.');
            cargarEstudiantes(); // recarga la tabla
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        console.error(error);
        alert('Error al enviar la calificación.');
    });
}
</script>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>
