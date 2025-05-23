<?php
session_start();



require_once(__DIR__ . '/../mdb/mdbProducto.php');
header('Content-Type: application/json');


$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        // Listar productos con filtro por vendedor si se proporciona
        $vendedor_id = $_GET['vendedor_id'] ?? null;
        
        if ($vendedor_id) {
            $productos = obtenerProductoPorVendedor( $vendedor_id);
        } else {
            $productos = listarProductos();
        }
        
        $respuesta = array_map(function($producto) {
            $stock = $producto->getStock();
            return [
                'id' => $producto->getIdProducto(),
                'name' => $producto->getNombre(),
                'description' => $producto->getDescripcion(),
                'price' => (float)$producto->getPrecio(),
                'fecha_publicacion' => $producto->getFechaPublicacion(),
                'vendedor_id' => $producto->getVendedorId(),
                'categoria' => $producto->getCategoria(),
                'stock' => (int)$stock,
                'image' => $producto->getImagenUrl(),
                'status' => $stock === 0 ? 'out-of-stock' : ($stock < 10 ? 'low-stock' : 'active')
            ];
        }, $productos);
        
        echo json_encode($respuesta);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    switch ($method) {
        case 'POST':
            // Validar datos requeridos
            $required = ['name', 'description', 'price', 'vendedor_id', 'categoria', 'stock', 'image'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("El campo $field es requerido.");
                }
            }
            $result = agregarProducto(
                $data['name'],
                $data['description'],
                (float)$data['price'],
                date('Y-m-d'),
                $data['vendedor_id'],
                $data['categoria'],
                (int)$data['stock'],
                $data['image']
            );
            
            echo json_encode([
                'success' => (bool)$result,
                'msg' => $result ? 'Producto creado correctamente.' : 'No se pudo crear el producto.'
            ]);
            break;

        case 'PUT':
            // Validar datos requeridos incluyendo ID
            if (empty($_GET['id'])) {
                throw new Exception("Se requiere el ID del producto para actualizar.");
            }

            $required = ['name', 'description', 'price', 'vendedor_id', 'categoria', 'stock', 'image'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("El campo $field es requerido.");
                }
            }

            $result = updateProducto(
                $_GET['id'],
                $data['name'],
                $data['description'],
                (float)$data['price'],
                date('Y-m-d'),
                $data['vendedor_id'],
                $data['categoria'],
                (int)$data['stock'],
                $data['image']
            );
            
            echo json_encode([
                'success' => (bool)$result,
                'msg' => $result ? 'Producto actualizado correctamente.' : 'No se pudo actualizar el producto.'
            ]);
            break;

        case 'DELETE':
            if (empty($_GET['id'])) {
                throw new Exception("Se requiere el ID del producto para eliminar.");
            }

            $result = eliminarProducto($_GET['id']);
            echo json_encode([
                'success' => (bool)$result,
                'msg' => $result ? 'Producto eliminado correctamente.' : 'No se pudo eliminar el producto.'
            ]);
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'msg' => 'Método no soportado.']);
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'msg' => $e->getMessage()
    ]);
}