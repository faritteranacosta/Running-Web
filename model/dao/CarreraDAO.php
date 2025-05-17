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
    $sql = "SELECT c.*, e.hora AS hora_evento, e.nombre AS evento_nombre, e.fecha AS evento_fecha,
                   e.descripcion AS evento_descripcion, p.nombre AS patrocinador_nombre, cat.nombre AS nombre_categoria,
                   r.descripcion AS descripcion_ruta, r.url_mapa
            FROM carrera c
            LEFT JOIN evento e ON c.id_evento = e.id_evento
            LEFT JOIN patrocinador p ON e.id_patrocinador = p.id_patrocidador -- Asegúrate de que esta unión es correcta
            LEFT JOIN categoria cat ON c.id_categoria = cat.id_categoria
            LEFT JOIN ruta r ON c.id_ruta = r.id_ruta
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
        // Crear instancia de Patrocinador y asignar el nombre
        $patrocinador = new Patrocinador();
        $patrocinador->setNombre($row['patrocinador_nombre']);
        $evento->setPatrocinador($patrocinador); // Asignar el patrocinador al evento
        $tipoCarrera = new TipoCarrera();
        $tipoCarrera->setIdTipoCarrera($row['tipo_carrera_id']);
        $categoria = new Categoria();
        $categoria->setIdCategoria($row['id_categoria']);
        $categoria->setNombre($row['nombre_categoria']);
        $ruta = new Ruta();
        $ruta->setIdRuta($row['id_ruta']);
        $ruta->setDescripcionRuta($row['descripcion_ruta']);
        $ruta->setUrlMapa($row['url_mapa']);
        $carrera = new Carrera($row['distancia'], $evento, $tipoCarrera, $categoria, $ruta);
        if (method_exists($carrera, 'setIdCarrera')) {
            $carrera->setIdCarrera($row['id_carrera']);
        }
        return $carrera;
    }
    return null;
}

    public function listarCarreras() {
        $sql = "SELECT c.id_carrera, e.nombre AS nombre_evento, e.fecha AS fecha_evento, e.descripcion AS descripcion_evento, c.distancia, cat.nombre AS nombre_categoria
            FROM carrera c
            LEFT JOIN evento e ON c.id_evento = e.id_evento
            LEFT JOIN categoria cat ON c.id_categoria = cat.id_categoria";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $carreras = array();
        foreach ($result as $row) {
            $carreras[] = array(
                'idCarrera' => $row['id_carrera'],
                'nombre' => $row['nombre_evento'],
                'descripcion' => $row['descripcion_evento'],
                'fecha' => $row['fecha_evento'],
                'distancia' => $row['distancia'],
                'categoria' => $row['nombre_categoria']
            );
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



    //agregar una participacion a un evento
    public function agregarParticipacion($id_usuario, $id_evento) {
    // Cambiar los nombres de columna para que coincidan con la base de datos
    $sql = "INSERT INTO participacion_evento (corredor_id, evento_id) VALUES (?, ?)";
    $params = [$id_usuario, $id_evento];
    return $this->dataSource->ejecutarActualizacion($sql, $params);
}
}
?>