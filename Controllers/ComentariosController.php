<?php
require_once 'Models/ComentariosModel.php';

class ComentariosController {
    private $comentariosModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->comentariosModel = new ComentariosModel($db);
    }

    public function mostrarComentario($id_curso, $id_usuario) {
        return $this->comentariosModel->obtenerComentario($id_curso, $id_usuario);
    }

    public function enviarComentario($id_curso, $id_usuario, $comentario, $calificacion) {
        return $this->comentariosModel->guardarComentario($id_curso, $id_usuario, $comentario, $calificacion);
    }
}
