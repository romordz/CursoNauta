<?php include 'Views\Parciales\Head.php'; ?>
<link rel="stylesheet" href="Views/css/SCurso.css">

<?php include 'Views\Parciales\Nav.php'; ?>

<?php
require_once 'Controllers/CursoController.php';

$cursoController = new CursoController();
$idCurso = isset($_GET['idCurso']) ? intval($_GET['idCurso']) : 0;

if ($idCurso > 0) {
    $curso = $cursoController->obtenerCursoPorId($idCurso);
    $niveles = $cursoController->obtenerNivelesPorCurso($idCurso);
    $valoracionPromedio = $cursoController->obtenerValoracionPromedio($idCurso);
    $comentarios = $cursoController->obtenerComentarios($idCurso);
} else {
    echo "Curso no encontrado.";
    exit;
}
?>

<div class="course-container">

    <div class="course-header" style="background-image: url('data:image/jpeg;base64,<?php echo base64_encode($curso['imagen']); ?>');">
        <h1 class="course-title"><?php echo htmlspecialchars($curso['titulo']); ?></h1>
        <p class="course-category">Categoría: <?php echo htmlspecialchars($curso['nombre_categoria']); ?></p>
        <p class="course-category"><strong>Creador:</strong> <?php echo htmlspecialchars($curso['nombre_creador']); ?></p>
        <a href="index.php?page=Mensajes&user_id=<?php echo $curso['id_instructor']; ?>" title="Enviar mensaje al creador" style="margin-left: 5px; font-size: 1.5em;">
            📧
        </a>
    </div>

    <div class="course-description">
        <p><?php echo htmlspecialchars($curso['descripcion']); ?></p>
    </div>

    <div class="course-content">
        <div class="video-and-topics">
            <div class="video-section">
                <video id="course-video" controls></video>
                <h4>Tu progreso: 50%</h4>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 50%;"></div>
                </div>
            </div>

            <div class="topics-section">
                <div class="topic-title">
                    <i class="fas fa-book"></i>
                    <h2>Niveles</h2>
                </div>
                <ul class="topics-list">
                    <?php foreach ($niveles as $nivel): ?>
                        <li>
                            <button class="topic-btn">
                                <?php echo htmlspecialchars($nivel['titulo_nivel']); ?>
                                <span class="arrow">▶</span>
                            </button>
                            <ul class="subtopics-list">
                                <!-- Enlace al video -->
                                <li>
                                    <input type="checkbox" class="subtopic-checkbox" id="subtopic<?php echo $nivel['id_nivel']; ?>">
                                    <label for="subtopic<?php echo $nivel['id_nivel']; ?>">
                                        <a href="data:video/mp4;base64,<?php echo base64_encode($nivel['video']); ?>" class="subtopic-link">
                                            Ver Video de <?php echo htmlspecialchars($nivel['titulo_nivel']); ?>
                                        </a>
                                    </label>
                                </li>
                                <!-- Visualización del archivo adicional en línea usando solo <object> -->
                                <?php if (!empty($nivel['archivos'])): ?>
                                    <li>
                                        <?php
                                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                                        $mime_type = $finfo->buffer($nivel['archivos']);
                                        ?>
                                        <object data="data:<?php echo $mime_type; ?>;base64,<?php echo base64_encode($nivel['archivos']); ?>"
                                            type="<?php echo $mime_type; ?>" width="100%" height="300px">
                                            <p>Tu navegador no puede mostrar este archivo.</p>
                                        </object>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="course-resources">
            <div class="resource-header">
                <span>Recursos</span>
                <span class="toggle-icon">▶</span>
            </div>
            <div class="resource-content">
                <ul>
                    <?php foreach ($niveles as $nivel): ?>
                        <?php if (!empty($nivel['archivos'])): ?>
                            <?php
                            // Detectar el tipo MIME del archivo y asignar extensión adecuada
                            $finfo = new finfo(FILEINFO_MIME_TYPE);
                            $mime_type = $finfo->buffer($nivel['archivos']);
                            $extension = '';

                            switch ($mime_type) {
                                case 'application/pdf':
                                    $extension = '.pdf';
                                    break;
                                case 'image/jpeg':
                                    $extension = '.jpg';
                                    break;
                                case 'image/png':
                                    $extension = '.png';
                                    break;
                                    // Agregar más casos según el tipo de archivo
                                default:
                                    $extension = '';
                            }
                            ?>
                            <li>
                                <a href="data:<?php echo $mime_type; ?>;base64,<?php echo base64_encode($nivel['archivos']); ?>"
                                    download="Archivo_nivel_<?php echo $nivel['numero_nivel'] . $extension; ?>">
                                    <i class="file-icon">📄</i> Descargar <?php echo htmlspecialchars($nivel['titulo_nivel']); ?> - Nivel <?php echo $nivel['numero_nivel']; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="feedback-section">
            <h2>Valoraciones</h2>
            <div class="ratings">
                <span class="rating">
                    <?php
                    $estrellas = round($valoracionPromedio);
                    echo str_repeat('⭐', $estrellas) . str_repeat('☆', 5 - $estrellas);
                    ?>
                </span>
                <span>(<?php echo count($comentarios); ?> valoraciones)</span>
            </div>

            <div class="comments">
                <h2>Comentarios</h2>
                <?php foreach ($comentarios as $comentario): ?>
                    <div class="comment">
                        <div class="user-info">
                            <img src="<?php echo htmlspecialchars($comentario['foto_avatar'] ?: 'Recursos/Icon.png'); ?>"
                                alt="Foto del Usuario" class="comment-user-img">
                            <div>
                                <p class="comment-username"><?php echo htmlspecialchars($comentario['nombre_usuario']); ?></p>
                                <p class="comment-date"><?php echo htmlspecialchars(date('d/m/Y, H:i', strtotime($comentario['fecha_comentario']))); ?></p>
                            </div>
                        </div>

                        <!-- Mostrar comentario o mensaje de eliminado -->
                        <?php if ($comentario['eliminado']): ?>
                            <p class="comment-text"><em>(Este comentario ha sido eliminado por el administrador)</em></p>
                        <?php else: ?>
                            <p class="comment-text"><?php echo htmlspecialchars($comentario['comentario']); ?></p>
                        <?php endif; ?>

                        <!-- Botón de Eliminar para administradores -->
                        <?php if ($_SESSION['user_role'] == 1 && !$comentario['eliminado']): ?>
                            <button class="delete-btn">Eliminar</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="course-purchase">
        <h2>Adquiere este curso</h2>
        <p class="course-price"><strong>$<?php echo htmlspecialchars($curso['costo']); ?></strong></p>
        <a href="index.php?page=Pago&idCurso=<?php echo $idCurso; ?>" class="purchase-btn">Comprar Curso</a>
    </div>

</div>

<script src="Views\js\JCurso.js"> </script>
<?php include 'Views\Parciales\Footer.php'; ?>w