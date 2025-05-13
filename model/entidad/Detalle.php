//Clase para los detalles de las carreras, intentar obtener de otras tablas


<?php  require_once __DIR__ . '/Conexion.php'; 

class Detalle {
    
    
    private $nonmbre_evento; //obtenido de la tabla evento
    private $patrocinador;//obtenido de la tabla evento mediante idpatrocinador para buscar el nombre en la tabla patrocinador
    private $descripcion_evento; //obtenido de la tabla evento
    private $fecha_evento; //obtenido de la tabla evento
    private $hora_evento; //obtenido de la tabla evento
    private $distancia; //obtener el id del evento actual en la tabla carrera
    private $categoria; ///obtenido el id categoria de la tabla carrera y sacarlo de dicha tabla
    private $id_evento;

    public function __construct($nonmbre_evento = null, $patrocinador = null, $descripcion_evento = null, $fecha_evento = null, $hora_evento = null, $distancia = null, $categoria = null, $id_evento = null) {
        $this->nonmbre_evento = $nonmbre_evento;
        $this->patrocinador = $patrocinador;
        $this->descripcion_evento = $descripcion_evento;
        $this->fecha_evento = $fecha_evento;
        $this->hora_evento = $hora_evento;
        $this->distancia = $distancia;
        $this->categoria = $categoria;
        $this->id_evento = $id_evento;
    }

    public function getNombreEvento() {
        return $this->nonmbre_evento;
    }
    public function getPatrocinador() {
        return $this->patrocinador;
    }
    public function getDescripcionEvento() {
        return $this->descripcion_evento;
    }
    public function getFechaEvento() {
        return $this->fecha_evento;
    }
    public function getHoraEvento() {
        return $this->hora_evento;
    }
    public function getDistancia() {
        return $this->distancia;
    }
    public function getCategoria() {
        return $this->categoria;
    }
    public function getIdEvento() {
        return $this->id_evento;
    }