<?php
require_once __DIR__ . '/../mdb/mdbCarrera.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'obtener' && isset($_GET['idCarrera'])) {
        // Obtener detalles de una carrera específica por ID
        $result = obtenerCarreraPorIdMDB($_GET['idCarrera']);
        if (ob_get_length()) ob_clean();
        echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    }

    try {
        // Obtener todas las carreras
        $carreras = obtenerTodasLasCarrerasMDB();
        if ($carreras === null || $carreras === false) {
            throw new Exception("Error al obtener carreras de la base de datos");
        }
        if (ob_get_length()) ob_clean();
        echo json_encode($carreras, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => $e->getMessage(),
            'success' => false
        ]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inserción de una nueva carrera
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['distancia'], $input['id_evento'], $input['tipo_carrera_id'], $input['id_categoria'], $input['id_ruta'])) {
        http_response_code(400);
        echo json_encode([
            'error' => 'Datos incompletos para crear una carrera',
            'success' => false
        ]);
        exit;
    }

    $distancia = floatval($input['distancia']);
    $id_evento = intval($input['id_evento']);
    $tipo_carrera_id = intval($input['tipo_carrera_id']);
    $id_categoria = intval($input['id_categoria']);
    $id_ruta = intval($input['id_ruta']);

    try {
        $resultado = insertarCarreraMDB($distancia, $id_evento, $tipo_carrera_id, $id_categoria, $id_ruta);

        if ($resultado) {
            echo json_encode([
                'message' => 'Carrera insertada exitosamente',
                'success' => true
            ]);
        } else {
            throw new Exception('No se pudo insertar la carrera');
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => $e->getMessage(),
            'success' => false
        ]);
    }
    exit;
}

http_response_code(405);
echo json_encode([
    'error' => 'Método no permitido',
    'success' => false
]);
