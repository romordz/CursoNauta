<?php
require_once 'Models\Database.php';

class CursoModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function insertarCurso($titulo, $descripcion, $imagen, $costo, $niveles, $id_instructor, $id_categoria)
    {
        $query = "CALL InsertarCurso(:titulo, :descripcion, :imagen, :costo, :niveles, :id_instructor, :id_categoria, @p_id_curso)";
        $stmt = $this->conn->prepare($query);

        // Vincular parámetros
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_LOB);
        $stmt->bindParam(':costo', $costo);
        $stmt->bindParam(':niveles', $niveles);
        $stmt->bindParam(':id_instructor', $id_instructor);
        $stmt->bindParam(':id_categoria', $id_categoria);

        if ($stmt->execute()) {
            $stmt->closeCursor();
            $result = $this->conn->query("SELECT @p_id_curso AS id_curso")->fetch(PDO::FETCH_ASSOC);
            return $result['id_curso'];
        }
        return false;
    }

    public function insertarNivel($id_curso, $numero_nivel, $titulo_nivel, $video, $contenido, $archivos, $costo)
    {
        $query = "CALL InsertarNivel(:id_curso, :numero_nivel, :titulo_nivel, :video, :contenido, :archivos, :costo)";
        $stmt = $this->conn->prepare($query);

        // Vincular parámetros
        $stmt->bindParam(':id_curso', $id_curso);
        $stmt->bindParam(':numero_nivel', $numero_nivel);
        $stmt->bindParam(':titulo_nivel', $titulo_nivel);
        $stmt->bindParam(':video', $video, PDO::PARAM_LOB);
        $stmt->bindParam(':contenido', $contenido);
        $stmt->bindParam(':archivos', $archivos, PDO::PARAM_LOB);
        $stmt->bindParam(':costo', $costo);

        return $stmt->execute();
    }

    public function actualizarNivel($id_nivel, $titulo_nivel, $contenido, $costo)
{
    $query = "CALL ActualizarNivel(:id_nivel, :titulo_nivel, :contenido, :costo)";
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':id_nivel', $id_nivel);
    $stmt->bindParam(':titulo_nivel', $titulo_nivel);
    $stmt->bindParam(':contenido', $contenido);
    $stmt->bindParam(':costo', $costo);

    return $stmt->execute();
}


    public function obtenerCursos()
    {
        $stmt = $this->conn->prepare("CALL sp_obtener_cursos()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarEstadoCurso($idCurso, $nuevoEstado)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_actualizar_estado_curso(:id_curso, :activo)");
            $stmt->bindParam(':id_curso', $idCurso, PDO::PARAM_INT);
            $stmt->bindParam(':activo', $nuevoEstado, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function obtenerCursoPorId($idCurso)
    {
        $stmt = $this->conn->prepare("CALL sp_obtener_curso_por_id(:idCurso)");
        $stmt->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerCursosPorInstructor($id_instructor)
    {
        $stmt = $this->conn->prepare("CALL sp_obtener_cursos_por_instructor(:id_instructor)");
        $stmt->bindParam(':id_instructor', $id_instructor);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTotalIngresos($id_instructor)
    {
        $stmt = $this->conn->prepare("CALL sp_obtener_total_ingresos(:id_instructor)");
        $stmt->bindParam(':id_instructor', $id_instructor);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_ingresos'] ?: 0;
    }

    public function obtenerNivelesPorCurso($idCurso)
    {
        $stmt = $this->conn->prepare("CALL sp_obtener_niveles_por_curso(:idCurso)");
        $stmt->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerValoracionPromedio($idCurso)
    {
        $stmt = $this->conn->prepare("CALL sp_obtener_valoracion_promedio(:idCurso)");
        $stmt->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['promedio'] ?? 0;
    }

    public function obtenerComentarios($idCurso)
    {
        $stmt = $this->conn->prepare("CALL sp_obtener_comentarios(:idCurso)");
        $stmt->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // NUEVO MÉTODO PARA ACTUALIZAR CURSO
    public function actualizarCurso($id_curso, $titulo, $descripcion, $imagen, $costo, $id_categoria)
    {
        $query = "CALL ActualizarCurso(:id_curso, :titulo, :descripcion, :imagen, :costo, :id_categoria)";
        $stmt = $this->conn->prepare($query);

        // Vincular parámetros
        $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_LOB);
        $stmt->bindParam(':costo', $costo);
        $stmt->bindParam(':id_categoria', $id_categoria);

        return $stmt->execute();
    }
}
