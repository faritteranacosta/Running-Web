<?php
require_once __DIR__ . '/../entidad/Usuario.php';
require_once __DIR__ . '/DataSource.php';

class UsuarioDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }
    public function autenticarUsuario($correo, $contrasena) {
        $sql = "SELECT * FROM usuario WHERE correo = :correo AND contrasena = :contrasena";
        $params = array(
            ':correo' => $correo,
            ':contrasena' => $contrasena
        );
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            return new Usuario($row['id_usuario'],$row['rol'], $row['nombre'], $row['apellido'], $row['correo'], $row['contrasena'], $row['sexo'], $row['fecha_nacimiento'], $row['fecha_registro']);
        }
        return null;
    }

    public function agregarUsuario(Usuario $usuario) {
        // Primero insertar el usuario
        $sqlUsuario = "INSERT INTO usuario (rol, nombre, apellido, correo, contrasena, sexo, fecha_nacimiento, fecha_registro) VALUES (:rol, :nombre, :apellido, :correo, :contrasena, :sexo, :fecha_nacimiento, :fecha_registro)";
        $paramsUsuario = array(
            ':rol' => $usuario->getRol(),
            ':nombre' => $usuario->getNombre(),
            ':apellido' => $usuario->getApellido(),
            ':correo' => $usuario->getCorreo(),
            ':contrasena' => $usuario->getContrasena(),
            ':sexo' => $usuario->getSexo(),
            ':fecha_nacimiento' => $usuario->getFechaNacimiento(),
            ':fecha_registro' => $usuario->getFechaRegistro()
        );
        $this->dataSource->ejecutarActualizacion($sqlUsuario, $paramsUsuario);
        $id_usuario = $this->dataSource->getLastInsertId();
        
        $this->dataSource->cerrarConexion();
        return $id_usuario;
        
    }

    public function obtenerUsuarioPorCorreo($correo) {
        $sql = "SELECT * FROM usuario WHERE correo = ?";
        $params = [$correo];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            $usuario = new Usuario(
                $row['id_usuario'],
                $row['rol'],
                $row['nombre'],
                $row['apellido'],
                $row['correo'],
                $row['contrasena'],
                $row['sexo'],
                $row['fecha_nacimiento'],
                $row['fecha_registro']
            );
            $usuario->setIdUsuario($row['id_usuario']);
            return $usuario;
        }
        return null;
    }

    public function obtenerUsuarioPorId($id_usuario) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = ?";
        $params = [$id_usuario];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            $usuario = new Usuario(
                $row['id_usuario'],
                $row['rol'],
                $row['nombre'],
                $row['apellido'],
                $row['correo'],
                $row['contrasena'],
                $row['sexo'],
                $row['fecha_nacimiento'],
                $row['fecha_registro']
            );
            $usuario->setIdUsuario($row['id_usuario']);
            return $usuario;
        }
        return null;
    }

    public function listarUsuarios() {
        $sql = "SELECT * FROM usuario";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $usuarios = [];
        foreach ($result as $row) {
            $usuario = new Usuario(
                $row['id_usuario'],
                $row['rol'],
                $row['nombre'],
                $row['apellido'],
                $row['correo'],
                $row['contrasena'],
                $row['sexo'],
                $row['fecha_nacimiento'],
                $row['fecha_registro']
            );
            $usuario->setIdUsuario($row['id_usuario']);
            $usuarios[] = $usuario;
        }
        return $usuarios;
    }

    public function actualizarUsuario(Usuario $usuario) {
        $sql = "UPDATE usuario SET rol = ?, nombre = ?, apellido = ?, correo = ?, contrasena = ?, sexo = ?, fecha_nacimiento = ?, fecha_registro = ? WHERE id_usuario = ?";
        $params = [
            $usuario->getRol(),
            $usuario->getNombre(),
            $usuario->getApellido(),
            $usuario->getCorreo(),
            $usuario->getContrasena(),
            $usuario->getSexo(),
            $usuario->getFechaNacimiento(),
            $usuario->getFechaRegistro(),
            $usuario->getIdUsuario()
        ];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function eliminarUsuario($id_usuario) {
        $sql = "DELETE FROM usuario WHERE id_usuario = ?";
        $params = [$id_usuario];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }
}