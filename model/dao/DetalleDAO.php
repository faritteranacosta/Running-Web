<?php
require_once 'DataSource.php';
require_once '../model/Detalle.php';

class DetalleDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function obtenerDetalles($id_evento) {
        $sql = "SELECT 
    e.id_evento,
    e.nombre AS nombre_evento,
    p.nombre AS nombre_patrocinador, 
    e.descripcion,
    e.fecha, 
    e.hora, 
    c.distancia, 
    cat.nombre AS categoria
FROM evento e
JOIN carrera c ON e.id_evento = c.id_evento
JOIN patrocinador p ON e.id_patrocidador = p.id_patrocidador
JOIN categoria cat ON c.id_categoria = cat.id_categoria
WHERE e.id_evento = ?";
        $result = $this->dataSource->ejecutarConsulta($sql, array($id_evento));
        $detalles= array();
        foreach ($result as $row) {
            $detalles[] = new Detalle(
                $row['nombre_evento'],
                $row['nombre_patrocinador'],
                $row['descripcion'],
                $row['fecha'],
                $row['hora'],
                $row['distancia'],
                $row['categoria'],
                $row['id_evento']
            );
        }
        return $detalles;
    }
}