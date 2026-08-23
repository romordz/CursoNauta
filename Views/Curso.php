<?php include 'Views/Parciales/Head.php'; ?>
<link rel="stylesheet" href="Views/css/SCurso.css">
<?php include 'Views/Parciales/Nav.php'; ?>

<?php
require_once 'Controllers/CursoController.php';
require_once 'Models/InscripcionModel.php';
require_once 'Models/ProgresoModel.php';
require_once 'Controllers/ComentariosController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

$idUsuario = $_SESSION['user_id'] ?? null;
$inscripcionModel = new InscripcionModel();
$yaComprado = $idUsuario ? $inscripcionModel->inscripcionYaRegistrada($idCurso, $idUsuario) : false;

if ($yaComprado) {
    $conn = (new Database())->getConnection();
    $stmt = $conn->prepare("UPDATE inscripciones SET fecha_ultimo_acceso = NOW() WHERE id_usuario = :id_usuario AND id_curso = :id_curso");
    $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_curso', $idCurso, PDO::PARAM_INT);
    $stmt->execute();
}

$progresoModel = new ProgresoModel();
$progresoActual = 0;
$nivelesCompletados = [];
if ($yaComprado) {
    $progresoActual = $progresoModel->obtenerProgresoActual($idUsuario, $idCurso);
    $nivelesCompletados = $progresoModel->obtenerNivelesCompletados($idUsuario, $idCurso);
}
$yaComento = false;
if ($yaComprado && $idUsuario) {
    $comentariosController = new ComentariosController();
    $miComentario = $comentariosController->mostrarComentario($idCurso, $idUsuario);
    $yaComento = !empty($miComentario);
}
?>

