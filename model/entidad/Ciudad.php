<?php  require_once __DIR__ . '/Conexion.php'; 

class Ciudad {
    private $id_ciudad;
    private $nombre;

    public function __construct($nombre = null) {
        $this->nombre = $nombre;

    }
    public function getIdCiudad() {
        return $this->id_ciudad;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function getNombre() {
        return $this->nombre;
    }

}