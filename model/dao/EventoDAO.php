<?php

require_once __DIR__ . '/DataSource.php';

require_once __DIR__ . '/../entidad/Evento.php';
 
class EventoDAO {
    private $dataSource;
 
    public function __construct() {
        $this->dataSource = new DataSource();
    }
 
    public function insertarEvento(Evento $evento) {
        $idUbicacion = $this->resolverUbicacion($evento->getDireccion());

        $sql = "INSERT INTO evento (nombre, tipo, fecha, hora, descripcion, id_patrocinador, ubicacion_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $evento->getNombreEvento(),
            $evento->getTipoEvento(),
            $evento->getFechaEvento(),
            $evento->getHoraEvento(),
            $evento->getDescripcionEvento(),
            $evento->getidPatrocinador(),
            $idUbicacion
        ];
        $this->dataSource->ejecutarActualizacion($sql, $params);
        return $this->dataSource->getLastInsertId(); // Retorna el ID del último evento insertado
    }

    public function actualizarEvento(Evento $evento) {
        // Buscar si el evento ya tiene una ubicación asociada, para actualizarla
        // en vez de crear una nueva cada vez que se edita el evento.
        $actual = $this->dataSource->ejecutarConsulta(
            "SELECT ubicacion_id FROM evento WHERE id_evento = ?",
            [$evento->getIdEvento()]
        );
        $idUbicacionExistente = $actual[0]['ubicacion_id'] ?? null;
        $idUbicacion = $this->resolverUbicacion($evento->getDireccion(), $idUbicacionExistente);

        $sql = "UPDATE evento SET nombre = ?, tipo = ?, fecha = ?, hora = ?, descripcion = ?, id_patrocinador = ?, ubicacion_id = ? WHERE id_evento = ?";
        $params = [
            $evento->getNombreEvento(),
            $evento->getTipoEvento(),
            $evento->getFechaEvento(),
            $evento->getHoraEvento(),
            $evento->getDescripcionEvento(),
            $evento->getidPatrocinador(), // Usar el ID del patrocinador
            $idUbicacion,
            $evento->getIdEvento()
        ];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    /**
     * La tabla `evento` no guarda la dirección directamente: la relaciona por
     * `ubicacion_id` con la tabla `ubicacion`, que a su vez exige una ciudad.
     * Como el formulario de crear/editar evento solo pide un texto de
     * dirección (sin ciudad), esta función crea o actualiza la fila de
     * `ubicacion` correspondiente y devuelve su id.
     */
    private function resolverUbicacion($direccion, $idUbicacionExistente = null) {
        if ($idUbicacionExistente) {
            $this->dataSource->ejecutarActualizacion(
                "UPDATE ubicacion SET direccion = ? WHERE id_ubicacion = ?",
                [$direccion, $idUbicacionExistente]
            );
            return $idUbicacionExistente;
        }

        // Ciudad por defecto: no hay campo de ciudad en el formulario actual,
        // así que se usa la primera ciudad registrada como valor temporal.
        $ciudad = $this->dataSource->ejecutarConsulta("SELECT MIN(id_ciudad) AS id FROM ciudad");
        $idCiudad = $ciudad[0]['id'] ?? null;
        if (!$idCiudad) {
            throw new Exception("No hay ciudades registradas para asociar la ubicación del evento");
        }

        $this->dataSource->ejecutarActualizacion(
            "INSERT INTO ubicacion (direccion, id_ciudad) VALUES (?, ?)",
            [$direccion, $idCiudad]
        );
        return $this->dataSource->getLastInsertId();
    }
 
    public function eliminarEvento($id_evento) {
        $sql = "DELETE FROM evento WHERE id_evento = ?";
        $params = [$id_evento];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }
 
    public function obtenerEventoPorId($id_evento) {

        $sql = "SELECT e.*, u.direccion AS direccion
                FROM evento e
                LEFT JOIN ubicacion u ON e.ubicacion_id = u.id_ubicacion
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
        $sql = "SELECT e.*, u.direccion AS direccion
                FROM evento e
                LEFT JOIN ubicacion u ON e.ubicacion_id = u.id_ubicacion";
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