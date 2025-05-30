<?php
require_once __DIR__ . '/../mdb/mdbCarrera.php';
require_once __DIR__ . '/../mdb/mdbTipoCarrera.php';
require_once __DIR__ . '/../mdb/mdbEvento.php';
require_once __DIR__ . '/../mdb/mdbCategoria.php';
require_once __DIR__ . '/../mdb/mdbRuta.php';


header('Content-Type: application/json; charset=utf-8');

// Endpoint: Obtener carrera por ID
if (isset($_GET['action']) && $_GET['action'] === 'obtener' && isset($_GET['idCarrera'])) {
    $result = obtenerCarreraPorIdMDB($_GET['idCarrera']);
    if(ob_get_length()) ob_clean();
    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

// Endpoint: Insertar nueva carrera
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'crear') {
    try {
        $distancia = $_POST['distancia'] ?? null;
        $idTipoCarrera = $_POST['idTipoCarrera'] ?? null;
        $idCategoria = $_POST['idCategoria'] ?? null;
        $Idruta = $_POST['idRuta'] ?? null;


        // Validar campos obligatorios
        if (!$distancia || !$idTipoCarrera || !$idCategoria || !$Idruta) {
            throw new Exception("Faltan datos obligatorios para crear la carrera");
        }

        $tipoCarrera = obtenerTipoCarreraPorId($idTipoCarrera);
        $ruta = obtenerRuta($Idruta);
        $categoria = obtenerCategoriaPorId($idCategoria);

        $idEvento = agregarEvento(
            $_POST['nombre'] ?? '',
            'carrera',
            $_POST['fecha'] ?? '',
            $_POST['hora'] ?? '',
            $_POST['descripcion'] ?? '',
            null,
            $_POST['direccion'] ?? ''
        );

        $evento = obtenerEventoPorId($idEvento);
        if (!$evento) {
            throw new Exception("Evento no encontrado");
        }
        
        if (!$tipoCarrera) {
            throw new Exception("Tipo de carrera no encontrado");
        }

        $resultado = insertarCarreraMDB($distancia, $evento, $tipoCarrera, $categoria, $ruta);
        
        if(ob_get_length()) ob_clean();
        echo json_encode([
            'success' => true,
            'mensaje' => 'Carrera creada exitosamente',
            'id' => $resultado // Si devuelve ID o algún resultado
        ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => $e->getMessage(),
            'success' => false
        ]);
        exit;
    }
}

// Endpoint: Obtener todas las carreras (por defecto)
try {
    $carreras = obtenerTodasLasCarrerasMDB();
    if ($carreras === null || $carreras === false) {
        throw new Exception("Error al obtener carreras de la base de datos");
    }
    if(ob_get_length()) ob_clean();
    echo json_encode($carreras, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'success' => false
    ]);
}
?>
