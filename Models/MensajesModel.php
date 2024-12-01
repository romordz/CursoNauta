<?php
class MensajesModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerInstructoresConMensajes($id_emisor) {
        try {
            $stmt = $this->db->prepare("CALL ObtenerInstructoresConMensajes(:id_emisor)");
            $stmt->bindParam(":id_emisor", $id_emisor, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener instructores con mensajes: " . $e->getMessage();
            return null;
        }
    }
    public function obtenerMensajesEntreUsuarios($id_emisor, $id_receptor) {
        try {
            $stmt = $this->db->prepare("CALL ObtenerMensajesEntreUsuarios(:id_emisor, :id_receptor)");
            $stmt->bindParam(":id_emisor", $id_emisor, PDO::PARAM_INT);
            $stmt->bindParam(":id_receptor", $id_receptor, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener mensajes: " . $e->getMessage();
            return null;
        }
    }
    public function iniciarChatSiNoExiste($id_emisor, $id_receptor) {
        try {
            $stmt = $this->db->prepare("CALL IniciarChatSiNoExiste(:id_emisor, :id_receptor)");
            $stmt->bindParam(":id_emisor", $id_emisor, PDO::PARAM_INT);
            $stmt->bindParam(":id_receptor", $id_receptor, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al iniciar chat: " . $e->getMessage();
        }
    }
    public function enviarMensaje($id_emisor, $id_receptor, $mensaje) {
        try {
            $stmt = $this->db->prepare("CALL EnviarMensaje(:id_emisor, :id_receptor, :mensaje)");
            $stmt->bindParam(":id_emisor", $id_emisor, PDO::PARAM_INT);
            $stmt->bindParam(":id_receptor", $id_receptor, PDO::PARAM_INT);
            $stmt->bindParam(":mensaje", $mensaje);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al enviar mensaje: " . $e->getMessage();
            return false;
        }
    }
    
}

