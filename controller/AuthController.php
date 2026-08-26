<?php
require_once __DIR__ . '/../model/dao/UsuarioDAO.php';

class AuthController
{
    public function login($username, $password)
    {
        $ruta = '#';
        $msg = 'Usuario y/o contraseña no válido.';
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->autenticarUsuario($username, $password);

        if ($usuario != null) {
            $_SESSION['ID_USUARIO'] = $usuario->getIdUsuario();
            $_SESSION['CORREO_USUARIO'] = $usuario->getCorreo();
            $_SESSION['NOMBRE_USUARIO'] = $usuario->getNombre();
            $_SESSION['APELLIDO_USUARIO'] = $usuario->getApellido();
            $_SESSION['SEXO_USUARIO'] = $usuario->getSexo();
            $_SESSION['FECHA_NACIMIENTO'] = $usuario->getFechaNacimiento();
            $_SESSION['FECHA_REGISTRO'] = $usuario->getFechaRegistro();
            $_SESSION['ROL_USUARIO'] = $usuario->getRol();

            if ($usuario->getRol() == 'vendedor') {
                $ruta = 'vendedor.php';
                $msg = 'Puede iniciar sesión satisfactoriamente como administrador';
            } elseif ($usuario->getRol() == 'admin') {
                $ruta = 'panelAdministrador.php';
                $msg = 'Puede iniciar sesión satisfactoriamente como administrador';
            } elseif ($usuario->getRol() == 'corredor') {
                $ruta = 'eventos.php';
                $msg = 'Puede iniciar sesión satisfactoriamente como corredor';
            }
        }

        return [
            'msg' => $msg,
            'type' => $usuario ? 'success' : 'error',
            'ruta' => $ruta,
            'id_usuario' => $usuario ? $usuario->getIdUsuario() : null
        ];
    }
}
