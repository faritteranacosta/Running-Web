<?php
require_once '../../model/dao/DetalleDAO.php';
require_once '../../model/entidad/Detalle.php';

function obtenerDetalles() {
    $dao = new DetalleDAO();
    return $dao->obtenerDetalles();
}

function obtenerDetallesPorId($id_evento) {
    $dao = new DetalleDAO();
    return $dao->obtenerDetallesPorId($id_evento);
}