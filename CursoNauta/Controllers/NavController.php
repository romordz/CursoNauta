<?php

class NavController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Obtener categorías activas
    public function getCategoriasActivas() {
        $query = "SELECT id_categoria, nombre_categoria FROM CategoriasActivas";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener instructores activos
    public function getInstructoresActivos() {
        $query = "SELECT idUsuario, nombre FROM InstructoresActivos";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar cursos por palabra clave
    public function buscarCursosPorPalabraClave($palabraClave) {
        $stmt = $this->db->getConnection()->prepare("CALL BuscarCursosPorPalabraClave(:palabraClave)");
        $stmt->bindParam(':palabraClave', $palabraClave, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar cursos con filtros dinámicos (categoría, instructor, rango de fechas)
    public function buscarCursosDinamico($categoriaID = null, $instructorID = null, $fechaInicio = null, $fechaFin = null) {
        $stmt = $this->db->getConnection()->prepare("CALL BuscarCursosDinamico(:categoriaID, :instructorID, :fechaInicio, :fechaFin)");
    
        $stmt->bindParam(':categoriaID', $categoriaID, PDO::PARAM_INT);
        $stmt->bindParam(':instructorID', $instructorID, PDO::PARAM_INT);
    
        // Verifica si las fechas son vacías y asigna NULL en su lugar
        $fechaInicio = empty($fechaInicio) ? null : $fechaInicio;
        $fechaFin = empty($fechaFin) ? null : $fechaFin;
    
        $stmt->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
        $stmt->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los cursos activos
    public function getCursosActivos() {
        $query = "SELECT * FROM CursosActivos";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
