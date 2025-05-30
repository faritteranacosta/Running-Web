<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../mdb/mdbRuta.php');
require_once('../../model/entidad/Ruta.php');

$response = ['success' => false, 'message' => '', 'data' => null];

try {
    // 1. Validar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido', 405);
    }

    // 2. Obtener y validar datos del request
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Formato JSON inválido', 400);
    }
    
    if (!$input || !isset($input['usuario_id'], $input['nombre'], $input['puntos'])) {
        throw new Exception('Datos incompletos. Se requieren usuario_id, nombre y puntos', 400);
    }
    
    // 3. Validaciones específicas
    if (!is_numeric($input['usuario_id']) || $input['usuario_id'] <= 0) {
        throw new Exception('ID de usuario inválido', 400);
    }
    
    if (strlen(trim($input['nombre'])) < 3) {
        throw new Exception('El nombre de la ruta debe tener al menos 3 caracteres', 400);
    }
    
    if (!is_array($input['puntos']) || count($input['puntos']) < 2) {
        throw new Exception('La ruta debe tener al menos 2 puntos geográficos', 400);
    }

    // 4. Validar estructura de puntos
    foreach ($input['puntos'] as $punto) {
        if (!isset($punto[0], $punto[1]) || !is_numeric($punto[0]) || !is_numeric($punto[1])) {
            throw new Exception('Estructura de puntos inválida. Se espera [[lat,lng],...]', 400);
        }
    }

    // 5. Calcular distancia
    $distancia = calcularDistancia($input['puntos']);
    
    // 6. Crear y validar modelo
    $ruta = new Ruta();
    $ruta->setUsuarioId($input['usuario_id']);
    $ruta->setNombreRuta(htmlspecialchars(trim($input['nombre'])));
    $ruta->setPuntosRuta($input['puntos']);
    $ruta->setDistanciaRuta($distancia);
    $ruta->setFechaCreacionRuta(date('Y-m-d H:i:s'));
    
    // 7. Guardar mediante MDB
    $rutaGuardada = guardarRuta($ruta);
    
    if (!$rutaGuardada) {
        throw new Exception('Error al persistir la ruta en la base de datos', 500);
    }

    // 8. Preparar respuesta exitosa
    $response = [
        'success' => true,
        'message' => 'Ruta guardada correctamente',
        'data' => [
            'id' => $rutaGuardada->getIdRuta(),
            'nombre' => htmlspecialchars(trim($input['nombre'])),
            'distancia' => $rutaGuardada->getDistanciaRuta(),
            'puntos_count' => count($rutaGuardada->getPuntosRuta())
        ],
        
    ];

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $e->getCode() ?: 500
    ];
    http_response_code(500);
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

/**
 * Calcula la distancia total de una ruta en kilómetros
 * 
 * @param array $puntos Array de puntos [[lat, lng], ...]
 * @return float Distancia total en kilómetros
 */
function calcularDistancia(array $puntos): float {
    $distancia = 0.0;
    $earthRadius = 6371; // Radio de la Tierra en km
    
    for ($i = 1; $i < count($puntos); $i++) {
        $lat1 = (float)$puntos[$i-1][0];
        $lon1 = (float)$puntos[$i-1][1];
        $lat2 = (float)$puntos[$i][0];
        $lon2 = (float)$puntos[$i][1];
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) + 
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
             sin($dLon/2) * sin($dLon/2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distancia += $earthRadius * $c;
    }
    
    return round($distancia, 2);
}