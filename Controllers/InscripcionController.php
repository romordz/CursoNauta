<?php
require_once 'Models/InscripcionModel.php';

class InscripcionController
{
    private $inscripcionModel;

    public function __construct()
    {
        $this->inscripcionModel = new InscripcionModel();
    }

    public function registrar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        $idCurso = isset($_POST['idCurso']) ? (int)$_POST['idCurso'] : 0;
        $idUsuario = $_SESSION['user_id'] ?? null;
        $formaPago = $_POST['forma_pago'] ?? '';
        $precioPagado = $_POST['precio_pagado'] ?? 0;
    
        // Verificar si el curso ya está inscrito
        if ($this->inscripcionModel->inscripcionYaRegistrada($idCurso, $idUsuario)) {
            echo "<script>alert('Ya estás inscrito en este curso.'); window.location.href = 'index.php?page=Principal';</script>";
            return; // Detener el proceso si el curso ya está inscrito
        }
    
        if ($idCurso > 0 && $idUsuario && $formaPago && $precioPagado > 0) {
            $inscripcionExitosa = $this->inscripcionModel->registrarInscripcion($idCurso, $idUsuario);
    
            if ($inscripcionExitosa) {
                // Registrar la venta después de la inscripción
                $ventaExitosa = $this->inscripcionModel->registrarVenta($idCurso, $idUsuario, $precioPagado, $formaPago);
                
                if ($ventaExitosa) {
                    echo "<script>alert('¡Inscripción y venta registradas con éxito!'); window.location.href = 'index.php?page=Kardex';</script>";
                } else {
                    echo "<script>alert('Error al registrar la venta.');</script>";
                }
            } else {
                echo "<script>alert('Error al registrar la inscripción.');</script>";
            }
        } else {
            echo "<script>alert('Datos de inscripción o venta inválidos.');</script>";
        }
   
    }
    public function mostrarCursosInscritos()
    {
        $idUsuario = $_SESSION['user_id'];
        return $this->inscripcionModel->obtenerCursosInscritos($idUsuario); // Asegúrate de devolver el resultado
    }
    
    public function generarCertificado($id_curso, $id_usuario) {
        return $this->inscripcionModel->obtenerDatosCertificado($id_curso, $id_usuario);
    }
    
    public function getCategoriasActivas() {
        return $this->inscripcionModel->getCategoriasActivas();
    }

    public function filtrarCursosInscritos($categoriaID, $estado, $fechaInicio, $fechaFin, $usuarioID) {
        return $this->inscripcionModel->buscarKardexDinamico($categoriaID, $estado, $fechaInicio, $fechaFin, $usuarioID);
    }
    
    
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controlador = new InscripcionController();
    if (isset($_POST['action']) && $_POST['action'] === 'registrarInscripcion') {
        $controlador->registrar();
    }
}
