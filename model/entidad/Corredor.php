<?php  require_once __DIR__ . '/Conexion.php'; 

class Corredor {
    private $id_corredor;
    private $nombre_usuario;
    private $apellido_usuario;
    private $correo_electronico;
    private $contrasena;
    
    public function __construct($nombre_usuario = null, $apellido_usuario = null, $correo_electronico = null, $contrasena = null) {
        $this->nombre_usuario = $nombre_usuario;
        $this->apellido_usuario = $apellido_usuario;
        $this->correo_electronico = $correo_electronico;
        $this->contrasena = $contrasena;
    }

    public function getIdCorredor() {
        return $this->id_corredor;
    }

    public function setIdCorredor($id_corredor) {
        $this->id_corredor = $id_corredor;
    }

    public function setNombreUsuario($nombre_usuario) {
        $this->nombre_usuario = $nombre_usuario;
    }

    public function setApellidoUsuario($apellido_usuario) {
        $this->apellido_usuario = $apellido_usuario;
    }

    public function setCorreoElectronico($correo_electronico) {
        $this->correo_electronico = $correo_electronico;
    }

    public function setContrasena($contrasena) {
        $this->contrasena = $contrasena;
    }

    public function getNombreUsuario() {
        return $this->nombre_usuario;
    }

    public function getApellidoUsuario() {
        return $this->apellido_usuario;
    }

    public function getCorreoElectronico() {
        return $this->correo_electronico;
    }

    public function getContrasena() {
        return $this->contrasena;
    }

}   