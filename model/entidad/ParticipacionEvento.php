<?php
require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/Evento.php';


class ParticipacionEvento {
    private $id_participacion;
    private $usuario;
    private $evento;

    public function __construct(Usuario $usuario = null, Evento $evento = null) {
        $this->usuario = $usuario;
        $this->evento = $evento;
    }

    public function getIdParticipacion() {
        return $this->id_participacion;
    }
    public function setIdParticipacion($id_participacion) {
        $this->id_participacion = $id_participacion;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getEvento() {
        return $this->evento;
    }


}
