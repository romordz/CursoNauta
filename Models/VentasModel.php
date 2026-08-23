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
                  FROM cursos c
                  LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
                  LEFT JOIN ventas v ON c.id_curso = v.id_curso
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
                  FROM ventas v
                  JOIN cursos c ON v.id_curso = c.id_curso
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
                  FROM inscripciones i
                  JOIN usuarios u ON i.id_usuario = u.idUsuario
                  JOIN ventas v ON i.id_curso = v.id_curso AND i.id_usuario = v.id_usuario
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

    public function getCategorias()
    {
        $query = "SELECT id_categoria, nombre_categoria FROM categorias";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCursosVentasFiltrados($filtros, $userId)
    {
        $query = "SELECT c.id_curso, c.titulo, COUNT(i.id_usuario) AS alumnos_inscritos, 
                         AVG(i.progreso) AS nivel_promedio, SUM(v.precio_pagado) AS ingresos_totales
                  FROM cursos c
                  LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
                  LEFT JOIN ventas v ON c.id_curso = v.id_curso
                  WHERE c.id_instructor = :userId";

        if (!empty($filtros['start_date'])) {
            $query .= " AND c.fecha_creacion >= :start_date";
        }
        if (!empty($filtros['end_date'])) {
            $query .= " AND c.fecha_creacion <= :end_date";
        }
        if (!empty($filtros['category']) && $filtros['category'] !== 'all') {
            $query .= " AND c.id_categoria = :category";
        }
        if (!empty($filtros['status']) && $filtros['status'] !== 'all') {
            $query .= " AND c.activo = :status";
        }

        $query .= " GROUP BY c.id_curso";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

        if (!empty($filtros['start_date'])) {
            $stmt->bindParam(':start_date', $filtros['start_date']);
        }
        if (!empty($filtros['end_date'])) {
            $stmt->bindParam(':end_date', $filtros['end_date']);
        }
        if (!empty($filtros['category']) && $filtros['category'] !== 'all') {
            $stmt->bindParam(':category', $filtros['category'], PDO::PARAM_INT);
        }
        if (!empty($filtros['status']) && $filtros['status'] !== 'all') {
            $status = $filtros['status'] === 'active' ? 1 : 0;
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
