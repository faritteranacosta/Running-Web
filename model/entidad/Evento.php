<?php  require_once __DIR__ . '/Conexion.php'; 

class Evento { 
    
    private $id_evento;
    private $nombre_evento;
    private $fecha_evento;
    private $hora_evento;
    private $descripcion_evento;
    
    public function __construct($nombre_evento = null, $fecha_evento = null, $hora_evento = null, $descripcion_evento = null) {
        $this->nombre_evento = $nombre_evento;
        $this->fecha_evento = $fecha_evento;
        $this->hora_evento = $hora_evento;
        $this->descripcion_evento = $descripcion_evento;
    }

    public function setNombreEvento($nombre_evento) {
        $this->nombre_evento = $nombre_evento;
    }

    public function setFechaEvento($fecha_evento) {
        $this->fecha_evento = $fecha_evento;
    }

    public function setHoraEvento($hora_evento) {
        $this->hora_evento = $hora_evento;
    }

    public function setDescripcionEvento($descripcion_evento) {
        $this->descripcion_evento = $descripcion_evento;
    }
    
    public function getIdEvento() {
        return $this->id_evento;
    }

    public function getNombreEvento() {
        return $this->nombre_evento;
    }
    
    public function getFechaEvento() {
        return $this->fecha_evento;
    }
    
    public function getHoraEvento() {
        return $this->hora_evento;
    }
    
    public function getDescripcionEvento() {
        return $this->descripcion_evento;
    }
    

}   