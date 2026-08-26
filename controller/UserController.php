<?php
require_once __DIR__ . '/../model/dao/UsuarioDAO.php';
require_once __DIR__ . '/../model/dao/CorredorDAO.php';
require_once __DIR__ . '/../model/dao/VendedorDAO.php';
require_once __DIR__ . '/../model/entidad/Usuario.php';
require_once __DIR__ . '/../model/entidad/Corredor.php';
require_once __DIR__ . '/../model/entidad/Vendedor.php';

class UserController
{
    private $usuarioDAO;

    public function __construct()
    {
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function autenticar($correo, $contrasena)
    {
        return $this->usuarioDAO->autenticarUsuario($correo, $contrasena);
    }

    public function agregar($rol, $nombre, $apellido, $correo, $contrasena, $sexo, $fechaNacimiento, $fechaRegistro, $ciudad = null, $equipo = null, $nombreTienda = null)
    {
        $usuario = new Usuario($rol, $nombre, $apellido, $correo, $contrasena, $sexo, $fechaNacimiento, $fechaRegistro);
        $idUsuario = $this->usuarioDAO->agregarUsuario($usuario);

        if (!$idUsuario) {
            throw new Exception('Error al registrar el usuario.');
        }

        if ($rol === 'corredor') {
            $dao = new CorredorDAO();
            $dao->agregarCorredor(new Corredor($idUsuario, $ciudad, $equipo));
        } elseif ($rol === 'vendedor') {
            if (!$nombreTienda) {
                throw new Exception('Debe proporcionar el nombre de la tienda para registrar un vendedor.');
            }
            $dao = new VendedorDAO();
            $dao->agregarVendedor(new Vendedor($idUsuario, $nombreTienda));
        }

        return $usuario;
    }

    public function obtenerPorId($idUsuario) { return $this->usuarioDAO->obtenerUsuarioPorId($idUsuario); }
    public function listar() { return $this->usuarioDAO->listarUsuarios(); }
    public function obtenerPaginados($offset, $porPagina) { return $this->usuarioDAO->obtenerUsuariosPaginados($offset, $porPagina); }
    public function eliminar($idUsuario) { return $this->usuarioDAO->eliminarUsuario($idUsuario); }
    public function contar() { return $this->usuarioDAO->contarUsuarios(); }
    public function buscarPorCorreo($correo) { return $this->usuarioDAO->obtenerUsuarioPorCorreo($correo); }
    public function guardarToken($correo, $token, $expira) { return $this->usuarioDAO->guardarToken($correo, $token, $expira); }
    public function verificarToken($token) { return $this->usuarioDAO->buscarPorToken($token); }
    public function actualizarContrasena($id, $nueva) { return $this->usuarioDAO->actualizarContrasena($id, $nueva); }
}
