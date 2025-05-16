<?php
require_once __DIR__ . '/Tipo_Carrera.php';
require_once __DIR__ . '/Categoria.php';
require_once __DIR__ . '/Ruta.php';
require_once __DIR__ . '/Evento.php';
class Carrera {
    private $id_carrera;
    private $distancia;
    private $evento; //instancia de Evento
    private $tipo_carrera;//instancia de Tipo_Carrera
    private $categoria;//instancia de Categoria
    private $ruta;//instancia de Ruta

    public function __construct($distancia = null, Evento $evento = null, TipoCarrera $tipo_carrera = null, Categoria $categoria = null, Ruta $ruta = null) {
        $this->distancia = $distancia;
        $this->evento = $evento;
        $this->tipo_carrera = $tipo_carrera;
        $this->categoria = $categoria;
        $this->ruta = $ruta;
    }

    public function getIdCarrera() {
        return $this->id_carrera;
    }

    public function setDistancia($distancia) {
        $this->distancia = $distancia;
    }

    public function getDistancia() {
        return $this->distancia;
    }

    public function setEvento(Evento $evento) {
        $this->evento = $evento;
    }

    public function getEvento() {
        return $this->evento;
    }

    public function setTipoCarrera(TipoCarrera $tipo_carrera) {
        $this->tipo_carrera = $tipo_carrera;
    }

    public function getTipoCarrera() {
        return $this->tipo_carrera;
    }

    public function setCategoria(Categoria $categoria) {
        $this->categoria = $categoria;
    }

    public function getCategoria() {
        return $this->categoria;
    }
    public function setRuta(Ruta $ruta) {
        $this->ruta = $ruta;
    }

    public function getRuta() {
        return $this->ruta;
    }

}
