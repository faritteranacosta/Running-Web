<?php
require_once '../mdb/mdbUsuario.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $data = json_decode(file_get_contents('php://input'), true);

        $token = $data['token'] ?? '';
        $nueva = $data['password'] ?? '';
        $nuevaConfirm = $data['confirm_password'] ?? '';
        $nuevaHashed = password_hash($nueva, PASSWORD_DEFAULT);
        
        if ($nueva !== $nuevaConfirm) {
            echo json_encode([
                "success" => false,
                "message" => "Las contraseñas no coinciden."
            ]);
            exit;
        }

        $usuario = verificarToken($token);

        if ($usuario) {
            actualizarContrasena($usuario['id_usuario'], $nuevaHashed);
            echo json_encode([
                "success" => true,
                "message" => "Contraseña actualizada con éxito."
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Token inválido."
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Ocurrió un error al actualizar la contraseña: " . $e->getMessage()
        ]);
    }
}
?>
