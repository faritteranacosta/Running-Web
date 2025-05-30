<?php

class TipoCarrera {
    private $id_tipo_carrera;
    private $nombre;
    private $descripcion;


    public function __construct($nombre = null, $descripcion = null) {
        $this->id_tipo_carrera = null; // Inicializar id_tipo_carrera como null
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    public function getIdTipoCarrera() {
        return $this->id_tipo_carrera;
    }
    public function setIdTipoCarrera($id_tipo_carrera) {
        $this->id_tipo_carrera = $id_tipo_carrera;
    }   
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    public function getDescripcion() {
        return $this->descripcion;
    }

}