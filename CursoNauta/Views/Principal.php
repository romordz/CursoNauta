<?php
include 'Views\Parciales\Head.php';
include_once 'Controllers\VistasController.php';

$controller = new VistasController();

$cursosMasVendidos = $controller->getCursosMasVendidos();
$cursosRecientes = $controller->getCursosRecientes();
$cursosMejorCalificados = $controller->getCursosMejorCalificados();

function renderStars($rating)
{
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

<link rel="stylesheet" href="Views/css/SPrincipal.css">

<?php include 'Views\Parciales\Nav.php'; ?>

<!-- Presentación -->
<section id="inicio" class="hero">
    <h2>Explora y Mejora tus Habilidades Creativas</h2>
    <p>Únete a una comunidad que aprende y comparte conocimientos creativos.</p>
    <a href="index.php?page=All" class="btn">Explorar Cursos</a>
</section>

<!-- Cursos Más Vendidos -->
<section class="courses-carousel">
    <h2>Cursos Más Vendidos</h2>
    <div class="course-grid">
        <?php foreach ($cursosMasVendidos as $curso): ?>
            <a href="index.php?page=Curso&idCurso=<?= $curso['id_curso'] ?>">
                <div class="course-card">
                    <img src="data:image/jpeg;base64,<?= base64_encode($curso['imagen']) ?>" alt="Imagen del Curso" class="course-img">
                    <h3><?= htmlspecialchars($curso['titulo']) ?></h3>
                    <span class="course-category"><?= htmlspecialchars($curso['nombre_categoria']) ?></span>
                    <p><?= htmlspecialchars($curso['descripcion']) ?></p>
                    <p><strong>Costo: $<?= number_format($curso['costo'], 2) ?></strong></p>
                    <p>Total Ventas: <?= $curso['total_ventas'] ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Cursos Recientes -->
<section class="courses-carousel">
    <h2>Cursos Recientes</h2>
    <div class="course-grid">
        <?php foreach ($cursosRecientes as $curso): ?>
            <a href="index.php?page=Curso&idCurso=<?= $curso['id_curso'] ?>">
                <div class="course-card">
                    <img src="data:image/jpeg;base64,<?= base64_encode($curso['imagen']) ?>" alt="Imagen del Curso" class="course-img">
                    <h3><?= htmlspecialchars($curso['titulo']) ?></h3>
                    <span class="course-category"><?= htmlspecialchars($curso['nombre_categoria']) ?></span>
                    <p><?= htmlspecialchars($curso['descripcion']) ?></p>
                    <p>Costo: $<?= number_format($curso['costo'], 2) ?></p>
                    <p>Fecha de Creación: <?= $curso['fecha_creacion'] ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Cursos Mejor Calificados -->
<section class="courses-carousel">
    <h2>Cursos Mejor Calificados</h2>
    <div class="course-grid">
        <?php foreach ($cursosMejorCalificados as $curso): ?>
            <a href="index.php?page=Curso&idCurso=<?= $curso['id_curso'] ?>">
                <div class="course-card">
                    <img src="data:image/jpeg;base64,<?= base64_encode($curso['imagen']) ?>" alt="Imagen del Curso" class="course-img">
                    <h3><?= htmlspecialchars($curso['titulo']) ?></h3>
                    <span class="course-category"><?= htmlspecialchars($curso['nombre_categoria']) ?></span>
                    <div class="stars"><?= renderStars($curso['calificacion_promedio']) ?></div>
                    <p><?= htmlspecialchars($curso['descripcion']) ?></p>
                    <p><strong>Costo: $<?= number_format($curso['costo'], 2) ?></strong></p>
                    <p>Calificación Promedio: <?= round($curso['calificacion_promedio'], 1) ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<?php include 'Views\Parciales\Footer.php'; ?>