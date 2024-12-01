<?php
class Router {
    private $routes = [];

    // Constructor para cargar rutas desde un archivo
    public function __construct($routesFile) {
        if (file_exists($routesFile)) {
            $this->routes = include $routesFile;
        }
    }

    // Método para manejar la ruta actual o por defecto
    public function handleRequest() {
        $page = isset($_GET['page']) ? $_GET['page'] : 'Principal';

        if (array_key_exists($page, $this->routes)) {
            include $this->routes[$page];
        } else {
            include 'Views/404.php';
        }
    }
}
