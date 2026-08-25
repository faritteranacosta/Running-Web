<?php 
require_once __DIR__ . '/../mdb/mdbProducto.php';
header('Content-Type: application/json');
// Los errores se registran en el log del servidor, pero nunca se muestran
// directamente al cliente (evita filtrar detalles internos como SQL).
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);


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

    if ($method === "DELETE") {
        $data = json_decode(file_get_contents('php://input'), true);
        $idProducto = $data['idProducto'] ?? null;

        if (!$idProducto) {
            throw new Exception("Se requiere el ID del producto para eliminar.");
        }

        $result = eliminarProducto($idProducto);
        echo json_encode([
            'success' => (bool)$result,
            'msg' => $result ? 'Producto eliminado correctamente.' : 'No se pudo eliminar el producto.'
        ]);
        exit;
    }
    
} catch (PDOException $e) {
    error_log('[ajax_productos_paginados] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Ocurrió un error al cargar los productos. Inténtalo de nuevo más tarde.',
        'success' => false
    ]);
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'success' => false
    ]);
}
?>
