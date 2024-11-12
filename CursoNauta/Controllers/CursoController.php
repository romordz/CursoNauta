<?php
require_once 'Models/CursoModel.php';

class CursoController
{
    private $cursoModel;

    public function __construct()
    {
        $this->cursoModel = new CursoModel();
    }
    public function agregarCurso()
    {
        session_start();
        $titulo = $_POST['course_title'];
        $descripcion = $_POST['course_description'];
        $imagen = isset($_FILES['course_image']['tmp_name']) && is_string($_FILES['course_image']['tmp_name'])
            ? file_get_contents($_FILES['course_image']['tmp_name'])
            : null;

        $costo = $_POST['course_price'];
        $niveles = $_POST['levels'];
        $id_instructor = $_SESSION['user_id'];
        $id_categoria = $_POST['course_category'];

        // Insertar el curso
        $id_curso = $this->cursoModel->insertarCurso($titulo, $descripcion, $imagen, $costo, $niveles, $id_instructor, $id_categoria);

        if ($id_curso) {
            // Insertar cada nivel
            for ($i = 1; $i <= $niveles; $i++) {
                $titulo_nivel = $_POST["level_title_$i"];
                $video = isset($_FILES["level_video_$i"]['tmp_name']) && is_string($_FILES["level_video_$i"]['tmp_name'])
                    ? file_get_contents($_FILES["level_video_$i"]['tmp_name'])
                    : null;

                $contenido = $_POST["level_content_$i"];

                $archivos = isset($_FILES["level_attachments_$i"]['tmp_name']) && is_string($_FILES["level_attachments_$i"]['tmp_name'])
                    ? file_get_contents($_FILES["level_attachments_$i"]['tmp_name'])
                    : null;
                // print_r($_FILES);

                $costo_nivel = $_POST['level_price'] ?: 0; // Puedes usar un valor predeterminado

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
        return $this->cursoModel->obtenerCursos();
    }

    public function cambiarEstadoCurso()
    {
        if (isset($_POST['idCurso']) && isset($_POST['nuevoEstado'])) {
            $idCurso = $_POST['idCurso'];
            $nuevoEstado = $_POST['nuevoEstado'];
            $this->cursoModel->actualizarEstadoCurso($idCurso, $nuevoEstado);
        }
    }

    public function obtenerCursoPorId($idCurso)
    {
        return $this->cursoModel->obtenerCursoPorId($idCurso);
    }

    public function obtenerNivelesPorCurso($idCurso)
    {
        return $this->cursoModel->obtenerNivelesPorCurso($idCurso);
    }
    public function obtenerValoracionPromedio($idCurso)
    {
        return $this->cursoModel->obtenerValoracionPromedio($idCurso);
    }
    
    public function obtenerComentarios($idCurso)
    {
        return $this->cursoModel->obtenerComentarios($idCurso);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controlador = new CursoController();

    if (isset($_POST['action']) && $_POST['action'] === 'agregarCurso') {
        $controlador->agregarCurso();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'cambiarEstadoCurso') {
        $controlador->cambiarEstadoCurso();
    }
}
