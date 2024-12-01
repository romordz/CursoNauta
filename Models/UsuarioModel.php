<?php
class UsuarioModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerUsuarios() {
        try {
            $stmt = $this->conn->prepare("CALL ObtenerUsuarios()");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener usuarios: " . $e->getMessage();
            return null;
        }
    }

    public function cambiarEstadoUsuario($idUsuario, $nuevoEstado) {
        $query = "CALL CambiarEstadoUsuario(:idUsuario, :nuevoEstado)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_BOOL);
        return $stmt->execute();
    }
    

}

