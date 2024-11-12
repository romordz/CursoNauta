<?php
class ReportesModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection(); // Obtener la conexión a la base de datos
    }

    public function obtenerReporteInstructores(): array {
        $query = "SELECT * FROM ReporteInstructores";
        $stmt = $this->db->query($query); // Ejecutar la consulta
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener los resultados
    }

    public function obtenerReporteEstudiantes(): array {
        $query = "SELECT * FROM ReporteEstudiantes";
        $stmt = $this->db->query($query); // Ejecutar la consulta
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener los resultados
    }
}
