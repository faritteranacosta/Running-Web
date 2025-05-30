<?php
session_start();
require_once(__DIR__ . '/../mdb/mdbCategoria.php');
require_once(__DIR__ . '/../mdb/mdbTipoCarrera.php');

// Configuración de encabezados
header('Content-Type: application/json; charset=utf-8');

try {
    // Obtener lista de categorías y tipos de carrera (adapta estas funciones a tu código)
    $categorias = obtenerCategorias();       // Devuelve array de objetos categoría
    $tiposCarrera = obtenerTiposCarrera();   // Devuelve array de objetos tipo carrera

    if ($categorias === null || $categorias === false) {
        throw new Exception("Error al obtener categorías de la base de datos");
    }
    if ($tiposCarrera === null || $tiposCarrera === false) {
        throw new Exception("Error al obtener tipos de carrera de la base de datos");
    }

    // Armar respuesta
    $respuesta = [
        "success" => true, 
        "categorias" => [],
        "tiposCarrera" => []
    ];

    foreach ($categorias as $categoria) {
        $respuesta["categorias"][] = [
            "id" => $categoria->getIdCategoria(),       // Cambia getId() al método que tengas
            "nombre" => $categoria->getNombre() // Cambia getNombre() al método que tengas
        ];
    }

    foreach ($tiposCarrera as $tipo) {
        $respuesta["tiposCarrera"][] = [
            "id" => $tipo->getIdTipoCarrera(),
            "nombre" => $tipo->getNombre()
        ];
    }

    if (ob_get_length()) ob_clean();
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'success' => false
    ]);
}

