<?php require_once 'model/Corredor.php';

class ControladorCorredor{

    private $modelo;


    public function __construct()
    {

        $this->modelo = new Corredor();

    }


    public function mostrarLogin()
    {
        require 'view/iniciar_sesion.html';
    }


    public function iniciarSesion($correo_electronico, $contrasena)
    {

        if ($this->modelo->verificarCredenciales($correo_electronico, $contrasena)) {

            session_start();

            $_SESSION['usuario'] = $correo_electronico;

            header('Location: view/runner.php');

        } else {

            echo "Credenciales incorrectas.";

        }

    }


    public function mostrarRegistro()
    {

        require 'view/registrarse.html';

    }

    public function registrarCorredor($nombre_usuario, $apellido_usuario, $correo_electronico, $contrasena)
    {

        if ($this->modelo->registrar($nombre_usuario,  $apellido_usuario, $correo_electronico, $contrasena)) {

            echo "Registro exitoso. Ahora puedes iniciar sesión.";

        } else {

            echo "Error al registrar.";

        }

    }

}