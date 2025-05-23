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
    public function getIdRuta() {
        return $this->id;
    }

    public function setIdRuta($id) {
        $this->id = $id;
    }

    public function getUsuarioId() {
        return $this->usuarioId;
    }

    public function setUsuarioId($usuarioId) {
        $this->usuarioId = $usuarioId;
    }

    public function getNombreRuta() {
        return $this->nombre;
    }

    public function setNombreRuta($nombre) {
        $this->nombre = $nombre;
    }

    public function getPuntosRuta() {
        return $this->puntos;
    }

    public function setPuntosRuta($puntos) {
        $this->puntos = $puntos;
    }

    public function getDistanciaRuta() {
        return $this->distancia;
    }

    public function setDistanciaRuta($distancia) {
        $this->distancia = $distancia;
    }

    public function getFechaCreacionRuta() {
        return $this->fechaCreacion;
    }

    public function setFechaCreacionRuta($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }
}
?>