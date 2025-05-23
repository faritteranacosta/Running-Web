<?php 
require_once __DIR__ . '/Ciudad.php';

class Ubicacion{

    private $id_ubicacion;
    private $direccion;
    private $descripcion;
    private $coordenadas;
    private $ciudad;

    public function __construct($direccion = null, $descripcion = null, $coordenadas = null, Ciudad $ciudad = null) {
        $this->direccion = $direccion;
        $this->descripcion = $descripcion;
        $this->coordenadas = $coordenadas;
        $this->ciudad = $ciudad;
    }

    public function setIdUbicacion($id_ubicacion) {
        $this->id_ubicacion = $id_ubicacion;
    }
    public function getIdUbicacion() {
        return $this->id_ubicacion;
    }
    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }
    public function getDireccion() {
        return $this->direccion;
    }
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    public function getDescripcion() {
        return $this->descripcion;
    }
    public function setCoordenadas($coordenadas) {
        $this->coordenadas = $coordenadas;
    }
    public function getCoordenadas() {
        return $this->coordenadas;
    }
    public function setCiudad(Ciudad $ciudad) {
        $this->ciudad = $ciudad;
    }
    public function getCiudad() {
        return $this->ciudad;
    }
   
}