<div class="course-container">
    <div class="course-header" style="background-image: url('<?php echo htmlspecialchars($curso['imagen_url']); ?>');">
        <h1 class="course-title"><?php echo htmlspecialchars($curso['titulo']); ?></h1>
        <p class="course-category">Categoría: <?php echo htmlspecialchars($curso['nombre_categoria']); ?></p>
        <p class="course-category"><strong>Creador:</strong> <?php echo htmlspecialchars($curso['nombre_creador']); ?>
        </p>
        <a href="index.php?page=Mensajes&user_id=<?php echo $curso['id_instructor']; ?>"
            title="Enviar mensaje al creador" style="margin-left: 5px; font-size: 1.5em;">📧</a>
    </div>

    <div class="course-description">
        <p><?php echo htmlspecialchars($curso['descripcion']); ?></p>
    </div>

    <div class="course-content">
        <div class="video-and-topics">
            <div class="video-section">
                <?php if ($yaComprado): ?>
                    <video id="course-video" controls data-id-curso="<?php echo $idCurso; ?>"></video>
                    <h4>Tu progreso: <?php echo round($progresoActual); ?>%</h4>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo round($progresoActual); ?>%;"></div>
                    </div>
                <?php else: ?>
                    <div class="video-locked">
                        <p>Compra este curso para acceder al contenido.</p>
                    </div>
                <?php endif; ?>
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
                                <?php if ($yaComprado && in_array($nivel['id_nivel'], $nivelesCompletados)): ?>
                                    <span class="completed-check">✅</span>
                                <?php endif; ?>
                                <span class="arrow">▶</span>
                            </button>
                            <ul class="subtopics-list">
                                <li>
                                    <?php if ($yaComprado): ?>
                                        <input type="checkbox" class="subtopic-checkbox"
                                            id="subtopic<?php echo $nivel['id_nivel']; ?>"
                                            <?php echo in_array($nivel['id_nivel'], $nivelesCompletados) ? 'checked' : ''; ?>> 
                                            <label
                                            for="subtopic<?php echo $nivel['id_nivel']; ?>">
                                        <a href="<?php echo htmlspecialchars($nivel['video_url']); ?>" class="subtopic-link"
                                            data-id-nivel="<?php echo $nivel['id_nivel']; ?>">
                                            Ver Video de <?php echo htmlspecialchars($nivel['titulo_nivel']); ?>
                                        </a>
                                        </label>
                                    <?php else: ?>
                                        <span class="locked-item">🔒 Ver Video de
                                            <?php echo htmlspecialchars($nivel['titulo_nivel']); ?></span>
                                    <?php endif; ?>
                                </li>
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
                <?php if (!$yaComprado): ?>
                    <p>Compra este curso para acceder a los recursos.</p>
                <?php else: ?>
                    <?php
                    $hayRecursos = false;
                    foreach ($niveles as $nivel) {
                        if (!empty($nivel['archivo_url'])) {
                            $hayRecursos = true;
                            break;
                        }
                    }
                    ?>
                    <?php if ($hayRecursos): ?>
                        <ul>
                            <?php foreach ($niveles as $nivel): ?>
                                <?php if (!empty($nivel['archivo_url'])): ?>
                                    <li>
                                        <a href="<?php echo htmlspecialchars($nivel['archivo_url']); ?>" target="_blank">
                                            <i class="file-icon">📄</i> Descargar
                                            <?php echo htmlspecialchars($nivel['titulo_nivel']); ?> - Nivel
                                            <?php echo $nivel['numero_nivel']; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No se incluyeron recursos adicionales.</p>
                    <?php endif; ?>
                <?php endif; ?>
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
                <?php if ($yaComprado && $progresoActual >= 100 && !$yaComento): ?>
                    <form id="comment-form" class="comment-form" style="display: block;">
                        <input type="hidden" name="id_curso" value="<?php echo $idCurso; ?>">
                        <label for="calificacion">Calificación:</label>
                        <select id="calificacion" name="calificacion" required>
                            <option value="5">⭐⭐⭐⭐⭐ Excelente</option>
                            <option value="4">⭐⭐⭐⭐ Muy bueno</option>
                            <option value="3">⭐⭐⭐ Bueno</option>
                            <option value="2">⭐⭐ Regular</option>
                            <option value="1">⭐ Malo</option>
                        </select>
                        <label for="comentario">Tu comentario:</label>
                        <textarea id="comentario" name="comentario" rows="3" required></textarea>
                        <button type="submit">Enviar comentario</button>
                    </form>
                <?php elseif ($yaComprado && $progresoActual < 100): ?>
                    <p class="comment-locked-notice">🔒 Solo puedes dejar un comentario cuando hayas completado el 100% del
                        curso.</p>
                <?php elseif ($yaComento): ?>
                    <p class="comment-already-notice">Ya has dejado tu comentario en este curso.</p>
                <?php endif; ?>
                <?php foreach ($comentarios as $comentario): ?>
                    <div class="comment">
                        <div class="user-info">
                            <img src="<?php echo htmlspecialchars($comentario['foto_avatar_url'] ?: 'Recursos/Icon.png'); ?>"
                                alt="Foto del Usuario" class="comment-user-img">
                            <div>
                                <p class="comment-username"><?php echo htmlspecialchars($comentario['nombre_usuario']); ?>
                                </p>
                                <p class="comment-date">
                                    <?php echo htmlspecialchars(date('d/m/Y, H:i', strtotime($comentario['fecha_comentario']))); ?>
                                </p>
                                <?php if (isset($comentario['calificacion'])): ?>
                                    <p class="comment-rating"><?php echo str_repeat('⭐', (int) $comentario['calificacion']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($comentario['eliminado']): ?>
                            <p class="comment-text"><em>(Este comentario ha sido eliminado por el administrador)</em></p>
                        <?php else: ?>
                            <p class="comment-text"><?php echo htmlspecialchars($comentario['comentario']); ?></p>
                        <?php endif; ?>

                        <?php if ($_SESSION['user_role'] == 1 && !$comentario['eliminado']): ?>
                            <button class="delete-btn">Eliminar</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php if (!$yaComprado): ?>
        <div class="course-purchase">
            <h2>Adquiere este curso</h2>
            <p class="course-price"><strong>$<?php echo htmlspecialchars($curso['costo']); ?></strong></p>
            <a href="index.php?page=Pago&idCurso=<?php echo $idCurso; ?>" class="purchase-btn">Comprar Curso</a>
        </div>
    <?php endif; ?>
</div>

<script src="Views/js/JCurso.js"></script>
<?php include 'Views/Parciales/Footer.php'; ?>