<?php

require_once __DIR__ . '/DataSource.php';

require_once __DIR__ . '/../entidad/Evento.php';
 
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
            $evento->getidPatrocinador(),
            $evento->getidUbicacion()
        ];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }
 
    public function actualizarEvento(Evento $evento) {
        $sql = "UPDATE evento SET nombre = ?, tipo = ?, fecha = ?, hora = ?, descripcion = ?, id_patrocinador = ?, ubicacion_id = ? WHERE id_evento = ?";
        $params = [
            $evento->getNombreEvento(),
            $evento->getTipoEvento(),
            $evento->getFechaEvento(),
            $evento->getHoraEvento(),
            $evento->getDescripcionEvento(),
            $evento->getidPatrocinador(), // Usar el ID del patrocinador
            $evento->getidUbicacion(),     // Usar el ID de la ubicación
            $evento->getIdEvento()
        ];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }
 
    public function eliminarEvento($id_evento) {
        $sql = "DELETE FROM evento WHERE id_evento = ?";
        $params = [$id_evento];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }
 
    public function obtenerEventoPorId($id_evento) {

        $sql = "SELECT e.*, u.direccion, u.descripcion AS ubicacion_descripcion, u.coordenadas, c.nombre AS ciudad_nombre, c.id_ciudad FROM evento e

                JOIN ubicacion u ON e.ubicacion_id = u.id_ubicacion

                JOIN ciudad c ON u.id_ciudad = c.id_ciudad

                WHERE e.id_evento = ?";
        $params = [$id_evento];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            $ciudad = new Ciudad($row['ciudad_nombre']);
            $ciudad->setIdCiudad($row['id_ciudad']);
            $ubicacion = new Ubicacion($row['direccion'], $row['ubicacion_descripcion'], $row['coordenadas'], $ciudad);
            $ubicacion->setIdUbicacion($row['ubicacion_id']);
            $evento = new Evento(
                $row['nombre'],
                $row['tipo'],
                $row['fecha'],
                $row['hora'],
                $row['descripcion'],
                $row['id_patrocinador'],

                $ubicacion

            );
            $evento->setIdEvento($row['id_evento']);
            return $evento;
        }
        return null;
    }
 
    public function obtenerTodosLosEventos() {
        $sql = "SELECT e.*, u.direccion, u.descripcion AS ubicacion_descripcion, u.coordenadas, u.id_ubicacion, c.nombre AS ciudad_nombre, c.id_ciudad FROM evento e
                JOIN ubicacion u ON e.ubicacion_id = u.id_ubicacion
                JOIN ciudad c ON u.id_ciudad = c.id_ciudad";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $eventos = [];
        foreach ($result as $row) {
            $ciudad = new Ciudad($row['ciudad_nombre']);
            $ciudad->setIdCiudad($row['id_ciudad']);
            $coordenadas = isset($row['coordenadas']) ? $row['coordenadas'] : null;
            $ubicacion = new Ubicacion($row['direccion'], $row['ubicacion_descripcion'], $coordenadas, $ciudad);
            if (isset($row['id_ubicacion'])) {
                $ubicacion->setIdUbicacion($row['id_ubicacion']);

            }
            $evento = new Evento(
                $row['nombre'],
                $row['tipo'],
                $row['fecha'],
                $row['hora'],
                $row['descripcion'],
                $row['id_patrocinador'],

                $ubicacion

            );
            $evento->setIdEvento($row['id_evento']);
            $eventos[] = $evento;
        }
        return $eventos;
    }
}
 