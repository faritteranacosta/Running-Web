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
    $carrera = $dao->obtenerCarreraPorId($idCarrera);
    if (!$carrera) return null;
    $categoriaNombre = method_exists($carrera->getCategoria(), 'getNombre') ? $carrera->getCategoria()->getNombre() : null;
    $evento = $carrera->getEvento();
    $eventoNombre = method_exists($evento, 'getNombreEvento') ? $evento->getNombreEvento() : null;
    $fecha = method_exists($evento, 'getFechaEvento') ? $evento->getFechaEvento() : null;
    $descripcion = method_exists($evento, 'getDescripcionEvento') ? $evento->getDescripcionEvento() : null;
    $hora = method_exists($evento, 'getHoraEvento') ? $evento->getHoraEvento() : null;
    // Punto de encuentro: puedes usar ubicacion->getDescripcion() si existe
    $punto_encuentro = null;
    if (method_exists($evento, 'getUbicacion') && $evento->getUbicacion()) {
        $ubicacion = $evento->getUbicacion();
        if (method_exists($ubicacion, 'getDescripcion')) {
            $punto_encuentro = $ubicacion->getDescripcion();
        }
    }
    return array(
        'idCarrera' => $carrera->getIdCarrera(),
        'nombre' => $eventoNombre,
        'descripcion' => $descripcion,
        'fecha' => $fecha,
        'hora' => $hora,
        'distancia' => $carrera->getDistancia(),
        'categoria' => $categoriaNombre,
        'punto_encuentro' => $punto_encuentro
    );
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
