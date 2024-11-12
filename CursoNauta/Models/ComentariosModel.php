<?php
class ComentariosModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerComentario($id_curso, $id_usuario) {
        $query = "SELECT comentario, calificacion, fecha_comentario 
                  FROM Comentarios 
                  WHERE id_curso = :id_curso AND id_usuario = :id_usuario AND eliminado = 0";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_curso', $id_curso);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna los datos del comentario si existe
    }

    public function guardarComentario($id_curso, $id_usuario, $comentario, $calificacion) {
        $query = "INSERT INTO Comentarios (id_curso, id_usuario, comentario, calificacion) 
                  VALUES (:id_curso, :id_usuario, :comentario, :calificacion)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_curso', $id_curso);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':comentario', $comentario);
        $stmt->bindParam(':calificacion', $calificacion);

        return $stmt->execute(); // Retorna true si se inserta correctamente
    }
}
