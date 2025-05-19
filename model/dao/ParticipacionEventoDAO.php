<?php

require_once __DIR__ . '/../entidad/ParticipacionEvento.php';
require_once __DIR__ . '/DataSource.php';

class ParticipacionEventoDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function agregarParticipacion($id_usuario, $id_evento) {
    $sql = "INSERT INTO participacion_evento (usuario_id, evento_id) VALUES (?, ?)";
    $params = [$id_usuario, $id_evento];
    return $this->dataSource->ejecutarActualizacion($sql, $params);
}

    public function obtenerParticipacionesPorUsuario($id_usuario) {
        $sql = "SELECT p.*, e.nombre_evento, e.fecha_evento, e.hora_evento, e.descripcion_evento
                FROM participacion_evento p
                JOIN evento e ON p.id_evento = e.id_evento
                WHERE p.id_usuario = ?";
        $params = array($id_usuario);
        $result = $this->dataSource->executeQuery($sql, $params);
        $participaciones = array();
        foreach ($result as $row) {
            $evento = new Evento();
            $evento->setIdEvento($row['id_evento']);
            $evento->setNombreEvento($row['nombre_evento']);
            $evento->setFechaEvento($row['fecha_evento']);
            $evento->setHoraEvento($row['hora_evento']);
            $evento->setDescripcionEvento($row['descripcion_evento']);
            $participacion = new ParticipacionEvento();
            $participacion->setIdParticipacion($row['id_participacion']);
            $participacion->setUsuario(new Usuario($id_usuario)); // Asignar el usuario
            $participacion->setEvento($evento);
            $participaciones[] = $participacion;
        }
        return $participaciones;
    }

    public function eliminarParticipacion($id_participacion) {
        $sql = "DELETE FROM participacion_evento WHERE id_participacion = ?";
        $params = array($id_participacion);
        return $this->dataSource->execute($sql, $params);
    }


}