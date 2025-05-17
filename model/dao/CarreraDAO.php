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
     $sql = "SELECT c.*, e.nombre AS evento_nombre, e.fecha AS evento_fecha, e.descripcion AS evento_descripcion, e.hora AS hora_evento, cat.nombre AS categoria_nombre
             FROM carrera c
             LEFT JOIN evento e ON c.id_evento = e.id_evento
             LEFT JOIN categoria cat ON c.id_categoria = cat.id_categoria
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
         $categoria->setNombre($row['categoria_nombre']);
         $ruta = new Ruta();
         $ruta->setIdRuta($row['id_ruta']);
         $carrera = new Carrera($row['distancia'], $evento, $tipoCarrera, $categoria, $ruta);
         if (method_exists($carrera, 'setIdCarrera')) {
             $carrera->setIdCarrera($row['id_carrera']);
         }
         return $carrera;
     }
     return null;
 }

    public function listarCarreras() {
        $sql = "SELECT c.id_carrera, e.nombre AS nombre_evento, e.fecha AS fecha_evento, e.descripcion AS descripcion_evento, cat.nombre AS categoria_nombre, c.distancia
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
                'categoria' => $row['categoria_nombre']
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
}
?>