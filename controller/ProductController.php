<?php
require_once __DIR__ . '/../model/dao/ProductoDAO.php';

class ProductController
{
    private $productoDAO;

    public function __construct()
    {
        $this->productoDAO = new ProductoDAO();
    }

    public function agregar($nombre, $descripcion, $precio, $fechaPublicacion, $vendedorId, $categoria = null, $stock = null, $imagenUrl = null)
    {
        return $this->productoDAO->agregarProducto(new Producto($nombre, $descripcion, $precio, $fechaPublicacion, $vendedorId, $categoria, $stock, $imagenUrl));
    }

    public function obtenerPorId($idProducto) { return $this->productoDAO->obtenerProductoPorId($idProducto); }
    public function listar() { return $this->productoDAO->listarProductos(); }
    public function index()
    {
        return array_map(function ($producto) {
            $stock = $producto->getStock();
            return [
                'id' => $producto->getIdProducto(),
                'name' => $producto->getNombre(),
                'description' => $producto->getDescripcion(),
                'price' => (float) $producto->getPrecio(),
                'fecha_publicacion' => $producto->getFechaPublicacion(),
                'vendedor_id' => $producto->getVendedorId(),
                'categoria' => $producto->getCategoria(),
                'stock' => (int) $stock,
                'image' => $producto->getImagenUrl(),
                'status' => $stock === 0 ? 'out-of-stock' : ($stock < 10 ? 'low-stock' : 'active')
            ];
        }, $this->listar());
    }
    public function obtenerPorVendedor($vendedorId) { return $this->productoDAO->obtenerProductosPorVendedor($vendedorId); }
    public function obtenerPaginados($offset, $porPagina) { return $this->productoDAO->obtenerProductosPaginados($offset, $porPagina); }
    public function contar() { return $this->productoDAO->contarProductos(); }

    public function actualizar($idProducto, $nombre, $descripcion, $precio, $fechaPublicacion, $vendedorId, $categoria = null, $stock = null, $imagenUrl = null)
    {
        $producto = new Producto($nombre, $descripcion, $precio, $fechaPublicacion, $vendedorId, $categoria, $stock, $imagenUrl);
        $producto->setIdProducto($idProducto);
        return $this->productoDAO->actualizarProducto($producto);
    }

    public function eliminar($idProducto) { return $this->productoDAO->eliminarProducto($idProducto); }
}
