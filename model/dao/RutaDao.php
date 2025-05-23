<?php
require_once __DIR__.'/../config/DataSource.php';
require_once __DIR__.'/../models/Ruta.php';

class RutaDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function guardar(Ruta $ruta) {
        $sql = "INSERT INTO ruta (usuario_id, nombre, puntos, distancia) 
                VALUES (:usuario_id, :nombre, :puntos, :distancia)";

        $params = [
            ':usuario_id' => $ruta->getUsuarioId(),
            ':nombre' => $ruta->getNombre(),
            ':puntos' => json_encode($ruta->getPuntos()),
            ':distancia' => $ruta->getDistancia()
        ];

        $result = $this->dataSource->ejecutarActualizacion($sql, $params);
        
        if($result > 0) {
            $ruta->setId($this->dataSource->getLastInsertId());
            return $ruta;
        }
        
        return null;
    }

    public function eliminar($id) {
        $sql = "DELETE FROM ruta WHERE id = :id";
        $params = [':id' => $id];
        return $this->dataSource->ejecutarActualizacion($sql, $params) > 0;
    }

    public function obtenerRuta($id) {
        $sql = "SELECT * FROM ruta WHERE id = :id";
        $params = [':id' => $id];
        $row = $this->dataSource->ejecutarConsulta($sql, $params);

        if ($row) {
            return new Ruta(
                $row['id'],
                $row['usuario_id'],
                $row['nombre'],
                json_decode($row['puntos'], true),
                $row['distancia']
            );
        }
        return null;
    }

    public function obtenerTodasLasRutas() {
        $sql = "SELECT * FROM ruta";
        $rows = $this->dataSource->ejecutarConsulta($sql);

        $rutas = [];
        foreach ($rows as $row) {
            $rutas[] = new Ruta(
                $row['id'],
                $row['usuario_id'],
                $row['nombre'],
                json_decode($row['puntos'], true),
                $row['distancia']
            );
        }
        return $rutas;
    }
    // Otros métodos como buscarPorId, listarPorUsuario, etc.
}
?>