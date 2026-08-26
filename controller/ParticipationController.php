<?php
require_once __DIR__ . '/../model/dao/ParticipacionEventoDAO.php';

class ParticipationController
{
    private $participacionDAO;

    public function __construct()
    {
        $this->participacionDAO = new ParticipacionEventoDAO();
    }

    public function agregar($idUsuario, $idEvento) { return $this->participacionDAO->agregarParticipacion($idUsuario, $idEvento); }
    public function obtenerPorUsuario($idUsuario) { return $this->participacionDAO->obtenerParticipacionesPorUsuario($idUsuario); }
    public function existe($idUsuario, $idEvento) { return $this->participacionDAO->existeParticipacion($idUsuario, $idEvento); }
    public function eliminar($idUsuario, $idEvento) { return $this->participacionDAO->eliminarParticipacion($idUsuario, $idEvento); }
}
