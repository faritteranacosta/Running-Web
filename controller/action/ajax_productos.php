<?php

require_once(__DIR__ . '/../mdb/mdbProducto.php');

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Listar productos
    $productos = listarProductos();
    $respuesta = [];
    foreach ($productos as $producto) {
        $respuesta[] = [
            'id' => $producto->getIdProducto(),
            'name' => $producto->getNombre(),
            'description' => $producto->getDescripcion(),
            'price' => $producto->getPrecio(),
            'fecha_publicacion' => $producto->getFechaPublicacion(),
            'vendedor_id' => $producto->getVendedorId(),
            'categoria' => $producto->getCategoria(),
            'stock' => $producto->getStock(),
            'imagenUrl' => $producto->getImagenUrl()
        ];
    }
    echo json_encode($respuesta);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'POST':
        // Crear producto
        $nombre = $data['name'] ?? '';
        $descripcion = $data['description'] ?? '';
        $precio = $data['price'] ?? '';
        $fecha_publicacion = date('Y-m-d');
        $vendedor_id = $data['vendedor_id'] ?? '';
        $categoria = $data['category'] ?? null;
        $stock = $data['stock'] ?? null;
        $imagenUrl = $data['image'] ?? null;
        $result = agregarProducto($nombre, $descripcion, $precio, $fecha_publicacion, $vendedor_id, $categoria, $stock, $imagenUrl);
        echo json_encode([
            'success' => (bool)$result,
            'msg' => $result ? 'Producto creado correctamente.' : 'No se pudo crear el producto.'
        ]);
        break;

    case 'PUT':
        // Editar producto
        $id_producto = $data['id'] ?? '';
        $nombre = $data['name'] ?? '';
        $descripcion = $data['description'] ?? '';
        $precio = $data['price'] ?? '';
        $fecha_publicacion = date('Y-m-d');
        $vendedor_id = $data['vendedor_id'] ?? '';
        $categoria = $data['category'] ?? null;
        $stock = $data['stock'] ?? null;
        $imagenUrl = $data['image'] ?? null;

        // Imprimir los datos recibidos para depuración
        error_log("Datos recibidos en PUT: " . print_r($data, true));
        
        $result = actualizarProducto($id_producto, $nombre, $descripcion, $precio,
         $fecha_publicacion, $vendedor_id, $categoria, $stock, $imagenUrl);
        echo json_encode([
            'success' => (bool)$result,
            'msg' => $result ? 'Producto actualizado correctamente.' : 'No se pudo actualizar el producto.'
        ]);
        break;

    case 'DELETE':
        // Eliminar producto
        $id_producto = $data['id'] ?? '';
        $result = eliminarProducto($id_producto);
        echo json_encode([
            'success' => (bool)$result,
            'msg' => $result ? 'Producto eliminado correctamente.' : 'No se pudo eliminar el producto.'
        ]);
        break;

    default:
        echo json_encode(['success' => false, 'msg' => 'Método no soportado.']);
        break;
}