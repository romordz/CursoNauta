<?php
require_once __DIR__ . '/../Models/ProgresoModel.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'marcarNivel') {
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'No autenticado']);
        exit;
    }

    $idNivel = (int) ($_POST['id_nivel'] ?? 0);
    $idCurso = (int) ($_POST['id_curso'] ?? 0);
    $idUsuario = $_SESSION['user_id'];

    $progresoModel = new ProgresoModel();
    $resultado = $progresoModel->marcarNivelCompletado($idUsuario, $idNivel, $idCurso);
    $progresoActual = $progresoModel->obtenerProgresoActual($idUsuario, $idCurso);

    echo json_encode(['success' => $resultado, 'progreso' => $progresoActual]);
    exit;
}