<?php
require_once __DIR__ . '/../model/dao/EventoDAO.php';
require_once __DIR__ . '/../model/entidad/Evento.php';

class EventController
{
    private $eventoDAO;

    public function __construct()
    {
        $this->eventoDAO = new EventoDAO();
    }

    public function agregar($nombre, $tipo, $fecha, $hora, $descripcion, $idPatrocinador, $direccion)
    {
        $evento = new Evento($nombre, $tipo, $fecha, $hora, $descripcion, $idPatrocinador, $direccion);
        if (is_numeric($direccion)) {
            $evento->setUbicacionId((int) $direccion);
        } else {
            $evento->setDireccion($direccion);
        }
        return $this->eventoDAO->insertarEvento($evento);
    }

    public function obtenerPorId($idEvento) { return $this->eventoDAO->obtenerEventoPorId($idEvento); }
    public function listar() { return $this->eventoDAO->obtenerTodosLosEventos(); }
    public function index()
    {
        return array_map(function ($evento) {
            return [
                'id' => $evento->getIdEvento(),
                'nombre' => $evento->getNombreEvento(),
                'fecha' => $evento->getFechaEvento(),
                'direccion' => $evento->getDireccion(),
                'hora' => $evento->getHoraEvento(),
                'tipo' => $evento->getTipoEvento(),
                'id_patrocinador' => $evento->getIdPatrocinador(),
                'descripcion' => $evento->getDescripcionEvento()
            ];
        }, $this->listar());
    }
    public function actualizar($evento) { return $this->eventoDAO->actualizarEvento($evento); }
    public function eliminar($idEvento) { return $this->eventoDAO->eliminarEvento($idEvento); }
}
