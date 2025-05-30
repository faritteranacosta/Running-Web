<?php

require_once __DIR__ . '/DataSource.php';

require_once __DIR__ . '/../entidad/Evento.php';
 
class EventoDAO {
    private $dataSource;
 
    public function __construct() {
        $this->dataSource = new DataSource();
    }
 
    public function insertarEvento(Evento $evento) {
        $sql = "INSERT INTO evento (nombre, tipo, fecha, hora, descripcion, id_patrocinador, direccion) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $evento->getNombreEvento(),
            $evento->getTipoEvento(),
            $evento->getFechaEvento(),
            $evento->getHoraEvento(),
            $evento->getDescripcionEvento(),
            $evento->getidPatrocinador(),
            $evento->getDireccion()
        ];
        $this->dataSource->ejecutarActualizacion($sql, $params);
        return $this->dataSource->getLastInsertId(); // Retorna el ID del último evento insertado
    }
 
    public function actualizarEvento(Evento $evento) {
        $sql = "UPDATE evento SET nombre = ?, tipo = ?, fecha = ?, hora = ?, descripcion = ?, id_patrocinador = ?, direccion = ? WHERE id_evento = ?";
        $params = [
            $evento->getNombreEvento(),
            $evento->getTipoEvento(),
            $evento->getFechaEvento(),
            $evento->getHoraEvento(),
            $evento->getDescripcionEvento(),
            $evento->getidPatrocinador(), // Usar el ID del patrocinador
            $evento->getDireccion(),     // Usar el ID de la ubicación
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

        $sql = "SELECT * FROM evento e
                WHERE e.id_evento = ?";
        $params = [$id_evento];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            $evento = new Evento(
                $row['nombre'],
                $row['tipo'],
                $row['fecha'],
                $row['hora'],
                $row['descripcion'],
                $row['id_patrocinador'],
                $row['direccion'] 
            );
            $evento->setIdEvento($row['id_evento']);
            return $evento;
        }
        return null;
    }
 
    public function obtenerTodosLosEventos() {
        $sql = "SELECT * FROM evento e";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $eventos = [];
        foreach ($result as $row) {
            $evento = new Evento(
                $row['nombre'],
                $row['tipo'],
                $row['fecha'],
                $row['hora'],
                $row['descripcion'],
                $row['id_patrocinador'],
                $row['direccion']
            );
            $evento->setIdEvento($row['id_evento']);
            $eventos[] = $evento;
        }
        return $eventos;
    }
}
 