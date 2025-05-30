<?php

use LDAP\Result;

require_once __DIR__ . '/../entidad/ParticipacionEvento.php';
require_once __DIR__ . '/DataSource.php';

class ParticipacionEventoDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function agregarParticipacion($id_usuario, $id_evento) {
        // Obtener el id_corredor a partir del id_usuario
        $sql1 = "SELECT id_corredor FROM corredor WHERE usuario_id = ?";
        $params1 = [$id_usuario];
        $result1 = $this->dataSource->ejecutarConsulta($sql1, $params1);
        if (empty($result1)) {
            return false; // No existe corredor para ese usuario
        }
        $id_corredor = $result1[0]['id_corredor'];

        // Insertar la participación usando el id_corredor
        $sql = "INSERT INTO participacion_evento (corredor_id, evento_id) VALUES (?, ?)";
        $params = [$id_corredor, (int)$id_evento];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function obtenerParticipacionesPorUsuario($id_usuario) {
        // Buscar el id_corredor correspondiente al id_usuario
        $sql1 = "SELECT id_corredor FROM corredor WHERE usuario_id = ?";
        $params1 = [$id_usuario];
        $result1 = $this->dataSource->ejecutarConsulta($sql1, $params1);
        if (empty($result1)) {
            return []; // No existe corredor para ese usuario
        }
        $id_corredor = $result1[0]['id_corredor'];

        // Ahora sí, buscar las participaciones usando el id_corredor
        $sql = "SELECT c.id_carrera, c.distancia, e.id_evento, e.nombre AS nombre_evento, e.fecha AS fecha_evento, e.hora AS hora_evento, e.descripcion AS descripcion_evento
                FROM participacion_evento p
                JOIN evento e ON p.evento_id = e.id_evento
                JOIN carrera c ON c.id_evento = e.id_evento
                WHERE p.corredor_id = ?";
        $params = array($id_corredor);
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

            // Guarda el id_carrera y la distancia en el objeto ParticipacionEvento para que estén disponibles en el backend AJAX
            $participacion->id_carrera = $row['id_carrera'];
            $participacion->distancia = $row['distancia'];

            $participaciones[] = $participacion;
        }
        return $participaciones;
    }

    public function eliminarParticipacion($id_usuario, $id_evento) {
        // Buscar el id_corredor correspondiente al id_usuario
        $sql1 = "SELECT id_corredor FROM corredor WHERE usuario_id = ?";
        $params1 = [$id_usuario];
        $result1 = $this->dataSource->ejecutarConsulta($sql1, $params1);
        if (empty($result1)) {
            return false; // No existe corredor para ese usuario
        }
        $id_corredor = $result1[0]['id_corredor'];

        // Eliminar la participación usando el id_corredor
        $sql = "DELETE FROM participacion_evento WHERE corredor_id = ? AND evento_id = ?";
        $params = array($id_corredor, $id_evento);
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function existeParticipacion($id_usuario, $id_evento) {
        // Buscar el id_corredor correspondiente al id_usuario
        $sql1 = "SELECT id_corredor FROM corredor WHERE usuario_id = ?";
        $params1 = [$id_usuario];
        $result1 = $this->dataSource->ejecutarConsulta($sql1, $params1);
        if (empty($result1)) {
            return false;
        }
        $id_corredor = $result1[0]['id_corredor'];

        // Verificar existencia usando el id_corredor
        $sql = "SELECT COUNT(*) as total FROM participacion_evento WHERE corredor_id = ? AND evento_id = ?";
        $params = [$id_corredor, $id_evento];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        return isset($result[0]['total']) && $result[0]['total'] > 0;
    }


}