<?php
require_once 'DataSource.php';
require_once '../model/Detalle.php';

class DetalleDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function obtenerDetalles() {
        $sql = "SELECT 
    e.nombre_evento,
    p.nombre_patrocinador, 
    e.descripcion_evento,
    e.fecha_evento, 
    e.hora_evento, 
    c.distancia, 
    c.categoria
FROM evento e
JOIN carrera c ON e.id_evento = c.id_evento
JOIN patrocinador p ON e.id_patrocinador = p.id_patrocinador";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $detalles= array();
        foreach ($result as $row) {
            $detalles[] = new Detalle(
                $row['nombre_evento'],
                $row['nombre_patrocinador'],
                $row['descripcion_evento'],
                $row['fecha_evento'],
                $row['hora_evento'],
                $row['distancia'],
                $row['categoria']
            );
        }
        return $detalles;
    }

    public function obtenerDetallesPorId($id_evento) {
        $sql = "SELECT 
    e.nombre_evento,
    p.nombre_patrocinador, 
    e.descripcion_evento,
    e.fecha_evento, 
    e.hora_evento, 
    c.distancia, 
    c.categoria
FROM evento e
JOIN carrera c ON e.id_evento = c.id_evento
JOIN patrocinador p ON e.id_patrocinador = p.id_patrocinador
WHERE e.id_evento = ?";
        $result = $this->dataSource->ejecutarConsulta($sql, array($id_evento));
        $detalles= array();
        foreach ($result as $row) {
            $detalles[] = new Detalle(
                $row['nombre_evento'],
                $row['nombre_patrocinador'],
                $row['descripcion_evento'],
                $row['fecha_evento'],
                $row['hora_evento'],
                $row['distancia'],
                $row['categoria']
            );
        }
        return $detalles;
    }
}