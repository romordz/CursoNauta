<?php include 'Views\Parciales\Head.php'; ?>

<link rel="stylesheet" href="Views/css/SVentas.css">

<?php 
include 'Views\Parciales\Nav.php';

require_once 'Controllers/CursoController.php';
$cursoController = new CursoController();
$cursos = $cursoController->mostrarCursos();
$totalIngresos = $cursoController->obtenerTotalIngresos();
?>

<!-- Lista Ventas -->
<div class="container">

    <div class="kardex-section">
        <!-- Filtros -->
        <div class="filters">
            <h3>Filtrar cursos</h3>

            <div class="filter-date">
                <label for="start-date">Fecha de creación (inicio)</label>
                <input type="date" id="start-date">
                <label for="end-date">Fecha de creación (fin)</label>
                <input type="date" id="end-date">
            </div>

            <div class="filter-category">
                <label for="category">Categoría</label>
                <select id="category">
                    <option value="all">Todas las categorías</option>
                    <option value="web">Desarrollo Web</option>
                    <option value="programming">Programación</option>
                    <option value="design">Diseño</option>
                </select>
            </div>

            <div class="filter-status">
                <label for="completed">Estado del Curso</label>
                <select id="completed">
                    <option value="all">Todos</option>
                    <option value="incomplete">Solo cursos activos</option>
                </select>
            </div>

            <button class="apply-filters">Aplicar filtros</button>
        </div>

        <!-- Tabla de Cursos -->
        <div class="courses-kardex">
            <div class="courses-kardex-header">
                <h2>Lista de Cursos</h2>
                <a href="index.php?page=AddCurso" id="add-course-btn" class="add-course-btn"><i class="fa fa-plus"></i>
                    Agregar Curso</a>
            </div>

            <table class="courses-table">
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th>Alumnos Inscritos</th>
                        <th>Nivel Promedio</th>
                        <th>Ingresos Totales</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($cursos as $curso): ?>
                        <tr class="course-row" data-course="<?= htmlspecialchars($curso['titulo']); ?>">
                            <td><?= htmlspecialchars($curso['titulo']); ?></td>
                            <td><?= $curso['alumnos_inscritos'] ?? 'N/A'; ?></td>
                            <td><?= $curso['nivel_promedio'] ?? 'N/A'; ?>%</td>
                            <td>$<?= number_format($curso['ingresos_totales'] ?? 0, 2); ?></td>

                            <td>
                                <!-- Botón de habilitar/deshabilitar -->
                                <form method="POST" style="display:inline" onsubmit="return confirmarAccion()">
                                    <input type="hidden" name="id_curso" value="<?= $curso['id_curso']; ?>">
                                    <input type="hidden" name="nuevoEstado"
                                        value="<?= !empty($curso['activo']) && $curso['activo'] ? 0 : 1; ?>">
                                    <input type="hidden" name="action" value="toggle">
                                    <button type="submit">
                                        <?= !empty($curso['activo']) && $curso['activo'] ? 'Deshabilitar' : 'Habilitar'; ?>
                                    </button>
                                </form>

                                <!-- Botón de editar -->
                                <a href="index.php?page=EditCurso&id=<?= $curso['id_curso']; ?>" class="edit-course-btn">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <!-- Total ingresos por todos los cursos -->
                <tfoot>
                    <tr>
                        <td colspan="3">Total ingresos:</td>
                        <td>$<?= number_format($totalIngresos, 2); ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <!-- Detalle de Alumnos Oculto -->
            <div id="course-details" class="course-details" style="display: none;">
                <h3>Detalles del Curso</h3>
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th>Fecha de Inscripción</th>
                            <th>Nivel de Avance</th>
                            <th>Precio Pagado</th>
                            <th>Forma de Pago</th>
                        </tr>
                    </thead>
                    <tbody id="students-body">
                        <!-- Los detalles de los alumnos se actualizarán dinámicamente -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">Total ingresos por el curso:</td>
                            <td id="course-total">$0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>

</div>

<script src="Views/js/JVentas.js"></script>
<script>
    function cambiarEstadoCurso(idCurso, nuevoEstado) {
        if (confirm(`¿Estás seguro de que deseas ${nuevoEstado ? 'habilitar' : 'deshabilitar'} este curso?`)) {
            fetch('Controllers/CursoController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'cambiarEstadoCurso', idCurso, nuevoEstado })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(`Curso ${nuevoEstado ? 'habilitado' : 'deshabilitado'} correctamente.`);
                        location.reload();
                    } else {
                        alert('Error al cambiar el estado del curso.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }
</script>

<?php include 'Views\Parciales\Footer.php'; ?>
