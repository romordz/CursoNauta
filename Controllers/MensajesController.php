<?php
include_once 'Models/MensajesModel.php';
include_once 'Models/Database.php';

$database = new Database();
$db = $database->getConnection();
$mensajesModel = new MensajesModel($db);

$id_emisor = $_SESSION['user_id'];
$id_receptor = $_GET['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mensaje']) && $id_receptor) {
    $mensaje = $_POST['mensaje'];
    $mensajesModel->enviarMensaje($id_emisor, $id_receptor, $mensaje);

    echo "<script>window.location.href = window.location.href;</script>";
    exit();
}

$mensajes = [];
if ($id_receptor) {
    $mensajesModel->iniciarChatSiNoExiste($id_emisor, $id_receptor);
    $mensajes = $mensajesModel->obtenerMensajesEntreUsuarios($id_emisor, $id_receptor);
}

$instructores = $mensajesModel->obtenerInstructoresConMensajes($id_emisor);