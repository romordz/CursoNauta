<?php
require_once 'Models/CursoModel.php';
require_once 'Models/CloudinaryUploader.php';

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
        $imagen_url = null;
        if (isset($_FILES['course_image']['tmp_name']) && !empty($_FILES['course_image']['tmp_name'])) {
            $uploader = new CloudinaryUploader();
            $imagen_url = $uploader->subirImagen($_FILES['course_image']['tmp_name']);
        }

        $costo = $_POST['course_price'];
        $niveles = (int) $_POST['levels'];
        $id_instructor = $_SESSION['user_id'];
        $id_categoria = $_POST['course_category'];

        $this->cursoModel->beginTransaction();

        try {
            $id_curso = $this->cursoModel->insertarCurso($titulo, $descripcion, $imagen_url, $costo, $niveles, $id_instructor, $id_categoria);

            if (!$id_curso) {
                throw new Exception("Error al agregar el curso.");
            }

            for ($i = 1; $i <= $niveles; $i++) {
                if (empty($_POST["level_title_$i"]) || !isset($_POST["level_content_$i"])) {
                    throw new Exception("Faltan datos en el nivel $i.");
                }

                $titulo_nivel = $_POST["level_title_$i"];
                $contenido = $_POST["level_content_$i"];

                $video_url = null;
                if (isset($_FILES["level_video_$i"]['tmp_name']) && !empty($_FILES["level_video_$i"]['tmp_name'])) {
                    $tmpPath = $_FILES["level_video_$i"]['tmp_name'];
                    $allowedTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm'];
                    $fileType = mime_content_type($tmpPath);

                    if (!in_array($fileType, $allowedTypes)) {
                        throw new Exception("El archivo del nivel $i no es un video válido.");
                    }

                    $uploader = new CloudinaryUploader();
                    $video_url = $uploader->subirVideo($tmpPath);
                }

                $archivo_url = null;
                if (isset($_FILES["level_attachments_$i"]['tmp_name']) && !empty($_FILES["level_attachments_$i"]['tmp_name'])) {
                    $uploader = new CloudinaryUploader();
                    $archivo_url = $uploader->subirArchivo($_FILES["level_attachments_$i"]['tmp_name']);
                }

                $costo_nivel = isset($_POST["level_price_$i"]) ? $_POST["level_price_$i"] : 0;

                $insertado = $this->cursoModel->insertarNivel($id_curso, $i, $titulo_nivel, $video_url, $contenido, $archivo_url, $costo_nivel);

                if (!$insertado) {
                    throw new Exception("Error al insertar el nivel $i.");
                }
            }

            $this->cursoModel->commit();
            header("Location: index.php?page=Ventas");
            exit;

        } catch (Exception $e) {
            $this->cursoModel->rollBack();
            echo "Error: " . $e->getMessage();
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
        $data = json_decode(file_get_contents('php://input'), true);

        $idCurso = $data['idCurso'] ?? $_POST['id_curso'] ?? null;
        $nuevoEstado = $data['nuevoEstado'] ?? $_POST['nuevoEstado'] ?? null;

        if ($idCurso !== null && $nuevoEstado !== null) {
            $idCurso = (int) $idCurso;
            $nuevoEstado = (int) $nuevoEstado;
            $result = $this->cursoModel->actualizarEstadoCurso($idCurso, $nuevoEstado);

            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos no validos']);
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

        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "Debug: ID de curso no válido.<br>";
            exit;
        }

        $id_curso = (int) $_GET['id'];
        echo "Debug: ID del curso recibido: $id_curso<br>";

        if (!isset($_POST['course_title']) || !isset($_POST['course_description'])) {
            echo "Debug: Datos del formulario no recibidos.<br>";
            exit;
        }

        $titulo = $_POST['course_title'];
        $descripcion = $_POST['course_description'];
        $imagen_url = null;
        if (isset($_FILES['course_image']['tmp_name']) && !empty($_FILES['course_image']['tmp_name'])) {
            $uploader = new CloudinaryUploader();
            $imagen_url = $uploader->subirImagen($_FILES['course_image']['tmp_name']);
        }

        $costo = $_POST['course_price'];
        $id_categoria = $_POST['course_category'];

        $resultado = $this->cursoModel->actualizarCurso($id_curso, $titulo, $descripcion, $imagen_url, $costo, $id_categoria);

        $niveles = (int) $_POST['levels'];
        for ($i = 1; $i <= $niveles; $i++) {
            if (!isset($_POST["level_title_$i"]) || !isset($_POST["level_content_$i"])) {
                continue;
            }
            $id_nivel = $_POST["level_id_$i"];
            $titulo_nivel = $_POST["level_title_$i"];
            $contenido = $_POST["level_content_$i"];
            $costo_nivel = $_POST["level_price_$i"];

            $this->cursoModel->actualizarNivel($id_nivel, $titulo_nivel, $contenido, $costo_nivel);
        }

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
