<?php  require_once __DIR__ . '/Conexion.php'; 
require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/Ciudad.php';
require_once __DIR__ . '/Equipo.php';

class Corredor {
    private $id_corredor;
    private $usuario;
    private $ciudad;
    private $equipo;

    public function __construct(Usuario $usuario = null, Ciudad $ciudad = null, Equipo $equipo = null) {
        $this->usuario = $usuario;
        $this->ciudad = $ciudad;
        $this->equipo = $equipo;
    }

    public function getIdCorredor() {
        return $this->id_corredor;
    }

    // public function setUsuario(Usuario $usuario) {
    //     $this->usuario = $usuario;
    // }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setCiudad(Ciudad $ciudad) {
        $this->ciudad = $ciudad;
    }
    public function getCiudad() {
        return $this->ciudad;
    }
    public function setEquipo(Equipo $equipo) {
        $this->equipo = $equipo;
    }
    public function getEquipo() {
        return $this->equipo;
    }

}   