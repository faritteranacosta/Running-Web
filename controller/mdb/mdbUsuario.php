<?php
require_once __DIR__ . '/../../model/dao/UsuarioDAO.php';
require_once '../../model/entidad/Corredor.php';
require_once '../../model/dao/CorredorDAO.php';
require_once '../../model/dao/VendedorDAO.php';
require_once '../../model/entidad/Corredor.php';
require_once '../../model/entidad/Vendedor.php';
require_once '../../model/entidad/Usuario.php';
require_once '../../model/dao/UsuarioDAO.php';

function autenticarUsuario($correo, $contrasena) {
    $dao = new UsuarioDAO();
    return $dao->autenticarUsuario($correo, $contrasena);
}

function agregarUsuario($rol, $nombre, $apellido, $correo, $contrasena, $sexo, $fecha_nacimiento, $fecha_registro, $ciudad = null, $equipo = null, $nombre_tienda = null) {
    // 1. Crear y guardar usuario
    $usuario = new Usuario($rol, $nombre, $apellido, $correo, $contrasena, $sexo, $fecha_nacimiento, $fecha_registro);
    $userDao = new UsuarioDAO();
    $id_usuario = $userDao->agregarUsuario($usuario); // Esto debe retornar el ID del usuario insertado

    if (!$id_usuario) {
        throw new Exception("Error al registrar el usuario.");
    }

    // 2. Insertar en la tabla correspondiente según el rol
    if ($rol === 'corredor') {
        $daoCorredor = new CorredorDAO();
        $corredor = new Corredor($id_usuario, $ciudad, $equipo);
        $daoCorredor->agregarCorredor($corredor);
        

    } elseif ($rol === 'vendedor') {
        if (!$nombre_tienda) {
            throw new Exception("Debe proporcionar el nombre de la tienda para registrar un vendedor.");
        }
        $vendedor = new Vendedor($id_usuario, $nombre_tienda);

        $daoVendedor = new VendedorDAO();
        $daoVendedor->agregarVendedor($vendedor);
    }

    return $usuario; // Usuario común
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

function buscarUsuarioPorCorreo($correo) {
    $usuarioDAO = new UsuarioDAO();
    return $usuarioDAO->obtenerUsuarioPorCorreo($correo);
}

function guardarToken($correo, $token, $expira) {
    $usuarioDAO = new UsuarioDAO();
    return $usuarioDAO->guardarToken($correo, $token, $expira);
}

function verificarToken($token) {
    $usuarioDAO = new UsuarioDAO();
    return $usuarioDAO->buscarPorToken($token);
}

function actualizarContrasena($id, $nueva_contrasena) {
    $usuarioDAO = new UsuarioDAO();
    return $usuarioDAO->actualizarContrasena($id, $nueva_contrasena);
}


