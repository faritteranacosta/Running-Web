<?php
require_once __DIR__ . '/../../model/dao/ParticipacionEventoDAO.php';

function agregarParticipacionMDB($id_usuario, $id_evento) {
    $dao = new ParticipacionEventoDAO();
    return $dao->agregarParticipacion($id_usuario, $id_evento);
}

function obtenerParticipacionesPorUsuarioMDB($id_usuario) {
    $dao = new ParticipacionEventoDAO();
    return $dao->obtenerParticipacionesPorUsuario($id_usuario);
}

function existeParticipacionMDB($id_usuario, $id_evento) {
    $dao = new ParticipacionEventoDAO();
    return $dao->existeParticipacion($id_usuario, $id_evento);
}

function eliminarParticipacionMDB($id_participacion) {
    $dao = new ParticipacionEventoDAO();
    return $dao->eliminarParticipacion($id_participacion);
}
