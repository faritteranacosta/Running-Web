<?php 
require_once __DIR__ . '/DataSource.php';
require_once __DIR__ . '/../entidad/Categoria.php';

class CategoriaDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function obtenerCategorias() {
        $sql = "SELECT * FROM categoria";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $categorias = [];
        foreach ($result as $row) {
            $categoria = new Categoria($row['nombre'], $row['descripcion']);
            $categoria->setIdCategoria($row['id_categoria']);
            $categorias[] = $categoria;
        }
        return $categorias;
    }

    public function obtenerCategoriaPorId($id_categoria) {
        $sql = "SELECT * FROM categoria WHERE id_categoria = ?";
        $params = [$id_categoria];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            $row = $result[0];
            $categoria = new Categoria($row['nombre'], $row['descripcion']);
            $categoria->setIdCategoria($row['id_categoria']);
            return $categoria;
        }
        return null;
    }
}


?>