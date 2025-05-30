<?php 

require_once __DIR__ . '/../../model/dao/TipoCarreraDAO.php';
function obtenerTiposCarrera() {
    $tipoCarreraDAO = new TipoCarreraDAO();
    return $tipoCarreraDAO->obtenerTiposCarrera();
}
function obtenerTipoCarreraPorId($id_tipo_carrera) {
    $tipoCarreraDAO = new TipoCarreraDAO();
    return $tipoCarreraDAO->obtenerTipoCarreraPorId($id_tipo_carrera);
}
?>