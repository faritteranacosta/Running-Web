<?php 
require_once __DIR__ . '/DataSource.php';
require_once __DIR__ . '/../entidad/TipoCarrera.php';

class TipoCarreraDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function obtenerTiposCarrera() {
        $sql = "SELECT * FROM tipo_carrera";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $tiposCarrera = [];
        foreach ($result as $row) {
            $tipo = new TipoCarrera($row['nombre'], $row['descripcion']);
            $tipo->setIdTipoCarrera($row['id_tipo_carrera']);
            $tiposCarrera[] = $tipo;
        }
        return $tiposCarrera;
    }

    public function obtenerTipoCarreraPorId($id_tipo_carrera) {
        $sql = "SELECT * FROM tipo_carrera WHERE id_tipo_carrera = ?";
        $params = [$id_tipo_carrera];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            $tipo = new TipoCarrera($row['nombre'], $row['descripcion']);
            $tipo->setIdTipoCarrera($row['id_tipo_carrera']);
            return $tipo;
        }
        return null;
    }
}

?>