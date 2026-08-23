<?php
require_once 'Database.php';

class ProgresoModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function marcarNivelCompletado($idUsuario, $idNivel, $idCurso)
    {
        $stmt = $this->conn->prepare("CALL MarcarNivelCompletado(:id_usuario, :id_nivel, :id_curso)");
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_nivel', $idNivel, PDO::PARAM_INT);
        $stmt->bindParam(':id_curso', $idCurso, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerNivelesCompletados($idUsuario, $idCurso)
    {
        $stmt = $this->conn->prepare("CALL ObtenerNivelesCompletados(:id_usuario, :id_curso)");
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_curso', $idCurso, PDO::PARAM_INT);
        $stmt->execute();
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'id_nivel');
    }

    public function obtenerProgresoActual($idUsuario, $idCurso)
    {
        $stmt = $this->conn->prepare("SELECT progreso FROM inscripciones WHERE id_usuario = :id_usuario AND id_curso = :id_curso");
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_curso', $idCurso, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['progreso'] ?? 0;
    }
}