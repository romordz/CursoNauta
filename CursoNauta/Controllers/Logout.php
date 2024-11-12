<?php
session_start();
session_destroy(); // Destruir la sesión actual

// Redirigir al inicio de sesión
header("Location: index.php?page=Principal");
exit();

