<?php
require_once __DIR__ . '/../entidad/Corredor.php';
require_once __DIR__ . '/../entidad/Usuario.php';
require_once __DIR__ . '/DataSource.php';

class CorredorDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function agregarCorredor(Corredor $corredor) {
        $usuario = $corredor->getUsuario();


        // Insertar en corredores
        $sqlCorredor = "INSERT INTO corredor (usuario_id, ciudad_id, equipo_id) VALUES (:id_usuario, :id_ciudad, :id_equipo)";
        $paramsCorredor = array(
            ':id_usuario' => $corredor->getUsuario(),
            ':id_ciudad' => $corredor->getCiudad(),
            ':id_equipo' => $corredor->getEquipo() 
        );
        return $this->dataSource->ejecutarActualizacion($sqlCorredor, $paramsCorredor);
    }

    public function listarCorredores() {
        $sql = "SELECT * FROM corredor c JOIN usuario u ON c.usuario_id = u.id_usuario";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $corredores = [];
        foreach ($result as $row) {
            $usuario = new Usuario($row['rol'], $row['nombre'], $row['apellido'], $row['correo'], $row['contrasena'], $row['sexo'], $row['fecha_nacimiento'], $row['fecha_registro']);
            $corredores[] = new Corredor($usuario, null, null); // Puedes cargar ciudad y equipo si lo necesitas
        }
        return $corredores;
    }

    public function obtenerCorredorPorCorreo($correo) {
        $sql = "SELECT * FROM corredores c JOIN usuarios u ON c.id_usuario = u.id_usuario WHERE u.correo = :correo";
        $params = [':correo' => $correo];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            $usuario = new Usuario($row['rol'], $row['nombre'], $row['apellido'], $row['correo'], $row['contrasena'], $row['sexo'], $row['fecha_nacimiento'], $row['fecha_registro']);
            return new Corredor($usuario, null, null);
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

    // Puedes añadir actualizar y eliminar si lo necesitas
}
?>
