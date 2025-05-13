<?php
require_once 'DataSource.php';
require_once '../model/Evento.php';

class EventoDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function insertarEvento(Evento $evento) {
        $sql = "INSERT INTO evento (nombre, tipo, fecha, hora, descripcion, id_patrocinador, ubicacion_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $evento->getNombreEvento(),
            $evento->getTipoEvento(),
            $evento->getFechaEvento(),
            $evento->getHoraEvento(),
            $evento->getDescripcionEvento(),
            $evento->getPatrocinador() ? $evento->getPatrocinador()->getIdPatrocinador() : null,
            $evento->getUbicacion() ? $evento->getUbicacion()->getIdUbicacion() : null
        ];
        return $this->dataSource->ejecutarConsulta($sql, $params);
    }

    public function actualizarEvento(Evento $evento) {
        $sql = "UPDATE evento SET nombre = ?, tipo = ?, fecha = ?, hora = ?, descripcion = ?, id_patrocinador = ?, ubicacion_id = ? WHERE id_evento = ?";
        $params = [
            $evento->getNombreEvento(),
            $evento->getTipoEvento(),
            $evento->getFechaEvento(),
            $evento->getHoraEvento(),
            $evento->getDescripcionEvento(),
            $evento->getPatrocinador() ? $evento->getPatrocinador()->getIdPatrocinador() : null,
            $evento->getUbicacion() ? $evento->getUbicacion()->getIdUbicacion() : null,
            $evento->getIdEvento()
        ];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function eliminarEvento($id_evento) {
        $sql = "DELETE FROM evento WHERE id_evento = ?";
        $params = [$id_evento];
        return $this->dataSource->ejecutarConsulta($sql, $params);
    }

    public function obtenerEventoPorId($id_evento) {
        $sql = "SELECT * FROM evento WHERE id_evento = ?";
        $params = [$id_evento];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            // Instanciar Patrocinador y Ubicacion si es necesario
            $patrocinador = null;
            $ubicacion = null;
            return new Evento(
                $row['nombre'],
                $row['tipo'],
                $row['fecha'],
                $row['hora'],
                $row['descripcion'],
                $patrocinador,
                $ubicacion
            );
        }
        return null;
    }

    public function obtenerTodosLosEventos() {
        $sql = "SELECT * FROM evento";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $eventos = [];
        foreach ($result as $row) {
            $patrocinador = null;
            $ubicacion = null;
            $eventos[] = new Evento(
                $row['nombre'],
                $row['tipo'],
                $row['fecha'],
                $row['hora'],
                $row['descripcion'],
                $patrocinador,
                $ubicacion
            );
        }
        return $eventos;
    }
}
