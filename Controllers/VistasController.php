<?php
class VistasController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getCursosMasVendidos() {
        $query = "SELECT * FROM cursosmasvendidos LIMIT 5"; 
        return $this->executeQuery($query);
    }

    public function getCursosRecientes() {
        $query = "SELECT * FROM cursosrecientes LIMIT 5"; 
        return $this->executeQuery($query);
    }

    public function getCursosMejorCalificados() {
        $query = "SELECT * FROM cursosmejorcalificados LIMIT 5"; 
        return $this->executeQuery($query);
    }

    public function getCursosActivos() {
        $query = "SELECT * FROM cursosactivos";
        return $this->executeQuery($query);
    }

    private function executeQuery($query) {
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}