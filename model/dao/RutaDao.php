<?php
require_once __DIR__ . '/DataSource.php';
require_once __DIR__ . '/../entidad/Ruta.php';

class RutaDao {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function guardar(Ruta $ruta) {
        $sql = "INSERT INTO rutas (usuario_id, nombre, puntos, distancia, fecha_creacion) 
                VALUES (:usuario_id, :nombre, :puntos, :distancia, :fecha_creacion)";

        $params = [
            ':usuario_id' => $ruta->getUsuarioId(),
            ':nombre' => $ruta->getNombreRuta(),
            ':puntos' => json_encode($ruta->getPuntosRuta()),
            ':distancia' => $ruta->getDistanciaRuta(),
            ':fecha_creacion' => $ruta->getFechaCreacionRuta()
        ];

        $result = $this->dataSource->ejecutarActualizacion($sql, $params);

        if ($result > 0) {
            $ruta->setIdRuta($this->dataSource->getLastInsertId());
            return $ruta;
        }
        return null;
    }

    public function eliminar($id) {
        $sql = "DELETE FROM rutas WHERE id = :id";
        $params = [':id' => $id];
        return $this->dataSource->ejecutarActualizacion($sql, $params) > 0;
    }

    public function obtenerRuta($id) {
        $sql = "SELECT * FROM rutas WHERE id = :id";
        $params = [':id' => $id];
        $rows = $this->dataSource->ejecutarConsulta($sql, $params);

        if ($rows && count($rows) > 0) {
            $row = $rows[0];
            return new Ruta(
                $row['id'],
                $row['usuario_id'],
                $row['nombre'],
                json_decode($row['puntos'], true),
                $row['distancia'],
                $row['fecha_creacion']
            );
        }
        return null;
    }

    public function obtenerTodasLasRutas() {
        $sql = "SELECT * FROM rutas";
        $rows = $this->dataSource->ejecutarConsulta($sql);

        $rutas = [];
        foreach ($rows as $row) {
            $rutas[] = new Ruta(
                $row['id'],
                $row['usuario_id'],
                $row['nombre'],
                json_decode($row['puntos'], true),
                $row['distancia'],
                $row['fecha_creacion']
            );
        }
        return $rutas;
    }
    // Otros métodos como buscarPorId, listarPorUsuario, etc.
}
?>