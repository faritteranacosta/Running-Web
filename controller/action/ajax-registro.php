<?php
require_once(__DIR__ . "/../mdb/mdbUsuario.php");

$data = json_decode(file_get_contents('php://input'), true);
$nombre = $data['nombre'] ?? '';
$apellido = $data['apellido'] ?? '';
$correo = $data['correo'] ?? '';
$contrasena = $data['contrasena'] ?? '';
$sexo = $data['sexo'] ?? '';
$fecha = $data['fecha_nacimiento'] ?? '';

$msg = "No se pudo registrar el usuario.";
$ruta = "#";
$type = "error";

// Puedes agregar validaciones aquí

$rol = "corredor"; // O el rol que corresponda por defecto
$fecha_registro = date('Y-m-d');

$result = agregarUsuario($rol, $nombre, $apellido, $correo, $contrasena,
                         $sexo, $fecha, $fecha_registro);

if ($result) {
    $msg = "Usuario registrado correctamente.";
    $ruta = "./home.html"; 
    $type = "success";
}

echo json_encode([
    "msg" => $msg,
    "type" => $type,
    "ruta" => $ruta
]);