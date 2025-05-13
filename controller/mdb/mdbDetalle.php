<?php
require_once '../../model/dao/DetalleDAO.php';
require_once '../../model/entidad/Detalle.php';

function obtenerDetalles($id_evento) {
    $dao = new DetalleDAO();
    return $dao->obtenerDetalles($id_evento);
}