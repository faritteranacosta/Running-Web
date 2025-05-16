<?php
require_once __DIR__ . '/../../model/dao/UsuarioDAO.php';

function agregarUsuario($rol, $nombre, $apellido, $correo, $contrasena, $sexo, $fecha_nacimiento, $fecha_registro) {
    $usuarioDAO = new UsuarioDAO();
    $usuario = new Usuario($rol, $nombre, $apellido, $correo, $contrasena, $sexo, $fecha_nacimiento, $fecha_registro);
    return $usuarioDAO->agregarUsuario($usuario);
}

function obtenerUsuarioPorId($id_usuario) {
    $usuarioDAO = new UsuarioDAO();
    return $usuarioDAO->obtenerUsuarioPorId($id_usuario);
}

function listarUsuarios() {
    $usuarioDAO = new UsuarioDAO();
    return $usuarioDAO->listarUsuarios();
}

function actualizarUsuario($id_usuario, $rol, $nombre, $apellido, $correo, $contrasena, $sexo, $fecha_nacimiento, $fecha_registro) {
    $usuarioDAO = new UsuarioDAO();
    $usuario = new Usuario($rol, $nombre, $apellido, $correo, $contrasena, $sexo, $fecha_nacimiento, $fecha_registro);
    $usuario->setIdUsuario($id_usuario);
    return $usuarioDAO->actualizarUsuario($usuario);
}

function eliminarUsuario($id_usuario) {
    $usuarioDAO = new UsuarioDAO();
    return $usuarioDAO->eliminarUsuario($id_usuario);
}

function autenticarUsuario($correo, $contrasena) {
    $usuarioDAO = new UsuarioDAO();
    $usuario = $usuarioDAO->obtenerUsuarioPorCorreo($correo);
    if ($usuario && $usuario->getContrasena() === $contrasena) {
        return $usuario;
    }
    return null;
}