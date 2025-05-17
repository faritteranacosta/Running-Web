<?php
require_once __DIR__ . '/../../model/dao/CarreraDAO.php';
require_once __DIR__ . '/../../model/entidad/Carrera.php';
require_once __DIR__ . '/../../model/entidad/Evento.php';
require_once __DIR__ . '/../../model/entidad/Tipo_Carrera.php';
require_once __DIR__ . '/../../model/entidad/Categoria.php';
require_once __DIR__ . '/../../model/entidad/Ruta.php';

function insertarCarreraMDB($distancia, $evento, $tipoCarrera, $categoria, $ruta) {
    $carrera = new Carrera($distancia, new Evento($evento), new TipoCarrera($tipoCarrera), new Categoria($categoria), new Ruta($ruta));
    $dao = new CarreraDAO();
    return $dao->agregarCarrera($carrera);
}

function actualizarCarreraMDB($idCarrera, $distancia, $evento, $tipoCarrera, $categoria, $ruta) {
    $carrera = new Carrera($distancia, new Evento($evento), new TipoCarrera($tipoCarrera), new Categoria($categoria), new Ruta($ruta));
    $carrera->id_carrera = $idCarrera;
    $dao = new CarreraDAO();
    return $dao->actualizarCarrera($carrera);
}

function eliminarCarreraMDB($idCarrera) {
    $dao = new CarreraDAO();
    return $dao->eliminarCarrera($idCarrera);
}

function obtenerTodasLasCarrerasMDB() {
    $dao = new CarreraDAO();
    return $dao->listarCarreras();
}

function obtenerCarreraPorIdMDB($idCarrera) {
    $dao = new CarreraDAO();
    $carrera = $dao->obtenerCarreraPorId($idCarrera);
    if (!$carrera) return null;
    // Obtener el nombre de la categoría de forma robusta
    $categoria = $carrera->getCategoria();
    $categoriaNombre = null;
    if ($categoria) {
        if (is_object($categoria) && method_exists($categoria, 'getNombre')) {
            $categoriaNombre = $categoria->getNombre();
        } elseif (is_array($categoria) && isset($categoria['nombre'])) {
            $categoriaNombre = $categoria['nombre'];
        }
    }
    $eventoNombre = method_exists($carrera->getEvento(), 'getNombreEvento') ? $carrera->getEvento()->getNombreEvento() : null;
    $fecha = method_exists($carrera->getEvento(), 'getFechaEvento') ? $carrera->getEvento()->getFechaEvento() : null;
    $hora = method_exists($carrera->getEvento(), 'getHoraEvento') ? $carrera->getEvento()->getHoraEvento() : null;
    $descripcion = method_exists($carrera->getEvento(), 'getDescripcionEvento') ? $carrera->getEvento()->getDescripcionEvento() : null;
    $ubicacion = $carrera->getEvento() && method_exists($carrera->getEvento(), 'getUbicacion') ? $carrera->getEvento()->getUbicacion() : null;
    if ($ubicacion && method_exists($ubicacion, 'getDescripcion')) {
        $descripcion = $ubicacion->getDescripcion();
    } elseif (is_array($ubicacion) && isset($ubicacion['descripcion'])) {
        $descripcion = $ubicacion['descripcion'];
    }
    return [
        'id' => $carrera->getIdCarrera(),
        'nombre' => $eventoNombre,
        'descripcion' => $descripcion,
        'fecha' => $fecha,
        'hora' => $hora,
        'distancia' => $carrera->getDistancia(),
        'categoria' => $categoriaNombre  //,
        //'patrocinador' => $patrocinador
    ];
}
?>