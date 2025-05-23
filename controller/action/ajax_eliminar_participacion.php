<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['ID_USUARIO'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit;
}

require_once __DIR__ . '/../mdb/mdbParticipacionEvento.php';

$id_usuario = $_SESSION['ID_USUARIO'];
$id_evento = $_POST['id_evento'] ?? null;

if (!$id_evento) {
    echo json_encode(['success' => false, 'error' => 'ID de evento no recibido']);
    exit;
}

$resultado = eliminarParticipacionMDB($id_usuario, $id_evento);

if ($resultado) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'No se pudo eliminar la participación']);
}