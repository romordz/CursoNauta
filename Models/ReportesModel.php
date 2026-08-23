<?php
class ReportesModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function obtenerReporteInstructores(): array {
        $stmt = $this->db->prepare("CALL ObtenerReporteInstructores()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerReporteEstudiantes(): array {
        $stmt = $this->db->prepare("CALL ObtenerReporteEstudiantes()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}