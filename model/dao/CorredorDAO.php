<?php
require_once __DIR__ . '/../entidad/Corredor.php';
require_once __DIR__ . '/DataSource.php';

class CorredorDAO {

    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function autenticarCorredor($correo_electronico, $contrasena) {
        $sql = "SELECT * FROM corredores WHERE correo_electronico = :correo_electronico AND contrasena = :contrasena";
        $params = array(
            ':correo_electronico' => $correo_electronico,
            ':contrasena' => $contrasena
        );
        $result = $this->dataSource->ejecutarConsulta($sql, $params);

        if (count($result) > 0) {
            return new Corredor(
                $result[0]['nombre_usuario'],
                $result[0]['apellido_usuario'],
                $result[0]['correo_electronico'],
                $result[0]['contrasena']
            );
        }
        return null;
    }

    public function agregarCorredor(Corredor $corredor) {
        $sql = "INSERT INTO corredores (nombre_usuario, apellido_usuario, correo_electronico, contrasena) 
                VALUES (:nombre_usuario, :apellido_usuario, :correo_electronico, :contrasena)";
        $params = array(
            ':nombre_usuario' => $corredor->getNombreUsuario(),
            ':apellido_usuario' => $corredor->getApellidoUsuario(),
            ':correo_electronico' => $corredor->getCorreoElectronico(),
            ':contrasena' => $corredor->getContrasena()
        );
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function obtenerCorredorPorCorreo($correo_electronico) {
        $sql = "SELECT * FROM corredores WHERE correo_electronico = :correo_electronico";
        $params = array(':correo_electronico' => $correo_electronico);
        $result = $this->dataSource->ejecutarConsulta($sql, $params);

        if (count($result) > 0) {
            $row = $result[0];
            return new Corredor(
                $row['nombre_usuario'],
                $row['apellido_usuario'],
                $row['correo_electronico'],
                $row['contrasena']
            );
        }
        return null;
    }

    public function obtenerCorredorPorId($id_corredor) {
        $sql = "SELECT * FROM corredores WHERE id_corredor = :id_corredor";
        $params = array(':id_corredor' => $id_corredor);
        $result = $this->dataSource->ejecutarConsulta($sql, $params);

        if (count($result) > 0) {
            $row = $result[0];
            return new Corredor(
                $row['nombre_usuario'],
                $row['apellido_usuario'],
                $row['correo_electronico'],
                $row['contrasena']
            );
        }
        return null;
    }

    public function actualizarCorredor(Corredor $corredor) {
        $sql = "UPDATE corredores SET 
                nombre_usuario = :nombre_usuario, 
                apellido_usuario = :apellido_usuario, 
                contrasena = :contrasena 
                WHERE correo_electronico = :correo_electronico";
        $params = array(
            ':nombre_usuario' => $corredor->getNombreUsuario(),
            ':apellido_usuario' => $corredor->getApellidoUsuario(),
            ':contrasena' => $corredor->getContrasena(),
            ':correo_electronico' => $corredor->getCorreoElectronico()
        );
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function eliminarCorredor($correo_electronico) {
        $sql = "DELETE FROM corredores WHERE correo_electronico = :correo_electronico";
        $params = array(':correo_electronico' => $correo_electronico);
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function listarCorredores() {
        $sql = "SELECT * FROM corredores";
        $result = $this->dataSource->ejecutarConsulta($sql);

        $corredores = array();
        foreach ($result as $row) {
            $corredores[] = new Corredor(
                $row['nombre_usuario'],
                $row['apellido_usuario'],
                $row['correo_electronico'],
                $row['contrasena']
            );
        }
        return $corredores;
    }
}
?>