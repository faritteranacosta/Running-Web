<?php
require '../../vendor/autoload.php'; // PHPMailer
require_once '../mdb/mdbUsuario.php';

use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    try {
        $usuario = buscarUsuarioPorCorreo($email);

        if ($usuario) {
            $token = bin2hex(random_bytes(50));
            $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Manejo de errores al guardar el token
            $guardado = guardarToken($email, $token, $expira);
            if (!$guardado) {
                echo json_encode([
                    "success" => false,
                    "message" => "Error al guardar el token de recuperación. Intenta nuevamente."
                ]);
                exit;
            }

            $link = "http://localhost/running-web/view/restablecer_contrasena.php?token=$token";

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'webrunning008@gmail.com';
            $mail->Password = '';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('webrunning008@gmail.com', 'Running Web');
            $mail->addAddress($email);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = '🔒 Recupera tu contraseña en Running Web';
            $mail->Body = "
                <h2>¡Hola!</h2>   
                <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en <b>Running Web</b>.</p>
                <p>Haz clic en el siguiente enlace para crear una nueva contraseña:</p>
                <p><a href='$link' style='color:#2563eb;font-weight:bold;'>Restablecer mi contraseña</a></p>
                <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
                <br>
                <p style='color:#888;'>¡Gracias por confiar en nosotros!<br>El equipo de Running Web 🏃‍♂️</p>
            ";
            $mail->isHTML(true);

            if ($mail->send()) {    
                echo json_encode([
                    "success" => true,
                    "message" => "Correo enviado. Revisa tu bandeja."
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Error al enviar el correo."
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Correo no encontrado."
            ]);
        }
    } catch (PDOException $e) {
        error_log('[act_recuperarContrasena] ' . $e->getMessage());
        echo json_encode([
            "success" => false,
            "message" => "Ocurrió un error al procesar tu solicitud. Inténtalo de nuevo más tarde."
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Ocurrió un error inesperado: " . $e->getMessage()
        ]);
    }
}
?>
