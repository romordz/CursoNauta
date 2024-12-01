<?php
class ComentariosModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerComentario($id_curso, $id_usuario)
    {
        try {
            $stmt = $this->db->prepare("CALL ObtenerComentario(:id_curso, :id_usuario)");
            $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener comentario: " . $e->getMessage();
            return null;
        }
    }

    public function guardarComentario($id_curso, $id_usuario, $comentario, $calificacion)
    {
        try {
            $stmt = $this->db->prepare("CALL GuardarComentario(:id_curso, :id_usuario, :comentario, :calificacion)");
            $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':comentario', $comentario);
            $stmt->bindParam(':calificacion', $calificacion, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al guardar comentario: " . $e->getMessage();
            return false;
        }
    }
}
