<?php
require_once __DIR__ . '/../Models/ComentariosModel.php';
require_once __DIR__ . '/../Models/ProgresoModel.php';
require_once __DIR__ . '/../Models/Database.php';

class ComentariosController {
    private $comentariosModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->comentariosModel = new ComentariosModel($db);
    }

    public function mostrarComentario($id_curso, $id_usuario) {
        return $this->comentariosModel->obtenerComentario($id_curso, $id_usuario);
    }

    public function enviarComentario($id_curso, $id_usuario, $comentario, $calificacion) {
        return $this->comentariosModel->guardarComentario($id_curso, $id_usuario, $comentario, $calificacion);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'enviarComentario') {
    header('Content-Type: application/json');

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'No autenticado']);
        exit;
    }

    $idUsuario = $_SESSION['user_id'];
    $idCurso = (int) ($_POST['id_curso'] ?? 0);
    $comentario = trim($_POST['comentario'] ?? '');
    $calificacion = (int) ($_POST['calificacion'] ?? 0);

    if ($idCurso <= 0 || $comentario === '' || $calificacion < 1 || $calificacion > 5) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }

    // Validación crítica: el progreso real se verifica en el servidor, no solo en el frontend
    $progresoModel = new ProgresoModel();
    $progreso = $progresoModel->obtenerProgresoActual($idUsuario, $idCurso);

    if ($progreso < 100) {
        echo json_encode(['success' => false, 'message' => 'Debes completar el curso para comentar']);
        exit;
    }

    $controller = new ComentariosController();
    $resultado = $controller->enviarComentario($idCurso, $idUsuario, $comentario, $calificacion);

    echo json_encode(['success' => (bool) $resultado]);
    exit;
}