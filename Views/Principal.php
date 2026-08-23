<?php
include 'Views/Parciales/Head.php';
include_once 'Controllers/VistasController.php';
require_once 'Views/Parciales/Helpers.php';

$controller = new VistasController();

$cursosMasVendidos = $controller->getCursosMasVendidos();
$cursosRecientes = $controller->getCursosRecientes();
$cursosMejorCalificados = $controller->getCursosMejorCalificados();
?>

<link rel="stylesheet" href="Views/css/SPrincipal.css">

<?php include 'Views/Parciales/Nav.php'; ?>

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
        <?php if (!empty($cursosMasVendidos)): ?>
            <?php foreach ($cursosMasVendidos as $curso): ?>
                <a href="index.php?page=Curso&idCurso=<?= $curso['id_curso'] ?>">
                    <div class="course-card">
                        <img src="<?= htmlspecialchars($curso['imagen_url']) ?>" alt="Imagen del Curso" class="course-img">
                        <h3><?= htmlspecialchars($curso['titulo']) ?></h3>
                        <span class="course-category"><?= htmlspecialchars($curso['nombre_categoria']) ?></span>
                        <p><?= htmlspecialchars($curso['descripcion']) ?></p>
                        <p><strong>Costo: $<?= number_format($curso['costo'], 2) ?></strong></p>
                        <p>Total Ventas: <?= $curso['total_ventas'] ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aún no hay cursos más vendidos disponibles.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Cursos Recientes -->
<section class="courses-carousel">
    <h2>Cursos Recientes</h2>
    <div class="course-grid">
        <?php if (!empty($cursosRecientes)): ?>
            <?php foreach ($cursosRecientes as $curso): ?>
                <a href="index.php?page=Curso&idCurso=<?= $curso['id_curso'] ?>">
                    <div class="course-card">
                        <img src="<?= htmlspecialchars($curso['imagen_url']) ?>" alt="Imagen del Curso" class="course-img">
                        <h3><?= htmlspecialchars($curso['titulo']) ?></h3>
                        <span class="course-category"><?= htmlspecialchars($curso['nombre_categoria']) ?></span>
                        <p><?= htmlspecialchars($curso['descripcion']) ?></p>
                        <p>Costo: $<?= number_format($curso['costo'], 2) ?></p>
                        <p>Fecha de Creación: <?= $curso['fecha_creacion'] ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aún no hay cursos recientes disponibles.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Cursos Mejor Calificados -->
<section class="courses-carousel">
    <h2>Cursos Mejor Calificados</h2>
    <div class="course-grid">
        <?php if (!empty($cursosMejorCalificados)): ?>
            <?php foreach ($cursosMejorCalificados as $curso): ?>
                <a href="index.php?page=Curso&idCurso=<?= $curso['id_curso'] ?>">
                    <div class="course-card">
                        <img src="<?= htmlspecialchars($curso['imagen_url']) ?>" alt="Imagen del Curso" class="course-img">
                        <h3><?= htmlspecialchars($curso['titulo']) ?></h3>
                        <span class="course-category"><?= htmlspecialchars($curso['nombre_categoria']) ?></span>
                        <div class="stars"><?= renderStarsHtml($curso['calificacion_promedio']) ?></div>
                        <p><?= htmlspecialchars($curso['descripcion']) ?></p>
                        <p><strong>Costo: $<?= number_format($curso['costo'], 2) ?></strong></p>
                        <p>Calificación Promedio: <?= round($curso['calificacion_promedio'], 1) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aún no hay cursos con calificación disponibles.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'Views/Parciales/Footer.php'; ?>