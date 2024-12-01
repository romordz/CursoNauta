<?php
session_start();
$error_correo = '';
$error_contrasena = '';
$correo_valor = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $password = $_POST['contrasena'];

    $correo_valor = $correo;

    $url = 'http://localhost:8080/CursoNauta/api.php';

    $data = array(
        'accion' => 'inicio_sesion',
        'correo' => $correo,
        'contrasena' => $password
    );

    // Configurar cURL para la solicitud POST
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => http_build_query($data)
    );

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);

    // Verificar si cURL tuvo algún error
    if ($response === false) {
        echo "Error de cURL: " . curl_error($ch);
    }

    curl_close($ch);

    // Decodificar la respuesta de la API
    $response = json_decode($response, true);

    // Verificar el resultado del inicio de sesión
    if (isset($response['error'])) {
        if ($response['error'] === "El correo no está registrado.") {
            $error_correo = $response['error'];
        } elseif ($response['error'] === "La cuenta está desactivada. Contacta al administrador.") {
            $error_desactivada = $response['error'];
        } elseif ($response['error'] === "Contraseña incorrecta.") {
            $error_contrasena = $response['error'];
        }
    } elseif (isset($response['message'])) {
        $_SESSION['user_id'] = $response['user']['idUsuario'];
        $_SESSION['user_name'] = $response['user']['nombre'];
        $_SESSION['user_role'] = $response['user']['id_rol'];
        if (!empty($response['user']['foto_avatar'])) {
            $_SESSION['user_img'] = 'data:image/jpeg;base64,' . $response['user']['foto_avatar'];
        } else {
            $_SESSION['user_img'] = 'Views/Recursos/Perfil.jpg';
        }
        header("Location: index.php?page=Principal");
        exit();
    }
}


include 'Views\Login.php';
