<?php
class VistasController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getCursosMasVendidos() {
        $query = "SELECT * FROM CursosMasVendidos LIMIT 5"; 
        return $this->executeQuery($query);
    }

    // Obtener cursos recientes (solo activos)
    public function getCursosRecientes() {
        $query = "SELECT * FROM CursosRecientes LIMIT 5"; 
        return $this->executeQuery($query);
    }

    public function getCursosMejorCalificados() {
        $query = "SELECT * FROM CursosMejorCalificados LIMIT 5"; 
        return $this->executeQuery($query);
    }

    public function getCursosActivos() {
        $query = "SELECT * FROM CursosActivos";
        return $this->executeQuery($query);
    }

    private function executeQuery($query) {
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
