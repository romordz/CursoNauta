<?php
class AuthMiddleware {
    public static function checkAuthentication($requiredRoles = []) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar si la sesión está iniciada
        if (!isset($_SESSION['user_id'])) {
            echo "<script>
                    alert('Debe iniciar sesión para acceder a esta página.');
                    window.location.href = 'index.php?page=Login';
                  </script>";
            exit();
        }

        // Verificar si el rol del usuario está en los roles permitidos
        if (!empty($requiredRoles) && (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $requiredRoles))) {
            echo "<script>
                    alert('No tienes acceso a esta página.');
                    window.location.href = 'index.php?page=Principal';
                  </script>";
            exit();
        }
    }
}
