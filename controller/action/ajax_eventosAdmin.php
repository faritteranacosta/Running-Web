<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
require_once(__DIR__ . '/../mdb/mdbEvento.php');


// Configuración de encabezados primero
header('Content-Type: application/json; charset=utf-8');




// Manejo de acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;


        // --- INICIO DE CÓDIGO DE DEPURACIÓN ADICIONAL ---
    error_log("------------------------------------------");
    error_log("Datos POST recibidos (raw):");
    error_log(print_r($input, true));
    error_log("------------------------------------------");
    // --- FIN DE CÓDIGO DE DEPURACIÓN ADICIONAL ---
    
   $action = $_POST['action'] ?? $input['action'] ?? null;
if ($action === 'agregar') {
    $nombre = $input['nombre'] ?? $_POST['nombre'] ?? null;
    $tipo = $input['tipo'] ?? $_POST['tipo'] ?? null;
    $fecha = $input['fecha'] ?? $_POST['fecha'] ?? null;
    $hora = $input['hora'] ?? $_POST['hora'] ?? null;

// --- INICIO DE DEPURACIÓN CRÍTICA PARA 'HORA' ---
error_log("--- Depuración de HORA ---");
error_log("Valor bruto de \$input['hora']: " . (isset($input['hora']) ? "'" . (string)$input['hora'] . "'" : "NO SET"));
error_log("Valor bruto de \$_POST['hora']: " . (isset($_POST['hora']) ? "'" . (string)$_POST['hora'] . "'" : "NO SET"));
error_log("Valor final de \$hora: '" . (string)$hora . "'");
error_log("Tipo de \$hora: " . gettype($hora));
error_log("is_null(\$hora): " . (is_null($hora) ? 'true' : 'false'));
error_log("empty(\$hora): " . (empty($hora) ? 'true' : 'false'));
error_log("--- Fin Depuración de HORA ---");
// --- FIN DE DEPURACIÓN CRÍTICA PARA 'HORA' ---

    $descripcion = $input['descripcion'] ?? $_POST['descripcion'] ?? null;
    $id_patrocinador = $input['id_patrocinador'] ?? $_POST['id_patrocinador'] ?? null;
    $ubicacion_id = $input['ubicacion_id'] ?? $_POST['ubicacion_id'] ?? null;



        // --- CÓDIGO DE DEPURACIÓN PARA IDENTIFICAR EL CAMPO FALTANTE ---
        $missing_fields = [];
        if (empty($nombre)) $missing_fields[] = 'nombre';
        if (empty($tipo)) $missing_fields[] = 'tipo';
        if (empty($fecha)) $missing_fields[] = 'fecha';
        if (empty($hora)) $missing_fields[] = 'hora';
        if (empty($descripcion)) $missing_fields[] = 'descripcion';
        // Para id_patrocinador y ubicacion_id, es crucial que sean números.
        // empty() con 0 o "0" puede dar falso positivo. Usamos validación más estricta.
        if (!isset($id_patrocinador) || $id_patrocinador === null || $id_patrocinador === '') $missing_fields[] = 'id_patrocinador';
        if (!isset($ubicacion_id) || $ubicacion_id === null || $ubicacion_id === '') $missing_fields[] = 'ubicacion_id';

        if (!empty($missing_fields)) {
            $error_message = 'Faltan datos obligatorios: ' . implode(', ', $missing_fields);
            error_log("Error: " . $error_message); // Esto lo verás en los logs del servidor
            echo json_encode(['success' => false, 'error' => $error_message]);
            exit;
        }
        // --- FIN CÓDIGO DE DEPURACIÓN PARA IDENTIFICAR EL CAMPO FALTANTE ---



    if (!$nombre || !$tipo || !$fecha || !$hora || !$descripcion || !$id_patrocinador || !$ubicacion_id) {
        echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios']);
        exit;
    }

    if (agregarEvento($nombre, $tipo, $fecha, $hora, $descripcion, $id_patrocinador, $ubicacion_id)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al agregar evento']);
    }
    exit;
}

    // Actualizar un evento
    if (isset($input['action']) && $input['action'] === 'actualizar') {
        $id_evento = $input['id_evento'] ?? null;
        $nombre = $input['nombre'] ?? null;
        $tipo = $input['tipo'] ?? null;
        $fecha = $input['fecha'] ?? null;
        $descripcion = $input['descripcion'] ?? null;
        $patrocinador = $input['patrocinador'] ?? null;
        $ubicacion_id = $input['ubicacion_id'] ?? null;

        if (!$id_evento || !$nombre || !$tipo || !$fecha || !$descripcion || !$patrocinador || !$ubicacion_id) {
            echo json_encode(['success' => false, 'error' => 'Faltan datos para actualizar el evento']);
            exit;
        }

        $evento = new Evento($nombre, $tipo, $fecha, null, $descripcion, $patrocinador, new Ubicacion(null, null, null, new Ciudad(null)));
        $evento->setIdEvento($id_evento);
        $evento->getUbicacion()->setIdUbicacion($ubicacion_id);

        if (actualizarEvento($evento)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar el evento']);
        }
        exit;
    }

    // Eliminar un evento
    if (isset($input['action']) && $input['action'] === 'eliminar') {
        $id_evento = $input['id_evento'] ?? null;

        if (!$id_evento) {
            echo json_encode(['success' => false, 'error' => 'ID de evento no recibido']);
            exit;
        }

        if (eliminarEvento($id_evento)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar el evento']);
        }
        exit;
    }
}

// Manejo de acciones GET para obtener un evento por ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'obtenerPorId' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $evento = obtenerEventoPorId($id);
    if ($evento) {
        // Extraer los datos relevantes para el formulario de edición
        $data = [
            'id' => $evento->getIdEvento(),
            'nombre' => $evento->getNombreEvento(),
            'tipo' => $evento->getTipoEvento(),
            'fecha' => $evento->getFechaEvento(),
            'hora' => $evento->getHoraEvento(),
            'descripcion' => $evento->getDescripcionEvento(),
            'ubicacion_id' => is_object($evento->getUbicacion()) ? $evento->getUbicacion()->getIdUbicacion() : $evento->getUbicacion(),
            'id_patrocinador' => is_object($evento->getPatrocinador()) ? $evento->getPatrocinador()->getIdPatrocinador() : $evento->getPatrocinador()
        ];
        echo json_encode(['success' => true, 'evento' => $data]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Evento no encontrado']);
    }
    exit;
}
