<?php
require_once 'Controllers/InscripcionController.php';
include 'Views/Parciales/Head.php';
include 'Views/Parciales/Nav.php';

$inscripcionController = new InscripcionController();

// Captura de filtros
$categoriaID = isset($_GET['category']) && $_GET['category'] !== 'all' ? intval($_GET['category']) : null;
$estado = isset($_GET['completed']) && $_GET['completed'] !== 'all' ? $_GET['completed'] : null;
$fechaInicio = !empty($_GET['start-date']) ? $_GET['start-date'] : null;
$fechaFin = !empty($_GET['end-date']) ? $_GET['end-date'] : null;


$idUsuario = $_SESSION['user_id'];
$cursos = ($categoriaID || $estado || $fechaInicio || $fechaFin)
    ? $inscripcionController->filtrarCursosInscritos($categoriaID, $estado, $fechaInicio, $fechaFin, $idUsuario)
    : $inscripcionController->mostrarCursosInscritos();

// Obtener categorías activas para el filtro
$categoriasActivas = $inscripcionController->getCategoriasActivas();
?>

<link rel="stylesheet" href="Views/css/SKardex.css">

<div class="container">
    <div class="kardex-section">
        <div class="filters">
            <h3>Filtrar cursos</h3>
            <form action="index.php" method="GET">
                <input type="hidden" name="page" value="Kardex">

                <!-- Rango de Fechas -->
                <div class="filter-date">
                    <label for="start-date">Fecha de inscripción (inicio)</label>
                    <input type="date" id="start-date" name="start-date" value="<?= htmlspecialchars($fechaInicio) ?>">
                    <label for="end-date">Fecha de inscripción (fin)</label>
                    <input type="date" id="end-date" name="end-date" value="<?= htmlspecialchars($fechaFin) ?>">
                </div>

                <!-- Categoría -->
                <div class="filter-category">
                    <label for="category">Categoría</label>
                    <select id="category" name="category">
                        <option value="all">Todas las categorías</option>
                        <?php foreach ($categoriasActivas as $categoria): ?>
                            <option value="<?= $categoria['id_categoria'] ?>" <?= $categoriaID == $categoria['id_categoria'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Cursos Terminados -->
                <div class="filter-status">
                    <label for="completed">Estado del Curso</label>
                    <select id="completed" name="completed">
                        <option value="all" <?= $estado == null ? 'selected' : '' ?>>Todos</option>
                        <option value="completado" <?= $estado === 'completado' ? 'selected' : '' ?>>Solo cursos terminados</option>
                        <option value="en curso" <?= $estado === 'en curso' ? 'selected' : '' ?>>Solo cursos activos</option>
                    </select>
                </div>

                <!-- Aplicar Filtros -->
                <button type="submit" class="apply-filters">Aplicar filtros</button>
            </form>
        </div>


        <div class="courses-kardex">
            <h2>Kardex</h2>
            <table class="courses-table">
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th>Fecha de Inscripción</th>
                        <th>Último Acceso</th>
                        <th>Progreso</th>
                        <th>Fecha de Terminación</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cursos as $curso): ?>
                        <tr>
                            <td>
                                <a href="index.php?page=Curso&idCurso=<?= $curso['id_curso'] ?>">
                                    <?= htmlspecialchars($curso['curso_titulo']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($curso['fecha_inscripcion']); ?></td>
                            <td><?php echo htmlspecialchars($curso['fecha_ultimo_acceso']); ?></td>
                            <td><?php echo htmlspecialchars($curso['progreso']) . '%'; ?></td>
                            <td><?php echo htmlspecialchars($curso['fecha_terminacion'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($curso['categoria']); ?></td>
                            <td class="status <?php echo $curso['estado'] === 'completado' ? 'completed' : 'incomplete'; ?>">
                                <?php if ($curso['estado'] === 'completado'): ?>
                                    <a href="index.php?page=Fin&id_curso=<?php echo $curso['id_curso']; ?>" class="btn-certificado">Completado</a>
                                <?php else: ?>
                                    <?php echo htmlspecialchars($curso['estado']); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'Views/Parciales/Footer.php'; ?>