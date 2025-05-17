<?php
session_start();

require_once __DIR__ . '/../../model/dao/CarreraDAO.php';
require_once __DIR__ . '/../mdb/mdbCarrera.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_evento = $_POST['id_evento']  // Falta un punto y coma aquí
    if (isset($_SESSION['id_usuario'])) { // Línea 9 (donde PHP reporta el error)
        $id_usuario = $_SESSION['id_usuario'];

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