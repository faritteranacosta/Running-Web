<?php
require_once 'DataSource.php';
require_once '../model/Evento.php';

class EventoDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function insertarEvento(Evento $evento) {
        $sql = "INSERT INTO eventos (nombre, fecha, lugar, descripcion) VALUES (?, ?, ?, ?)";
        $params = [
            $evento->getNombreEvento(),
            $evento->getFechaEvento(),
            $evento->getHoraEvento(),
            $evento->getDescripcionEvento()
        ];
        return $this->dataSource->ejecutarConsulta($sql, $params);
    }

    public function actualizarEvento(Evento $evento) {
        $sql = "UPDATE eventos SET nombre = ?, fecha = ?, lugar = ?, descripcion = ? WHERE id = ?";
        $params = [
            $evento->getNombreEvento(),
            $evento->getFechaEvento(),
            $evento->getDescripcionEvento(),
            $evento->getHoraEvento(),
            $evento->getIdEvento()
        ];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function eliminarEvento($id) {
        $sql = "DELETE FROM eventos WHERE id = ?";
        $params = [$id];
        return $this->dataSource->ejecutarConsulta($sql, $params);
    }

    public function obtenerEventoPorId($id) {
        $sql = "SELECT * FROM eventos WHERE id = ?";
        $params = [$id];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            return new Evento($row['id'], $row['nombre'], $row['fecha'], $row['descripcion']);
        }
        return null;
    }

    public function obtenerTodosLosEventos() {
        $sql = "SELECT * FROM eventos";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $eventos = [];
        foreach ($result as $row) {
            $eventos[] = new Evento($row['id'], $row['nombre'], $row['fecha'], $row['descripcion']);
        }
        return $eventos;
    }
}
