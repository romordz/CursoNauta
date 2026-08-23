<?php
include_once 'Models/Database.php';
include 'Models/Router.php';
require_once 'vendor/autoload.php';
require_once 'init.php';
$database = new Database();
$db = $database->getConnection();
$router = new Router('routes.php');
$router->handleRequest();


if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}
