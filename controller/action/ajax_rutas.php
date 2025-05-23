<?php
require_once __DIR__.'/../../model/entidad/Ruta.php';
require_once __DIR__.'/../mdb/mdbRuta.php';

header('Content-Type: application/json');

try {
    // Obtener ID de la ruta de la URL
    $routeId = $_GET['id'] ?? null;
    
    if (!$routeId) {
        throw new Exception('ID de ruta no proporcionado', 400);
    }
    
    $ruta = obtenerRuta($routeId);
    
    if (!$ruta) { 
        throw new Exception('Ruta no encontrada', 404);
    }   
    
    // Preparar respuesta
    $response = [
        'success' => true,
        'data' => [
            'id' => $ruta->getIdRuta(),
            'nombre' => $ruta->getNombreRuta(),
            'puntos' => $ruta->getPuntosRuta(),
            'distancia' => json_encode($ruta->getDistanciaRuta(),true), 
            'fecha_creacion' => $ruta->getFechaCreacionRuta()
        ]
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>