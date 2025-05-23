<?php 
require_once __DIR__ . '/../mdb/mdbProducto.php';
header('Content-Type: application/json');
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$method = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['action']) && $_GET['action'] === 'obtener' && isset($_GET['idProducto'])) {
    // Obtener detalles de un producto específico
    $result = obtenerProductoPorId($_GET['idProducto']);
    if(ob_get_length()) ob_clean();
    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

try {
    if($method === "GET") {
        // --- PAGINACIÓN ---
        $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
        $porPagina = isset($_GET['porPagina']) ? max(1, intval($_GET['porPagina'])) : 20;
        $offset = ($pagina - 1) * $porPagina;

        $productos = obtenerProductosPaginados($offset, $porPagina);
        $total = contarProductos();

        if ($productos === null || $productos === false) {
            throw new Exception("Error al obtener productos de la base de datos");
        }

        $respuesta = array_map(function($producto) {
            return [
                'id' => $producto->getIdProducto(),
                'nombre' => $producto->getNombre(),
                'descripcion' => $producto->getDescripcion(),
                'precio' => $producto->getPrecio(),
                'stock' => $producto->getStock(),
                'categoria' => $producto->getCategoria(),
            ];
        }, $productos);

        echo json_encode([
            'productos' => $respuesta,
            'total' => $total
        ]);
        exit;
    }
    
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'success' => false
    ]);
}
?>
