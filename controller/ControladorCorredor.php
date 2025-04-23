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
                if (count($tabla)) {
                    $usuario = new Corredor(
                        $tabla[$indice]['nombre_usuario'],
                        $tabla[$indice]['apellido_usuario'],
                        $tabla[$indice]['correo_electronico'],
                        $tabla[$indice]['contrasena']
                    );
                    $_SESSION['nombre'] = $usuario->getNombreUsuario();
                    $_SESSION['apellido'] = $usuario->getApellidoUsuario();
                    $_SESSION['correo_electronico'] = $usuario->getCorreoElectronico();

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
    public function eliminarCorredor($correo_electronico)
    {
        if ($this->modelo->eliminarCorredor($correo_electronico)) {
            echo "<script>
                alert('Su cuenta ha sido eliminada exitosamente.');
                window.location.href = 'index.php';
            </script>";
        } else {    
            echo "<script>
                alert('Hubo un error al intentar eliminar su cuenta. Por favor, inténtelo de nuevo.');
                window.location.href = 'index.php';
            </script>";
        }
    }

    public function cambiarContrasena($correo_electronico, $new_password, $confirm_password, $current_password)
    {
        if ($this->modelo->verificarCredenciales($correo_electronico, $current_password)) {
            if ($new_password === $confirm_password) {
                if ($this->modelo->cambiarContrasena($correo_electronico, $new_password)) {
                    echo "Contraseña cambiada exitosamente.";
                } else {
                    echo "Error al cambiar la contraseña.";
                }
            } else {
                echo "Las contraseñas no coinciden.";
            }
        } else {
            echo "La contraseña actual es incorrecta.";
        }
    }

    public function cerrarSesion()
    {
        session_start();
        session_unset(); // Elimina todas las variables de sesión
        session_destroy();

        require 'view/iniciar_sesion.html';
    }

}