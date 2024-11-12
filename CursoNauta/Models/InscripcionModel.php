<?php
require_once 'Models\Database.php';

class InscripcionModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function registrarInscripcion($idCurso, $idUsuario)
    {
        try {
            $stmt = $this->conn->prepare("CALL RegistrarInscripcion(:id_curso, :id_usuario)");
            $stmt->bindParam(':id_curso', $idCurso, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error al registrar la inscripción: " . $e->getMessage();
            return false;
        }
    }
    public function obtenerCursosInscritos($idUsuario) {
        try {
            $query = "
                SELECT i.*, c.titulo AS curso_titulo, cat.nombre_categoria AS categoria
                FROM Inscripciones i
                JOIN Cursos c ON i.id_curso = c.id_curso
                JOIN Categorias cat ON c.id_categoria = cat.id_categoria
                WHERE i.id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener los cursos inscritos: " . $e->getMessage();
            return [];
        }
    }
    
    public function registrarVenta($idCurso, $idUsuario, $precioPagado, $formaPago) {
        try {
            $query = "
                INSERT INTO Ventas (id_curso, id_usuario, precio_pagado, forma_pago)
                VALUES (:id_curso, :id_usuario, :precio_pagado, :forma_pago)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $idCurso, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(':precio_pagado', $precioPagado, PDO::PARAM_STR);
            $stmt->bindParam(':forma_pago', $formaPago, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error al registrar la venta: " . $e->getMessage();
            return false;
        }
    }
    public function inscripcionYaRegistrada($idCurso, $idUsuario) {
        try {
            $query = "SELECT COUNT(*) FROM Inscripciones WHERE id_curso = :id_curso AND id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $idCurso, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0; // Retorna true si ya existe una inscripción, false si no
        } catch (PDOException $e) {
            echo "Error al verificar la inscripción: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerDatosCertificado($id_curso, $id_usuario) {
        $query = "SELECT 
                    u.nombre AS nombre_estudiante,
                    c.titulo AS nombre_curso,
                    i.fecha_terminacion,
                    instructor.nombre AS nombre_instructor
                  FROM Inscripciones i
                  JOIN Usuarios u ON i.id_usuario = u.idUsuario
                  JOIN Cursos c ON i.id_curso = c.id_curso
                  JOIN Usuarios instructor ON c.id_instructor = instructor.idUsuario
                  WHERE i.id_curso = :id_curso AND i.id_usuario = :id_usuario AND i.completado = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_curso', $id_curso);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getCategoriasActivas() {
        try {
            $query = "SELECT id_categoria, nombre_categoria FROM Categorias WHERE activo = TRUE";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener categorías activas: " . $e->getMessage();
            return [];
        }
    }
    
    public function buscarKardexDinamico($categoriaID, $estado, $fechaInicio, $fechaFin, $usuarioID) {
        try {
            $query = "CALL BuscarKardexDinamico(:categoriaID, :estado, :fechaInicio, :fechaFin, :usuarioID)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':categoriaID', $categoriaID, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
            $stmt->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);
            $stmt->bindParam(':usuarioID', $usuarioID, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al buscar cursos en el Kardex: " . $e->getMessage();
            return [];
        }
    }
    

}
