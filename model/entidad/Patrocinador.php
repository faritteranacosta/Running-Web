<?php
require_once __DIR__ . '/Ciudad.php';

class Patrocinador {
    private $id_patrocinador;
    private $nombre;
    private $ciudad; //instancia de Ciudad


    public function __construct($nombre = null, $ciudad = null) {
        $this->nombre = $nombre;
        $this->ciudad = $ciudad;
    }

    public function getIdPatrocinador() {
        return $this->id_patrocinador;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function setCiudad(Ciudad $ciudad) {
        $this->ciudad = $ciudad;
    }
    public function getCiudad() {
        return $this->ciudad;
    }



}