<?php
require_once __DIR__ . '/../mdb/mdbParticipacionEvento.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['ID_USUARIO'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit;
}

$id_usuario = $_SESSION['ID_USUARIO'];
$participaciones = obtenerParticipacionesPorUsuarioMDB($id_usuario);

$result = [];
foreach ($participaciones as $participacion) {
    $evento = $participacion->getEvento();
    $id_carrera = property_exists($participacion, 'id_carrera') ? $participacion->id_carrera : null;
    $distancia = property_exists($participacion, 'distancia') ? $participacion->distancia : null;
    $result[] = [
        'id_evento' => $evento->getIdEvento(),
        'nombre_evento' => $evento->getNombreEvento(),
        'fecha_evento' => $evento->getFechaEvento(),
        'hora_evento' => $evento->getHoraEvento(),
        'descripcion_evento' => $evento->getDescripcionEvento(),
        'id_carrera' => $id_carrera,
        'distancia' => $distancia
    ];
}

echo json_encode(['success' => true, 'participaciones' => $result]);
