<?php
<?php
// ajax_participaciones.php: Devuelve las carreras/eventos en los que el usuario autenticado está inscrito
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['ID_USUARIO'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit;
}

require_once __DIR__ . '/../mdb/mdbParticipacionEvento.php';

$id_usuario = $_SESSION['ID_USUARIO'];
$participaciones = obtenerParticipacionesPorUsuarioMDB($id_usuario);

$result = [];
foreach ($participaciones as $participacion) {
    $evento = $participacion->getEvento();
    // Intentar obtener el id_carrera desde el evento, si existe el método getIdCarrera
    $id_carrera = null;
    if (method_exists($evento, 'getIdCarrera')) {
        $id_carrera = $evento->getIdCarrera();
    }
    $result[] = [
        'id_carrera' => $id_carrera,
        'id_evento' => $evento->getIdEvento(),
        'nombre_evento' => $evento->getNombreEvento(),
        'fecha_evento' => $evento->getFechaEvento(),
        'hora_evento' => $evento->getHoraEvento(),
        'descripcion_evento' => $evento->getDescripcionEvento(),
        'evento' => $evento->getIdEvento()
    ];
}

echo json_encode(['success' => true, 'participaciones' => $result]);