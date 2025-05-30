<?php

require_once __DIR__ . '/../entidad/Producto.php';
require_once __DIR__ . '/DataSource.php';

class ProductoDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function agregarProducto(Producto $producto) {
        // Obtener el id_usuario desde el producto
        $usuario_id = $producto->getVendedorId();

        // Consultar el id_vendedor correspondiente al id_usuario
        $sqlVendedor = "SELECT id_vendedor FROM vendedor WHERE usuario_id = ?";
        $paramsVendedor = [$usuario_id];
        $resultVendedor = $this->dataSource->ejecutarConsulta($sqlVendedor, $paramsVendedor);

        if (count($resultVendedor) === 0) {
            // No se encontró el vendedor, puedes lanzar una excepción o retornar false
            return false;
        }

        $id_vendedor = $resultVendedor[0]['id_vendedor'];

        // Insertar el producto usando el id_vendedor obtenido
        $sql = "INSERT INTO producto (nombre, descripcion, precio, fecha_publicacion, vendedor_id, categoria, stock, imagenUrl) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $producto->getNombre(),
            $producto->getDescripcion(),
            $producto->getPrecio(),
            $producto->getFechaPublicacion(),
            $id_vendedor,
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
        // Obtener el id_usuario desde el producto
        $usuario_id = $producto->getVendedorId();

        // Consultar el id_vendedor correspondiente al id_usuario
        $sqlVendedor = "SELECT id_vendedor FROM vendedor WHERE usuario_id = ?";
        $paramsVendedor = [$usuario_id];
        $resultVendedor = $this->dataSource->ejecutarConsulta($sqlVendedor, $paramsVendedor);

        if (count($resultVendedor) === 0) {
            // No se encontró el vendedor, puedes lanzar una excepción o retornar false
            return false;
        }

        $id_vendedor = $resultVendedor[0]['id_vendedor'];
        $sql = "UPDATE producto SET nombre = ?, descripcion = ?, precio = ?, fecha_publicacion = ?, vendedor_id = ?, categoria = ?, stock = ?, imagenUrl = ? WHERE id_producto = ?";
        $params = [
            $producto->getNombre(),
            $producto->getDescripcion(),
            $producto->getPrecio(),
            $producto->getFechaPublicacion(),
            $id_vendedor,
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
        // Buscar el id_vendedor en la tabla vendedor usando el vendedor_id proporcionado
        $sqlVendedor = "SELECT id_vendedor FROM vendedor WHERE usuario_id = ?";
        $paramsVendedor = [$vendedor_id];
        $resultVendedor = $this->dataSource->ejecutarConsulta($sqlVendedor, $paramsVendedor);

        if (count($resultVendedor) === 0) {
            // No se encontró el vendedor, retornar array vacío
            return [];
        }

        $id_vendedor = $resultVendedor[0]['id_vendedor'];

        // Buscar productos usando el id_vendedor obtenido
        $sql = "SELECT * FROM producto WHERE vendedor_id = ?";
        $params = [$id_vendedor];
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


    public function obtenerProductosPaginados($offset, $porPagina) {
        $offset = (int)$offset;
        $porPagina = (int)$porPagina;
        // Interpolamos los valores directamente, ya que son enteros y seguros
        $sql = "SELECT * FROM producto LIMIT $offset, $porPagina";
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
    public function contarProductos() {
        $sql = "SELECT COUNT(*) as total FROM producto";
        $result = $this->dataSource->ejecutarConsulta($sql);
        if (count($result) > 0) {
            return (int)$result[0]['total'];
        }
        return 0;
    }
}