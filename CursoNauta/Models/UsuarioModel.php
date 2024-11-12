<?php
class UsuarioModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerUsuarios() {
        $query = "SELECT idUsuario, nombre, correo, fecha_registro, activo, id_rol FROM Usuarios";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cambiarEstadoUsuario($idUsuario, $nuevoEstado) {
        $query = "CALL CambiarEstadoUsuario(:idUsuario, :nuevoEstado)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_BOOL);
        return $stmt->execute();
    }
    

}

