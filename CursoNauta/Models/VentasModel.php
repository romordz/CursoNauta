<?php
class VentasModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getCursosVentas($userId)
    {
        $query = "SELECT c.id_curso, c.titulo, COUNT(i.id_usuario) AS alumnos_inscritos, 
                         AVG(i.progreso) AS nivel_promedio, SUM(v.precio_pagado) AS ingresos_totales
                  FROM Cursos c
                  LEFT JOIN Inscripciones i ON c.id_curso = i.id_curso
                  LEFT JOIN Ventas v ON c.id_curso = v.id_curso
                  WHERE c.id_instructor = :userId
                  GROUP BY c.id_curso";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getTotalesPorPago($userId)
    {
        $query = "SELECT v.forma_pago, SUM(v.precio_pagado) AS total_ingresos
                  FROM Ventas v
                  JOIN Cursos c ON v.id_curso = c.id_curso
                  WHERE c.id_instructor = :userId
                  GROUP BY v.forma_pago";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getDetallesCurso($idCurso)
    {
        $query = "SELECT u.nombre AS alumno, i.fecha_inscripcion, i.progreso, v.precio_pagado, v.forma_pago
                  FROM Inscripciones i
                  JOIN Usuarios u ON i.id_usuario = u.idUsuario
                  JOIN Ventas v ON i.id_curso = v.id_curso AND i.id_usuario = v.id_usuario
                  WHERE i.id_curso = :idCurso";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function actualizarEstadoCurso($idCurso, $nuevoEstado)
    {
        $query = "UPDATE cursos SET activo = :nuevoEstado WHERE id_curso = :idCurso";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_INT);
        $stmt->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
