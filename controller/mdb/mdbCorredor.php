<?php
require_once '../../model/dao/CorredorDAO.php';
require_once '../../model/entidad/Corredor.php';

function autenticarCorredor($correo, $contrasena) {
    $dao = new CorredorDAO();
    return $dao->autenticarCorredor($correo, $contrasena);
}

function agregarCorredor($rol, $nombre, $apellido, $correo, $contrasena, $sexo, $fecha_nacimiento, $fecha_registro, $ciudad = null, $equipo = null) {
    $usuario = new Usuario($rol, $nombre, $apellido, $correo, $contrasena, $sexo, $fecha_nacimiento, $fecha_registro);
    $ciudadObj = $ciudad ? new Ciudad($ciudad) : null;
    $equipoObj = $equipo ? new Equipo($equipo) : null;
    $corredor = new Corredor($usuario, $ciudadObj, $equipoObj);
    $dao = new CorredorDAO();
    return $dao->agregarCorredor($corredor);
}

function listarCorredores() {
    $dao = new CorredorDAO();
    return $dao->listarCorredores();
}

function buscarCorredorPorId($id_corredor) {
    $dao = new CorredorDAO();
    return $dao->obtenerCorredorPorId($id_corredor);
}

function buscarCorredorPorCorreo($correo) {
    $dao = new CorredorDAO();
    return $dao->obtenerCorredorPorCorreo($correo);
}

function modificarCorredor($rol, $nombre, $apellido, $correo, $contrasena, $sexo, $fecha_nacimiento) {
    $usuario = new Usuario($rol, $nombre, $apellido, $correo, $contrasena, $sexo, $fecha_nacimiento);
    $corredor = new Corredor($usuario);
    $dao = new CorredorDAO();
    return $dao->actualizarCorredor($corredor);
}

function eliminarCorredor($correo) {
    $dao = new CorredorDAO();
    return $dao->eliminarCorredor($correo);
}

