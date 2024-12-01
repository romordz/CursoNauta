<?php
class ReportesModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection(); // Obtener la conexión a la base de datos
    }

    public function obtenerReporteInstructores(): array {
        $stmt = $this->db->prepare("CALL ObtenerReporteInstructores()"); // Llamamos al procedimiento
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retornamos los resultados
    }

    // Obtener reporte de estudiantes llamando al procedimiento almacenado
    public function obtenerReporteEstudiantes(): array {
        $stmt = $this->db->prepare("CALL ObtenerReporteEstudiantes()"); // Llamamos al procedimiento
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retornamos los resultados
    }
}
