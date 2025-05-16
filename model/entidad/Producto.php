<?php
// filepath: c:\xampp\htdocs\Running-Web\model\entidad\Producto.php

class Producto {
    private $id_producto;
    private $nombre;
    private $descripcion;
    private $precio;
    private $fecha_publicacion;
    private $categoria;
    private $stock;
    private $vendedor_id;
    private $imagenUrl;

    public function __construct($id_producto, $nombre = null, $descripcion = null, $precio = null, $fecha_publicacion = null, $vendedor_id = null, $categoria = null, $stock = null, $imagenUrl = null) {
        $this->id_producto = $id_producto;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->fecha_publicacion = $fecha_publicacion;
        $this->vendedor_id = $vendedor_id;
        $this->categoria = $categoria;
        $this->stock = $stock; 
        $this->imagenUrl = $imagenUrl; 
    }

    public function getImagenUrl() {
        return $this->imagenUrl;
    }

    public function setImagenUrl($imagenUrl) {
        $this->imagenUrl = $imagenUrl;
    }

    public function getStock() {
        return $this->stock;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    public function getIdProducto() {
        return $this->id_producto;
    }
    public function setIdProducto($id_producto) {
        $this->id_producto = $id_producto;
    }

    public function getNombre() {
        return $this->nombre;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getPrecio() {
        return $this->precio;
    }
    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function getFechaPublicacion() {
        return $this->fecha_publicacion;
    }
    public function setFechaPublicacion($fecha_publicacion) {
        $this->fecha_publicacion = $fecha_publicacion;
    }

    public function getVendedorId() {
        return $this->vendedor_id;
    }
    public function setVendedorId($vendedor_id) {
        $this->vendedor_id = $vendedor_id;
    }
}