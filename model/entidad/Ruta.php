<?php

class Ruta {
    private $id_ruta;
    private $descripcion_ruta;
    private $url_mapa;

    public function __construct($descripcion_ruta = null, $url_mapa = null) {
        $this->descripcion_ruta = $descripcion_ruta;
        $this->url_mapa = $url_mapa;
    }

    public function getIdRuta() {
        return $this->id_ruta;
    }
    public function setIdRuta($id_ruta) {
        $this->id_ruta = $id_ruta;
    }
    public function setDescripcionRuta($descripcion_ruta) {
        $this->descripcion_ruta = $descripcion_ruta;
    }
    public function getDescripcionRuta() {
        return $this->descripcion_ruta;
    }
    public function setUrlMapa($url_mapa) {
        $this->url_mapa = $url_mapa;
    }
    public function getUrlMapa() {
        return $this->url_mapa;
    }
}
?>