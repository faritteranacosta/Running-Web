<?php

require_once __DIR__ . '/../entidad/Producto.php';
require_once __DIR__ . '/DataSource.php';

class ProductoDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function agregarProducto(Producto $producto) {
        $sql = "INSERT INTO producto (nombre, descripcion, precio, fecha_publicacion, vendedor_id, categoria, stock, imagenUrl) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $producto->getNombre(),
            $producto->getDescripcion(),
            $producto->getPrecio(),
            $producto->getFechaPublicacion(),
            $producto->getVendedorId(),
            $producto->getCategoria(),
            $producto->getStock(),
            $producto->getImagenUrl()
        ];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function obtenerProductoPorId($id_producto) {
        $sql = "SELECT * FROM producto WHERE id_producto = ?";
        $params = [$id_producto];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            $producto = new Producto(
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['fecha_publicacion'],
                $row['vendedor_id'],
                $row['categoria'],
                $row['stock'],
                $row['imagenUrl']
            );
            $producto->setIdProducto($row['id_producto']);
            return $producto;
        }
        return null;
    }

    public function listarProductos() {
        $sql = "SELECT * FROM producto";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $productos = [];
        foreach ($result as $row) {
            $producto = new Producto(
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['fecha_publicacion'],
                $row['vendedor_id'],
                $row['categoria'],
                $row['stock'],
                $row['imagenUrl']
            );
            $producto->setIdProducto($row['id_producto']);
            $productos[] = $producto;
        }
        return $productos;
    }

    public function actualizarProducto(Producto $producto) {
        $sql = "UPDATE producto SET nombre = ?, descripcion = ?, precio = ?, fecha_publicacion = ?, vendedor_id = ?, categoria = ?, stock = ?, imagenUrl = ? WHERE id_producto = ?";
        $params = [
            $producto->getNombre(),
            $producto->getDescripcion(),
            $producto->getPrecio(),
            $producto->getFechaPublicacion(),
            $producto->getVendedorId(),
            $producto->getCategoria(),
            $producto->getStock(),
            $producto->getImagenUrl(),
            $producto->getIdProducto()
        ];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function eliminarProducto($id_producto) {
        $sql = "DELETE FROM producto WHERE id_producto = ?";
        $params = [$id_producto];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function obtenerProductosPorVendedor($vendedor_id) {
        $sql = "SELECT * FROM producto WHERE vendedor_id = ?";
        $params = [$vendedor_id];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        $productos = [];
        foreach ($result as $row) {
            $producto = new Producto(
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['fecha_publicacion'],
                $row['vendedor_id'],
                $row['categoria'],
                $row['stock'],
                $row['imagenUrl']
            );
            $producto->setIdProducto($row['id_producto']);
            $productos[] = $producto;
        }
        return $productos;
    }
}