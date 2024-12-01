<?php
require_once 'Models/CursoModel.php';

class CursoController
{
    private $cursoModel;

    public function __construct()
    {
        $this->cursoModel = new CursoModel();
    }

    private function iniciarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function agregarCurso()
    {
        $this->iniciarSesion();

        if (
            empty($_POST['course_title']) ||
            empty($_POST['course_description']) ||
            empty($_POST['course_price']) ||
            empty($_POST['levels']) ||
            empty($_POST['course_category'])
        ) {
            echo "Faltan campos obligatorios.";
            return;
        }

        $titulo = $_POST['course_title'];
        $descripcion = $_POST['course_description'];
        $imagen = isset($_FILES['course_image']['tmp_name']) && !empty($_FILES['course_image']['tmp_name'])
            ? file_get_contents($_FILES['course_image']['tmp_name'])
            : null;

        $costo = $_POST['course_price'];
        $niveles = (int) $_POST['levels'];
        $id_instructor = $_SESSION['user_id'];
        $id_categoria = $_POST['course_category'];

        $id_curso = $this->cursoModel->insertarCurso($titulo, $descripcion, $imagen, $costo, $niveles, $id_instructor, $id_categoria);

        if ($id_curso) {
            for ($i = 1; $i <= $niveles; $i++) {
                $titulo_nivel = $_POST["level_title_$i"];
                $video = isset($_FILES["level_video_$i"]['tmp_name']) && !empty($_FILES["level_video_$i"]['tmp_name'])
                    ? file_get_contents($_FILES["level_video_$i"]['tmp_name'])
                    : null;

                $contenido = $_POST["level_content_$i"];
                $archivos = isset($_FILES["level_attachments_$i"]['tmp_name']) && !empty($_FILES["level_attachments_$i"]['tmp_name'])
                    ? file_get_contents($_FILES["level_attachments_$i"]['tmp_name'])
                    : null;

                $costo_nivel = isset($_POST["level_price_$i"]) ? $_POST["level_price_$i"] : 0;

                $this->cursoModel->insertarNivel($id_curso, $i, $titulo_nivel, $video, $contenido, $archivos, $costo_nivel);
            }

            header("Location: index.php?page=Ventas");
            exit;
        } else {
            echo "Error al agregar el curso.";
        }
    }

    public function mostrarCursos()
    {
        $this->iniciarSesion();
        $id_instructor = $_SESSION['user_id'];
        return $this->cursoModel->obtenerCursosPorInstructor($id_instructor);
    }

    public function obtenerTotalIngresos()
    {
        $this->iniciarSesion();
        $id_instructor = $_SESSION['user_id'];
        return $this->cursoModel->obtenerTotalIngresos($id_instructor);
    }

    public function cambiarEstadoCurso()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['idCurso']) && isset($data['nuevoEstado'])) {
                $idCurso = (int) $data['idCurso'];
                $nuevoEstado = (int) $data['nuevoEstado'];
                $result = $this->cursoModel->actualizarEstadoCurso($idCurso, $nuevoEstado);

                echo json_encode(['success' => $result]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos no válidos']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function obtenerCursoPorId($idCurso)
    {
        return $this->cursoModel->obtenerCursoPorId((int) $idCurso);
    }

    public function obtenerNivelesPorCurso($idCurso)
    {
        return $this->cursoModel->obtenerNivelesPorCurso((int) $idCurso);
    }

    public function obtenerValoracionPromedio($idCurso)
    {
        return $this->cursoModel->obtenerValoracionPromedio((int) $idCurso);
    }

    public function obtenerComentarios($idCurso)
    {
        return $this->cursoModel->obtenerComentarios((int) $idCurso);
    }

    public function editarCurso()
{
    $this->iniciarSesion();

    // Debug: Verifica si el ID es válido
    if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
        echo "Debug: ID de curso no válido.<br>";
        exit;
    }

    $id_curso = (int) $_GET['id'];
    echo "Debug: ID del curso recibido: $id_curso<br>";

    // Debug: Verifica si se reciben los datos del formulario
    if (!isset($_POST['course_title']) || !isset($_POST['course_description'])) {
        echo "Debug: Datos del formulario no recibidos.<br>";
        exit;
    }

    $titulo = $_POST['course_title'];
    $descripcion = $_POST['course_description'];
    $imagen = isset($_FILES['course_image']['tmp_name']) && !empty($_FILES['course_image']['tmp_name'])
        ? file_get_contents($_FILES['course_image']['tmp_name'])
        : null; // Enviar NULL si no se actualiza la imagen

    echo "Debug: Datos del formulario - Título: $titulo, Descripción: $descripcion<br>";

    $costo = $_POST['course_price'];
    $id_categoria = $_POST['course_category'];

    // Debug: Verifica antes de actualizar
    echo "Debug: Preparando para actualizar el curso.<br>";

    $resultado = $this->cursoModel->actualizarCurso($id_curso, $titulo, $descripcion, $imagen, $costo, $id_categoria);

    // Debug: Resultado de la actualización
    if ($resultado) {
        echo "Debug: Curso actualizado exitosamente.<br>";
    } else {
        echo "Debug: Falló la actualización del curso.<br>";
        exit;
    }

    // Debug: Iteración sobre niveles
    $niveles = (int) $_POST['levels'];
    echo "Debug: Número de niveles a actualizar: $niveles<br>";
    for ($i = 1; $i <= $niveles; $i++) {
        echo "Debug: Procesando nivel $i<br>";
        if (!isset($_POST["level_title_$i"]) || !isset($_POST["level_content_$i"])) {
            echo "Debug: Datos faltantes para el nivel $i<br>";
            continue;
        }
        $id_nivel = $_POST["level_id_$i"];
        $titulo_nivel = $_POST["level_title_$i"];
        $contenido = $_POST["level_content_$i"];
        $costo_nivel = $_POST["level_price_$i"];

        echo "Debug: Nivel $i - Título: $titulo_nivel, Contenido: $contenido, Costo: $costo_nivel<br>";

        $this->cursoModel->actualizarNivel($id_nivel, $titulo_nivel, $contenido, $costo_nivel);
    }

    // Redirige después de la actualización
    echo '<script>window.location.href = "index.php?page=Ventas";</script>';
    exit;
}

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controlador = new CursoController();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'agregarCurso':
                $controlador->agregarCurso();
                break;
            case 'toggle':
                $controlador->cambiarEstadoCurso();
                break;
            case 'editarCurso':
                $controlador->editarCurso();
                break;
        }
    }
}
