<?php
session_start();
require_once(__DIR__ . "/../mdb/mdbUsuario.php");

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['user'] ?? '';
$password = $data['pass'] ?? '';

$ruta = "#";
$msg = "Usuario y/o contraseña no válido.";
$usuario = autenticarUsuario($username, $password);


if ($usuario != null) {
    $_SESSION['ID_USUARIO'] = $usuario->getIdUsuario();
    $_SESSION['CORREO_USUARIO'] = $usuario->getCorreo();
    $_SESSION['NOMBRE_USUARIO'] = $usuario->getNombre();
    $_SESSION['ROL_USUARIO'] = $usuario->getRol();
    $ruta = "./runner.php";
    $msg = "Puede iniciar sesión satisfactoriamente";
}

$resultado = [
    "msg" => $msg,
    "type" => $usuario ? "success" : "error",
    "ruta" => $ruta
];

echo json_encode($resultado);
