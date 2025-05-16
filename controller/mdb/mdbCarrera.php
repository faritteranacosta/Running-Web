<?php
require_once __DIR__ . '/../../model/dao/CarreraDAO.php';
require_once __DIR__ . '/../../model/entidad/Carrera.php';
require_once __DIR__ . '/../../model/entidad/Evento.php';
require_once __DIR__ . '/../../model/entidad/Tipo_Carrera.php';
require_once __DIR__ . '/../../model/entidad/Categoria.php';
require_once __DIR__ . '/../../model/entidad/Ruta.php';

function insertarCarreraMDB($distancia, $evento, $tipoCarrera, $categoria, $ruta) {
    $carrera = new Carrera($distancia, new Evento($evento), new TipoCarrera($tipoCarrera), new Categoria($categoria), new Ruta($ruta));
    $dao = new CarreraDAO();
    return $dao->agregarCarrera($carrera);
}

function actualizarCarreraMDB($idCarrera, $distancia, $evento, $tipoCarrera, $categoria, $ruta) {
    $carrera = new Carrera($distancia, new Evento($evento), new TipoCarrera($tipoCarrera), new Categoria($categoria), new Ruta($ruta));
    $carrera->id_carrera = $idCarrera;
    $dao = new CarreraDAO();
    return $dao->actualizarCarrera($carrera);
}

function eliminarCarreraMDB($idCarrera) {
    $dao = new CarreraDAO();
    return $dao->eliminarCarrera($idCarrera);
}

function obtenerCarreraPorIdMDB($idCarrera) {
    $dao = new CarreraDAO();
    return $dao->obtenerCarreraPorId($idCarrera);
}

function obtenerTodasLasCarrerasMDB() {
    $dao = new CarreraDAO();
    $carreras = $dao->listarCarreras();
    $result = array();
    foreach ($carreras as $carrera) {
        $categoriaNombre = method_exists($carrera->getCategoria(), 'getNombre') ? $carrera->getCategoria()->getNombre() : null;
        $eventoNombre = method_exists($carrera->getEvento(), 'getNombreEvento') ? $carrera->getEvento()->getNombreEvento() : null;
        $fecha = method_exists($carrera->getEvento(), 'getFechaEvento') ? $carrera->getEvento()->getFechaEvento() : null;
        $descripcion = method_exists($carrera->getEvento(), 'getDescripcionEvento') ? $carrera->getEvento()->getDescripcionEvento() : null;
        $result[] = array(
            'idCarrera' => $carrera->getIdCarrera(),
            'nombre' => $eventoNombre,
            'descripcion' => $descripcion,
            'fecha' => $fecha,
            'distancia' => $carrera->getDistancia(),
            'categoria' => $categoriaNombre,
        );
    }
    return $result;
}
