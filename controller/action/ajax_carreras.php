<?php
require_once __DIR__ . '/../mdb/mdbCarrera.php';


header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['action']) && $_GET['action'] === 'obtener' && isset($_GET['idCarrera'])) {
    // Endpoint para obtener detalles de una carrera específica
    $result = obtenerCarreraPorIdMDB($_GET['idCarrera']);
    if(ob_get_length()) ob_clean();
    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

try {
    $carreras = obtenerTodasLasCarrerasMDB();
    if ($carreras === null || $carreras === false) {
        throw new Exception("Error al obtener carreras de la base de datos");
    }
    if(ob_get_length()) ob_clean();
    echo json_encode($carreras, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'success' => false
    ]);
}
?>