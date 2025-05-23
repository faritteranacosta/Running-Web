<?php
require_once __DIR__ . '/../../model/dao/RutaDao.php';
require_once '../../model/entidad/Ruta.php';

function guardarRuta(Ruta $ruta) {
    $dao = new RutaDao();
    return $dao->guardar($ruta);
}

function obtenerRuta($id) {
    $dao = new RutaDao();
    return $dao->obtenerRuta($id);
}

function eliminarRuta($id) {
    $dao = new RutaDao();
    return $dao->eliminar($id);
}