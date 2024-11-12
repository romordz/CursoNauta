<?php
require_once 'Models/ReportesModel.php';

class ReportesController {
    private $reportesModel;

    public function __construct() {
        $this->reportesModel = new ReportesModel();
    }

    public function mostrarReporteInstructores() {
        return $this->reportesModel->obtenerReporteInstructores();
    }

    public function mostrarReporteEstudiantes() {
        return $this->reportesModel->obtenerReporteEstudiantes();
    }
}
