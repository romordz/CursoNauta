<?php
session_start();
include_once 'Models/Database.php';

$error_correo = '';
$error_contrasena = '';
$error_desactivada = '';
$correo_valor = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $password = $_POST['contrasena'];
    $correo_valor = $correo;

    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT * FROM usuarios WHERE correo = :correo";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $error_correo = "El correo no está registrado.";
    } elseif ($user['activo'] == 0) {
        $error_desactivada = "La cuenta está desactivada. Contacta al administrador.";
    } elseif (!password_verify($password, $user['contrasena'])) {
        $error_contrasena = "Contraseña incorrecta.";
    } else {
        $_SESSION['user_id'] = $user['idUsuario'];
        $_SESSION['user_name'] = $user['nombre'];
        $_SESSION['user_role'] = $user['id_rol'];
        if (!empty($user['foto_avatar'])) {
            $_SESSION['user_img'] = 'data:image/jpeg;base64,' . base64_encode($user['foto_avatar']);
        } else {
            $_SESSION['user_img'] = 'Views/Recursos/Perfil.jpg';
        }
        header("Location: index.php?page=Principal");
        exit();
    }
}

include 'Views/Login.php';