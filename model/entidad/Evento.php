<?php
require_once __DIR__ . '/Patrocinador.php';
require_once __DIR__ . '/Ubicacion.php';


class Evento { 
    
    private $id_evento;
    private $nombre_evento;
    private $tipo_evento;
    private $fecha_evento;
    private $hora_evento;
    private $descripcion_evento;
    private $patrocinador; //instancia de Patrocinador
    private $direccion; //instancia de Ubicacion


    public function __construct($nombre_evento = null, $tipo_evento = null, $fecha_evento = null, $hora_evento = null, $descripcion_evento = null, $patrocinador = null, $direccion = null) {
        $this->nombre_evento = $nombre_evento;
        $this->tipo_evento = $tipo_evento;
        $this->fecha_evento = $fecha_evento;
        $this->hora_evento = $hora_evento;
        $this->descripcion_evento = $descripcion_evento;
        $this->patrocinador = $patrocinador;
        $this->direccion = $direccion;
    }


    public function getIdEvento() {
        return $this->id_evento;
    }

    public function setIdEvento($id_evento) {
        $this->id_evento = $id_evento;
    }

    public function setNombreEvento($nombre_evento) {
        $this->nombre_evento = $nombre_evento;
    }
    
    public function getNombreEvento() {
        return $this->nombre_evento;
    }
    
    public function setTipoEvento($tipo_evento) {
        $this->tipo_evento = $tipo_evento;
    }
    
    public function getTipoEvento() {
        return $this->tipo_evento;
    }
    
    public function setFechaEvento($fecha_evento) {
        $this->fecha_evento = $fecha_evento;
    }
    
    public function getFechaEvento() {
        return $this->fecha_evento;
    }
    
    public function setHoraEvento($hora_evento) {
        $this->hora_evento = $hora_evento;
    }
    
    public function getHoraEvento() {
        return $this->hora_evento;
    }
    
    public function setDescripcionEvento($descripcion_evento) {
        $this->descripcion_evento = $descripcion_evento;
    }
    
    public function getDescripcionEvento() {
        return $this->descripcion_evento;
    }
    
    public function setPatrocinador(Patrocinador $patrocinador) {
        $this->patrocinador = $patrocinador;
    }
    
    public function getPatrocinador() {
        return $this->patrocinador;
    }
    
    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }
    
    public function getDireccion() {
        return $this->direccion;
    }
    
    public function getidPatrocinador() {
        if (is_object($this->patrocinador) && method_exists($this->patrocinador, 'getIdPatrocinador')) {
            return $this->patrocinador->getIdPatrocinador();
        }
        // Si es un string o int, simplemente lo retorna
        return $this->patrocinador;
    }

}