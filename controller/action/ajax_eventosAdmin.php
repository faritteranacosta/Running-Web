<?php

// Los errores se registran en el log del servidor, nunca se muestran al cliente.
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);

session_start();
require_once(__DIR__ . '/../mdb/mdbEvento.php');

header('Content-Type: application/json; charset=utf-8');

try {
    // Manejo de acciones POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $action = $input['action'] ?? $_POST['action'] ?? null;

        if ($action === 'agregar') {
            $nombre = $input['nombre'] ?? null;
            $tipo = $input['tipo'] ?? null;
            $fecha = $input['fecha'] ?? null;
            $hora = $input['hora'] ?? null;
            $descripcion = $input['descripcion'] ?? null;
            $id_patrocinador = $input['id_patrocinador'] ?? null;
            $ubicacion_id = $input['ubicacion_id'] ?? null;

            $missing_fields = [];
            if (empty($nombre)) $missing_fields[] = 'nombre';
            if (empty($tipo)) $missing_fields[] = 'tipo';
            if (empty($fecha)) $missing_fields[] = 'fecha';
            if (empty($hora)) $missing_fields[] = 'hora';
            if (empty($descripcion)) $missing_fields[] = 'descripcion';
            // Para id_patrocinador y ubicacion_id, empty() con 0 o "0" puede dar
            // falso positivo, por eso se valida con isset()/=== por separado.
            if (!isset($id_patrocinador) || $id_patrocinador === null || $id_patrocinador === '') $missing_fields[] = 'id_patrocinador';
            if (!isset($ubicacion_id) || $ubicacion_id === null || $ubicacion_id === '') $missing_fields[] = 'ubicacion_id';

            if (!empty($missing_fields)) {
                echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios: ' . implode(', ', $missing_fields)]);
                exit;
            }

            if (agregarEvento($nombre, $tipo, $fecha, $hora, $descripcion, $id_patrocinador, $ubicacion_id)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al agregar evento']);
            }
            exit;
        }

        if ($action === 'actualizar') {
            $id_evento = $input['id_evento'] ?? null;
            $nombre = $input['nombre'] ?? null;
            $tipo = $input['tipo'] ?? null;
            $fecha = $input['fecha'] ?? null;
            $hora = $input['hora'] ?? null;
            $descripcion = $input['descripcion'] ?? null;
            $patrocinador = $input['patrocinador'] ?? null;
            $ubicacion_id = $input['ubicacion_id'] ?? null;

            if (!$id_evento || !$nombre || !$tipo || !$fecha || !$descripcion || !$patrocinador || !$ubicacion_id) {
                echo json_encode(['success' => false, 'error' => 'Faltan datos para actualizar el evento']);
                exit;
            }

            $evento = new Evento($nombre, $tipo, $fecha, $hora, $descripcion, $patrocinador);
            $evento->setIdEvento($id_evento);
            $evento->setUbicacionId((int)$ubicacion_id);

            if (actualizarEvento($evento)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al actualizar el evento']);
            }
            exit;
        }

        if ($action === 'eliminar') {
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

        echo json_encode(['success' => false, 'error' => 'Acción no reconocida']);
        exit;
    }

    // Manejo de acciones GET para obtener un evento por ID
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? null) === 'obtenerPorId' && isset($_GET['id'])) {
        $evento = obtenerEventoPorId($_GET['id']);
        if ($evento) {
            $data = [
                'id' => $evento->getIdEvento(),
                'nombre' => $evento->getNombreEvento(),
                'tipo' => $evento->getTipoEvento(),
                'fecha' => $evento->getFechaEvento(),
                'hora' => $evento->getHoraEvento(),
                'descripcion' => $evento->getDescripcionEvento(),
                'ubicacion_id' => $evento->getUbicacionId(),
                'id_patrocinador' => $evento->getidPatrocinador()
            ];
            echo json_encode(['success' => true, 'evento' => $data]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Evento no encontrado']);
        }
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Solicitud no válida']);
} catch (PDOException $e) {
    error_log('[ajax_eventosAdmin] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Ocurrió un error al procesar el evento. Inténtalo de nuevo más tarde.'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
