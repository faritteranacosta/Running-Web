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
            $tabla = $this->modelo->getCorredor($correo_electronico);
            foreach ($tabla as $indice => $value) {
                if (count($tabla) == 1) {
                    $_SESSION['usuario'] = new Corredor(
                        $tabla[$indice]['nombre_usuario'],
                        $tabla[$indice]['apellido_usuario'],
                        $tabla[$indice]['correo_electronico'],
                        $tabla[$indice]['contrasena']
                    );

                    header('Location: view/runner.php');
                }
            }
                     
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