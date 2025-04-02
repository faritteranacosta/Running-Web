<?php  require_once 'model/Conexion.php';

class Corredor {
    private $conexion;

    public function __construct() {
            $this->conexion = (new Conexion())->getConexion();
    }


    public function registrar($nombre_usuario, $apellido_usuario,  $correo_electronico, $contrasena){
        $contrasena_hashed = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $this->conexion->prepare('INSERT INTO corredores (nombre_usuario, apellido_usuario, correo_electronico, contrasena) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $nombre_usuario,$apellido_usuario, $correo_electronico, $contrasena_hashed);
        return $stmt->execute();
    }

    public function verificarCredenciales($correo_electronico, $contrasena){
        $stmt = $this->conexion->prepare('SELECT contrasena FROM corredores WHERE correo_electronico = ?');
        $stmt->bind_param("s",$correo_electronico);
        $stmt->execute();
        $stmt->bind_result($contrasena_hashed);
        $stmt->fetch();
        return password_verify( $contrasena, $contrasena_hashed );
    }
}   