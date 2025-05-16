<?php
class Categoria {
    private $id_categoria;
    private $nombre;
    private $descripcion;

    public function __construct($nombre = null, $descripcion = null) {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }
    public function getIdCategoria() {
        return $this->id_categoria;
    }
    public function setIdCategoria($id_categoria) {
        $this->id_categoria = $id_categoria;
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