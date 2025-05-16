<?php

class Equipo {
private $id_equipo;
private $nombre;

public function __construct($nombre = null) {
    $this->nombre = $nombre;
}

public function getIdEquipo() {
    return $this->id_equipo;
}

public function setIdEquipo($id_equipo) {
    $this->id_equipo = $id_equipo;
}

public function setNombre($nombre) {
    $this->nombre = $nombre;
}

public function getNombre() {
    return $this->nombre;
}
}