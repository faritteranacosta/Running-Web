<?php 
require_once __DIR__ . '/../../model/dao/CategoriaDAO.php';
function obtenerCategorias() {
    $categoriaDAO = new CategoriaDAO();
    return $categoriaDAO->obtenerCategorias();
}
function obtenerCategoriaPorId($id_categoria) {
    $categoriaDAO = new CategoriaDAO();
    return $categoriaDAO->obtenerCategoriaPorId($id_categoria);
}
?>
