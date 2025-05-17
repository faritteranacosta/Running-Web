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
            $carrera->getEvento(),
            $carrera->getTipoCarrera(),
            $carrera->getCategoria(),
            $carrera->getRuta()
        ];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function obtenerCarreraPorId($id_carrera) {
        $sql = "SELECT c.*, e.hora AS hora_evento, e.nombre AS evento_nombre, e.fecha AS evento_fecha, e.descripcion AS evento_descripcion
                FROM carrera c
                LEFT JOIN evento e ON c.id_evento = e.id_evento
                WHERE c.id_carrera = ?";
        $params = [$id_carrera];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            $evento = new Evento();
            $evento->setIdEvento($row['id_evento']);
            $evento->setNombreEvento($row['evento_nombre']);
            $evento->setFechaEvento($row['evento_fecha']);
            $evento->setDescripcionEvento($row['evento_descripcion']);
            $evento->setHoraEvento($row['hora_evento']);
            $tipoCarrera = new TipoCarrera();
            $tipoCarrera->setIdTipoCarrera($row['tipo_carrera_id']);
            $categoria = new Categoria();
            $categoria->setIdCategoria($row['id_categoria']);
            $ruta = new Ruta();
            $ruta->setIdRuta($row['id_ruta']);
            return new Carrera($row['distancia'], $evento, $tipoCarrera, $categoria, $ruta);
        }
        return null;
    }

    public function listarCarreras() {
        $sql = "SELECT c.*, e.nombre AS evento_nombre, e.fecha AS evento_fecha, e.descripcion AS evento_descripcion, cat.nombre AS categoria_nombre FROM carrera c
                LEFT JOIN evento e ON c.id_evento = e.id_evento
                LEFT JOIN categoria cat ON c.id_categoria = cat.id_categoria";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $carreras = array();
        foreach ($result as $row) {
            $evento = new Evento();
            $evento->setIdEvento($row['id_evento']);
            $evento->setNombreEvento($row['evento_nombre']);
            $evento->setFechaEvento($row['evento_fecha']);
            $evento->setDescripcionEvento($row['evento_descripcion']);
            $tipoCarrera = new TipoCarrera();
            $tipoCarrera->setIdTipoCarrera($row['tipo_carrera_id']);
            $categoria = new Categoria();
            $categoria->setIdCategoria($row['id_categoria']);
            $categoria->setNombre($row['categoria_nombre']);
            $ruta = new Ruta();
            $ruta->setIdRuta($row['id_ruta']);
            $carrera = new Carrera($row['distancia'], $evento, $tipoCarrera, $categoria, $ruta);
            // Set id_carrera si existe el método
            if (method_exists($carrera, 'setIdCarrera')) {
                $carrera->setIdCarrera($row['id_carrera']);
            }
            $carreras[] = $carrera;
        }
        return $carreras;
    }

    public function actualizarCarrera(Carrera $carrera) {
        $sql = "UPDATE carrera SET distancia = ?, id_evento = ?, tipo_carrera_id = ?, id_categoria = ?, id_ruta = ? WHERE id_carrera = ?";
        $params = [
            $carrera->getDistancia(),
            $carrera->getEvento(),
            $carrera->getTipoCarrera(),
            $carrera->getCategoria(),
            $carrera->getRuta(),
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