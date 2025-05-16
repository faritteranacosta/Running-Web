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
    // Convertir objetos Carrera a arrays simples para JSON
    $result = array();
    foreach ($carreras as $carrera) {
        $result[] = array(
            'idCarrera' => $carrera->getIdCarrera(),
            'nombre' => 'Carrera ' . $carrera->getIdCarrera(), // Puedes ajustar esto si tienes un campo nombre
            'descripcion' => '', // Ajusta si tienes descripción
            'fecha' => '', // Ajusta si tienes fecha
            'distancia' => $carrera->getDistancia(),
            'evento' => $carrera->getEvento() ? $carrera->getEvento()->getIdEvento() : null,
            'tipoCarrera' => $carrera->getTipoCarrera() ? $carrera->getTipoCarrera()->getIdTipoCarrera() : null,
            'categoria' => $carrera->getCategoria() ? $carrera->getCategoria()->getIdCategoria() : null,
            'ruta' => $carrera->getRuta() ? $carrera->getRuta()->getIdRuta() : null
        );
    }
    return $result;
}
