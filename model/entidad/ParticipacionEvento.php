<?php
require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/Evento.php';


class ParticipacionEvento {
    private $usuario;
    private $evento;

    public function __construct(Usuario $usuario = null, Evento $evento = null) {
        $this->usuario = $usuario;
        $this->evento = $evento;
    }

    public function getUsuario() {
        return $this->usuario;
    }
    
    public function getEvento() {
        return $this->evento;
    }


}
