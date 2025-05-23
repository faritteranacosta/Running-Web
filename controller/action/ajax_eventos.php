<?php
session_start();
require_once(__DIR__ . '/../mdb/mdbEvento.php');

// Configuración de encabezados primero
header('Content-Type: application/json; charset=utf-8');

// Manejo de errores
try {
    $eventos = listarEventos();
    
    if($eventos === null || $eventos === false) {
        throw new Exception("Error al obtener eventos de la base de datos");
    }

    $respuesta = [];
    foreach ($eventos as $evento) {
        $respuesta[] = [
            "id" => $evento->getIdEvento(),
            "nombre" => $evento->getNombreEvento(),
            "fecha" => $evento->getFechaEvento(),
            "ubicacion" => $evento->getUbicacion(),
            "descripcion" => $evento->getDescripcionEvento()
        ];
    }
    
    // Limpia cualquier salida potencial antes de json_encode
    if(ob_get_length()) ob_clean();
    
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'success' => false
    ]);
}