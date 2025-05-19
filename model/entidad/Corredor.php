<?php  

class Corredor {
    private $id_corredor;
    private $id_usuario;
    private $id_ciudad;
    private $id_equipo;


    public function __construct($usuario, $ciudad = null, $equipo = null) {
        $this->id_usuario = $usuario;
        $this->id_ciudad = $ciudad;
        $this->id_equipo = $equipo;
    }

    public function getIdCorredor() {
        return $this->id_corredor;
    }

    // public function setUsuario(Usuario $usuario) {
    //     $this->usuario = $usuario;
    // }

    public function getUsuario() {
        return $this->id_usuario;
    }

    public function setCiudad($ciudad) {
        $this->id_ciudad = $ciudad;
    }
    public function getCiudad() {
        return $this->id_ciudad;
    }
    public function setEquipo($equipo) {
        $this->id_equipo = $equipo;
    }
    public function getEquipo() {
        return $this->id_equipo;
    }

}   