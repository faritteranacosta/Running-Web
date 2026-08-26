<?php
require_once __DIR__ . '/../model/dao/CarreraDAO.php';
require_once __DIR__ . '/../model/entidad/Carrera.php';
require_once __DIR__ . '/../model/entidad/Evento.php';
require_once __DIR__ . '/../model/entidad/TipoCarrera.php';
require_once __DIR__ . '/../model/entidad/Categoria.php';
require_once __DIR__ . '/../model/entidad/Ruta.php';

class RaceController
{
    private $carreraDAO;

    public function __construct()
    {
        $this->carreraDAO = new CarreraDAO();
    }

    public function insertar($distancia, $evento, $tipoCarrera, $categoria, $ruta)
    {
        return $this->carreraDAO->agregarCarrera(new Carrera($distancia, $evento, $tipoCarrera, $categoria, $ruta));
    }

    public function actualizar($idCarrera, $distancia, $evento, $tipoCarrera, $categoria, $ruta)
    {
        $carrera = new Carrera($distancia, new Evento($evento), new TipoCarrera($tipoCarrera), new Categoria($categoria), new Ruta($ruta));
        $carrera->setIdCarrera($idCarrera);
        return $this->carreraDAO->actualizarCarrera($carrera);
    }

    public function eliminar($idCarrera) { return $this->carreraDAO->eliminarCarrera($idCarrera); }
    public function listar() { return $this->carreraDAO->listarCarreras(); }
    public function index() { return $this->listar(); }
    public function obtenerPorId($idCarrera) { return $this->carreraDAO->obtenerCarreraPorId($idCarrera); }
    public function obtenerIdPorEvento($idEvento) { return $this->carreraDAO->obtenerIdCarreraPorIdEvento($idEvento); }

    public function serializar($idCarrera)
    {
        $carrera = $this->obtenerPorId($idCarrera);
        if (!$carrera) return null;

        $categoria = $carrera->getCategoria();
        $categoriaNombre = null;
        if ($categoria && is_object($categoria) && method_exists($categoria, 'getNombre')) {
            $categoriaNombre = $categoria->getNombre();
        }

        $evento = $carrera->getEvento();
        return [
            'id' => $carrera->getIdCarrera(),
            'evento' => $evento->getIdEvento(),
            'nombre' => $evento->getNombreEvento(),
            'descripcion' => $evento->getDescripcionEvento(),
            'fecha' => $evento->getFechaEvento(),
            'hora' => $evento->getHoraEvento(),
            'distancia' => $carrera->getDistancia(),
            'categoria' => $categoriaNombre,
            'patrocinador' => $evento->getPatrocinador() && method_exists($evento->getPatrocinador(), 'getNombre') ? $evento->getPatrocinador()->getNombre() : null,
            'direccion' => $evento->getDireccion()
        ];
    }
}
