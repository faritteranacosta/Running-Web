<?php
require_once __DIR__ . '/../../model/dao/EventoDAO.php';

function agregarEvento($nombre, $tipo, $fecha, $hora, $descripcion, $id_patrocinador, $direcion) {
    $eventoDAO = new EventoDAO();
    
    // Crear una Ubicación básica con solo el ID (si no necesitas más datos)
    
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