<?php
require_once __DIR__ . '/../entidad/Vendedor.php';
require_once __DIR__ . '/../entidad/Usuario.php';
require_once __DIR__ . '/DataSource.php';

class VendedorDAO {

    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function agregarVendedor(Vendedor $vendedor) {

        // Insertar en corredores
        $sqlVendedor = "INSERT INTO vendedor (nombre_tienda, usuario_id) VALUES (:nombre_tienda, :usuario_id)";
        $paramsVendedor = array(
            ':nombre_tienda' => $vendedor->getNombreTienda(),
            ':usuario_id' => $vendedor->getUsuario()
        );
        return $this->dataSource->ejecutarActualizacion($sqlVendedor, $paramsVendedor);
    }

    public function obtenerVendedorPorIdUsuario($id_usuario) {
        $sql = "SELECT * FROM vendedor WHERE usuario_id = :id_usuario";
        $params = array(':id_usuario' => $id_usuario);
        return $this->dataSource->ejecutarConsulta($sql, $params);
    }

    public function eliminarVendedorPorIdUsuario($id_usuario) {
        $sql = "DELETE FROM vendedor WHERE usuario_id = :id_usuario";
        $params = array(':id_usuario' => $id_usuario);
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }
}
?>
