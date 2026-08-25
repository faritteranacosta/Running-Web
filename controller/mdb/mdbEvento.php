<?php
require_once __DIR__ . '/../../model/dao/EventoDAO.php';

function agregarEvento($nombre, $tipo, $fecha, $hora, $descripcion, $id_patrocinador, $direcion) {
    $eventoDAO = new EventoDAO();

    // Crear el Evento con los IDs recibidos
    $evento = new Evento(
        $nombre,
        $tipo,
        $fecha,
        $hora,
        $descripcion,
        $id_patrocinador,  // Asegúrate de que la clase Evento use este ID directamente
        $direcion         // una ubicacion en string
    );

    // El panel de admin manda un ubicacion_id numérico de una ubicación ya
    // existente; el formulario de "crear carrera" manda texto libre de
    // dirección. Se distinguen para no tratar un ID como si fuera texto.
    if (is_numeric($direcion)) {
        $evento->setUbicacionId((int)$direcion);
    } else {
        $evento->setDireccion($direcion);
    }

    return $eventoDAO->insertarEvento($evento);
}

function obtenerEventoPorId($id_evento) {
    $eventoDAO = new EventoDAO();
    return $eventoDAO->obtenerEventoPorId($id_evento);
}

function listarEventos() {
    $eventoDAO = new EventoDAO();
    return $eventoDAO->obtenerTodosLosEventos();
}

function actualizarEvento($evento) {
    $eventoDAO = new EventoDAO();
    return $eventoDAO->actualizarEvento($evento);
}

function eliminarEvento($id_evento) {
    $eventoDAO = new EventoDAO();
    return $eventoDAO->eliminarEvento($id_evento);
}