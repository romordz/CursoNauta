<?php
require_once 'Controllers/InscripcionController.php';
include 'Views/Parciales/Head.php';

$id_curso = $_GET['id_curso'];
$id_usuario = $_SESSION['user_id']; // ID del usuario desde la sesión

$inscripcionController = new InscripcionController();
$certificado = $inscripcionController->generarCertificado($id_curso, $id_usuario);
?>

<?php
require_once 'Controllers/ComentariosController.php';
$comentariosController = new ComentariosController();

// Procesar el formulario si se ha enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comentario = $_POST['comentario'];
    $calificacion = $_POST['calificacion'];

    // Guardar el comentario y la calificación
    $comentariosController->enviarComentario($id_curso, $id_usuario, $comentario, $calificacion);

    // Actualizar el comentario existente después de enviarlo
    $comentarioExistente = [
        'comentario' => $comentario,
        'calificacion' => $calificacion,
        'fecha_comentario' => date("Y-m-d H:i:s"),
    ];
} else {
    // Mostrar el comentario y la calificación existentes
    $comentarioExistente = $comentariosController->mostrarComentario($id_curso, $id_usuario);
}
?>



<link rel="stylesheet" href="Views/css/SFin.css">

<div class="completion-section">
    <h2>¡Felicidades por completar el curso!</h2>
    <p>Has completado satisfactoriamente el curso <strong><?php echo htmlspecialchars($certificado['nombre_curso']); ?></strong>. Aquí tienes tu diploma:</p>

    <!-- Diploma -->
    <div class="diploma-container">
        <div class="diploma-header">
            <h1>Diploma de Reconocimiento</h1>
        </div>
        <div class="diploma-body">
            <p>Este diploma se otorga a:</p>
            <h2><?php echo htmlspecialchars($certificado['nombre_estudiante']); ?></h2>
            <p>En reconocimiento a su excelente desempeño y dedicación en el curso de:</p>
            <h3><?php echo htmlspecialchars($certificado['nombre_curso']); ?></h3>
        </div>
        <div class="diploma-footer">
            <div class="signature">
                <p>Firma del Instructor</p>
                <p><?php echo htmlspecialchars($certificado['nombre_instructor']); ?></p>
                <hr>
            </div>
            <div class="date">
                <p>Fecha de Emisión</p>
                <p><?php echo date("d/m/Y", strtotime($certificado['fecha_terminacion'])); ?></p>
                <hr>
            </div>
        </div>
    </div>

    <!-- Botón para descargar diploma
    <a href="" download="Diploma_<?php echo htmlspecialchars($certificado['nombre_estudiante']); ?>.png" class="download-button">Descargar Diploma</a> -->

    <!-- Sección para valorar el curso -->
    <div class="rating-section">
        <h3>Valoración de curso</h3>
        <?php if ($comentarioExistente): ?>
            <!-- Mostrar la calificación y comentario existentes -->
            <div class="existing-rating">
                <p><strong>Calificación:</strong> <?php echo htmlspecialchars($comentarioExistente['calificacion']); ?> estrellas</p>
                <p><strong>Comentario:</strong> <?php echo htmlspecialchars($comentarioExistente['comentario']); ?></p>
                <p><em>Fecha: <?php echo htmlspecialchars(date("d/m/Y", strtotime($comentarioExistente['fecha_comentario']))); ?></em></p>
            </div>
        <?php else: ?>
            <!-- Mostrar el formulario para enviar calificación y comentario -->
            <form method="post">
                <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($id_curso); ?>">
                <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($id_usuario); ?>">

                <div class="stars">
                    <input type="radio" id="star5" name="calificacion" value="5" required><label for="star5">★</label>
                    <input type="radio" id="star4" name="calificacion" value="4"><label for="star4">★</label>
                    <input type="radio" id="star3" name="calificacion" value="3"><label for="star3">★</label>
                    <input type="radio" id="star2" name="calificacion" value="2"><label for="star2">★</label>
                    <input type="radio" id="star1" name="calificacion" value="1"><label for="star1">★</label>
                </div>

                <div class="comments-section">
                    <h3>Deja un comentario</h3>
                    <textarea name="comentario" placeholder="Escribe tu comentario aquí..." required></textarea>
                    <button type="submit" class="submit-comment">Enviar comentario</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>