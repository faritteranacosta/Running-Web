<?php
if (!isset($requiredRole)) {
    throw new RuntimeException('Debe definirse $requiredRole antes de incluir session.php');
}

session_start();
if (!isset($_SESSION['ROL_USUARIO']) || $_SESSION['ROL_USUARIO'] !== $requiredRole) {
    header('Location: acceso_denegado.html');
    exit();
}

$id = $_SESSION['ID_USUARIO'];
$nombre = ucfirst($_SESSION['NOMBRE_USUARIO']);
$apellido = ucfirst($_SESSION['APELLIDO_USUARIO']);
$correo = $_SESSION['CORREO_USUARIO'];
$sexo = $_SESSION['SEXO_USUARIO'];
$rol = ucfirst($_SESSION['ROL_USUARIO']);
$fecha_nacimiento = $_SESSION['FECHA_NACIMIENTO'];
$fecha_registro = $_SESSION['FECHA_REGISTRO'];
