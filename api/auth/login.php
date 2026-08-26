<?php
session_start();
require_once __DIR__ . '/../../controller/AuthController.php';

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['user'] ?? '';
$password = $data['pass'] ?? '';

$authController = new AuthController();
$resultado = $authController->login($username, $password);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($resultado);
