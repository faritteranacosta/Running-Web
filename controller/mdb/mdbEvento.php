<?php
require_once __DIR__ . '/../../model/dao/EventoDAO.php';

function agregarEvento($nombre, $fecha, $ubicacion, $descripcion) {
    $eventoDAO = new EventoDAO();
    $evento = new Evento($nombre, $fecha, $ubicacion, $descripcion);
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

function actualizarEvento($id_evento, $nombre, $fecha, $ubicacion, $descripcion) {
    $eventoDAO = new EventoDAO();
    $evento = new Evento($nombre, $fecha, $ubicacion, $descripcion);
    $evento->setIdEvento($id_evento);
    return $eventoDAO->actualizarEvento($evento);
}

function eliminarEvento($id_evento) {
    $eventoDAO = new EventoDAO();
    return $eventoDAO->eliminarEvento($id_evento);
}