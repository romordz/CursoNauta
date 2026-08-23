<?php
require_once 'vendor/autoload.php';
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad(); 
}
include_once 'Models/Database.php';
include 'Models/Router.php';
require_once 'init.php';
$database = new Database();
$db = $database->getConnection();
$router = new Router('routes.php');
$router->handleRequest();
