<?php
require_once __DIR__ . '/../entidad/Carrera.php';
require_once __DIR__ . '/DataSource.php';

class CarreraDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function agregarCarrera(Carrera $carrera) {
        $sql = "INSERT INTO carrera (distancia, id_evento, tipo_carrera_id, id_categoria, id_ruta) VALUES (?, ?, ?, ?, ?)";
        $params = [
            $carrera->getDistancia(),
            $carrera->getEvento() ? $carrera->getEvento()->getIdEvento() : null,
            $carrera->getTipoCarrera() ? $carrera->getTipoCarrera()->getIdTipoCarrera() : null,
            $carrera->getCategoria() ? $carrera->getCategoria()->getIdCategoria() : null,
            $carrera->getRuta() ? $carrera->getRuta()->getIdRuta() : null
        ];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function obtenerCarreraPorId($id_carrera) {
        $sql = "SELECT * FROM carrera WHERE id_carrera = ?";
        $params = [$id_carrera];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            $evento = new Evento();
            $evento->id_evento = $row['id_evento'];
            $tipoCarrera = new TipoCarrera();
            $tipoCarrera->id_tipo_carrera = $row['tipo_carrera_id'];
            $categoria = new Categoria();
            $categoria->id_categoria = $row['id_categoria'];
            $ruta = new Ruta();
            $ruta->id_ruta = $row['id_ruta'];
            return new Carrera($row['distancia'], $evento, $tipoCarrera, $categoria, $ruta);
        }
        return null;
    }

    public function listarCarreras() {
        $sql = "SELECT * FROM carrera";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $carreras = array();
        foreach ($result as $row) {
            $evento = new Evento();
            $evento->id_evento = $row['id_evento'];
            $tipoCarrera = new TipoCarrera();
            $tipoCarrera->id_tipo_carrera = $row['tipo_carrera_id'];
            $categoria = new Categoria();
            $categoria->id_categoria = $row['id_categoria'];
            $ruta = new Ruta();
            $ruta->id_ruta = $row['id_ruta'];
            $carreras[] = new Carrera($row['distancia'], $evento, $tipoCarrera, $categoria, $ruta);
        }
        return $carreras;
    }

    public function actualizarCarrera(Carrera $carrera) {
        $sql = "UPDATE carrera SET distancia = ?, id_evento = ?, tipo_carrera_id = ?, id_categoria = ?, id_ruta = ? WHERE id_carrera = ?";
        $params = [
            $carrera->getDistancia(),
            $carrera->getEvento() ? $carrera->getEvento()->getIdEvento() : null,
            $carrera->getTipoCarrera() ? $carrera->getTipoCarrera()->getIdTipoCarrera() : null,
            $carrera->getCategoria() ? $carrera->getCategoria()->getIdCategoria() : null,
            $carrera->getRuta() ? $carrera->getRuta()->getIdRuta() : null,
            $carrera->getIdCarrera()
        ];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function eliminarCarrera($id_carrera) {
        $sql = "DELETE FROM carrera WHERE id_carrera = ?";
        $params = [$id_carrera];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }
}