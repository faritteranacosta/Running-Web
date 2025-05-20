<?php 

class Vendedor {
    private $id_vendedor;
    private $id_usuario;
    private $nombreTienda;

    public function __construct($usuario, $nombreTienda) {
        $this->id_usuario = $usuario;
        $this->nombreTienda = $nombreTienda;
    }

    public function getIdVendedor() {
        return $this->id_vendedor;
    }

    public function getUsuario() {
        return $this->id_usuario;
    }

    public function getNombreTienda() {
        return $this->nombreTienda;
    }
    public function setNombreTienda($nombreTienda) {
        $this->nombreTienda = $nombreTienda;
    }
}

?>