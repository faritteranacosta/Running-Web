<?php
require_once __DIR__ . '/../entidad/Carrera.php';
require_once __DIR__ . '/DataSource.php';

class CarreraDAO {
    private $dataSource;

    public function __construct() {
        $this->dataSource = new DataSource();
    }

    public function agregarCarrera(Carrera $carrera) {
        $sql = "INSERT INTO carrera (distancia, id_evento, tipo_carrera_id, id_categoria, id_ruta) VALUES (?, ?, ?, ?, ?)";
        $params = [
            $carrera->getDistancia(),
            $carrera->getEvento()->getIdEvento(),
            $carrera->getTipoCarrera()->getIdTipoCarrera(),
            $carrera->getCategoria()->getIdCategoria(),
            $carrera->getRuta()->getIdRuta()
        ];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }
    
   public function obtenerCarreraPorId($id_carrera) {

        $sql = "SELECT c.*, e.hora AS hora_evento, e.nombre AS evento_nombre, e.fecha AS evento_fecha,
                    e.descripcion AS evento_descripcion, e.direccion, cat.nombre AS nombre_categoria,
                    r.id AS id_ruta, r.nombre AS nombre_ruta, r.puntos, r.distancia AS distancia_ruta,
                    r.fecha_creacion AS fecha_creacion_ruta
                FROM carrera c
                LEFT JOIN evento e ON c.id_evento = e.id_evento
                LEFT JOIN categoria cat ON c.id_categoria = cat.id_categoria
                LEFT JOIN rutas r ON c.id_ruta = r.id
                WHERE c.id_carrera = ?";
        
        $params = [$id_carrera];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        
        if (count($result) > 0) {
            $row = $result[0];
            
            // Crear instancia de Evento
            $evento = new Evento();
            $evento->setIdEvento($row['id_evento']);
            $evento->setNombreEvento($row['evento_nombre']);
            $evento->setFechaEvento($row['evento_fecha']);
            $evento->setDescripcionEvento($row['evento_descripcion']);
            $evento->setHoraEvento($row['hora_evento']);
            $evento->setDireccion($row['direccion']);
            
            // Crear instancia de Patrocinador
            $patrocinador = new Patrocinador();
            $patrocinador->setNombre($row['patrocinador_nombre']);
            $evento->setPatrocinador($patrocinador);
            
            // Crear instancia de TipoCarrera
            $tipoCarrera = new TipoCarrera();
            $tipoCarrera->setIdTipoCarrera($row['tipo_carrera_id']);
            
            // Crear instancia de Categoria
            $categoria = new Categoria();
            $categoria->setIdCategoria($row['id_categoria']);
            $categoria->setNombre($row['nombre_categoria']);
            
            // Crear instancia de Ruta con la nueva estructura
            $ruta = new Ruta();
            $ruta->setIdRuta($row['id_ruta']);
            $ruta->setNombreRuta($row['nombre_ruta']);
            $ruta->setPuntosRuta(json_decode($row['puntos'], true)); // Decodificar el JSON
            $ruta->setDistanciaRuta($row['distancia_ruta']);
            $ruta->setFechaCreacionRuta($row['fecha_creacion_ruta']);
            
            // Crear instancia de Carrera
            $carrera = new Carrera($row['distancia'], $evento, $tipoCarrera, $categoria, $ruta);
            
            if (method_exists($carrera, 'setIdCarrera')) {
                $carrera->setIdCarrera($row['id_carrera']);
            }
            
            return $carrera;
        }
        
        return null;
    }

    public function listarCarreras() {
        $sql = "SELECT c.id_carrera, e.nombre AS nombre_evento, e.fecha AS fecha_evento, e.descripcion AS descripcion_evento, c.distancia, cat.nombre AS nombre_categoria, c.id_ruta
            FROM carrera c
            LEFT JOIN evento e ON c.id_evento = e.id_evento
            LEFT JOIN categoria cat ON c.id_categoria = cat.id_categoria";
        $result = $this->dataSource->ejecutarConsulta($sql);
        $carreras = array();
        foreach ($result as $row) {
            $carreras[] = array(
                'idCarrera' => $row['id_carrera'],
                'nombre' => $row['nombre_evento'],
                'descripcion' => $row['descripcion_evento'],
                'fecha' => $row['fecha_evento'],
                'distancia' => $row['distancia'],
                'categoria' => $row['nombre_categoria'],
                'ruta' => $row['id_ruta']
            );
        }
        return $carreras;
    }

    public function actualizarCarrera(Carrera $carrera) {
        $sql = "UPDATE carrera SET distancia = ?, id_evento = ?, tipo_carrera_id = ?, id_categoria = ?, id_ruta = ? WHERE id_carrera = ?";
        $params = [
            $carrera->getDistancia(),
            $carrera->getEvento()->getIdEvento(),
            $carrera->getTipoCarrera(),
            $carrera->getCategoria(),
            $carrera->getRuta(),
            $carrera->getIdCarrera()
        ];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function eliminarCarrera($id_carrera) {
        $sql = "DELETE FROM carrera WHERE id_carrera = ?";
        $params = [$id_carrera];
        return $this->dataSource->ejecutarActualizacion($sql, $params);
    }

    public function obtenerIdCarreraPorIdEvento($id_evento) {
        $sql = "SELECT id_carrera FROM carrera WHERE id_evento = ?";
        $params = [$id_evento];
        $result = $this->dataSource->ejecutarConsulta($sql, $params);
        if (count($result) > 0) {
            return $result[0]['id_carrera'];
        }
      return null; // No es una carrera o no existe
    }


    //agregar una participacion a un evento
//     public function agregarParticipacion($id_usuario, $id_evento) {
//     $sql = "INSERT INTO participacion_evento (usuario_id, evento_id) VALUES (?, ?)";
//     $params = [$id_usuario, $id_evento];
//     return $this->dataSource->ejecutarActualizacion($sql, $params);
// }



}
?>