<?php require_once 'model/Corredor.php';

class ControladorCorredor
{

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

            echo "
                <!DOCTYPE html>
                <html lang='es'>
                <head>
                    <meta charset='UTF-8'>
                    <title>Error</title>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            title: 'Error',
                            text: 'Credenciales incorrectas.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            window.location.href = 'index.php';
                        });
                    </script>
                </body>
                </html>";
        }

    }

    public function mostrarHome()
    {
        require 'view/home.html';

    }

    public function mostrarRegistro()
    {
        require 'view/registrarse.html';

    }

    public function registrarCorredor($nombre_usuario, $apellido_usuario, $correo_electronico, $contrasena)
    {

        if ($this->modelo->registrar($nombre_usuario, $apellido_usuario, $correo_electronico, $contrasena)) {
        
            echo "
                <!DOCTYPE html>
                <html lang='es'>
                <head>
                    <meta charset='UTF-8'>
                    <title>Error</title>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'Registro exitoso. Ahora puedes iniciar sesión.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                     </script>
                </body>
                </html>";

        } else {
            echo "
                <!DOCTYPE html>
                <html lang='es'>
                <head>
                    <meta charset='UTF-8'>
                    <title>Error</title>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al registrar.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    </script>;
                </body>
                </html>";
        }
    }
    public function eliminarCorredor($correo_electronico)
    {
        if ($this->modelo->eliminarCorredor($correo_electronico)) {
            echo "
                <!DOCTYPE html>
                <html lang='es'>
                <head>
                    <meta charset='UTF-8'>
                    <title>Cuenta Eliminada</title>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'Su cuenta ha sido eliminada exitosamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            window.location.href = 'index.php';
                        });
                    </script>
                </body>
                </html>";
        } else {
            echo "
                <!DOCTYPE html>
                <html lang='es'>
                <head>
                    <meta charset='UTF-8'>
                    <title>Error</title>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un error al intentar eliminar su cuenta. Por favor, inténtelo de nuevo.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            window.location.href = 'index.php';
                        });
                    </script>
                </body>
                </html>";
        }
    }

    public function cambiarContrasena($correo_electronico, $new_password, $confirm_password, $current_password)
    {
        if ($this->modelo->verificarCredenciales($correo_electronico, $current_password)) {
            if ($new_password === $confirm_password) {
                if ($this->modelo->cambiarContrasena($correo_electronico, $new_password)) {
                    echo "
                    <!DOCTYPE html>
                    <html lang='es'>
                    <head>
                        <meta charset='UTF-8'>
                        <title>¡Éxito!</title>
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    </head>
                    <body>
                        <script>
                            Swal.fire({
                                title: '¡Éxito!',
                                text: '¡Contraseña cambiada exitosamente!',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'index.php';
                                }
                            });
                        </script>
                    </body>
                    </html>";
                } else {
                    echo "
                    <!DOCTYPE html>
                    <html lang='es'>
                    <head>
                        <meta charset='UTF-8'>
                        <title>Error</title>
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    </head>
                    <body>
                        <script>
                            Swal.fire({
                                title: 'Error',
                                text: 'Error al cambiar la contraseña.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'index.php';
                                }
                            });;
                        </script>
                    </body>
                    </html>";
                }
            } else {
                echo "
                <!DOCTYPE html>
                <html lang='es'>
                <head>
                    <meta charset='UTF-8'>
                    <title>Error</title>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            title: 'Error',
                            text: 'Las contraseñas no coinciden.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'index.php';
                                }
                            });;
                    </script>
                </body>
                </html>";
            }
        } else {
            echo "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <title>Error</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        title: 'Error',
                        text: 'La contraseña actual es incorrecta.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'index.php';
                                }
                            });;
                </script>
            </body>
            </html>";
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