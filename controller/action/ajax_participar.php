<?php
session_start();

require_once __DIR__ . '/../../model/dao/CarreraDAO.php';
require_once __DIR__ . '/../../model/dao/DataSource.php'; // Para consulta directa
require_once __DIR__ . '/../mdb/mdbCarrera.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_evento = $_POST['id_evento'];
    if (isset($_SESSION['ID_USUARIO'])) {
        $id_usuario = $_SESSION['ID_USUARIO'];
        // DEBUG: Loguear el id de usuario de la sesión
        file_put_contents(__DIR__ . '/debug_usuario.log', date('Y-m-d H:i:s') . " - ID_USUARIO: " . print_r($id_usuario, true) . "\n", FILE_APPEND);
        // Buscar el id_corredor correspondiente a este usuario
        $dataSource = new DataSource();
        $sql = "SELECT id_corredor FROM corredor WHERE usuario_id = ? LIMIT 1";
        $result = $dataSource->ejecutarConsulta($sql, [$id_usuario]);
        // DEBUG: Loguear el resultado de la consulta
        file_put_contents(__DIR__ . '/debug_usuario.log', date('Y-m-d H:i:s') . " - Resultado SQL: " . print_r($result, true) . "\n", FILE_APPEND);
        if ($result && isset($result[0]['id_corredor'])) {
            $id_corredor = $result[0]['id_corredor'];
            $resultado = agregarParticipacionMDB($id_corredor, $id_evento);
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Participación registrada']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al registrar la participación']);
            }
        } else {
            // Mostrar los valores en la consola del navegador (devuelve en la respuesta JSON)
            echo json_encode([
                'success' => false,
                'message' => 'No se encontró el corredor para este usuario',
                'debug' => [
                    'ID_USUARIO' => $id_usuario,
                    'sql_result' => $result
                ]
            ]);
            return;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>