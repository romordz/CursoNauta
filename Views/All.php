<?php 
include 'Views/Parciales/Head.php'; 
include_once 'Controllers/NavController.php';

$navController = new NavController();
$categoriasActivas = $navController->getCategoriasActivas();
$instructores = $navController->getInstructoresActivos();

// Captura de filtros, manejando los valores como NULL si están vacíos
$busqueda = !empty($_GET['search']) ? $_GET['search'] : null;
$categoriaId = isset($_GET['categoria']) && $_GET['categoria'] !== '' ? intval($_GET['categoria']) : null;
$instructorId = isset($_GET['user']) && $_GET['user'] !== '' ? intval($_GET['user']) : null;
$fechaInicio = isset($_GET['start-date']) && $_GET['start-date'] !== '' ? $_GET['start-date'] : null;
$fechaFin = isset($_GET['end-date']) && $_GET['end-date'] !== '' ? $_GET['end-date'] : null;

// Aplicar filtros según los parámetros disponibles
if ($busqueda) {
    $cursos = $navController->buscarCursosPorPalabraClave($busqueda);
} elseif ($categoriaId || $instructorId || $fechaInicio || $fechaFin) {
    $cursos = $navController->buscarCursosDinamico($categoriaId, $instructorId, $fechaInicio, $fechaFin);
} else {
    $cursos = $navController->getCursosActivos();
}

// Función para mostrar estrellas de calificación
function renderStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($rating >= $i) {
            $stars .= '⭐';
        } elseif ($rating >= $i - 0.5) {
            $stars .= '⭐️';
        } else {
            $stars .= '☆';
        }
    }
    return $stars;
}
?>

<link rel="stylesheet" href="Views/css/SAll.css">
<?php include 'Views/Parciales/Nav.php'; ?>

<section class="courses-section">

    <!-- Filtros -->
    <div class="filters">
        <h3>Filtrar Por:</h3>

        <form action="index.php" method="GET">
            <input type="hidden" name="page" value="All">

            <!-- Filtro de Categoría -->
            <div class="filter-category">
                <label for="category">Categoría:</label>
                <select id="category" name="categoria">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categoriasActivas as $categoria): ?>
                        <option value="<?= $categoria['id_categoria'] ?>" <?= $categoriaId == $categoria['id_categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filtro de Instructor -->
            <div class="filter-user">
                <label for="user">Instructor:</label>
                <select id="user" name="user">
                    <option value="">Todos los Instructores</option>
                    <?php foreach ($instructores as $instructor): ?>
                        <option value="<?= $instructor['idUsuario'] ?>" <?= $instructorId == $instructor['idUsuario'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($instructor['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filtro de Fecha -->
            <div class="filter-date">
                <label for="date-range">Rango de fechas:</label>
                <input type="date" id="start-date" name="start-date" value="<?= htmlspecialchars($fechaInicio) ?>">
                <input type="date" id="end-date" name="end-date" value="<?= htmlspecialchars($fechaFin) ?>">
            </div>

            <!-- Botón para aplicar filtros -->
            <button type="submit" class="apply-filters">Aplicar Filtros</button>
        </form>
    </div>

    <!-- Cursos -->
    <section class="courses-carousel">
        <h2>Cursos</h2>

        <!-- Contenedor de los cursos -->
        <div class="course-grid">
            <?php if (!empty($cursos)): ?>
                <?php foreach ($cursos as $curso): ?>
                     <a href="index.php?page=Curso&idCurso=<?= $curso['id_curso'] ?>">
                    <div class="course-card">
                        <img src="data:image/jpeg;base64,<?= base64_encode($curso['imagen']) ?>" alt="Imagen del Curso" class="course-img">
                        <h3><?= htmlspecialchars($curso['titulo']) ?></h3>
                        <span class="course-category"><?= htmlspecialchars($curso['nombre_categoria']) ?></span>
                        <div class="stars"><?= renderStars($curso['calificacion_promedio']) ?></div>
                        <p><strong>Instructor: <?= htmlspecialchars($curso['nombre_instructor']) ?></strong></p>
                        <p>Niveles: <?= htmlspecialchars($curso['niveles']) ?></p>
                        <p>Costo: $<?= htmlspecialchars(number_format($curso['costo'], 2)) ?></p>
                        <p><?= htmlspecialchars($curso['descripcion']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No se encontraron cursos con los filtros seleccionados.</p>
            <?php endif; ?>
        </div>
    </section>

</section>

<?php include 'Views/Parciales/Footer.php'; ?>
