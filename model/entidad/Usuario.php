<?php  require_once __DIR__ . '/Conexion.php'; 

class Usuario{

    private $id_usuario;
    private $rol;
    private $nombre;
    private $apellido;
    private $correo;
    private $contrasena;
    private $sexo;
    private $fecha_nacimiento; 
    private $fecha_registro;

    public function __construct($rol = null, $nombre = null, $apellido = null, $correo = null, $contrasena = null, $sexo = null, $fecha_nacimiento = null, $fecha_registro = null) {
        $this->rol = $rol;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->contrasena = $contrasena;
        $this->sexo = $sexo;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->fecha_registro = $fecha_registro;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }

    public function getRol() {
        return $this->rol;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function setContrasena($contrasena) {
        $this->contrasena = $contrasena;
    }

    public function getContrasena() {
        return $this->contrasena;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function setFechaNacimiento($fecha_nacimiento) {
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    public function getFechaNacimiento() {
        return $this->fecha_nacimiento;
    }

    public function setFechaRegistro($fecha_registro) {
        $this->fecha_registro = $fecha_registro;
    }

    public function getFechaRegistro() {
        return $this->fecha_registro;
    }

}