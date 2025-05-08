<?php
require_once '../../model/dao/CorredorDAO.php';
require_once '../../model/entidad/Corredor.php';

function autenticarCorredor($correo_electronico, $contrasena) {
    $dao = new CorredorDAO();
    return $dao->autenticarCorredor($correo_electronico, $contrasena);
}



function agregarCorredor($nombre_usuario, $apellido_usuario, $correo_electronico, $contrasena) {
    $dao = new CorredorDAO();
    $corredor = new Corredor($nombre_usuario, $apellido_usuario, $correo_electronico, $contrasena);
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

function buscarCorredorPorCorreo($correo_electronico) {
    $dao = new CorredorDAO();
    return $dao->obtenerCorredorPorCorreo($correo_electronico);
}

function modificarCorredor($nombre_usuario, $apellido_usuario, $correo_electronico, $contrasena) {
    $dao = new CorredorDAO();
    $corredor = new Corredor($nombre_usuario, $apellido_usuario, $correo_electronico, $contrasena);
    return $dao->actualizarCorredor($corredor);
}

function eliminarCorredor($correo_electronico) {
    $dao = new CorredorDAO();
    return $dao->eliminarCorredor($correo_electronico);
}

