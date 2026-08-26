<?php
require_once __DIR__ . '/../model/dao/RutaDao.php';
require_once __DIR__ . '/../model/entidad/Ruta.php';

class RouteController
{
    private $rutaDAO;

    public function __construct()
    {
        $this->rutaDAO = new RutaDao();
    }

    public function guardar(Ruta $ruta) { return $this->rutaDAO->guardar($ruta); }
    public function obtener($id) { return $this->rutaDAO->obtenerRuta($id); }
    public function eliminar($id) { return $this->rutaDAO->eliminar($id); }

    public function calcularDistancia(array $puntos): float
    {
        $distancia = 0.0;
        $earthRadius = 6371;

        for ($i = 1; $i < count($puntos); $i++) {
            $lat1 = (float) $puntos[$i - 1][0];
            $lon1 = (float) $puntos[$i - 1][1];
            $lat2 = (float) $puntos[$i][0];
            $lon2 = (float) $puntos[$i][1];
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);
            $a = sin($dLat / 2) * sin($dLat / 2)
                + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
                * sin($dLon / 2) * sin($dLon / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distancia += $earthRadius * $c;
        }

        return round($distancia, 2);
    }
}
