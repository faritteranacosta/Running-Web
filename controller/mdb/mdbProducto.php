<?php

require_once __DIR__ . '/../../model/dao/ProductoDAO.php';

function agregarProducto($nombre, $descripcion, $precio, $fecha_publicacion, $vendedor_id, $categoria = null, $stock = null, $imagenUrl = null) {
    $productoDAO = new ProductoDAO();
    $producto = new Producto($nombre, $descripcion, $precio, $fecha_publicacion, $vendedor_id, $categoria, $stock, $imagenUrl);
    return $productoDAO->agregarProducto($producto);
}

function obtenerProductoPorId($id_producto) {
    $productoDAO = new ProductoDAO();
    return $productoDAO->obtenerProductoPorId($id_producto);
}

function listarProductos() {
    $productoDAO = new ProductoDAO();
    return $productoDAO->listarProductos();
}

function obtenerProductoPorVendedor($vendedor_id) {
    $productoDAO = new ProductoDAO();
    return $productoDAO->obtenerProductosPorVendedor($vendedor_id);
}

function obtenerProductosPaginados($offset, $porPagina) {
    $productoDAO = new ProductoDAO();
    return $productoDAO->obtenerProductosPaginados($offset, $porPagina);
}
function contarProductos() {
    $productoDAO = new ProductoDAO();
    return $productoDAO->contarProductos();
}

function updateProducto($id_producto, $nombre, $descripcion, $precio, $fecha_publicacion, $vendedor_id, $categoria = null, $stock = null, $imagenUrl = null) {
    $productoDAO = new ProductoDAO();
    $producto = new Producto($nombre, $descripcion, $precio, $fecha_publicacion
    , $vendedor_id, $categoria, $stock, $imagenUrl);
    $producto->setIdProducto($id_producto);
    return $productoDAO->actualizarProducto($producto);
}


function eliminarProducto($id_producto) {
    $productoDAO = new ProductoDAO();
    return $productoDAO->eliminarProducto($id_producto);
}