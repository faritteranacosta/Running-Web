<?php
require_once __DIR__ . '/../../model/dao/ParticipacionEventoDAO.php';

function agregarParticipacionMDB($id_usuario, $id_evento) {
    $dao = new ParticipacionEventoDAO();
    return $dao->agregarParticipacion($id_usuario, $id_evento);
}

function obtenerParticipacionPorId($id_participacion) {
    $dao = new ParticipacionEventoDAO();
    return $participacionEventoDAO->obtenerParticipacionPorId($id_participacion);
}


function eliminarParticipacion($id_participacion) {
    $dao = new ParticipacionEventoDAO();
    return $participacionEventoDAO->eliminarParticipacion($id_participacion);
}
