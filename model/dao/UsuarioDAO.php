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
            $user= new Usuario($row['rol'], $row['nombre'], $row['apellido'], $row['correo'], $row['contrasena'], $row['sexo'], $row['fecha_nacimiento'], $row['fecha_registro']);
            $user->setIdUsuario($row['id_usuario']);
            return $user;
        }
        return null;
    }

    public function agregarUsuario(Usuario $usuario) {
        
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
        // Finalmente, eliminamos de usuario
        $sqlUsuario = "DELETE FROM usuario WHERE id_usuario = ?";
        return $this->dataSource->ejecutarActualizacion($sqlUsuario, [$id_usuario]);
    }
    public function obtenerUsuariosPaginados($offset, $porPagina) {
        $offset = (int)$offset;
        $porPagina = (int)$porPagina;
        // Interpolamos los valores directamente, ya que son enteros y seguros
        $sql = "SELECT * FROM usuario LIMIT $offset, $porPagina";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $usuarios = [];
        foreach ($result as $row) {
            $usuario = new Usuario(
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
    public function contarUsuarios() {
        $sql = "SELECT COUNT(*) as total FROM usuario";
        $result = $this->dataSource->ejecutarConsulta($sql);
        if (count($result) > 0) {
            return (int)$result[0]['total'];
        }
        return 0;
    }

    public function guardarToken($email, $token, $expira) {
        $dataSource = new DataSource();
        $sql = "UPDATE usuario SET token_recuperacion = ?, token_expiracion = ? WHERE correo = ?";
        return $dataSource->ejecutarActualizacion($sql, [$token, $expira, $email]);
    }
 
    public function buscarPorToken($token) {
        $dataSource = new DataSource();
        $sql = "SELECT * FROM usuario WHERE token_recuperacion = ? AND token_expiracion > NOW()";
        $result = $dataSource->ejecutarConsulta($sql, [$token]);
        return count($result) > 0 ? $result[0] : null;
    }
 
    public function actualizarContrasena($id_usuario, $nueva) {
        $dataSource = new DataSource();
        $sql = "UPDATE usuario SET contrasena = ?, token_recuperacion = NULL, token_expiracion = NULL WHERE id_usuario = ?";
        return $dataSource->ejecutarActualizacion($sql, [$nueva, $id_usuario]);
    }
    
}