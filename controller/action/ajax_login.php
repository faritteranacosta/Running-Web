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
    // Log temporal para depuración
    file_put_contents(__DIR__ . '/debug_login.txt', print_r($usuario, true) . "\nID: " . $usuario->getIdUsuario() . "\n", FILE_APPEND);
    $_SESSION['CORREO_USUARIO'] = $usuario->getCorreo();
    $_SESSION['NOMBRE_USUARIO'] = $usuario->getNombre();
    $_SESSION['APELLIDO_USUARIO'] = $usuario->getApellido();
    $_SESSION['SEXO_USUARIO'] = $usuario->getSexo();
    $_SESSION['FECHA_NACIMIENTO'] = $usuario->getFechaNacimiento();
    $_SESSION['FECHA_REGISTRO'] = $usuario->getFechaRegistro();
    $_SESSION['ROL_USUARIO'] = $usuario->getRol();
    if($usuario->getRol() == "vendedor"){
        $ruta = "vendedor.php";
        $msg = "Puede iniciar sesión satisfactoriamente como administrador";

    }else if($usuario->getRol() == "admin"){
        $ruta = "panelAdministrador.php";
        $msg = "Puede iniciar sesión satisfactoriamente como administrador";
    }else if($usuario->getRol() == "corredor"){
        $ruta = "runner.php";
        $msg = "Puede iniciar sesión satisfactoriamente como corredor";
    }
    
}

$resultado = [
    "msg" => $msg,
    "type" => $usuario ? "success" : "error",
    "ruta" => $ruta
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($resultado);
