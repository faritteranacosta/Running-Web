<?php
require_once __DIR__ . '/../model/dao/TipoCarreraDAO.php';

class TypeRaceController
{
    private $tipoCarreraDAO;

    public function __construct()
    {
        $this->tipoCarreraDAO = new TipoCarreraDAO();
    }

    public function listar() { return $this->tipoCarreraDAO->listarTiposCarrera(); }
    public function obtenerPorId($idTipoCarrera) { return $this->tipoCarreraDAO->obtenerTipoCarreraPorId($idTipoCarrera); }
}
