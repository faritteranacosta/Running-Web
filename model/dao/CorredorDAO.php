<?php
require_once __DIR__ . '/../entidad/Corredor.php';
require_once __DIR__ . '/DataSource.php';

class CorredorDAO {

    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function autenticarCorredor($correo, $contrasena) {
        $sql = "SELECT * FROM corredores c JOIN usuarios u ON c.id_usuario = u.id_usuario WHERE u.correo = :correo AND u.contrasena = :contrasena";
        $params = array(
            ':correo' => $correo,
            ':contrasena' => $contrasena
        );
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            $usuario = new Usuario($row['rol'], $row['nombre'], $row['apellido'], $row['correo'], $row['contrasena'], $row['sexo'], $row['fecha_nacimiento'], $row['fecha_registro']);
            $ciudad = null;
            $equipo = null;
            return new Corredor($usuario, $ciudad, $equipo);
        }
        return null;
    }

    public function agregarCorredor(Corredor $corredor) {
        $usuario = $corredor->getUsuario();
        // Primero insertar el usuario
        $sqlUsuario = "INSERT INTO usuarios (rol, nombre, apellido, correo, contrasena, sexo, fecha_nacimiento, fecha_registro) VALUES (:rol, :nombre, :apellido, :correo, :contrasena, :sexo, :fecha_nacimiento, :fecha_registro)";
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
        $ciudad = $corredor->getCiudad();
        $equipo = $corredor->getEquipo();
        $sql = "INSERT INTO corredores (id_usuario, id_ciudad, id_equipo) VALUES (:id_usuario, :id_ciudad, :id_equipo)";
        $params = array(
            ':id_usuario' => $id_usuario,
            ':id_ciudad' => $ciudad ? $ciudad->getIdCiudad() : null,
            ':id_equipo' => $equipo ? $equipo->getIdEquipo() : null
        );
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function obtenerCorredorPorCorreo($correo) {
        $sql = "SELECT * FROM corredores c JOIN usuarios u ON c.id_usuario = u.id_usuario WHERE u.correo = :correo";
        $params = array(':correo' => $correo);
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            $usuario = new Usuario($row['rol'], $row['nombre'], $row['apellido'], $row['correo'], $row['contrasena'], $row['sexo'], $row['fecha_nacimiento'], $row['fecha_registro']);
            $ciudad = null;
            $equipo = null;
            return new Corredor($usuario, $ciudad, $equipo);
        }
        return null;
    }

    public function obtenerCorredorPorId($id_corredor) {
        $sql = "SELECT * FROM corredores c JOIN usuarios u ON c.id_usuario = u.id_usuario WHERE c.id_corredor = :id_corredor";
        $params = array(':id_corredor' => $id_corredor);
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            $usuario = new Usuario($row['rol'], $row['nombre'], $row['apellido'], $row['correo'], $row['contrasena'], $row['sexo'], $row['fecha_nacimiento'], $row['fecha_registro']);
            $ciudad = null;
            $equipo = null;
            return new Corredor($usuario, $ciudad, $equipo);
        }
        return null;
    }

    public function actualizarCorredor(Corredor $corredor) {
        $usuario = $corredor->getUsuario();
        $sql = "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, contrasena = :contrasena, sexo = :sexo, fecha_nacimiento = :fecha_nacimiento WHERE correo = :correo";
        $params = array(
            ':nombre' => $usuario->getNombre(),
            ':apellido' => $usuario->getApellido(),
            ':contrasena' => $usuario->getContrasena(),
            ':sexo' => $usuario->getSexo(),
            ':fecha_nacimiento' => $usuario->getFechaNacimiento(),
            ':correo' => $usuario->getCorreo()
        );
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function eliminarCorredor($correo) {
        $sql = "DELETE FROM usuarios WHERE correo = :correo";
        $params = array(':correo' => $correo);
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function listarCorredores() {
        $sql = "SELECT * FROM corredores c JOIN usuarios u ON c.id_usuario = u.id_usuario";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $corredores = array();
        foreach ($result as $row) {
            $usuario = new Usuario($row['rol'], $row['nombre'], $row['apellido'], $row['correo'], $row['contrasena'], $row['sexo'], $row['fecha_nacimiento'], $row['fecha_registro']);
            $ciudad = null;
            $equipo = null;
            $corredores[] = new Corredor($usuario, $ciudad, $equipo);
        }
        return $corredores;
    }
}
?>