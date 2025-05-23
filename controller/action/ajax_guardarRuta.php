<?php
header('Content-Type: application/json');
require_once __DIR__.'/../dao/RutaDAO.php';
require_once __DIR__.'/../models/Ruta.php';

$response = ['success' => false, 'message' => ''];

try {
    // Obtener datos del request
    $input = json_decode(file_get_contents('php://input'), true);
    
    if(!$input || !isset($input['usuario_id'], $input['nombre'], $input['puntos'])) {
        throw new Exception('Datos incompletos');
    }
    
    // Validar puntos
    if(!is_array($input['puntos']) || count($input['puntos']) < 2) {
        throw new Exception('La ruta debe tener al menos 2 puntos');
    }
    
    // Calcular distancia
    $distancia = calcularDistancia($input['puntos']);
    
    // Crear modelo
    $ruta = new Ruta();
    $ruta->setUsuarioId($input['usuario_id']);
    $ruta->setNombre($input['nombre']);
    $ruta->setPuntos($input['puntos']);
    $ruta->setDistancia($distancia);
    
    // Guardar mediante DAO
    $rutaDAO = new RutaDAO();
    $rutaGuardada = $rutaDAO->guardar($ruta);
    
    if($rutaGuardada) {
        $response['success'] = true;
        $response['message'] = 'Ruta guardada correctamente';
        $response['data'] = [
            'id' => $rutaGuardada->getId(),
            'nombre' => $rutaGuardada->getNombre(),
            'distancia' => $rutaGuardada->getDistancia()
        ];
    } else {
        throw new Exception('Error al guardar la ruta');
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);

function calcularDistancia($puntos) {
    $distancia = 0;
    for ($i = 1; $i < count($puntos); $i++) {
        $lat1 = $puntos[$i-1][0];
        $lng1 = $puntos[$i-1][1];
        $lat2 = $puntos[$i][0];
        $lng2 = $puntos[$i][1];
        
        $R = 6371; // Radio de la Tierra en km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distancia += $R * $c;
    }
    
    return round($distancia, 2);
}
?>