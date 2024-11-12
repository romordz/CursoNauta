<?php
include_once 'Models\MensajesModel.php';
include_once 'Models\Database.php';

// Crear una conexión y acceder al modelo
$database = new Database();
$db = $database->getConnection();
$mensajesModel = new MensajesModel($db);

// Obtener el id del usuario actual y el instructor seleccionado de la URL
$id_emisor = $_SESSION['user_id'];
$id_receptor = $_GET['user_id'] ?? null;

// Procesar el envío del mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mensaje']) && $id_receptor) {
    $mensaje = $_POST['mensaje'];
    $mensajesModel->enviarMensaje($id_emisor, $id_receptor, $mensaje);

    // Redirigir para evitar reenvío de formulario
    header("Location: index.php?page=Mensajes&user_id=$id_receptor");
    exit();
}

// Iniciar el chat si no existe y cargar mensajes del instructor seleccionado
$mensajes = [];
if ($id_receptor) {
    $mensajesModel->iniciarChatSiNoExiste($id_emisor, $id_receptor); // Crear chat si es necesario
    $mensajes = $mensajesModel->obtenerMensajesEntreUsuarios($id_emisor, $id_receptor); // Obtener mensajes
}

// Obtener instructores con los que el usuario ha hablado
$instructores = $mensajesModel->obtenerInstructoresConMensajes($id_emisor);
