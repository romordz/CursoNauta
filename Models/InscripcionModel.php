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
    public function obtenerCursosInscritos($idUsuario)
    {
        try {
            $stmt = $this->conn->prepare("CALL ObtenerCursosInscritos(:id_usuario)");
            $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener los cursos inscritos: " . $e->getMessage();
            return [];
        }
    }
    
    public function registrarVenta($idCurso, $idUsuario, $precioPagado, $formaPago)
    {
        try {
            $stmt = $this->conn->prepare("CALL RegistrarVenta(:id_curso, :id_usuario, :precio_pagado, :forma_pago)");
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
            $query = "SELECT inscripcionYaRegistrada(:id_curso, :id_usuario) AS yaRegistrada";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $idCurso, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['yaRegistrada'] > 0; // Retorna true si ya existe una inscripción, false si no
        } catch (PDOException $e) {
            echo "Error al verificar la inscripción: " . $e->getMessage();
            return false;
        }
    }
    

    public function obtenerDatosCertificado($id_curso, $id_usuario)
    {
        $stmt = $this->conn->prepare("CALL ObtenerDatosCertificado(:id_curso, :id_usuario)");
        $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getCategoriasActivas()
    {
        try {
            $stmt = $this->conn->prepare("CALL ObtenerCategoriasActivas()");
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
