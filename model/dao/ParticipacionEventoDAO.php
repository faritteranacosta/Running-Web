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

    public function obtenerParticipacionesPorUsuario($id_usuario) {/// se va a usar solo para las carreras
    $sql = "SELECT c.id_carrera, e.id_evento, e.nombre AS nombre_evento, e.fecha AS fecha_evento, e.hora AS hora_evento, e.descripcion AS descripcion_evento
            FROM participacion_evento p
            JOIN evento e ON p.evento_id = e.id_evento
            JOIN carrera c ON c.id_evento = e.id_evento
            WHERE p.usuario_id = ?";
    $params = array($id_usuario);
    $result = $this->dataSource->ejecutarConsulta($sql, $params);
    $participaciones = array();
    foreach ($result as $row) {
        $evento = new Evento();
        $evento->setIdEvento($row['id_evento']);
        $evento->setNombreEvento($row['nombre_evento']);
        $evento->setFechaEvento($row['fecha_evento']);
        $evento->setHoraEvento($row['hora_evento']);
        $evento->setDescripcionEvento($row['descripcion_evento']);
        
    $participacion = new ParticipacionEvento(new Usuario($id_usuario), $evento);

    // Guarda el id_carrera en el objeto ParticipacionEvento para que esté disponible en el backend AJAX
    $participacion->id_carrera = $row['id_carrera'];

    $participaciones[] = $participacion;
    }
    return $participaciones;
}

    public function eliminarParticipacion($id_usuario, $id_evento) {
        $sql = "DELETE FROM participacion_evento WHERE usuario_id = ? AND evento_id = ?";
        $params = array($id_usuario, $id_evento);
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function existeParticipacion($id_usuario, $id_evento) {
        $sql = "SELECT COUNT(*) as total FROM participacion_evento WHERE usuario_id = ? AND evento_id = ?";
        $params = [$id_usuario, $id_evento];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        return isset($result[0]['total']) && $result[0]['total'] > 0;
    }


}