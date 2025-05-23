<?php

class Ruta {
    private $id;
    private $usuarioId;
    private $nombre;
    private $puntos;
    private $distancia;
    private $fechaCreacion;

    public function __construct($id = null, $usuarioId = null, $nombre = null, $puntos = null, $distancia = null, $fechaCreacion = null) {
        $this->id = $id;
        $this->usuarioId = $usuarioId;
        $this->nombre = $nombre;
        $this->puntos = $puntos;
        $this->distancia = $distancia;
        $this->fechaCreacion = $fechaCreacion;
    }

    // Getters y Setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUsuarioId() {
        return $this->usuarioId;
    }

    public function setUsuarioId($usuarioId) {
        $this->usuarioId = $usuarioId;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getPuntos() {
        return $this->puntos;
    }

    public function setPuntos($puntos) {
        $this->puntos = $puntos;
    }

    public function getDistancia() {
        return $this->distancia;
    }

    public function setDistancia($distancia) {
        $this->distancia = $distancia;
    }

    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }
}
?>