<?php
// filepath: c:\xampp\htdocs\Running-Web\controller\mdb\mdbRuta.php

require_once __DIR__ . '/../../model/dao/RouteDAO.php';


function guardarRuta(Ruta $ruta) {
    $dao = new RutaDao();
    return $dao->guardar($ruta);
}

function eliminarRuta($id) {
    $dao = new RutaDao();
    return $dao->eliminar($id);
}