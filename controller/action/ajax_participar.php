<?php
session_start();


require_once __DIR__ . '/../../model/dao/DataSource.php'; 
require_once __DIR__ . '/../mdb/mdbParticipacionEvento.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_evento = $_POST['id_evento'];
    if (isset($_SESSION['ID_USUARIO'])) {
        $id_usuario = $_SESSION['ID_USUARIO'];
        $resultado = agregarParticipacionMDB($id_usuario, $id_evento);
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Participación registrada']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar la participación']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>