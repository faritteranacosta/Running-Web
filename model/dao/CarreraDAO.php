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
    $sql = "SELECT c.id_carrera, e.nombre AS nombre_evento, e.fecha AS fecha_evento, e.descripcion AS descripcion_evento, e.hora AS hora_evento, c.distancia, cat.nombre AS categoria_nombre, u.descripcion AS punto_encuentro
            FROM carrera c
            LEFT JOIN evento e ON c.id_evento = e.id_evento
            LEFT JOIN categoria cat ON c.id_categoria = cat.id_categoria
            LEFT JOIN ubicacion u ON e.id_ubicacion = u.id_ubicacion
            WHERE c.id_carrera = ?";
    $params = [$id_carrera];
    $result = $this->dataSource->ejecutarConsulta($sql, $params);
    if (count($result) > 0) {
        $row = $result[0];
        return array(
            'idCarrera' => $row['id_carrera'],
            'nombre' => $row['nombre_evento'],
            'descripcion' => $row['descripcion_evento'],
            'fecha' => $row['fecha_evento'],
            'hora' => $row['hora_evento'],
            'distancia' => $row['distancia'],
            'categoria' => $row['categoria_nombre'],
            'punto_encuentro' => $row['punto_encuentro']
        );
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
    
}