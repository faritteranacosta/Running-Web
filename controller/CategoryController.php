<?php
require_once __DIR__ . '/../model/dao/CategoriaDAO.php';

class CategoryController
{
    private $categoriaDAO;

    public function __construct()
    {
        $this->categoriaDAO = new CategoriaDAO();
    }

    public function listar() { return $this->categoriaDAO->obtenerCategorias(); }
    public function obtenerPorId($idCategoria) { return $this->categoriaDAO->obtenerCategoriaPorId($idCategoria); }
}
