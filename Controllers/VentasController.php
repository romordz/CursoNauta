<?php
require_once 'Models/VentasModel.php';

class VentasController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new VentasModel($db);
    }

    public function obtenerVentas()
    {
        $userId = $_SESSION['user_id'];
        $cursosVentas = $this->model->getCursosVentas($userId);
        $totalesPorPago = $this->model->getTotalesPorPago($userId);
        return ['cursosVentas' => $cursosVentas, 'totalesPorPago' => $totalesPorPago];
    }

    public function obtenerCategorias()
    {
        return $this->model->getCategorias();
    }

    public function obtenerVentasFiltradas($filtros)
    {
        $userId = $_SESSION['user_id'];
        return $this->model->getCursosVentasFiltrados($filtros, $userId);
    }

    public function obtenerDetallesCurso($idCurso)
    {
        return $this->model->getDetallesCurso($idCurso);
    }

    public function actualizarEstadoCurso($idCurso, $nuevoEstado)
    {
        return $this->model->actualizarEstadoCurso($idCurso, $nuevoEstado);
    }
}
