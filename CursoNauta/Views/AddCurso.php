<?php include 'Views\Parciales\Head.php'; ?>

<link rel="stylesheet" href="Views/css/SAddCurso.css">

<?php include 'Views\Parciales\Nav.php'; ?>

<!------------ SECCION DE OBTENER CATEGORIAS PARA MOSTRAR --------------->
<?php
require_once 'Models/CategoriaModel.php';
require_once 'Controllers/CategoriaController.php';

// Instanciamos el controlador
$controller = new CategoriaController();

// Obtenemos las categorías
$categorias = $controller->obtenerCategorias();
?>


<!-- Agregar -->
<div class="add-courses-page">
    <div class="container">
        <h2>Agregar Curso</h2>
        <form id="course-form" action="index.php?page=CurC" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="agregarCurso">
            <!-- Contenedor de Información General -->
            <div class="general-info">
                <div class="row">
                    <div class="field">
                        <label for="course-image">Cargar imagen del curso:</label>
                        <input type="file" id="course-image" name="course_image" accept="image/*">
                    </div>

                    <div class="field">
                        <label for="course-title">Título del curso:</label>
                        <input type="text" id="course-title" name="course_title">
                    </div>

                    <div class="field">
                        <label for="course-category">Categoría del curso:</label>

                        <select id="course-category" name="course_category">
                            <option value=""></option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?php echo htmlspecialchars($categoria['id_categoria']); ?>">
                                    <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <div class="row">
                    <div class="field">
                        <label for="levels">Cantidad de niveles:</label>
                        <input type="number" id="levels" name="levels" min="1" oninput="generateLevelFields()">
                    </div>
                    <div class="field">
                        <label for="course-price">Costo del curso completo:</label>
                        <input type="number" id="course-price" name="course_price" step="0.01">
                    </div>
                    <div class="field">
                        <label for="level-price">Costo por nivel (opcional):</label>
                        <input type="number" id="level-price" name="level_price" step="0.01">
                    </div>
                </div>

                <label for="course-description">Descripción general:</label>
                <textarea id="course-description" name="course_description" rows="3"></textarea>
            </div>

            <!-- Contenedor de Niveles -->
            <div id="level-container" class="level-container"></div>

            <!-- Botón de envío -->
            <button type="submit">Guardar Curso</button>
        </form>
    </div>
</div>

<script src="Views\js\JAddCurso.js"> </script>

<?php include 'Views\Parciales\Footer.php'; ?>