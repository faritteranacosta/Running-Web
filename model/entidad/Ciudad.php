<?php  

class Ciudad {
    private $id_ciudad;
    private $nombre;

    public function __construct($nombre = null) {
        $this->nombre = $nombre;

    }
    public function getIdCiudad() {
        return $this->id_ciudad;
    }
    public function setIdCiudad($id_ciudad) {
        $this->id_ciudad = $id_ciudad;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function getNombre() {
        return $this->nombre;
    }

}