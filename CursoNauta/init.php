<?php

require_once 'Controllers/AuthMiddleware.php';

$routes = require 'routes.php';

// La clave es el nombre de la página, y el valor es el rol requerido (1 = admin, 2 = instructor, 3 = estudiante)
$routesWithAuthentication = [
    'Perfil' =>  null,          
    'Ventas' =>  [2],             
    'Admi' =>  [1],               
    'AddCurso' => [2],           
    'Kardex' =>  [3],             
    'Mensajes' => [2, 3],  
    'Pago' => [3],
    'Fin' => [3],
 
];

$page = $_GET['page'] ?? 'Principal';

if (isset($routesWithAuthentication[$page])) {
    $requiredRole = $routesWithAuthentication[$page];
    AuthMiddleware::checkAuthentication($requiredRole);
}
