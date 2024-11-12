<?php
class MensajesModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerInstructoresConMensajes($id_emisor) {
        $query = "
            SELECT DISTINCT u.idUsuario, u.nombre, u.foto_avatar
            FROM Usuarios u
            JOIN Mensajes m ON (u.idUsuario = m.id_receptor AND m.id_emisor = :id_emisor)
                           OR (u.idUsuario = m.id_emisor AND m.id_receptor = :id_emisor)
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id_emisor", $id_emisor);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function obtenerMensajesEntreUsuarios($id_emisor, $id_receptor) {
        $query = "
            SELECT m.*, u.foto_avatar, u.nombre 
            FROM Mensajes m
            JOIN Usuarios u ON u.idUsuario = m.id_emisor
            WHERE (m.id_emisor = :id_emisor AND m.id_receptor = :id_receptor) 
               OR (m.id_emisor = :id_receptor AND m.id_receptor = :id_emisor)
            ORDER BY m.fecha_hora ASC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id_emisor", $id_emisor);
        $stmt->bindParam(":id_receptor", $id_receptor);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function iniciarChatSiNoExiste($id_emisor, $id_receptor) {
        // Verificar si ya existe un chat entre el usuario y el instructor
        $query = "SELECT * FROM Mensajes WHERE (id_emisor = :id_emisor AND id_receptor = :id_receptor) OR (id_emisor = :id_receptor AND id_receptor = :id_emisor)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id_emisor", $id_emisor);
        $stmt->bindParam(":id_receptor", $id_receptor);
        $stmt->execute();

        // Crear un mensaje de bienvenida si no existe un chat previo
        if ($stmt->rowCount() == 0) {
            $query = "INSERT INTO Mensajes (id_emisor, id_receptor, mensaje) VALUES (:id_emisor, :id_receptor, 'Hola, este es el inicio de nuestra conversación')";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_emisor", $id_emisor);
            $stmt->bindParam(":id_receptor", $id_receptor);
            $stmt->execute();
        }
    }
    public function enviarMensaje($id_emisor, $id_receptor, $mensaje) {
        $query = "INSERT INTO Mensajes (id_emisor, id_receptor, mensaje) VALUES (:id_emisor, :id_receptor, :mensaje)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id_emisor", $id_emisor);
        $stmt->bindParam(":id_receptor", $id_receptor);
        $stmt->bindParam(":mensaje", $mensaje);
        return $stmt->execute();
    }
    
}

