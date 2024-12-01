<?php include 'Views/Parciales/Head.php'; ?>
<link rel="stylesheet" href="Views/css/SAddCurso.css">
<?php include 'Views/Parciales/Nav.php'; ?>

<?php
require_once 'Controllers/CursoController.php';
require_once 'Controllers/CategoriaController.php';

// Validar el parámetro id_curso en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: error.php?message=ID de curso no válido');
    exit;
}

$id_curso = (int) htmlspecialchars($_GET['id']);

// Obtener los datos del curso a editar
$cursoController = new CursoController();
$curso = $cursoController->obtenerCursoPorId($id_curso);

// Validar que el curso exista
if (!$curso) {
    header('Location: error.php?message=Curso no encontrado');
    exit;
}

// Obtener los niveles del curso
$niveles = $cursoController->obtenerNivelesPorCurso($id_curso);

// Obtener las categorías disponibles
$categoriaController = new CategoriaController();
$categorias = $categoriaController->obtenerCategorias();
?>

<div class="add-courses-page">
    <div class="container">
        <h2>Editar Curso</h2>
        <form id="course-form" action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_curso" value="<?= $id_curso; ?>">
            <input type="hidden" name="action" value="editarCurso">

            <!-- Información General -->
            <div class="general-info">
                <div class="row">
                    <div class="field">
                        <label for="course-image">Actualizar imagen del curso (opcional):</label>
                        <?php if (!empty($curso['imagen'])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($curso['imagen']); ?>" alt="Imagen del curso" width="100">
                        <?php endif; ?>
                        <input type="file" id="course-image" name="course_image" accept="image/*">
                    </div>
                    <div class="field">
                        <label for="course-title">Título del curso:</label>
                        <input type="text" id="course-title" name="course_title" 
                            value="<?= htmlspecialchars($curso['titulo'] ?? ''); ?>" required>
                    </div>
                    <div class="field">
                        <label for="course-category">Categoría del curso:</label>
                        <select id="course-category" name="course_category" required>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= $categoria['id_categoria'] ?? ''; ?>" 
                                    <?= isset($curso['id_categoria']) && $curso['id_categoria'] == $categoria['id_categoria'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($categoria['nombre_categoria'] ?? ''); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <label for="levels">Cantidad de niveles:</label>
                        <input type="number" id="levels" name="levels" 
                            value="<?= $niveles ? count($niveles) : 0; ?>" readonly>
                    </div>
                    <div class="field">
                        <label for="course-price">Costo del curso completo:</label>
                        <input type="number" id="course-price" name="course_price" step="0.01" 
                            value="<?= htmlspecialchars($curso['precio'] ?? 0); ?>" required>
                    </div>
                    <div class="field">
                        <label for="course-status">Estado:</label>
                        <select id="course-status" name="course_status" required>
                            <option value="1" <?= $curso['activo'] == 1 ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?= $curso['activo'] == 0 ? 'selected' : ''; ?>>Inactivo</option>
                        </select>
                    </div>
                </div>
                <label for="course-description">Descripción general:</label>
                <textarea id="course-description" name="course_description" rows="3" required><?= htmlspecialchars($curso['descripcion'] ?? ''); ?></textarea>
            </div>

            <!-- Niveles -->
            <div id="level-container" class="level-container">
                <?php if ($niveles): ?>
                    <?php foreach ($niveles as $index => $nivel): ?>
                        <div class="level">
                            <h4>Nivel <?= $index + 1; ?></h4>
                            <div class="field">
                                <label for="level_title_<?= $index; ?>">Título del nivel:</label>
                                <input type="text" id="level_title_<?= $index; ?>" 
                                    name="level_title_<?= $index + 1; ?>" 
                                    value="<?= htmlspecialchars($nivel['titulo'] ?? ''); ?>" required>
                            </div>
                            <div class="field">
                                <label for="level_content_<?= $index; ?>">Contenido:</label>
                                <textarea id="level_content_<?= $index; ?>" 
                                    name="level_content_<?= $index + 1; ?>" required><?= htmlspecialchars($nivel['contenido'] ?? ''); ?></textarea>
                                    <input type="hidden" name="level_id_<?= $index + 1; ?>" value="<?= $nivel['id_nivel']; ?>">
                            </div>
                            <div class="field">
                                <label for="level_price_<?= $index; ?>">Costo:</label>
                                <input type="number" id="level_price_<?= $index; ?>" 
                                    name="level_price_<?= $index + 1; ?>" 
                                    value="<?= htmlspecialchars($nivel['precio'] ?? 0); ?>" step="0.01" required>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay niveles disponibles para este curso.</p>
                <?php endif; ?>
            </div>

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</div>

<?php include 'Views/Parciales/Footer.php'; ?>
